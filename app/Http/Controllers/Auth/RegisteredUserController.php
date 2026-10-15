<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;           //  import règles mot de passe
use Illuminate\Support\Facades\Schema;             // pour tester l'existence des colonnes

class RegisteredUserController extends Controller
{
    //  Affiche le formulaire d'inscription. */
    public function create()
    {
        return view('auth.register');
    }

    //Gère l'inscription de l'utilisateur. 
    public function store(Request $request)
    {
        // Validation des champs (RGPD)
        $request->validate([
            'prenom'            => ['required','string','max:255'],
            'nom'               => ['required','string','max:255'],
            'email'             => ['required','string','email','max:255','unique:users,email'],
            'telephone'         => ['required','string','max:20'],
            'adresse_postale'   => ['required','string','max:255'],
            'code_postal'       => ['required','string','max:10'],
            'ville'             => ['required','string','max:100'],

            'password'          => [
                'required','confirmed',
                Password::min(12)     // longueur minimale 12
                    ->letters()       // au moins une lettre
                    ->mixedCase()     // minuscules + majuscules
                    ->numbers()       // au moins un chiffre
                    ->symbols()       // au moins un symbole
            ],

            'privacy'           => ['accepted'],          //  case “confidentialité” obligatoire
            'marketing_optin'   => ['nullable','boolean'] 
        ]);

        // Création de l'utilisateur
        $user = User::create([
            'prenom'          => $request->string('prenom')->trim(),
            'name'            => ucfirst($request->string('prenom')) . ' ' . strtoupper($request->string('nom')),
            'email'           => $request->string('email')->trim(),
            'telephone'       => $request->string('telephone')->trim(),
            'adresse_postale' => $request->string('adresse_postale')->trim(),
            'code_postal'     => $request->string('code_postal')->trim(),
            'ville'           => ucfirst($request->string('ville')->trim()),
            'password'        => Hash::make($request->password),
            'role'            => 'client',
        ]);

        // Trace des consentements 
        if (Schema::hasColumn('users', 'privacy_accepted_at')) {
            $user->privacy_accepted_at = now();
        }
        if (Schema::hasColumn('users', 'marketing_optin_at')) {
            $user->marketing_optin_at = $request->boolean('marketing_optin') ? now() : null;
        }
        if ($user->isDirty()) {
            $user->save();
        }

        // Déclenche l’envoi du lien de vérification
        event(new Registered($user));

        // Connecte l’utilisateur
        Auth::login($user);

        // Redirige vers la page "vérifiez votre email"
        return redirect()->route('verification.notice');
    }
}
