@extends('layouts.app')

@section('content')
<div class="container py-5" style="max-width:480px;">
    <h2 class="mb-3 fw-bold">Vérification de l'email</h2>
    <p class="text-muted">Un code à 6 chiffres vous a été envoyé par email. Entrez-le ci-dessous.</p>

    @if (session('status'))
        <div class="alert alert-success">{{ session('status') }}</div>
    @endif

    <form method="POST" action="{{ route('verification.check') }}">
        @csrf
        <div class="mb-3">
            <label for="code" class="form-label">Code de vérification</label>
            <input id="code" name="code" type="text" maxlength="6"
                   class="form-control @error('code') is-invalid @enderror"
                   value="{{ old('code') }}" placeholder="123456" required>
            @error('code') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
        <button class="btn btn-primary w-100">Confirmer mon adresse</button>
    </form>

    <form method="POST" action="{{ route('verification.resend') }}" class="mt-3">
        @csrf
        <button class="btn btn-link p-0">Renvoyer le code</button>
    </form>
</div>
@endsection
