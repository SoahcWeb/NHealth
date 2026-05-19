<x-app-layout>
    <x-slot name="header">
        <x-nhealth.page-header
            eyebrow="NHealth statistics"
            title="Monthly statistics"
            :description="'Current month overview for ' . $monthLabel . ' · ' . $monthRangeLabel"
        >
            <x-slot name="action">
                <a href="{{ route('nhealth.dashboard') }}" class="nhealth-ghost-link">Back to dashboard</a>
            </x-slot>
        </x-nhealth.page-header>
    </x-slot>

    <div class="nhealth-shell">
        <section class="grid gap-4 md:grid-cols-2 xl:grid-cols-4">
            <x-nhealth.stat-card
                label="Check-ins this month"
                :value="$stats['check_ins_count']"
                hint="Private journal entries recorded in the current month."
                variant="accent"
            />
            <x-nhealth.stat-card
                label="Sleep average"
                :value="$stats['average_sleep_hours'] !== null ? number_format($stats['average_sleep_hours'], 2) . ' h' : '—'"
                hint="Average sleep across this month's check-ins."
            />
            <x-nhealth.stat-card
                label="Energy average"
                :value="$stats['average_energy_level'] !== null ? number_format($stats['average_energy_level'], 2) : '—'"
                hint="Average energy level from current month journal entries."
                variant="secondary"
            />
            <x-nhealth.stat-card
                label="Mood average"
                :value="$stats['average_mood_level'] !== null ? number_format($stats['average_mood_level'], 2) : '—'"
                hint="Average mood level from current month journal entries."
            />
        </section>

        <section class="grid gap-4 md:grid-cols-2 xl:grid-cols-4">
            <x-nhealth.stat-card
                label="Start of month weight"
                :value="$stats['month_start_weight_kg'] !== null ? number_format($stats['month_start_weight_kg'], 2) . ' kg' : '—'"
                hint="First dedicated weight entry recorded this month."
                variant="secondary"
            />
            <x-nhealth.stat-card
                label="Current weight"
                :value="$stats['current_weight_kg'] !== null ? number_format($stats['current_weight_kg'], 2) . ' kg' : '—'"
                hint="Latest dedicated weight entry recorded this month."
                variant="accent"
            />
            <x-nhealth.stat-card
                label="Weight change"
                :value="$stats['weight_change_kg'] !== null ? (($stats['weight_change_kg'] > 0 ? '+' : '') . number_format($stats['weight_change_kg'], 2) . ' kg') : '—'"
                hint="Difference between the first and latest weight entries this month."
            />
            <x-nhealth.stat-card
                label="Weight entries"
                :value="$stats['weight_entries_count']"
                :hint="$stats['unlocked_badges_count'] . ' unlocked badges overall.'"
            />
        </section>

        <section class="grid gap-6 xl:grid-cols-[1.15fr,0.85fr]">
            <x-nhealth.section eyebrow="Monthly evolution" title="Weight movement" description="Dedicated weight entries recorded during the current month.">
                <div class="nhealth-chart-frame">
                    @if (count($weightChart['labels']) > 1)
                        <div class="h-80">
                            <canvas
                                data-weight-chart
                                data-labels='@json($weightChart['labels'])'
                                data-values='@json($weightChart['weights'])'
                                class="h-full w-full"
                            ></canvas>
                        </div>
                    @else
                        <x-nhealth.empty-state
                            class="flex h-80 items-center justify-center text-center"
                            message="Add at least two weight entries this month to visualize your monthly evolution."
                        />
                    @endif
                </div>
            </x-nhealth.section>

            <x-nhealth.section eyebrow="Milestones" title="Unlocked badges" description="Current MVP progression badges already unlocked in your private NHealth space.">
                @if (count($unlockedBadges) > 0)
                    <div class="grid gap-4">
                        @foreach ($unlockedBadges as $badge)
                            <x-nhealth.widget-card
                                :label="$badge['title']"
                                value="Unlocked"
                                :hint="$badge['achieved_label']"
                                variant="accent"
                            />
                        @endforeach
                    </div>
                @else
                    <x-nhealth.empty-state
                        title="No badges unlocked yet"
                        message="Keep logging check-ins, weight entries and active goals to unlock your first milestones."
                    />
                @endif
            </x-nhealth.section>
        </section>
    </div>
</x-app-layout>
