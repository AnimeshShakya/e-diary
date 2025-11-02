<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Livewire\Admin\Users\Edit as EditUser;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Livewire\Livewire;
use Tests\TestCase;

class NonAdminPasswordChangeTest extends TestCase
{
    use RefreshDatabase;

    public function test_non_admin_can_update_password_with_correct_current_password(): void
    {
        /** @var User $user */
        $user = User::factory()->create([
            'password' => Hash::make('old-password'),
            'role' => 'user',
        ]);

        $this->actingAs($user);

        Livewire::test(EditUser::class, ['user' => $user])
            ->set('form.password', 'old-password')
            ->set('form.new_password', 'new-secure-password')
            ->set('form.password_confirmation', 'new-secure-password')
            ->call('save')
            ->assertHasNoErrors()
            ->assertRedirect(route('settings.password'));

        $this->assertTrue(Hash::check('new-secure-password', $user->fresh()->password));
    }

    public function test_non_admin_cannot_update_password_with_wrong_current_password(): void
    {
        /** @var User $user */
        $user = User::factory()->create([
            'password' => Hash::make('old-password'),
            'role' => 'user',
        ]);

        $this->actingAs($user);

        Livewire::test(EditUser::class, ['user' => $user])
            ->set('form.password', 'incorrect-old-password')
            ->set('form.new_password', 'new-secure-password')
            ->set('form.password_confirmation', 'new-secure-password')
            ->call('save')
            ->assertHasErrors(['form.password']);

        $this->assertTrue(Hash::check('old-password', $user->fresh()->password));
    }
}
