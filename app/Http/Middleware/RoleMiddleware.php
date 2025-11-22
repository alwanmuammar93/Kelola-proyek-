<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        // Ambil user dari session (atau auth)
        $user = session('user');

        // Jika user belum login, redirect ke login
        if (!$user) {
            return redirect()->route('login');
        }

        // Perbaikan: akses role sebagai object, bukan array
        $role = is_array($user) ? ($user['role'] ?? null) : ($user->role ?? null);

        // Jika role user tidak cocok dengan role yang diizinkan
        if (!in_array($role, $roles)) {
            abort(403, 'Akses ditolak. Anda tidak memiliki izin untuk halaman ini.');
        }

        return $next($request);
    }
}
