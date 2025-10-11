@extends('layouts.app')

@section('content')
<style>
    body {
        background: #000;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

    .login-container {
        display: flex;
        justify-content: center;
        align-items: center;
        min-height: 100vh;
        padding: 2rem;
    }

    .login-box {
        display: flex;
        background-color: #fff;
        border-radius: 16px;
        box-shadow: 0 0 25px rgba(255, 255, 0, 0.2);
        overflow: hidden;
        max-width: 900px;
        width: 100%;
    }

    .login-left {
        background-color: #ffc107;
        padding: 40px;
        flex: 1;
        display: flex;
        flex-direction: column;
        justify-content: center;
        color: #000;
    }

    .login-left h2 {
        font-size: 2.5rem;
        font-weight: 800;
        margin-bottom: 20px;
    }

    .login-left p {
        font-size: 1.1rem;
        line-height: 1.6;
    }

    .login-right {
        flex: 1;
        padding: 40px;
        background-color: #fff;
    }

    .login-title {
        font-size: 2rem;
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

    .form-check-label {
        font-size: 0.9rem;
    }

    .login-footer {
        margin-top: 20px;
        text-align: center;
    }

    .login-footer a {
        color: #000;
        text-decoration: underline;
        font-weight: bold;
    }

    .alert {
        background-color: #fff3cd;
        color: #856404;
        border: 1px solid #ffeeba;
        padding: 12px;
        border-radius: 10px;
        margin-bottom: 1rem;
    }

    @media (max-width: 768px) {
        .login-box {
            flex-direction: column;
        }

        .login-left,
        .login-right {
            padding: 30px;
        }
    }
</style>

<div class="login-container">
    <div class="login-box">

        <!-- Partie gauche -->
        <div class="login-left">
            <h2>Bienvenue !</h2>
            <p>Connectez-vous pour accéder à votre espace personnel, suivre vos demandes, accepter des devis, ou consulter vos interventions.</p>
        </div>

        <!-- Partie droite -->
        <div class="login-right">
            <h3 class="login-title">Se connecter</h3>

            {{-- Message d’erreur --}}
            @if ($errors->any())
                <div class="alert d-flex align-items-center gap-2" role="alert">
                    <i class="bi bi-exclamation-triangle-fill fs-5"></i>
                    <div>
                        @foreach ($errors->all() as $error)
                            <div>{{ $error }}</div>
                        @endforeach
                    </div>
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}">
                @csrf

                <div class="form-group">
                    <i class="bi bi-envelope-fill"></i>
                    <input type="email" name="email" class="form-control" required placeholder="Adresse e-mail">
                </div>

                <div class="form-group">
                    <i class="bi bi-lock-fill"></i>
                    <input type="password" name="password" class="form-control" required placeholder="Mot de passe">
                </div>

                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div class="form-check">
                        <input type="checkbox" class="form-check-input" name="remember" id="remember">
                        <label class="form-check-label" for="remember">Se souvenir de moi</label>
                    </div>

                    @if (Route::has('password.request'))
                        <a href="{{ route('password.request') }}" class="text-decoration-underline text-secondary fw-semibold">
                            Mot de passe oublié ?
                        </a>
                    @endif
                </div>

                <button type="submit" class="btn btn-yellow">Connexion</button>
            </form>

            <div class="login-footer">
                <p>Pas encore de compte ? <a href="{{ route('register') }}">Inscrivez-vous ici</a></p>
            </div>
        </div>
    </div>
</div>

<!-- Bootstrap Icons -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
@endsection
