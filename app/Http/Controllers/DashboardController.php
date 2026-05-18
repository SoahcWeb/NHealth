<?php

namespace App\Http\Controllers;

use App\Models\CheckIn;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class DashboardController extends Controller
{
    /**
     * Display the private cockpit for the authenticated user.
     */
    public function index(Request $request): View
    {
        $user = $request->user();

        $allCheckIns = $user->checkIns()
            ->latest('recorded_on')
            ->get();

        $latestCheckIn = $allCheckIns->first();
        $recentCheckIns = $allCheckIns->take(5);

        $chartCheckIns = $user->checkIns()
            ->whereNotNull('weight_kg')
            ->latest('recorded_on')
            ->take(7)
            ->get()
            ->reverse()
            ->values();

        $sleepAverage = $user->checkIns()
            ->whereDate('recorded_on', '>=', now()->subDays(6)->toDateString())
            ->avg('sleep_hours');

        return view('dashboard', [
            'latestCheckIn' => $latestCheckIn,
            'recentCheckIns' => $recentCheckIns,
            'stats' => [
                'total_check_ins' => $allCheckIns->count(),
                'current_streak' => $this->calculateCurrentStreak($allCheckIns),
                'latest_weight_kg' => $latestCheckIn?->weight_kg,
                'average_sleep_hours' => $sleepAverage !== null ? round((float) $sleepAverage, 1) : null,
            ],
            'chart' => [
                'labels' => $chartCheckIns
                    ->map(static fn (CheckIn $checkIn): string => $checkIn->recorded_on->format('d M'))
                    ->all(),
                'weights' => $chartCheckIns
                    ->map(static fn (CheckIn $checkIn): ?float => $checkIn->weight_kg !== null ? (float) $checkIn->weight_kg : null)
                    ->all(),
            ],
        ]);
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
