<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_login_and_logout(): void
    {
        $user = User::factory()->create(['is_admin' => true, 'password' => Hash::make('test-only-password')]);

        $this->post(route('login.submit'), ['email' => $user->email, 'password' => 'test-only-password'])->assertRedirect(route('admin.dashboard'));
        $this->assertAuthenticatedAs($user);
        $this->post(route('logout'))->assertRedirect(route('login'));
        $this->assertGuest();
        $this->get(route('admin.dashboard'))->assertRedirect(route('login'));
    }

    public function test_non_admin_credentials_are_rejected(): void
    {
        $user = User::factory()->create(['is_admin' => false, 'password' => Hash::make('test-only-password')]);

        $this->post(route('login.submit'), ['email' => $user->email, 'password' => 'test-only-password'])->assertSessionHasErrors('email');
        $this->assertGuest();
    }

    public function test_wrong_password_is_rejected(): void
    {
        $user = User::factory()->create(['is_admin' => true]);

        $this->post(route('login.submit'), ['email' => $user->email, 'password' => 'incorrect'])->assertSessionHasErrors('email');
        $this->assertGuest();
    }

    public function test_login_attempts_are_throttled(): void
    {
        for ($attempt = 0; $attempt < 5; $attempt++) {
            $this->post(route('login.submit'), ['email' => 'missing@example.test', 'password' => 'incorrect'])->assertSessionHasErrors('email');
        }
        $this->post(route('login.submit'), ['email' => 'missing@example.test', 'password' => 'incorrect'])->assertStatus(429);
    }
}
