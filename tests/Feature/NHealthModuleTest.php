<?php

namespace Tests\Feature;

use App\Models\CheckIn;
use App\Models\Goal;
use App\Models\HealthProfile;
use App\Models\HealthReminder;
use App\Models\User;
use App\Models\WeightEntry;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class NHealthModuleTest extends TestCase
{
    use RefreshDatabase;

    public function test_guests_cannot_access_nhealth_routes(): void
    {
        $routes = [
            ['GET', route('nhealth.dashboard')],
            ['GET', route('nhealth.profile.edit')],
            ['PATCH', route('nhealth.profile.update')],
            ['GET', route('nhealth.goals.index')],
            ['POST', route('nhealth.goals.store')],
            ['GET', route('nhealth.weight.index')],
            ['POST', route('nhealth.weight.store')],
            ['GET', route('nhealth.reminders.index')],
            ['PATCH', route('nhealth.reminders.update')],
            ['GET', route('nhealth.statistics.index')],
            ['GET', route('nhealth.export.summary')],
        ];

        foreach ($routes as [$method, $uri]) {
            $this->call($method, $uri)
                ->assertRedirect(route('login'));
        }
    }

    public function test_authenticated_user_can_access_the_nhealth_dashboard(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->get(route('nhealth.dashboard'))
            ->assertOk()
            ->assertSee('Personal dashboard');
    }

    public function test_user_can_create_and_update_their_health_profile(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->patch(route('nhealth.profile.update'), [
                'date_of_birth' => '1994-03-18',
                'biological_sex' => 'female',
                'height_cm' => '168.5',
                'activity_level' => 'moderate',
                'unit_system' => 'metric',
                'health_notes' => 'Initial health profile.',
            ])
            ->assertRedirect(route('nhealth.profile.edit'));

        $this->assertDatabaseHas('health_profiles', [
            'user_id' => $user->id,
            'activity_level' => 'moderate',
            'health_notes' => 'Initial health profile.',
        ]);

        $this->actingAs($user)
            ->patch(route('nhealth.profile.update'), [
                'height_cm' => '170.0',
                'health_notes' => 'Updated health profile.',
            ])
            ->assertRedirect(route('nhealth.profile.edit'));

        $profile = HealthProfile::query()->whereBelongsTo($user)->first();

        $this->assertNotNull($profile);
        $this->assertSame(1, HealthProfile::query()->whereBelongsTo($user)->count());
        $this->assertSame(170.0, (float) $profile->height_cm);
        $this->assertSame('Updated health profile.', $profile->health_notes);
    }

    public function test_user_can_create_a_goal(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->post(route('nhealth.goals.store'), [
                'title' => 'Summer cut',
                'description' => 'Lean down steadily.',
                'goal_type' => 'weight',
                'status' => 'active',
                'direction' => 'decrease',
                'unit' => 'kg',
                'start_value' => '84.5',
                'target_value' => '78.0',
                'starts_on' => now()->subDays(5)->toDateString(),
                'target_date' => now()->addDays(30)->toDateString(),
            ])
            ->assertRedirect(route('nhealth.goals.index'));

        $this->assertDatabaseHas('goals', [
            'user_id' => $user->id,
            'title' => 'Summer cut',
            'status' => 'active',
        ]);
    }

    public function test_user_cannot_see_another_users_goals(): void
    {
        $user = User::factory()->create();
        $otherUser = User::factory()->create();

        $this->createGoal($user, [
            'title' => 'Visible goal',
        ]);

        $this->createGoal($otherUser, [
            'title' => 'Hidden goal',
        ]);

        $this->actingAs($user)
            ->get(route('nhealth.goals.index'))
            ->assertOk()
            ->assertSee('Visible goal')
            ->assertDontSee('Hidden goal');
    }

    public function test_user_can_add_a_weight_entry(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->post(route('nhealth.weight.store'), [
                'recorded_on' => now()->toDateString(),
                'weight_kg' => '79.40',
                'source' => 'manual',
                'notes' => 'Fasted morning weigh-in.',
            ])
            ->assertRedirect(route('nhealth.weight.index'));

        $this->assertDatabaseHas('weight_entries', [
            'user_id' => $user->id,
            'notes' => 'Fasted morning weigh-in.',
            'source' => 'manual',
        ]);
    }

    public function test_user_cannot_see_another_users_weight_entries(): void
    {
        $user = User::factory()->create();
        $otherUser = User::factory()->create();

        $this->createWeightEntry($user, [
            'notes' => 'Visible weight note',
        ]);

        $this->createWeightEntry($otherUser, [
            'notes' => 'Hidden weight note',
        ]);

        $this->actingAs($user)
            ->get(route('nhealth.weight.index'))
            ->assertOk()
            ->assertSee('Visible weight note')
            ->assertDontSee('Hidden weight note');
    }

    public function test_user_can_manage_their_internal_reminders(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->patch(route('nhealth.reminders.update'), [
                'reminders' => [
                    HealthReminder::DAILY_CHECK_IN => ['is_enabled' => '1'],
                    HealthReminder::WEIGH_IN => ['is_enabled' => '1'],
                ],
            ])
            ->assertRedirect(route('nhealth.reminders.index'));

        $this->assertDatabaseHas('health_reminders', [
            'user_id' => $user->id,
            'reminder_key' => HealthReminder::DAILY_CHECK_IN,
            'is_enabled' => true,
        ]);

        $this->assertDatabaseHas('health_reminders', [
            'user_id' => $user->id,
            'reminder_key' => HealthReminder::WEIGH_IN,
            'is_enabled' => true,
        ]);

        $this->assertDatabaseHas('health_reminders', [
            'user_id' => $user->id,
            'reminder_key' => HealthReminder::ACTIVE_GOAL,
            'is_enabled' => false,
        ]);

        $this->actingAs($user)
            ->get(route('nhealth.reminders.index'))
            ->assertOk()
            ->assertSee('Check-in quotidien')
            ->assertSee('Pesee');
    }

    public function test_user_can_view_their_monthly_statistics(): void
    {
        $user = User::factory()->create();

        $this->createCheckIn($user, [
            'recorded_on' => now()->startOfMonth()->addDay()->toDateString(),
            'sleep_hours' => 7.5,
            'energy_level' => 6,
            'mood_level' => 7,
            'notes' => 'First monthly entry',
        ]);

        $this->createCheckIn($user, [
            'recorded_on' => now()->startOfMonth()->addDays(2)->toDateString(),
            'sleep_hours' => 8.0,
            'energy_level' => 8,
            'mood_level' => 8,
            'notes' => 'Second monthly entry',
        ]);

        $this->createWeightEntry($user, [
            'recorded_on' => now()->startOfMonth()->addDay()->toDateString(),
            'weight_kg' => 80.5,
        ]);

        $this->createWeightEntry($user, [
            'recorded_on' => now()->startOfMonth()->addDays(6)->toDateString(),
            'weight_kg' => 79.8,
        ]);

        $this->createGoal($user, [
            'title' => 'Visible monthly goal',
            'status' => 'active',
        ]);

        $this->actingAs($user)
            ->get(route('nhealth.statistics.index'))
            ->assertOk()
            ->assertSee('Monthly statistics')
            ->assertSee('Check-ins this month')
            ->assertSee('80.50 kg')
            ->assertSee('79.80 kg')
            ->assertSee('Unlocked badges')
            ->assertSee('Premier check-in');
    }

    public function test_pdf_export_is_protected_by_auth_and_available_to_authenticated_users(): void
    {
        $this->get(route('nhealth.export.summary'))
            ->assertRedirect(route('login'));

        $user = User::factory()->create();

        $this->actingAs($user)
            ->get(route('nhealth.export.summary'))
            ->assertOk()
            ->assertHeader('content-type', 'application/pdf');
    }

    /**
     * @param  array<string, mixed>  $attributes
     */
    private function createGoal(User $user, array $attributes = []): Goal
    {
        return $user->goals()->create(array_merge([
            'title' => 'Default goal',
            'description' => 'Default goal description.',
            'goal_type' => 'weight',
            'status' => 'active',
            'direction' => 'decrease',
            'unit' => 'kg',
            'start_value' => 82.5,
            'target_value' => 78.0,
            'starts_on' => now()->subDays(10)->toDateString(),
            'target_date' => now()->addDays(20)->toDateString(),
        ], $attributes));
    }

    /**
     * @param  array<string, mixed>  $attributes
     */
    private function createWeightEntry(User $user, array $attributes = []): WeightEntry
    {
        return $user->weightEntries()->create(array_merge([
            'recorded_on' => now()->toDateString(),
            'weight_kg' => 80.0,
            'source' => 'manual',
            'notes' => 'Default weight note',
        ], $attributes));
    }

    /**
     * @param  array<string, mixed>  $attributes
     */
    private function createCheckIn(User $user, array $attributes = []): CheckIn
    {
        return $user->checkIns()->create(array_merge([
            'recorded_on' => now()->toDateString(),
            'sleep_hours' => 7.0,
            'energy_level' => 7,
            'mood_level' => 7,
            'notes' => 'Default check-in note',
        ], $attributes));
    }
}
