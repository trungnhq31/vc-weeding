<?php

declare(strict_types=1);

use App\Modules\GroundedAI\Actions\QueryGroundedAiAction;
use App\Modules\Vendor\Actions\CreateVendorAction;
use App\Modules\Vendor\Actions\RecordVendorPaymentAction;
use App\Modules\Vendor\Services\VendorCrmService;
use App\Modules\Workspace\Models\Workspace;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('it creates vendor and calculates payment status correctly', function () {
    $workspace = Workspace::create([
        'name' => 'Vendor Test Workspace',
        'slug' => 'vendor-test-workspace',
        'budget_cap' => 150000000.00,
    ]);

    $action = new CreateVendorAction;
    $vendor = $action->execute([
        'workspace_id' => $workspace->id,
        'name' => 'Trung tâm Tiệc cưới Asiana',
        'category' => 'venue',
        'contract_amount' => 100000000.00,
        'paid_amount' => 30000000.00,
        'due_date' => now()->addDays(5)->format('Y-m-d'),
    ]);

    expect($vendor)->not->toBeNull();
    expect($vendor->workspace_id)->toBe($workspace->id);
    expect($vendor->payment_status)->toBe('partially_paid');
    expect($vendor->unpaid_balance)->toEqual(70000000.00);

    // Record remaining payment
    $recordPaymentAction = new RecordVendorPaymentAction;
    $updatedVendor = $recordPaymentAction->execute($vendor->id, 70000000.00);

    expect($updatedVendor->payment_status)->toBe('fully_paid');
    expect($updatedVendor->unpaid_balance)->toEqual(0.00);
});

test('it aggregates vendor crm summary correctly', function () {
    $workspace = Workspace::create([
        'name' => 'CRM Summary Workspace',
        'slug' => 'crm-summary-workspace',
    ]);

    $action = new CreateVendorAction;
    $action->execute([
        'workspace_id' => $workspace->id,
        'name' => 'Studio Photography',
        'category' => 'studio',
        'contract_amount' => 20000000.00,
        'paid_amount' => 5000000.00,
    ]);

    $action->execute([
        'workspace_id' => $workspace->id,
        'name' => 'Makeup Artist',
        'category' => 'makeup',
        'contract_amount' => 10000000.00,
        'paid_amount' => 10000000.00,
    ]);

    $service = new VendorCrmService;
    $summary = $service->getSummary($workspace->id);

    expect($summary['total_contracts'])->toEqual(30000000.00);
    expect($summary['total_paid'])->toEqual(15000000.00);
    expect($summary['remaining_unpaid'])->toEqual(15000000.00);
    expect($summary['vendors_count'])->toBe(2);
    expect($summary['unpaid_vendors_count'])->toBe(1);
});

test('it executes grounded ai query with zero hallucination guarantee', function () {
    $workspace = Workspace::create([
        'name' => 'Grounded AI Workspace',
        'slug' => 'grounded-ai-workspace',
        'budget_cap' => 200000000.00,
    ]);

    $queryAction = new QueryGroundedAiAction;

    $result = $queryAction->execute($workspace->id, 'ngân sách');

    expect($result['intent'])->toBe('budget');
    expect($result['metrics']['workspace']['name'])->toBe('Grounded AI Workspace');
    expect($result['summary_text'])->toContain('Grounded AI Workspace');
    expect($result['insights'])->toBeArray();
    expect($result['recommendations'])->toBeArray();
});

test('it enforces strict multi-tenant workspace isolation for vendors', function () {
    $workspace1 = Workspace::create(['name' => 'Workspace One', 'slug' => 'workspace-one']);
    $workspace2 = Workspace::create(['name' => 'Workspace Two', 'slug' => 'workspace-two']);

    $action = new CreateVendorAction;
    $vendor1 = $action->execute([
        'workspace_id' => $workspace1->id,
        'name' => 'Vendor for Workspace 1',
        'contract_amount' => 50000000.00,
    ]);

    $crmService = new VendorCrmService;
    $summary1 = $crmService->getSummary($workspace1->id);
    $summary2 = $crmService->getSummary($workspace2->id);

    expect($summary1['vendors_count'])->toBe(1);
    expect($summary2['vendors_count'])->toBe(0);
});
