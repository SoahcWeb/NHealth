<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCheckInRequest;
use Carbon\CarbonImmutable;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class CheckInController extends Controller
{
    /**
     * Show the user's journal of daily health check-ins.
     */
    public function index(Request $request): View
    {
        return view('check-ins.index', [
            'checkIns' => $request->user()->checkIns()->latest('recorded_on')->simplePaginate(10),
            'defaultRecordedOn' => now()->toDateString(),
        ]);
    }

    /**
     * Store a new daily check-in for the authenticated user.
     */
    public function store(StoreCheckInRequest $request): RedirectResponse
    {
        $recordedOn = CarbonImmutable::parse($request->validated('recorded_on'))->toDateString();
        $payload = $this->validatedCheckInPayload($request, $recordedOn);

        $checkIn = $request->user()->checkIns()
            ->whereDate('recorded_on', $recordedOn)
            ->first();

        if ($checkIn) {
            $checkIn->fill($payload)->save();
        } else {
            $checkIn = $request->user()->checkIns()->create($payload);
        }

        return redirect()
            ->route('check-ins.index')
            ->with('status', $checkIn->wasRecentlyCreated ? 'Check-in saved.' : 'Check-in updated for that date.');
    }

    /**
     * Build a normalized payload that stays consistent with the daily unique key.
     *
     * @return array<string, float|int|string|null>
     */
    private function validatedCheckInPayload(StoreCheckInRequest $request, string $recordedOn): array
    {
        return [
            'recorded_on' => $recordedOn,
            'weight_kg' => $request->validated('weight_kg'),
            'sleep_hours' => $request->validated('sleep_hours'),
            'steps' => $request->validated('steps'),
            'energy_level' => $request->validated('energy_level'),
            'mood_level' => $request->validated('mood_level'),
            'notes' => $request->validated('notes'),
        ];
    }
}
