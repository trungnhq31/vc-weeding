<?php

declare(strict_types=1);

namespace App\Models;

use App\Modules\Workspace\Models\Workspace;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WeddingRunOfShow extends Model
{
    use HasFactory, HasUlids;

    protected $fillable = [
        'workspace_id',
        'session_type',
        'time_slot',
        'title',
        'description',
        'person_in_charge',
        'pic_phone',
        'location_note',
        'is_completed',
        'order_index',
    ];

    protected function casts(): array
    {
        return [
            'is_completed' => 'boolean',
            'order_index' => 'integer',
        ];
    }

    public function workspace(): BelongsTo
    {
        return $this->belongsTo(Workspace::class);
    }
}
