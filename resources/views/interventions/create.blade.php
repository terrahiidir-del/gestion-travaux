@extends('layouts.app')

@section('title', 'Créer une intervention')

@section('content')
<div class="container py-5">
    <h1 class="mb-4 fw-bold text-primary">🛠️ Planifier une intervention</h1>

    {{-- 🔴 Erreurs --}}
    @if ($errors->any())
        <div class="alert alert-danger">
            <strong>Veuillez corriger les erreurs :</strong>
            <ul class="mb-0">
                @foreach ($errors->all() as $err)
                    <li>{{ $err }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('interventions.store') }}" method="POST">
        @csrf

        {{-- 🔗 Devis accepté --}}
        <div class="mb-3">
            <label class="form-label fw-semibold">Devis accepté</label>
            <select name="devis_id" class="form-select" required>
                @foreach($devis as $d)
                    <option value="{{ $d->id }}" @selected(old('devis_id')==$d->id)>
                        Devis #{{ $d->id }} – {{ $d->demande->user->name ?? 'Client inconnu' }}
                    </option>
                @endforeach
            </select>
        </div>

        {{-- 👨‍🔧 Technicien --}}
        <div class="mb-3">
            <label class="form-label fw-semibold">Technicien</label>
            <select name="technicien_id" class="form-select" required id="technicien-select">
                <option value="">-- Sélectionner un technicien --</option>
                @foreach($techniciens as $tech)
                    <option value="{{ $tech->id }}" @selected(old('technicien_id')==$tech->id)>{{ $tech->name }}</option>
                @endforeach
            </select>
        </div>

        {{-- 📅 Disponibilités --}}
        <div id="disponibilites" class="mb-4">
            <strong>📆 Disponibilités du technicien sélectionné :</strong>
            <div id="liste-disponibilites" class="mt-2 text-muted">
                <p class="text-secondary">Sélectionnez un technicien pour voir ses disponibilités.</p>
            </div>
        </div>

        {{-- 📝 Description --}}
        <div class="mb-3">
            <label class="form-label fw-semibold">Instructions pour le technicien</label>
            <textarea name="description" class="form-control" rows="4" placeholder="Détaillez les points importants…" required>{{ old('description') }}</textarea>
        </div>

        {{-- 💾 Actions --}}
        <div class="d-flex justify-content-between">
            <a href="{{ route('interventions.index') }}" class="btn btn-outline-secondary">⬅ Annuler</a>
            <button type="submit" class="btn btn-success" id="btn-planifier">💾 Planifier</button>
        </div>

        {{-- ✅ Datetime sélectionné (YYYY-MM-DD HH:MM:SS) --}}
        <input type="hidden" name="date" id="date-hidden" value="{{ old('date') }}">
    </form>
</div>
@endsection

@section('scripts')
<script>
(function () {
    const form        = document.querySelector('form');
    const selectTech  = document.getElementById('technicien-select');
    const liste       = document.getElementById('liste-disponibilites');
    const hiddenDate  = document.getElementById('date-hidden');
    let   selectedBtn = null;

    // Empêche la soumission si aucun créneau choisi
    form.addEventListener('submit', function (e) {
        if (!hiddenDate.value) {
            e.preventDefault();
            alert("Veuillez choisir un créneau dans les disponibilités avant de planifier.");
            return;
        }
        // feedback visuel (optionnel)
        if (selectedBtn) {
            selectedBtn.disabled = true;
            selectedBtn.classList.remove('btn-success','btn-outline-primary');
            selectedBtn.classList.add('btn-secondary');
            selectedBtn.title = "Créneau réservé (enregistrement en cours)";
        }
    });

    async function chargerDispos() {
        const technicienId = selectTech.value;
        hiddenDate.value = '';
        selectedBtn = null;
        liste.innerHTML = '';

        if (!technicienId) {
            liste.innerHTML = '<p class="text-warning">Veuillez sélectionner un technicien.</p>';
            return;
        }

        liste.innerHTML = '<p>⏳ Chargement des disponibilités...</p>';

        // ❗️Utilise un chemin direct (évite tout souci de nom de route)
        const url = `/techniciens/${encodeURIComponent(technicienId)}/disponibilites`;

        try {
            const res = await fetch(url, {
                method: 'GET',
                headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
                credentials: 'same-origin'
            });

            const contentType = res.headers.get('content-type') || '';
            if (!res.ok) {
                const body = contentType.includes('application/json') ? JSON.stringify(await res.json()) : await res.text();
                console.error('Erreur HTTP', res.status, body);
                liste.innerHTML = `<p class="text-danger">❌ Erreur ${res.status} lors du chargement des disponibilités.</p>`;
                return;
            }

            const data = contentType.includes('application/json') ? await res.json() : [];
            if (!Array.isArray(data) || data.length === 0) {
                liste.innerHTML = '<p class="text-muted">Aucune disponibilité à venir pour ce technicien.</p>';
                return;
            }

            // Groupage par date
            const grouped = {};
            for (const item of data) {
                (grouped[item.date] ||= []).push(item);
            }

            // Affichage
            liste.innerHTML = '';
            Object.entries(grouped).forEach(([date, heures]) => {
                const dateDiv = document.createElement('div');
                const d = new Date(date + 'T00:00:00');
                dateDiv.innerHTML = `<strong style="display:block; margin-top:10px; font-size:16px;">
                    ${d.toLocaleDateString('fr-FR', { weekday: 'long', day: '2-digit', month: 'long', year: 'numeric' })}
                </strong>`;
                liste.appendChild(dateDiv);

                const heuresDiv = document.createElement('div');

                heures.forEach(item => {
                    const btn = document.createElement('button');
                    btn.type = 'button';
                    const hhmm = item.heure.substring(0,5);
                    btn.textContent = hhmm;

                    if (item.pris) {
                        btn.className = "btn btn-secondary btn-sm m-1";
                        btn.disabled = true;
                        btn.title = "Créneau déjà réservé";
                    } else {
                        btn.className = "btn btn-outline-primary btn-sm m-1";
                        btn.title = "Choisir ce créneau";
                        btn.addEventListener('click', () => {
                            hiddenDate.value = `${item.date} ${item.heure}`; // "YYYY-MM-DD HH:MM:SS"

                            document.querySelectorAll('#liste-disponibilites button').forEach(b => {
                                if (!b.disabled) {
                                    b.classList.remove('btn-success');
                                    b.classList.add('btn-outline-primary');
                                }
                            });

                            btn.classList.remove('btn-outline-primary');
                            btn.classList.add('btn-success');
                            selectedBtn = btn;
                        });
                    }

                    heuresDiv.appendChild(btn);
                });

                liste.appendChild(heuresDiv);
            });

        } catch (err) {
            console.error('AJAX error:', err);
            liste.innerHTML = '<p class="text-danger">❌ Erreur lors du chargement des disponibilités.</p>';
        }
    }

    // Événements
    selectTech.addEventListener('change', chargerDispos);

    // Pré-charger si un technicien est déjà sélectionné (retour de validation)
    if (selectTech.value) {
        chargerDispos();
    }
})();
</script>
@endsection
