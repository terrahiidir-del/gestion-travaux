<?php

namespace App\Http\Controllers;

use App\Models\Intervention;
use App\Models\Devis;
use App\Models\Demande;
use App\Models\User;
// use App\Models\Disponibilite; // ❌ non utilisé avec l’Option B
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Mail\InterventionPlanifiee;

class InterventionController extends Controller
{
    public function index()
    {
        $query = Intervention::with(['demande', 'technicien']);

        if (request()->has('date') && request('date')) {
            $query->whereDate('date', request('date'));
        }

        $interventions = $query->orderBy('date', 'asc')->get();

        return view('interventions.index', compact('interventions'));
    }

    public function create()
    {
        $devis = Devis::with('demande.user')
            ->whereHas('demande', fn($query) =>
                $query->where('statut', 'devis accepté')
            )
            ->get();

        $techniciens = User::where('role', 'technicien')->get();

        return view('interventions.create', compact('devis', 'techniciens'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'devis_id'       => 'required|exists:devis,id',
            'technicien_id'  => 'required|exists:users,id',
            'date'           => 'required|string', // ⬅ parsing strict juste après
            'description'    => 'nullable|string|max:1000',
        ]);

        try {
            // attendu exactement: "YYYY-MM-DD HH:MM:SS"
            $dt = Carbon::createFromFormat('Y-m-d H:i:s', $request->date);
        } catch (\Throwable $e) {
            return back()->withErrors([
                'date' => 'Format de date invalide. Sélectionnez un créneau dans la liste.'
            ])->withInput();
        }

        $jour  = $dt->toDateString();   // YYYY-MM-DD
        $heure = $dt->format('H:i:s');  // HH:MM:SS

        // ❌ Conflit exact (même technicien, même date+heure)
        $exists = Intervention::where('technicien_id', $request->technicien_id)
            ->whereDate('date', $jour)
            ->whereTime('heure', $heure)
            ->exists();

        if ($exists) {
            return back()->withErrors([
                'date' => '❌ Ce technicien est déjà assigné à ce créneau.'
            ])->withInput();
        }

        $devis = Devis::with('demande.user')->findOrFail($request->devis_id);

        DB::beginTransaction();
        try {
            $intervention = Intervention::create([
                'demande_id'     => $devis->demande->id,
                'devis_id'       => $devis->id,
                'technicien_id'  => $request->technicien_id,
                'date'           => $jour,
                'heure'          => $heure,
                'description'    => $request->description,
            ]);

            $devis->demande->update(['statut' => 'intervention planifiée']);

            // ⚠️ Option B : aucune suppression de disponibilité (pas de table)
            // Disponibilite::where(...)->delete();

            DB::commit();

            // Email client (demande.email ou user.email)
            $emailClient = $devis->demande->email ?? optional($devis->demande->user)->email;
            if ($emailClient) {
                Mail::to($emailClient)->send(new InterventionPlanifiee($intervention));
            }

            return redirect()->route('interventions.index')->with('success', '✅ Intervention planifiée et client notifié.');
        } catch (\Throwable $e) {
            DB::rollBack();
            report($e);
            return back()->withErrors([
                'general' => 'Erreur lors de la planification.'
            ])->withInput();
        }
    }

    public function mesInterventions()
    {
        $interventions = Intervention::with([
                'demande',
                'demande.user:id,name,email',
                'technicien:id,name',
            ])
            ->where('technicien_id', Auth::id())
            ->orderBy('date', 'asc')
            ->get();

        return view('interventions.mes', compact('interventions'));
    }

    public function destroy(Intervention $intervention)
    {
        $intervention->delete();
        return redirect()->route('interventions.index')->with('success', '🗑️ Intervention supprimée.');
    }

