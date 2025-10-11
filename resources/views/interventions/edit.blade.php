@extends('layouts.app')

@section('title', 'Modifier une intervention')

@section('content')
<div class="container py-5">
    <h1 class="mb-4 fw-bold text-warning">✏️ Modifier l’intervention #{{ $intervention->id }}</h1>

    <form action="{{ route('interventions.update', $intervention) }}" method="POST">
        @csrf
        @method('PUT')

        {{-- 🔗 Devis (non modifiable, juste affiché) --}}
        <div class="mb-3">
            <label class="form-label fw-semibold">Devis lié</label>
            <input type="text" class="form-control" value="Devis #{{ $intervention->demande->devis->id ?? 'N/A' }} – {{ $intervention->demande->user->name ?? 'Client inconnu' }}" disabled>
        </div>

        {{-- 👨‍🔧 Technicien --}}
        <div class="mb-3">
            <label class="form-label fw-semibold">Technicien</label>
            <select name="technicien_id" class="form-select" required id="technicien-select">
                <option value="">-- Sélectionner un technicien --</option>
                @foreach($techniciens as $tech)
                    <option value="{{ $tech->id }}" {{ $intervention->technicien_id == $tech->id ? 'selected' : '' }}>
                        {{ $tech->name }}
                    </option>
                @endforeach
            </select>
        </div>

        {{-- 📅 Disponibilités AJAX --}}
        <div id="disponibilites" class="mb-4">
            <strong>📆 Disponibilités du technicien sélectionné :</strong>
            <div id="liste-disponibilites" class="mt-2 text-muted">
                <p class="text-secondary">Sélectionnez un technicien pour voir ses disponibilités.</p>
            </div>
        </div>

        {{-- 📝 Description --}}
        <div class="mb-3">
            <label class="form-label fw-semibold">Instructions pour le technicien</label>
            <textarea name="description" class="form-control" rows="4">{{ $intervention->description }}</textarea>
        </div>

        {{-- 💾 Boutons --}}
        <div class="d-flex justify-content-between">
            <a href="{{ route('interventions.index') }}" class="btn btn-outline-secondary">⬅ Annuler</a>
            <button type="submit" class="btn btn-warning">💾 Modifier</button>
        </div>

        {{-- ✅ Champ caché pour stocker la date sélectionnée --}}
        <input type="hidden" name="date" id="date-hidden" value="{{ \Carbon\Carbon::parse($intervention->date)->format('Y-m-d\TH:i') }}">
    </form>
</div>
@endsection

@section('scripts')
<script>
document.getElementById('technicien-select').addEventListener('change', function () {
    const technicienId = this.value;
    const liste = document.getElementById('liste-disponibilites');

    if (!technicienId) {
        liste.innerHTML = '<p class="text-warning">Veuillez sélectionner un technicien.</p>';
        return;
    }

    liste.innerHTML = '<p>⏳ Chargement des disponibilités...</p>';

    fetch(`/techniciens/${technicienId}/disponibilites`)
        .then(response => {
            if (!response.ok) throw new Error('Erreur lors du chargement des disponibilités');
            return response.json();
        })
        .then(data => {
            const grouped = {};
            data.forEach(item => {
                if (!grouped[item.date]) {
                    grouped[item.date] = [];
                }
                grouped[item.date].push(item);
            });

            liste.innerHTML = '';

            for (const [date, heures] of Object.entries(grouped)) {
                const dateDiv = document.createElement('div');
                dateDiv.innerHTML = `<strong style="display:block; margin-top:10px; font-size:16px;">
                    ${new Date(date).toLocaleDateString('fr-FR', { weekday: 'long', day: '2-digit', month: 'long', year: 'numeric' })}
                </strong>`;
                liste.appendChild(dateDiv);

                const heuresDiv = document.createElement('div');
                heures.forEach(item => {
                    const btn = document.createElement('button');
                    btn.textContent = item.heure.slice(0, 5);

                    if (item.pris) {
                        btn.className = "btn btn-secondary btn-sm m-1";
                        btn.disabled = true;
                    } else {
                        btn.className = "btn btn-outline-primary btn-sm m-1";

                        btn.addEventListener('click', () => {
                            document.getElementById('date-hidden').value = `${date}T${item.heure.slice(0,5)}`;

                            document.querySelectorAll('#liste-disponibilites button').forEach(b => {
                                if (!b.disabled) {
                                    b.classList.remove('btn-success');
                                    b.classList.add('btn-outline-primary');
                                }
                            });
                            btn.classList.remove('btn-outline-primary');
                            btn.classList.add('btn-success');
                        });
                    }

                    heuresDiv.appendChild(btn);
                });

                liste.appendChild(heuresDiv);
            }
        })
        .catch(error => {
            console.error("❌ Erreur AJAX :", error);
            liste.innerHTML = '<p class="text-danger">❌ Erreur lors du chargement des disponibilités.</p>';
        });
});
</script>
@endsection
