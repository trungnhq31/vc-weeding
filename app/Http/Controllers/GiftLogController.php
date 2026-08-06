<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\WeddingGiftLog;
use App\Modules\Workspace\Models\Workspace;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class GiftLogController extends Controller
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

        $giftLogs = WeddingGiftLog::where('workspace_id', $workspaceId)
            ->latest()
            ->get();

        $totalAmount = (float) $giftLogs->sum('amount');
        $cashCount = $giftLogs->where('gift_type', 'cash')->count();
        $transferCount = $giftLogs->where('gift_type', 'transfer')->count();

        return Inertia::render('Wedding/GiftLog', [
            'workspace' => $workspace,
            'giftLogs' => $giftLogs,
            'stats' => [
                'totalAmount' => $totalAmount,
                'totalGivers' => $giftLogs->count(),
                'cashCount' => $cashCount,
                'transferCount' => $transferCount,
                'thankYouSentCount' => $giftLogs->where('thank_you_sent', true)->count(),
            ],
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $workspaceId = $this->getActiveWorkspaceId($request);

        $validated = $request->validate([
            'giver_name' => 'required|string|max:255',
            'relationship' => 'required|string|in:groom_friend,bride_friend,family,colleague,other',
            'amount' => 'required|numeric|min:0',
            'gift_type' => 'required|string|in:cash,transfer,gift_item',
            'gift_item_description' => 'nullable|string|max:255',
            'wish_message' => 'nullable|string|max:1000',
        ]);

        $giftLog = WeddingGiftLog::create(array_merge($validated, [
            'workspace_id' => $workspaceId,
            'thank_you_sent' => false,
        ]));

        return response()->json([
            'success' => true,
            'message' => 'Đã ghi nhận mừng cưới vào Sổ Vàng thành công!',
            'giftLog' => $giftLog,
        ]);
    }

    public function toggleThankYou(string $id): JsonResponse
    {
        $giftLog = WeddingGiftLog::findOrFail($id);
        $giftLog->update(['thank_you_sent' => ! $giftLog->thank_you_sent]);

        return response()->json([
            'success' => true,
            'thank_you_sent' => $giftLog->thank_you_sent,
            'message' => $giftLog->thank_you_sent ? 'Đã đánh dấu gửi lời Cảm Ơn!' : 'Chưa gửi lời cảm ơn',
        ]);
    }

    public function destroy(string $id): JsonResponse
    {
        $giftLog = WeddingGiftLog::findOrFail($id);
        $giftLog->delete();

        return response()->json([
            'success' => true,
            'message' => 'Đã xóa mục ghi nhận khỏi Sổ Vàng thành công',
        ]);
    }
}
