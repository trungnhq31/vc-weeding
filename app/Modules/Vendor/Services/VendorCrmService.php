<?php

declare(strict_types=1);

namespace App\Modules\Vendor\Services;

use App\Modules\Vendor\Models\Vendor;
use Illuminate\Support\Carbon;

class VendorCrmService
{
    /**
     * @return array{
     *     total_contracts: float,
     *     total_paid: float,
     *     remaining_unpaid: float,
     *     vendors_count: int,
     *     unpaid_vendors_count: int,
     *     upcoming_due_vendors: array<int, array<string, mixed>>
     * }
     */
    public function getSummary(string $workspaceId): array
    {
        $vendors = Vendor::forWorkspace($workspaceId)->get();

        $totalContracts = (float) $vendors->sum('contract_amount');
        $totalPaid = (float) $vendors->sum('paid_amount');
        $remainingUnpaid = max(0.00, $totalContracts - $totalPaid);
        $unpaidVendorsCount = $vendors->where('payment_status', '!=', 'fully_paid')->count();

        $upcomingDueThreshold = Carbon::now()->addDays(7);
        $upcomingDueVendors = $vendors
            ->where('payment_status', '!=', 'fully_paid')
            ->filter(function (Vendor $vendor) use ($upcomingDueThreshold) {
                return $vendor->due_date && $vendor->due_date->lte($upcomingDueThreshold);
            })
            ->values()
            ->map(function (Vendor $vendor) {
                return [
                    'id' => $vendor->id,
                    'name' => $vendor->name,
                    'category' => $vendor->category,
                    'unpaid_balance' => $vendor->unpaid_balance,
                    'due_date' => $vendor->due_date?->format('Y-m-d'),
                ];
            })
            ->toArray();

        return [
            'total_contracts' => $totalContracts,
            'total_paid' => $totalPaid,
            'remaining_unpaid' => $remainingUnpaid,
            'vendors_count' => $vendors->count(),
            'unpaid_vendors_count' => $unpaidVendorsCount,
            'upcoming_due_vendors' => $upcomingDueVendors,
        ];
    }
}
