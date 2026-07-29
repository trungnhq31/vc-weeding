<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Enums\PostStatus;
use App\Models\Post;
use Inertia\Inertia;
use Inertia\Response;

class PortfolioController extends Controller
{
    public function index(): Response
    {
        $latestPosts = Post::where('status', PostStatus::Published)
            ->latest('published_at')
            ->take(3)
            ->get(['id', 'title', 'slug', 'excerpt', 'reading_time_minutes', 'published_at']);

        return Inertia::render('Portfolio/Index', [
            'latestPosts' => $latestPosts,
        ]);
    }
}
