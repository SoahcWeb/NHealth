<?php

namespace Database\Factories;

use App\Models\HealthProfile;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<HealthProfile>
 */
class HealthProfileFactory extends Factory
{
    protected $model = HealthProfile::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'date_of_birth' => fake()->dateTimeBetween('-60 years', '-18 years'),
            'biological_sex' => fake()->randomElement(['female', 'male', 'intersex', 'other', 'prefer_not_to_say']),
            'height_cm' => fake()->randomFloat(2, 145, 205),
            'activity_level' => fake()->randomElement(['sedentary', 'light', 'moderate', 'active', 'athlete']),
            'unit_system' => fake()->randomElement(['metric', 'imperial']),
            'health_notes' => fake()->sentence(),
            'metadata' => null,
        ];
    }
}
