<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LoginTest extends TestCase
{
    use RefreshDatabase;

    public function test_login_authenticates_and_redirects_to_dashboard()
    {
        // Create user
        $user = User::factory()->create([
            'username' => 'mrlv',
            'password' => bcrypt('password'),
        ]);

        $response = $this->post(route('login'), [
            'username' => 'mrlv',
            'password' => 'password',
        ]);

        $response->assertRedirect('/dashboard');
        $this->assertAuthenticatedAs($user);
    }

    public function test_login_fails_with_invalid_credentials()
    {
        // Create user
        User::factory()->create([
            'username' => 'mrlv',
            'password' => bcrypt('password'),
        ]);

        $response = $this->post(route('login'), [
            'username' => 'mrlv',
            'password' => 'wrong-password',
        ]);

        $response->assertSessionHasErrors('username');
        $this->assertGuest();
    }
}
