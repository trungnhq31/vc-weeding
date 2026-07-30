<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Enums\PostStatus;
use App\Models\Post;
use App\Modules\Invitation\Models\InvitationTemplate;
use Inertia\Inertia;
use Inertia\Response;

class LandingController extends Controller
{
    public function index(): Response
    {
        $templates = InvitationTemplate::all();
        $latestPosts = Post::where('status', PostStatus::Published)
            ->latest('published_at')
            ->take(3)
            ->get();

        return Inertia::render('Landing/Index', [
            'templates' => $templates,
            'latestPosts' => $latestPosts,
        ]);
    }
}
