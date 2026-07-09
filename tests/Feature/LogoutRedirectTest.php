<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LogoutRedirectTest extends TestCase
{
    use RefreshDatabase;

    public function test_logout_redirects_to_splash_before_login_page(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user);

        $response = $this->post(route('logout'));

        $response->assertRedirect(route('logout.splash'));
    }
}
