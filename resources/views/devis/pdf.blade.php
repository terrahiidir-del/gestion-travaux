<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <title>Devis #{{ $devis->id ?? '-' }}</title>
    <style>
        @page { margin: 24px 28px; }
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; color: #222; }
        h1 { font-size: 20px; margin: 0 0 8px; }
        h2 { font-size: 14px; margin: 0 0 6px; color:#444; }
        .header, .footer { width: 100%; }
        .header { margin-bottom: 12px; }
        .brand { font-weight: bold; font-size: 16px; }
        .muted { color:#666; }
        .grid { width: 100%; border-collapse: collapse; }
        .grid th, .grid td { border:1px solid #ddd; padding:8px; vertical-align: top; }
        .grid th { background:#f4f4f4; text-align:left; }
        .totals { margin-top: 12px; width: 40%; }
        .totals td { padding:6px; }
        .right { text-align: right; }
        .box { border:1px solid #ddd; padding:10px; margin-top:8px; }
        .small { font-size: 11px; }
    </style>
</head>
<body>
    {{-- En-tête --}}
    <table class="header">
        <tr>
            <td class="brand">Votre Société</td>
            <td class="right small">Devis n° {{ $devis->id ?? '-' }}<br>
                Émis le {{ optional($devis->created_at)->format('d/m/Y') ?? '-' }}
            </td>
        </tr>
    </table>

    {{-- Infos client --}}
    <div class="box">
        <h2>Client</h2>
        @php
            $demande = $demande ?? $devis->demande ?? null;
            $user = optional($demande)->user;
            $clientName = $user->name ?? ($demande->nom ?? 'Client');
            $telephone  = $demande->telephone ?? $user->phone ?? null;
            $adresse    = $demande->adresse ?? $user->address ?? null;
            $ville      = $demande->ville ?? null;
            $codePostal = $demande->code_postal ?? null;
        @endphp
        <div><strong>{{ $clientName }}</strong></div>
        @if($adresse)
            <div>{{ $adresse }}</div>
        @endif
        @if($codePostal || $ville)
            <div>{{ $codePostal }} {{ $ville }}</div>
        @endif
        @if($telephone)
            <div>Tél : {{ $telephone }}</div>
        @endif
        @if($user && $user->email)
            <div>Email : {{ $user->email }}</div>
        @elseif(!empty($demande->email))
            <div>Email : {{ $demande->email }}</div>
        @endif
    </div>

    {{-- Détails du devis --}}
    <h2 style="margin-top:14px;">Détails du devis</h2>
    <table class="grid">
        <thead>
            <tr>
                <th>Description</th>
                <th class="right">Montant (€)</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>{!! nl2br(e($devis->description ?? '')) !!}</td>
                <td class="right">
                    {{ isset($devis->montant) ? number_format((float)$devis->montant, 2, ',', ' ') : '-' }}
                </td>
            </tr>
        </tbody>
    </table>

    <table class="totals" align="right">
        <tr>
            <td class="right"><strong>Total TTC (€)</strong></td>
            <td class="right">
                <strong>{{ isset($devis->montant) ? number_format((float)$devis->montant, 2, ',', ' ') : '-' }}</strong>
            </td>
        </tr>
    </table>

    <div class="small muted" style="margin-top:28px;">
        Conditions : ce devis est valable 30 jours. Merci de nous contacter pour toute question.
    </div>

    <table class="footer" style="position: fixed; bottom: 0;">
        <tr>
            <td class="small muted">
                © {{ date('Y') }} Votre Société — Généré par l’application
            </td>
            <td class="right small muted">Page 1/1</td>
        </tr>
    </table>
</body>
</html>
