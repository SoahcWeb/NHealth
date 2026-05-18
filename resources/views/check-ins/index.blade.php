<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-2 md:flex-row md:items-end md:justify-between">
            <div>
                <p class="text-xs font-semibold uppercase tracking-[0.35em] text-ankhor-300">Daily journal</p>
                <h2 class="text-3xl font-semibold text-white">Daily check-ins</h2>
            </div>
            <p class="max-w-2xl text-sm text-slate-400">
                One private journal entry per day, attached only to your account and reusable for today's update.
            </p>
        </div>
    </x-slot>

    <div class="mx-auto flex max-w-7xl flex-col gap-6 px-4 py-8 sm:px-6 lg:px-8">
        @if (session('status'))
            <div class="rounded-2xl border border-emerald-400/30 bg-emerald-500/10 px-4 py-3 text-sm text-emerald-200">
                {{ session('status') }}
            </div>
        @endif

        <section class="grid gap-6 lg:grid-cols-[1.1fr,0.9fr]">
            <div class="panel">
                <div class="flex items-center justify-between gap-3">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-[0.35em] text-nethra-300">
                            {{ $todayCheckIn ? 'Today already logged' : 'Today not logged yet' }}
                        </p>
                        <h3 class="mt-2 text-xl font-semibold text-white">
                            {{ $todayCheckIn ? 'Update today\'s journal' : 'Capture today\'s journal' }}
                        </h3>
                    </div>
                    <div class="rounded-full border border-white/10 bg-white/5 px-3 py-1 text-xs text-slate-300">
                        One entry per date
                    </div>
                </div>

                <form method="POST" action="{{ route('check-ins.store') }}" class="mt-6 grid gap-5">
                    @csrf

                    <div class="grid gap-5 md:grid-cols-2">
                        <div>
                            <x-input-label for="recorded_on" value="Date" />
                            <x-text-input id="recorded_on" name="recorded_on" type="date" class="mt-2 block w-full" :value="old('recorded_on', optional($todayCheckIn?->recorded_on)->toDateString() ?? $defaultRecordedOn)" required />
                            <x-input-error :messages="$errors->get('recorded_on')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="weight_kg" value="Weight (kg)" />
                            <x-text-input id="weight_kg" name="weight_kg" type="number" step="0.01" min="0" class="mt-2 block w-full" :value="old('weight_kg', $todayCheckIn?->weight_kg)" />
                            <x-input-error :messages="$errors->get('weight_kg')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="sleep_hours" value="Sleep (hours)" />
                            <x-text-input id="sleep_hours" name="sleep_hours" type="number" step="0.25" min="0" max="24" class="mt-2 block w-full" :value="old('sleep_hours', $todayCheckIn?->sleep_hours)" />
                            <x-input-error :messages="$errors->get('sleep_hours')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="steps" value="Steps" />
                            <x-text-input id="steps" name="steps" type="number" min="0" max="100000" class="mt-2 block w-full" :value="old('steps', $todayCheckIn?->steps)" />
                            <x-input-error :messages="$errors->get('steps')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="energy_level" value="Energy level (1-10)" />
                            <x-text-input id="energy_level" name="energy_level" type="number" min="1" max="10" class="mt-2 block w-full" :value="old('energy_level', $todayCheckIn?->energy_level)" />
                            <x-input-error :messages="$errors->get('energy_level')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="mood_level" value="Mood level (1-10)" />
                            <x-text-input id="mood_level" name="mood_level" type="number" min="1" max="10" class="mt-2 block w-full" :value="old('mood_level', $todayCheckIn?->mood_level)" />
                            <x-input-error :messages="$errors->get('mood_level')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="stress_level" value="Stress level (1-10)" />
                            <x-text-input id="stress_level" name="stress_level" type="number" min="1" max="10" class="mt-2 block w-full" :value="old('stress_level', $todayCheckIn?->stress_level)" />
                            <x-input-error :messages="$errors->get('stress_level')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="water_intake_liters" value="Water (liters)" />
                            <x-text-input id="water_intake_liters" name="water_intake_liters" type="number" step="0.1" min="0" max="20" class="mt-2 block w-full" :value="old('water_intake_liters', $todayCheckIn?->water_intake_liters)" />
                            <x-input-error :messages="$errors->get('water_intake_liters')" class="mt-2" />
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
                        >{{ old('notes', $todayCheckIn?->notes) }}</textarea>
                        <x-input-error :messages="$errors->get('notes')" class="mt-2" />
                    </div>

                    <div class="flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
                        <p class="text-sm text-slate-400">
                            Saving an existing date updates that journal entry instead of creating a duplicate.
                        </p>
                        <x-primary-button>{{ $todayCheckIn ? 'Update daily check-in' : 'Save daily check-in' }}</x-primary-button>
                    </div>
                </form>
            </div>

            <div class="panel">
                <p class="text-xs font-semibold uppercase tracking-[0.35em] text-ankhor-300">Today at a glance</p>
                <h3 class="mt-2 text-xl font-semibold text-white">Journal status</h3>

                <div class="mt-6 grid gap-4">
                    <div class="rounded-2xl border border-white/10 bg-white/5 p-4">
                        <p class="text-sm font-semibold text-white">{{ $todayCheckIn ? 'Today is already recorded' : 'No entry for today yet' }}</p>
                        <p class="mt-2 text-sm text-slate-400">
                            {{ $todayCheckIn ? 'The form is prefilled with today\'s data so you can update it quickly.' : 'Create today\'s entry to keep your streak and health context up to date.' }}
                        </p>
                    </div>
                    <div class="rounded-2xl border border-white/10 bg-white/5 p-4">
                        <p class="text-sm font-semibold text-white">Private by design</p>
                        <p class="mt-2 text-sm text-slate-400">Every check-in belongs to your `user_id` and stays isolated from other accounts.</p>
                    </div>
                    <div class="rounded-2xl border border-white/10 bg-white/5 p-4">
                        <p class="text-sm font-semibold text-white">What gets tracked</p>
                        <p class="mt-2 text-sm text-slate-400">Date, weight, sleep, energy, mood, stress, hydration and daily notes in one clean journal entry.</p>
                    </div>

                    @if ($todayCheckIn)
                        <div class="rounded-2xl border border-nethra-400/20 bg-nethra-500/10 p-4">
                            <p class="text-sm font-semibold text-white">Current snapshot for today</p>
                            <div class="mt-3 grid grid-cols-2 gap-3 text-sm text-slate-200">
                                <p>Weight: {{ $todayCheckIn->weight_kg ? number_format((float) $todayCheckIn->weight_kg, 2) . ' kg' : '—' }}</p>
                                <p>Sleep: {{ $todayCheckIn->sleep_hours ? number_format((float) $todayCheckIn->sleep_hours, 2) . ' h' : '—' }}</p>
                                <p>Energy: {{ $todayCheckIn->energy_level ?? '—' }}</p>
                                <p>Mood: {{ $todayCheckIn->mood_level ?? '—' }}</p>
                                <p>Stress: {{ $todayCheckIn->stress_level ?? '—' }}</p>
                                <p>Water: {{ $todayCheckIn->water_intake_liters ? number_format((float) $todayCheckIn->water_intake_liters, 1) . ' L' : '—' }}</p>
                            </div>
                        </div>
                    @endif

                    <div class="rounded-2xl border border-white/10 bg-white/5 p-4">
                        <p class="text-sm font-semibold text-white">Journal workflow</p>
                        <p class="mt-2 text-sm text-slate-400">You can revisit any date by changing the date field. If an entry already exists for that day, it will be updated automatically.</p>
                    </div>
                </div>
            </div>
        </section>

        <section class="panel">
            <div class="flex items-center justify-between gap-3">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-[0.35em] text-nethra-300">History</p>
                    <h3 class="mt-2 text-xl font-semibold text-white">Recent journal entries</h3>
                </div>
                <p class="text-sm text-slate-400">Latest first, private to your account, one day per card.</p>
            </div>

            <div class="mt-6 grid gap-4">
                @forelse ($checkIns as $checkIn)
                    <article class="rounded-3xl border border-white/10 bg-slate-950/60 p-5">
                        <div class="flex flex-col gap-3 lg:flex-row lg:items-start lg:justify-between">
                            <div>
                                <p class="text-xs uppercase tracking-[0.3em] text-slate-400">Entry date</p>
                                <h4 class="mt-2 text-xl font-semibold text-white">{{ $checkIn->recorded_on->format('d M Y') }}</h4>
                            </div>
                            <div class="rounded-full border border-white/10 bg-white/5 px-3 py-1 text-xs text-slate-300">
                                {{ $checkIn->recorded_on->isToday() ? 'Today' : 'Journal entry' }}
                            </div>
                        </div>

                        <div class="mt-5 grid gap-4 md:grid-cols-2 xl:grid-cols-4">
                            <div class="rounded-2xl border border-white/10 bg-white/5 p-4">
                                <p class="text-xs uppercase tracking-[0.25em] text-slate-400">Weight</p>
                                <p class="mt-2 text-lg font-semibold text-white">{{ $checkIn->weight_kg ? number_format((float) $checkIn->weight_kg, 2) . ' kg' : '—' }}</p>
                            </div>
                            <div class="rounded-2xl border border-white/10 bg-white/5 p-4">
                                <p class="text-xs uppercase tracking-[0.25em] text-slate-400">Sleep</p>
                                <p class="mt-2 text-lg font-semibold text-white">{{ $checkIn->sleep_hours ? number_format((float) $checkIn->sleep_hours, 2) . ' h' : '—' }}</p>
                            </div>
                            <div class="rounded-2xl border border-white/10 bg-white/5 p-4">
                                <p class="text-xs uppercase tracking-[0.25em] text-slate-400">Energy / Mood</p>
                                <p class="mt-2 text-lg font-semibold text-white">{{ $checkIn->energy_level ?? '—' }} / {{ $checkIn->mood_level ?? '—' }}</p>
                            </div>
                            <div class="rounded-2xl border border-white/10 bg-white/5 p-4">
                                <p class="text-xs uppercase tracking-[0.25em] text-slate-400">Stress / Water</p>
                                <p class="mt-2 text-lg font-semibold text-white">
                                    {{ $checkIn->stress_level ?? '—' }} / {{ $checkIn->water_intake_liters ? number_format((float) $checkIn->water_intake_liters, 1) . ' L' : '—' }}
                                </p>
                            </div>
                        </div>

                        <div class="mt-4 grid gap-4 md:grid-cols-[0.35fr,1fr]">
                            <div class="rounded-2xl border border-white/10 bg-white/5 p-4">
                                <p class="text-xs uppercase tracking-[0.25em] text-slate-400">Steps</p>
                                <p class="mt-2 text-lg font-semibold text-white">{{ $checkIn->steps ? number_format($checkIn->steps) : '—' }}</p>
                            </div>
                            <div class="rounded-2xl border border-white/10 bg-white/5 p-4">
                                <p class="text-xs uppercase tracking-[0.25em] text-slate-400">Notes</p>
                                <p class="mt-2 text-sm leading-6 text-slate-300">{{ $checkIn->notes ?: 'No notes for this day.' }}</p>
                            </div>
                        </div>
                    </article>
                @empty
                    <div class="rounded-3xl border border-dashed border-white/10 bg-white/5 p-8 text-center text-slate-400">
                        No check-ins yet. Add your first private journal entry to start building your daily history.
                    </div>
                @endforelse
            </div>

            <div class="mt-5">
                {{ $checkIns->links() }}
            </div>
        </section>
    </div>
</x-app-layout>
