<?php

declare(strict_types=1);

namespace App\Modules\Invitation\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class InvitationTemplate extends Model
{
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id',
        'name',
        'description',
        'thumbnail_url',
        'vue_component',
        'is_premium',
    ];

    protected function casts(): array
    {
        return [
            'is_premium' => 'boolean',
        ];
    }

    public function workspaceInvitations(): HasMany
    {
        return $this->hasMany(WorkspaceInvitation::class, 'template_id');
    }
}
