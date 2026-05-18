<?php

namespace App\Http\Controllers;

use App\Services\DashboardStatsService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class NHealthDashboardController extends Controller
{
    public function __construct(
        private readonly DashboardStatsService $dashboardStatsService,
    ) {
    }

    /**
     * Display the private NHealth cockpit for the authenticated user.
     */
    public function index(Request $request): View
    {
        return view('nhealth.dashboard', $this->dashboardStatsService->build($request->user()));
    }
}
