<?php

declare(strict_types=1);

namespace App\Modules\Guest\Models;

use App\Models\Guest;
use App\Modules\Workspace\Concerns\HasWorkspace;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Table extends Model
{
    use HasUlids, HasWorkspace;

    protected $fillable = [
        'workspace_id',
        'table_name',
        'capacity',
        'zone_name',
        'shape',
    ];

    protected function casts(): array
    {
        return [
            'capacity' => 'integer',
        ];
    }

    public function guests(): HasMany
    {
        return $this->hasMany(Guest::class, 'table_id');
    }

    public function getCurrentSeatedCountAttribute(): int
    {
        return (int) $this->guests()->sum('confirmed_count') ?: (int) $this->guests()->count();
    }

    public function getIsOverCapacityAttribute(): bool
    {
        return $this->current_seated_count > $this->capacity;
    }
}
