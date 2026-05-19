<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\WeightEntry;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<WeightEntry>
 */
class WeightEntryFactory extends Factory
{
    protected $model = WeightEntry::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'recorded_on' => fake()->dateTimeBetween('-30 days', 'today'),
            'weight_kg' => fake()->randomFloat(2, 55, 110),
            'source' => fake()->randomElement(['manual', 'scale', 'import']),
            'notes' => fake()->sentence(),
            'metadata' => null,
        ];
    }

    public function today(): static
    {
        return $this->state(fn (array $attributes) => [
            'recorded_on' => now()->toDateString(),
        ]);
    }
}
