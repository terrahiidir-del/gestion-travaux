<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;
use App\Mail\ContactMessage;

class ContactController extends Controller
{
    /**
     * Affiche le formulaire de contact.
     */
    public function index()
    {
        // ❌ Bloquer l'accès pour les admins
        if (Auth::check() && Auth::user()->is_admin) {
            abort(403, 'Les administrateurs ne peuvent pas accéder à cette page.');
        }

        return view('contact.index');
    }

    /**
     * Enregistre le message et envoie un email.
     */
    public function store(Request $request)
    {
        // Bloquer la soumission pour les admins
        if (Auth::check() && Auth::user()->is_admin) {
            abort(403, 'Action non autorisée pour un administrateur.');
        }

        //  Validation du formulaire
        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'message' => 'required|string',
        ]);

        // Enregistrer le message dans la base
        Contact::create($validated);

        // ENVOI DE L'EMAIL à ton adresse Gmail (configurée dans .env)
        Mail::to('anis.boubakricristal@gmail.com')->send(new ContactMessage($validated));

        //  Message de succès pour l'utilisateur
        return redirect()->back()->with('success', '✅ Votre message a bien été envoyé ! Nous vous répondrons bientôt.');
    }

    public function create() { /* pas utilisé */ }
    public function show(Contact $contact) { /* à implémenter si nécessaire */ }
    public function edit(Contact $contact) { /* à implémenter si nécessaire */ }
    public function update(Request $request, Contact $contact) { /* à implémenter si nécessaire */ }
    public function destroy(Contact $contact) { /* à implémenter si nécessaire */ }
}
