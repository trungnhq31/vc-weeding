<?php

declare(strict_types=1);

namespace App\Modules\Workspace\Concerns;

use App\Modules\Workspace\Models\Workspace;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

trait HasWorkspace
{
    public static function bootHasWorkspace(): void
    {
        static::creating(function ($model) {
            if (!$model->workspace_id && session()->has('active_workspace_id')) {
                $model->workspace_id = session()->get('active_workspace_id');
            }
        });
    }

    public function workspace(): BelongsTo
    {
        return $this->belongsTo(Workspace::class);
    }

    public function scopeForWorkspace(Builder $query, string $workspaceId): Builder
    {
        return $query->where('workspace_id', $workspaceId);
    }
}
