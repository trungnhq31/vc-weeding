<?php

declare(strict_types=1);

namespace App\Modules\Budget\Services;

use App\Modules\Budget\Models\BudgetItem;
use App\Modules\Workspace\Models\Workspace;

class CashFlowCalculatorService
{
    public function calculateOverview(string $workspaceId): array
    {
        $workspace = Workspace::findOrFail($workspaceId);
        $budgetCap = (float) $workspace->budget_cap;

        $items = BudgetItem::where('workspace_id', $workspaceId)->get();

        $totalEstimated = (float) $items->sum('estimated_amount');
        $totalActual = (float) $items->sum('actual_amount');
        $totalDepositPaid = (float) $items->sum('deposit_paid');
        $remainingBalance = (float) max(0, $totalActual - $totalDepositPaid);

        $overrunAmount = (float) max(0, $totalActual - $budgetCap);
        $isOverrunAlert = $totalActual > $budgetCap;

        $upcomingPayments = BudgetItem::where('workspace_id', $workspaceId)
            ->where('payment_status', '!=', 'fully_paid')
            ->whereNotNull('due_payment_date')
            ->where('due_payment_date', '<=', now()->addDays(7))
            ->orderBy('due_payment_date', 'asc')
            ->get();

        return [
            'workspace_id' => $workspaceId,
            'budget_cap' => $budgetCap,
            'currency' => $workspace->currency,
            'total_estimated' => $totalEstimated,
            'total_actual' => $totalActual,
            'total_deposit_paid' => $totalDepositPaid,
            'remaining_balance' => $remainingBalance,
            'overrun_amount' => $overrunAmount,
            'is_overrun_alert' => $isOverrunAlert,
            'upcoming_payments_count' => $upcomingPayments->count(),
            'upcoming_payments' => $upcomingPayments,
        ];
    }
}
