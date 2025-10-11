@extends('layouts.app')

@section('title', 'Créer un devis')

@section('content')
<div class="container py-5">
    <div class="bg-white p-4 rounded shadow">
        <h1 class="mb-4">📄 Création d’un devis</h1>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('devis.store') }}" method="POST">
            @csrf

            <div class="mb-3">
                <label for="demande_id" class="form-label">Sélectionnez une demande</label>
                <select name="demande_id" id="demande_id" class="form-select" required>
                    <option value="">-- Choisir --</option>
                    @foreach($demandes as $demande)
                        <option value="{{ $demande->id }}" {{ (old('demande_id', $demandeId) == $demande->id) ? 'selected' : '' }}>
                            Demande #{{ $demande->id }} - {{ $demande->nom }} {{ $demande->prenom }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="mb-3">
                <label for="montant" class="form-label">Montant (€)</label>
                <input type="number" name="montant" class="form-control" step="0.01" min="0" required value="{{ old('montant') }}">
            </div>

            <div class="mb-3">
                <label for="description" class="form-label">Description du devis</label>
                <textarea name="description" class="form-control" rows="5" required>{{ old('description') }}</textarea>
            </div>

            <button type="submit" class="btn btn-primary">💾 Enregistrer le devis</button>
        </form>
    </div>
</div>
@endsection
