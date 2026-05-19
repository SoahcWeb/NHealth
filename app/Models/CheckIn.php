<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    'user_id',
    'recorded_on',
    'weight_kg',
    'sleep_hours',
    'steps',
    'water_intake_liters',
    'energy_level',
    'mood_level',
    'stress_level',
    'notes',
    'metadata',
])]
class CheckIn extends Model
{
    use HasFactory;

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
            'weight_kg' => 'decimal:2',
            'sleep_hours' => 'decimal:2',
            'steps' => 'integer',
            'water_intake_liters' => 'decimal:2',
            'energy_level' => 'integer',
            'mood_level' => 'integer',
            'stress_level' => 'integer',
            'metadata' => 'array',
        ];
    }
}
