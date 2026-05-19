<?php

namespace App\Services;

use App\Models\CheckIn;
use App\Models\Goal;
use App\Models\User;
use App\Models\WeightEntry;
use Illuminate\Support\Collection;

class BadgeService
{
    /**
     * Build lightweight MVP badges directly from the user's existing private data.
     *
     * @return array{items: array<int, array<string, mixed>>, unlocked_count: int, total_count: int}
     */
    public function build(User $user): array
    {
        $checkIns = $user->checkIns()
            ->orderBy('recorded_on')
            ->get();
        $weightEntries = $user->weightEntries()
            ->orderBy('recorded_on')
            ->get();
        $activeGoals = $user->activeGoals()
            ->orderBy('created_at')
            ->get();

        $items = [
            $this->buildCountBadge(
                key: 'first_check_in',
                title: 'Premier check-in',
                description: 'Record your first private daily journal entry.',
                currentCount: $checkIns->count(),
                targetCount: 1,
                unitLabel: 'check-ins',
                achievedAt: $this->checkInAchievedAt($checkIns, 1),
            ),
            $this->buildCountBadge(
                key: 'journal_7_days',
                title: '7 jours de journal',
                description: 'Log 7 private journal days to build early consistency.',
                currentCount: $checkIns->count(),
                targetCount: 7,
                unitLabel: 'journal days',
                achievedAt: $this->checkInAchievedAt($checkIns, 7),
            ),
            $this->buildCountBadge(
                key: 'journal_30_days',
                title: '30 jours de journal',
                description: 'Reach 30 logged journal days in your health cockpit.',
                currentCount: $checkIns->count(),
                targetCount: 30,
                unitLabel: 'journal days',
                achievedAt: $this->checkInAchievedAt($checkIns, 30),
            ),
            $this->buildCountBadge(
                key: 'first_weight_entry',
                title: 'Première entrée de poids',
                description: 'Capture your first dedicated weight measurement.',
                currentCount: $weightEntries->count(),
                targetCount: 1,
                unitLabel: 'weight entries',
                achievedAt: $this->weightEntryAchievedAt($weightEntries, 1),
            ),
            $this->buildCountBadge(
                key: 'weight_10_entries',
                title: '10 entrées de poids',
                description: 'Build a stronger trend with 10 dedicated weigh-ins.',
                currentCount: $weightEntries->count(),
                targetCount: 10,
                unitLabel: 'weight entries',
                achievedAt: $this->weightEntryAchievedAt($weightEntries, 10),
            ),
            $this->buildGoalBadge($activeGoals),
        ];

        return [
            'items' => $items,
            'unlocked_count' => collect($items)->where('is_unlocked', true)->count(),
            'total_count' => count($items),
        ];
    }

    /**
     * @return array<string, mixed>
     */
    private function buildCountBadge(
        string $key,
        string $title,
        string $description,
        int $currentCount,
        int $targetCount,
        string $unitLabel,
        ?string $achievedAt,
    ): array {
        $isUnlocked = $currentCount >= $targetCount;
        $progressCount = min($currentCount, $targetCount);

        return [
            'key' => $key,
            'title' => $title,
            'description' => $description,
            'is_unlocked' => $isUnlocked,
            'status_label' => $isUnlocked ? 'Unlocked' : 'Locked',
            'progress_label' => $progressCount . ' / ' . $targetCount . ' ' . $unitLabel,
            'achieved_label' => $isUnlocked && $achievedAt !== null
                ? 'Unlocked on ' . $achievedAt
                : 'Keep going to unlock this badge.',
        ];
    }

    /**
     * @param  Collection<int, Goal>  $activeGoals
     * @return array<string, mixed>
     */
    private function buildGoalBadge(Collection $activeGoals): array
    {
        $activeGoal = $activeGoals->first();
        $isUnlocked = $activeGoal !== null;

        return [
            'key' => 'active_goal_created',
            'title' => 'Objectif actif créé',
            'description' => 'Create an active transformation goal in NHealth.',
            'is_unlocked' => $isUnlocked,
            'status_label' => $isUnlocked ? 'Unlocked' : 'Locked',
            'progress_label' => $isUnlocked ? '1 / 1 active goal' : '0 / 1 active goal',
            'achieved_label' => $isUnlocked
                ? 'Unlocked on ' . $activeGoal->created_at->format('d M Y')
                : 'Create an active goal to unlock this badge.',
        ];
    }

    /**
     * @param  Collection<int, CheckIn>  $checkIns
     */
    private function checkInAchievedAt(Collection $checkIns, int $targetCount): ?string
    {
        $entry = $checkIns->get($targetCount - 1);

        return $entry?->recorded_on?->format('d M Y');
    }

    /**
     * @param  Collection<int, WeightEntry>  $weightEntries
     */
    private function weightEntryAchievedAt(Collection $weightEntries, int $targetCount): ?string
    {
        $entry = $weightEntries->get($targetCount - 1);

        return $entry?->recorded_on?->format('d M Y');
    }
}
