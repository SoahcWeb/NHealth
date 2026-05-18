<?php

namespace App\Http\Controllers;

use App\Models\CheckIn;
use App\Models\Goal;
use App\Models\WeightEntry;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class NHealthDashboardController extends Controller
{
    /**
     * Display the private NHealth cockpit for the authenticated user.
     */
    public function index(Request $request): View
    {
        $user = $request->user();

        $healthProfile = $user->healthProfile;
        $activeGoal = $user->activeGoals()->latest('created_at')->first();
        $latestWeightEntry = $user->weightEntries()->latest('recorded_on')->first();
        $recentWeightEntries = $user->weightEntries()->latest('recorded_on')->take(2)->get();
        $chartWeightEntries = $user->weightEntries()
            ->latest('recorded_on')
            ->take(30)
            ->get()
            ->reverse()
            ->values();
        $latestCheckIn = $user->checkIns()->latest('recorded_on')->first();
        $allCheckIns = $user->checkIns()->latest('recorded_on')->get();

        $currentWeightKg = $latestWeightEntry?->weight_kg !== null
            ? (float) $latestWeightEntry->weight_kg
            : ($latestCheckIn?->weight_kg !== null ? (float) $latestCheckIn->weight_kg : null);

        $progress = $this->estimateGoalProgress($activeGoal, $currentWeightKg);

        return view('nhealth.dashboard', [
            'healthProfile' => $healthProfile,
            'activeGoal' => $activeGoal,
            'latestWeightEntry' => $latestWeightEntry,
            'latestCheckIn' => $latestCheckIn,
            'weightChart' => [
                'labels' => $chartWeightEntries
                    ->map(static fn (WeightEntry $weightEntry): string => $weightEntry->recorded_on->format('d M'))
                    ->all(),
                'weights' => $chartWeightEntries
                    ->map(static fn (WeightEntry $weightEntry): float => (float) $weightEntry->weight_kg)
                    ->all(),
            ],
            'stats' => [
                'current_weight_kg' => $currentWeightKg,
                'recent_weight_change_kg' => $this->calculateRecentWeightChange($recentWeightEntries, $latestCheckIn),
                'total_weight_entries' => $user->weightEntries()->count(),
                'total_check_ins' => $allCheckIns->count(),
                'active_goals_count' => $user->activeGoals()->count(),
                'current_streak' => $this->calculateCurrentStreak($allCheckIns),
            ],
            'progress' => $progress,
        ]);
    }

    /**
     * Estimate progress for a weight-related active goal when enough data exists.
     *
     * @return array<string, float|int|string|null>
     */
    private function estimateGoalProgress(?Goal $activeGoal, ?float $currentWeightKg): array
    {
        if ($activeGoal === null) {
            return [
                'percentage' => null,
                'remaining' => null,
                'remaining_label' => null,
                'is_estimable' => false,
                'message' => 'No active goal yet.',
            ];
        }

        $goalType = Str::lower($activeGoal->goal_type);
        $unit = Str::lower((string) $activeGoal->unit);
        $isWeightGoal = Str::contains($goalType, 'weight') || in_array($unit, ['kg', 'kgs', 'kilogram', 'kilograms'], true);

        if (! $isWeightGoal || $currentWeightKg === null || $activeGoal->start_value === null || $activeGoal->target_value === null) {
            return [
                'percentage' => null,
                'remaining' => null,
                'remaining_label' => null,
                'is_estimable' => false,
                'message' => 'Add enough weight data and numeric targets to estimate progress.',
            ];
        }

        $start = (float) $activeGoal->start_value;
        $target = (float) $activeGoal->target_value;
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

        $coveredDistance = match ($activeGoal->direction) {
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

    /**
     * Calculate the most recent weight change using dedicated entries first.
     */
    private function calculateRecentWeightChange(Collection $recentWeightEntries, ?CheckIn $latestCheckIn): ?float
    {
        if ($recentWeightEntries->count() >= 2) {
            return round(
                (float) $recentWeightEntries->first()->weight_kg - (float) $recentWeightEntries->last()->weight_kg,
                2,
            );
        }

        if ($recentWeightEntries->count() === 1 && $latestCheckIn?->weight_kg !== null) {
            return round(
                (float) $recentWeightEntries->first()->weight_kg - (float) $latestCheckIn->weight_kg,
                2,
            );
        }

        return null;
    }

    /**
     * Calculate the streak of consecutive recorded days ending at the latest entry.
     */
    private function calculateCurrentStreak(Collection $checkIns): int
    {
        if ($checkIns->isEmpty()) {
            return 0;
        }

        $expectedDate = $checkIns->first()->recorded_on->copy();
        $streak = 0;

        foreach ($checkIns as $checkIn) {
            if (! $checkIn->recorded_on->isSameDay($expectedDate)) {
                break;
            }

            $streak++;
            $expectedDate->subDay();
        }

        return $streak;
    }
}
