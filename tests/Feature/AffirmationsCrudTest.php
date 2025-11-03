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
            ->set('form.type', 1)
            ->set('form.description', 'Start the day with gratitude')
            ->call('save')
            ->assertHasNoErrors();

        $this->assertDatabaseHas('affirmations', [
            'user_id' => $user->id,
            'description' => 'Start the day with gratitude',
        ]);
    }

    public function test_user_can_edit_affirmation(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $affirmation = Affirmation::create([
            'user_id' => $user->id,
            'type' => true,
            'description' => 'Old',
        ]);

        Livewire::test(\App\Livewire\Affirmations\Edit::class, ['affirmation' => $affirmation->id])
            ->set('form.description', 'Updated')
            ->set('form.type', 0)
            ->call('save')
            ->assertHasNoErrors();

        $this->assertDatabaseHas('affirmations', [
            'id' => $affirmation->id,
            'description' => 'Updated',
            'type' => false,
        ]);
    }

    public function test_user_can_delete_affirmation(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $affirmation = Affirmation::create([
            'user_id' => $user->id,
            'type' => true,
            'description' => 'To be deleted',
        ]);

        Livewire::test(\App\Livewire\Affirmations\Edit::class, ['affirmation' => $affirmation->id])
            ->call('delete')
            ->assertHasNoErrors();

        $this->assertDatabaseMissing('affirmations', [
            'id' => $affirmation->id,
        ]);
    }
}
