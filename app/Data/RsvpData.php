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
        public ?RsvpStatus $rsvpCeremony = null,
        public ?RsvpStatus $rsvpReception = null,
        public ?RsvpStatus $rsvpAfterparty = null,
    ) {}

    public static function fromRequest(array $data): self
    {
        $status = RsvpStatus::from((string) $data['rsvp_status']);

        return new self(
            guestSlug: isset($data['guest_slug']) ? (string) $data['guest_slug'] : null,
            guestName: isset($data['guest_name']) ? (string) $data['guest_name'] : null,
            status: $status,
            confirmedCount: (int) ($data['confirmed_count'] ?? 1),
            dietaryPreference: isset($data['dietary_preference']) ? (string) $data['dietary_preference'] : null,
            shuttleBus: isset($data['shuttle_bus']) ? (string) $data['shuttle_bus'] : 'no',
            notes: isset($data['notes']) ? (string) $data['notes'] : null,
            rsvpCeremony: isset($data['rsvp_ceremony']) ? RsvpStatus::from((string) $data['rsvp_ceremony']) : null,
            rsvpReception: isset($data['rsvp_reception']) ? RsvpStatus::from((string) $data['rsvp_reception']) : null,
            rsvpAfterparty: isset($data['rsvp_afterparty']) ? RsvpStatus::from((string) $data['rsvp_afterparty']) : null,
        );
    }
}
