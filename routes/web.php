<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth; 
use App\Http\Controllers\DemandeController;
use App\Http\Controllers\DevisController;
use App\Http\Controllers\InterventionController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\ProfileController; 



Route::get('/', function () {
    
    if (Auth::check() && in_array(Auth::user()->role, ['admin', 'technicien'])) {
        return redirect()->route('dashboard'); // destination par défaut
        
        if (Auth::user()->role === 'admin') return redirect()->route('demandes.admin');
        if (Auth::user()->role === 'technicien') return redirect()->route('interventions.mes');
    }
    return view('accueil.index');
})->name('home');

Route::get('/contact', [ContactController::class, 'index'])->name('contact');
Route::post('/contact', [ContactController::class, 'store'])->name('contact.store');




Route::get('/test-send-mail', function () {
    Mail::raw(' Ceci est un email de test envoyé depuis Laravel.', function ($message) {
        $message->to('idirtrahi840@gmail.com')->subject('📧 Test Laravel');
    });

    return ' Email de test envoyé avec succès !';
});

//route utilisateur connecté 
Route::middleware(['auth'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth', 'verified'])->group(function () {

    // Tableau de bord
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    //  Client : Gestion de ses demandes et devis
    Route::resource('demandes', DemandeController::class);//crée une demande

    // Actions devis côté client
    Route::get('/devis/telecharger/{devis}', [DevisController::class, 'telecharger'])
        ->name('devis.telecharger');
    Route::post('/devis/{devis}/accepter', [DevisController::class, 'accepter'])
        ->name('devis.accepter');
    Route::post('/devis/{devis}/refuser', [DevisController::class, 'refuser'])
        ->name('devis.refuser');

    //Admin
    Route::middleware('is_admin')->group(function () {
        Route::get('/admin/demandes', [DemandeController::class, 'indexAdmin'])
            ->name('demandes.admin');//Afficher toutes les demandes pour l’administrateur.
        Route::get('/admin/demandes-validees', [DemandeController::class, 'demandesValidees'])
            ->name('demandes.validées');//Afficher les demandes dont le devis a été accepté.
        Route::get('/admin/demandes/{demande}', [DemandeController::class, 'show'])
            ->name('admin.demandes.show');//Donc l’admin peut voir les détails d’une demande.

        Route::resource('devis', DevisController::class);
        Route::resource('interventions', InterventionController::class);

        // recuperé les disponibilité des techniciens
        Route::get('/techniciens/{id}/disponibilites', [InterventionController::class, 'disponibilites'])
            ->name('techniciens.disponibilites');
    });

    //Technicien
    Route::middleware('is_technicien')->group(function () {
        Route::get('/mes-interventions', [InterventionController::class, 'mesInterventions'])
            ->name('interventions.mes');//afficher les intervention du techniien

        // permet de clôturer une intervention
        Route::put('/interventions/{intervention}/cloturer', [InterventionController::class, 'cloturer'])
            ->name('interventions.cloturer');
    });
});

require __DIR__.'/auth.php';

// Pages légales (publiques)
Route::view('/privacy', 'legal.privacy')->name('privacy');
Route::view('/cookies', 'legal.cookies')->name('cookies');
Route::view('/mentions-legales', 'legal.mentions')->name('mentions');

//Consentement cookies 
use App\Http\Controllers\RgdController;
Route::post('/me/cookie-consent', [RgdController::class, 'saveCookieConsent'])
    ->name('me.cookie-consent');

//Droits RGPD
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/me/export',  [RgdController::class, 'export'])->name('me.export');
    Route::delete('/me/delete', [RgdController::class, 'destroy'])->name('me.delete');
});
