<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateGoalRequest extends FormRequest
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
            'title' => ['sometimes', 'required', 'string', 'max:255'],
            'description' => ['sometimes', 'nullable', 'string', 'max:2000'],
            'goal_type' => ['sometimes', 'required', 'string', 'max:50'],
            'status' => ['sometimes', Rule::in(['draft', 'active', 'paused', 'completed', 'archived'])],
            'direction' => ['sometimes', 'nullable', Rule::in(['increase', 'decrease', 'maintain'])],
            'unit' => ['sometimes', 'nullable', 'string', 'max:20'],
            'start_value' => ['sometimes', 'nullable', 'numeric', 'gt:0'],
            'target_value' => ['sometimes', 'nullable', 'numeric', 'gt:0'],
            'starts_on' => ['sometimes', 'nullable', 'date'],
            'target_date' => ['sometimes', 'nullable', 'date', 'after_or_equal:starts_on'],
            'completed_at' => ['sometimes', 'nullable', 'date'],
            'metadata' => ['sometimes', 'nullable', 'array'],
        ];
    }
}
