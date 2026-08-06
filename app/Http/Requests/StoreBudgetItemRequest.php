<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreBudgetItemRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'category_name' => 'required|string|max:100',
            'item_name' => 'required|string|max:255',
            'estimated_amount' => 'required|numeric|min:0',
            'actual_amount' => 'nullable|numeric|min:0',
            'deposit_paid' => 'nullable|numeric|min:0',
            'due_payment_date' => 'nullable|date',
            'notes' => 'nullable|string',
        ];
    }
}
