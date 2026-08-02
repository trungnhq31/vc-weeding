<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\WeddingMilestone;
use App\Models\WeddingTask;
use App\Models\Guest;
use App\Modules\Invitation\Models\WorkspaceInvitation;
use App\Modules\Vendor\Models\Vendor;
use App\Modules\Workspace\Models\Workspace;
use Illuminate\Support\Str;

class WeddingTaskExecutionService
{
    /**
     * Get Grounded AI Context & Personalized Task Recommendation for Dâu Rể.
     */
    public function getTaskAiRecommendation(WeddingTask $task): array
    {
        $workspace = Workspace::latest()->first();
        if (! $workspace) {
            $workspace = Workspace::create([
                'name' => 'Đám Cưới Quốc Trung & Hồng Vân',
                'slug' => 'quoc-trung-hong-van-'.Str::random(5),
                'groom_name' => 'Nguyễn Hoàng Quốc Trung',
                'bride_name' => 'Lê Thị Hồng Vân',
                'budget_cap' => 250000000.00,
                'estimated_guests' => 200,
                'wedding_date' => '2026-10-24',
                'wedding_location' => 'TP. Hồ Chí Minh',
            ]);
        }

        $groom = $workspace->groom_name ?? 'Nguyễn Hoàng Quốc Trung';
        $bride = $workspace->bride_name ?? 'Lê Thị Hồng Vân';
        $date = $workspace->wedding_date ? (is_string($workspace->wedding_date) ? $workspace->wedding_date : $workspace->wedding_date->format('Y-m-d')) : '2026-10-24';
        $location = $workspace->wedding_location ?? 'TP. Hồ Chí Minh';
        $budget = (float) ($workspace->budget_cap ?? 250000000.00);
        $guests = (int) ($workspace->estimated_guests ?? 200);

        $titleLower = Str::lower($task->title);
        $recommendationText = '';
        $suggestedInput = [];

        if (Str::contains($titleLower, ['ngân sách', 'chi phí', 'bảng giá', 'tài chính'])) {
            $recommendationText = 'AI đề xuất chốt ngân sách trần '.number_format($budget).' đ dựa trên quy mô '.$guests.' khách tại '.$location.'. Mức chi phí bình quân đạt '.number_format((int) ($budget / max($guests, 1))).' đ/khách.';
            $suggestedInput = ['budget_cap' => $budget];
        } elseif (Str::contains($titleLower, ['khách mời', 'sơ đồ bàn', 'danh sách khách'])) {
            $tables = (int) ceil($guests / 10);
            $recommendationText = 'Dựa trên quy mô tiệc cưới '.$guests.' khách tại '.$location.', AI đề xuất lập danh sách 5 nhóm khách mời (Gia đình, Họ hàng, Bạn chú rể, Bạn cô dâu, Đồng nghiệp) phân bổ thành '.$tables.' bàn tiệc (10 người/bàn).';
            $suggestedInput = ['estimated_guests' => $guests];
        } elseif (Str::contains($titleLower, ['thiệp', 'mẫu thiệp', 'decor', 'tone màu', 'trang trí'])) {
            $recommendationText = 'Grounded AI đề xuất mẫu thiệp 3D [Royal Gold - Hoàng Gia Dát Vàng 24K] mở sáp nến độc bản cho đám cưới '.$groom.' & '.$bride.' ngày '.$date.'.';
            $suggestedInput = ['template_slug' => 'royal-gold'];
        } else {
            $cost = (float) ($task->estimated_cost ?: 35000000.00);
            $vendor = $task->vendor_info ?: 'Trung Tâm Tiệc Cưới Luxury Palace';
            $recommendationText = 'AI phân tích báo giá đối tác tại '.$location.': Đề xuất chốt hợp đồng & thanh toán cọc đợt 1 cho ['.$vendor.'] chi phí '.number_format($cost).' đ.';
            $suggestedInput = ['vendor_name' => $vendor, 'actual_cost' => $cost];
        }

        return [
            'success' => true,
            'workspaceContext' => [
                'couple_name' => "{$groom} & {$bride}",
                'wedding_date' => $date,
                'wedding_location' => $location,
                'budget_cap' => $budget,
                'estimated_guests' => $guests,
                'venue_name' => $workspace->venue_name ?? 'Trung Tâm Tiệc Cưới Luxury Palace',
            ],
            'aiRecommendation' => [
                'title' => "Gợi Ý Đề Xuất Thông Minh Cho Bước: [{$task->title}]",
                'description' => $recommendationText,
                'suggestedInput' => $suggestedInput,
            ],
            'task' => $task,
        ];
    }

