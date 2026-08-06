<?php

declare(strict_types=1);

namespace App\Modules\GroundedAI\Services;

use App\Models\Guest;
use App\Modules\Budget\Services\CashFlowCalculatorService;
use App\Modules\Guest\Models\Table;
use App\Modules\Task\Models\Task;
use App\Modules\Vendor\Services\VendorCrmService;
use App\Modules\Workspace\Models\Workspace;

class GroundedDataQueryService
{
    public function __construct(
        protected CashFlowCalculatorService $cashFlowCalculator = new CashFlowCalculatorService,
        protected VendorCrmService $vendorCrmService = new VendorCrmService
    ) {}

    /**
     * Get all grounded data for a workspace with ZERO hallucination guarantee.
     *
     * @return array{
     *     workspace: array<string, mixed>,
     *     budget: array<string, mixed>,
     *     tasks: array<string, mixed>,
     *     vendors: array<string, mixed>,
     *     guests: array<string, mixed>
     * }
     */
    public function getWorkspaceMetrics(string $workspaceId): array
    {
        $workspace = Workspace::find($workspaceId);
        $workspaceData = [
            'id' => $workspaceId,
            'name' => $workspace?->name ?? 'Default Workspace',
            'budget_cap' => (float) ($workspace?->budget_cap ?? 0.00),
            'wedding_date' => $workspace?->wedding_date?->format('Y-m-d'),
        ];

        // 1. Budget Overview
        $budgetOverview = $this->cashFlowCalculator->calculateOverview($workspaceId);

        // 2. Tasks Overview
        $allTasks = Task::forWorkspace($workspaceId)->get();
        $totalTasks = $allTasks->count();
        $completedTasks = $allTasks->where('status', 'done')->count();
        $overdueTasks = $allTasks->filter(function (Task $task) {
            return $task->status !== 'done' && $task->due_date && $task->due_date->isPast();
        });

        $tasksOverview = [
            'total_tasks' => $totalTasks,
            'completed_tasks' => $completedTasks,
            'pending_tasks' => $totalTasks - $completedTasks,
            'progress_percentage' => $totalTasks > 0 ? (int) round(($completedTasks / $totalTasks) * 100) : 0,
            'overdue_count' => $overdueTasks->count(),
            'overdue_tasks' => $overdueTasks->values()->map(fn (Task $t) => [
                'id' => $t->id,
                'title' => $t->title,
                'category' => $t->category,
                'priority' => $t->priority,
                'due_date' => $t->due_date?->format('Y-m-d'),
            ])->toArray(),
        ];

        // 3. Vendors Overview
        $vendorSummary = $this->vendorCrmService->getSummary($workspaceId);

        // 4. Guests & Seating Overview
        $allGuests = Guest::forWorkspace($workspaceId)->get();
        $totalGuests = $allGuests->count();
        $attendingGuests = $allGuests->filter(fn (Guest $g) => in_array($g->rsvp_status?->value ?? $g->rsvp_status, ['attending', 'confirmed', 'yes']))->count();
        $unseatedGuests = $allGuests->whereNull('table_id')->count();

        $allTables = Table::forWorkspace($workspaceId)->get();
        $overCapacityTablesCount = $allTables->filter(fn (Table $table) => $table->is_over_capacity)->count();

        $guestsOverview = [
            'total_guests' => $totalGuests,
            'attending_guests' => $attendingGuests,
            'unseated_guests' => $unseatedGuests,
            'total_tables' => $allTables->count(),
            'over_capacity_tables_count' => $overCapacityTablesCount,
        ];

        $hcmBenchmarks = [
            'budget_distribution' => [
                'venue_and_catering_pct' => 50,
                'photo_video_makeup_pct' => 15,
                'decor_and_flowers_pct' => 10,
                'rings_and_invitations_pct' => 10,
                'reserve_and_afterparty_pct' => 15,
            ],
            'venue_tiers' => [
                'luxury' => 'Gem Center, White Palace Pham Van Dong, Park Hyatt (500M - 1.5B VND)',
                'premium' => 'White Palace Hoang Van Thu, Capella Gallery, Asiana Plaza (250M - 500M VND)',
                'cozy_outdoor' => 'Villa Song Saigon, Chloe Gallery (150M - 350M VND)',
                'saving' => 'Queen Plaza, Gala Center, Dong Phuong (80M - 200M VND)',
            ],
            'milestones' => [
                'morning' => '07:30 Lễ Gia Tiên & Rước Dâu',
                'welcome' => '11:00 / 17:30 Đón Khách & Check-in Photo Booth',
                'ceremony' => '12:00 / 18:30 Nghi Thức Cắt Bánh & Rót Champagne',
                'reception' => '12:30 / 19:00 Khai Tiệc Cỗ Cưới & Music',
                'afterparty' => '14:00 / 21:00 After-Party bạn thân & Tung Hoa',
            ],
        ];

        $remediationSuggestions = [];

        if ($overdueTasks->count() > 0) {
            foreach ($overdueTasks as $ot) {
                $remediationSuggestions[] = [
                    'task_id' => $ot->id,
                    'task_title' => $ot->title,
                    'severity' => 'urgent',
                    'advice' => "Công việc '{$ot->title}' đã trễ hạn (".($ot->due_date?->format('Y-m-d') ?? 'quá hạn').'). Đề xuất: Liên hệ đối tác trực tiếp hoặc gộp lịch thực hiện ngay trong 48h tới.',
                    'action_label' => 'Liên hệ ngay',
                ];
            }
        }

        if ($budgetOverview['is_overrun_alert']) {
            $remediationSuggestions[] = [
                'task_id' => null,
                'task_title' => 'Cảnh báo vỡ ngân sách',
                'severity' => 'warning',
                'advice' => 'Ngân sách thực tế đang vượt '.number_format($budgetOverview['overrun_amount']).' VNĐ so với trần ngân sách. Đề xuất: Cắt giảm chi phí hoa tươi phụ phụ hoặc chọn gói thiệp cưới digital.',
                'action_label' => 'Tối ưu ngân sách',
            ];
        }

        if ($unseatedGuests > 0) {
            $remediationSuggestions[] = [
                'task_id' => null,
                'task_title' => 'Khách mời chưa xếp bàn tiệc',
                'severity' => 'info',
                'advice' => "Còn {$unseatedGuests} khách mời đã xác nhận tham dự nhưng chưa được xếp vào sơ đồ bàn tiệc. Đề xuất: Xếp khách vào bàn tiệc sảnh chính.",
                'action_label' => 'Xếp bàn ngay',
            ];
        }

        return [
            'workspace' => $workspaceData,
            'budget' => $budgetOverview,
            'tasks' => $tasksOverview,
            'vendors' => $vendorSummary,
            'guests' => $guestsOverview,
            'hcm_benchmarks' => $hcmBenchmarks,
            'remediation_suggestions' => $remediationSuggestions,
        ];
    }
}
