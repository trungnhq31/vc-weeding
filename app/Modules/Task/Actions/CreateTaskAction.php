<?php

declare(strict_types=1);

namespace App\Modules\Task\Actions;

use App\Modules\Task\Models\Task;

class CreateTaskAction
{
    public function execute(array $data): Task
    {
        return Task::create([
            'workspace_id' => $data['workspace_id'],
            'category' => $data['category'] ?? 'other',
            'title' => $data['title'],
            'description' => $data['description'] ?? null,
            'status' => $data['status'] ?? 'todo',
            'priority' => $data['priority'] ?? 'medium',
            'due_date' => $data['due_date'] ?? null,
            'estimated_cost' => $data['estimated_cost'] ?? 0,
            'actual_cost' => $data['actual_cost'] ?? 0,
        ]);
    }
}
