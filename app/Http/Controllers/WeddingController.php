<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Guest;
use App\Models\WeddingMemory;
use App\Models\Wish;
use App\Modules\Workspace\Models\Workspace;
use App\Modules\Budget\Actions\ExportBudgetAction;
use App\Modules\Guest\Actions\ExportGuestListAction;
use App\Modules\Invitation\Actions\UpdateInvitationCmsAction;
use App\Modules\Invitation\Models\InvitationTemplate;
use App\Modules\Invitation\Models\WorkspaceInvitation;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
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

    public function budget(\App\Services\WeddingBudgetAllocationService $allocationService): Response
    {
        $workspace = Workspace::latest()->first();
        $budgetCap = (float) ($workspace->budget_cap ?? 250000000.00);
        $guests = (int) ($workspace->estimated_guests ?? 200);

        $breakdown = $allocationService->calculateStandardBreakdown($budgetCap, $guests);
        $recommendedVenues = $allocationService->getRecommendedVenues($budgetCap, $guests, $workspace->wedding_location ?? 'TP. Hồ Chí Minh');

        return Inertia::render('Wedding/Budget', [
            'workspace' => $workspace,
            'budgetBreakdown' => $breakdown,
            'recommendedVenues' => $recommendedVenues,
        ]);
    }

    public function selectVenue(Request $request, \App\Services\WeddingBudgetAllocationService $allocationService): JsonResponse
    {
        $validated = $request->validate([
            'venue_name' => 'required|string|max:255',
            'deposit_amount' => 'nullable|numeric|min:0',
        ]);

        $result = $allocationService->selectVenue(
            $validated['venue_name'],
            (float) ($validated['deposit_amount'] ?? 35000000.00)
        );

        return response()->json($result);
    }

    public function guests(): Response
    {
        $workspace = Workspace::latest()->first();
        if (! $workspace) {
            $workspace = Workspace::create([
                'name' => 'Đám Cưới Quốc Trung & Hồng Vân',
                'slug' => 'quoc-trung-hong-van-'.Str::random(5),
                'groom_name' => 'Nguyễn Hoàng Quốc Trung',
                'bride_name' => 'Lê Thị Hồng Vân',
                'budget_cap' => 250000000.00,
                'estimated_guests' => 200,
                'wedding_date' => '2026-10-24',
                'wedding_location' => 'TP. Hồ Chí Minh',
            ]);
        }

        if (Guest::where('workspace_id', $workspace->id)->count() === 0) {
            $samples = [
                ['name' => 'Nguyễn Văn Anh', 'group' => 'Nhà Trai', 'phone' => '0901234567', 'table_name' => 'Bàn VIP 1 (Họ Hàng)', 'dietary_preference' => 'Không cay', 'rsvp_status' => 'attending', 'notes' => 'Thêm bởi: Chú rể'],
                ['name' => 'Trần Thị Bích', 'group' => 'Nhà Gái', 'phone' => '0909876543', 'table_name' => 'Bàn VIP 1 (Họ Hàng)', 'dietary_preference' => 'Bình thường', 'rsvp_status' => 'attending', 'notes' => 'Thêm bởi: Cô dâu'],
                ['name' => 'Lê Hoàng Nam', 'group' => 'Bạn Chú Rể', 'phone' => '0912345678', 'table_name' => 'Bàn Bạn Học 1', 'dietary_preference' => 'Ăn chay', 'rsvp_status' => 'attending', 'notes' => 'Thêm bởi: Bạn học'],
                ['name' => 'Phạm Minh Tâm', 'group' => 'Đồng Nghiệp', 'phone' => '0987654321', 'table_name' => 'Bàn Công Ty', 'dietary_preference' => 'Bình thường', 'rsvp_status' => 'pending', 'notes' => 'Thêm bởi: Bạn đồng nghiệp'],
                ['name' => 'Đặng Tuấn Kiệt', 'group' => 'Họ Hàng Dâu', 'phone' => '0933445566', 'table_name' => 'Chưa xếp', 'dietary_preference' => '-', 'rsvp_status' => 'attending', 'notes' => 'Thêm qua Share Link: Mẹ Cô Dâu'],
                ['name' => 'Vũ Quốc Huy', 'group' => 'Bạn Chú Rể', 'phone' => '0977889900', 'table_name' => 'Chưa xếp', 'dietary_preference' => 'Ăn chay', 'rsvp_status' => 'attending', 'notes' => 'Thêm qua Share Link: Phù rể'],
            ];

            foreach ($samples as $s) {
                Guest::create([
                    'workspace_id' => $workspace->id,
                    'name' => $s['name'],
                    'group' => $s['group'],
                    'phone' => $s['phone'],
                    'table_name' => $s['table_name'],
                    'dietary_preference' => $s['dietary_preference'],
                    'rsvp_status' => $s['rsvp_status'],
                    'notes' => $s['notes'],
                    'guest_slug' => Str::slug($s['name']).'-'.Str::random(4),
                ]);
            }
        }

        $guests = Guest::where('workspace_id', $workspace->id)->latest()->get();
        $shareUrl = url('/wedding/share-guest-list/'.($workspace->slug ?? 'quoc-trung-hong-van'));

        return Inertia::render('Wedding/Guests', [
            'workspace' => $workspace,
            'dbGuests' => $guests,
            'shareUrl' => $shareUrl,
        ]);
    }

    public function showSharedGuestList(string $token): Response
    {
        $workspace = Workspace::where('slug', $token)->first() ?? Workspace::latest()->first();
        $recentGuests = Guest::where('workspace_id', $workspace->id ?? null)->latest()->take(10)->get();

        return Inertia::render('Wedding/SharedGuestList', [
            'workspace' => $workspace,
            'recentGuests' => $recentGuests,
            'shareToken' => $token,
        ]);
    }

    public function storeSharedGuest(Request $request, string $token): JsonResponse
    {
        $workspace = Workspace::where('slug', $token)->first() ?? Workspace::latest()->first();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:50',
            'group' => 'required|string|max:255',
            'added_by' => 'nullable|string|max:255',
            'dietary_preference' => 'nullable|string|max:255',
            'estimated_count' => 'nullable|integer|min:1',
        ]);

        $addedByText = $validated['added_by'] ? "Thêm qua Share Link bởi: {$validated['added_by']}" : 'Thêm qua Share Link người thân';

        $guest = Guest::create([
            'workspace_id' => $workspace->id,
            'name' => $validated['name'],
            'phone' => $validated['phone'] ?? null,
            'group' => $validated['group'],
            'dietary_preference' => $validated['dietary_preference'] ?? '-',
            'estimated_count' => $validated['estimated_count'] ?? 1,
            'rsvp_status' => 'attending',
            'table_name' => 'Chưa xếp',
            'notes' => $addedByText,
            'guest_slug' => Str::slug($validated['name']).'-'.Str::random(4),
        ]);

        return response()->json([
            'success' => true,
            'message' => "Cảm ơn! Đã thêm khách mời [{$guest->name}] vào danh sách đám cưới thành công.",
            'guest' => $guest,
        ]);
    }

    public function quickStoreGuest(Request $request): JsonResponse
    {
        $workspace = Workspace::latest()->first();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:50',
            'group' => 'nullable|string|max:255',
            'dietary_preference' => 'nullable|string|max:255',
            'notes' => 'nullable|string|max:255',
        ]);

        $guest = Guest::create([
            'workspace_id' => $workspace->id,
            'name' => $validated['name'],
            'phone' => $validated['phone'] ?? null,
            'group' => $validated['group'] ?? 'Nhà Trai',
            'dietary_preference' => $validated['dietary_preference'] ?? '-',
            'rsvp_status' => 'attending',
            'table_name' => 'Chưa xếp',
            'notes' => $validated['notes'] ?? 'Thêm nhanh từ Workspace',
            'guest_slug' => Str::slug($validated['name']).'-'.Str::random(4),
        ]);

        return response()->json([
            'success' => true,
            'message' => "Đã thêm nhanh khách mời [{$guest->name}]!",
            'guest' => $guest,
        ]);
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
