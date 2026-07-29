<?php

declare(strict_types=1);

namespace App\Actions\Wedding;

use App\Data\RsvpData;
use App\Models\Guest;

class SubmitRsvpAction
{
    public function execute(RsvpData $data): Guest
    {
        $guest = Guest::where('guest_slug', $data->guestSlug)->firstOrFail();

        $guest->update([
            'rsvp_status' => $data->status,
            'confirmed_count' => $data->confirmedCount,
            'dietary_preference' => $data->dietaryPreference,
            'notes' => $data->notes,
        ]);

        return $guest;
    }
}
