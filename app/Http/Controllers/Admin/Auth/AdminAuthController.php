<?php

namespace App\Http\Controllers\Admin\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use RyanChandler\LaravelCloudflareTurnstile\Rules\Turnstile;

class AdminAuthController extends Controller
{
    // Tampilkan form login admin
    public function showLoginForm()
    {
        return view('admin.login');
    }

    // Proses login
    public function login(Request $request)
    {
        // 1. Validasi SEMUA data dulu (termasuk Turnstile)
        $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ], [
    ]);

        // 2. AMBIL HANYA email dan password saja untuk login
        // Jangan kirim cf-turnstile-response ke fungsi attempt()
        $credentials = $request->only('email', 'password');

        // 3. Proses login dengan credentials yang sudah bersih
        if (Auth::guard('admin')->attempt($credentials, $request->remember)) {
            $request->session()->regenerate();

            return redirect()->route('admin.dashboard');
        }

        return back()->withErrors([
            'email' => 'Email atau password salah',
        ]);
    }

    // Logout admin
    public function logout(Request $request)
    {
        Auth::guard('admin')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('admin.login');
    }
}
