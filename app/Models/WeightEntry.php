<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    'user_id',
    'recorded_on',
    'weight_kg',
    'source',
    'notes',
    'metadata',
])]
class WeightEntry extends Model
{
    /**
     * Get the user that owns the weight entry.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Cast weight tracking values to the proper types.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'recorded_on' => 'date',
            'weight_kg' => 'decimal:2',
            'metadata' => 'array',
        ];
    }
}
