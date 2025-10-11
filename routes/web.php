<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth; // 👈 déjà présent
use App\Http\Controllers\DemandeController;
use App\Http\Controllers\DevisController;
use App\Http\Controllers\InterventionController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\ProfileController; // 👈 AJOUT : contrôleur du profil

/*
|--------------------------------------------------------------------------
| 🏠 Routes Publiques
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    // ⛔️ Interdire l'accueil aux rôles admin/technicien
    if (Auth::check() && in_array(Auth::user()->role, ['admin', 'technicien'])) {
        return redirect()->route('dashboard'); // destination par défaut
        // 👉 Option si tu préfères rediriger par rôle :
        // if (Auth::user()->role === 'admin') return redirect()->route('demandes.admin');
        // if (Auth::user()->role === 'technicien') return redirect()->route('interventions.mes');
    }
    return view('accueil.index');
})->name('home');

Route::get('/contact', [ContactController::class, 'index'])->name('contact');
Route::post('/contact', [ContactController::class, 'store'])->name('contact.store');

/*
|--------------------------------------------------------------------------
| ✅ TEST CONFIGURATION MAIL (dev seulement)
|--------------------------------------------------------------------------
*/
Route::get('/test-mail-config', function () {
    return [
        'MAIL_MAILER'       => config('mail.default'),
        'MAIL_HOST'         => config('mail.mailers.smtp.host'),
        'MAIL_USERNAME'     => config('mail.mailers.smtp.username'),
        'MAIL_FROM_ADDRESS' => config('mail.from.address'),
        'MAIL_FROM_NAME'    => config('mail.from.name'),
    ];
});

/*
|--------------------------------------------------------------------------
| ✅ TEST ENVOI EMAIL (dev seulement)
|--------------------------------------------------------------------------
*/
Route::get('/test-send-mail', function () {
    Mail::raw('✅ Ceci est un email de test envoyé depuis Laravel.', function ($message) {
        $message->to('idirtrahi840@gmail.com')->subject('📧 Test Laravel');
    });

    return '📨 Email de test envoyé avec succès !';
});

/*
|--------------------------------------------------------------------------
| 👤 Routes Profil (utilisateur connecté)
| - On laisse l'accès au profil avec seulement 'auth' (pas besoin d’être "verified")
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function () {
    // Profil (édition / mise à jour)
    Route::get('/profil',  [ProfileController::class, 'edit'])->name('profil.edit');
    Route::post('/profil', [ProfileController::class, 'update'])->name('profil.update');

    // ✅ Suppression de compte pour le partial Breeze
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});
/*
|--------------------------------------------------------------------------
| 🔐 Routes Authentifiées & Vérifiées
| -> Un utilisateur connecté mais NON vérifié est redirigé vers /email/verify
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'verified'])->group(function () {

    // Tableau de bord
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    /*
    |----------------------------------------------------------------------
    | 👤 Client : Gestion de ses demandes et devis
    |----------------------------------------------------------------------
    */
    Route::resource('demandes', DemandeController::class);

    // Actions devis côté client
    Route::get('/devis/telecharger/{devis}', [DevisController::class, 'telecharger'])
        ->name('devis.telecharger');
    Route::post('/devis/{devis}/accepter', [DevisController::class, 'accepter'])
        ->name('devis.accepter');
    Route::post('/devis/{devis}/refuser', [DevisController::class, 'refuser'])
        ->name('devis.refuser');

    /*
    |----------------------------------------------------------------------
    | 🛡️ Admin
    |----------------------------------------------------------------------
    */
    Route::middleware('is_admin')->group(function () {
        Route::get('/admin/demandes', [DemandeController::class, 'indexAdmin'])
            ->name('demandes.admin');
        Route::get('/admin/demandes-validees', [DemandeController::class, 'demandesValidees'])
            ->name('demandes.validées');
        Route::get('/admin/demandes/{demande}', [DemandeController::class, 'show'])
            ->name('admin.demandes.show');

        Route::resource('devis', DevisController::class);
        Route::resource('interventions', InterventionController::class);

        // ⚠️ correspond à ton JS: /techniciens/{id}/disponibilites
        Route::get('/techniciens/{id}/disponibilites', [InterventionController::class, 'disponibilites'])
            ->name('techniciens.disponibilites');
    });

    /*
    |----------------------------------------------------------------------
    | 🔧 Technicien
    |----------------------------------------------------------------------
    */
    Route::middleware('is_technicien')->group(function () {
        Route::get('/mes-interventions', [InterventionController::class, 'mesInterventions'])
            ->name('interventions.mes');

        // On modifie une intervention existante => PUT
        Route::put('/interventions/{intervention}/cloturer', [InterventionController::class, 'cloturer'])
            ->name('interventions.cloturer');
    });
});

require __DIR__.'/auth.php';

/*
|--------------------------------------------------------------------------
| 📄 Pages légales (publiques)
|--------------------------------------------------------------------------
*/
Route::view('/privacy', 'legal.privacy')->name('privacy');
Route::view('/cookies', 'legal.cookies')->name('cookies');
Route::view('/mentions-legales', 'legal.mentions')->name('mentions');

/*
|--------------------------------------------------------------------------
| 🍪 Consentement cookies (public → stocke un cookie + enregistre en BDD)
|--------------------------------------------------------------------------
*/
use App\Http\Controllers\RgdController;
Route::post('/me/cookie-consent', [RgdController::class, 'saveCookieConsent'])
    ->name('me.cookie-consent');

/*
|--------------------------------------------------------------------------
| 🔐 Droits RGPD (utilisateur connecté)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/me/export',  [RgdController::class, 'export'])->name('me.export');
    Route::delete('/me/delete', [RgdController::class, 'destroy'])->name('me.delete');
});
