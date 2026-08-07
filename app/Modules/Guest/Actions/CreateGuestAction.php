<?php

declare(strict_types=1);

namespace App\Modules\Guest\Actions;

use App\Models\Guest;
use Illuminate\Support\Str;

class CreateGuestAction
{
    public function execute(array $data): Guest
    {
        $name = (string) $data['name'];
        $slug = Str::slug($name).'-'.Str::random(5);

        $status = (string) ($data['rsvp_status'] ?? 'pending');
        if (in_array($status, ['confirmed', 'yes'], true)) {
            $status = 'attending';
        }

        return Guest::create([
            'workspace_id' => $data['workspace_id'],
            'name' => $name,
            'guest_slug' => $slug,
            'group' => $data['group'] ?? 'Bạn Học',
            'salutation' => $data['salutation'] ?? 'Anh/Chị',
            'estimated_count' => (int) ($data['estimated_count'] ?? 1),
            'confirmed_count' => (int) ($data['confirmed_count'] ?? 0),
            'dietary_preference' => $data['dietary_preference'] ?? 'Bình thường',
            'shuttle_bus' => (bool) ($data['shuttle_bus'] ?? false),
            'table_id' => $data['table_id'] ?? null,
            'table_name' => $data['table_name'] ?? 'Chưa xếp',
            'rsvp_status' => $status,
            'rsvp_ceremony' => $data['rsvp_ceremony'] ?? $status,
            'rsvp_reception' => $data['rsvp_reception'] ?? $status,
            'rsvp_afterparty' => $data['rsvp_afterparty'] ?? $status,
            'notes' => $data['notes'] ?? null,
        ]);
    }
}
