<?php

declare(strict_types=1);

use App\Models\WeddingMilestone;
use Database\Seeders\WeddingMilestoneSeeder;

it('renders the wedding timeline page with milestones and stats', function () {
    $this->seed(WeddingMilestoneSeeder::class);

    $response = $this->get('/wedding/timeline');

    $response->assertStatus(200)
        ->assertInertia(fn ($page) => $page
            ->component('Wedding/Timeline')
            ->has('milestones')
            ->has('stats')
        );
});

it('can toggle a task completion status via API', function () {
    $this->seed(WeddingMilestoneSeeder::class);

    $milestone = WeddingMilestone::first();
    $task = $milestone->tasks()->first();

    $initialState = $task->is_completed;

    $response = $this->postJson("/wedding/tasks/{$task->id}/toggle");

    $response->assertStatus(200)
        ->assertJson([
            'success' => true,
        ]);

    expect($task->fresh()->is_completed)->toBe(! $initialState);
});
