<section class="grid gap-4 md:grid-cols-2 xl:grid-cols-4">
    <x-nhealth.stat-card
        label="Poids actuel"
        :value="$stats['current_weight_kg'] !== null ? number_format($stats['current_weight_kg'], 2) . ' kg' : '—'"
        hint="Dernière entrée dédiée ou dernier instantané de check-in."
        variant="accent"
    />
    <x-nhealth.stat-card
        label="Entrées de poids"
        :value="$stats['total_weight_entries']"
        hint="Total des pesées dédiées enregistrées."
    />
    <x-nhealth.stat-card
        label="Check-ins ce mois-ci"
        :value="$stats['check_ins_this_month']"
        :hint="$stats['total_check_ins'] . ' entrées du journal au total.'"
    />
    <x-nhealth.stat-card
        label="Série actuelle"
        :value="$stats['current_streak'] . ' jours'"
        hint="Jours consécutifs de check-in à partir de votre dernière entrée."
        variant="secondary"
    />
</section>

<section class="grid gap-4 md:grid-cols-2 xl:grid-cols-4">
    <x-nhealth.stat-card
        label="Sommeil moyen"
        :value="$stats['average_sleep_hours_7d'] !== null ? number_format($stats['average_sleep_hours_7d'], 2) . ' h' : '—'"
        hint="Sommeil moyen sur les 7 derniers jours."
        variant="secondary"
    />
    <x-nhealth.stat-card
        label="Variation du poids sur 7 jours"
        :value="$stats['weight_change_7d_kg'] !== null ? (($stats['weight_change_7d_kg'] > 0 ? '+' : '') . number_format($stats['weight_change_7d_kg'], 2) . ' kg') : '—'"
        hint="Différence entre l’entrée la plus ancienne et la plus récente des 7 derniers jours."
        variant="accent"
    />
    <x-nhealth.stat-card
        label="Dernière énergie"
        :value="$stats['latest_energy_level'] ?? '—'"
        hint="Niveau d’énergie le plus récent de votre journal."
    />
    <x-nhealth.stat-card
        label="Dernière humeur"
        :value="$stats['latest_mood_level'] ?? '—'"
        hint="Niveau d’humeur le plus récent de votre journal."
    />
</section>
