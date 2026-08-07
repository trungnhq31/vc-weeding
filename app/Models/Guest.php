<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\RsvpStatus;
use App\Modules\Guest\Models\Table;
use App\Modules\Workspace\Concerns\HasWorkspace;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Guest extends Model
{
    use HasFactory, HasUlids, HasWorkspace;

    protected $fillable = [
        'workspace_id',
        'table_id',
        'guest_slug',
        'name',
        'salutation',
        'group',
        'estimated_count',
        'confirmed_count',
        'dietary_preference',
        'shuttle_bus',
        'qr_code_token',
        'is_checked_in',
        'checked_in_at',
        'table_name',
        'rsvp_status',
        'rsvp_ceremony',
        'rsvp_reception',
        'rsvp_afterparty',
        'notes',
    ];

    public function table(): BelongsTo
    {
        return $this->belongsTo(Table::class);
    }

    protected function casts(): array
    {
        return [
            'estimated_count' => 'integer',
            'confirmed_count' => 'integer',
            'is_checked_in' => 'boolean',
            'checked_in_at' => 'datetime',
            'rsvp_status' => RsvpStatus::class,
            'rsvp_ceremony' => RsvpStatus::class,
            'rsvp_reception' => RsvpStatus::class,
            'rsvp_afterparty' => RsvpStatus::class,
        ];
    }

    public function wishes(): HasMany
    {
        return $this->hasMany(Wish::class);
    }

    public function memories(): HasMany
    {
        return $this->hasMany(WeddingMemory::class);
    }
}
