@extends('layouts.app')

@section('title', 'Interventions')

@section('content')
<div class="container py-5">
    <h1 class="mb-4 fw-bold">📆 Interventions</h1>

    {{-- Filtre par date --}}
    <form method="GET" action="{{ route('interventions.index') }}" class="row g-3 mb-4">
        <div class="col-auto">
            <label for="date" class="form-label fw-semibold">Filtrer par date</label>
            <input type="date" id="date" name="date" value="{{ request('date') }}" class="form-control">
        </div>
        <div class="col-auto align-self-end">
            <button class="btn btn-primary">Rechercher</button>
            <a href="{{ route('interventions.index') }}" class="btn btn-outline-secondary">Réinitialiser</a>
        </div>
    </form>

    <table class="table table-bordered table-striped align-middle">
        <thead class="table-dark">
            <tr>
                <th>#</th>
                <th>Demande</th>
                <th>Client</th>
                <th>Date</th>
                <th>Heure</th>
                <th>Technicien</th>
                <th>État</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
        @forelse ($interventions as $iv)
            @php
                $dateTxt  = $iv->date  ? \Carbon\Carbon::parse($iv->date)->format('d/m/Y') : '—';
                $heureTxt = $iv->heure ? substr($iv->heure, 0, 5) : '—';
            @endphp
            <tr>
                <td>#{{ $iv->id }}</td>
                <td>{{ $iv->demande->description ?? '—' }}</td>
                <td>{{ optional($iv->demande->user)->name ?? '—' }}</td>
                <td>{{ $dateTxt }}</td>
                <td>{{ $heureTxt }}</td>
                <td>{{ optional($iv->technicien)->name ?? '—' }}</td>
                <td>
                    @if($iv->rapport)
                        <span class="badge bg-success">Clôturée</span>
                    @else
                        <span class="badge bg-warning text-dark">Planifiée</span>
                    @endif
                </td>
                <td class="d-flex gap-2">
                    <a href="{{ route('interventions.show', $iv) }}" class="btn btn-info btn-sm">👁</a>
                    <a href="{{ route('interventions.edit', $iv) }}" class="btn btn-warning btn-sm">✏️</a>
                    <form action="{{ route('interventions.destroy', $iv) }}" method="POST"
                          onsubmit="return confirm('Supprimer cette intervention ?')">
                        @csrf @method('DELETE')
                        <button class="btn btn-danger btn-sm">🗑️</button>
                    </form>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="8" class="text-center text-muted">Aucune intervention.</td>
            </tr>
        @endforelse
        </tbody>
    </table>
</div>
@endsection
