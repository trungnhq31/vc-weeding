<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Guest;
use App\Modules\Budget\Models\BudgetItem;
use App\Modules\Workspace\Models\Workspace;
use Inertia\Inertia;
use Inertia\Response;

class PlannerDashboardController extends Controller
{
    public function index(): Response
    {
        $workspaces = Workspace::withCount(['guests', 'milestones'])
            ->with('invitation.template')
            ->latest()
            ->get();

        $totalManagedBudget = BudgetItem::sum('actual_amount');
        $totalGuestsCount = Guest::count();

        return Inertia::render('Wedding/PlannerDashboard', [
            'workspaces' => $workspaces,
            'totalManagedBudget' => $totalManagedBudget,
            'totalGuestsCount' => $totalGuestsCount,
            'totalWorkspacesCount' => $workspaces->count(),
        ]);
    }
}
