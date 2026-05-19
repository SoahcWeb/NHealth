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
