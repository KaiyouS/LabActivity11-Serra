<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_can_view_login_page(): void
    {
        $response = $this->get('/login');

        $response->assertStatus(200);
        $response->assertSee('Sign In');
        $response->assertSee('Barangay Bulletin');
        $response->assertSee('Purok Serra');
    }

    public function test_user_can_login_with_valid_credentials_and_redirects_to_posts(): void
    {
        $user = User::create([
            'name' => 'Lola Serra',
            'email' => 'lola.serra@bulletin.test',
            'password' => Hash::make('bulletin2026'),
        ]);

        $response = $this->post('/login', [
            'email' => 'lola.serra@bulletin.test',
            'password' => 'bulletin2026',
        ]);

        $response->assertRedirect('/posts');
        $this->assertAuthenticatedAs($user);
    }

    public function test_user_cannot_login_with_invalid_credentials(): void
    {
        User::create([
            'name' => 'Lola Serra',
            'email' => 'lola.serra@bulletin.test',
            'password' => Hash::make('bulletin2026'),
        ]);

        $response = $this->post('/login', [
            'email' => 'lola.serra@bulletin.test',
            'password' => 'wrongpassword',
        ]);

        $response->assertSessionHasErrors('email');
        $this->assertGuest();
    }

    public function test_authenticated_user_can_logout(): void
    {
        $user = User::create([
            'name' => 'Lola Serra',
            'email' => 'lola.serra@bulletin.test',
            'password' => Hash::make('bulletin2026'),
        ]);

        $response = $this->actingAs($user)->post('/logout');

        $response->assertRedirect('/login');
        $this->assertGuest();
    }
}
