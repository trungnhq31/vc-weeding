<?php

declare(strict_types=1);

namespace App\Modules\Invitation\Actions;

use App\Modules\Invitation\Models\WorkspaceInvitation;
use App\Modules\Workspace\Models\Workspace;

final class UpdateInvitationCmsAction
{
    /**
     * @param  array<string, mixed>  $data
     */
    public function execute(string $workspaceId, array $data): WorkspaceInvitation
    {
        $workspace = Workspace::findOrFail($workspaceId);

        // Update core workspace event fields
        if (isset($data['groom_name']) || isset($data['bride_name']) || isset($data['wedding_date']) || isset($data['venue_name']) || isset($data['wedding_location'])) {
            $workspace->update(array_filter([
                'groom_name' => $data['groom_name'] ?? null,
                'bride_name' => $data['bride_name'] ?? null,
                'wedding_date' => $data['wedding_date'] ?? null,
                'venue_name' => $data['venue_name'] ?? null,
                'wedding_location' => $data['wedding_location'] ?? null,
            ], fn ($val) => ! is_null($val)));
        }

        // Update or create WorkspaceInvitation CMS configuration
        $invitation = WorkspaceInvitation::firstOrCreate(
            ['workspace_id' => $workspaceId],
            ['template_id' => 'romantic-pastel', 'primary_color' => '#EC4899']
        );

        $invitation->update([
            'template_id' => $data['template_id'] ?? $invitation->template_id,
            'custom_title' => $data['custom_title'] ?? $invitation->custom_title,
            'primary_color' => $data['primary_color'] ?? $invitation->primary_color,
            'font_family' => $data['font_family'] ?? $invitation->font_family,
            'groom_parents' => $data['groom_parents'] ?? $invitation->groom_parents,
            'bride_parents' => $data['bride_parents'] ?? $invitation->bride_parents,
            'event_time' => $data['event_time'] ?? $invitation->event_time,
            'google_maps_url' => $data['google_maps_url'] ?? $invitation->google_maps_url,
            'bank_name' => $data['bank_name'] ?? $invitation->bank_name,
            'bank_account_number' => $data['bank_account_number'] ?? $invitation->bank_account_number,
            'bank_account_holder' => $data['bank_account_holder'] ?? $invitation->bank_account_holder,
            'music_url' => $data['music_url'] ?? $invitation->music_url,
            'enable_wax_seal' => (bool) ($data['enable_wax_seal'] ?? $invitation->enable_wax_seal),
            'enable_qr_checkin' => (bool) ($data['enable_qr_checkin'] ?? $invitation->enable_qr_checkin),
            'enable_gift_box' => (bool) ($data['enable_gift_box'] ?? $invitation->enable_gift_box),
        ]);

        return $invitation->fresh(['template', 'workspace']);
    }
}
