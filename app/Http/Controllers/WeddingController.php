<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Guest;
use App\Models\Wish;
use Inertia\Inertia;
use Inertia\Response;

class WeddingController extends Controller
{
    public function index(): Response
    {
        $wishes = Wish::where('is_approved', true)
            ->latest()
            ->take(20)
            ->get();

        return Inertia::render('Wedding/Index', [
            'wishes' => $wishes,
            'guest' => null,
        ]);
    }

    public function invitation(string $guestSlug): Response
    {
        $guest = Guest::where('guest_slug', $guestSlug)->firstOrFail();

        $wishes = Wish::where('is_approved', true)
            ->latest()
            ->take(20)
            ->get();

        return Inertia::render('Wedding/Show', [
            'guest' => $guest,
            'wishes' => $wishes,
        ]);
    }
}
