<?php

namespace Database\Factories;

use App\Models\FavoriteModule;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<FavoriteModule>
 */
class FavoriteModuleFactory extends Factory
{
    protected $model = FavoriteModule::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'module_key' => FavoriteModule::NHEALTH_KEY,
            'module_name' => FavoriteModule::NHEALTH_NAME,
            'is_active' => true,
            'last_toggled_at' => now(),
        ];
    }

    public function enabled(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_active' => true,
            'last_toggled_at' => now(),
        ]);
    }

    public function disabled(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_active' => false,
            'last_toggled_at' => now(),
        ]);
    }
}
