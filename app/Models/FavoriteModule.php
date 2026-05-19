<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    'user_id',
    'module_key',
    'module_name',
    'is_active',
    'last_toggled_at',
])]
class FavoriteModule extends Model
{
    use HasFactory;

    public const NHEALTH_KEY = 'nhealth';

    public const NHEALTH_NAME = 'Nethra Health';

    /**
     * Get the user that owns the module preference.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Cast module preference attributes to the correct scalar types.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'last_toggled_at' => 'datetime',
        ];
    }
}
