<?php

namespace App\Services;

use App\Models\Goal;
use Illuminate\Support\Str;

class GoalProgressService
{
    /**
     * Estimate progress for a goal when enough structured data exists.
     *
     * @return array<string, float|int|string|null|bool>
     */
    public function build(?Goal $goal, ?float $currentWeightKg): array
    {
        if ($goal === null) {
            return [
                'percentage' => null,
                'remaining' => null,
                'remaining_label' => null,
                'is_estimable' => false,
                'message' => 'No active goal yet.',
            ];
        }

        $goalType = Str::lower($goal->goal_type);
        $unit = Str::lower((string) $goal->unit);
        $isWeightGoal = Str::contains($goalType, 'weight') || in_array($unit, ['kg', 'kgs', 'kilogram', 'kilograms'], true);

        if (! $isWeightGoal || $currentWeightKg === null || $goal->start_value === null || $goal->target_value === null) {
            return [
                'percentage' => null,
                'remaining' => null,
                'remaining_label' => null,
                'is_estimable' => false,
                'message' => 'Add enough weight data and numeric targets to estimate progress.',
            ];
        }

        $start = (float) $goal->start_value;
        $target = (float) $goal->target_value;
        $totalDistance = abs($target - $start);

        if ($totalDistance === 0.0) {
            return [
                'percentage' => 100,
                'remaining' => 0.0,
                'remaining_label' => 'Target already matched.',
                'is_estimable' => true,
                'message' => null,
            ];
        }

        $coveredDistance = match ($goal->direction) {
            'decrease' => $start - $currentWeightKg,
            'increase' => $currentWeightKg - $start,
            default => $totalDistance - abs($target - $currentWeightKg),
        };

        $percentage = (int) round(max(0, min(100, ($coveredDistance / $totalDistance) * 100)));
        $remaining = round(abs($target - $currentWeightKg), 2);

        return [
            'percentage' => $percentage,
            'remaining' => $remaining,
            'remaining_label' => $remaining . ' kg remaining',
            'is_estimable' => true,
            'message' => null,
        ];
    }
}
