<?php

declare(strict_types=1);

namespace App\Modules\Budget\Actions;

use App\Modules\Budget\Models\BudgetItem;

class CreateBudgetItemAction
{
    public function execute(array $data): BudgetItem
    {
        return BudgetItem::create([
            'workspace_id' => $data['workspace_id'],
            'category_name' => $data['category_name'],
            'item_name' => $data['item_name'],
            'estimated_amount' => $data['estimated_amount'] ?? 0,
            'actual_amount' => $data['actual_amount'] ?? 0,
            'deposit_paid' => $data['deposit_paid'] ?? 0,
            'payment_status' => $data['payment_status'] ?? 'unpaid',
            'due_payment_date' => $data['due_payment_date'] ?? null,
            'notes' => $data['notes'] ?? null,
        ]);
    }
}
