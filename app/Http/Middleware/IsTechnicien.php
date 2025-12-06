<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class IsTechnicien
{
    public function handle(Request $request, Closure $next): Response
    {
        // Non connecté → rediriger vers login
        if (!$request->user()) {
            return redirect()->route('login'); // ou redirect('/login')
        }

        // Connecté mais pas technicien → 403
        if ($request->user()->role !== 'technicien') {
            abort(403, 'Accès refusé. Réservé aux techniciens.');
        }

        return $next($request);
    }
}
