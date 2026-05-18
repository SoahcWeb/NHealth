<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    'user_id',
    'title',
    'description',
    'goal_type',
    'status',
    'direction',
    'unit',
    'start_value',
    'target_value',
    'starts_on',
    'target_date',
    'completed_at',
    'metadata',
])]
class Goal extends Model
{
    /**
     * Get the user that owns the goal.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Cast dates, numbers and metadata for goal tracking.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'start_value' => 'decimal:2',
            'target_value' => 'decimal:2',
            'starts_on' => 'date',
            'target_date' => 'date',
            'completed_at' => 'datetime',
            'metadata' => 'array',
        ];
    }
}
