<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DemoUserSeeder extends Seeder
{
    public function run(): void
    {
        $accounts = [
            ['name' => 'Admin Demo', 'email' => 'admin@admin.telkomsel.com', 'role' => 'admin'],
            ['name' => 'Staff Demo', 'email' => 'staff@staff.telkomsel.com', 'role' => 'staff'],
            ['name' => 'Manager Demo', 'email' => 'manager@manager.telkomsel.com', 'role' => 'manager'],
        ];

        foreach ($accounts as $account) {
            $role = Role::where('name', $account['role'])->first();

            User::updateOrCreate(
                ['email' => $account['email']],
                [
                    'name' => $account['name'],
                    'password' => Hash::make('password'),
                    'role_id' => $role?->id,
                    'email_verified_at' => now(),
                ]
            );
        }
    }
}
