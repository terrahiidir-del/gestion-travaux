<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Afficher le formulaire de profil.
     */
    public function edit(Request $request): View
    {
        // La vue se trouve bien dans resources/views/profile/edit.blade.php
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Mettre à jour les informations du profil utilisateur.
     */
    public function update(Request $request): RedirectResponse
    {
        $user = $request->user();

        $validated = $request->validate([
            'name'            => ['required','string','max:255'],
            'prenom'          => ['nullable','string','max:255'],
            'email'           => ['required','email','max:255','unique:users,email,'.$user->id],
            'telephone'       => ['nullable','string','max:30'],
            'adresse_postale' => ['nullable','string','max:255'],
            'code_postal'     => ['nullable','string','max:20'],
            'ville'           => ['nullable','string','max:100'],
            'password'        => ['nullable','confirmed','min:8'],
        ]);

        if (array_key_exists('email', $validated) && $validated['email'] !== $user->email) {
            $user->email_verified_at = null;
        }

        $user->fill([
            'name'            => $validated['name']            ?? $user->name,
            'prenom'          => $validated['prenom']          ?? $user->prenom,
            'email'           => $validated['email']           ?? $user->email,
            'telephone'       => $validated['telephone']       ?? $user->telephone,
            'adresse_postale' => $validated['adresse_postale'] ?? $user->adresse_postale,
            'code_postal'     => $validated['code_postal']     ?? $user->code_postal,
            'ville'           => $validated['ville']           ?? $user->ville,
        ]);

        if (!empty($validated['password'])) {
            $user->password = Hash::make($validated['password']);
        }

        $user->save();

        return Redirect::route('profil.edit')->with('status', 'profile-updated');
    }

    /**
     * Supprimer le compte utilisateur.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();
        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
