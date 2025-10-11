{{-- resources/views/auth/verify-email.blade.php --}}
@extends('layouts.app')

@section('title', 'Vérification de votre adresse e-mail')

@section('content')
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-lg border-0 rounded-4">
                <div class="card-header bg-primary text-white text-center rounded-top-4">
                    <h4><i class="fas fa-envelope-open-text"></i> Confirmation d'adresse e-mail</h4>
                </div>
                <div class="card-body text-center">
                    <img src="{{ asset('images/email-verification.svg') }}" 
                         alt="Vérification Email" 
                         class="mb-4" style="width: 150px;">

                    <p class="mb-4">
                        Merci de vous être inscrit(e) !<br>
                        Avant de commencer, veuillez confirmer votre adresse e-mail en cliquant sur le lien que nous venons de vous envoyer.
                    </p>

                    @if (session('status') == 'verification-link-sent')
                        <div class="alert alert-success">
                            ✅ Un nouveau lien de vérification a été envoyé à votre adresse e-mail.
                        </div>
                    @endif

                    <form method="POST" action="{{ route('verification.send') }}" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-success px-4">
                            <i class="fas fa-paper-plane"></i> Renvoyer l'e-mail de vérification
                        </button>
                    </form>

                    <form method="POST" action="{{ route('logout') }}" class="d-inline ms-2">
                        @csrf
                        <button type="submit" class="btn btn-outline-danger px-4">
                            <i class="fas fa-sign-out-alt"></i> Se déconnecter
                        </button>
                    </form>
                </div>
                <div class="card-footer text-muted text-center">
                    📧 Assurez-vous de vérifier aussi votre dossier de spams.
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
