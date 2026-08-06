<?php

declare(strict_types=1);

namespace App\Modules\Workspace\Actions;

use App\Modules\Workspace\Models\Workspace;

class UpdateWorkspaceSettingsAction
{
    public function execute(string $workspaceId, array $data): Workspace
    {
        $workspace = Workspace::findOrFail($workspaceId);

        $workspace->update(array_filter([
            'name' => isset($data['groom_name']) && isset($data['bride_name'])
                ? "Đám Cưới {$data['groom_name']} & {$data['bride_name']}"
                : ($data['name'] ?? null),
            'groom_name' => $data['groom_name'] ?? null,
            'bride_name' => $data['bride_name'] ?? null,
            'wedding_date' => $data['wedding_date'] ?? null,
            'wedding_location' => $data['wedding_location'] ?? null,
            'venue_name' => $data['venue_name'] ?? null,
            'budget_cap' => isset($data['budget_cap']) ? (float) $data['budget_cap'] : null,
            'estimated_guests' => isset($data['estimated_guests']) ? (int) $data['estimated_guests'] : null,
            'wedding_hashtag' => $data['wedding_hashtag'] ?? null,
            'couple_story' => $data['couple_story'] ?? null,
        ], fn ($val) => $val !== null));

        return $workspace->fresh();
    }
}
