<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\Notification;
use App\Models\Role;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class RegisterController extends Controller
{
    public function create()
    {
        return view('auth.register');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                'unique:users',
                function ($attribute, $value, $fail) {
                    $lower = strtolower($value);

                    if (! str_ends_with($lower, '@gmail.com')) {
                        $fail('Email harus menggunakan domain @gmail.com.');
                    }
                },
            ],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'employee_id' => ['required', 'string', 'max:50', 'unique:users,employee_id'],
        ]);

        $employee = Employee::where('employee_id', $validated['employee_id'])->first();
        if (! $employee) {
            return back()->withErrors(['employee_id' => 'ID karyawan tidak ditemukan.'])->withInput();
        }

        $existingUser = User::where('employee_id', $employee->employee_id)->exists();
        if ($existingUser) {
            return back()->withErrors(['employee_id' => 'ID karyawan sudah terdaftar.'])->withInput();
        }

        $roleName = $employee->role;
        if (! in_array($roleName, ['manager', 'staff'], true)) {
            return back()->withErrors(['employee_id' => 'Role karyawan tidak valid.'])->withInput();
        }

        $role = Role::where('name', $roleName)->first();
        if (! $role) {
            return back()->withErrors(['employee_id' => 'Role terkait tidak ditemukan. Silakan hubungi administrator.'])->withInput();
        }

        $employee->update(['name' => $validated['name']]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role_id' => $role->id,
            'employee_id' => $employee->employee_id,
        ]);

        event(new Registered($user));

        User::whereHas('role', function ($q) {
            $q->whereIn('name', ['admin']);
        })->each(function ($admin) use ($user) {
            Notification::create([
                'user_id' => $admin->id,
                'title' => 'Akun baru',
                'message' => "{$user->name} terdaftar sebagai {$user->role->name}.",
                'type' => 'user_registered',
                'data' => ['user_id' => $user->id, 'role' => $user->role->name],
            ]);
        });

        return redirect()->route('login')->with('success', 'Akun berhasil dibuat. Silakan masuk dengan email dan password Anda.');
    }
}
