<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    'user_id',
    'date_of_birth',
    'biological_sex',
    'height_cm',
    'activity_level',
    'unit_system',
    'health_notes',
    'metadata',
])]
class HealthProfile extends Model
{
    use HasFactory;

    /**
     * Get the user that owns the health profile.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Cast structured profile data to useful PHP types.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'date_of_birth' => 'date',
            'height_cm' => 'decimal:2',
            'metadata' => 'array',
        ];
    }
}
