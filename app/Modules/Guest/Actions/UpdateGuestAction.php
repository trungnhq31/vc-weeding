<?php

declare(strict_types=1);

namespace App\Modules\Guest\Actions;

use App\Models\Guest;

class UpdateGuestAction
{
    public function execute(string $guestId, array $data): Guest
    {
        $guest = Guest::findOrFail($guestId);

        $rsvpStatus = $data['rsvp_status'] ?? null;
        if ($rsvpStatus && in_array($rsvpStatus, ['confirmed', 'yes'], true)) {
            $rsvpStatus = 'attending';
        }

        $rsvpCeremony = $data['rsvp_ceremony'] ?? null;
        if ($rsvpCeremony && in_array($rsvpCeremony, ['confirmed', 'yes'], true)) {
            $rsvpCeremony = 'attending';
        }
        $rsvpReception = $data['rsvp_reception'] ?? null;
        if ($rsvpReception && in_array($rsvpReception, ['confirmed', 'yes'], true)) {
            $rsvpReception = 'attending';
        }
        $rsvpAfterparty = $data['rsvp_afterparty'] ?? null;
        if ($rsvpAfterparty && in_array($rsvpAfterparty, ['confirmed', 'yes'], true)) {
            $rsvpAfterparty = 'attending';
        }

        $guest->update(array_filter([
            'name' => $data['name'] ?? null,
            'group' => $data['group'] ?? null,
            'salutation' => $data['salutation'] ?? null,
            'estimated_count' => isset($data['estimated_count']) ? (int) $data['estimated_count'] : null,
            'confirmed_count' => isset($data['confirmed_count']) ? (int) $data['confirmed_count'] : null,
            'dietary_preference' => $data['dietary_preference'] ?? null,
            'shuttle_bus' => isset($data['shuttle_bus']) ? (bool) $data['shuttle_bus'] : null,
            'table_id' => $data['table_id'] ?? null,
            'table_name' => $data['table_name'] ?? null,
            'rsvp_status' => $rsvpStatus,
            'rsvp_ceremony' => $rsvpCeremony,
            'rsvp_reception' => $rsvpReception,
            'rsvp_afterparty' => $rsvpAfterparty,
            'notes' => $data['notes'] ?? null,
        ], fn ($val) => $val !== null));

        return $guest->fresh();
    }
}
