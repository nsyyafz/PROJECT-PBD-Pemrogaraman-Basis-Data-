<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class isSuperadmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $userId = $request->session()->get('user_id');

        if (!$userId) {
            return redirect()->route('login')
                ->withErrors(['error' => 'Silakan login terlebih dahulu.']);
        }

        // Raw SQL
        $user = DB::selectOne(
            'SELECT idrole FROM user WHERE iduser = ?',
            [$userId]
        );

        if (!$user || $user->idrole != 2) {
            abort(403, 'Akses ditolak. Hanya Admin yang diizinkan.');
        }

        return $next($request);
    }
}
