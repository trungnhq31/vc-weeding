<?php

declare(strict_types=1);

use App\Services\WeddingBudgetAllocationService;
use App\Modules\Workspace\Models\Workspace;

test('it calculates 5-pillar meticulous budget allocation and per-table budget cap correctly', function () {
    $service = new WeddingBudgetAllocationService;
    $breakdown = $service->calculateStandardBreakdown(250000000.00, 200);

    expect($breakdown['total_budget'])->toBe(250000000.00);
    expect($breakdown['total_tables'])->toBe(20);
    expect($breakdown['per_table_cap'])->toBe(6250000.00);
    expect(count($breakdown['pillars']))->toBe(5);

    // Verify Venue allocation is 50%
    $venuePillar = collect($breakdown['pillars'])->firstWhere('key', 'venue');
    expect($venuePillar['percentage'])->toBe(50);
    expect($venuePillar['allocated'])->toBe(125000000.00);
});

test('it generates recommended banquet halls filtered by per-table price tier', function () {
    $service = new WeddingBudgetAllocationService;
    $venues = $service->getRecommendedVenues(250000000.00, 200, 'TP. Hồ Chí Minh');

    expect(count($venues))->toBeGreaterThanOrEqual(3);
    expect($venues[0]['name'])->toContain('Luxury Palace');
    expect($venues[0]['match_score'])->toBeGreaterThanOrEqual(90);
});

test('user can select a venue in 1 click and lock it into workspace and update timeline task', function () {
    $service = new WeddingBudgetAllocationService;
    $result = $service->selectVenue('Trung Tâm Tiệc Cưới Luxury Palace', 35000000.00);

    expect($result['success'])->toBeTrue();
    
    $workspace = Workspace::latest()->first();
    expect($workspace->venue_name)->toBe('Trung Tâm Tiệc Cưới Luxury Palace');
});

test('budget inertia route returns budget breakdown and recommended venues', function () {
    $response = $this->get('/wedding/budget');

    $response->assertStatus(200)
        ->assertInertia(fn ($page) => $page
            ->component('Wedding/Budget')
            ->has('budgetBreakdown')
            ->has('recommendedVenues')
        );
});
