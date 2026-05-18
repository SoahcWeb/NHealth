<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-2 md:flex-row md:items-end md:justify-between">
            <div>
                <p class="text-xs font-semibold uppercase tracking-[0.35em] text-ankhor-300">Daily journal</p>
                <h2 class="text-3xl font-semibold text-white">Health check-ins</h2>
            </div>
            <p class="max-w-2xl text-sm text-slate-400">
                One private entry per day, attached to your account only.
            </p>
        </div>
    </x-slot>

    <div class="mx-auto flex max-w-7xl flex-col gap-6 px-4 py-8 sm:px-6 lg:px-8">
        @if (session('status'))
            <div class="rounded-2xl border border-emerald-400/30 bg-emerald-500/10 px-4 py-3 text-sm text-emerald-200">
                {{ session('status') }}
            </div>
        @endif

        <section class="grid gap-6 lg:grid-cols-[1.05fr,0.95fr]">
            <div class="panel">
                <div class="flex items-center justify-between gap-3">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-[0.35em] text-nethra-300">New entry</p>
                        <h3 class="mt-2 text-xl font-semibold text-white">Capture the day</h3>
                    </div>
                    <div class="rounded-full border border-white/10 bg-white/5 px-3 py-1 text-xs text-slate-300">
                        Private by design
                    </div>
                </div>

                <form method="POST" action="{{ route('check-ins.store') }}" class="mt-6 grid gap-5">
                    @csrf

                    <div class="grid gap-5 md:grid-cols-2">
                        <div>
                            <x-input-label for="recorded_on" value="Date" />
                            <x-text-input id="recorded_on" name="recorded_on" type="date" class="mt-2 block w-full" :value="old('recorded_on', $defaultRecordedOn)" required />
                            <x-input-error :messages="$errors->get('recorded_on')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="weight_kg" value="Weight (kg)" />
                            <x-text-input id="weight_kg" name="weight_kg" type="number" step="0.1" min="20" max="500" class="mt-2 block w-full" :value="old('weight_kg')" />
                            <x-input-error :messages="$errors->get('weight_kg')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="sleep_hours" value="Sleep (hours)" />
                            <x-text-input id="sleep_hours" name="sleep_hours" type="number" step="0.1" min="0" max="24" class="mt-2 block w-full" :value="old('sleep_hours')" />
                            <x-input-error :messages="$errors->get('sleep_hours')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="steps" value="Steps" />
                            <x-text-input id="steps" name="steps" type="number" min="0" max="100000" class="mt-2 block w-full" :value="old('steps')" />
                            <x-input-error :messages="$errors->get('steps')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="energy_level" value="Energy level (1-10)" />
                            <x-text-input id="energy_level" name="energy_level" type="number" min="1" max="10" class="mt-2 block w-full" :value="old('energy_level')" />
                            <x-input-error :messages="$errors->get('energy_level')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="mood_level" value="Mood level (1-10)" />
                            <x-text-input id="mood_level" name="mood_level" type="number" min="1" max="10" class="mt-2 block w-full" :value="old('mood_level')" />
                            <x-input-error :messages="$errors->get('mood_level')" class="mt-2" />
                        </div>
                    </div>

                    <div>
                        <x-input-label for="notes" value="Notes" />
                        <textarea
                            id="notes"
                            name="notes"
                            rows="4"
                            class="mt-2 block w-full rounded-2xl border border-white/10 bg-slate-950/70 px-4 py-3 text-sm text-slate-100 placeholder:text-slate-500 focus:border-ankhor-400 focus:ring-ankhor-400"
                            placeholder="Workout, meals, recovery, stress, wins..."
                        >{{ old('notes') }}</textarea>
                        <x-input-error :messages="$errors->get('notes')" class="mt-2" />
                    </div>

                    <div class="flex justify-end">
                        <x-primary-button>Save check-in</x-primary-button>
                    </div>
                </form>
            </div>

            <div class="panel">
                <p class="text-xs font-semibold uppercase tracking-[0.35em] text-ankhor-300">How to use it</p>
                <h3 class="mt-2 text-xl font-semibold text-white">A solid foundation for the cockpit</h3>

                <div class="mt-6 grid gap-4">
                    <div class="rounded-2xl border border-white/10 bg-white/5 p-4">
                        <p class="text-sm font-semibold text-white">One entry per day</p>
                        <p class="mt-2 text-sm text-slate-400">
                            Saving the same date again updates that day instead of duplicating data.
                        </p>
                    </div>
                    <div class="rounded-2xl border border-white/10 bg-white/5 p-4">
                        <p class="text-sm font-semibold text-white">User ownership enforced</p>
                        <p class="mt-2 text-sm text-slate-400">
                            Every check-in is linked to `user_id` and only queried through the authenticated user.
                        </p>
                    </div>
                    <div class="rounded-2xl border border-white/10 bg-white/5 p-4">
                        <p class="text-sm font-semibold text-white">Ready for next steps</p>
                        <p class="mt-2 text-sm text-slate-400">
                            This structure will support goals, body measurements, habits, symptoms and progress charts cleanly.
                        </p>
                    </div>
                </div>
            </div>
        </section>

        <section class="panel">
            <div class="flex items-center justify-between gap-3">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-[0.35em] text-nethra-300">History</p>
                    <h3 class="mt-2 text-xl font-semibold text-white">Recent entries</h3>
                </div>
                <p class="text-sm text-slate-400">Latest first, private to your account.</p>
            </div>

            <div class="mt-6 overflow-hidden rounded-2xl border border-white/10">
                <table class="min-w-full divide-y divide-white/10 text-sm">
                    <thead class="bg-white/5 text-left text-xs uppercase tracking-[0.3em] text-slate-400">
                        <tr>
                            <th class="px-4 py-3">Date</th>
                            <th class="px-4 py-3">Weight</th>
                            <th class="px-4 py-3">Sleep</th>
                            <th class="px-4 py-3">Steps</th>
                            <th class="px-4 py-3">Energy</th>
                            <th class="px-4 py-3">Mood</th>
                            <th class="px-4 py-3">Notes</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-white/10 bg-slate-950/60 text-slate-200">
                        @forelse ($checkIns as $checkIn)
                            <tr class="align-top">
                                <td class="px-4 py-4 font-medium text-white">{{ $checkIn->recorded_on->format('d M Y') }}</td>
                                <td class="px-4 py-4">{{ $checkIn->weight_kg ? number_format((float) $checkIn->weight_kg, 1) . ' kg' : '—' }}</td>
                                <td class="px-4 py-4">{{ $checkIn->sleep_hours ? number_format((float) $checkIn->sleep_hours, 1) . ' h' : '—' }}</td>
                                <td class="px-4 py-4">{{ $checkIn->steps ? number_format($checkIn->steps) : '—' }}</td>
                                <td class="px-4 py-4">{{ $checkIn->energy_level ?? '—' }}</td>
                                <td class="px-4 py-4">{{ $checkIn->mood_level ?? '—' }}</td>
                                <td class="px-4 py-4 text-slate-400">{{ $checkIn->notes ?: '—' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-4 py-8 text-center text-slate-400">
                                    No check-ins yet. Add your first private entry to start the cockpit.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-5">
                {{ $checkIns->links() }}
            </div>
        </section>
    </div>
</x-app-layout>
