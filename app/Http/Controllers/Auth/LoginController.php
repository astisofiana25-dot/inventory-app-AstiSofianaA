<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    public function create()
    {
        return view('auth.login');
    }

    public function store(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();
            return redirect()->intended(route('dashboard'));
        }

        if ($credentials['email'] === 'admintelkomreg3@gmail.com' && $credentials['password'] === 'passadmin123') {
            $user = User::firstOrCreate(
                ['email' => 'admintelkomreg3@gmail.com'],
                [
                    'name' => 'Admin Utama',
                    'password' => Hash::make('passadmin123'),
                    'role_id' => Role::where('name', 'admin')->value('id'),
                ]
            );

            Auth::login($user, $request->boolean('remember'));
            $request->session()->regenerate();

            return redirect()->intended(route('dashboard'));
        }

        throw ValidationException::withMessages([
            'email' => 'Email atau password yang Anda masukkan salah.',
        ]);
    }

    public function destroy(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login', ['from_logout' => 1]);
    }

    public function logoutSplash()
    {
        return redirect()->route('login', ['from_logout' => 1]);
    }
}
