<?php

declare(strict_types=1);

namespace App\Data;

use App\Enums\RsvpStatus;

readonly class RsvpData
{
    public function __construct(
        public ?string $guestSlug,
        public ?string $guestName,
        public RsvpStatus $status,
        public int $confirmedCount,
        public ?string $dietaryPreference = null,
        public ?string $shuttleBus = 'no',
        public ?string $notes = null,
    ) {}

    public static function fromRequest(array $data): self
    {
        return new self(
            guestSlug: isset($data['guest_slug']) ? (string) $data['guest_slug'] : null,
            guestName: isset($data['guest_name']) ? (string) $data['guest_name'] : null,
            status: RsvpStatus::from((string) $data['rsvp_status']),
            confirmedCount: (int) ($data['confirmed_count'] ?? 1),
            dietaryPreference: isset($data['dietary_preference']) ? (string) $data['dietary_preference'] : null,
            shuttleBus: isset($data['shuttle_bus']) ? (string) $data['shuttle_bus'] : 'no',
            notes: isset($data['notes']) ? (string) $data['notes'] : null,
        );
    }
}
