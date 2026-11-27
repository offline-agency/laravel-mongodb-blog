<?php

namespace Tests\Feature;

use Tests\Concerns\SeedsBlog;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    use SeedsBlog;

    public function test_login_form_is_shown_to_guests(): void
    {
        $this->get('/login')->assertOk();
    }

    public function test_user_can_log_in_with_the_demo_credentials(): void
    {
        $this->seedUser();

        $this->post('/login', ['email' => 'test@test.com', 'password' => 'demo'])
            ->assertRedirect('/admin/dashboard');

        $this->assertAuthenticated();
    }

    public function test_login_fails_with_a_wrong_password(): void
    {
        $this->seedUser();

        $this->from('/login')
            ->post('/login', ['email' => 'test@test.com', 'password' => 'wrong'])
            ->assertRedirect('/login')
            ->assertSessionHasErrors('email');

        $this->assertGuest();
    }

    public function test_login_fails_for_an_unknown_email(): void
    {
        $this->from('/login')
            ->post('/login', ['email' => 'nobody@test.com', 'password' => 'demo'])
            ->assertRedirect('/login')
            ->assertSessionHasErrors('email');
    }

    public function test_authenticated_user_is_redirected_away_from_login(): void
    {
        $this->actingAs($this->seedUser())
            ->get('/login')
            ->assertRedirect('/home');
    }

    public function test_user_can_log_out(): void
    {
        $this->actingAs($this->seedUser())
            ->get('/logout')
            ->assertRedirect('/');

        $this->assertGuest();
    }
}
