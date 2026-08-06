<?php

declare(strict_types=1);

namespace App\Modules\Guest\Actions;

use App\Modules\Guest\Models\Table;

class CreateTableAction
{
    public function execute(array $data): Table
    {
        return Table::create([
            'workspace_id' => $data['workspace_id'],
            'name' => (string) $data['name'],
            'capacity' => (int) ($data['capacity'] ?? 10),
            'shape' => $data['shape'] ?? 'round',
            'zone' => $data['zone'] ?? 'Sân Sân Chính',
            'assigned_count' => (int) ($data['assigned_count'] ?? 0),
        ]);
    }
}
