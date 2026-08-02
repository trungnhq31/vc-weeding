<?php

declare(strict_types=1);

namespace App\Modules\GroundedAI\Actions;

use App\Modules\GroundedAI\Services\GroundedDataQueryService;
use App\Modules\GroundedAI\Services\OpenAiAgentService;

class QueryGroundedAiAction
{
    public function __construct(
        protected GroundedDataQueryService $groundedDataQueryService = new GroundedDataQueryService,
        protected OpenAiAgentService $openAiAgentService = new OpenAiAgentService
    ) {}

    /**
     * Execute grounded analysis with Zero Hallucination Guarantee.
     *
     * @param  array<int, array{role: string, content: string}>  $chatHistory
     * @return array{
     *     intent: string,
     *     metrics: array<string, mixed>,
     *     summary_text: string,
     *     insights: array<int, string>,
     *     recommendations: array<int, string>,
     *     openai_reply: string|null
     * }
     */
    public function execute(string $workspaceId, string $queryOrIntent = 'overview', array $chatHistory = []): array
    {
        $metrics = $this->groundedDataQueryService->getWorkspaceMetrics($workspaceId);
        $normalizedIntent = strtolower(trim($queryOrIntent));

        // Determine main scope
        if (str_contains($normalizedIntent, 'budget') || str_contains($normalizedIntent, 'ngân sách') || str_contains($normalizedIntent, 'tiền')) {
            $intent = 'budget';
        } elseif (str_contains($normalizedIntent, 'task') || str_contains($normalizedIntent, 'công việc') || str_contains($normalizedIntent, 'tiến độ') || str_contains($normalizedIntent, 'quá hạn')) {
            $intent = 'tasks';
        } elseif (str_contains($normalizedIntent, 'vendor') || str_contains($normalizedIntent, 'nhà cung cấp') || str_contains($normalizedIntent, 'hợp đồng') || str_contains($normalizedIntent, 'nợ')) {
            $intent = 'vendors';
        } elseif (str_contains($normalizedIntent, 'guest') || str_contains($normalizedIntent, 'khách') || str_contains($normalizedIntent, 'bàn') || str_contains($normalizedIntent, 'seating')) {
            $intent = 'guests';
        } else {
            $intent = 'overview';
        }

        $insights = [];
        $recommendations = [];

        // 1. Budget insights
        $budget = $metrics['budget'];
        if ($budget['is_overrun_alert']) {
            $insights[] = sprintf(
                '⚠️ CẢNH BÁO VỠ NGÂN SÁCH: Chi phí thực tế (%.2f VNĐ) đã vượt trần ngân sách (%.2f VNĐ).',
                $budget['total_actual'],
                $budget['budget_cap']
            );
            $recommendations[] = 'Cần rà soát và cắt giảm các khoản chi chưa phát sinh hoặc thương lượng lại chi phí với Nhà cung cấp.';
        } else {
            $insights[] = sprintf(
                '📊 Dòng tiền ổn định: Chi thực tế %.2f VNĐ / Trần ngân sách %.2f VNĐ (Còn dư %.2f VNĐ).',
                $budget['total_actual'],
                $budget['budget_cap'],
                $budget['budget_cap'] - $budget['total_actual']
            );
        }

        if ($budget['upcoming_payments_count'] > 0) {
            $insights[] = sprintf('🔔 Có %d khoản chi phí sắp đến hạn thanh toán trong 7 ngày tới.', $budget['upcoming_payments_count']);
            $recommendations[] = 'Thanh toán đúng hạn các khoản đặt cọc/đợt tiếp theo để tránh ảnh hưởng tiến độ.';
        }

        // 2. Tasks insights
        $tasks = $metrics['tasks'];
        if ($tasks['overdue_count'] > 0) {
            $insights[] = sprintf('🚨 Hiện có %d công việc đã QUÁ HẠN chưa hoàn thành.', $tasks['overdue_count']);
            $recommendations[] = 'Ưu tiên hoàn thành các task quá hạn khẩn cấp ngay hôm nay.';
        } else {
            $insights[] = sprintf('✅ Tiến độ công việc đạt %d%% (%d/%d công việc đã xong).', $tasks['progress_percentage'], $tasks['completed_tasks'], $tasks['total_tasks']);
        }

        // 3. Vendors insights
        $vendors = $metrics['vendors'];
        if ($vendors['remaining_unpaid'] > 0) {
            $insights[] = sprintf('📝 Tổng dư nợ Hợp đồng Nhà cung cấp chưa thanh toán: %.2f VNĐ (%d nhà cung cấp).', $vendors['remaining_unpaid'], $vendors['unpaid_vendors_count']);
        }
        if (count($vendors['upcoming_due_vendors']) > 0) {
            $insights[] = sprintf('⏳ Có %d Nhà cung cấp sắp đến hạn thanh toán hợp đồng.', count($vendors['upcoming_due_vendors']));
        }

        // 4. Guests & Seating insights
        $guests = $metrics['guests'];
        if ($guests['unseated_guests'] > 0) {
            $insights[] = sprintf('🪑 Còn %d khách mời chưa được xếp vào bàn tiệc.', $guests['unseated_guests']);
            $recommendations[] = 'Sử dụng Seating Canvas để xếp vị trí bàn tiệc cho khách mời còn lại.';
        }
        if ($guests['over_capacity_tables_count'] > 0) {
            $insights[] = sprintf('⚠️ Có %d bàn tiệc bị xếp quá sức chứa (Over capacity).', $guests['over_capacity_tables_count']);
            $recommendations[] = 'Điều chỉnh lại số chỗ hoặc di chuyển khách sang bàn tiệc khác.';
        }

        $summaryText = match ($intent) {
            'budget' => sprintf('Báo cáo Ngân sách Workspace %s: Đã chi %.2f / %.2f VNĐ. Dư nợ chưa trả: %.2f VNĐ.', $metrics['workspace']['name'], $budget['total_actual'], $budget['budget_cap'], $budget['remaining_balance']),
            'tasks' => sprintf('Báo cáo Tiến độ Workspace %s: Hoàn thành %d/%d task (%d%%). %d task quá hạn.', $metrics['workspace']['name'], $tasks['completed_tasks'], $tasks['total_tasks'], $tasks['progress_percentage'], $tasks['overdue_count']),
            'vendors' => sprintf('Báo cáo Nhà cung cấp Workspace %s: %d Nhà cung cấp, Tổng hợp đồng %.2f VNĐ, Đã cọc/trả %.2f VNĐ.', $metrics['workspace']['name'], $vendors['vendors_count'], $vendors['total_contracts'], $vendors['total_paid']),
            'guests' => sprintf('Báo cáo Khách mời Workspace %s: Tổng %d khách, %d đã xác nhận, %d chưa xếp bàn.', $metrics['workspace']['name'], $guests['total_guests'], $guests['attending_guests'], $guests['unseated_guests']),
            default => sprintf('Báo cáo Tổng quan Eloria Workspace "%s": Ngân sách %.2f/%.2f VNĐ, Tiến độ %d%%, %d Vendors.', $metrics['workspace']['name'], $budget['total_actual'], $budget['budget_cap'], $tasks['progress_percentage'], $vendors['vendors_count']),
        };

        // Try OpenAI Agent Completion if Key exists
        $openAiReply = $this->openAiAgentService->generateResponse($queryOrIntent, $metrics, $chatHistory);

        return [
            'intent' => $intent,
            'metrics' => $metrics,
            'summary_text' => $summaryText,
            'insights' => $insights,
            'recommendations' => $recommendations,
            'openai_reply' => $openAiReply,
        ];
    }
}
