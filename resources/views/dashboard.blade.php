<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-2 md:flex-row md:items-end md:justify-between">
            <div>
                <p class="text-xs font-semibold uppercase tracking-[0.35em] text-nethra-300">Nethra cockpit</p>
                <h2 class="text-3xl font-semibold text-white leading-tight">
                    Personal health dashboard
                </h2>
            </div>
            <p class="max-w-2xl text-sm text-slate-400">
                A private command center for your transformation, habits and recovery.
            </p>
        </div>
    </x-slot>

    <div class="mx-auto flex max-w-7xl flex-col gap-6 px-4 py-8 sm:px-6 lg:px-8">
        <section class="grid gap-4 md:grid-cols-2 xl:grid-cols-4">
            <article class="stat-card">
                <p class="stat-label">Total check-ins</p>
                <p class="stat-value">{{ $stats['total_check_ins'] }}</p>
                <p class="stat-hint">Private entries recorded over time.</p>
            </article>

            <article class="stat-card">
                <p class="stat-label">Current streak</p>
                <p class="stat-value">{{ $stats['current_streak'] }} days</p>
                <p class="stat-hint">Consecutive days ending at your latest entry.</p>
            </article>

            <article class="stat-card">
                <p class="stat-label">Latest weight</p>
                <p class="stat-value">
                    {{ $stats['latest_weight_kg'] !== null ? number_format((float) $stats['latest_weight_kg'], 1) . ' kg' : '—' }}
                </p>
                <p class="stat-hint">Most recent registered weight.</p>
            </article>

            <article class="stat-card">
                <p class="stat-label">7-day sleep avg</p>
                <p class="stat-value">
                    {{ $stats['average_sleep_hours'] !== null ? number_format($stats['average_sleep_hours'], 1) . ' h' : '—' }}
                </p>
                <p class="stat-hint">Average sleep over the last 7 days.</p>
            </article>
        </section>

        <section class="grid gap-6 xl:grid-cols-[1.3fr,0.7fr]">
            <article class="panel">
                <div class="flex items-center justify-between gap-3">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-[0.35em] text-ankhor-300">Weight trend</p>
                        <h3 class="mt-2 text-xl font-semibold text-white">Last recorded evolution</h3>
                    </div>
                    <a href="{{ route('check-ins.index') }}" class="inline-flex items-center rounded-full border border-ankhor-400/40 bg-ankhor-500/10 px-4 py-2 text-sm font-medium text-ankhor-100 transition hover:border-ankhor-300 hover:bg-ankhor-500/20">
                        Open journal
                    </a>
                </div>

                <div class="mt-6 rounded-3xl border border-white/10 bg-slate-950/60 p-4">
                    @if (count($chart['labels']) > 1)
                        <canvas
                            data-weight-chart
                            data-labels='@json($chart['labels'])'
                            data-values='@json($chart['weights'])'
                            class="h-72 w-full"
                        ></canvas>
                    @else
                        <div class="flex h-72 items-center justify-center rounded-2xl border border-dashed border-white/10 bg-white/5 text-center text-sm text-slate-400">
                            Add at least two weight entries to unlock the progress chart.
                        </div>
                    @endif
                </div>
            </article>

            <article class="panel">
                <p class="text-xs font-semibold uppercase tracking-[0.35em] text-nethra-300">Latest pulse</p>
                <h3 class="mt-2 text-xl font-semibold text-white">Today at a glance</h3>

                @if ($latestCheckIn)
                    <div class="mt-6 grid gap-4">
                        <div class="rounded-2xl border border-white/10 bg-white/5 p-4">
                            <p class="text-sm text-slate-400">Recorded on</p>
                            <p class="mt-2 text-lg font-semibold text-white">{{ $latestCheckIn->recorded_on->format('d M Y') }}</p>
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div class="rounded-2xl border border-white/10 bg-white/5 p-4">
                                <p class="text-sm text-slate-400">Energy</p>
                                <p class="mt-2 text-2xl font-semibold text-white">{{ $latestCheckIn->energy_level ?? '—' }}</p>
                            </div>
                            <div class="rounded-2xl border border-white/10 bg-white/5 p-4">
                                <p class="text-sm text-slate-400">Mood</p>
                                <p class="mt-2 text-2xl font-semibold text-white">{{ $latestCheckIn->mood_level ?? '—' }}</p>
                            </div>
                        </div>
                        <div class="rounded-2xl border border-white/10 bg-white/5 p-4">
                            <p class="text-sm text-slate-400">Notes</p>
                            <p class="mt-2 text-sm leading-6 text-slate-200">{{ $latestCheckIn->notes ?: 'No note saved for the latest entry.' }}</p>
                        </div>
                    </div>
                @else
                    <div class="mt-6 rounded-3xl border border-dashed border-white/10 bg-white/5 p-6 text-sm text-slate-400">
                        No health data yet. Start with your first daily check-in to activate the cockpit.
                    </div>
                @endif
            </article>
        </section>

        <section class="panel">
            <div class="flex flex-col gap-3 md:flex-row md:items-end md:justify-between">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-[0.35em] text-ankhor-300">Recent activity</p>
                    <h3 class="mt-2 text-xl font-semibold text-white">Last private entries</h3>
                </div>
                <a href="{{ route('check-ins.index') }}" class="text-sm font-medium text-nethra-200 transition hover:text-nethra-100">
                    Manage all check-ins
                </a>
            </div>

            <div class="mt-6 grid gap-4 md:grid-cols-2 xl:grid-cols-5">
                @forelse ($recentCheckIns as $checkIn)
                    <article class="rounded-3xl border border-white/10 bg-white/5 p-5">
                        <p class="text-xs uppercase tracking-[0.3em] text-slate-400">{{ $checkIn->recorded_on->format('d M') }}</p>
                        <p class="mt-3 text-lg font-semibold text-white">
                            {{ $checkIn->weight_kg ? number_format((float) $checkIn->weight_kg, 1) . ' kg' : 'No weight' }}
                        </p>
                        <div class="mt-4 space-y-2 text-sm text-slate-300">
                            <p>Sleep: {{ $checkIn->sleep_hours ? number_format((float) $checkIn->sleep_hours, 1) . ' h' : '—' }}</p>
                            <p>Steps: {{ $checkIn->steps ? number_format($checkIn->steps) : '—' }}</p>
                            <p>Energy: {{ $checkIn->energy_level ?? '—' }}</p>
                        </div>
                    </article>
                @empty
                    <article class="rounded-3xl border border-dashed border-white/10 bg-white/5 p-6 text-sm text-slate-400 md:col-span-2 xl:col-span-5">
                        Your recent entries will appear here once you begin tracking.
                    </article>
                @endforelse
            </div>
        </section>
    </div>
</x-app-layout>
