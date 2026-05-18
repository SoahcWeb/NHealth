<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreGoalRequest extends FormRequest
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
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:2000'],
            'goal_type' => ['required', 'string', 'max:50'],
            'status' => ['sometimes', Rule::in(['draft', 'active', 'paused', 'completed', 'archived'])],
            'direction' => ['nullable', Rule::in(['increase', 'decrease', 'maintain'])],
            'unit' => ['nullable', 'string', 'max:20'],
            'start_value' => ['nullable', 'numeric', 'gt:0'],
            'target_value' => ['nullable', 'numeric', 'gt:0'],
            'starts_on' => ['nullable', 'date'],
            'target_date' => ['nullable', 'date', 'after_or_equal:starts_on'],
            'completed_at' => ['nullable', 'date'],
            'metadata' => ['nullable', 'array'],
        ];
    }
}
