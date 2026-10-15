<?php

namespace App\Http\Controllers;

use App\Models\CookieConsent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;   
use Illuminate\Support\Str;

class RgdController extends Controller
{
    //Enregistre le choix de cookies et dépose les cookies côté navigateur.
    public function saveCookieConsent(Request $request)
    {
        $choices = [
            'analytics' => (bool) $request->boolean('analytics'),
            'ads'       => (bool) $request->boolean('ads'),
        ];

        $visitorId = $request->cookie('visitor_id') ?? Str::uuid()->toString();

        CookieConsent::create([
            'user_id'        => optional($request->user())->id,
            'visitor_id'     => $visitorId,
            'choices'        => $choices,
            'policy_version' => '1.0',
            'ip'             => $request->ip(),
            'consented_at'   => now(),
        ]);

        return back()
            ->cookie('visitor_id', $visitorId, 60 * 24 * 365)  // la durée de vie du cookie en minutes.
            ->cookie('cookie_consent', json_encode($choices), 60 * 24 * 365); 
    }

    /**
     * Export des données personnelles de l'utilisateur connecté (JSON).
     */
    public function export(Request $request)
    {
        $u = $request->user();

        
        $payload = [
            'user'          => $u->only(['id','name','email','role','created_at']),
            'demandes'      => method_exists($u,'demandes')      ? $u->demandes()->get()      : [],
            'devis'         => method_exists($u,'devis')         ? $u->devis()->get()         : [],
            'interventions' => method_exists($u,'interventions') ? $u->interventions()->get() : [],
        ];

        return response()->json($payload, 200, [
            'Content-Disposition' => 'attachment; filename="mes-donnees.json"',
        ]);
    }

    public function destroy(Request $request)
    {
        $user = $request->user();

        // Anonymisation minimale (garde la cohérence référentielle)
        $host = parse_url(config('app.url'), PHP_URL_HOST) ?: 'example.local';
        $user->update([
            'name'  => 'Utilisateur supprimé',
            'email' => "deleted+{$user->id}@{$host}",
        ]);

        // Supprime le compte : soft delete si SoftDeletes est activé, sinon hard delete.
        $user->delete();

        // Déconnexion + hygiène de session
        Auth::logout();                          
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/')->with('status', 'Votre compte a été supprimé/anonymisé.');
    }
}
