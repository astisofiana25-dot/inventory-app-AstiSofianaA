<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\ResetPasswordCodeMail;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class PasswordResetCodeController extends Controller
{
    public function create()
    {
        return view('auth.forgot-password');
    }

    public function store(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email'],
        ]);

        $user = User::where('email', $request->email)->first();
        if (! $user) {
            return back()->withErrors(['email' => 'Email tidak ditemukan.']);
        }

        $code = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);

        cache()->put('password_reset_code_'.$request->email, $code, now()->addMinutes(10));

        Mail::to($request->email)->send(new ResetPasswordCodeMail($code));

        return redirect()->route('password.code.verify.show')->with('email', $request->email);
    }

    public function showVerifyForm(Request $request)
    {
        return view('auth.password-code-verify', [
            'email' => session('email', $request->email),
        ]);
    }

    public function verifyCode(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email'],
            'code' => ['required', 'string', 'size:6'],
        ]);

        $storedCode = cache()->get('password_reset_code_'.$request->email);
        if (! $storedCode || $storedCode !== $request->code) {
            return back()->withErrors(['code' => 'Kode konfirmasi tidak valid atau sudah kadaluarsa.'])->withInput();
        }

        $token = Str::random(60);
        cache()->put('password_reset_token_'.$request->email, $token, now()->addMinutes(10));
        cache()->forget('password_reset_code_'.$request->email);

        return redirect()->route('password.reset.code', ['token' => $token])->with('email', $request->email);
    }

    public function showResetForm(Request $request, string $token)
    {
        $email = session('email', $request->email);
        if (! $email || ! cache()->has('password_reset_token_'.$email) || cache()->get('password_reset_token_'.$email) !== $token) {
            return redirect()->route('password.request')->withErrors(['email' => 'Token reset tidak valid atau telah kadaluarsa. Silakan ulangi proses lupa password.']);
        }

        return view('auth.reset-password-code', [
            'token' => $token,
            'email' => $email,
        ]);
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'token' => ['required', 'string'],
            'email' => ['required', 'email'],
            'password' => ['required', 'confirmed', 'min:8'],
        ]);

        $storedToken = cache()->get('password_reset_token_'.$request->email);
        if (! $storedToken || $storedToken !== $request->token) {
            return back()->withErrors(['email' => 'Token reset tidak valid atau sudah kadaluarsa.']);
        }

        $user = User::where('email', $request->email)->first();
        if (! $user) {
            return back()->withErrors(['email' => 'Email tidak ditemukan.']);
        }

        $user->forceFill([
            'password' => Hash::make($request->password),
        ])->save();

        cache()->forget('password_reset_token_'.$request->email);

        return redirect()->route('login')->with('status', 'Password berhasil diubah. Silakan login dengan password baru.');
    }
}
