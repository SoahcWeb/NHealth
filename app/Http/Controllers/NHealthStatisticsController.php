<?php

namespace App\Http\Controllers;

use App\Services\MonthlyStatisticsService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class NHealthStatisticsController extends Controller
{
    public function __construct(
        private readonly MonthlyStatisticsService $monthlyStatisticsService,
    ) {
    }

    /**
     * Display the authenticated user's current month health statistics.
     */
    public function index(Request $request): View
    {
        return view('nhealth.statistics.index', $this->monthlyStatisticsService->build($request->user()));
    }
}
