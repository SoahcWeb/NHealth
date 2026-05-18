<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

class StoreCheckInRequest extends FormRequest
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
     * @return array<string, array<int, string>>
     */
    public function rules(): array
    {
        return [
            'recorded_on' => ['required', 'date', 'before_or_equal:today'],
            'weight_kg' => ['nullable', 'numeric', 'between:20,500'],
            'sleep_hours' => ['nullable', 'numeric', 'between:0,24'],
            'steps' => ['nullable', 'integer', 'between:0,100000'],
            'energy_level' => ['nullable', 'integer', 'between:1,10'],
            'mood_level' => ['nullable', 'integer', 'between:1,10'],
            'notes' => ['nullable', 'string', 'max:1000'],
        ];
    }

    /**
     * Ensure a check-in always contains at least one real metric.
     *
     * @return array<int, \Closure>
     */
    public function after(): array
    {
        return [
            function (Validator $validator): void {
                $hasTrackedValue = collect([
                    $this->input('weight_kg'),
                    $this->input('sleep_hours'),
                    $this->input('steps'),
                    $this->input('energy_level'),
                    $this->input('mood_level'),
                    $this->input('notes'),
                ])->contains(static fn (mixed $value): bool => filled($value));

                if (! $hasTrackedValue) {
                    $validator->errors()->add('recorded_on', 'Add at least one health metric or note.');
                }
            },
        ];
    }
}
