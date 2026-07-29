<?php

declare(strict_types=1);

namespace App\Http\Requests\Wedding;

use App\Enums\RsvpStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreRsvpRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'guest_slug' => ['required', 'string', 'exists:guests,guest_slug'],
            'rsvp_status' => ['required', Rule::enum(RsvpStatus::class)],
            'confirmed_count' => ['required', 'integer', 'min:0', 'max:10'],
            'dietary_preference' => ['nullable', 'string', 'max:255'],
            'notes' => ['nullable', 'string', 'max:1000'],
        ];
    }
}
