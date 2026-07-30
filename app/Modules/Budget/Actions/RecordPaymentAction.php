<?php

declare(strict_types=1);

namespace App\Modules\Budget\Actions;

use App\Modules\Budget\Models\BudgetItem;

class RecordPaymentAction
{
    public function execute(string $budgetItemId, float $amountPaid): BudgetItem
    {
        $item = BudgetItem::findOrFail($budgetItemId);
        $newDeposit = (float) $item->deposit_paid + $amountPaid;
        $actual = (float) $item->actual_amount;

        $status = 'deposit_paid';
        if ($newDeposit >= $actual && $actual > 0) {
            $status = 'fully_paid';
        }

        $item->update([
            'deposit_paid' => $newDeposit,
            'payment_status' => $status,
        ]);

        return $item;
    }
}
