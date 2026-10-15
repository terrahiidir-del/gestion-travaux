<?php

namespace App\Http\Controllers;

use App\Models\Demande;
use App\Models\Devis;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;

class DevisController extends Controller
{
    //Afficher le formulaire de création de devis (admin).
     
    public function create(Request $request)
    {
        $demandeId = $request->get('demande_id');
        $demandes = Demande::where('statut', 'en attente')->get();

        return view('devis.create', compact('demandes', 'demandeId'));
    }

    // Enregistrer un nouveau devis (admin).
    
    public function store(Request $request)
    {
        $request->validate([
            'demande_id' => 'required|exists:demandes,id',//demande doit exister 
            'montant' => 'required|numeric|min:0',
            'description' => 'required|string',
        ]);

        $devis = Devis::create([
            'demande_id' => $request->demande_id,
            'montant' => $request->montant,
            'description' => $request->description,
        ]);

        $demande = Demande::findOrFail($request->demande_id);//récupère la demande correspondant à l’id
        $demande->update(['statut' => 'devis envoyé']);

        return redirect()->route('demandes.admin')->with('success', ' Devis envoyé au client.');
    }

    //  Télécharger un devis au format PDF.
     

    public function telecharger(Devis $devis)
{
    $demande = $devis->demande;

    if (!$demande) {
        return redirect()->back()->with('error', 'Aucune demande associée à ce devis.');
    }

    $pdf = Pdf::loadView('devis.pdf', [
        'demande' => $demande,
        'devis' => $devis
    ]);

    return $pdf->download('devis_demande_' . $demande->id . '.pdf');
}


    
    public function accepter(Devis $devis)
    {
        $demande = $devis->demande;

        if (!$demande || $demande->user_id !== Auth::id()) {
            abort(403);
        }

        $devis->update(['reponse_client' => 'accepté']);
        $demande->update(['statut' => 'devis accepté']);

        return redirect()->route('demandes.index')->with('success', ' Vous avez accepté le devis.');
    }

    public function refuser(Devis $devis)
    {
        $demande = $devis->demande;

        if (!$demande || $demande->user_id !== Auth::id()) {
            abort(403);
        }

        $devis->update(['reponse_client' => 'refusé']);
        $demande->update(['statut' => 'devis refusé']);

        return redirect()->route('demandes.index')->with('info', ' Vous avez refusé le devis.');
    }
     // Répondre au devis 
     
    /*public function repondre(Request $request, Devis $devis)
    {
        $demande = $devis->demande;

        if (!$demande || $demande->user_id !== Auth::id()) {
            abort(403);
        }

        $request->validate([
            'reponse' => 'required|in:accepte,refuse',
        ]);

        $devis->update([
            'reponse_client' => $request->reponse,//enregistrer la reponse en BDD
        ]);

        if ($request->reponse === 'accepte') {
            $demande->update(['statut' => 'devis accepté']);
            return redirect()->route('demandes.index')->with('success', '✅ Vous avez accepté le devis.');
        } else {
            $demande->update(['statut' => 'devis refusé']);
            return redirect()->route('demandes.index')->with('info', '❌ Vous avez refusé le devis.');
        }
    }*/
}
