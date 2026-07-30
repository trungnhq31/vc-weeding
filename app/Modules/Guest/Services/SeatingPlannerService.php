<?php

declare(strict_types=1);

namespace App\Modules\Guest\Services;

use App\Models\Guest;
use App\Modules\Guest\Models\Table;

class SeatingPlannerService
{
    public function getSeatingOverview(string $workspaceId): array
    {
        $tables = Table::where('workspace_id', $workspaceId)->with('guests')->get();
        $allGuests = Guest::where('workspace_id', $workspaceId)->get();

        $totalTablesCount = $tables->count();
        $totalCapacity = $tables->sum('capacity');
        $totalAssignedGuests = Guest::where('workspace_id', $workspaceId)->whereNotNull('table_id')->count();
        $totalUnassignedGuests = Guest::where('workspace_id', $workspaceId)->whereNull('table_id')->count();

        $overCapacityTables = $tables->filter(function (Table $t) {
            return $t->is_over_capacity;
        });

        return [
            'workspace_id' => $workspaceId,
            'total_tables_count' => $totalTablesCount,
            'total_capacity' => $totalCapacity,
            'total_assigned_guests' => $totalAssignedGuests,
            'total_unassigned_guests' => $totalUnassignedGuests,
            'has_over_capacity_alert' => $overCapacityTables->isNotEmpty(),
            'over_capacity_tables_count' => $overCapacityTables->count(),
            'tables' => $tables,
        ];
    }

    public function assignGuestToTable(string $guestId, ?string $tableId): Guest
    {
        $guest = Guest::findOrFail($guestId);
        $tableName = null;

        if ($tableId) {
            $table = Table::findOrFail($tableId);
            $tableName = $table->table_name;
        }

        $guest->update([
            'table_id' => $tableId,
            'table_name' => $tableName,
        ]);

        return $guest;
    }
}