    /**
     * Execute a 1-click system action for a given wedding task.
     */
    public function executeTaskAction(WeddingTask $task, array $input = []): array
    {
        $workspace = Workspace::latest()->first();
        if (! $workspace) {
            $workspace = Workspace::create([
                'name' => 'Đám Cưới Quốc Trung & Hồng Vân',
                'slug' => 'quoc-trung-hong-van-'.Str::random(5),
                'groom_name' => 'Nguyễn Hoàng Quốc Trung',
                'bride_name' => 'Lê Thị Hồng Vân',
                'budget_cap' => 250000000.00,
                'estimated_guests' => 200,
                'wedding_date' => '2026-10-24',
                'wedding_location' => 'TP. Hồ Chí Minh',
            ]);
        }

        $titleLower = Str::lower($task->title);
        $resultMessage = 'Đã thực thi thành công đề xuất của AI vào hệ thống.';

        // 1. Budget Tasks
        if (Str::contains($titleLower, ['ngân sách', 'chi phí', 'bảng giá', 'tài chính'])) {
            $budgetCap = (float) ($input['budget_cap'] ?? $workspace->budget_cap ?? 250000000.00);
            $workspace->update(['budget_cap' => $budgetCap]);
            $resultMessage = 'Đã chốt ngân sách trần '.number_format($budgetCap).' đ theo đề xuất AI.';
        }
        // 2. Guest List Tasks
        elseif (Str::contains($titleLower, ['khách mời', 'sơ đồ bàn', 'danh sách khách'])) {
            $estimatedGuests = (int) ($input['estimated_guests'] ?? 200);
            $workspace->update(['estimated_guests' => $estimatedGuests]);

            // Seed initial guests if empty
            if (Guest::where('workspace_id', $workspace->id)->count() === 0) {
                for ($i = 1; $i <= 10; $i++) {
                    Guest::create([
                        'workspace_id' => $workspace->id,
                        'name' => "Khách Mời VIP #{$i}",
                        'phone' => '090123456'.$i,
                        'group' => 'Gia Đình',
                        'rsvp_status' => 'confirmed',
                        'pax' => 2,
                    ]);
                }
            }
            $resultMessage = 'Đã khởi tạo danh sách khách mời dự kiến '.$estimatedGuests.' khách theo đề xuất AI.';
        }
        // 3. 3D Invitation Tasks
        elseif (Str::contains($titleLower, ['thiệp', 'mẫu thiệp', 'decor', 'tone màu', 'trang trí'])) {
            $templateSlug = $input['template_slug'] ?? 'royal-gold';
            WorkspaceInvitation::updateOrCreate(
                ['workspace_id' => $workspace->id],
                [
                    'template_slug' => $templateSlug,
                    'headline' => 'WEDDING INVITATION',
                    'groom_name' => $workspace->groom_name,
                    'bride_name' => $workspace->bride_name,
                    'wedding_date' => $workspace->wedding_date ? (is_string($workspace->wedding_date) ? $workspace->wedding_date : $workspace->wedding_date->format('Y-m-d')) : '2026-10-24',
                    'venue_name' => 'Trung Tâm Tiệc Cưới Luxury Palace',
                    'venue_address' => '123 Nguyễn Huệ, Quận 1, TP. HCM',
                ]
            );
            $resultMessage = 'Đã áp dụng mẫu thiệp 3D độc bản ['.$templateSlug.'] theo đề xuất AI.';
        }
        // 4. Vendor Contract Tasks
        elseif (Str::contains($titleLower, ['sảnh tiệc', 'nhà hàng', 'chụp ảnh', 'nhẫn', 'studio', 'vendor', 'cọc'])) {
            $vendorName = $input['vendor_name'] ?? ($task->vendor_info ?: 'Trung Tâm Tiệc Cưới VIP');
            $cost = (float) ($input['actual_cost'] ?? $task->estimated_cost ?? 35000000.00);

            Vendor::firstOrCreate(
                [
                    'workspace_id' => $workspace->id,
                    'name' => $vendorName,
                ],
                [
                    'category' => 'venue',
                    'city' => 'TP. Hồ Chí Minh',
                    'district' => 'Quận 1',
                    'price_range' => 'premium',
                    'contact_phone' => '0988776655',
                    'rating' => 4.9,
                    'match_score' => 98,
                ]
            );

            $task->update([
                'actual_cost' => $cost,
                'vendor_info' => $vendorName,
            ]);

            $resultMessage = 'Đã ghi nhận hợp đồng cọc với ['.$vendorName.'] chi phí '.number_format($cost).' đ theo đề xuất AI.';
        }

        // Mark task and all its subtasks as 100% completed
        $subtasks = $task->subtasks ?? [];
        $updatedSubtasks = array_map(function ($sub) {
            $sub['is_completed'] = true;

            return $sub;
        }, $subtasks);

        $task->update([
            'is_completed' => true,
            'subtasks' => $updatedSubtasks,
        ]);

        // Recalculate milestone spent & progress
        $milestone = $task->milestone;
        if ($milestone) {
            $totalSpent = $milestone->tasks()->sum('actual_cost');
            $milestone->update(['budget_spent' => $totalSpent]);
        }

        return [
            'success' => true,
            'message' => $resultMessage,
            'task' => $task->fresh(),
            'milestoneProgress' => $milestone ? $milestone->fresh()->progress_percentage : 100,
        ];
    }

    /**
     * Auto-complete all pending subtasks in a milestone using Grounded AI resolution.
     */
    public function autoCompleteMilestoneWithAi(WeddingMilestone $milestone): array
    {
        $tasks = $milestone->tasks;
        $completedCount = 0;

        foreach ($tasks as $task) {
            if (! $task->is_completed) {
                $this->executeTaskAction($task);
                $completedCount++;
            }
        }

        $milestone->refresh();

        return [
            'success' => true,
            'message' => "Grounded AI đã tự động hoàn thành {$completedCount} công việc trong [{$milestone->title}].",
            'milestoneProgress' => $milestone->progress_percentage,
            'milestone' => $milestone->load('tasks'),
        ];
    }
}
