<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\RsvpStatus;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Guest extends Model
{
    use HasFactory, HasUlids;

    protected $fillable = [
        'guest_slug',
        'name',
        'salutation',
        'group',
        'estimated_count',
        'confirmed_count',
        'dietary_preference',
        'rsvp_status',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'estimated_count' => 'integer',
            'confirmed_count' => 'integer',
            'rsvp_status' => RsvpStatus::class,
        ];
    }

    public function wishes(): HasMany
    {
        return $this->hasMany(Wish::class);
    }
}
