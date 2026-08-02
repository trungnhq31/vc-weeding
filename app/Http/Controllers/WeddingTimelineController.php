<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\WeddingMilestone;
use App\Models\WeddingTask;
use App\Modules\Workspace\Models\Workspace;
use Database\Seeders\WeddingMilestoneSeeder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Inertia\Response;

class WeddingTimelineController extends Controller
{
    public function index(): Response
    {
        // Auto-seed default milestone tasks only if table is completely empty
        if (WeddingMilestone::count() === 0) {
            (new WeddingMilestoneSeeder)->run();
        }

        $milestones = WeddingMilestone::with(['tasks'])
            ->orderBy('order')
            ->get();

        $totalTasks = WeddingTask::count();
        $completedTasks = WeddingTask::where('is_completed', true)->count();
        $overallProgress = $totalTasks > 0 ? (int) round(($completedTasks / $totalTasks) * 100) : 0;

        $workspace = Workspace::latest()->first();

        $budgetCap = $workspace ? (float) $workspace->budget_cap : 250000000.0;
        $totalBudgetAllocated = WeddingMilestone::sum('budget_allocated');
        $totalBudgetSpent = WeddingMilestone::sum('budget_spent');

        return Inertia::render('Wedding/Timeline', [
            'milestones' => $milestones,
            'workspace' => $workspace ? [
                'name' => $workspace->name,
                'groom_name' => $workspace->groom_name ?? 'Nguyễn Hoàng Quốc Trung',
                'bride_name' => $workspace->bride_name ?? 'Lê Thị Hồng Vân',
                'wedding_date' => $workspace->wedding_date ? (is_string($workspace->wedding_date) ? $workspace->wedding_date : $workspace->wedding_date->format('Y-m-d')) : '2026-10-24',
                'wedding_location' => $workspace->wedding_location ?? 'TP. Hồ Chí Minh',
                'venue_name' => $workspace->venue_name,
                'budget_cap' => $budgetCap,
                'estimated_guests' => $workspace->estimated_guests ?? 200,
                'wedding_hashtag' => $workspace->wedding_hashtag ?? '#TrungVanWedding2026',
                'couple_story' => $workspace->couple_story,
            ] : null,
            'stats' => [
                'overallProgress' => $overallProgress,
                'totalTasks' => $totalTasks,
                'completedTasks' => $completedTasks,
                'totalBudgetAllocated' => $budgetCap > 0 ? $budgetCap : (float) $totalBudgetAllocated,
                'totalBudgetSpent' => (float) $totalBudgetSpent,
            ],
        ]);
    }

    public function toggleTask(Request $request, string $taskId): JsonResponse
    {
        $task = WeddingTask::findOrFail($taskId);
        $task->update([
            'is_completed' => ! $task->is_completed,
        ]);

        $milestone = $task->milestone->load('tasks');

        return response()->json([
            'success' => true,
            'task' => $task,
            'milestoneProgress' => $milestone->progress_percentage,
        ]);
    }

    public function updateTaskDetails(Request $request, string $taskId): JsonResponse
    {
        $task = WeddingTask::findOrFail($taskId);

        $validated = $request->validate([
            'notes' => 'nullable|string',
            'vendor_info' => 'nullable|string|max:255',
            'actual_cost' => 'nullable|numeric|min:0',
        ]);

        $task->update($validated);

        // Recalculate milestone actual spent
        $milestone = $task->milestone;
        $totalMilestoneSpent = $milestone->tasks()->sum('actual_cost');
        $milestone->update(['budget_spent' => $totalMilestoneSpent]);

        return response()->json([
            'success' => true,
            'task' => $task->fresh(),
            'milestoneSpent' => (float) $totalMilestoneSpent,
        ]);
    }

    public function uploadTaskImage(Request $request, string $taskId): JsonResponse
    {
        $task = WeddingTask::findOrFail($taskId);

        $request->validate([
            'image' => 'required|image|mimes:jpeg,jpg,png,webp,gif|max:10240',
        ]);

        $path = $request->file('image')->store('wedding/attachments', 'public');
        $url = Storage::url($path);

        $existing = $task->attachments ?? [];
        $existing[] = $url;

        $task->update(['attachments' => $existing]);

        return response()->json([
            'success' => true,
            'url' => $url,
            'attachments' => $task->fresh()->attachments,
        ]);
    }

    public function deleteTaskImage(Request $request, string $taskId): JsonResponse
    {
        $task = WeddingTask::findOrFail($taskId);

        $request->validate([
            'url' => 'required|string',
        ]);

        $url = $request->input('url');
        $existing = $task->attachments ?? [];

        $updated = array_values(array_filter($existing, fn ($item) => $item !== $url));

        // Delete physical file if under storage
        $relativePath = str_replace('/storage/', '', $url);
        if (Storage::disk('public')->exists($relativePath)) {
            Storage::disk('public')->delete($relativePath);
        }

        $task->update(['attachments' => $updated]);

        return response()->json([
            'success' => true,
            'attachments' => $updated,
        ]);
    }

