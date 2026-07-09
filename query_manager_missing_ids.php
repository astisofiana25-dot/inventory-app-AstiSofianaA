<?php
require __DIR__ . '/vendor/autoload.php';
$app = require __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();
$managerIds = App\Models\Employee::where('role', 'manager')->pluck('employee_id');
$registered = App\Models\User::whereIn('employee_id', $managerIds)->pluck('employee_id');
$missing = $managerIds->diff($registered);
echo $missing->isEmpty() ? 'NONE' : implode(',', $missing->all());
