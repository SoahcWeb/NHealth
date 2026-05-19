<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    'user_id',
    'reminder_key',
    'reminder_name',
    'is_enabled',
])]
class HealthReminder extends Model
{
    public const DAILY_CHECK_IN = 'daily_check_in';

    public const WEIGH_IN = 'weigh_in';

    public const ACTIVE_GOAL = 'active_goal';

    /**
     * Get the user that owns the reminder preference.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Cast simple reminder settings to their proper scalar types.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'is_enabled' => 'boolean',
        ];
    }

    /**
     * Return the fixed MVP reminder definitions used across the UI.
     *
     * @return array<int, array<string, string>>
     */
    public static function definitions(): array
    {
        return [
            [
                'key' => self::DAILY_CHECK_IN,
                'name' => 'Rappel check-in quotidien',
                'title' => 'Check-in quotidien',
                'description' => 'Aide-memoire interne pour ne pas oublier votre journal prive chaque jour.',
            ],
            [
                'key' => self::WEIGH_IN,
                'name' => 'Rappel de pesee',
                'title' => 'Pesee',
                'description' => 'Repere visuel pour garder une mesure de poids reguliere dans votre historique dedie.',
            ],
            [
                'key' => self::ACTIVE_GOAL,
                'name' => 'Rappel objectif actif',
                'title' => 'Objectif actif',
                'description' => 'Invite a revisiter votre objectif actif pour garder une direction claire.',
            ],
        ];
    }

    /**
     * Resolve one reminder definition from its fixed key.
     *
     * @return array<string, string>|null
     */
    public static function definitionFor(string $reminderKey): ?array
    {
        foreach (self::definitions() as $definition) {
            if ($definition['key'] === $reminderKey) {
                return $definition;
            }
        }

        return null;
    }
}
