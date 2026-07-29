<?php

declare(strict_types=1);

namespace App\Data;

use App\Enums\RsvpStatus;

readonly class RsvpData
{
    public function __construct(
        public string $guestSlug,
        public RsvpStatus $status,
        public int $confirmedCount,
        public ?string $dietaryPreference = null,
        public ?string $notes = null,
    ) {}

    public static function fromRequest(array $data): self
    {
        return new self(
            guestSlug: (string) $data['guest_slug'],
            status: RsvpStatus::from((string) $data['rsvp_status']),
            confirmedCount: (int) ($data['confirmed_count'] ?? 1),
            dietaryPreference: isset($data['dietary_preference']) ? (string) $data['dietary_preference'] : null,
            notes: isset($data['notes']) ? (string) $data['notes'] : null,
        );
    }
}
