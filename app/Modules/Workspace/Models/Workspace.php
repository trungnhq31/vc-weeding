<?php

declare(strict_types=1);

namespace App\Modules\Workspace\Models;

use App\Models\Guest;
use App\Models\WeddingMemory;
use App\Models\WeddingMilestone;
use App\Models\Wish;
use App\Modules\Invitation\Models\WorkspaceInvitation;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Workspace extends Model
{
    use HasUlids;

    protected $fillable = [
        'name',
        'slug',
        'groom_name',
        'bride_name',
        'wedding_date',
        'wedding_location',
        'venue_name',
        'estimated_guests',
        'wedding_hashtag',
        'couple_story',
        'budget_cap',
        'ceremony_type',
        'wedding_vibe',
        'region',
        'currency',
        'subscription_plan',
        'subscription_expires_at',
        'custom_subdomain',
    ];

    protected function casts(): array
    {
        return [
            'wedding_date' => 'date',
            'budget_cap' => 'decimal:2',
            'subscription_expires_at' => 'datetime',
        ];
    }

    public function members(): HasMany
    {
        return $this->hasMany(WorkspaceMember::class);
    }

    public function invitation(): HasOne
    {
        return $this->hasOne(WorkspaceInvitation::class);
    }

    public function guests(): HasMany
    {
        return $this->hasMany(Guest::class);
    }

    public function wishes(): HasMany
    {
        return $this->hasMany(Wish::class);
    }

    public function milestones(): HasMany
    {
        return $this->hasMany(WeddingMilestone::class);
    }

    public function memories(): HasMany
    {
        return $this->hasMany(WeddingMemory::class);
    }
}
