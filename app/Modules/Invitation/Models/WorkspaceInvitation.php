<?php

declare(strict_types=1);

namespace App\Modules\Invitation\Models;

use App\Modules\Workspace\Models\Workspace;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WorkspaceInvitation extends Model
{
    use HasUlids;

    protected $fillable = [
        'workspace_id',
        'template_id',
        'custom_title',
        'primary_color',
        'music_url',
        'cover_photo_url',
        'enable_wax_seal',
        'enable_qr_checkin',
    ];

    protected function casts(): array
    {
        return [
            'enable_wax_seal' => 'boolean',
            'enable_qr_checkin' => 'boolean',
        ];
    }

    public function workspace(): BelongsTo
    {
        return $this->belongsTo(Workspace::class);
    }

    public function template(): BelongsTo
    {
        return $this->belongsTo(InvitationTemplate::class, 'template_id');
    }
}
