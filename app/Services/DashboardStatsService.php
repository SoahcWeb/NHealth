<?php

namespace App\Services;

use App\Models\CheckIn;
use App\Models\User;
use App\Models\WeightEntry;
use Illuminate\Support\Collection;

class DashboardStatsService
{
    public function __construct(
        private readonly GoalProgressService $goalProgressService,
        private readonly BadgeService $badgeService,
    ) {
    }

    /**
     * Build the complete private dashboard payload for the authenticated user.
     *
     * @return array<string, mixed>
     */
    public function build(User $user): array
    {
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
        $weekWeightEntries = $user->weightEntries()
            ->whereDate('recorded_on', '>=', now()->subDays(6)->toDateString())
            ->orderBy('recorded_on')
            ->get();
        $latestCheckIn = $user->checkIns()->latest('recorded_on')->first();
        $allCheckIns = $user->checkIns()->latest('recorded_on')->get();
        $recentCheckIns = $user->checkIns()
            ->whereDate('recorded_on', '>=', now()->subDays(6)->toDateString())
            ->latest('recorded_on')
            ->get();

        $currentWeightKg = $latestWeightEntry?->weight_kg !== null
            ? (float) $latestWeightEntry->weight_kg
            : ($latestCheckIn?->weight_kg !== null ? (float) $latestCheckIn->weight_kg : null);

        return [
            'healthProfile' => $healthProfile,
            'activeGoal' => $activeGoal,
            'latestWeightEntry' => $latestWeightEntry,
            'latestCheckIn' => $latestCheckIn,
            'weightChart' => $this->buildWeightChart($chartWeightEntries),
            'stats' => [
                'current_weight_kg' => $currentWeightKg,
                'recent_weight_change_kg' => $this->calculateRecentWeightChange($recentWeightEntries, $latestCheckIn),
                'weight_change_7d_kg' => $this->calculateWeightChangeOverPeriod($weekWeightEntries),
                'total_weight_entries' => $user->weightEntries()->count(),
                'total_check_ins' => $allCheckIns->count(),
                'check_ins_this_month' => $user->checkIns()
                    ->whereDate('recorded_on', '>=', now()->startOfMonth()->toDateString())
                    ->count(),
                'active_goals_count' => $user->activeGoals()->count(),
                'current_streak' => $this->calculateCurrentStreak($allCheckIns),
                'average_sleep_hours_7d' => $this->calculateAverageSleep($recentCheckIns),
                'latest_mood_level' => $latestCheckIn?->mood_level,
                'latest_energy_level' => $latestCheckIn?->energy_level,
            ],
            'progress' => $this->goalProgressService->build($activeGoal, $currentWeightKg),
            'habitSummary' => $this->buildHabitSummary($recentCheckIns),
            'badges' => $this->badgeService->build($user),
        ];
    }

    /**
     * @param  Collection<int, WeightEntry>  $weightEntries
     * @return array<string, array<int, float|string>>
     */
    private function buildWeightChart(Collection $weightEntries): array
    {
        return [
            'labels' => $weightEntries
                ->map(static fn (WeightEntry $weightEntry): string => $weightEntry->recorded_on->format('d M'))
                ->all(),
            'weights' => $weightEntries
                ->map(static fn (WeightEntry $weightEntry): float => (float) $weightEntry->weight_kg)
                ->all(),
        ];
    }

    /**
     * Calculate the most recent weight change using dedicated entries first.
     *
     * @param  Collection<int, WeightEntry>  $recentWeightEntries
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
     * Calculate the dedicated weight change over the last 7 days.
     *
     * @param  Collection<int, WeightEntry>  $weightEntries
     */
    private function calculateWeightChangeOverPeriod(Collection $weightEntries): ?float
    {
        if ($weightEntries->count() < 2) {
            return null;
        }

        return round(
            (float) $weightEntries->last()->weight_kg - (float) $weightEntries->first()->weight_kg,
            2,
        );
    }

    /**
     * Calculate the streak of consecutive recorded days ending at the latest entry.
     *
     * @param  Collection<int, CheckIn>  $checkIns
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

    /**
     * Calculate the average sleep over the recent 7-day journal window.
     *
     * @param  Collection<int, CheckIn>  $checkIns
     */
    private function calculateAverageSleep(Collection $checkIns): ?float
    {
        $sleepValues = $checkIns
            ->pluck('sleep_hours')
            ->filter(static fn (mixed $value): bool => $value !== null);

        if ($sleepValues->isEmpty()) {
            return null;
        }

        return round((float) $sleepValues->avg(), 2);
    }

    /**
     * Build concise habit widgets from the recent 7-day window.
     *
     * @param  Collection<int, CheckIn>  $checkIns
     * @return array<int, array<string, string>>
     */
    private function buildHabitSummary(Collection $checkIns): array
    {
        $waterAverage = $this->formatAverage($checkIns->pluck('water_intake_liters'), 'L avg hydration');
        $stepsAverage = $this->formatAverage($checkIns->pluck('steps'), 'steps avg');
        $stressAverage = $this->formatAverage($checkIns->pluck('stress_level'), 'stress avg');

        return [
            [
                'label' => 'Last 7 days',
                'value' => $checkIns->count() . '/7',
                'hint' => 'check-ins recorded',
            ],
            [
                'label' => 'Hydration',
                'value' => $waterAverage['value'],
                'hint' => $waterAverage['hint'],
            ],
            [
                'label' => 'Steps',
                'value' => $stepsAverage['value'],
                'hint' => $stepsAverage['hint'],
            ],
            [
                'label' => 'Stress',
                'value' => $stressAverage['value'],
                'hint' => $stressAverage['hint'],
            ],
        ];
    }

    /**
     * @param  Collection<int, mixed>  $values
     * @return array<string, string>
     */
    private function formatAverage(Collection $values, string $hint): array
    {
        $filtered = $values->filter(static fn (mixed $value): bool => $value !== null);

        if ($filtered->isEmpty()) {
            return [
                'value' => '—',
                'hint' => 'no recent data',
            ];
        }

        $average = $filtered->avg();
        $isWholeNumberSeries = $filtered->every(static fn (mixed $value): bool => is_int($value) || ctype_digit((string) $value));

        return [
            'value' => $isWholeNumberSeries
                ? number_format((float) $average, 0)
                : number_format((float) $average, 1),
            'hint' => $hint,
        ];
    }
}
