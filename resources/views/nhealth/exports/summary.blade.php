<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <title>Bilan NHealth</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            color: #111827;
            font-size: 12px;
            line-height: 1.5;
            margin: 24px;
        }

        h1, h2, h3, p {
            margin: 0;
        }

        .header {
            border-bottom: 2px solid #eab308;
            padding-bottom: 16px;
            margin-bottom: 24px;
        }

        .eyebrow {
            color: #7c3aed;
            font-size: 10px;
            font-weight: 700;
            letter-spacing: 0.2em;
            text-transform: uppercase;
            margin-bottom: 8px;
        }

        .title {
            font-size: 24px;
            font-weight: 700;
            margin-bottom: 6px;
        }

        .subtitle {
            color: #4b5563;
            font-size: 11px;
        }

        .section {
            margin-top: 22px;
        }

        .section-title {
            font-size: 14px;
            font-weight: 700;
            color: #111827;
            margin-bottom: 10px;
            border-left: 4px solid #a855f7;
            padding-left: 10px;
        }

        .grid {
            width: 100%;
        }

        .grid:after {
            content: "";
            display: block;
            clear: both;
        }

        .card {
            border: 1px solid #e5e7eb;
            border-radius: 10px;
            padding: 12px;
            margin-bottom: 12px;
            background: #fcfcfd;
        }

        .card-half {
            width: 48%;
            float: left;
        }

        .card-half.right {
            float: right;
        }

        .label {
            color: #6b7280;
            font-size: 10px;
            text-transform: uppercase;
            letter-spacing: 0.12em;
            margin-bottom: 4px;
        }

        .value {
            font-size: 16px;
            font-weight: 700;
            color: #111827;
        }

        .muted {
            color: #6b7280;
        }

        .body-copy {
            color: #374151;
            margin-top: 6px;
        }

        .list {
            margin: 8px 0 0;
            padding-left: 18px;
        }

        .list li {
            margin-bottom: 6px;
        }

        .footer {
            margin-top: 28px;
            border-top: 1px solid #e5e7eb;
            padding-top: 14px;
            color: #4b5563;
            font-size: 11px;
        }
    </style>
