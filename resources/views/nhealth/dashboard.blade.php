<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-2 md:flex-row md:items-end md:justify-between">
            <div>
                <p class="text-xs font-semibold uppercase tracking-[0.35em] text-nethra-300">NHealth cockpit</p>
                <h2 class="text-3xl font-semibold text-white">Personal dashboard</h2>
            </div>
            <p class="max-w-2xl text-sm text-slate-400">
                Your private overview of health profile, goals, weight tracking and daily journal.
            </p>
        </div>
    </x-slot>

    <div class="mx-auto flex max-w-7xl flex-col gap-6 px-4 py-8 sm:px-6 lg:px-8">
        <section class="grid gap-4 md:grid-cols-2 xl:grid-cols-4">
            <article class="stat-card">
                <p class="stat-label">Current weight</p>
                <p class="stat-value">
                    {{ $stats['current_weight_kg'] !== null ? number_format($stats['current_weight_kg'], 2) . ' kg' : '—' }}
                </p>
                <p class="stat-hint">Latest dedicated entry or latest check-in snapshot.</p>
            </article>

            <article class="stat-card">
                <p class="stat-label">Weight entries</p>
                <p class="stat-value">{{ $stats['total_weight_entries'] }}</p>
                <p class="stat-hint">Total dedicated weigh-ins recorded.</p>
            </article>

            <article class="stat-card">
                <p class="stat-label">Check-ins</p>
                <p class="stat-value">{{ $stats['total_check_ins'] }}</p>
                <p class="stat-hint">Daily journal entries in your private history.</p>
            </article>

            <article class="stat-card">
                <p class="stat-label">Current streak</p>
                <p class="stat-value">{{ $stats['current_streak'] }} days</p>
                <p class="stat-hint">Consecutive check-in days from your latest entry.</p>
            </article>
        </section>

        <section class="grid gap-6 xl:grid-cols-[1.15fr,0.85fr]">
            <article class="panel">
                <div class="flex items-center justify-between gap-3">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-[0.35em] text-ankhor-300">Health profile</p>
                        <h3 class="mt-2 text-xl font-semibold text-white">Your baseline</h3>
                    </div>
                    <a href="{{ route('nhealth.profile.edit') }}" class="rounded-full border border-ankhor-400/40 bg-ankhor-500/10 px-4 py-2 text-sm font-medium text-ankhor-100 transition hover:border-ankhor-300 hover:bg-ankhor-500/20">
                        Edit profile
                    </a>
                </div>

                @if ($healthProfile)
                    <div class="mt-6 grid gap-4 md:grid-cols-2">
                        <div class="rounded-2xl border border-white/10 bg-white/5 p-4">
                            <p class="text-sm text-slate-400">Date of birth</p>
                            <p class="mt-2 text-lg font-semibold text-white">{{ optional($healthProfile->date_of_birth)->format('d M Y') ?: '—' }}</p>
                        </div>
                        <div class="rounded-2xl border border-white/10 bg-white/5 p-4">
                            <p class="text-sm text-slate-400">Height</p>
                            <p class="mt-2 text-lg font-semibold text-white">{{ $healthProfile->height_cm ? number_format((float) $healthProfile->height_cm, 2) . ' cm' : '—' }}</p>
                        </div>
                        <div class="rounded-2xl border border-white/10 bg-white/5 p-4">
                            <p class="text-sm text-slate-400">Activity level</p>
                            <p class="mt-2 text-lg font-semibold text-white">{{ $healthProfile->activity_level ?: '—' }}</p>
                        </div>
                        <div class="rounded-2xl border border-white/10 bg-white/5 p-4">
                            <p class="text-sm text-slate-400">Unit system</p>
                            <p class="mt-2 text-lg font-semibold text-white">{{ ucfirst($healthProfile->unit_system) }}</p>
                        </div>
                    </div>

                    <div class="mt-4 rounded-2xl border border-white/10 bg-white/5 p-4">
                        <p class="text-sm text-slate-400">Health notes</p>
                        <p class="mt-2 text-sm leading-6 text-slate-200">{{ $healthProfile->health_notes ?: 'No health notes saved yet.' }}</p>
                    </div>
                @else
                    <div class="mt-6 rounded-3xl border border-dashed border-white/10 bg-white/5 p-6 text-sm text-slate-400">
                        Your health profile has not been filled yet. Add your baseline information to personalize the cockpit.
                    </div>
                @endif
            </article>

            <article class="panel">
                <div class="flex items-center justify-between gap-3">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-[0.35em] text-nethra-300">Active goal</p>
                        <h3 class="mt-2 text-xl font-semibold text-white">Focus target</h3>
                    </div>
                    <a href="{{ route('nhealth.goals.index') }}" class="rounded-full border border-white/10 bg-white/5 px-4 py-2 text-sm font-medium text-slate-100 transition hover:bg-white/10">
                        Manage goals
                    </a>
                </div>

                @if ($activeGoal)
                    <div class="mt-6 rounded-2xl border border-white/10 bg-white/5 p-5">
                        <p class="text-xs uppercase tracking-[0.3em] text-slate-400">{{ $activeGoal->goal_type }}</p>
                        <p class="mt-3 text-2xl font-semibold text-white">{{ $activeGoal->title }}</p>
                        <p class="mt-2 text-sm text-slate-300">{{ $activeGoal->description ?: 'No description provided.' }}</p>

                        <div class="mt-5 grid gap-4 md:grid-cols-2">
                            <div>
                                <p class="text-sm text-slate-400">Target</p>
                                <p class="mt-2 text-lg font-semibold text-white">
                                    {{ $activeGoal->target_value ? number_format((float) $activeGoal->target_value, 2) . ' ' . $activeGoal->unit : '—' }}
                                </p>
                            </div>
                            <div>
                                <p class="text-sm text-slate-400">Target date</p>
                                <p class="mt-2 text-lg font-semibold text-white">{{ optional($activeGoal->target_date)->format('d M Y') ?: '—' }}</p>
                            </div>
                        </div>

                        <div class="mt-6 rounded-2xl border border-ankhor-400/20 bg-slate-950/50 p-4">
                            <div class="flex items-center justify-between gap-3">
                                <p class="text-sm font-medium text-white">Estimated progress</p>
                                @if ($progress['is_estimable'])
                                    <span class="text-sm text-nethra-200">{{ $progress['percentage'] }}%</span>
                                @endif
                            </div>

                            @if ($progress['is_estimable'])
                                <div class="mt-3 h-3 overflow-hidden rounded-full bg-white/10">
                                    <div class="h-full rounded-full bg-gradient-to-r from-nethra-400 to-ankhor-400" style="width: {{ $progress['percentage'] }}%"></div>
                                </div>
                                <p class="mt-3 text-sm text-slate-300">{{ $progress['remaining_label'] }}</p>
                            @else
                                <p class="mt-3 text-sm text-slate-400">{{ $progress['message'] }}</p>
                            @endif
                        </div>
                    </div>
                @else
                    <div class="mt-6 rounded-3xl border border-dashed border-white/10 bg-white/5 p-6 text-sm text-slate-400">
                        No active goal yet. Create one to track progress from the dashboard.
                    </div>
                @endif
            </article>
        </section>

        <section class="grid gap-6 xl:grid-cols-[0.9fr,1.1fr]">
            <article class="panel">
                <p class="text-xs font-semibold uppercase tracking-[0.35em] text-ankhor-300">Recent evolution</p>
                <h3 class="mt-2 text-xl font-semibold text-white">Latest movement</h3>

                <div class="mt-6 grid gap-4">
                    <div class="rounded-2xl border border-white/10 bg-white/5 p-4">
                        <p class="text-sm text-slate-400">Latest dedicated weight</p>
                        <p class="mt-2 text-2xl font-semibold text-white">
                            {{ $latestWeightEntry ? number_format((float) $latestWeightEntry->weight_kg, 2) . ' kg' : '—' }}
                        </p>
                        <p class="mt-2 text-sm text-slate-400">
                            {{ $latestWeightEntry ? 'Recorded on ' . $latestWeightEntry->recorded_on->format('d M Y') : 'No dedicated weight entry yet.' }}
                        </p>
                    </div>

                    <div class="rounded-2xl border border-white/10 bg-white/5 p-4">
                        <p class="text-sm text-slate-400">Recent change</p>
                        <p class="mt-2 text-2xl font-semibold text-white">
                            @if ($stats['recent_weight_change_kg'] !== null)
                                {{ $stats['recent_weight_change_kg'] > 0 ? '+' : '' }}{{ number_format($stats['recent_weight_change_kg'], 2) }} kg
                            @else
                                —
                            @endif
                        </p>
                        <p class="mt-2 text-sm text-slate-400">Difference between your latest and previous usable weight points.</p>
                    </div>
                </div>
            </article>

            <article class="panel">
                <p class="text-xs font-semibold uppercase tracking-[0.35em] text-nethra-300">Latest check-in</p>
                <h3 class="mt-2 text-xl font-semibold text-white">Daily journal snapshot</h3>

                @if ($latestCheckIn)
                    <div class="mt-6 grid gap-4 md:grid-cols-2">
                        <div class="rounded-2xl border border-white/10 bg-white/5 p-4">
                            <p class="text-sm text-slate-400">Date</p>
                            <p class="mt-2 text-lg font-semibold text-white">{{ $latestCheckIn->recorded_on->format('d M Y') }}</p>
                        </div>
                        <div class="rounded-2xl border border-white/10 bg-white/5 p-4">
                            <p class="text-sm text-slate-400">Sleep</p>
                            <p class="mt-2 text-lg font-semibold text-white">{{ $latestCheckIn->sleep_hours ? number_format((float) $latestCheckIn->sleep_hours, 2) . ' h' : '—' }}</p>
                        </div>
                        <div class="rounded-2xl border border-white/10 bg-white/5 p-4">
                            <p class="text-sm text-slate-400">Energy / Mood</p>
                            <p class="mt-2 text-lg font-semibold text-white">{{ $latestCheckIn->energy_level ?? '—' }} / {{ $latestCheckIn->mood_level ?? '—' }}</p>
                        </div>
                        <div class="rounded-2xl border border-white/10 bg-white/5 p-4">
                            <p class="text-sm text-slate-400">Stress</p>
                            <p class="mt-2 text-lg font-semibold text-white">{{ $latestCheckIn->stress_level ?? '—' }}</p>
                        </div>
                    </div>

                    <div class="mt-4 rounded-2xl border border-white/10 bg-white/5 p-4">
                        <p class="text-sm text-slate-400">Notes</p>
                        <p class="mt-2 text-sm leading-6 text-slate-200">{{ $latestCheckIn->notes ?: 'No notes saved for the latest check-in.' }}</p>
                    </div>
                @else
                    <div class="mt-6 rounded-3xl border border-dashed border-white/10 bg-white/5 p-6 text-sm text-slate-400">
                        No check-in yet. Start the daily journal to populate this cockpit section.
                    </div>
                @endif
            </article>
        </section>

        <section class="panel">
            <div class="flex flex-col gap-2 md:flex-row md:items-end md:justify-between">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-[0.35em] text-ankhor-300">Shortcuts</p>
                    <h3 class="mt-2 text-xl font-semibold text-white">Quick access</h3>
                </div>
                <p class="text-sm text-slate-400">Jump directly into the core NHealth workflows.</p>
            </div>

            <div class="mt-6 grid gap-4 md:grid-cols-2 xl:grid-cols-4">
                <a href="{{ route('nhealth.profile.edit') }}" class="rounded-3xl border border-white/10 bg-white/5 p-5 transition hover:bg-white/10">
                    <p class="text-xs uppercase tracking-[0.3em] text-slate-400">Profile</p>
                    <p class="mt-3 text-lg font-semibold text-white">Health profile</p>
                    <p class="mt-2 text-sm text-slate-400">Maintain baseline health information.</p>
                </a>
                <a href="{{ route('nhealth.goals.index') }}" class="rounded-3xl border border-white/10 bg-white/5 p-5 transition hover:bg-white/10">
                    <p class="text-xs uppercase tracking-[0.3em] text-slate-400">Goals</p>
                    <p class="mt-3 text-lg font-semibold text-white">Transformation goals</p>
                    <p class="mt-2 text-sm text-slate-400">Create, review and adjust active targets.</p>
                </a>
                <a href="{{ route('nhealth.weight.index') }}" class="rounded-3xl border border-white/10 bg-white/5 p-5 transition hover:bg-white/10">
                    <p class="text-xs uppercase tracking-[0.3em] text-slate-400">Weight</p>
                    <p class="mt-3 text-lg font-semibold text-white">Weight history</p>
                    <p class="mt-2 text-sm text-slate-400">Track dedicated weigh-ins over time.</p>
                </a>
                <a href="{{ route('check-ins.index') }}" class="rounded-3xl border border-white/10 bg-white/5 p-5 transition hover:bg-white/10">
                    <p class="text-xs uppercase tracking-[0.3em] text-slate-400">Journal</p>
                    <p class="mt-3 text-lg font-semibold text-white">Daily check-ins</p>
                    <p class="mt-2 text-sm text-slate-400">Capture sleep, mood, stress and notes.</p>
                </a>
            </div>
        </section>
    </div>
</x-app-layout>
