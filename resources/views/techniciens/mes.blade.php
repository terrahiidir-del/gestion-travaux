@extends('layouts.app')

@section('title', 'Mes interventions')

@section('content')
<div class="container py-4">
    <h1 class="mb-4 fw-bold text-primary">📋 Mes interventions</h1>

    <div class="row">
        @forelse ($interventions as $intervention)
            @php
                $demande = $intervention->demande ?? null;
                $client  = optional($demande)->user;

                // Date/Heure
                try {
                    if (!empty($intervention->date) && !empty($intervention->heure)) {
                        $dtText = \Carbon\Carbon::parse($intervention->date.' '.$intervention->heure)->format('d/m/Y H:i');
                    } elseif (!empty($intervention->date)) {
                        $dtText = \Carbon\Carbon::parse($intervention->date)->format('d/m/Y H:i');
                    } else {
                        $dtText = '—';
                    }
                } catch (\Exception $e) {
                    $dtText = trim(($intervention->date ?? '').' '.($intervention->heure ?? ''));
                }

                // Téléphone / Adresse : priorité demande (où elles existent), fallback éventuel user
                $phone = $demande->telephone
                      ?? $client->phone
                      ?? null;

                $address = $demande->adresse
                         ?? $client->address
                         ?? null;

                $mapsUrl = $address ? 'https://www.google.com/maps/search/?api=1&query='.urlencode($address) : null;
            @endphp

            <div class="col-md-4 mb-4">
                <div class="card shadow-sm border-0 h-100">
                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title fw-bold text-dark">
                            🛠 Intervention #{{ $intervention->id }}
                        </h5>

                        <p class="card-text mb-2">
                            <strong>📊 État :</strong>
                            @if(!empty($intervention->rapport))
                                <span class="badge bg-success">✅ Terminée</span>
                            @else
                                <span class="badge bg-warning text-dark">⏳ En cours</span>
                            @endif
                        </p>

                        <p class="card-text mb-2">
                            <strong>📅 Date :</strong> {{ $dtText }}
                        </p>

                        <p class="card-text mb-2">
                            <strong>👤 Client :</strong>
                            {{ $client?->name ?? ($demande->nom_client ?? 'Client inconnu') }}
                        </p>

                        @if(!empty($phone))
                            <p class="card-text mb-2">
                                <strong>📞 Téléphone :</strong>
                                <a class="text-decoration-none" href="tel:{{ preg_replace('/\s+/', '', $phone) }}">
                                    {{ $phone }}
                                </a>
                            </p>
                        @endif

                        @if(!empty($address))
                            <p class="card-text mb-2">
                                <strong>📍 Adresse :</strong>
                                @if(!empty($mapsUrl))
                                    <a class="text-decoration-none" href="{{ $mapsUrl }}" target="_blank" rel="noopener">
                                        {{ $address }}
                                    </a>
                                @else
                                    {{ $address }}
                                @endif
                            </p>
                        @endif

                        <p class="card-text mb-3">
                            <strong>📄 Rapport :</strong>
                            @if(!empty($intervention->rapport))
                                <span class="badge bg-success">✅ Déjà fourni</span>
                            @else
                                <span class="badge bg-warning text-dark">❌ Non fourni</span>
                            @endif
                        </p>

                        <button
                            class="btn {{ !empty($intervention->rapport) ? 'btn-secondary' : 'btn-primary' }} w-100 mt-auto"
                            data-bs-toggle="modal"
                            data-bs-target="#rapportModal"
                            data-id="{{ $intervention->id }}"
                            data-rapport="{{ $intervention->rapport ?? '' }}">
                            {{ !empty($intervention->rapport) ? '✏ Modifier le rapport' : '✅ Clôturer l’intervention' }}
                        </button>
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

<div class="modal fade" id="rapportModal" tabindex="-1" aria-labelledby="rapportModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="rapportModalLabel">📝 Ajouter / Modifier le rapport</h5>
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
            <button type="submit" class="btn btn-success">💾 Enregistrer</button>
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
        const interventionId = button.getAttribute('data-id');
        const rapport = button.getAttribute('data-rapport');
        document.getElementById('rapport').value = rapport || '';
        document.getElementById('rapportForm').action = `/interventions/${interventionId}/cloturer`;
    });
});
</script>
@endsection
