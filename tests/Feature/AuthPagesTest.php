<?php

namespace Tests\Feature;

use Tests\TestCase;

class AuthPagesTest extends TestCase
{
    public function test_login_page_includes_password_toggle_script(): void
    {
        $response = $this->get(route('login'));

        $response->assertStatus(200);
        $response->assertSee('data-password-toggle');
        $response->assertSee('initPasswordToggles');
    }
}
