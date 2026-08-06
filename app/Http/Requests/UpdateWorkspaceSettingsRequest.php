<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateWorkspaceSettingsRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'groom_name' => 'required|string|max:255',
            'bride_name' => 'required|string|max:255',
            'wedding_date' => 'nullable|date',
            'wedding_location' => 'nullable|string|max:255',
            'venue_name' => 'nullable|string|max:255',
            'budget_cap' => 'nullable|numeric|min:0',
            'estimated_guests' => 'nullable|integer|min:1',
            'wedding_hashtag' => 'nullable|string|max:100',
            'couple_story' => 'nullable|string',
        ];
    }
}
