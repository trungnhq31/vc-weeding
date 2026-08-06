<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreGuestRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'group' => 'nullable|string|max:100',
            'salutation' => 'nullable|string|max:50',
            'phone' => 'nullable|string|max:50',
            'estimated_count' => 'nullable|integer|min:1',
            'confirmed_count' => 'nullable|integer|min:0',
            'dietary_preference' => 'nullable|string|max:255',
            'table_id' => 'nullable|string|exists:tables,id',
            'table_name' => 'nullable|string|max:255',
            'rsvp_status' => 'nullable|string|in:pending,confirmed,attending,declined',
            'notes' => 'nullable|string',
        ];
    }
}
