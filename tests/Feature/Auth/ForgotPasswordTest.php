<?php

declare(strict_types=1);

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Password;
use Tests\TestCase;

class ForgotPasswordTest extends TestCase
{
    use RefreshDatabase;

    public function test_forgot_password_page_can_be_rendered(): void
    {
        $response = $this->get(route('password.request'));

        $response->assertStatus(200);
        $response->assertSee('Email');
    }

    public function test_reset_link_can_be_requested(): void
    {
        $user = User::factory()->create(['email' => 'jane@example.com']);

        $response = $this->post(route('password.email'), [
            'email' => 'jane@example.com',
        ]);

        $response->assertSessionHas('status', trans(Password::RESET_LINK_SENT));
        $response->assertRedirect();
    }

    public function test_invalid_email_shows_error(): void
    {
        $response = $this->from(route('password.request'))->post(route('password.email'), [
            'email' => 'not-a-user@example.com',
        ]);

        $response->assertSessionHasErrors('email');
        $response->assertRedirect(route('password.request'));
    }
}
