@extends('layouts.app')

@section('title', 'Demandes à traiter')

@section('content')
<div class="container py-4">

    <h1 class="fw-bold mb-4">📋 Demandes à traiter</h1>

    {{-- Filtres --}}
    <form method="GET" action="{{ route('demandes.admin') }}" class="row g-3 mb-4">
        <div class="col-md-4">
            <label for="date" class="form-label fw-semibold">📅 Filtrer par date de création</label>
            <input type="date" id="date" name="date" value="{{ request('date', $selectedDate) }}" class="form-control">
        </div>
        <div class="col-md-4 align-self-end">
            <button class="btn btn-primary">🔎 Rechercher</button>
            <a href="{{ route('demandes.admin') }}" class="btn btn-outline-secondary">Réinitialiser</a>
        </div>
    </form>

    {{-- Messages --}}
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    {{-- Liste --}}
    @forelse($demandes as $demande)
        <div class="card shadow-sm mb-3">
            <div class="card-body">
                <h5 class="card-title mb-2">Demande #{{ $demande->id }}</h5>

                {{-- 🛠️ Type de travaux du client --}}
                @if(!empty($demande->type_travaux))
                    <div class="mb-2">
                        <span class="badge bg-info text-dark">🛠️ {{ $demande->type_travaux }}</span>
                    </div>
                @else
                    <div class="mb-2 text-muted">🛠️ Type de travaux : —</div>
                @endif

                {{-- Description --}}
                <p class="mb-2">{{ $demande->description }}</p>

                <p class="mb-1"><strong>Client :</strong> {{ $demande->user->name ?? '—' }}</p>
                <p class="mb-1"><strong>Créée le :</strong> {{ $demande->created_at->format('d/m/Y H:i') }}</p>

                <p class="mb-1">
                    <strong>Statut :</strong>
                    <span class="badge
                        @if($demande->statut === 'en attente') bg-warning text-dark
                        @elseif(in_array($demande->statut, ['validée','devis accepté'])) bg-success
                        @elseif(in_array($demande->statut, ['refusée','devis refusé'])) bg-danger
                        @else bg-secondary @endif">
                        {{ ucfirst($demande->statut) }}
                    </span>
                </p>

                {{-- Intervention (NE PAS concaténer date + heure) --}}
                @if ($demande->intervention)
                    @php
                        $i        = $demande->intervention;
                        $dateTxt  = $i->date  ? \Carbon\Carbon::parse($i->date)->format('d/m/Y') : null;
                        $heureTxt = $i->heure ? substr($i->heure, 0, 5) : null;
                    @endphp
                    <p class="mb-1">
                        <strong>📅 Intervention :</strong>
                        @if($dateTxt) {{ $dateTxt }} @endif
                        @if($heureTxt) à {{ $heureTxt }} @endif
                        — 👨‍🔧 {{ optional($i->technicien)->name ?? 'Non assigné' }}
                    </p>
                @else
                    <p class="mb-1 text-muted">Aucune intervention planifiée</p>
                @endif

                <div class="mt-2 d-flex gap-2">
                    <a href="{{ route('admin.demandes.show', $demande->id) }}" class="btn btn-info btn-sm">👁 Voir</a>

                    @if (!$demande->devis)
                        <a href="{{ route('devis.create', ['demande_id' => $demande->id]) }}"
                           class="btn btn-success btn-sm">
                            Proposer un devis
                        </a>
                    @endif
                </div>
            </div>
        </div>
    @empty
        <div class="alert alert-info">Aucune demande trouvée pour cette date.</div>
    @endforelse
</div>
@endsection
