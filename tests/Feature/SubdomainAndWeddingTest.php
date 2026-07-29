<?php

declare(strict_types=1);

use App\Events\WishSubmittedEvent;
use App\Models\Guest;
use Illuminate\Support\Facades\Event;

test('portfolio page returns successful response on main domain', function () {
    $response = $this->get('/');

    $response->assertStatus(200);
});

test('wedding invitation personalized page returns guest data', function () {
    $guest = Guest::create([
        'guest_slug' => 'test-guest-slug',
        'name' => 'Nguyễn Văn A',
        'salutation' => 'Trân trọng kính mời Nguyễn Văn A',
    ]);

    $response = $this->get('/wedding/invitation/test-guest-slug');

    $response->assertStatus(200);
});

test('rsvp endpoint updates guest status correctly', function () {
    $guest = Guest::create([
        'guest_slug' => 'rsvp-test-slug',
        'name' => 'Trần Thị B',
    ]);

    $response = $this->post('/wedding/rsvp', [
        'guest_slug' => 'rsvp-test-slug',
        'rsvp_status' => 'attending',
        'confirmed_count' => 2,
        'dietary_preference' => 'Món mặn',
        'notes' => 'Sẽ tham dự',
    ]);

    $response->assertRedirect();
    $this->assertDatabaseHas('guests', [
        'guest_slug' => 'rsvp-test-slug',
        'rsvp_status' => 'attending',
        'confirmed_count' => 2,
    ]);
});

test('wish submission broadcasts reverb event', function () {
    Event::fake();

    $response = $this->post('/wedding/wishes', [
        'sender_name' => 'Người Bạn Thân',
        'message' => 'Chúc hai bạn mãi mãi hạnh phúc!',
    ]);

    $response->assertRedirect();
    $this->assertDatabaseHas('wishes', [
        'sender_name' => 'Người Bạn Thân',
    ]);

    Event::assertDispatched(WishSubmittedEvent::class);
});
