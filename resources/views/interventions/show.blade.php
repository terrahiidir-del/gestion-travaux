@extends('layouts.app')

@section('title', 'Détails de l’intervention')

@section('content')
<div class="container py-5">
    <h1 class="mb-4 fw-bold text-primary">📄 Détails de l’intervention #{{ $intervention->id }}</h1>

    <div class="card shadow-sm p-4">
        <p><strong>📅 Date :</strong> {{ \Carbon\Carbon::parse($intervention->date)->format('d/m/Y H:i') }}</p>
        <p><strong>👨‍🔧 Technicien :</strong> {{ $intervention->technicien->name ?? 'Non assigné' }}</p>
        <p><strong>👤 Client :</strong> {{ $intervention->demande->user->name ?? 'Client inconnu' }}</p>
        <p><strong>📝 Description :</strong> {{ $intervention->description ?? 'Aucune description' }}</p>
        
        <hr>
        <h4 class="fw-bold">📋 Rapport du technicien</h4>
        @if($intervention->rapport)
            <p class="text-success">{{ $intervention->rapport }}</p>
        @else
            <p class="text-danger">❌ Aucun rapport fourni pour le moment.</p>
        @endif
    </div>

    <div class="mt-4">
        <a href="{{ route('interventions.index') }}" class="btn btn-secondary">⬅ Retour à la liste</a>
    </div>
</div>
@endsection
