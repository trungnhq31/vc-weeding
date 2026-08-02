<?php

declare(strict_types=1);

use App\Models\Guest;
use Illuminate\Foundation\Http\Middleware\ValidateCsrfToken;

beforeEach(function () {
    $this->withoutMiddleware(ValidateCsrfToken::class);
});

test('invitations catalog index page returns successful response', function () {
    $response = $this->get('/invitations');

    $response->assertStatus(200);
});

test('invitations template subroute renders specified template slug', function () {
    $response = $this->get('/invitations/romantic-pastel');

    $response->assertStatus(200);
});

test('invitations template subroute renders royal gold template', function () {
    $response = $this->get('/invitations/royal-gold');

    $response->assertStatus(200);
});

test('invitations template subroute renders indochine traditional template', function () {
    $response = $this->get('/invitations/indochine-traditional');

    $response->assertStatus(200);
});

test('invitations template subroute renders vintage sepia template', function () {
    $response = $this->get('/invitations/vintage-sepia');

    $response->assertStatus(200);
});

test('invitations template subroute renders ocean breeze template', function () {
    $response = $this->get('/invitations/ocean-breeze');

    $response->assertStatus(200);
});

test('invitations template subroute renders midnight velvet template', function () {
    $response = $this->get('/invitations/midnight-velvet');

    $response->assertStatus(200);
});

test('invitations template subroute renders boho chic template', function () {
    $response = $this->get('/invitations/boho-chic');

    $response->assertStatus(200);
});

test('invitations template subroute renders minimalist ivory template', function () {
    $response = $this->get('/invitations/minimalist-ivory');

    $response->assertStatus(200);
});

test('invitations guest personalized subroute returns personalized guest invitation', function () {
    $guest = Guest::create([
        'guest_slug' => 'demo-guest-invitation-subroute',
        'name' => 'Lê Văn C',
        'salutation' => 'Trân trọng kính mời Anh Lê Văn C',
    ]);

    $response = $this->get('/invitations/romantic-pastel/guest/demo-guest-invitation-subroute');

    $response->assertStatus(200);
});
