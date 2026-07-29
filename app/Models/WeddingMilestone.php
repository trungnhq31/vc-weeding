<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\MilestoneStatus;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class WeddingMilestone extends Model
{
    use HasFactory, HasUlids;

    protected $fillable = [
        'title',
        'slug',
        'timeframe',
        'icon',
        'order',
        'status',
        'summary',
        'notes',
        'attachments',
        'budget_allocated',
        'budget_spent',
    ];

    protected function casts(): array
    {
        return [
            'status' => MilestoneStatus::class,
            'attachments' => 'array',
            'budget_allocated' => 'decimal:2',
            'budget_spent' => 'decimal:2',
            'order' => 'integer',
        ];
    }

    public function tasks(): HasMany
    {
        return $this->hasMany(WeddingTask::class, 'milestone_id')->orderBy('created_at');
    }

    public function getProgressPercentageAttribute(): int
    {
        $total = $this->tasks->count();
        if ($total === 0) {
            return $this->status === MilestoneStatus::Completed ? 100 : 0;
        }

        $completed = $this->tasks->where('is_completed', true)->count();

        return (int) round(($completed / $total) * 100);
    }
}
