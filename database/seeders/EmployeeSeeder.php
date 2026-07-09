<?php

namespace Database\Seeders;

use App\Models\Employee;
use Illuminate\Database\Seeder;

class EmployeeSeeder extends Seeder
{
    public function run(): void
    {
        $employees = [
            ['employee_id' => 'EMP001', 'name' => '', 'role' => 'staff'],
            ['employee_id' => 'EMP002', 'name' => '', 'role' => 'manager'],
            ['employee_id' => 'EMP003', 'name' => '', 'role' => 'staff'],
            ['employee_id' => 'EMP004', 'name' => '', 'role' => 'manager'],
            ['employee_id' => 'EMP005', 'name' => '', 'role' => 'staff'],
            ['employee_id' => 'EMP006', 'name' => '', 'role' => 'manager'],
            ['employee_id' => 'EMP007', 'name' => '', 'role' => 'staff'],
            ['employee_id' => 'EMP008', 'name' => '', 'role' => 'manager'],
            ['employee_id' => 'EMP009', 'name' => '', 'role' => 'staff'],
            ['employee_id' => 'EMP010', 'name' => '', 'role' => 'staff'],
            ['employee_id' => 'EMP011', 'name' => '', 'role' => 'staff'],
            ['employee_id' => 'EMP012', 'name' => '', 'role' => 'staff'],
            ['employee_id' => 'EMP013', 'name' => '', 'role' => 'staff'],
            ['employee_id' => 'EMP014', 'name' => '', 'role' => 'manager'],
            ['employee_id' => 'EMP015', 'name' => '', 'role' => 'manager'],
            ['employee_id' => 'EMP016', 'name' => '', 'role' => 'manager'],
            ['employee_id' => 'EMP017', 'name' => '', 'role' => 'manager'],
            ['employee_id' => 'EMP018', 'name' => '', 'role' => 'manager'],
        ];

        foreach ($employees as $employee) {
            Employee::updateOrCreate(
                ['employee_id' => $employee['employee_id']],
                $employee
            );
        }
    }
}
