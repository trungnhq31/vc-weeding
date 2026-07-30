<?php

declare(strict_types=1);

namespace App\Modules\Task\Models;

use App\Modules\Workspace\Concerns\HasWorkspace;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasUlids, HasWorkspace;

    protected $fillable = [
        'workspace_id',
        'category',
        'title',
        'description',
        'status',
        'priority',
        'due_date',
        'estimated_cost',
        'actual_cost',
        'is_reminded',
    ];

    protected function casts(): array
    {
        return [
            'due_date' => 'datetime',
            'estimated_cost' => 'decimal:2',
            'actual_cost' => 'decimal:2',
            'is_reminded' => 'boolean',
        ];
    }
}
