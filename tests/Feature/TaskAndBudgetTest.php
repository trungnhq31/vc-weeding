<?php

declare(strict_types=1);

use App\Modules\Budget\Actions\CreateBudgetItemAction;
use App\Modules\Budget\Actions\RecordPaymentAction;
use App\Modules\Budget\Services\CashFlowCalculatorService;
use App\Modules\Task\Actions\CreateTaskAction;
use App\Modules\Task\Actions\UpdateTaskStatusAction;
use App\Modules\Workspace\Models\Workspace;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('it creates task successfully within a workspace', function () {
    $workspace = Workspace::create([
        'name' => 'Test Wedding Workspace',
        'slug' => 'test-wedding-workspace',
        'budget_cap' => 200000000.00,
    ]);

    $action = new CreateTaskAction;
    $task = $action->execute([
        'workspace_id' => $workspace->id,
        'category' => 'venue',
        'title' => 'Chốt Hợp đồng Địa điểm',
        'priority' => 'high',
    ]);

    expect($task)->not->toBeNull();
    expect($task->workspace_id)->toBe($workspace->id);
    expect($task->status)->toBe('todo');
});

test('it updates task status via UpdateTaskStatusAction', function () {
    $workspace = Workspace::create([
        'name' => 'Test Workspace 2',
        'slug' => 'test-workspace-2',
    ]);

    $task = (new CreateTaskAction)->execute([
        'workspace_id' => $workspace->id,
        'title' => 'Nhiệm vụ kiểm thử',
    ]);

    $updated = (new UpdateTaskStatusAction)->execute($task->id, 'done');

    expect($updated->status)->toBe('done');
});

test('it calculates cash flow overview and tracks upcoming payments correctly', function () {
    $workspace = Workspace::create([
        'name' => 'Budget Test Workspace',
        'slug' => 'budget-test-workspace',
        'budget_cap' => 100000000.00,
    ]);

    $createBudgetAction = new CreateBudgetItemAction;
    $budgetItem = $createBudgetAction->execute([
        'workspace_id' => $workspace->id,
        'category_name' => 'Tiệc cưới',
        'item_name' => 'Sảnh tiệc Grand Ballroom',
        'estimated_amount' => 80000000.00,
        'actual_amount' => 85000000.00,
        'deposit_paid' => 20000000.00,
        'payment_status' => 'deposit_paid',
        'due_payment_date' => now()->addDays(3)->format('Y-m-d'),
    ]);

    $service = new CashFlowCalculatorService;
    $overview = $service->calculateOverview($workspace->id);

    expect($overview['total_actual'])->toEqual(85000000.00);
    expect($overview['total_deposit_paid'])->toEqual(20000000.00);
    expect($overview['remaining_balance'])->toEqual(65000000.00);
    expect($overview['upcoming_payments_count'])->toBe(1);

    // Record additional payment
    (new RecordPaymentAction)->execute($budgetItem->id, 65000000.00);

    $updatedOverview = $service->calculateOverview($workspace->id);
    expect($updatedOverview['remaining_balance'])->toEqual(0.00);
    expect($updatedOverview['upcoming_payments_count'])->toBe(0);
});