    /**
     * OPTION B : Génère les disponibilités à la volée (aucun stockage).
     * - Jours : lundi → samedi
     * - Créneaux : 09:00 à 16:00 (départ), fin à 17:00
     * - Fenêtre : 30 jours à venir
     * - "pris" = existe déjà une intervention sur le créneau
     * Retour : [{ date:'YYYY-MM-DD', heure:'HH:MM:SS', pris: bool }, ...]
     */
    public function disponibilites($id)
    {
        $technicien = User::find($id);
        if (!$technicien || strtolower($technicien->role) !== 'technicien') {
            return response()->json(['error' => 'Technicien introuvable.'], 404);
        }

        $now       = Carbon::now();
        $jours     = 30; // nombre de jours à générer
        $startHour = 9;  // 09:00
        $lastStart = 16; // dernier départ 16:00 (créneau d'1h → fin 17:00)

        // Interventions à venir → set de clés "date|heure"
        $prisRows = Intervention::where('technicien_id', $id)
            ->whereDate('date', '>=', $now->toDateString())
            ->selectRaw("DATE_FORMAT(date, '%Y-%m-%d') as d, TIME_FORMAT(heure, '%H:%i:%s') as t")
            ->get();

        $prisSet = [];
        foreach ($prisRows as $r) {
            $prisSet[$r->d.'|'.$r->t] = true;
        }

        $payload = [];

        for ($d = 0; $d < $jours; $d++) {
            $date = $now->copy()->addDays($d);

            // saute le dimanche
            if ($date->isSunday()) continue;

            for ($h = $startHour; $h <= $lastStart; $h++) {
                $dateStr = $date->toDateString();
                $timeStr = sprintf('%02d:00:00', $h);

                // pas de créneau dans le passé pour "aujourd'hui"
                if ($date->isSameDay($now) && $timeStr < $now->format('H:i:s')) {
                    continue;
                }

                $isPris = isset($prisSet["$dateStr|$timeStr"]);

                $payload[] = [
                    'date'  => $dateStr,
                    'heure' => $timeStr,
                    'pris'  => $isPris,
                ];
            }
        }

        return response()->json($payload);
    }

    public function cloturer(Request $request, Intervention $intervention)
    {
        $request->validate([
            'rapport' => 'required|string|max:2000'
        ]);

        $intervention->update([
            'rapport' => $request->rapport
        ]);

        if ($intervention->demande) {
            $intervention->demande->update(['statut' => 'terminé']);
        }

        return redirect()
            ->route('interventions.mes')
            ->with('success', '✅ Intervention clôturée avec succès.');
    }

    public function show(Intervention $intervention)
    {
        return view('interventions.show', compact('intervention'));
    }

    /** ✅ FORMULAIRE MODIFIER */
    public function edit(Intervention $intervention)
    {
        $techniciens = User::where('role', 'technicien')->get();
        return view('interventions.edit', compact('intervention', 'techniciens'));
    }

    /** ✅ MISE À JOUR DE L’INTERVENTION */
    public function update(Request $request, Intervention $intervention)
    {
        $request->validate([
            'description'   => 'nullable|string|max:1000',
            'technicien_id' => 'required|exists:users,id',
            'date'          => 'required|string', // ⬅ parsing strict juste après
        ]);

        try {
            $dt = Carbon::createFromFormat('Y-m-d H:i:s', $request->date);
        } catch (\Throwable $e) {
            return back()->withErrors([
                'date' => 'Format de date invalide. Sélectionnez un créneau dans la liste.'
            ])->withInput();
        }

        $jour  = $dt->toDateString();
        $heure = $dt->format('H:i:s');

        // ❌ Conflit avec une autre intervention
        $conflict = Intervention::where('technicien_id', $request->technicien_id)
            ->whereDate('date', $jour)
            ->whereTime('heure', $heure)
            ->where('id', '!=', $intervention->id)
            ->exists();

        if ($conflict) {
            return back()->withErrors(['date' => '❌ Conflit avec une autre intervention.'])->withInput();
        }

        $intervention->update([
            'description'   => $request->description,
            'technicien_id' => $request->technicien_id,
            'date'          => $jour,
            'heure'         => $heure,
        ]);

        // ⚠️ Option B : aucune suppression de disponibilité (pas de table)
        // Disponibilite::where(...)->delete();

        return redirect()->route('interventions.index')->with('success', '✅ Intervention mise à jour avec succès.');
    }
}
