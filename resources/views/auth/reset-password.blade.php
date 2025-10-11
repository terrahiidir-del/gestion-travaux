@extends('layouts.app')

@section('content')
<style>
    body {
        background: #000;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

    .reset-container {
        display: flex;
        justify-content: center;
        align-items: center;
        min-height: 100vh;
        padding: 2rem;
    }

    .reset-box {
        display: flex;
        background-color: #fff;
        border-radius: 16px;
        box-shadow: 0 0 25px rgba(255, 255, 0, 0.2);
        overflow: hidden;
        max-width: 800px;
        width: 100%;
    }

    .reset-left {
        background-color: #ffc107;
        padding: 40px;
        flex: 1;
        display: flex;
        flex-direction: column;
        justify-content: center;
        color: #000;
    }

    .reset-left h2 {
        font-size: 2.5rem;
        font-weight: 800;
        margin-bottom: 20px;
    }

    .reset-left p {
        font-size: 1.1rem;
        line-height: 1.6;
    }

    .reset-right {
        flex: 1;
        padding: 40px;
        background-color: #fff;
    }

    .reset-title {
        font-size: 1.8rem;
        font-weight: bold;
        margin-bottom: 25px;
        color: #000;
    }

    .form-group {
        position: relative;
        margin-bottom: 1.5rem;
    }

    .form-group i {
        position: absolute;
        top: 50%;
        left: 12px;
        transform: translateY(-50%);
        color: #888;
    }

    .form-control {
        width: 100%;
        padding: 12px 12px 12px 40px;
        border: 1px solid #ccc;
        border-radius: 10px;
        font-size: 1rem;
    }

    .btn-yellow {
        background-color: #ffc107;
        color: #000;
        border: none;
        padding: 12px;
        width: 100%;
        font-weight: bold;
        border-radius: 10px;
        transition: background-color 0.3s;
    }

    .btn-yellow:hover {
        background-color: #e0a800;
    }

    .form-footer {
        margin-top: 20px;
        text-align: center;
    }

    .form-footer a {
        color: #000;
        text-decoration: underline;
        font-weight: bold;
    }

    @media (max-width: 768px) {
        .reset-box {
            flex-direction: column;
        }
        .reset-left,
        .reset-right {
            padding: 30px;
        }
    }
</style>

<div class="reset-container">
    <div class="reset-box">

        <!-- Partie gauche -->
        <div class="reset-left">
            <h2>🔐 Nouveau mot de passe</h2>
            <p>Entrez votre nouvel identifiant de mot de passe pour finaliser la réinitialisation de votre compte.</p>
        </div>

        <!-- Partie droite -->
        <div class="reset-right">
            <h3 class="reset-title">Réinitialiser le mot de passe</h3>

            <!-- ✅ Formulaire -->
            <form method="POST" action="{{ route('password.store') }}">
                @csrf

                <!-- Token envoyé par email -->
                <input type="hidden" name="token" value="{{ $request->route('token') }}">

                <div class="form-group">
                    <i class="bi bi-envelope-fill"></i>
                    <input type="email" name="email" class="form-control" 
                           value="{{ old('email', $request->email) }}" 
                           placeholder="Adresse e-mail" required autofocus>
                </div>

                <div class="form-group">
                    <i class="bi bi-lock-fill"></i>
                    <input type="password" name="password" class="form-control" placeholder="Nouveau mot de passe" required>
                </div>

                <div class="form-group">
                    <i class="bi bi-lock-fill"></i>
                    <input type="password" name="password_confirmation" class="form-control" placeholder="Confirmer le mot de passe" required>
                </div>

                <button type="submit" class="btn btn-yellow">✅ Changer mon mot de passe</button>
            </form>

            <div class="form-footer">
                <p><a href="{{ route('login') }}">← Retour à la connexion</a></p>
            </div>
        </div>
    </div>
</div>

<!-- Bootstrap Icons -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
@endsection
