<?php

declare(strict_types=1);

namespace App\Modules\Guest\Actions;

use App\Models\Guest;

class DeleteGuestAction
{
    public function execute(string $guestId): bool
    {
        $guest = Guest::findOrFail($guestId);

        return (bool) $guest->delete();
    }
}
