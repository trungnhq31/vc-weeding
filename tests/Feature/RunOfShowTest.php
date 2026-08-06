<?php

declare(strict_types=1);

use App\Models\WeddingRunOfShow;
use App\Modules\Workspace\Models\Workspace;

test('workspace admin can view run-of-show page and auto-seed default items', function () {
    $workspace = Workspace::create([
        'name' => 'Đám Cưới Test RunOfShow',
        'slug' => 'test-ros-1',
        'groom_name' => 'Quốc Trung',
        'bride_name' => 'Hồng Vân',
    ]);

    session()->put('active_workspace_id', $workspace->id);

    $response = $this->get('/wedding/run-of-show');

    $response->assertStatus(200);
    $this->assertDatabaseHas('wedding_run_of_shows', [
        'workspace_id' => $workspace->id,
        'session_type' => 'morning_ceremony',
    ]);
});

test('workspace admin can create run-of-show timeline item', function () {
    $workspace = Workspace::create([
        'name' => 'Đám Cưới Test RunOfShow 2',
        'slug' => 'test-ros-2',
        'groom_name' => 'Quốc Trung',
        'bride_name' => 'Hồng Vân',
    ]);

    session()->put('active_workspace_id', $workspace->id);

    $response = $this->postJson('/wedding/run-of-show', [
        'session_type' => 'evening_reception',
        'time_slot' => '19:00 - 19:30',
        'title' => 'Múa Mở Màn & Rót Rượu',
        'description' => 'Đội múa nghệ thuật biểu diễn',
        'person_in_charge' => 'MC Tuấn',
        'pic_phone' => '0901234567',
        'location_note' => 'Sân Khấu Chính',
    ]);

    $response->assertStatus(200);
    $response->assertJson(['success' => true]);

    $this->assertDatabaseHas('wedding_run_of_shows', [
        'workspace_id' => $workspace->id,
        'title' => 'Múa Mở Màn & Rót Rượu',
        'person_in_charge' => 'MC Tuấn',
    ]);
});

test('workspace admin can toggle completion status of run-of-show item', function () {
    $workspace = Workspace::create([
        'name' => 'Đám Cưới Test RunOfShow 3',
        'slug' => 'test-ros-3',
    ]);

    $item = WeddingRunOfShow::create([
        'workspace_id' => $workspace->id,
        'session_type' => 'party',
        'time_slot' => '20:00 - 21:00',
        'title' => 'Bốc Thăm trúng thưởng',
        'is_completed' => false,
    ]);

    $response = $this->postJson("/wedding/run-of-show/{$item->id}/toggle");
    $response->assertStatus(200);
    $response->assertJson(['success' => true, 'is_completed' => true]);
});
