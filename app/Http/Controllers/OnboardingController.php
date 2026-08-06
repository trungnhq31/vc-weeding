<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Modules\Workspace\Models\Workspace;
use App\Modules\Workspace\Models\WorkspaceMember;
use App\Services\PersonalizedPlanGeneratorService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Inertia\Response;

class OnboardingController extends Controller
{
    public function __construct(
        protected PersonalizedPlanGeneratorService $planGenerator = new PersonalizedPlanGeneratorService
    ) {}

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
            'ceremony_type' => 'nullable|string|in:traditional_south,traditional_north,catholic_church,destination_outdoor,hotel_luxury',
            'wedding_vibe' => 'nullable|string|in:pastel,royal_gold,botanical,minimalist',
            'region' => 'nullable|string|max:255',
            'wedding_location' => 'nullable|string|max:255',
            'venue_name' => 'nullable|string|max:255',
        ]);

        $groomName = $validated['groom_name'];
        $brideName = $validated['bride_name'];
        $workspaceName = 'Đám Cưới '.$groomName.' & '.$brideName;
        $slug = Str::slug($groomName.'-'.$brideName.'-'.rand(100, 999));

        $workspace = Workspace::create([
            'name' => $workspaceName,
            'slug' => $slug,
            'groom_name' => $groomName,
            'bride_name' => $brideName,
            'wedding_date' => $validated['wedding_date'],
            'budget_cap' => $validated['budget_cap'],
            'estimated_guests' => $validated['estimated_guests'] ?? 200,
            'ceremony_type' => $validated['ceremony_type'] ?? 'traditional_south',
            'wedding_vibe' => $validated['wedding_vibe'] ?? 'pastel',
            'region' => $validated['region'] ?? 'hcm',
            'wedding_location' => $validated['wedding_location'] ?? 'TP. Hồ Chí Minh',
            'venue_name' => $validated['venue_name'] ?? 'Trung tâm Tiệc cưới Center Palace',
            'wedding_hashtag' => '#'.Str::slug($groomName.$brideName).'Wedding2026',
            'currency' => 'VND',
        ]);

        if ($user = Auth::user()) {
            WorkspaceMember::create([
                'workspace_id' => $workspace->id,
                'user_id' => $user->id,
                'role' => 'groom',
            ]);
        }

        // Generate personalized milestones, tasks, and budget allocations for this couple
        $this->planGenerator->generateForWorkspace($workspace);

        session()->put('active_workspace_id', $workspace->id);

        return redirect('/wedding/timeline')->with('success', 'Khởi tạo kế hoạch cá nhân hóa thành công!');
    }
}
