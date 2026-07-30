<?php

declare(strict_types=1);

namespace App\Modules\Budget\Models;

use App\Modules\Workspace\Concerns\HasWorkspace;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;

class BudgetItem extends Model
{
    use HasUlids, HasWorkspace;

    protected $table = 'budget_items';

    protected $fillable = [
        'workspace_id',
        'category_name',
        'item_name',
        'estimated_amount',
        'actual_amount',
        'deposit_paid',
        'payment_status',
        'due_payment_date',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'estimated_amount' => 'decimal:2',
            'actual_amount' => 'decimal:2',
            'deposit_paid' => 'decimal:2',
            'due_payment_date' => 'date',
        ];
    }
}
