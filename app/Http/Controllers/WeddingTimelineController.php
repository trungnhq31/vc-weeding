<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\WeddingMilestone;
use App\Models\WeddingTask;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Inertia\Response;

class WeddingTimelineController extends Controller
{
    public function index(): Response
    {
        $milestones = WeddingMilestone::with(['tasks'])
            ->orderBy('order')
            ->get();

        $totalTasks = WeddingTask::count();
        $completedTasks = WeddingTask::where('is_completed', true)->count();
        $overallProgress = $totalTasks > 0 ? (int) round(($completedTasks / $totalTasks) * 100) : 0;

        $totalBudgetAllocated = WeddingMilestone::sum('budget_allocated');
        $totalBudgetSpent = WeddingMilestone::sum('budget_spent');

        return Inertia::render('Wedding/Timeline', [
            'milestones' => $milestones,
            'stats' => [
                'overallProgress' => $overallProgress,
                'totalTasks' => $totalTasks,
                'completedTasks' => $completedTasks,
                'totalBudgetAllocated' => (float) $totalBudgetAllocated,
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
}
