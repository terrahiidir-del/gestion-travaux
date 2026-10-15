<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\MobileAuthController;
use App\Http\Controllers\Api\MobileInterventionController;

Route::post('/login', [MobileAuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {

    Route::get('/mes-interventions', [
        MobileInterventionController::class,
        'index'
    ]);

    Route::get('/interventions/{intervention}', [
        MobileInterventionController::class,
        'show'
    ]);

    Route::put('/interventions/{intervention}/cloturer', [
        MobileInterventionController::class,
        'cloturer'
    ]);

    Route::post('/logout', [
        MobileAuthController::class,
        'logout'
    ]);
});