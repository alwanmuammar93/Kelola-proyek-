<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string  ...$roles
     * @return mixed
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        // Cek user dari session atau Laravel Auth
        $user = session('user') ?? auth()->user();

        // Jika user belum login, redirect ke login
        if (!$user) {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu.');
        }

        // Ambil role user (support array dan object)
        $userRole = is_array($user) ? ($user['role'] ?? null) : ($user->role ?? null);

        // Jika role tidak ditemukan
        if (!$userRole) {
            return redirect()->route('login')->with('error', 'Role tidak valid. Silakan login kembali.');
        }

        // Jika role user tidak sesuai dengan yang diizinkan
        if (!in_array($userRole, $roles)) {
            // Redirect berdasarkan role dengan pesan yang friendly
            if ($userRole === 'kasir') {
                return redirect()->route('kasir.index')
                    ->with('error', 'Anda tidak memiliki akses ke halaman ini. Anda adalah Kasir.');
            } elseif ($userRole === 'admin') {
                return redirect()->route('admin.index')
                    ->with('error', 'Halaman ini khusus untuk role tertentu.');
            }

            // Untuk role lain atau tidak dikenal
            abort(403, 'Akses ditolak. Anda tidak memiliki izin untuk halaman ini.');
        }

        return $next($request);
    }
}