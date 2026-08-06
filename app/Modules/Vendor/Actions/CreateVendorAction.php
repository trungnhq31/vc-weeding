<?php

declare(strict_types=1);

namespace App\Modules\Vendor\Actions;

use App\Modules\Vendor\Models\Vendor;

class CreateVendorAction
{
    /**
     * @param array{
     *     workspace_id: string,
     *     name: string,
     *     category?: string,
     *     contact_name?: string|null,
     *     phone?: string|null,
     *     email?: string|null,
     *     contract_amount?: float|int|string,
     *     paid_amount?: float|int|string,
     *     due_date?: string|null,
     *     contract_file?: string|null,
     *     notes?: string|null,
     *     address?: string|null,
     *     latitude?: float|int|string|null,
     *     longitude?: float|int|string|null
     * } $data
     */
    public function execute(array $data): Vendor
    {
        $contractAmount = (float) ($data['contract_amount'] ?? 0);
        $paidAmount = (float) ($data['paid_amount'] ?? 0);

        $status = 'unpaid';
        if ($paidAmount >= $contractAmount && $contractAmount > 0) {
            $status = 'fully_paid';
        } elseif ($paidAmount > 0) {
            $status = 'partially_paid';
        }

        return Vendor::create([
            'workspace_id' => $data['workspace_id'],
            'name' => $data['name'],
            'category' => $data['category'] ?? 'other',
            'contact_name' => $data['contact_name'] ?? null,
            'phone' => $data['phone'] ?? null,
            'email' => $data['email'] ?? null,
            'contract_amount' => $contractAmount,
            'paid_amount' => $paidAmount,
            'payment_status' => $status,
            'due_date' => $data['due_date'] ?? null,
            'contract_file' => $data['contract_file'] ?? null,
            'notes' => $data['notes'] ?? null,
            'address' => $data['address'] ?? null,
            'latitude' => isset($data['latitude']) && $data['latitude'] !== '' ? (float) $data['latitude'] : null,
            'longitude' => isset($data['longitude']) && $data['longitude'] !== '' ? (float) $data['longitude'] : null,
        ]);
    }
}
