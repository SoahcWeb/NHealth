<x-app-layout>
    <x-slot name="header">
        <x-nhealth.page-header
            eyebrow="Statistiques NHealth"
            title="Statistiques mensuelles"
            :description="'Vue d’ensemble du mois en cours pour ' . $monthLabel . ' · ' . $monthRangeLabel"
        >
            <x-slot name="action">
                <a href="{{ route('nhealth.dashboard') }}" class="nhealth-ghost-link">Retour au tableau de bord</a>
            </x-slot>
        </x-nhealth.page-header>
    </x-slot>

    <div class="nhealth-shell">
        <section class="grid gap-4 md:grid-cols-2 xl:grid-cols-4">
            <x-nhealth.stat-card
                label="Check-ins ce mois-ci"
                :value="$stats['check_ins_count']"
                hint="Entrées de journal privé enregistrées pendant le mois en cours."
                variant="accent"
            />
            <x-nhealth.stat-card
                label="Sommeil moyen"
                :value="$stats['average_sleep_hours'] !== null ? number_format($stats['average_sleep_hours'], 2) . ' h' : '—'"
                hint="Sommeil moyen sur les check-ins du mois."
            />
            <x-nhealth.stat-card
                label="Énergie moyenne"
                :value="$stats['average_energy_level'] !== null ? number_format($stats['average_energy_level'], 2) : '—'"
                hint="Niveau d’énergie moyen sur les entrées du mois."
                variant="secondary"
            />
            <x-nhealth.stat-card
                label="Humeur moyenne"
                :value="$stats['average_mood_level'] !== null ? number_format($stats['average_mood_level'], 2) : '—'"
                hint="Niveau d’humeur moyen sur les entrées du mois."
            />
        </section>

        <section class="grid gap-4 md:grid-cols-2 xl:grid-cols-4">
            <x-nhealth.stat-card
                label="Poids au début du mois"
                :value="$stats['month_start_weight_kg'] !== null ? number_format($stats['month_start_weight_kg'], 2) . ' kg' : '—'"
                hint="Première entrée de poids dédiée enregistrée ce mois-ci."
                variant="secondary"
            />
            <x-nhealth.stat-card
                label="Poids actuel"
                :value="$stats['current_weight_kg'] !== null ? number_format($stats['current_weight_kg'], 2) . ' kg' : '—'"
                hint="Dernière entrée de poids dédiée enregistrée ce mois-ci."
                variant="accent"
            />
            <x-nhealth.stat-card
                label="Variation du poids"
                :value="$stats['weight_change_kg'] !== null ? (($stats['weight_change_kg'] > 0 ? '+' : '') . number_format($stats['weight_change_kg'], 2) . ' kg') : '—'"
                hint="Différence entre la première et la dernière entrée de poids du mois."
            />
            <x-nhealth.stat-card
                label="Entrées de poids"
                :value="$stats['weight_entries_count']"
                :hint="$stats['unlocked_badges_count'] . ' badges débloqués au total.'"
            />
        </section>

        <section class="grid gap-6 xl:grid-cols-[1.15fr,0.85fr]">
            <x-nhealth.section eyebrow="Évolution mensuelle" title="Mouvement du poids" description="Entrées de poids dédiées enregistrées pendant le mois en cours.">
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
                            message="Ajoutez au moins deux entrées de poids ce mois-ci pour visualiser votre évolution mensuelle."
                        />
                    @endif
                </div>
            </x-nhealth.section>

            <x-nhealth.section eyebrow="Paliers" title="Badges débloqués" description="Badges de progression du MVP déjà débloqués dans votre espace NHealth privé.">
                @if (count($unlockedBadges) > 0)
                    <div class="grid gap-4">
                        @foreach ($unlockedBadges as $badge)
                            <x-nhealth.widget-card
                                :label="$badge['title']"
                                value="Débloqué"
                                :hint="$badge['achieved_label']"
                                variant="accent"
                            />
                        @endforeach
                    </div>
                @else
                    <x-nhealth.empty-state
                        title="Aucun badge débloqué pour le moment"
                        message="Continuez à enregistrer vos check-ins, vos poids et vos objectifs actifs pour débloquer vos premiers paliers."
                    />
                @endif
            </x-nhealth.section>
        </section>
    </div>
</x-app-layout>
