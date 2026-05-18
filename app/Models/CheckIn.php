<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    'user_id',
    'recorded_on',
    'weight_kg',
    'sleep_hours',
    'steps',
    'energy_level',
    'mood_level',
    'notes',
])]
class CheckIn extends Model
{
    /**
     * Get the authenticated owner of the check-in.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Cast health metrics to the right scalar types.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'recorded_on' => 'date',
            'weight_kg' => 'decimal:1',
            'sleep_hours' => 'decimal:1',
            'steps' => 'integer',
            'energy_level' => 'integer',
            'mood_level' => 'integer',
        ];
    }
}
