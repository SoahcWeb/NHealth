<?php

namespace Database\Factories;

use App\Models\CheckIn;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<CheckIn>
 */
class CheckInFactory extends Factory
{
    protected $model = CheckIn::class;

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
            'sleep_hours' => fake()->randomFloat(2, 5, 9),
            'steps' => fake()->numberBetween(2500, 16000),
            'water_intake_liters' => fake()->randomFloat(2, 0.8, 3.5),
            'energy_level' => fake()->numberBetween(1, 10),
            'mood_level' => fake()->numberBetween(1, 10),
            'stress_level' => fake()->numberBetween(1, 10),
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
