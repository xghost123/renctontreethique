<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureModerator
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (!$user || !in_array($user->role, ['admin', 'moderator'])) {
            if ($request->wantsJson() || $request->is('api/*')) {
                return response()->json(['error' => 'Accès réservé aux modérateurs.'], 403);
            }
            return redirect()->route('login');
        }

        return $next($request);
    }
}
