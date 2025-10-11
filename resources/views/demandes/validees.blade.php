@extends('layouts.app')

@section('content')
<div class="container py-5">
    <h2 class="mb-4 text-primary">✅ Demandes validées (devis accepté)</h2>

    @if($demandes->isEmpty())
        <div class="alert alert-info">Aucune demande validée pour le moment.</div>
    @else
        @foreach($demandes as $demande)
            <div class="card mb-4 shadow-sm">
                <div class="card-body">
                    <h5 class="card-title text-success">📌 Client : {{ $demande->user->name }}</h5>
                    <ul class="list-group mb-3">
                        <li class="list-group-item"><strong>Email :</strong> {{ $demande->user->email }}</li>
                        <li class="list-group-item"><strong>Téléphone :</strong> {{ $demande->user->telephone ?? 'Non fourni' }}</li>
                        <li class="list-group-item"><strong>Adresse :</strong> {{ $demande->user->adresse_postale ?? 'Non fournie' }}</li>
                    </ul>

                    <h6 class="text-primary">🛠️ Détails de l’intervention</h6>
                    <ul class="list-group mb-3">
                        <li class="list-group-item"><strong>Type de travaux :</strong> {{ $demande->type_travaux }}</li>
                        <li class="list-group-item"><strong>Description :</strong> {{ $demande->description }}</li>
                        <li class="list-group-item"><strong>Statut :</strong> 
                            <span class="badge bg-success">{{ ucfirst($demande->statut) }}</span>
                        </li>
                    </ul>

                    {{-- ✅ Bouton planifier une intervention --}}
                    @if($demande->statut === 'devis accepté' && !$demande->intervention)
                        <a href="{{ route('interventions.create', ['demande_id' => $demande->id]) }}" class="btn btn-primary">
                            📅 Planifier une intervention
                        </a>
                    @endif
                </div>
            </div>
        @endforeach
    @endif

    <a href="{{ route('demandes.admin') }}" class="btn btn-secondary mt-4">⬅ Retour à toutes les demandes</a>
</div>
@endsection
