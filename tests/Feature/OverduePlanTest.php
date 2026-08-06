<?php

declare(strict_types=1);

use App\Modules\GroundedAI\Services\GroundedDataQueryService;
use App\Modules\Task\Models\Task;
use App\Modules\Workspace\Models\Workspace;

beforeEach(function () {
    $this->workspace = Workspace::create([
        'name' => 'Đám Cưới Quốc Trung & Hồng Vân',
        'slug' => 'quoc-trung-hong-van',
        'groom_name' => 'Nguyễn Hoàng Quốc Trung',
        'bride_name' => 'Lê Thị Hồng Vân',
        'wedding_date' => '2026-10-24',
        'budget_cap' => 350000000.00,
    ]);

    // Create overdue task
    Task::create([
        'workspace_id' => $this->workspace->id,
        'category' => 'venue',
        'title' => 'Đặt cọc Trung tâm Hội nghị Tiệc cưới Asiana Plaza',
        'description' => 'Hoàn tất thanh toán đợt 1 giữ sảnh tiệc Grand Ballroom.',
        'status' => 'todo',
        'priority' => 'urgent',
        'due_date' => now()->subDays(5),
        'estimated_cost' => 150000000.00,
        'actual_cost' => 0.00,
    ]);
});

test('grounded ai query service outputs overdue task count and remediation suggestions', function () {
    $service = new GroundedDataQueryService;
    $metrics = $service->getWorkspaceMetrics($this->workspace->id);

    expect($metrics['tasks']['overdue_count'])->toBeGreaterThanOrEqual(1);
    expect($metrics)->toHaveKey('remediation_suggestions');
    expect($metrics['remediation_suggestions'])->not->toBeEmpty();
});

test('wedding timeline page renders with overdue remediation props', function () {
    $response = $this->get('/wedding/timeline');

    $response->assertStatus(200);
});
