<?php

declare(strict_types=1);

use App\Models\WeddingMilestone;
use App\Models\WeddingTask;
use App\Modules\Workspace\Models\Workspace;
use App\Services\PersonalizedPlanGeneratorService;

beforeEach(function () {
    $this->generator = new PersonalizedPlanGeneratorService;
});

test('it generates customized catholic church ceremony planner tasks', function () {
    $workspace = Workspace::create([
        'name' => 'Đám Cưới Công Giáo Quốc Trung & Hồng Vân',
        'slug' => 'quoc-trung-hong-van-church',
        'groom_name' => 'Nguyễn Hoàng Quốc Trung',
        'bride_name' => 'Lê Thị Hồng Vân',
        'wedding_date' => '2026-10-24',
        'budget_cap' => 400000000.00,
        'ceremony_type' => 'catholic_church',
        'wedding_vibe' => 'pastel',
    ]);

    $this->generator->generateForWorkspace($workspace);

    $milestones = WeddingMilestone::where('workspace_id', $workspace->id)->get();
    expect($milestones)->not->toBeEmpty();

    $churchTask = WeddingTask::whereHas('milestone', fn ($q) => $q->where('workspace_id', $workspace->id))
        ->where('title', 'like', '%Giáo Lý%')
        ->first();

    expect($churchTask)->not->toBeNull();
});

test('it generates customized destination outdoor beach wedding planner tasks', function () {
    $workspace = Workspace::create([
        'name' => 'Outdoor Beach Wedding',
        'slug' => 'beach-wedding-2026',
        'groom_name' => 'Quốc Trung',
        'bride_name' => 'Hồng Vân',
        'wedding_date' => '2026-11-20',
        'budget_cap' => 500000000.00,
        'ceremony_type' => 'destination_outdoor',
        'wedding_vibe' => 'botanical',
    ]);

    $this->generator->generateForWorkspace($workspace);

    $outdoorTask = WeddingTask::whereHas('milestone', fn ($q) => $q->where('workspace_id', $workspace->id))
        ->where(fn ($q) => $q->where('title', 'like', '%Outdoor%')->orWhere('title', 'like', '%Bãi Biển%'))
        ->first();

    expect($outdoorTask)->not->toBeNull();
});

test('onboarding endpoint creates personalized couple workspace', function () {
    $response = $this->post('/onboarding', [
        'groom_name' => 'Lê Minh Hùng',
        'bride_name' => 'Trần Ngọc Bích',
        'wedding_date' => '2026-12-25',
        'budget_cap' => 300000000,
        'estimated_guests' => 150,
        'ceremony_type' => 'traditional_south',
        'wedding_vibe' => 'royal_gold',
        'region' => 'hcm',
    ]);

    $response->assertRedirect('/wedding/timeline');

    $workspace = Workspace::where('groom_name', 'Lê Minh Hùng')->first();
    expect($workspace)->not->toBeNull();
    expect($workspace->ceremony_type)->toBe('traditional_south');
});
