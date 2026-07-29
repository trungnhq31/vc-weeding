<?php

declare(strict_types=1);

namespace App\Events;

use App\Models\Wish;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class WishSubmittedEvent implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(public Wish $wish) {}

    public function broadcastOn(): array
    {
        return [
            new Channel('wedding-wishes'),
        ];
    }

    public function broadcastAs(): string
    {
        return 'wish.submitted';
    }
}
