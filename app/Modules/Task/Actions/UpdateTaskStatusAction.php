<?php

declare(strict_types=1);

namespace App\Modules\Task\Actions;

use App\Modules\Task\Models\Task;

class UpdateTaskStatusAction
{
    public function execute(string $taskId, string $status): Task
    {
        $task = Task::findOrFail($taskId);
        $task->update(['status' => $status]);
        return $task;
    }
}
