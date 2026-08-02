<?php

declare(strict_types=1);

use App\Models\WeddingMilestone;
use App\Models\WeddingTask;
use Database\Seeders\WeddingMilestoneSeeder;

beforeEach(function () {
    (new WeddingMilestoneSeeder)->run();
});

test('user can fetch AI smart task recommendation with full workspace couple context', function () {
    $task = WeddingTask::first();
    expect($task)->not->toBeNull();

    $response = $this->getJson("/wedding/tasks/{$task->id}/ai-recommendation");

    $response->assertStatus(200)
        ->assertJson([
            'success' => true,
        ])
        ->assertJsonStructure([
            'workspaceContext' => ['couple_name', 'wedding_date', 'budget_cap', 'estimated_guests'],
            'aiRecommendation' => ['title', 'description', 'suggestedInput'],
        ]);
});

test('user can execute budget task in 1 click and update workspace budget cap', function () {
    $task = WeddingTask::where('title', 'like', '%ngân sách%')->first();
    expect($task)->not->toBeNull();

    $response = $this->postJson("/wedding/tasks/{$task->id}/execute-action", [
        'budget_cap' => 300000000.00,
    ]);

    $response->assertStatus(200)
        ->assertJson([
            'success' => true,
        ]);

    expect($task->fresh()->is_completed)->toBeTrue();
});

test('user can execute guest task in 1 click and populate initial guests', function () {
    $task = WeddingTask::where('title', 'like', '%khách mời%')->first();
    expect($task)->not->toBeNull();

    $response = $this->postJson("/wedding/tasks/{$task->id}/execute-action", [
        'estimated_guests' => 250,
    ]);

    $response->assertStatus(200)
        ->assertJson([
            'success' => true,
        ]);

    expect($task->fresh()->is_completed)->toBeTrue();
});

test('user can execute invitation task in 1 click and activate 3D template', function () {
    $task = WeddingTask::where('title', 'like', '%trang trí%')->orWhere('title', 'like', '%tone màu%')->first();
    expect($task)->not->toBeNull();

    $response = $this->postJson("/wedding/tasks/{$task->id}/execute-action", [
        'template_slug' => 'royal-gold',
    ]);

    $response->assertStatus(200)
        ->assertJson([
            'success' => true,
        ]);

    expect($task->fresh()->is_completed)->toBeTrue();
});

test('user can auto complete entire milestone via Grounded AI in 1 click', function () {
    $milestone = WeddingMilestone::first();
    expect($milestone)->not->toBeNull();

    $response = $this->postJson("/wedding/milestones/{$milestone->id}/auto-complete-ai");

    $response->assertStatus(200)
        ->assertJson([
            'success' => true,
        ]);

    expect($milestone->fresh()->progress_percentage)->toBe(100);
});
