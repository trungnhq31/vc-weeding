<?php

declare(strict_types=1);

namespace App\Models;

use App\Modules\Workspace\Models\Workspace;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WeddingMemory extends Model
{
    use HasFactory, HasUlids;

    protected $fillable = [
        'workspace_id',
        'guest_id',
        'uploader_name',
        'category',
        'title',
        'description',
        'image_url',
        'is_approved',
        'is_pinned',
    ];

    protected function casts(): array
    {
        return [
            'is_approved' => 'boolean',
            'is_pinned' => 'boolean',
        ];
    }

    public function guest(): BelongsTo
    {
        return $this->belongsTo(Guest::class);
    }

    public function workspace(): BelongsTo
    {
        return $this->belongsTo(Workspace::class);
    }
}
