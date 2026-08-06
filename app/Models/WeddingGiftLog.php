<?php

declare(strict_types=1);

namespace App\Models;

use App\Modules\Workspace\Models\Workspace;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WeddingGiftLog extends Model
{
    use HasFactory, HasUlids;

    protected $fillable = [
        'workspace_id',
        'guest_id',
        'giver_name',
        'relationship',
        'amount',
        'gift_type',
        'gift_item_description',
        'wish_message',
        'thank_you_sent',
    ];

    protected function casts(): array
    {
        return [
            'amount' => 'decimal:2',
            'thank_you_sent' => 'boolean',
        ];
    }

    public function workspace(): BelongsTo
    {
        return $this->belongsTo(Workspace::class);
    }

    public function guest(): BelongsTo
    {
        return $this->belongsTo(Guest::class);
    }
}
