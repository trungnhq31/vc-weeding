<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Guest;
use App\Models\WeddingMemory;
use App\Models\Wish;
use App\Models\Workspace;
use App\Modules\Budget\Actions\ExportBudgetAction;
use App\Modules\Guest\Actions\ExportGuestListAction;
use App\Modules\Invitation\Actions\UpdateInvitationCmsAction;
use App\Modules\Invitation\Models\InvitationTemplate;
use App\Modules\Invitation\Models\WorkspaceInvitation;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Inertia\Response;
use Symfony\Component\HttpFoundation\StreamedResponse;

class WeddingController extends Controller
{
    public function index(?string $templateSlug = 'romantic-pastel'): Response
    {
        $wishes = Wish::where('is_approved', true)
            ->latest()
            ->take(20)
            ->get();

        $memories = WeddingMemory::where('is_approved', true)
            ->latest()
            ->get();

        return Inertia::render('Wedding/Index', [
            'templateSlug' => $templateSlug ?? 'romantic-pastel',
            'wishes' => $wishes,
            'memories' => $memories,
            'guest' => null,
        ]);
    }

    public function invitation(string $templateSlugOrGuestSlug, ?string $guestSlug = null): Response
    {
        $actualGuestSlug = $guestSlug ?? $templateSlugOrGuestSlug;
        $actualTemplateSlug = $guestSlug ? $templateSlugOrGuestSlug : 'romantic-pastel';

        $guest = Guest::where('guest_slug', $actualGuestSlug)->firstOrFail();

        $wishes = Wish::where('is_approved', true)
            ->latest()
            ->take(20)
            ->get();

        $memories = WeddingMemory::where('is_approved', true)
            ->latest()
            ->get();

        return Inertia::render('Wedding/Show', [
            'templateSlug' => $actualTemplateSlug,
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

    public function budget(): Response
    {
        return Inertia::render('Wedding/Budget');
    }

    public function guests(): Response
    {
        return Inertia::render('Wedding/Guests');
    }

    public function invitationEditor(): Response
    {
        $workspace = Workspace::first();
        $invitation = WorkspaceInvitation::with('template')
            ->where('workspace_id', $workspace->id ?? null)
            ->first();
        $templates = InvitationTemplate::all();

        return Inertia::render('Wedding/InvitationEditor', [
            'workspace' => $workspace,
            'invitation' => $invitation,
            'templates' => $templates,
        ]);
    }

    public function saveInvitationCms(Request $request, UpdateInvitationCmsAction $action): JsonResponse
    {
        $workspace = Workspace::first();
        $updatedInvitation = $action->execute((string) $workspace->id, $request->all());

        return response()->json([
            'success' => true,
            'message' => 'Đã lưu thay đổi CMS thiệp cưới thành công!',
            'invitation' => $updatedInvitation,
        ]);
    }

    public function settings(): Response
    {
        return Inertia::render('Wedding/Settings');
    }

    public function documents(): Response
    {
        return Inertia::render('Wedding/Documents');
    }

    public function exportGuests(ExportGuestListAction $action): StreamedResponse
    {
        $workspace = Workspace::first();

        return $action->execute((string) ($workspace->id ?? 1));
    }

    public function exportBudget(ExportBudgetAction $action): StreamedResponse
    {
        $workspace = Workspace::first();

        return $action->execute((string) ($workspace->id ?? 1));
    }
}
