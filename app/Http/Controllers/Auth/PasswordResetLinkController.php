<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Log;

class PasswordResetLinkController extends Controller
{
    /**
     * Affiche le formulaire de demande de lien de réinitialisation.
     */
    public function create()
    {
        return view('auth.forgot-password');
    }

    /**
     * Envoie le lien de réinitialisation de mot de passe.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'email' => ['required', 'email'],
        ]);

        // 🔍 AJOUT DEBUG – On log l’email entré
        Log::info('📨 Tentative de reset password pour : ' . $request->email);

        // Envoi du lien
        $status = Password::sendResetLink(
            $request->only('email')
        );

        // 🔍 AJOUT DEBUG – On log la réponse
        Log::info('📬 Status retour reset password : ' . $status);

        return $status == Password::RESET_LINK_SENT
            ? back()->with('status', __($status))
            : back()->withInput($request->only('email'))
                ->withErrors(['email' => __($status)]);
    }
}
