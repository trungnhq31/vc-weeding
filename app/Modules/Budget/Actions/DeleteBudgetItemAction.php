<?php

declare(strict_types=1);

namespace App\Modules\Budget\Actions;

use App\Modules\Budget\Models\BudgetItem;

class DeleteBudgetItemAction
{
    public function execute(string $budgetItemId): bool
    {
        $item = BudgetItem::findOrFail($budgetItemId);

        return (bool) $item->delete();
    }
}
