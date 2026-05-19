<?php

namespace App\Http\Controllers;

use App\Services\DashboardStatsService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Str;

class NHealthExportController extends Controller
{
    public function __construct(
        private readonly DashboardStatsService $dashboardStatsService,
    ) {
    }

    /**
     * Export a private NHealth progress summary as a PDF for the authenticated user.
     */
    public function summary(Request $request): Response
    {
        $user = $request->user();
        $dashboardData = $this->dashboardStatsService->build($user);
        $filename = 'nhealth-summary-' . Str::slug($user->name) . '-' . now()->format('Y-m-d') . '.pdf';

        $pdf = Pdf::loadView('nhealth.exports.summary', [
            'user' => $user,
            'healthProfile' => $dashboardData['healthProfile'],
            'activeGoal' => $dashboardData['activeGoal'],
            'latestWeightEntry' => $dashboardData['latestWeightEntry'],
            'latestCheckIn' => $dashboardData['latestCheckIn'],
            'stats' => $dashboardData['stats'],
            'progress' => $dashboardData['progress'],
            'unlockedBadges' => collect($dashboardData['badges']['items'])
                ->where('is_unlocked', true)
                ->values(),
            'exportedAt' => now(),
        ])->setPaper('a4');

        return $pdf->download($filename);
    }
}
