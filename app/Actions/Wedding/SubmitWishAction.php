<?php

declare(strict_types=1);

namespace App\Actions\Wedding;

use App\Data\WishData;
use App\Events\WishSubmittedEvent;
use App\Models\Wish;

class SubmitWishAction
{
    public function execute(WishData $data): Wish
    {
        $wish = Wish::create([
            'guest_id' => $data->guestId,
            'sender_name' => $data->senderName,
            'message' => $data->message,
            'is_approved' => true,
            'is_pinned' => false,
        ]);

        // Broadcast realtime via Laravel Reverb
        event(new WishSubmittedEvent($wish));

        return $wish;
    }
}
