<?php

declare(strict_types=1);

use App\Models\Guest;
use App\Modules\Budget\Actions\CreateBudgetItemAction;
use App\Modules\Budget\Actions\RecordPaymentAction;
use App\Modules\Budget\Services\CashFlowCalculatorService;
use App\Modules\GroundedAI\Services\GroundedDataQueryService;
use App\Modules\Guest\Actions\CreateGuestAction;
use App\Modules\Guest\Actions\DeleteGuestAction;
use App\Modules\Guest\Actions\UpdateGuestAction;
use App\Modules\Workspace\Actions\UpdateWorkspaceSettingsAction;
use App\Modules\Workspace\Models\Workspace;

beforeEach(function () {
    $this->workspace1 = Workspace::create([
        'name' => 'Đám Cưới Quốc Trung & Hồng Vân',
        'slug' => 'workspace-1',
        'groom_name' => 'Quốc Trung',
        'bride_name' => 'Hồng Vân',
        'budget_cap' => 300000000.00,
    ]);

    $this->workspace2 = Workspace::create([
        'name' => 'Đám Cưới Minh Tuấn & Phương Anh',
        'slug' => 'workspace-2',
        'groom_name' => 'Minh Tuấn',
        'bride_name' => 'Phương Anh',
        'budget_cap' => 200000000.00,
    ]);
});

test('guest actions respect multi-tenant workspace isolation', function () {
    $createAction = new CreateGuestAction;

    $guest1 = $createAction->execute([
        'workspace_id' => $this->workspace1->id,
        'name' => 'Nguyễn Văn A',
        'group' => 'Họ Hàng Rể',
        'rsvp_status' => 'attending',
    ]);

    $guest2 = $createAction->execute([
        'workspace_id' => $this->workspace2->id,
        'name' => 'Trần Thị B',
        'group' => 'Bạn Học',
        'rsvp_status' => 'pending',
    ]);

    expect($guest1->workspace_id)->toBe($this->workspace1->id);
    expect($guest2->workspace_id)->toBe($this->workspace2->id);

    // Scoped queries check
    $w1Guests = Guest::forWorkspace($this->workspace1->id)->get();
    expect($w1Guests)->toHaveCount(1);
    expect($w1Guests->first()->id)->toBe($guest1->id);

    // Update Action
    $updateAction = new UpdateGuestAction;
    $updatedGuest = $updateAction->execute($guest1->id, [
        'name' => 'Nguyễn Văn A (Updated)',
        'dietary_preference' => 'Ăn chay',
    ]);
    expect($updatedGuest->name)->toBe('Nguyễn Văn A (Updated)');

    // Delete Action
    $deleteAction = new DeleteGuestAction;
    $deleted = $deleteAction->execute($guest1->id);
    expect($deleted)->toBeTrue();
    expect(Guest::find($guest1->id))->toBeNull();
});

test('budget actions and cash flow calculations work correctly', function () {
    $createBudget = new CreateBudgetItemAction;

    $item1 = $createBudget->execute([
        'workspace_id' => $this->workspace1->id,
        'category_name' => 'Venue',
        'item_name' => 'Đặt cọc Tiệc cưới',
        'estimated_amount' => 100000000,
        'actual_amount' => 120000000,
        'deposit_paid' => 40000000,
        'payment_status' => 'partially_paid',
    ]);

    expect($item1->workspace_id)->toBe($this->workspace1->id);

    // Record Payment
    $recordPayment = new RecordPaymentAction;
    $updatedItem = $recordPayment->execute($item1->id, 80000000);
    expect((float) $updatedItem->deposit_paid)->toBe(120000000.0);
    expect($updatedItem->payment_status)->toBe('fully_paid');

    // Calculator Service
    $calculator = new CashFlowCalculatorService;
    $overview = $calculator->calculateOverview($this->workspace1->id);
    expect($overview['total_actual'])->toBe(120000000.0);
    expect($overview['total_deposit_paid'])->toBe(120000000.0);
    expect($overview['remaining_balance'])->toBe(0.0);
});

test('workspace settings action updates personalization fields', function () {
    $updateAction = new UpdateWorkspaceSettingsAction;

    $updatedWorkspace = $updateAction->execute($this->workspace1->id, [
        'groom_name' => 'Nguyễn Hoàng Quốc Trung',
        'bride_name' => 'Lê Thị Hồng Vân',
        'wedding_date' => '2026-12-19',
        'wedding_location' => 'Hà Nội',
        'budget_cap' => 400000000,
    ]);

    expect($updatedWorkspace->groom_name)->toBe('Nguyễn Hoàng Quốc Trung');
    expect($updatedWorkspace->bride_name)->toBe('Lê Thị Hồng Vân');
    expect((float) $updatedWorkspace->budget_cap)->toBe(400000000.0);
});

test('grounded ai query service returns structured metrics without hallucination', function () {
    $groundedAi = new GroundedDataQueryService;
    $metrics = $groundedAi->getWorkspaceMetrics($this->workspace1->id);

    expect($metrics)->toHaveKeys(['workspace', 'budget', 'tasks', 'vendors', 'guests', 'hcm_benchmarks']);
    expect($metrics['workspace']['id'])->toBe($this->workspace1->id);
    expect($metrics['hcm_benchmarks']['budget_distribution']['venue_and_catering_pct'])->toBe(50);
});
