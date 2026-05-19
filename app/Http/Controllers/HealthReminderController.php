<?php

namespace App\Http\Controllers;

use App\Models\HealthReminder;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class HealthReminderController extends Controller
{
    /**
     * Display the authenticated user's simple internal reminders.
     */
    public function index(Request $request): View
    {
        $userReminders = $request->user()->healthReminders()
            ->get()
            ->keyBy('reminder_key');

        $reminders = collect(HealthReminder::definitions())
            ->map(function (array $definition) use ($userReminders): array {
                $storedReminder = $userReminders->get($definition['key']);

                return [
                    ...$definition,
                    'is_enabled' => (bool) ($storedReminder?->is_enabled ?? false),
                ];
            })
            ->all();

        return view('nhealth.reminders.index', [
            'reminders' => $reminders,
            'activeRemindersCount' => collect($reminders)
                ->where('is_enabled', true)
                ->count(),
        ]);
    }

    /**
     * Persist the authenticated user's simple internal reminder toggles.
     */
    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'reminders' => ['nullable', 'array'],
            'reminders.*.is_enabled' => ['nullable', 'boolean'],
        ]);

        foreach (HealthReminder::definitions() as $definition) {
            $isEnabled = (bool) data_get($validated, 'reminders.' . $definition['key'] . '.is_enabled', false);

            $request->user()->healthReminders()->updateOrCreate(
                ['reminder_key' => $definition['key']],
                [
                    'reminder_name' => $definition['name'],
                    'is_enabled' => $isEnabled,
                ],
            );
        }

        return redirect()
            ->route('nhealth.reminders.index')
            ->with('status', 'Internal reminders updated.');
    }
}
