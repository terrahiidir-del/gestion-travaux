@extends('layouts.app')

@section('title', 'Créer une Demande')

@section('content')
@php
    $user = Auth::user();
@endphp

<div class="container py-5">
    <h1 class="mb-4 text-primary fw-bold">📝 Nouvelle Demande</h1>

    {{-- ✅ Barre de progression --}}
    <div class="progress mb-4" style="height: 25px;">
        <div id="progressBar" class="progress-bar progress-bar-striped bg-primary" role="progressbar" style="width: 33%">
            Étape 1/3
        </div>
    </div>

    <form action="{{ route('demandes.store') }}" method="POST" id="demandeForm">
        @csrf

        {{-- Étape 1 : Type de travaux --}}
        <div id="step1">
            <div class="mb-4">
                <label for="type_travaux" class="form-label fw-bold">Quel type de travaux souhaitez-vous ?</label>
                <select name="type_travaux" id="type_travaux" class="form-select" required>
                    <option value="" disabled selected>-- Sélectionnez un type --</option>
                    <option value="Installation Sowee">Installation Sowee</option>
                    <option value="Installation Cofirénov">Installation Cofirénov</option>
                    <option value="Installation Adage">Installation Adage</option>
                    <option value="Réparation imprimante">Réparation imprimante</option>
                    <option value="Changement de toner imprimante">Changement de toner imprimante</option>
                    <option value="Installation borne WiFi">Installation borne WiFi</option>
                    <option value="Installation fibre optique">Installation fibre optique</option>
                </select>
            </div>
            <button type="button" class="btn btn-primary" onclick="nextStep(1)">Suivant</button>
        </div>

        {{-- Étape 2 : Description --}}
        <div id="step2" style="display:none;">
            <div class="mb-4">
                <label for="description" class="form-label fw-bold">Décrivez votre demande</label>
                <textarea name="description" id="description" rows="4" class="form-control" required>{{ old('description') }}</textarea>
            </div>
            <button type="button" class="btn btn-secondary" onclick="prevStep(1)">Retour</button>
            <button type="button" class="btn btn-primary" onclick="nextStep(2)">Suivant</button>
        </div>

        {{-- Étape 3 : Coordonnées --}}
        <div id="step3" style="display:none;">
            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="nom" class="form-label fw-bold">Nom</label>
                    <input type="text" name="nom" id="nom" class="form-control" required 
                        value="{{ old('nom', $user->name ?? '') }}">
                </div>
                <div class="col-md-6">
                    <label for="prenom" class="form-label fw-bold">Prénom</label>
                    <input type="text" name="prenom" id="prenom" class="form-control" required
                        value="{{ old('prenom', $user->prenom ?? '') }}">
                </div>
            </div>

            <div class="mb-3">
                <label for="email" class="form-label fw-bold">Email</label>
                <input type="email" name="email" id="email" class="form-control" required 
                       value="{{ old('email', $user->email) }}">
            </div>

            <div class="mb-3">
                <label for="telephone" class="form-label fw-bold">Téléphone</label>
                <input type="text" name="telephone" id="telephone" class="form-control" required 
                       value="{{ old('telephone', $user->telephone ?? '') }}">
            </div>

            <div class="row mb-3">
                <div class="col-md-8">
                    <label for="adresse" class="form-label fw-bold">Adresse</label>
                    <input type="text" name="adresse" id="adresse" class="form-control" required 
                           value="{{ old('adresse', $user->adresse_postale ?? '') }}">
                </div>
                <div class="col-md-2">
                    <label for="code_postal" class="form-label fw-bold">Code Postal</label>
                    <input type="text" name="code_postal" id="code_postal" class="form-control" required 
                           value="{{ old('code_postal', $user->code_postal ?? '') }}">
                </div>
                <div class="col-md-2">
                    <label for="ville" class="form-label fw-bold">Ville</label>
                    <input type="text" name="ville" id="ville" class="form-control" required 
                           value="{{ old('ville', $user->ville ?? '') }}">
                </div>
            </div>

            <button type="button" class="btn btn-secondary" onclick="prevStep(2)">Retour</button>
            <button type="submit" class="btn btn-success">Envoyer la demande</button>
        </div>
    </form>
</div>

{{-- JavaScript de navigation entre les étapes + mise à jour de la barre --}}
<script>
    function updateProgressBar(step) {
        const bar = document.getElementById('progressBar');
        let pourcentage = 0;
        let label = "";

        if (step === 1) {
            pourcentage = 33;
            label = "Étape 1/3";
        } else if (step === 2) {
            pourcentage = 66;
            label = "Étape 2/3";
        } else if (step === 3) {
            pourcentage = 100;
            label = "Étape 3/3";
        }

        bar.style.width = pourcentage + "%";
        bar.textContent = label;
    }

    function nextStep(current) {
        document.getElementById('step' + current).style.display = 'none';
        document.getElementById('step' + (current + 1)).style.display = 'block';
        updateProgressBar(current + 1);
    }

    function prevStep(current) {
        document.getElementById('step' + current).style.display = 'none';
        document.getElementById('step' + (current - 1)).style.display = 'block';
        updateProgressBar(current - 1);
    }

    // Initialiser la barre au chargement
    document.addEventListener('DOMContentLoaded', function () {
        updateProgressBar(1);
    });
</script>
@endsection
