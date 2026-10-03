<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Auth\Events\PasswordReset;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function showRegister()
    {
        return view('auth.register');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ], [
            'email.required' => 'Email wajib diisi',
            'email.email' => 'Format email tidak valid',
            'password.required' => 'Password wajib diisi',
        ]);

        $credentials = $request->only('email', 'password');

        if (!$token = auth()->guard('api')->attempt($credentials)) {
            return back()
                ->with('error', 'Email atau password salah')
                ->withInput($request->only('email'));
        }

        $user = auth()->guard('api')->user();
        $cookie = cookie('token', $token, 60, null, null, false, true);

        if ($user->role === 'admin') {
            return redirect()->route('admin.dashboard')->withCookie($cookie);
        }

        return redirect()->route('home')->withCookie($cookie);
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:8',
        ], [
            'email.unique' => 'Email sudah digunakan, silakan gunakan email lain',
            'email.required' => 'Email wajib diisi',
            'email.email' => 'Format email tidak valid',
            'name.required' => 'Nama wajib diisi',
            'password.required' => 'Password wajib diisi',
            'password.min' => 'Password minimal 8 karakter',
        ]);

        $user = User::create([
            'username' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'pelanggan',
        ]);

        $token = JWTAuth::fromUser($user);
        $cookie = cookie('token', $token, 60, null, null, false, true);

        return redirect()->route('home')->withCookie($cookie);
    }

    public function logout()
    {
        auth()->guard('api')->logout();
        return redirect('/')->withoutCookie('token');
    }

    public function showForgotPassword()
    {
        return view('auth.forgot-password');
    }

    public function sendResetLink(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ], [
            'email.required' => 'Email wajib diisi',
            'email.email' => 'Format email tidak valid',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return back()->withErrors(['email' => 'Kami tidak dapat menemukan akun dengan email tersebut'])->withInput();
        }

        $token = Password::broker()->createToken($user);
        $user->sendPasswordResetNotification($token);

        $resetUrl = route('password.reset', ['token' => $token, 'email' => $user->email]);

        return back()
            ->with('status', 'Tautan untuk mengatur ulang kata sandi telah dikirim ke email Anda.')
            ->with('reset_link', $resetUrl);
    }

    public function showResetPassword($token, Request $request)
    {
        return view('auth.reset-password', [
            'token' => $token,
            'email' => $request->query('email'),
        ]);
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:8|confirmed',
        ], [
            'token.required' => 'Token reset kata sandi tidak valid',
            'email.required' => 'Email wajib diisi',
            'email.email' => 'Format email tidak valid',
            'password.required' => 'Kata sandi baru wajib diisi',
            'password.min' => 'Kata sandi minimal 8 karakter',
            'password.confirmed' => 'Konfirmasi kata sandi tidak cocok',
        ]);

        $status = Password::broker()->reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, string $password) {
                $user->forceFill([
                    'password' => Hash::make($password),
                ])->setRememberToken(Str::random(60));

                $user->save();

                event(new PasswordReset($user));
            }
        );

        if ($status === Password::PASSWORD_RESET) {
            return redirect()->route('login')->with('success', 'Kata sandi berhasil diperbarui! Silakan masuk dengan kata sandi baru Anda.');
        }

        $messages = [
            Password::INVALID_USER => 'Kami tidak dapat menemukan pengguna dengan alamat email tersebut.',
            Password::INVALID_TOKEN => 'Token reset kata sandi tidak valid atau sudah kedaluwarsa.',
            Password::RESET_THROTTLED => 'Harap tunggu beberapa saat sebelum mencoba lagi.',
        ];

        return back()
            ->withInput($request->only('email'))
            ->withErrors(['email' => $messages[$status] ?? 'Gagal mengatur ulang kata sandi. Silakan coba lagi.']);
    }
}
