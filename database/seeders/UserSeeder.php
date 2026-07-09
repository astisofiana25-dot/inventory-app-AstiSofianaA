<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $accounts = [
            ['name' => 'Admin Utama', 'email' => 'admintelkomreg3@gmail.com', 'role' => 'admin', 'password' => 'passadmin123'],
            ['name' => 'Staff Gudang', 'email' => 'staff@kerjain.test', 'role' => 'staff', 'password' => 'password'],
            ['name' => 'Manager Kantor', 'email' => 'manager@kerjain.test', 'role' => 'manager', 'password' => 'password'],
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
