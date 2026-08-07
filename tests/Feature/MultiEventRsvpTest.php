<?php

declare(strict_types=1);

use App\Actions\Wedding\SubmitRsvpAction;
use App\Data\RsvpData;
use App\Enums\RsvpStatus;
use App\Models\Guest;
use App\Modules\Guest\Actions\CreateGuestAction;
use App\Modules\Workspace\Models\Workspace;

beforeEach(function () {
    $this->workspace = Workspace::create([
        'name' => 'Lễ Cưới Quốc Trung & Hồng Vân',
        'slug' => 'quoc-trung-hong-van',
        'groom_name' => 'Quốc Trung',
        'bride_name' => 'Hồng Vân',
        'budget_cap' => 350000000.00,
    ]);
});

test('can create guest with explicit multi-event rsvp statuses', function () {
    $action = new CreateGuestAction;

    $guest = $action->execute([
        'workspace_id' => $this->workspace->id,
        'name' => 'Anh Hoàng Tuấn',
        'group' => 'Bạn Cấp 3',
        'rsvp_status' => 'attending',
        'rsvp_ceremony' => 'attending',
        'rsvp_reception' => 'attending',
        'rsvp_afterparty' => 'declined',
        'confirmed_count' => 2,
        'dietary_preference' => 'Không ăn cay',
    ]);

    expect($guest->rsvp_status)->toBe(RsvpStatus::Attending);
    expect($guest->rsvp_ceremony)->toBe(RsvpStatus::Attending);
    expect($guest->rsvp_reception)->toBe(RsvpStatus::Attending);
    expect($guest->rsvp_afterparty)->toBe(RsvpStatus::Declined);
    expect($guest->confirmed_count)->toBe(2);
    expect($guest->dietary_preference)->toBe('Không ăn cay');
});

test('sub-events default to main rsvp status when not specified', function () {
    $action = new CreateGuestAction;

    $guest = $action->execute([
        'workspace_id' => $this->workspace->id,
        'name' => 'Chị Minh Phương',
        'group' => 'Đồng Nghiệp',
        'rsvp_status' => 'attending',
    ]);

    expect($guest->rsvp_status)->toBe(RsvpStatus::Attending);
    expect($guest->rsvp_ceremony)->toBe(RsvpStatus::Attending);
    expect($guest->rsvp_reception)->toBe(RsvpStatus::Attending);
    expect($guest->rsvp_afterparty)->toBe(RsvpStatus::Attending);
});

test('submit rsvp action updates existing guest sub-events correctly', function () {
    $guest = Guest::create([
        'workspace_id' => $this->workspace->id,
        'name' => 'Phạm Minh Đức',
        'guest_slug' => 'pham-minh-duc',
        'rsvp_status' => RsvpStatus::Pending,
        'estimated_count' => 1,
    ]);

    $rsvpData = RsvpData::fromRequest([
        'guest_slug' => $guest->guest_slug,
        'guest_name' => 'Phạm Minh Đức',
        'rsvp_status' => 'attending',
        'rsvp_ceremony' => 'attending',
        'rsvp_reception' => 'attending',
        'rsvp_afterparty' => 'declined',
        'confirmed_count' => 1,
        'dietary_preference' => 'Ăn chay',
        'notes' => 'Chúc hai bạn trăm năm hạnh phúc!',
    ]);

    $action = new SubmitRsvpAction;
    $updatedGuest = $action->execute($rsvpData);

    expect($updatedGuest->rsvp_status)->toBe(RsvpStatus::Attending);
    expect($updatedGuest->rsvp_ceremony)->toBe(RsvpStatus::Attending);
    expect($updatedGuest->rsvp_reception)->toBe(RsvpStatus::Attending);
    expect($updatedGuest->rsvp_afterparty)->toBe(RsvpStatus::Declined);
    expect($updatedGuest->dietary_preference)->toBe('Ăn chay');
    expect($updatedGuest->notes)->toBe('Chúc hai bạn trăm năm hạnh phúc!');
});

test('workspace guest sub-event counts calculation is accurate', function () {
    $createAction = new CreateGuestAction;

    // Guest 1: Attending all 3 events (2 confirmed)
    $createAction->execute([
        'workspace_id' => $this->workspace->id,
        'name' => 'Khách 1',
        'rsvp_status' => 'attending',
        'rsvp_ceremony' => 'attending',
        'rsvp_reception' => 'attending',
        'rsvp_afterparty' => 'attending',
        'confirmed_count' => 2,
    ]);

    // Guest 2: Attending ceremony and reception, declining afterparty (1 confirmed)
    $createAction->execute([
        'workspace_id' => $this->workspace->id,
        'name' => 'Khách 2',
        'rsvp_status' => 'attending',
        'rsvp_ceremony' => 'attending',
        'rsvp_reception' => 'attending',
        'rsvp_afterparty' => 'declined',
        'confirmed_count' => 1,
    ]);

    // Guest 3: Declined all (0 confirmed)
    $createAction->execute([
        'workspace_id' => $this->workspace->id,
        'name' => 'Khách 3',
        'rsvp_status' => 'declined',
        'rsvp_ceremony' => 'declined',
        'rsvp_reception' => 'declined',
        'rsvp_afterparty' => 'declined',
        'confirmed_count' => 0,
    ]);

    $allGuests = Guest::forWorkspace($this->workspace->id)->get();

    $ceremonyCount = $allGuests->filter(fn ($g) => $g->rsvp_ceremony === RsvpStatus::Attending)->sum('confirmed_count');
    $receptionCount = $allGuests->filter(fn ($g) => $g->rsvp_reception === RsvpStatus::Attending)->sum('confirmed_count');
    $afterpartyCount = $allGuests->filter(fn ($g) => $g->rsvp_afterparty === RsvpStatus::Attending)->sum('confirmed_count');

    expect($ceremonyCount)->toBe(3);
    expect($receptionCount)->toBe(3);
    expect($afterpartyCount)->toBe(2);
});
