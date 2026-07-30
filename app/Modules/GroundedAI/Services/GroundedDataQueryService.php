<?php

declare(strict_types=1);

namespace App\Modules\GroundedAI\Services;

use App\Models\Guest;
use App\Modules\Budget\Services\CashFlowCalculatorService;
use App\Modules\Guest\Models\Table;
use App\Modules\Task\Models\Task;
use App\Modules\Vendor\Services\VendorCrmService;
use App\Modules\Workspace\Models\Workspace;

class GroundedDataQueryService
{
    public function __construct(
        protected CashFlowCalculatorService $cashFlowCalculator = new CashFlowCalculatorService,
        protected VendorCrmService $vendorCrmService = new VendorCrmService
    ) {}

    /**
     * Get all grounded data for a workspace with ZERO hallucination guarantee.
     *
     * @return array{
     *     workspace: array<string, mixed>,
     *     budget: array<string, mixed>,
     *     tasks: array<string, mixed>,
     *     vendors: array<string, mixed>,
     *     guests: array<string, mixed>
     * }
     */
    public function getWorkspaceMetrics(string $workspaceId): array
    {
        $workspace = Workspace::find($workspaceId);
        $workspaceData = [
            'id' => $workspaceId,
            'name' => $workspace?->name ?? 'Default Workspace',
            'budget_cap' => (float) ($workspace?->budget_cap ?? 0.00),
            'wedding_date' => $workspace?->wedding_date?->format('Y-m-d'),
        ];

        // 1. Budget Overview
        $budgetOverview = $this->cashFlowCalculator->calculateOverview($workspaceId);

        // 2. Tasks Overview
        $allTasks = Task::forWorkspace($workspaceId)->get();
        $totalTasks = $allTasks->count();
        $completedTasks = $allTasks->where('status', 'done')->count();
        $overdueTasks = $allTasks->filter(function (Task $task) {
            return $task->status !== 'done' && $task->due_date && $task->due_date->isPast();
        });

        $tasksOverview = [
            'total_tasks' => $totalTasks,
            'completed_tasks' => $completedTasks,
            'pending_tasks' => $totalTasks - $completedTasks,
            'progress_percentage' => $totalTasks > 0 ? (int) round(($completedTasks / $totalTasks) * 100) : 0,
            'overdue_count' => $overdueTasks->count(),
            'overdue_tasks' => $overdueTasks->values()->map(fn (Task $t) => [
                'id' => $t->id,
                'title' => $t->title,
                'category' => $t->category,
                'priority' => $t->priority,
                'due_date' => $t->due_date?->format('Y-m-d'),
            ])->toArray(),
        ];

        // 3. Vendors Overview
        $vendorSummary = $this->vendorCrmService->getSummary($workspaceId);

        // 4. Guests & Seating Overview
        $allGuests = Guest::forWorkspace($workspaceId)->get();
        $totalGuests = $allGuests->count();
        $attendingGuests = $allGuests->filter(fn (Guest $g) => in_array($g->rsvp_status?->value ?? $g->rsvp_status, ['attending', 'confirmed', 'yes']))->count();
        $unseatedGuests = $allGuests->whereNull('table_id')->count();

        $allTables = Table::forWorkspace($workspaceId)->get();
        $overCapacityTablesCount = $allTables->filter(fn (Table $table) => $table->is_over_capacity)->count();

        $guestsOverview = [
            'total_guests' => $totalGuests,
            'attending_guests' => $attendingGuests,
            'unseated_guests' => $unseatedGuests,
            'total_tables' => $allTables->count(),
            'over_capacity_tables_count' => $overCapacityTablesCount,
        ];

        return [
            'workspace' => $workspaceData,
            'budget' => $budgetOverview,
            'tasks' => $tasksOverview,
            'vendors' => $vendorSummary,
            'guests' => $guestsOverview,
        ];
    }
}
