<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WeddingTask extends Model
{
    use HasFactory, HasUlids;

    protected $fillable = [
        'milestone_id',
        'title',
        'notes',
        'vendor_info',
        'attachments',
        'is_completed',
        'due_date',
        'estimated_cost',
        'actual_cost',
    ];

    protected function casts(): array
    {
        return [
            'is_completed' => 'boolean',
            'attachments' => 'array',
            'due_date' => 'date',
            'estimated_cost' => 'decimal:2',
            'actual_cost' => 'decimal:2',
        ];
    }

    public function milestone(): BelongsTo
    {
        return $this->belongsTo(WeddingMilestone::class, 'milestone_id');
    }
}
