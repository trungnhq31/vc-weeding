<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Modules\Workspace\Models\Workspace;
use App\Modules\Workspace\Models\WorkspaceMember;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Inertia\Response;

class OnboardingController extends Controller
{
    public function show(): Response
    {
        return Inertia::render('Auth/Onboarding');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'groom_name' => 'required|string|max:255',
            'bride_name' => 'required|string|max:255',
            'wedding_date' => 'required|date|after_or_equal:today',
            'budget_cap' => 'required|numeric|min:10000000',
            'estimated_guests' => 'nullable|integer|min:1',
            'invitation_template' => 'nullable|string|in:romantic-pastel,royal-gold,modern-slate,botanical-sage',
        ]);

        $workspaceName = "Đám Cưới " . $validated['groom_name'] . " & " . $validated['bride_name'];
        $slug = Str::slug($validated['groom_name'] . '-' . $validated['bride_name'] . '-' . rand(100, 999));

        $workspace = Workspace::create([
            'name' => $workspaceName,
            'slug' => $slug,
            'wedding_date' => $validated['wedding_date'],
            'budget_cap' => $validated['budget_cap'],
            'currency' => 'VND',
        ]);

        if ($user = Auth::user()) {
            WorkspaceMember::create([
                'workspace_id' => $workspace->id,
                'user_id' => $user->id,
                'role' => 'groom',
            ]);
        }

        return redirect('/wedding/timeline')->with('success', 'Khởi tạo kế hoạch đám cưới thành công!');
    }
}
