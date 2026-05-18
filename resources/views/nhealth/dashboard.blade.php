<x-app-layout>
    <x-slot name="header">
        <x-nhealth.page-header
            eyebrow="NHealth cockpit"
            title="Personal dashboard"
            description="Your private overview of health profile, goals, weight tracking and daily journal."
        >
            <x-slot name="action">
                <a href="{{ route('check-ins.index') }}" class="nhealth-accent-link">Open daily journal</a>
            </x-slot>
        </x-nhealth.page-header>
    </x-slot>

    <div class="nhealth-shell">
        <section class="grid gap-4 md:grid-cols-2 xl:grid-cols-4">
            <x-nhealth.stat-card
                label="Current weight"
                :value="$stats['current_weight_kg'] !== null ? number_format($stats['current_weight_kg'], 2) . ' kg' : '—'"
                hint="Latest dedicated entry or latest check-in snapshot."
                variant="accent"
            />
            <x-nhealth.stat-card
                label="Weight entries"
                :value="$stats['total_weight_entries']"
                hint="Total dedicated weigh-ins recorded."
            />
            <x-nhealth.stat-card
                label="Check-ins this month"
                :value="$stats['check_ins_this_month']"
                :hint="$stats['total_check_ins'] . ' total daily journal entries.'"
            />
            <x-nhealth.stat-card
                label="Current streak"
                :value="$stats['current_streak'] . ' days'"
                hint="Consecutive check-in days from your latest entry."
                variant="secondary"
            />
        </section>

        <section class="grid gap-4 md:grid-cols-2 xl:grid-cols-4">
            <x-nhealth.stat-card
                label="Sleep average"
                :value="$stats['average_sleep_hours_7d'] !== null ? number_format($stats['average_sleep_hours_7d'], 2) . ' h' : '—'"
                hint="Average sleep over the last 7 days."
                variant="secondary"
            />
            <x-nhealth.stat-card
                label="Weight 7-day change"
                :value="$stats['weight_change_7d_kg'] !== null ? (($stats['weight_change_7d_kg'] > 0 ? '+' : '') . number_format($stats['weight_change_7d_kg'], 2) . ' kg') : '—'"
                hint="Difference between the oldest and latest entry in the last 7 days."
                variant="accent"
            />
            <x-nhealth.stat-card
                label="Latest energy"
                :value="$stats['latest_energy_level'] ?? '—'"
                hint="Most recent energy level from your journal."
            />
            <x-nhealth.stat-card
                label="Latest mood"
                :value="$stats['latest_mood_level'] ?? '—'"
                hint="Most recent mood level from your journal."
            />
        </section>

        <section class="grid gap-6 xl:grid-cols-[1.15fr,0.85fr]">
            <x-nhealth.section eyebrow="Health profile" title="Your baseline">
                <x-slot name="action">
                    <a href="{{ route('nhealth.profile.edit') }}" class="nhealth-accent-link">Edit profile</a>
                </x-slot>

                @if ($healthProfile)
                    <div class="grid gap-4 md:grid-cols-2">
                        <x-nhealth.widget-card label="Date of birth" :value="optional($healthProfile->date_of_birth)->format('d M Y') ?: '—'" />
                        <x-nhealth.widget-card label="Height" :value="$healthProfile->height_cm ? number_format((float) $healthProfile->height_cm, 2) . ' cm' : '—'" />
                        <x-nhealth.widget-card label="Activity level" :value="$healthProfile->activity_level ?: '—'" />
                        <x-nhealth.widget-card label="Unit system" :value="ucfirst($healthProfile->unit_system)" />
                    </div>

                    <x-nhealth.widget-card class="mt-4" label="Health notes" :value="$healthProfile->health_notes ?: 'No health notes saved yet.'" />
                @else
                    <x-nhealth.empty-state
                        title="Profile still empty"
                        message="Add your baseline information to personalize the cockpit and improve the meaning of your stats."
                    />
                @endif
            </x-nhealth.section>

            <x-nhealth.section eyebrow="Active goal" title="Focus target">
                <x-slot name="action">
                    <a href="{{ route('nhealth.goals.index') }}" class="nhealth-ghost-link">Manage goals</a>
                </x-slot>

                @if ($activeGoal)
                    <div class="rounded-2xl border border-white/10 bg-white/5 p-5">
                        <div class="flex items-start justify-between gap-4">
                            <div>
                                <p class="text-xs uppercase tracking-[0.3em] text-slate-400">{{ $activeGoal->goal_type }}</p>
                                <p class="mt-3 text-2xl font-semibold text-white">{{ $activeGoal->title }}</p>
                                <p class="nhealth-copy mt-2">{{ $activeGoal->description ?: 'No description provided.' }}</p>
                            </div>
                            <div class="rounded-full border border-white/10 bg-slate-950/70 px-3 py-1 text-xs uppercase tracking-[0.25em] text-slate-300">
                                {{ $activeGoal->status }}
                            </div>
                        </div>

                        <div class="mt-5 grid gap-4 md:grid-cols-2">
                            <x-nhealth.widget-card variant="secondary" label="Target" :value="$activeGoal->target_value ? number_format((float) $activeGoal->target_value, 2) . ' ' . $activeGoal->unit : '—'" />
                            <x-nhealth.widget-card variant="secondary" label="Target date" :value="optional($activeGoal->target_date)->format('d M Y') ?: '—'" />
                        </div>

                        <div class="mt-4 rounded-2xl border border-ankhor-400/20 bg-slate-950/50 p-4">
                            <div class="flex items-center justify-between gap-3">
                                <p class="text-sm font-medium text-white">Estimated progress</p>
                                @if ($progress['is_estimable'])
                                    <span class="text-sm text-nethra-200">{{ $progress['percentage'] }}%</span>
                                @else
                                    <span class="text-sm text-slate-400">Pending</span>
                                @endif
                            </div>

                            @if ($progress['is_estimable'])
                                <div class="mt-3 h-3 overflow-hidden rounded-full bg-white/10">
                                    <div class="h-full rounded-full bg-gradient-to-r from-nethra-400 to-ankhor-400" style="width: {{ $progress['percentage'] }}%"></div>
                                </div>
                                <div class="mt-3 flex items-center justify-between gap-3 text-sm">
                                    <span class="text-slate-300">{{ $progress['remaining_label'] }}</span>
                                    <span class="text-slate-400">{{ $stats['current_weight_kg'] !== null ? number_format($stats['current_weight_kg'], 2) . ' kg now' : 'No current weight' }}</span>
                                </div>
                            @else
                                <p class="mt-3 text-sm text-slate-400">{{ $progress['message'] }}</p>
                            @endif
                        </div>
                    </div>
                @else
                    <x-nhealth.empty-state
                        title="No active goal"
                        message="Create an active target to unlock progress visibility and give the cockpit a clear focus."
                    >
                        <a href="{{ route('nhealth.goals.create') }}" class="nhealth-accent-link">Create goal</a>
                    </x-nhealth.empty-state>
                @endif
            </x-nhealth.section>
        </section>

        <section class="grid gap-6 xl:grid-cols-[0.9fr,1.1fr]">
            <x-nhealth.section eyebrow="Recent evolution" title="Latest movement">
                <div class="grid gap-4">
                    <x-nhealth.widget-card
                        label="Latest dedicated weight"
                        :value="$latestWeightEntry ? number_format((float) $latestWeightEntry->weight_kg, 2) . ' kg' : '—'"
                        :hint="$latestWeightEntry ? 'Recorded on ' . $latestWeightEntry->recorded_on->format('d M Y') : 'No dedicated weight entry yet.'"
                    />
                    <x-nhealth.widget-card
                        label="Recent change"
                        :value="$stats['recent_weight_change_kg'] !== null ? (($stats['recent_weight_change_kg'] > 0 ? '+' : '') . number_format($stats['recent_weight_change_kg'], 2) . ' kg') : '—'"
                        hint="Difference between your latest and previous usable weight points."
                    />
                </div>

                <div class="nhealth-chart-frame mt-6">
                    <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                        <div>
                            <p class="text-sm font-medium text-white">Weight trend</p>
                            <p class="mt-1 text-sm text-slate-400">Last 30 private weight entries.</p>
                        </div>
                        <a href="{{ route('nhealth.weight.index') }}" class="nhealth-ghost-link">View all</a>
                    </div>

                    @if (count($weightChart['labels']) > 1)
                        <div class="mt-4 h-72 sm:h-80">
                            <canvas
                                data-weight-chart
                                data-labels='@json($weightChart['labels'])'
                                data-values='@json($weightChart['weights'])'
                                class="h-full w-full"
                            ></canvas>
                        </div>
                    @else
                        <div class="mt-4">
                            <x-nhealth.empty-state
                                class="flex h-72 items-center justify-center text-center"
                                message="Add at least two weight entries to unlock the evolution chart."
                            />
                        </div>
                    @endif
                </div>
            </x-nhealth.section>

            <x-nhealth.section eyebrow="Daily rhythm" title="Latest journal and habits">
                @if ($latestCheckIn)
                    <div class="grid gap-4 md:grid-cols-2">
                        <x-nhealth.widget-card label="Date" :value="$latestCheckIn->recorded_on->format('d M Y')" />
                        <x-nhealth.widget-card label="Sleep" :value="$latestCheckIn->sleep_hours ? number_format((float) $latestCheckIn->sleep_hours, 2) . ' h' : '—'" />
                        <x-nhealth.widget-card label="Energy / Mood" :value="($latestCheckIn->energy_level ?? '—') . ' / ' . ($latestCheckIn->mood_level ?? '—')" />
                        <x-nhealth.widget-card label="Stress" :value="$latestCheckIn->stress_level ?? '—'" />
                    </div>

                    <div class="mt-4 grid gap-4 md:grid-cols-2">
                        <x-nhealth.widget-card label="Water intake" :value="$latestCheckIn->water_intake_liters ? number_format((float) $latestCheckIn->water_intake_liters, 1) . ' L' : '—'" />
                        <x-nhealth.widget-card label="Steps" :value="$latestCheckIn->steps ? number_format($latestCheckIn->steps) : '—'" />
                    </div>

                    <x-nhealth.widget-card class="mt-4" label="Notes" :value="$latestCheckIn->notes ?: 'No notes saved for the latest check-in.'" />
                @else
                    <x-nhealth.empty-state
                        title="No journal data yet"
                        message="Start the daily journal to populate this cockpit section with mood, energy and recovery context."
                    >
                        <a href="{{ route('check-ins.index') }}" class="nhealth-accent-link">Start check-in</a>
                    </x-nhealth.empty-state>
                @endif

                <div class="nhealth-chart-frame mt-6">
                    <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                        <div>
                            <p class="text-sm font-medium text-white">Habit summary</p>
                            <p class="mt-1 text-sm text-slate-400">Quick 7-day health behavior snapshot.</p>
                        </div>
                        <span class="rounded-full border border-white/10 bg-white/5 px-3 py-1 text-xs uppercase tracking-[0.25em] text-slate-300">
                            7 days
                        </span>
                    </div>

                    <div class="mt-4 grid gap-4 sm:grid-cols-2">
                        @foreach ($habitSummary as $item)
                            <x-nhealth.widget-card :label="$item['label']" :value="$item['value']" :hint="$item['hint']" />
                        @endforeach
                    </div>
                </div>
            </x-nhealth.section>
        </section>

        <x-nhealth.section eyebrow="Shortcuts" title="Quick access" description="Jump directly into the core NHealth workflows.">
            <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-4">
                <x-nhealth.shortcut-card
                    href="{{ route('nhealth.profile.edit') }}"
                    eyebrow="Profile"
                    title="Health profile"
                    description="Maintain baseline health information."
                />
                <x-nhealth.shortcut-card
                    href="{{ route('nhealth.goals.index') }}"
                    eyebrow="Goals"
                    title="Transformation goals"
                    description="Create, review and adjust active targets."
                />
                <x-nhealth.shortcut-card
                    href="{{ route('nhealth.weight.index') }}"
                    eyebrow="Weight"
                    title="Weight history"
                    description="Track dedicated weigh-ins over time."
                />
                <x-nhealth.shortcut-card
                    href="{{ route('check-ins.index') }}"
                    eyebrow="Journal"
                    title="Daily check-ins"
                    description="Capture sleep, mood, stress and notes."
                />
            </div>
        </x-nhealth.section>
    </div>
</x-app-layout>
