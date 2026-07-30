<?php

declare(strict_types=1);

namespace App\Actions\Wedding;

use App\Data\RsvpData;
use App\Models\Guest;
use Illuminate\Support\Str;

class SubmitRsvpAction
{
    public function execute(RsvpData $data): Guest
    {
        $guest = null;

        if ($data->guestSlug) {
            $guest = Guest::where('guest_slug', $data->guestSlug)->first();
        }

        if (! $guest && $data->guestName) {
            $slug = Str::slug($data->guestName);
            $existing = Guest::where('guest_slug', $slug)->first();
            if ($existing) {
                $guest = $existing;
            } else {
                $guest = Guest::create([
                    'guest_slug' => $slug.'-'.Str::random(4),
                    'name' => $data->guestName,
                    'salutation' => 'Kính mời '.$data->guestName,
                    'group' => 'Khách Tự Đăng Ký',
                    'qr_code_token' => 'QR-'.strtoupper(Str::random(8)),
                ]);
            }
        }

        if (! $guest) {
            throw new \InvalidArgumentException('Không tìm thấy thông tin khách mời.');
        }

        if (! $guest->qr_code_token) {
            $guest->qr_code_token = 'QR-'.strtoupper(Str::random(8));
        }

        $guest->update([
            'rsvp_status' => $data->status,
            'confirmed_count' => $data->confirmedCount,
            'dietary_preference' => $data->dietaryPreference,
            'shuttle_bus' => $data->shuttleBus ?? 'no',
            'notes' => $data->notes,
            'qr_code_token' => $guest->qr_code_token,
        ]);

        return $guest;
    }
}
