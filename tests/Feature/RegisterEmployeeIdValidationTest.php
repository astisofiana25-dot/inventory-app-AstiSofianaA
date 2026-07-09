<?php

namespace Tests\Feature;

use Tests\TestCase;

class RegisterEmployeeIdValidationTest extends TestCase
{
    public function test_unknown_employee_id_is_rejected(): void
    {
        $response = $this->from(route('register'))->post(route('register'), [
            'name' => 'Test User',
            'email' => 'test'.uniqid().'@gmail.com',
            'employee_id' => 'EMP999',
            'password' => 'Password123!',
            'password_confirmation' => 'Password123!',
        ]);

        $response->assertStatus(302);
        $response->assertSessionHasErrors(['employee_id']);
    }
}
