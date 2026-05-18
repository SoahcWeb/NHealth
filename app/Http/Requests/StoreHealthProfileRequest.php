<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreHealthProfileRequest extends FormRequest
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
            'date_of_birth' => ['nullable', 'date', 'before_or_equal:today'],
            'biological_sex' => ['nullable', Rule::in(['female', 'male', 'intersex', 'other', 'prefer_not_to_say'])],
            'height_cm' => ['nullable', 'numeric', 'gt:0', 'max:300'],
            'activity_level' => ['nullable', Rule::in(['sedentary', 'light', 'moderate', 'active', 'athlete'])],
            'unit_system' => ['nullable', Rule::in(['metric', 'imperial'])],
            'health_notes' => ['nullable', 'string', 'max:2000'],
            'metadata' => ['nullable', 'array'],
        ];
    }
}
