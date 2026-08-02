<?php

declare(strict_types=1);

use App\Models\Guest;
use App\Modules\Workspace\Models\Workspace;

beforeEach(function () {
    Workspace::firstOrCreate(
        ['slug' => 'quoc-trung-hong-van'],
        [
            'name' => 'Đám Cưới Quốc Trung & Hồng Vân',
            'groom_name' => 'Nguyễn Hoàng Quốc Trung',
            'bride_name' => 'Lê Thị Hồng Vân',
            'budget_cap' => 250000000.00,
            'estimated_guests' => 200,
            'wedding_date' => '2026-10-24',
            'wedding_location' => 'TP. Hồ Chí Minh',
        ]
    );
});

test('public shared guest list page can be rendered via share token', function () {
    $response = $this->get('/wedding/share-guest-list/quoc-trung-hong-van');

    $response->assertStatus(200)
        ->assertInertia(fn ($page) => $page
            ->component('Wedding/SharedGuestList')
            ->has('workspace')
            ->has('recentGuests')
            ->has('shareToken')
        );
});

test('user or family member can submit a guest entry via public share link', function () {
    $response = $this->postJson('/wedding/share-guest-list/quoc-trung-hong-van/add', [
        'name' => 'Bác Hai - Họ Hàng Trai',
        'phone' => '0909998877',
        'group' => 'Nhà Trai (Họ Hàng)',
        'added_by' => 'Mẹ Chú Rể',
        'dietary_preference' => 'Ăn chay',
        'estimated_count' => 2,
    ]);

    $response->assertStatus(200)
        ->assertJson([
            'success' => true,
        ]);

    $guest = Guest::where('name', 'Bác Hai - Họ Hàng Trai')->first();
    expect($guest)->not->toBeNull();
    expect($guest->notes)->toContain('Mẹ Chú Rể');
});

test('private guest list page returns dbGuests and shareUrl', function () {
    $response = $this->get('/wedding/guests');

    $response->assertStatus(200)
        ->assertInertia(fn ($page) => $page
            ->component('Wedding/Guests')
            ->has('workspace')
            ->has('dbGuests')
            ->has('shareUrl')
        );
});
