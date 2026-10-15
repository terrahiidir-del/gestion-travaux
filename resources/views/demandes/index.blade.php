@extends('layouts.app')

@section('title', 'Mes Demandes')

@section('content')
<div class="py-5" style="min-height: 100vh;">
    <div class="container bg-white bg-opacity-90 rounded shadow p-4">
        <h1 class="mb-4 fw-bold text-primary"> Mes Demandes</h1>

        {{-- Bouton ajout --}}
        <div class="mb-3 text-end">
            <a href="{{ route('demandes.create') }}" class="btn btn-success">+ Ajouter une demande</a>
        </div>

        {{-- Messages flash --}}
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @elseif(session('info'))
            <div class="alert alert-info">{{ session('info') }}</div>
        @elseif(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        {{-- Liste des demandes --}}
        @if($demandes->isEmpty())
            <div class="alert alert-info text-center">Aucune demande pour le moment.</div>
        @else
            <div class="table-responsive">
                <table class="table table-bordered table-hover bg-white rounded">
                    <thead class="table-dark">
                        <tr>
                            <th>Description</th>
                            <th>Date de la demande</th>
                            <th>Statut</th>
                            <th>Montant du devis</th>
                            <th>Intervention</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($demandes as $demande)
                            @if(!$demande->supprime_par_client || ($demande->supprime_par_client && $demande->devis))
                                <tr class="{{ $demande->supprime_par_client ? 'text-muted bg-light' : '' }}">
                                    <td>
                                        {{ $demande->description }}
                                        @if($demande->supprime_par_client)
                                            <span class="badge bg-secondary ms-2">Impossible de supprimer la demande</span>
                                        @endif
                                    </td>

                                    <td>{{ $demande->created_at->format('d/m/Y H:i') }}</td>

                                    <td>
                                        <span class="badge 
                                            @if($demande->statut === 'en attente') bg-warning text-dark
                                            @elseif($demande->statut === 'validée' || $demande->statut === 'devis accepté') bg-success
                                            @elseif($demande->statut === 'refusée' || $demande->statut === 'devis refusé') bg-danger
                                            @else bg-secondary @endif">
                                            {{ $demande->statut }}
                                        </span>
                                    </td>

                                    <td>
                                        @if($demande->devis)
                                            {{ number_format($demande->devis->montant, 2, ',', ' ') }} €
                                        @else
                                            <span class="text-muted">—</span>
                                        @endif
                                    </td>

                                    {{-- ✅ Intervention (date + heure sûres) --}}
                                    <td>
                                        @if($demande->intervention)
                                            @php
                                                $i  = $demande->intervention;
                                                $dt = null;
                                                if ($i->date) {
                                                    $base = \Carbon\Carbon::parse($i->date);
                                                    if (!empty($i->heure)) {
                                                        $base->setTimeFromTimeString($i->heure);
                                                    }
                                                    $dt = $base;
                                                }
                                            @endphp

                                            @if($dt)
                                                 <strong>{{ $dt->format('d/m/Y H:i') }}</strong><br>
                                            @endif
                                             <em>{{ optional($i->technicien)->name ?? 'Non assigné' }}</em>
                                        @else
                                            <span class="text-muted">Aucune intervention planifiée</span>
                                        @endif
                                    </td>

                                    <td class="text-center">
                                        @php
                                            $aUnDevis = (bool) $demande->devis;
                                            $rep = $aUnDevis ? $demande->devis->reponse_client : null;
                                            // ✅ En attente si null OU "en attente"
                                            $enAttente = in_array($rep, [null, 'en attente'], true);
                                        @endphp

                                        @if(!$demande->supprime_par_client)
                                            {{--  Modifier : apparaît seulement si la policy autorise (et donc avant devis) --}}
                                            @can('update', $demande)
                                                <a href="{{ route('demandes.edit', $demande) }}"
                                                   class="btn btn-sm btn-outline-warning me-2"
                                                   title="Modifier">✏️</a>
                                            @endcan

                                            {{-- 🗑️ Supprimer : totalement masqué si un devis existe --}}
                                            @unless($aUnDevis)
                                                <form action="{{ route('demandes.destroy', $demande) }}" method="POST" class="d-inline" onsubmit="return confirm('Confirmer la suppression ?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button class="btn btn-sm btn-outline-danger" title="Supprimer">🗑️</button>
                                                </form>
                                            @endunless
                                        @endif

                                        {{-- 📄 Devis --}}
                                        @if($aUnDevis)
                                            <a href="{{ route('devis.telecharger', $demande->devis->id) }}" class="btn btn-sm btn-outline-primary mt-1">
                                                📄 Télécharger le devis
                                            </a>

                                            {{-- ✅ Affiche les boutons si EN ATTENTE --}}
                                            @if($enAttente && !$demande->supprime_par_client)
                                                <form action="{{ route('devis.accepter', $demande->devis->id) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    <button type="submit" class="btn btn-sm btn-success me-1">✅ Accepter</button>
                                                </form>

                                                <form action="{{ route('devis.refuser', $demande->devis->id) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    <button type="submit" class="btn btn-sm btn-danger">❌ Refuser</button>
                                                </form>
                                            @else
                                                {{-- ✅ Badge réponse (vert/rouge/jaune) --}}
                                                <div class="mt-2">
                                                    <span class="badge
                                                        @if($rep === 'accepté') bg-success
                                                        @elseif($rep === 'refusé') bg-danger
                                                        @else bg-warning text-dark
                                                        @endif">
                                                        Réponse : {{ ucfirst($rep ?? 'en attente') }}
                                                    </span>
                                                </div>
                                            @endif
                                        @endif
                                    </td>
                                </tr>
                            @endif
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</div>
@endsection