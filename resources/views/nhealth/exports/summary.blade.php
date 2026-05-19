<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>NHealth Summary</title>
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
        <h1 class="title">Personal progress summary</h1>
        <p class="subtitle">{{ $user->name }} · Exported on {{ $exportedAt->format('d M Y H:i') }}</p>
    </div>

    <div class="section">
        <h2 class="section-title">Quick overview</h2>
        <div class="grid">
            <div class="card card-half">
                <p class="label">Latest weight</p>
                <p class="value">
                    {{ $latestWeightEntry ? number_format((float) $latestWeightEntry->weight_kg, 2) . ' kg' : '—' }}
                </p>
                <p class="body-copy">
                    {{ $latestWeightEntry ? 'Recorded on ' . $latestWeightEntry->recorded_on->format('d M Y') : 'No dedicated weight entry yet.' }}
                </p>
            </div>

            <div class="card card-half right">
                <p class="label">Recent evolution</p>
                <p class="value">
                    {{ $stats['weight_change_7d_kg'] !== null ? (($stats['weight_change_7d_kg'] > 0 ? '+' : '') . number_format($stats['weight_change_7d_kg'], 2) . ' kg') : '—' }}
                </p>
                <p class="body-copy">7-day movement based on recent dedicated weight entries.</p>
            </div>
        </div>

        <div class="grid">
            <div class="card card-half">
                <p class="label">Check-ins</p>
                <p class="value">{{ $stats['total_check_ins'] }}</p>
                <p class="body-copy">Private daily journal entries recorded so far.</p>
            </div>

            <div class="card card-half right">
                <p class="label">Sleep average 7 days</p>
                <p class="value">{{ $stats['average_sleep_hours_7d'] !== null ? number_format($stats['average_sleep_hours_7d'], 2) . ' h' : '—' }}</p>
                <p class="body-copy">Average sleep from the recent 7-day journal window.</p>
            </div>
        </div>
    </div>

    <div class="section">
        <h2 class="section-title">Health profile</h2>
        <div class="card">
            @if ($healthProfile)
                <p><strong>Date of birth:</strong> {{ optional($healthProfile->date_of_birth)->format('d M Y') ?: '—' }}</p>
                <p><strong>Height:</strong> {{ $healthProfile->height_cm ? number_format((float) $healthProfile->height_cm, 2) . ' cm' : '—' }}</p>
                <p><strong>Activity level:</strong> {{ $healthProfile->activity_level ?: '—' }}</p>
                <p><strong>Unit system:</strong> {{ ucfirst($healthProfile->unit_system) }}</p>
                <p><strong>Health notes:</strong> {{ $healthProfile->health_notes ?: 'No health notes saved yet.' }}</p>
            @else
                <p class="muted">No health profile has been filled yet.</p>
            @endif
        </div>
    </div>

    <div class="section">
        <h2 class="section-title">Active goal</h2>
        <div class="card">
            @if ($activeGoal)
                <p><strong>Title:</strong> {{ $activeGoal->title }}</p>
                <p><strong>Type:</strong> {{ $activeGoal->goal_type }}</p>
                <p><strong>Status:</strong> {{ $activeGoal->status }}</p>
                <p><strong>Target:</strong> {{ $activeGoal->target_value ? number_format((float) $activeGoal->target_value, 2) . ' ' . $activeGoal->unit : '—' }}</p>
                <p><strong>Target date:</strong> {{ optional($activeGoal->target_date)->format('d M Y') ?: '—' }}</p>
                <p><strong>Estimated progress:</strong> {{ $progress['is_estimable'] ? $progress['percentage'] . '%' : ($progress['message'] ?? 'Pending') }}</p>
                <p><strong>Description:</strong> {{ $activeGoal->description ?: 'No description provided.' }}</p>
            @else
                <p class="muted">No active goal exists yet.</p>
            @endif
        </div>
    </div>

    <div class="section">
        <h2 class="section-title">Latest journal context</h2>
        <div class="card">
            @if ($latestCheckIn)
                <p><strong>Latest check-in date:</strong> {{ $latestCheckIn->recorded_on->format('d M Y') }}</p>
                <p><strong>Energy / Mood:</strong> {{ ($latestCheckIn->energy_level ?? '—') . ' / ' . ($latestCheckIn->mood_level ?? '—') }}</p>
                <p><strong>Stress:</strong> {{ $latestCheckIn->stress_level ?? '—' }}</p>
                <p><strong>Notes:</strong> {{ $latestCheckIn->notes ?: 'No notes saved for the latest check-in.' }}</p>
            @else
                <p class="muted">No daily journal entry exists yet.</p>
            @endif
        </div>
    </div>

    <div class="section">
        <h2 class="section-title">Unlocked badges</h2>
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
                <p class="muted">No badges unlocked yet.</p>
            @endif
        </div>
    </div>

    <div class="footer">
        Document généré par Nethra Health — Ankhor Protocol
    </div>
</body>
</html>