    public function aiPersonalizeTimeline(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'groom_name' => 'nullable|string|max:255',
            'bride_name' => 'nullable|string|max:255',
            'wedding_date' => 'nullable|string',
            'wedding_location' => 'nullable|string|max:255',
            'venue_type' => 'nullable|string|max:255',
            'religion' => 'nullable|string|max:255',
            'style' => 'nullable|string|max:255',
            'budget_range' => 'nullable|string|max:255',
            'estimated_guests' => 'nullable|string|max:255',
            'special_requirements' => 'nullable|array',
        ]);

        $workspace = Workspace::latest()->first();

        if ($workspace) {
            $workspace->update([
                'groom_name' => $validated['groom_name'] ?? $workspace->groom_name,
                'bride_name' => $validated['bride_name'] ?? $workspace->bride_name,
                'wedding_date' => $validated['wedding_date'] ?? $workspace->wedding_date,
                'wedding_location' => $validated['wedding_location'] ?? $workspace->wedding_location,
                'venue_name' => $validated['venue_type'] ?? $workspace->venue_name,
            ]);
        }

        $milestone4 = WeddingMilestone::where('order', 4)->first();
        if ($milestone4) {
            $religion = $validated['religion'] ?? '';
            if (str_contains($religion, 'Công Giáo')) {
                $milestone4->tasks()->updateOrCreate(
                    ['title' => 'Hoàn thành khóa học Giáo Lý Hôn Nhân & Đăng ký Lễ Hôn Phối tại Nhà Thờ'],
                    [
                        'estimated_cost' => 3000000,
                        'actual_cost' => 0,
                        'notes' => 'Nghi lễ Thánh Lễ Hôn Phối trang trọng tại Nhà Thờ.',
                        'vendor_info' => 'Hội Thánh Công Giáo / Giáo Xứ',
                        'subtasks' => [
                            ['id' => 'sub-cg1', 'title' => 'Học và nhận chứng chỉ Lớp Giáo Lý Hôn Nhân (3-6 tháng)', 'is_completed' => false],
                            ['id' => 'sub-cg2', 'title' => 'Trình diện Cha Xứ & nộp giấy chứng nhận Rửa Tội, Thêm Sức', 'is_completed' => false],
                            ['id' => 'sub-cg3', 'title' => 'Thống nhất ca đoàn & hoa tươi trang trí Nhà Thờ', 'is_completed' => false],
                        ],
                    ]
                );
            } elseif (str_contains($religion, 'Phật')) {
                $milestone4->tasks()->updateOrCreate(
                    ['title' => 'Đăng ký & Chuẩn bị Lễ Hằng Thuận tại Chùa'],
                    [
                        'estimated_cost' => 5000000,
                        'actual_cost' => 0,
                        'notes' => 'Nghi lễ Hằng Thuận trước sự chứng minh của Chư Tăng Ni.',
                        'vendor_info' => 'Tự Viện / Chùa',
                        'subtasks' => [
                            ['id' => 'sub-pg1', 'title' => 'Thỉnh ý Quý Chư Tăng / Ni Sư trụ trì Chùa', 'is_completed' => false],
                            ['id' => 'sub-pg2', 'title' => 'Chốt ngày giờ làm Lễ Hằng Thuận & nghe giảng đạo nghĩa phu thê', 'is_completed' => false],
                            ['id' => 'sub-pg3', 'title' => 'Chuẩn bị mâm cỗ chay đãi Chư Tăng & họ hàng hai bên', 'is_completed' => false],
                        ],
                    ]
                );
            }
        }

        $milestones = WeddingMilestone::with(['tasks'])->orderBy('order')->get();

        return response()->json([
            'success' => true,
            'message' => 'AI đã phân tích bối cảnh Đám cưới Việt Nam & cá nhân hóa lộ trình thành công!',
            'milestones' => $milestones,
            'workspace' => $workspace,
        ]);
    }

    public function getTaskAiRecommendation(Request $request, string $taskId, \App\Services\WeddingTaskExecutionService $executionService): JsonResponse
    {
        $task = WeddingTask::findOrFail($taskId);
        $result = $executionService->getTaskAiRecommendation($task);

        return response()->json($result);
    }

    public function executeTaskAction(Request $request, string $taskId, \App\Services\WeddingTaskExecutionService $executionService): JsonResponse
    {
        $task = WeddingTask::findOrFail($taskId);
        $result = $executionService->executeTaskAction($task, $request->all());

        return response()->json($result);
    }

    public function autoCompleteMilestoneAi(Request $request, string $milestoneId, \App\Services\WeddingTaskExecutionService $executionService): JsonResponse
    {
        $milestone = WeddingMilestone::findOrFail($milestoneId);
        $result = $executionService->autoCompleteMilestoneWithAi($milestone);

        return response()->json($result);
    }
}
