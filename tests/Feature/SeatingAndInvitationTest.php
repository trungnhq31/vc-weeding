<?php

declare(strict_types=1);

use App\Models\Guest;
use App\Modules\Guest\Models\Table;
use App\Modules\Guest\Services\SeatingPlannerService;
use App\Modules\Invitation\Models\InvitationTemplate;
use App\Modules\Workspace\Models\Workspace;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('it queries available invitation templates catalog correctly', function () {
    InvitationTemplate::create([
        'id' => 'romantic-pastel',
        'name' => 'Romantic Pastel',
        'vue_component' => 'Templates/Pastel.vue',
    ]);

    $templates = InvitationTemplate::all();
    expect($templates)->toHaveCount(1);
    expect($templates->first()->id)->toBe('romantic-pastel');
});

test('it manages seating planner overview and detects over capacity tables', function () {
    $workspace = Workspace::create([
        'name' => 'Seating Test Workspace',
        'slug' => 'seating-test-workspace',
    ]);

    $table = Table::create([
        'workspace_id' => $workspace->id,
        'table_name' => 'Bàn VIP 01',
        'capacity' => 2,
        'zone_name' => 'Sảnh Chính',
    ]);

    $guest1 = Guest::create([
        'workspace_id' => $workspace->id,
        'table_id' => $table->id,
        'guest_slug' => 'khach-01',
        'name' => 'Khách 01',
        'confirmed_count' => 2,
    ]);

    $service = new SeatingPlannerService;
    $overview = $service->getSeatingOverview($workspace->id);

    expect($overview['total_tables_count'])->toBe(1);
    expect($overview['total_assigned_guests'])->toBe(1);
    expect($overview['has_over_capacity_alert'])->toBeFalse();

    // Assign another guest causing over capacity (2 + 2 = 4 > capacity 2)
    Guest::create([
        'workspace_id' => $workspace->id,
        'table_id' => $table->id,
        'guest_slug' => 'khach-02',
        'name' => 'Khách 02',
        'confirmed_count' => 2,
    ]);

    $updatedOverview = $service->getSeatingOverview($workspace->id);
    expect($updatedOverview['has_over_capacity_alert'])->toBeTrue();
    expect($updatedOverview['over_capacity_tables_count'])->toBe(1);
});

test('it assigns guest to table via SeatingPlannerService', function () {
    $workspace = Workspace::create([
        'name' => 'Seating Assign Workspace',
        'slug' => 'seating-assign-workspace',
    ]);

    $table = Table::create([
        'workspace_id' => $workspace->id,
        'table_name' => 'Bàn Bạn Thân',
        'capacity' => 10,
    ]);

    $guest = Guest::create([
        'workspace_id' => $workspace->id,
        'guest_slug' => 'ban-than-01',
        'name' => 'Bạn Thân 01',
    ]);

    $service = new SeatingPlannerService;
    $updatedGuest = $service->assignGuestToTable($guest->id, $table->id);

    expect($updatedGuest->table_id)->toBe($table->id);
    expect($updatedGuest->table_name)->toBe('Bàn Bạn Thân');
});
