<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureAdmin
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (!$user || $user->role !== 'admin') {
            if ($request->wantsJson() || $request->is('api/*')) {
                return response()->json(['error' => 'Accès refusé. Réservé aux administrateurs.'], 403);
            }
            return redirect()->route('login');
        }

        return $next($request);
    }
}
