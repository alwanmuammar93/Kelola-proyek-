<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

class AuthController extends Controller
{
    // Menampilkan form login
    public function loginForm()
    {
        return view('login');
    }

    // Proses login
    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required',
            'password' => 'required',
        ]);

        // Ambil user dari database berdasarkan username
        $user = DB::table('users')->where('username', $request->username)->first();

        if ($user) {

            $passwordInput = $request->password;
            $passwordDB = $user->password;

            $isValid = false;

            // ✔ CEK 1 — Jika password DB adalah bcrypt
            if (Hash::needsRehash($passwordDB) === false) {
                try {
                    $isValid = Hash::check($passwordInput, $passwordDB);
                } catch (\Exception $e) {
                    // Jika DB bukan hash, maka lanjut cek plain text
                    $isValid = false;
                }
            }

            // ✔ CEK 2 — Jika password DB TIDAK pakai hash → cek biasa
            if (!$isValid && $passwordInput === $passwordDB) {
                $isValid = true;
            }

            // Jika cocok (hash atau plain)
            if ($isValid) {

                // Simpan ke session
                Session::put('user', [
                    'id_user' => $user->id_user,
                    'username' => $user->username,
                    'role'     => $user->role,
                ]);

                // Tambahan session login
                Session::put('is_login', true);

                // Redirect sesuai role
                if ($user->role === 'admin') {
                    return redirect()->route('admin.index');
                } elseif ($user->role === 'kasir') {
                    return redirect()->route('kasir.index');
                }
            }
        }

        return back()->withErrors(['login' => 'Username atau password salah.']);
    }

    // Logout
    public function logout()
    {
        Session::forget('user');
        Session::forget('is_login');

        return redirect()->route('login');
    }
}
