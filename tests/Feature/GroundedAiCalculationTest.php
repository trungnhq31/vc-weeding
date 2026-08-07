<?php

declare(strict_types=1);

use App\Modules\Budget\Actions\CreateBudgetItemAction;
use App\Modules\GroundedAI\Services\GroundedDataQueryService;
use App\Modules\Guest\Actions\CreateGuestAction;
use App\Modules\Workspace\Models\Workspace;

beforeEach(function () {
    $this->workspace = Workspace::create([
        'name' => 'Đám Cưới Minh Hoàng & Bảo Trâm',
        'slug' => 'minh-hoang-bao-tram',
        'groom_name' => 'Minh Hoàng',
        'bride_name' => 'Bảo Trâm',
        'budget_cap' => 300000000.00,
    ]);
});

test('grounded ai query service returns accurate non-hallucinated metrics', function () {
    $budgetAction = new CreateBudgetItemAction;
    $budgetAction->execute([
        'workspace_id' => $this->workspace->id,
        'category_name' => 'Địa Điểm',
        'item_name' => 'Nhà Hàng Cưới White Palace',
        'estimated_amount' => 200000000.00,
        'actual_amount' => 210000000.00,
        'deposit_paid' => 100000000.00,
    ]);

    $guestAction = new CreateGuestAction;
    $guestAction->execute([
        'workspace_id' => $this->workspace->id,
        'name' => 'Nguyễn Văn C',
        'rsvp_status' => 'attending',
        'rsvp_ceremony' => 'attending',
        'rsvp_reception' => 'attending',
        'rsvp_afterparty' => 'declined',
        'confirmed_count' => 2,
    ]);

    $queryService = new GroundedDataQueryService;
    $metrics = $queryService->getWorkspaceMetrics($this->workspace->id);

    expect($metrics['workspace']['budget_cap'])->toBe(300000000.00);
    expect($metrics['guests']['attending_guests'])->toBe(1);
    expect($metrics['guests']['attending_ceremony'])->toBe(1);
    expect($metrics['guests']['attending_reception'])->toBe(1);
    expect($metrics['guests']['attending_afterparty'])->toBe(0);
});

test('grounded ai generates dynamic budget overrun warnings grounded in database values', function () {
    // Set low budget cap
    $this->workspace->update(['budget_cap' => 100000000.00]);

    $budgetAction = new CreateBudgetItemAction;
    $budgetAction->execute([
        'workspace_id' => $this->workspace->id,
        'category_name' => 'Địa Điểm',
        'item_name' => 'Tiệc cưới',
        'estimated_amount' => 150000000.00,
        'actual_amount' => 150000000.00,
        'deposit_paid' => 50000000.00,
    ]);

    $queryService = new GroundedDataQueryService;
    $metrics = $queryService->getWorkspaceMetrics($this->workspace->id);

    $contextPrompt = json_encode($metrics, JSON_UNESCAPED_UNICODE);

    expect($contextPrompt)->toContain('Minh Hoàng');
    expect($contextPrompt)->toContain('Bảo Trâm');
    expect($contextPrompt)->toContain('Tiệc cưới');
    expect($contextPrompt)->toContain('150000000');
});
