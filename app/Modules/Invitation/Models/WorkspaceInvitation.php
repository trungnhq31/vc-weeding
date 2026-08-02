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
        'font_family',
        'groom_parents',
        'bride_parents',
        'event_time',
        'google_maps_url',
        'bank_name',
        'bank_account_number',
        'bank_account_holder',
        'music_url',
        'cover_photo_url',
        'enable_wax_seal',
        'enable_qr_checkin',
        'enable_gift_box',
    ];

    protected function casts(): array
    {
        return [
            'enable_wax_seal' => 'boolean',
            'enable_qr_checkin' => 'boolean',
            'enable_gift_box' => 'boolean',
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
