<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\WeddingMemory;
use App\Modules\Workspace\Models\Workspace;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Inertia\Response;

class GalleryController extends Controller
{
    protected function getActiveWorkspaceId(Request $request): string
    {
        $workspaceId = $request->input('workspace_id')
            ?? session()->get('active_workspace_id');

        if (! $workspaceId) {
            $workspace = Workspace::latest()->first();
            if (! $workspace) {
                $workspace = Workspace::create([
                    'name' => 'Đám Cưới Nguyễn Hoàng Quốc Trung & Lê Thị Hồng Vân',
                    'slug' => 'quoc-trung-hong-van',
                    'groom_name' => 'Nguyễn Hoàng Quốc Trung',
                    'bride_name' => 'Lê Thị Hồng Vân',
                    'wedding_date' => '2026-10-24',
                    'budget_cap' => 350000000.00,
                ]);
            }
            $workspaceId = $workspace->id;
            session()->put('active_workspace_id', $workspaceId);
        }

        return (string) $workspaceId;
    }

    public function index(Request $request): Response
    {
        $workspaceId = $this->getActiveWorkspaceId($request);
        $workspace = Workspace::find($workspaceId);

        $memories = WeddingMemory::where('workspace_id', $workspaceId)
            ->latest()
            ->get();

        $shareUrl = url('/wedding/shared-gallery/'.($workspace->slug ?? 'quoc-trung-hong-van'));

        return Inertia::render('Wedding/Gallery', [
            'workspace' => $workspace,
            'memories' => $memories,
            'shareUrl' => $shareUrl,
            'stats' => [
                'totalPhotos' => $memories->count(),
                'preWeddingCount' => $memories->where('category', 'pre_wedding')->count(),
                'engagementCount' => $memories->where('category', 'engagement')->count(),
                'weddingDayCount' => $memories->where('category', 'wedding_day')->count(),
                'guestUploadCount' => $memories->where('category', 'guest_upload')->count(),
                'pinnedCount' => $memories->where('is_pinned', true)->count(),
            ],
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $workspaceId = $this->getActiveWorkspaceId($request);

        $validated = $request->validate([
            'category' => 'required|string|in:pre_wedding,engagement,wedding_day,guest_upload,honeymoon',
            'title' => 'nullable|string|max:255',
            'description' => 'nullable|string|max:1000',
            'uploader_name' => 'nullable|string|max:255',
            'images' => 'required|array',
            'images.*' => 'image|mimes:jpeg,jpg,png,webp,gif|max:15360',
        ]);

        $uploaded = [];
        $uploaderName = $validated['uploader_name'] ?? 'Chú Rể & Cô Dâu';

        foreach ($request->file('images') as $file) {
            $path = $file->store('wedding/gallery', 'public');
            $url = Storage::url($path);

            $memory = WeddingMemory::create([
                'workspace_id' => $workspaceId,
                'uploader_name' => $uploaderName,
                'category' => $validated['category'],
                'title' => $validated['title'] ?? 'Kỷ niệm tiệc cưới',
                'description' => $validated['description'] ?? null,
                'image_url' => $url,
                'is_approved' => true,
                'is_pinned' => false,
            ]);

            $uploaded[] = $memory;
        }

        return response()->json([
            'success' => true,
            'message' => 'Đã tải lên '.count($uploaded).' ảnh thành công!',
            'memories' => $uploaded,
        ]);
    }

    public function togglePin(string $id): JsonResponse
    {
        $memory = WeddingMemory::findOrFail($id);
        $memory->update(['is_pinned' => ! $memory->is_pinned]);

        return response()->json([
            'success' => true,
            'is_pinned' => $memory->is_pinned,
            'message' => $memory->is_pinned ? 'Đã ghim ảnh lên ưu tiên!' : 'Đã bỏ ghim ảnh',
        ]);
    }

    public function toggleApproval(string $id): JsonResponse
    {
        $memory = WeddingMemory::findOrFail($id);
        $memory->update(['is_approved' => ! $memory->is_approved]);

        return response()->json([
            'success' => true,
            'is_approved' => $memory->is_approved,
            'message' => $memory->is_approved ? 'Đã duyệt ảnh xuất hiện công khai' : 'Đã ẩn ảnh khỏi gallery công khai',
        ]);
    }

    public function destroy(string $id): JsonResponse
    {
        $memory = WeddingMemory::findOrFail($id);

        if ($memory->image_url) {
            $relativePath = str_replace('/storage/', '', $memory->image_url);
            if (Storage::disk('public')->exists($relativePath)) {
                Storage::disk('public')->delete($relativePath);
            }
        }

        $memory->delete();

        return response()->json([
            'success' => true,
            'message' => 'Đã xóa ảnh khỏi gallery thành công',
        ]);
    }

    public function publicShow(string $slug): Response
    {
        $workspace = Workspace::where('slug', $slug)->first() ?? Workspace::latest()->first();

        $memories = WeddingMemory::where('workspace_id', $workspace->id ?? null)
            ->where('is_approved', true)
            ->latest()
            ->get();

        return Inertia::render('Wedding/PublicGallery', [
            'workspace' => $workspace,
            'memories' => $memories,
            'shareSlug' => $slug,
        ]);
    }

    public function publicGuestUpload(Request $request, string $slug): JsonResponse
    {
        $workspace = Workspace::where('slug', $slug)->first() ?? Workspace::latest()->first();

        $validated = $request->validate([
            'uploader_name' => 'required|string|max:255',
            'description' => 'nullable|string|max:500',
            'image' => 'required|image|mimes:jpeg,jpg,png,webp,gif|max:10240',
        ]);

        $path = $request->file('image')->store('wedding/gallery/guest', 'public');
        $url = Storage::url($path);

        $memory = WeddingMemory::create([
            'workspace_id' => $workspace->id,
            'uploader_name' => $validated['uploader_name'],
            'category' => 'guest_upload',
            'title' => 'Ảnh gửi tặng Dâu Rể',
            'description' => $validated['description'] ?? null,
            'image_url' => $url,
            'is_approved' => true,
            'is_pinned' => false,
        ]);

        return response()->json([
            'success' => true,
            'message' => "Cảm ơn {$memory->uploader_name}! Đã tải ảnh lên gallery thành công.",
            'memory' => $memory,
        ]);
    }
}
