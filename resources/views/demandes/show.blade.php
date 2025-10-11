@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="mb-3">Détail de la demande #{{ $demande->id }}</h2>

    <p class="mb-2"><strong>Type de travaux :</strong> {{ $demande->type_travaux ?? '—' }}</p>
    <p class="mb-2"><strong>Description :</strong> {{ $demande->description ?? '—' }}</p>
    <p class="mb-2">
        <strong>Statut :</strong>
        <span class="badge
            @if($demande->statut === 'en attente') bg-warning text-dark
            @elseif(in_array($demande->statut, ['validée','devis accepté'])) bg-success
            @elseif(in_array($demande->statut, ['refusée','devis refusé'])) bg-danger
            @else bg-secondary @endif">
            {{ ucfirst($demande->statut) }}
        </span>
    </p>
    <p class="mb-2"><strong>Client :</strong> {{ $demande->user->name ?? '—' }}</p>
    <p class="mb-4"><strong>Créée le :</strong> {{ $demande->created_at->format('d/m/Y H:i') }}</p>

    {{-- Intervention (si chargée) --}}
    @if ($demande->intervention)
        @php
            $i        = $demande->intervention;
            $dateTxt  = $i->date  ? \Carbon\Carbon::parse($i->date)->format('d/m/Y') : null;
            $heureTxt = $i->heure ? substr($i->heure, 0, 5) : null;
        @endphp
        <p class="mb-2"><strong>📅 Intervention :</strong>
            @if($dateTxt) {{ $dateTxt }} @endif
            @if($heureTxt) à {{ $heureTxt }} @endif
            — 👨‍🔧 {{ optional($i->technicien)->name ?? 'Non assigné' }}
        </p>
    @else
        <p class="text-muted">Aucune intervention planifiée</p>
    @endif

    <a href="{{ route('demandes.admin') }}" class="btn btn-secondary mt-3">⬅ Retour</a>
</div>
@endsection
