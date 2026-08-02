<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\WeddingMilestone;
use App\Models\WeddingTask;
use App\Modules\Guest\Models\Guest;
use App\Modules\Invitation\Models\WorkspaceInvitation;
use App\Modules\Vendor\Models\Vendor;
use App\Modules\Workspace\Models\Workspace;
use Illuminate\Support\Str;

class WeddingTaskExecutionService
{
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
        $resultMessage = 'Đã hoàn thành công việc qua hệ thống.';

        // 1. Budget Tasks
        if (Str::contains($titleLower, ['ngân sách', 'chi phí', 'bảng giá', 'tài chính'])) {
            $budgetCap = (float) ($input['budget_cap'] ?? $workspace->budget_cap ?? 250000000.00);
            $workspace->update(['budget_cap' => $budgetCap]);
            $resultMessage = 'Đã chốt ngân sách trần '.number_format($budgetCap).' đ vào hệ thống.';
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
            $resultMessage = 'Đã khởi tạo danh sách khách mời dự kiến '.$estimatedGuests.' khách.';
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
            $resultMessage = 'Đã kích hoạt mẫu thiệp 3D độc bản ['.$templateSlug.'] vào hệ thống.';
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

            $resultMessage = 'Đã ghi nhận hợp đồng cọc với ['.$vendorName.'] chi phí '.number_format($cost).' đ.';
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
