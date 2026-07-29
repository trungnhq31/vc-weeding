<?php

declare(strict_types=1);

namespace App\Http\Requests\Wedding;

use Illuminate\Foundation\Http\FormRequest;

class StoreWishRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'sender_name' => ['required', 'string', 'max:100'],
            'message' => ['required', 'string', 'max:1000'],
            'guest_id' => ['nullable', 'string', 'exists:guests,id'],
        ];
    }
}
