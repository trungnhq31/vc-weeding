<?php

declare(strict_types=1);

namespace App\Modules\Vendor\Actions;

use App\Modules\Vendor\Models\Vendor;

class RecordVendorPaymentAction
{
    public function execute(string $vendorId, float $additionalAmount): Vendor
    {
        $vendor = Vendor::findOrFail($vendorId);

        $newPaid = (float) $vendor->paid_amount + $additionalAmount;
        $contractAmount = (float) $vendor->contract_amount;

        $status = 'unpaid';
        if ($newPaid >= $contractAmount && $contractAmount > 0) {
            $status = 'fully_paid';
        } elseif ($newPaid > 0) {
            $status = 'partially_paid';
        }

        $vendor->update([
            'paid_amount' => $newPaid,
            'payment_status' => $status,
        ]);

        return $vendor->fresh();
    }
}
