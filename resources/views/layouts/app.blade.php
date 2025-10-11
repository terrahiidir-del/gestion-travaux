<!DOCTYPE html> 
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Vite -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased">
    <div class="min-h-screen bg-gray-100">
        
        {{-- ✅ Barre de navigation --}}
        @include('layouts.navigation')

        {{-- ✅ Zone personnalisée juste en dessous --}}
        <div class="bg-warning text-dark text-center py-3 fw-semibold shadow-sm">
            🎉 Bienvenue sur <strong>EtetiSolution</strong> — Gérez vos demandes, interventions et techniciens facilement !
        </div>

        {{-- ✅ Titre de la page --}}
        @isset($header)
            <header class="bg-white shadow">
                <div class="max-w-7xl mx-auto py-4 px-4 sm:px-6 lg:px-8">
                    {{ $header }}
                </div>
            </header>
        @endisset

        {{-- ✅ Contenu principal --}}
        <main class="py-4">
            @yield('content')
        </main>
    </div>

    <!-- JS Bootstrap -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    {{-- ✅ Scripts personnalisés --}}
    @yield('scripts')
    {{-- ✅ Bandeau cookies --}}
    <x-cookie-banner />

    {{-- ✅ Charger Analytics SEULEMENT si consentement analytics = true --}}
    @php
      $consent = json_decode(request()->cookie('cookie_consent','{}'), true);
    @endphp
    @if(($consent['analytics'] ?? false) === true)
      <!-- Exemple Google Analytics (remplace G-XXXXXXX) -->
      <script async src="https://www.googletagmanager.com/gtag/js?id=G-XXXXXXX"></script>
      <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());
        gtag('config', 'G-XXXXXXX', { 'anonymize_ip': true });
      </script>
    @endif

</body>
</html>
