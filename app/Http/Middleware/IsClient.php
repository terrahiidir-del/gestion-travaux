<?php
// app/Http/Middleware/IsClient.php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class IsClient
{
    public function handle(Request $request, Closure $next)
    {
        // si non connecté → vers login
        if (!$request->user()) {
            return redirect()->route('login');
        }

        // si connecté mais rôle != client → 403
        if ($request->user()->role !== 'client') {
            abort(403, 'Accès refusé. Réservé aux clients.');
        }

        return $next($request);
    }
}
