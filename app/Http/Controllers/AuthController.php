<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    /**
     * Menampilkan form login
     */
    public function loginForm()
    {
        // Jika sudah login, redirect sesuai role
        if (Auth::check()) {
            if (Auth::user()->role === 'admin') {
                return redirect()->route('admin.index');
            } elseif (Auth::user()->role === 'kasir') {
                return redirect()->route('kasir.index');
            }
        }

        return view('login');
    }

    /**
     * Proses login menggunakan Laravel Auth
     */
    public function login(Request $request)
    {
        // Validasi input
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ], [
            'username.required' => 'Username wajib diisi.',
            'password.required' => 'Password wajib diisi.',
        ]);

        // Credentials untuk login
        $credentials = [
            'username' => $request->username,
            'password' => $request->password,
        ];

        // Attempt login menggunakan Laravel Auth
        if (Auth::attempt($credentials, $request->filled('remember'))) {
            // Regenerate session untuk keamanan
            $request->session()->regenerate();

            // Ambil user yang login
            $user = Auth::user();

            // Redirect berdasarkan role
            if ($user->role === 'admin') {
                return redirect()->intended(route('admin.index'))
                    ->with('success', 'Selamat datang, Admin ' . $user->username . '!');
            } elseif ($user->role === 'kasir') {
                return redirect()->intended(route('kasir.index'))
                    ->with('success', 'Selamat datang, ' . $user->username . '!');
            }

            // Fallback jika role tidak dikenali
            return redirect()->intended(route('admin.index'));
        }

        // Login gagal
        return back()
            ->withErrors([
                'username' => 'Username atau password salah.',
            ])
            ->withInput($request->only('username'));
    }

    /**
     * Logout menggunakan Laravel Auth
     */
    public function logout(Request $request)
    {
        // Ambil nama user sebelum logout (untuk pesan)
        $username = Auth::user()->username ?? 'User';

        // Logout dari Laravel Auth
        Auth::logout();

        // Invalidate session
        $request->session()->invalidate();

        // Regenerate CSRF token
        $request->session()->regenerateToken();

        // Redirect ke login dengan pesan
        return redirect()->route('login')
            ->with('success', 'Anda telah berhasil logout. Sampai jumpa, ' . $username . '!');
    }
}