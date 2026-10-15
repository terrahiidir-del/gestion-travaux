<?php

namespace App\Http\Controllers;

use App\Models\Intervention;
use App\Models\Devis;
use App\Models\Demande;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Mail\InterventionPlanifiee;

class InterventionController extends Controller
{
    public function index() // récupérer toutes les interventions
    {
        $interventions = Intervention::with(['demande', 'technicien'])
            ->orderBy('date', 'asc')
            ->get();

        return view('interventions.index', compact('interventions'));
    }

    public function create() // afficher le formulaire de création d'une intervention
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
        'date'           => 'required|string',
        'description'    => 'nullable|string|max:1000',
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

    $exists = Intervention::where('technicien_id', $request->technicien_id)
        ->whereDate('date', $jour)
        ->whereTime('heure', $heure)
        ->exists();

    if ($exists) {
        return back()->withErrors([
            'date' => 'Ce technicien est déjà assigné à ce créneau.'
        ])->withInput();
    }

    $devis = Devis::with('demande.user')->findOrFail($request->devis_id);

    try {
        DB::beginTransaction();

        $intervention = Intervention::create([
            'demande_id'     => $devis->demande->id,
            'devis_id'       => $devis->id,
            'technicien_id'  => $request->technicien_id,
            'date'           => $jour,
            'heure'          => $heure,
            'description'    => $request->description,
        ]);

        $devis->demande->update([
            'statut' => 'intervention planifiée'
        ]);

        DB::commit();
    } catch (\Throwable $e) {
        DB::rollBack();
        report($e);

        return back()->withErrors([
            'general' => 'Erreur lors de la planification.'
        ])->withInput();
    }

    $emailClient = $devis->demande->email ?? optional($devis->demande->user)->email;

    if ($emailClient) {
        try {
            Mail::to($emailClient)->send(new InterventionPlanifiee($intervention));
        } catch (\Throwable $e) {
            report($e);

            return redirect()->route('interventions.index')
                ->with('warning', 'Intervention planifiée, mais l’email du client n’a pas pu être envoyé.');
        }
    }

    return redirect()->route('interventions.index')
        ->with('success', 'Intervention planifiée et client notifié.');
}

    public function mesInterventions()//technicien connecté de consulter ses interventions
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

        return redirect()->route('interventions.index')
            ->with('success', '🗑️ Intervention supprimée.');
    }

    public function disponibilites($id)
    {
        $technicien = User::find($id);

        if (!$technicien || strtolower($technicien->role) !== 'technicien') {//Si l’utilisateur n’existe pas ou si son rôle n’est pas technicien → retourne un JSON d’erreur 404.
            return response()->json(['error' => 'Technicien introuvable.'], 404);
        }

        $now       = Carbon::now();//date/heure actuelle.
        $jours     = 30;
        $startHour = 9;
        $lastStart = 16;

        $prisRows = Intervention::where('technicien_id', $id)//Récupérer les créneaux déjà pris
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

            if ($date->isSunday()) continue;

            for ($h = $startHour; $h <= $lastStart; $h++) {

                $dateStr = $date->toDateString();
                $timeStr = sprintf('%02d:00:00', $h);

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

    public function cloturer(Request $request, Intervention $intervention)//clôturer une intervention
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
            ->with('success', 'Intervention clôturée avec succès.');
    }

    public function show(Intervention $intervention)
    {
        return view('interventions.show', compact('intervention'));
    }

    public function edit(Intervention $intervention)
    {
        $techniciens = User::where('role', 'technicien')->get();

        return view('interventions.edit', compact('intervention', 'techniciens'));
    }

    public function update(Request $request, Intervention $intervention)// a reviser 
    {
        $request->validate([
            'description'   => 'nullable|string|max:1000',
            'technicien_id' => 'required|exists:users,id',
            'date'          => 'required|string',
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

        $conflict = Intervention::where('technicien_id', $request->technicien_id)
            ->whereDate('date', $jour)
            ->whereTime('heure', $heure)
            ->where('id', '!=', $intervention->id)
            ->exists();

        if ($conflict) {
            return back()->withErrors([
                'date' => ' Conflit avec une autre intervention.'
            ])->withInput();
        }

        $intervention->update([
            'description'   => $request->description,
            'technicien_id' => $request->technicien_id,
            'date'          => $jour,
            'heure'         => $heure,
        ]);

        return redirect()->route('interventions.index')
            ->with('success', ' Intervention mise à jour avec succès.');
    }
}