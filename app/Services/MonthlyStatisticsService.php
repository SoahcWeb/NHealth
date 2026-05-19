<?php

namespace App\Services;

use App\Models\CheckIn;
use App\Models\User;
use App\Models\WeightEntry;
use Carbon\CarbonImmutable;
use Illuminate\Support\Collection;

class MonthlyStatisticsService
{
    public function __construct(
        private readonly BadgeService $badgeService,
    ) {
    }

    /**
     * Build the current month NHealth statistics for one authenticated user.
     *
     * @return array<string, mixed>
     */
    public function build(User $user): array
    {
        $monthStart = CarbonImmutable::now()->startOfMonth();
        $monthEnd = CarbonImmutable::now()->endOfMonth();

        $monthlyCheckIns = $user->checkIns()
            ->whereBetween('recorded_on', [$monthStart->toDateString(), $monthEnd->toDateString()])
            ->orderBy('recorded_on')
            ->get();

        $monthlyWeightEntries = $user->weightEntries()
            ->whereBetween('recorded_on', [$monthStart->toDateString(), $monthEnd->toDateString()])
            ->orderBy('recorded_on')
            ->get();

        $unlockedBadges = collect($this->badgeService->build($user)['items'])
            ->where('is_unlocked', true)
            ->values()
            ->all();

        $startWeight = $monthlyWeightEntries->first();
        $currentWeight = $monthlyWeightEntries->last();

        return [
            'monthLabel' => $monthStart->translatedFormat('F Y'),
            'monthRangeLabel' => $monthStart->format('d M Y') . ' - ' . $monthEnd->format('d M Y'),
            'stats' => [
                'check_ins_count' => $monthlyCheckIns->count(),
                'average_sleep_hours' => $this->calculateAverage($monthlyCheckIns->pluck('sleep_hours')),
                'average_energy_level' => $this->calculateAverage($monthlyCheckIns->pluck('energy_level')),
                'average_mood_level' => $this->calculateAverage($monthlyCheckIns->pluck('mood_level')),
                'month_start_weight_kg' => $startWeight?->weight_kg !== null ? (float) $startWeight->weight_kg : null,
                'current_weight_kg' => $currentWeight?->weight_kg !== null ? (float) $currentWeight->weight_kg : null,
                'weight_change_kg' => $this->calculateWeightChange($monthlyWeightEntries),
                'weight_entries_count' => $monthlyWeightEntries->count(),
                'unlocked_badges_count' => count($unlockedBadges),
            ],
            'weightChart' => $this->buildWeightChart($monthlyWeightEntries),
            'unlockedBadges' => $unlockedBadges,
        ];
    }

    /**
     * @param  Collection<int, mixed>  $values
     */
    private function calculateAverage(Collection $values): ?float
    {
        $filtered = $values->filter(static fn (mixed $value): bool => $value !== null);

        if ($filtered->isEmpty()) {
            return null;
        }

        return round((float) $filtered->avg(), 2);
    }

    /**
     * @param  Collection<int, WeightEntry>  $weightEntries
     */
    private function calculateWeightChange(Collection $weightEntries): ?float
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
}
