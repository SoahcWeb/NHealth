<?php

namespace Database\Factories;

use App\Models\Goal;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Goal>
 */
class GoalFactory extends Factory
{
    protected $model = Goal::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $startsOn = fake()->dateTimeBetween('-2 weeks', 'now');

        return [
            'user_id' => User::factory(),
            'title' => fake()->sentence(3),
            'description' => fake()->sentence(),
            'goal_type' => 'weight',
            'status' => 'draft',
            'direction' => 'decrease',
            'unit' => 'kg',
            'start_value' => fake()->randomFloat(2, 65, 95),
            'target_value' => fake()->randomFloat(2, 55, 85),
            'starts_on' => $startsOn,
            'target_date' => fake()->dateTimeBetween($startsOn, '+2 months'),
            'completed_at' => null,
            'metadata' => null,
        ];
    }

    public function active(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'active',
            'starts_on' => now()->subWeek()->toDateString(),
            'target_date' => now()->addMonth()->toDateString(),
        ]);
    }
}
