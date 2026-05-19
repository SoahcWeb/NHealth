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
                description: 'Enregistrez votre première entrée de journal quotidien privé.',
                currentCount: $checkIns->count(),
                targetCount: 1,
                unitLabel: 'check-ins',
                achievedAt: $this->checkInAchievedAt($checkIns, 1),
            ),
            $this->buildCountBadge(
                key: 'journal_7_days',
                title: '7 jours de journal',
                description: 'Enregistrez 7 jours de journal privé pour installer une première régularité.',
                currentCount: $checkIns->count(),
                targetCount: 7,
                unitLabel: 'jours de journal',
                achievedAt: $this->checkInAchievedAt($checkIns, 7),
            ),
            $this->buildCountBadge(
                key: 'journal_30_days',
                title: '30 jours de journal',
                description: 'Atteignez 30 jours de journal enregistrés dans votre cockpit santé.',
                currentCount: $checkIns->count(),
                targetCount: 30,
                unitLabel: 'jours de journal',
                achievedAt: $this->checkInAchievedAt($checkIns, 30),
            ),
            $this->buildCountBadge(
                key: 'first_weight_entry',
                title: 'Première entrée de poids',
                description: 'Enregistrez votre première mesure de poids dédiée.',
                currentCount: $weightEntries->count(),
                targetCount: 1,
                unitLabel: 'entrées de poids',
                achievedAt: $this->weightEntryAchievedAt($weightEntries, 1),
            ),
            $this->buildCountBadge(
                key: 'weight_10_entries',
                title: '10 entrées de poids',
                description: 'Construisez une tendance plus solide avec 10 pesées dédiées.',
                currentCount: $weightEntries->count(),
                targetCount: 10,
                unitLabel: 'entrées de poids',
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
            'status_label' => $isUnlocked ? 'Débloqué' : 'Verrouillé',
            'progress_label' => $progressCount . ' / ' . $targetCount . ' ' . $unitLabel,
            'achieved_label' => $isUnlocked && $achievedAt !== null
                ? 'Débloqué le ' . $achievedAt
                : 'Continuez pour débloquer ce badge.',
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
            'description' => 'Créez un objectif actif de transformation dans NHealth.',
            'is_unlocked' => $isUnlocked,
            'status_label' => $isUnlocked ? 'Débloqué' : 'Verrouillé',
            'progress_label' => $isUnlocked ? '1 / 1 objectif actif' : '0 / 1 objectif actif',
            'achieved_label' => $isUnlocked
                ? 'Débloqué le ' . $activeGoal->created_at->translatedFormat('d M Y')
                : 'Créez un objectif actif pour débloquer ce badge.',
        ];
    }

    /**
     * @param  Collection<int, CheckIn>  $checkIns
     */
    private function checkInAchievedAt(Collection $checkIns, int $targetCount): ?string
    {
        $entry = $checkIns->get($targetCount - 1);

        return $entry?->recorded_on?->translatedFormat('d M Y');
    }

    /**
     * @param  Collection<int, WeightEntry>  $weightEntries
     */
    private function weightEntryAchievedAt(Collection $weightEntries, int $targetCount): ?string
    {
        $entry = $weightEntries->get($targetCount - 1);

        return $entry?->recorded_on?->translatedFormat('d M Y');
    }
}
