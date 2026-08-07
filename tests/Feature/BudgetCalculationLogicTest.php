<?php

declare(strict_types=1);

use App\Modules\Budget\Actions\CreateBudgetItemAction;
use App\Modules\Budget\Actions\RecordPaymentAction;
use App\Modules\Budget\Models\BudgetItem;
use App\Modules\Budget\Services\CashFlowCalculatorService;
use App\Modules\Workspace\Models\Workspace;

beforeEach(function () {
    $this->workspace = Workspace::create([
        'name' => 'Lễ Cưới Đạt & Quỳnh',
        'slug' => 'dat-quynh-wedding',
        'groom_name' => 'Thành Đạt',
        'bride_name' => 'Như Quỳnh',
        'budget_cap' => 400000000.00,
    ]);
});

test('budget estimated amount and actual amount sum calculations', function () {
    $action = new CreateBudgetItemAction;

    $item1 = $action->execute([
        'workspace_id' => $this->workspace->id,
        'category_name' => 'Địa Điểm & Tiệc',
        'item_name' => 'Đặt cọc nhà hàng Asiana Plaza',
        'estimated_amount' => 150000000.00,
        'actual_amount' => 160000000.00,
        'deposit_paid' => 50000000.00,
    ]);

    $item2 = $action->execute([
        'workspace_id' => $this->workspace->id,
        'category_name' => 'Trang Phục & Makeup',
        'item_name' => 'Thuê Váy Cưới & Vest',
        'estimated_amount' => 30000000.00,
        'actual_amount' => 28000000.00,
        'deposit_paid' => 28000000.00,
    ]);

    $items = BudgetItem::forWorkspace($this->workspace->id)->get();

    $totalEstimated = (float) $items->sum('estimated_amount');
    $totalActual = (float) $items->sum('actual_amount');
    $totalPaid = (float) $items->sum('deposit_paid');

    expect($totalEstimated)->toBe(180000000.00);
    expect($totalActual)->toBe(188000000.00);
    expect($totalPaid)->toBe(78000000.00);
});

test('remaining budget balance calculation math is accurate', function () {
    $budgetCap = (float) $this->workspace->budget_cap; // 400,000,000

    $action = new CreateBudgetItemAction;
    $action->execute([
        'workspace_id' => $this->workspace->id,
        'category_name' => 'Địa Điểm',
        'item_name' => 'Tiệc cưới',
        'estimated_amount' => 250000000.00,
        'actual_amount' => 260000000.00,
        'deposit_paid' => 100000000.00,
    ]);

    $totalSpent = (float) BudgetItem::forWorkspace($this->workspace->id)->sum('actual_amount');
    $remainingBalance = $budgetCap - $totalSpent;

    expect($totalSpent)->toBe(260000000.00);
    expect($remainingBalance)->toBe(140000000.00);
});

test('record payment action updates deposit paid amount and status', function () {
    $createAction = new CreateBudgetItemAction;
    $item = $createAction->execute([
        'workspace_id' => $this->workspace->id,
        'category_name' => 'Quay Phim & Chụp Ảnh',
        'item_name' => 'Gói quay Chụp Pre-wedding Đà Lạt',
        'estimated_amount' => 25000000.00,
        'actual_amount' => 25000000.00,
        'deposit_paid' => 10000000.00,
    ]);

    $recordAction = new RecordPaymentAction;
    $updatedItem = $recordAction->execute($item->id, 15000000.00);

    expect((float) $updatedItem->deposit_paid)->toBe(25000000.00);
    expect($updatedItem->payment_status)->toBe('fully_paid');
});

test('budget cash flow calculator service handles unpaid obligations', function () {
    $createAction = new CreateBudgetItemAction;
    $createAction->execute([
        'workspace_id' => $this->workspace->id,
        'category_name' => 'Trang Trí Gia Tiên',
        'item_name' => 'Gói hoa tươi hoa hồng Ý',
        'estimated_amount' => 20000000.00,
        'actual_amount' => 22000000.00,
        'deposit_paid' => 5000000.00,
    ]);

    $calculator = new CashFlowCalculatorService;
    $metrics = $calculator->calculateOverview($this->workspace->id);

    expect((float) $metrics['total_estimated'])->toBe(20000000.00);
    expect((float) $metrics['total_actual'])->toBe(22000000.00);
    expect((float) $metrics['total_deposit_paid'])->toBe(5000000.00);
    expect((float) $metrics['remaining_balance'])->toBe(17000000.00);
});
