<?php

declare(strict_types=1);

namespace App\Data;

readonly class WishData
{
    public function __construct(
        public string $senderName,
        public string $message,
        public ?string $guestId = null,
    ) {}

    public static function fromRequest(array $data): self
    {
        return new self(
            senderName: (string) $data['sender_name'],
            message: (string) $data['message'],
            guestId: isset($data['guest_id']) ? (string) $data['guest_id'] : null,
        );
    }
}
