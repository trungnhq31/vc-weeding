<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\WeddingRunOfShow;
use App\Modules\Workspace\Models\Workspace;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class RunOfShowController extends Controller
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

        $items = WeddingRunOfShow::where('workspace_id', $workspaceId)
            ->orderBy('order_index', 'asc')
            ->orderBy('created_at', 'asc')
            ->get();

        // Seed default template items if empty
        if ($items->isEmpty()) {
            $defaultItems = [
                ['session_type' => 'morning_ceremony', 'time_slot' => '07:00 - 08:00', 'title' => 'Trang điểm Cô Dâu & Phù Dâu', 'person_in_charge' => 'TuArt Makeup Team', 'location_note' => 'Nhà Cô Dâu', 'order_index' => 1],
                ['session_type' => 'morning_ceremony', 'time_slot' => '08:30 - 09:30', 'title' => 'Đoàn Nhà Trai Khởi Hành Sang Rước Dâu', 'person_in_charge' => 'Bác Trưởng Đoàn', 'location_note' => 'Nhà Chú Rể ➔ Nhà Cô Dâu', 'order_index' => 2],
                ['session_type' => 'morning_ceremony', 'time_slot' => '09:30 - 10:30', 'title' => 'Lễ Gia Tiên & Trao Nữ Trang', 'person_in_charge' => 'MC & Đại Diện 2 Họ', 'location_note' => 'Phòng Thờ Gia Tiên', 'order_index' => 3],
                ['session_type' => 'evening_reception', 'time_slot' => '17:30 - 18:30', 'title' => 'Đón Khách Mời & Chụp Ảnh Thảm Đỏ', 'person_in_charge' => 'Dâu Rể & Đội Phù Dâu/Chàng', 'location_note' => 'Sảnh Tiệc Cưới White Palace', 'order_index' => 4],
                ['session_type' => 'evening_reception', 'time_slot' => '18:30 - 19:15', 'title' => 'Nghi Thức Khai Tiệc, Cắt Bánh & Rót Rượu Champagne', 'person_in_charge' => 'MC Tiệc & Ban Quản Lý Sảnh', 'location_note' => 'Sân Khấu Chính', 'order_index' => 5],
                ['session_type' => 'party', 'time_slot' => '19:30 - 20:30', 'title' => 'Giao Lưu Bàn Tiệc & Bốc Thăm Lucky Draw', 'person_in_charge' => 'Dâu Rể & MC', 'location_note' => 'Hội Trường Tiệc Cưới', 'order_index' => 6],
            ];

            foreach ($defaultItems as $item) {
                WeddingRunOfShow::create(array_merge($item, ['workspace_id' => $workspaceId]));
            }

            $items = WeddingRunOfShow::where('workspace_id', $workspaceId)
                ->orderBy('order_index', 'asc')
                ->get();
        }

        return Inertia::render('Wedding/RunOfShow', [
            'workspace' => $workspace,
            'items' => $items,
            'stats' => [
                'totalItems' => $items->count(),
                'completedCount' => $items->where('is_completed', true)->count(),
                'morningCount' => $items->where('session_type', 'morning_ceremony')->count(),
                'eveningCount' => $items->where('session_type', 'evening_reception')->count(),
            ],
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $workspaceId = $this->getActiveWorkspaceId($request);

        $validated = $request->validate([
            'session_type' => 'required|string|in:morning_ceremony,evening_reception,party',
            'time_slot' => 'required|string|max:100',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'person_in_charge' => 'nullable|string|max:255',
            'pic_phone' => 'nullable|string|max:50',
            'location_note' => 'nullable|string|max:255',
        ]);

        $maxOrder = WeddingRunOfShow::where('workspace_id', $workspaceId)->max('order_index') ?? 0;

        $item = WeddingRunOfShow::create(array_merge($validated, [
            'workspace_id' => $workspaceId,
            'order_index' => $maxOrder + 1,
            'is_completed' => false,
        ]));

        return response()->json([
            'success' => true,
            'message' => 'Đã thêm hạng mục kịch bản ngày cưới thành công!',
            'item' => $item,
        ]);
    }

    public function toggle(string $id): JsonResponse
    {
        $item = WeddingRunOfShow::findOrFail($id);
        $item->update(['is_completed' => ! $item->is_completed]);

        return response()->json([
            'success' => true,
            'is_completed' => $item->is_completed,
            'message' => $item->is_completed ? 'Đã hoàn thành hạng mục!' : 'Đã chuyển về chưa xong',
        ]);
    }

    public function destroy(string $id): JsonResponse
    {
        $item = WeddingRunOfShow::findOrFail($id);
        $item->delete();

        return response()->json([
            'success' => true,
            'message' => 'Đã xóa hạng mục khỏi kịch bản thành công',
        ]);
    }
}
