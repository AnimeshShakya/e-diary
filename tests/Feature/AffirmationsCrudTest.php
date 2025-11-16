<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\Affirmation;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class AffirmationsCrudTest extends TestCase
{
    use RefreshDatabase;

    public function test_index_renders_for_authenticated_user(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $this->get(route('affirmations.index'))
            ->assertStatus(200)
            ->assertSee('Affirmations');
    }

    public function test_user_can_create_affirmation(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        Livewire::test(\App\Livewire\Affirmations\Create::class)
            ->set('form.morning_affirmation', 'Morning test affirmation')
            ->set('form.night_affirmation', 'Night test affirmation')
            ->call('save')
            ->assertHasNoErrors();

        $this->assertDatabaseHas('affirmations', [
            'user_id' => $user->id,
            'morning_affirmation' => 'Morning test affirmation',
            'night_affirmation' => 'Night test affirmation',
        ]);
    }

    public function test_user_can_edit_affirmation(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $affirmation = Affirmation::create([
            'user_id' => $user->id,
            'morning_affirmation' => 'Original morning',
            'night_affirmation' => 'Original night',
        ]);

        Livewire::test(\App\Livewire\Affirmations\Edit::class, ['affirmation' => $affirmation->id])
            ->set('form.morning_affirmation', 'Updated morning')
            ->set('form.night_affirmation', 'Updated night')
            ->call('save')
            ->assertHasNoErrors();

        $this->assertDatabaseHas('affirmations', [
            'id' => $affirmation->id,
            'morning_affirmation' => 'Updated morning',
            'night_affirmation' => 'Updated night',
        ]);
    }

    public function test_user_can_delete_affirmation(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $affirmation = Affirmation::create([
            'user_id' => $user->id,
            'morning_affirmation' => 'To be deleted morning',
            'night_affirmation' => 'To be deleted night',
        ]);

        Livewire::test(\App\Livewire\Affirmations\Edit::class, ['affirmation' => $affirmation->id])
            ->call('delete')
            ->assertHasNoErrors();

        $this->assertDatabaseMissing('affirmations', [
            'id' => $affirmation->id,
        ]);
    }
}
