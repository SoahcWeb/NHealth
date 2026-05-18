<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateHealthProfileRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, array<int, string|\Illuminate\Contracts\Validation\ValidationRule>>
     */
    public function rules(): array
    {
        return [
            'user_id' => ['prohibited'],
            'date_of_birth' => ['sometimes', 'nullable', 'date', 'before_or_equal:today'],
            'biological_sex' => ['sometimes', 'nullable', Rule::in(['female', 'male', 'intersex', 'other', 'prefer_not_to_say'])],
            'height_cm' => ['sometimes', 'nullable', 'numeric', 'gt:0', 'max:300'],
            'activity_level' => ['sometimes', 'nullable', Rule::in(['sedentary', 'light', 'moderate', 'active', 'athlete'])],
            'unit_system' => ['sometimes', 'nullable', Rule::in(['metric', 'imperial'])],
            'health_notes' => ['sometimes', 'nullable', 'string', 'max:2000'],
            'metadata' => ['sometimes', 'nullable', 'array'],
        ];
    }
}
