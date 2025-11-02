<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SettingsPasswordRouteTest extends TestCase
{
    use RefreshDatabase;

    public function test_authenticated_user_can_view_password_settings_page(): void
    {
        /** @var User $user */
        $user = User::factory()->create(['role' => 'user']);

        $this->actingAs($user)
            ->get(route('settings.password'))
            ->assertOk()
            ->assertSee('Current Password');
    }
}
