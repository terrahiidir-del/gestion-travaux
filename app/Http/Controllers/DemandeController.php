<?php

namespace App\Http\Controllers;

use App\Models\Demande;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class DemandeController extends Controller
{
    use AuthorizesRequests;//vérifier les permissions via les Policies.

    public function index()
    {
        // affiche les demandes du client connecté
        $demandes = Demande::where('user_id', Auth::id())
            ->with(['devis', 'intervention.technicien'])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('demandes.index', compact('demandes'));
    }

    public function show(Demande $demande)
{
    $user = Auth::user();

    // interdit si non connecté
    if (!$user) {
        abort(403);
    }

    
    $isAdmin = $user->role === 'admin';
    $isOwner = $demande->user_id === $user->id;//proprietaire de la demande 

    if (!($isAdmin || $isOwner)) {//Vérification des permissions
        abort(403);
    }

    return view('demandes.show', compact('demande'));
}

    /**
     * 🔍 Admin : Liste des demandes avec filtre par date
     */
    public function indexAdmin(Request $request)
    {
        $query = Demande::with([
            'user',
            'devis',
            'intervention.technicien', // on charge le technicien
        ]);

        if ($request->filled('date')) {
            $query->whereDate('created_at', $request->date);
        }

        $demandes     = $query->orderBy('created_at', 'desc')->get();
        $selectedDate = $request->input('date');


        return view('demandes.index_admin', compact('demandes', 'selectedDate'));
    }

    public function demandesValidees()//recupéré les demande deja valide dans la BDD
    {
        $demandes = Demande::where('statut', 'devis accepté')
            ->with(['user', 'devis'])
            ->get();

        return view('demandes.validees', compact('demandes'));
    }

    public function create()//crée une nouveau formulaire 
    {
        return view('demandes.create');
    }

    public function store(Request $request)//enregistrer une nouvelle demande
    {
        $validated = $request->validate([
            'type_travaux' => 'required|string|max:255',
            'description'  => 'required|string',
            'nom'          => 'required|string|max:255',
            'prenom'       => 'required|string|max:255',
            'email'        => 'required|email|max:255',
            'telephone'    => 'required|string|max:20',
            'adresse'      => 'required|string|max:255',
            'code_postal'  => 'required|string|max:10',
            'ville'        => 'required|string|max:255',
        ]);

        $validated['user_id'] = Auth::id();
        $validated['statut']  = 'en attente';

        Demande::create($validated);

        return redirect()->route('demandes.index')->with('success', 'Votre demande a bien été envoyée.');
    }

    public function edit(Demande $demande)
    {
        $this->authorize('update', $demande);//pour modifier formulaire //demande autorisation de policy
        return view('demandes.edit', compact('demande'));
    }

    public function update(Request $request, Demande $demande)
    {
        $this->authorize('update', $demande);

        $rules = [
            
            'description'  => 'required|string',
        ];

        if (Auth::user()->role === 'admin') {
            $rules['statut'] = 'required|in:en attente,validée,refusée,devis accepté,devis refusé';
        }

        $validated = $request->validate($rules);

        $demande->update($validated);
        //rediriger l'utilisateur selon son role 
        if (Auth::user()->role === 'admin') {
            $route = 'demandes.admin';
        } else {
            $route = 'demandes.index';
}
        return redirect()->route($route)->with('success', 'Demande mise à jour.');
    }

    public function destroy(Demande $demande)
    {
        if ($demande->user_id !== Auth::id()) {
            abort(403);
        }

        if (!$demande->devis) {
            $demande->delete();
            return redirect()->route('demandes.index')->with('success', ' Demande supprimée.');
        }

        $demande->update(['supprime_par_client' => true]);//la demande appartient à l'utilisateur connecté.
        return redirect()->route('demandes.index')->with('success', 'Impossible de supprimer la demande (car un devis existe déjà).');
    }
}
