<?php

declare(strict_types=1);

use App\Models\WeddingGiftLog;
use App\Modules\Workspace\Models\Workspace;

test('workspace admin can view gift-log page', function () {
    $workspace = Workspace::create([
        'name' => 'Đám Cưới Test GiftLog',
        'slug' => 'test-gift-1',
        'groom_name' => 'Quốc Trung',
        'bride_name' => 'Hồng Vân',
    ]);

    session()->put('active_workspace_id', $workspace->id);

    $response = $this->get('/wedding/gift-log');

    $response->assertStatus(200);
});

test('workspace admin can record gift in gift-log', function () {
    $workspace = Workspace::create([
        'name' => 'Đám Cưới Test GiftLog 2',
        'slug' => 'test-gift-2',
    ]);

    session()->put('active_workspace_id', $workspace->id);

    $response = $this->postJson('/wedding/gift-log', [
        'giver_name' => 'Anh Tuấn',
        'relationship' => 'groom_friend',
        'amount' => 5000000,
        'gift_type' => 'transfer',
        'wish_message' => 'Chúc hai bạn hạnh phúc!',
    ]);

    $response->assertStatus(200);
    $response->assertJson(['success' => true]);

    $this->assertDatabaseHas('wedding_gift_logs', [
        'workspace_id' => $workspace->id,
        'giver_name' => 'Anh Tuấn',
        'amount' => 5000000.00,
    ]);
});

test('workspace admin can toggle thank you status for gift log', function () {
    $workspace = Workspace::create([
        'name' => 'Đám Cưới Test GiftLog 3',
        'slug' => 'test-gift-3',
    ]);

    $log = WeddingGiftLog::create([
        'workspace_id' => $workspace->id,
        'giver_name' => 'Bác Hai',
        'relationship' => 'family',
        'amount' => 10000000,
        'gift_type' => 'cash',
        'thank_you_sent' => false,
    ]);

    $response = $this->postJson("/wedding/gift-log/{$log->id}/thank-you");
    $response->assertStatus(200);
    $response->assertJson(['success' => true, 'thank_you_sent' => true]);
});