</head>
<body>
    <div class="header">
        <p class="eyebrow">Nethra Health</p>
        <h1 class="title">Bilan personnel de progression</h1>
        <p class="subtitle">{{ $user->name }} · Exporté le {{ $exportedAt->translatedFormat('d M Y H:i') }}</p>
    </div>

    <div class="section">
        <h2 class="section-title">Vue rapide</h2>
        <div class="grid">
            <div class="card card-half">
                <p class="label">Dernier poids</p>
                <p class="value">
                    {{ $latestWeightEntry ? number_format((float) $latestWeightEntry->weight_kg, 2) . ' kg' : '—' }}
                </p>
                <p class="body-copy">
                    {{ $latestWeightEntry ? 'Enregistré le ' . $latestWeightEntry->recorded_on->translatedFormat('d M Y') : 'Aucune entrée de poids dédiée pour le moment.' }}
                </p>
            </div>

            <div class="card card-half right">
                <p class="label">Évolution récente</p>
                <p class="value">
                    {{ $stats['weight_change_7d_kg'] !== null ? (($stats['weight_change_7d_kg'] > 0 ? '+' : '') . number_format($stats['weight_change_7d_kg'], 2) . ' kg') : '—' }}
                </p>
                <p class="body-copy">Variation sur 7 jours basée sur les dernières entrées de poids dédiées.</p>
            </div>
        </div>

        <div class="grid">
            <div class="card card-half">
                <p class="label">Check-ins</p>
                <p class="value">{{ $stats['total_check_ins'] }}</p>
                <p class="body-copy">Entrées privées du journal quotidien enregistrées jusqu’à présent.</p>
            </div>

            <div class="card card-half right">
                <p class="label">Sommeil moyen sur 7 jours</p>
                <p class="value">{{ $stats['average_sleep_hours_7d'] !== null ? number_format($stats['average_sleep_hours_7d'], 2) . ' h' : '—' }}</p>
                <p class="body-copy">Sommeil moyen calculé sur la fenêtre récente de 7 jours.</p>
            </div>
        </div>
    </div>

    <div class="section">
        <h2 class="section-title">Profil santé</h2>
        <div class="card">
            @if ($healthProfile)
                <p><strong>Date de naissance :</strong> {{ optional($healthProfile->date_of_birth)?->translatedFormat('d M Y') ?: '—' }}</p>
                <p><strong>Taille :</strong> {{ $healthProfile->height_cm ? number_format((float) $healthProfile->height_cm, 2) . ' cm' : '—' }}</p>
                <p><strong>Niveau d’activité :</strong> {{ $healthProfile->activity_level ?: '—' }}</p>
                <p><strong>Système d’unités :</strong> {{ ucfirst($healthProfile->unit_system) }}</p>
                <p><strong>Notes de santé :</strong> {{ $healthProfile->health_notes ?: 'Aucune note de santé enregistrée pour le moment.' }}</p>
            @else
                <p class="muted">Aucun profil santé n’a encore été rempli.</p>
            @endif
        </div>
    </div>

    <div class="section">
        <h2 class="section-title">Objectif actif</h2>
        <div class="card">
            @if ($activeGoal)
                <p><strong>Titre :</strong> {{ $activeGoal->title }}</p>
                <p><strong>Type :</strong> {{ $activeGoal->goal_type }}</p>
                <p><strong>Statut :</strong> {{ $activeGoal->status }}</p>
                <p><strong>Cible :</strong> {{ $activeGoal->target_value ? number_format((float) $activeGoal->target_value, 2) . ' ' . $activeGoal->unit : '—' }}</p>
                <p><strong>Date cible :</strong> {{ optional($activeGoal->target_date)?->translatedFormat('d M Y') ?: '—' }}</p>
                <p><strong>Progression estimée :</strong> {{ $progress['is_estimable'] ? $progress['percentage'] . '%' : ($progress['message'] ?? 'En attente') }}</p>
                <p><strong>Description :</strong> {{ $activeGoal->description ?: 'Aucune description fournie.' }}</p>
            @else
                <p class="muted">Aucun objectif actif pour le moment.</p>
            @endif
        </div>
    </div>

    <div class="section">
        <h2 class="section-title">Dernier contexte du journal</h2>
        <div class="card">
            @if ($latestCheckIn)
                <p><strong>Date du dernier check-in :</strong> {{ $latestCheckIn->recorded_on->translatedFormat('d M Y') }}</p>
                <p><strong>Énergie / Humeur :</strong> {{ ($latestCheckIn->energy_level ?? '—') . ' / ' . ($latestCheckIn->mood_level ?? '—') }}</p>
                <p><strong>Stress :</strong> {{ $latestCheckIn->stress_level ?? '—' }}</p>
                <p><strong>Notes :</strong> {{ $latestCheckIn->notes ?: 'Aucune note enregistrée pour le dernier check-in.' }}</p>
            @else
                <p class="muted">Aucune entrée du journal quotidien pour le moment.</p>
            @endif
        </div>
    </div>

    <div class="section">
        <h2 class="section-title">Badges débloqués</h2>
        <div class="card">
            @if ($unlockedBadges->isNotEmpty())
                <ul class="list">
                    @foreach ($unlockedBadges as $badge)
                        <li>
                            <strong>{{ $badge['title'] }}</strong> — {{ $badge['achieved_label'] }}
                        </li>
                    @endforeach
                </ul>
            @else
                <p class="muted">Aucun badge débloqué pour le moment.</p>
            @endif
        </div>
    </div>

    <div class="footer">
        Document généré par Nethra Health — Ankhor Protocol
    </div>
</body>
</html>
