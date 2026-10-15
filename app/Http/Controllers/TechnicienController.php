<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Disponibilite;
use App\Models\Intervention;
use Illuminate\Http\JsonResponse;
use Carbon\Carbon;

class TechnicienController extends Controller
{
    //Page liste des techniciens
     
    public function index()
    {
        return view('techniciens.index');
    }

    //Renvoie les disponibilités 
    public function disponibilites(int $id): JsonResponse
    {
        $technicien = User::find($id);

        if (!$technicien || $technicien->role !== 'technicien') {
            return response()->json(['error' => 'Technicien introuvable.'], 404);
        }

        $now = Carbon::now();//Récupérer la date actuelle

        // Dispos futures uniquement (aujourd’hui: heures >= maintenant)
        $dispos = Disponibilite::where('user_id', $id)//Chercher les disponibilités du technicien
            ->where(function ($q) use ($now) {//Filtrer les disponibilités futures
                $q->whereDate('date', '>', $now->toDateString())
                  ->orWhere(function ($q2) use ($now) {
                      $q2->whereDate('date', $now->toDateString())
                         ->whereTime('heure', '>=', $now->format('H:i:s'));
                  });
            })
            ->orderBy('date')
            ->orderBy('heure')
            ->get(['date', 'heure']);

        if ($dispos->isEmpty()) {//verifie si ya des disponibilité du tech
            return response()->json([]);
        }

        // Créneaux déjà pris pour ce technicien
        $pris = Intervention::where('technicien_id', $id)
            ->whereIn('date', $dispos->pluck('date')->unique())
            ->get(['date', 'heure'])
            ->map(fn ($i) => $i->date . ' ' . $i->heure)
            ->toArray();

        // Construction du payload
        $payload = $dispos->map(function ($d) use ($pris) {
            $date  = Carbon::parse($d->date)->toDateString();
            $heure = Carbon::parse($d->heure)->format('H:i:s');
            $isPris = in_array("$date $heure", $pris, true);

            return [
                'date'  => $date,
                'heure' => $heure,
                'pris'  => $isPris,
            ];
        })->values();

        return response()->json($payload);
    }
}
