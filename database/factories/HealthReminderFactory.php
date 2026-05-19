<?php

namespace Database\Factories;

use App\Models\HealthReminder;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<HealthReminder>
 */
class HealthReminderFactory extends Factory
{
    protected $model = HealthReminder::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $definition = fake()->randomElement(HealthReminder::definitions());

        return [
            'user_id' => User::factory(),
            'reminder_key' => $definition['key'],
            'reminder_name' => $definition['name'],
            'is_enabled' => false,
        ];
    }

    public function enabled(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_enabled' => true,
        ]);
    }

    public function disabled(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_enabled' => false,
        ]);
    }
}
