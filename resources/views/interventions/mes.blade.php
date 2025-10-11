@extends('layouts.app')

@section('title', 'Mes interventions')

@section('content')
<div class="container py-4">
    <h1 class="mb-4 fw-bold text-primary">📋 Mes interventions</h1>

    <div class="row">
        @forelse ($interventions as $intervention)
            <div class="col-md-4 mb-4">
                <div class="card shadow-sm border-0">
                    <div class="card-body">
                        
                        {{-- 📌 Titre intervention --}}
                        <h5 class="card-title fw-bold text-dark">
                            🛠 Intervention #{{ $intervention->id }}
                        </h5>

                        {{-- 🗓 Date (COMBINE date + heure) --}}
                        @php
                            $dt = null;
                            if ($intervention->date) {
                                $dt = \Carbon\Carbon::parse($intervention->date);
                                if (!empty($intervention->heure)) {
                                    $dt->setTimeFromTimeString($intervention->heure);
                                }
                            }
                        @endphp
                        <p class="card-text">
                            <strong>📅 Date :</strong>
                            {{ $dt ? $dt->format('d/m/Y H:i') : '—' }}
                        </p>

                        {{-- 👤 Client --}}
                        <p class="card-text">
                            <strong>👤 Client :</strong> 
                            {{ $intervention->demande->user->name ?? 'Client inconnu' }}
                        </p>

                        {{-- 📞 Téléphone client --}}
                        <p class="card-text">
                            <strong>📞 Téléphone :</strong>
                            {{ $intervention->demande->telephone ?? '—' }}
                        </p>

                        {{-- 📍 Adresse client --}}
                        @php
                            $addrParts = array_filter([
                                $intervention->demande->adresse ?? null,
                                $intervention->demande->code_postal ?? null,
                                $intervention->demande->ville ?? null,
                            ]);
                            $adresseFull = implode(' ', $addrParts);
                        @endphp
                        <p class="card-text">
                            <strong>📍 Adresse :</strong>
                            {{ $adresseFull ?: '—' }}
                        </p>

                        {{-- 🛠 Type d’intervention (priorité à la colonne de l’intervention, sinon type_travaux de la demande) --}}
                        <p class="card-text">
                            <strong>🛠 Type :</strong>
                            {{ $intervention->type_intervention ?? ($intervention->demande->type_travaux ?? '—') }}
                        </p>

                        {{-- 📝 Instructions pour le technicien (saisies par l’admin lors de la planification) --}}
                        @php
                            $instructions = $intervention->description_technicien ?? $intervention->description;
                        @endphp
                        <p class="card-text">
                            <strong>📝 Instructions :</strong>
                            {!! $instructions ? nl2br(e($instructions)) : '<span class="text-muted">—</span>' !!}
                        </p>

                        {{-- 📊 État de l’intervention --}}
                        <p class="card-text">
                            <strong>📊 État :</strong>
                            @if($intervention->rapport)
                                <span class="badge bg-success">✅ Clôturée</span>
                            @else
                                <span class="badge bg-danger">⏳ En cours</span>
                            @endif
                        </p>

                        {{-- 📄 Rapport --}}
                        <p class="card-text">
                            <strong>📄 Rapport :</strong>
                            @if($intervention->rapport)
                                <span class="badge bg-success">✅ Déjà fourni</span>
                            @else
                                <span class="badge bg-warning text-dark">❌ Non fourni</span>
                            @endif
                        </p>

                        {{-- 🔘 Bouton pour ouvrir la modale --}}
                        @if(!$intervention->rapport)
                        <button 
                            class="btn btn-primary w-100"
                            data-bs-toggle="modal" 
                            data-bs-target="#rapportModal" 
                            data-id="{{ $intervention->id }}" 
                            data-rapport="{{ $intervention->rapport ?? '' }}">
                            ✏ Clôturer l’intervention
                        </button>
                        @else
                        <button class="btn btn-secondary w-100" disabled>
                            ✅ Intervention déjà clôturée
                        </button>
                        @endif

                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="alert alert-info text-center">
                    📭 Aucune intervention pour le moment.
                </div>
            </div>
        @endforelse
    </div>
</div>

{{-- 🔽 MODALE Bootstrap --}}
<div class="modal fade" id="rapportModal" tabindex="-1" aria-labelledby="rapportModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="rapportModalLabel">📝 Clôturer l’intervention</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <form id="rapportForm" method="POST" action="">
          @csrf
          @method('PUT')
          <div class="modal-body">
              <div class="mb-3">
                  <label for="rapport" class="form-label fw-semibold">Rapport d’intervention</label>
                  <textarea id="rapport" name="rapport" class="form-control" rows="4" required placeholder="Décrivez l’intervention..."></textarea>
              </div>
          </div>

          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">❌ Annuler</button>
            <button type="submit" class="btn btn-success">💾 Clôturer</button>
          </div>
      </form>
    </div>
  </div>
</div>

@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const rapportModal = document.getElementById('rapportModal');
    rapportModal.addEventListener('show.bs.modal', function (event) {
        const button = event.relatedTarget;

        // Récupère l'ID de l’intervention et le rapport existant
        const interventionId = button.getAttribute('data-id');
        const rapport = button.getAttribute('data-rapport') || '';

        // Insère le rapport dans le textarea
        document.getElementById('rapport').value = rapport;

        // Met à jour l’action du formulaire dynamiquement (route PUT /interventions/{id}/cloturer)
        document.getElementById('rapportForm').action = `/interventions/${interventionId}/cloturer`;
    });
});
</script>
@endsection
