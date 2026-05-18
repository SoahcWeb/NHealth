<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreWeightEntryRequest;
use App\Http\Requests\UpdateWeightEntryRequest;
use App\Models\WeightEntry;
use Carbon\CarbonImmutable;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class WeightEntryController extends Controller
{
    /**
     * Display the authenticated user's weight history.
     */
    public function index(Request $request): View
    {
        return view('nhealth.weight.index', [
            'weightEntries' => $request->user()->weightEntries()->latest('recorded_on')->paginate(15),
            'defaultRecordedOn' => now()->toDateString(),
        ]);
    }

    /**
     * Store a dedicated weight entry for the authenticated user.
     */
    public function store(StoreWeightEntryRequest $request): RedirectResponse
    {
        $recordedOn = CarbonImmutable::parse($request->validated('recorded_on'))->toDateString();
        $payload = $this->validatedWeightEntryPayload($request->validated(), $recordedOn);

        $weightEntry = $request->user()->weightEntries()
            ->whereDate('recorded_on', $recordedOn)
            ->first();

        if ($weightEntry) {
            $weightEntry->fill($payload)->save();
        } else {
            $weightEntry = $request->user()->weightEntries()->create($payload);
        }

        return redirect()
            ->route('nhealth.weight.index')
            ->with('status', $weightEntry->wasRecentlyCreated ? 'Weight entry saved.' : 'Weight entry updated for that date.');
    }

    /**
     * Show the form for editing one of the authenticated user's weight entries.
     */
    public function edit(Request $request, WeightEntry $weightEntry): View
    {
        return view('nhealth.weight.edit', [
            'weightEntry' => $this->findUserWeightEntryOrFail($request, $weightEntry),
        ]);
    }

    /**
     * Update one of the authenticated user's weight entries.
     */
    public function update(UpdateWeightEntryRequest $request, WeightEntry $weightEntry): RedirectResponse
    {
        $weightEntry = $this->findUserWeightEntryOrFail($request, $weightEntry);
        $validated = $request->validated();

        if (array_key_exists('recorded_on', $validated)) {
            $validated['recorded_on'] = CarbonImmutable::parse($validated['recorded_on'])->toDateString();
        }

        $weightEntry->update($validated);

        return redirect()
            ->route('nhealth.weight.index')
            ->with('status', 'Weight entry updated.');
    }

    /**
     * Remove one of the authenticated user's weight entries.
     */
    public function destroy(Request $request, WeightEntry $weightEntry): RedirectResponse
    {
        $this->findUserWeightEntryOrFail($request, $weightEntry)->delete();

        return redirect()
            ->route('nhealth.weight.index')
            ->with('status', 'Weight entry deleted.');
    }

    /**
     * Resolve a weight entry only within the authenticated user's scope.
     */
    private function findUserWeightEntryOrFail(Request $request, WeightEntry $weightEntry): WeightEntry
    {
        return $request->user()->weightEntries()
            ->whereKey($weightEntry->getKey())
            ->firstOrFail();
    }

    /**
     * Build a normalized payload that stays consistent with the daily unique key.
     *
     * @param  array<string, mixed>  $validated
     * @return array<string, mixed>
     */
    private function validatedWeightEntryPayload(array $validated, string $recordedOn): array
    {
        $validated['recorded_on'] = $recordedOn;

        return $validated;
    }
}
