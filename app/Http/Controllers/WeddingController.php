<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Guest;
use App\Models\WeddingMemory;
use App\Models\Wish;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
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

        $memories = WeddingMemory::where('is_approved', true)
            ->latest()
            ->get();

        return Inertia::render('Wedding/Index', [
            'wishes' => $wishes,
            'memories' => $memories,
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

        $memories = WeddingMemory::where('is_approved', true)
            ->latest()
            ->get();

        return Inertia::render('Wedding/Show', [
            'guest' => $guest,
            'wishes' => $wishes,
            'memories' => $memories,
        ]);
    }

    public function uploadMemory(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'uploader_name' => 'required|string|max:255',
            'title' => 'nullable|string|max:255',
            'description' => 'nullable|string|max:1000',
            'image' => 'required|image|mimes:jpeg,jpg,png,webp|max:10240',
            'guest_id' => 'nullable|string|exists:guests,id',
        ]);

        $path = $request->file('image')->store('wedding/memories', 'public');
        $url = Storage::url($path);

        $memory = WeddingMemory::create([
            'guest_id' => $validated['guest_id'] ?? null,
            'uploader_name' => $validated['uploader_name'],
            'title' => $validated['title'] ?? 'Kỷ niệm cùng Dâu Rể',
            'description' => $validated['description'] ?? null,
            'image_url' => $url,
            'category' => 'guest_upload',
            'is_approved' => true,
        ]);

        return response()->json([
            'success' => true,
            'memory' => $memory,
        ]);
    }
}
