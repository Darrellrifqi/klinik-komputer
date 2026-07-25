<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, string ...$roles)
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $user = auth()->user();

        if ($user->status !== 'active') {
            auth()->logout();
            return redirect()->route('login')
                ->with('error', 'Akun Anda belum diaktifkan atau telah ditolak.');
        }

        if (!in_array($user->role, $roles)) {
            return redirect()->route('dashboard')->with('error', 'Akses tidak diizinkan.');
        }

        return $next($request);
    }
}
