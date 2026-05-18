<?php

namespace Tests\Feature;

use App\Models\CheckIn;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CheckInTest extends TestCase
{
    use RefreshDatabase;

    public function test_guests_are_redirected_from_check_in_routes(): void
    {
        $this->get(route('check-ins.index'))
            ->assertRedirect(route('login'));

        $this->post(route('check-ins.store'), [])
            ->assertRedirect(route('login'));
    }

    public function test_authenticated_user_can_create_a_private_check_in(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post(route('check-ins.store'), [
            'recorded_on' => '2026-05-18',
            'weight_kg' => '78.4',
            'sleep_hours' => '7.5',
            'steps' => 10432,
            'energy_level' => 8,
            'mood_level' => 7,
            'notes' => 'Strong training day.',
        ]);

        $response->assertRedirect(route('check-ins.index'));

        $checkIn = CheckIn::query()->whereBelongsTo($user)->first();

        $this->assertNotNull($checkIn);
        $this->assertSame('Strong training day.', $checkIn->notes);
        $this->assertSame(78.4, (float) $checkIn->weight_kg);
    }

    public function test_user_only_sees_their_own_check_ins(): void
    {
        $user = User::factory()->create();
        $otherUser = User::factory()->create();

        CheckIn::create([
            'user_id' => $user->id,
            'recorded_on' => '2026-05-18',
            'notes' => 'Visible note',
            'energy_level' => 6,
        ]);

        CheckIn::create([
            'user_id' => $otherUser->id,
            'recorded_on' => '2026-05-18',
            'notes' => 'Hidden note',
            'energy_level' => 9,
        ]);

        $this->actingAs($user)
            ->get(route('check-ins.index'))
            ->assertOk()
            ->assertSee('Visible note')
            ->assertDontSee('Hidden note');
    }

    public function test_saving_the_same_day_updates_the_existing_check_in_for_the_same_user(): void
    {
        $user = User::factory()->create();

        $checkIn = CheckIn::create([
            'user_id' => $user->id,
            'recorded_on' => '2026-05-18',
            'weight_kg' => 83.0,
            'notes' => 'Initial note',
        ]);

        $this->actingAs($user)->post(route('check-ins.store'), [
            'recorded_on' => '2026-05-18',
            'weight_kg' => '82.2',
            'sleep_hours' => '8.0',
            'notes' => 'Updated note',
        ])->assertRedirect(route('check-ins.index'));

        $checkIn->refresh();

        $this->assertSame(1, CheckIn::query()->whereBelongsTo($user)->count());
        $this->assertSame('Updated note', $checkIn->notes);
        $this->assertSame(82.2, (float) $checkIn->weight_kg);
    }
}
