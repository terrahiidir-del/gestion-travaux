@extends('layouts.app')

@section('content')
<div class="container py-4">
  <h1 class="h3 mb-3">Politique de confidentialité — EtetiSolution</h1>

  <p><strong>Responsable du traitement</strong><br>
  EtetiSolution — 118 Avenue jean jaures 75019 — Contact : <a href="mailto:[email]">bachir.bessaou@eteti.fr</a></p>

  <h2 class="h5 mt-4">Quelles données nous collectons</h2>
  <ul>
    <li>Compte : nom, email, mot de passe (haché)</li>
    <li>Demandes & interventions : titres, descriptions, pièces jointes (le cas échéant), dates, technicien assigné, statut.</li>
    <li>Devis / facturation (si applicable) : montants, références, coordonnées de facturation (pas de numéros de carte).</li>
    <li>Journaux techniques : IP, user-agent, logs d’erreur et d’accès.</li>
    <li>Cookies : nécessaires ; analytics/publicité uniquement après consentement.</li>
  </ul>

  <h2 class="h5 mt-4">Pourquoi (bases légales)</h2>
  <ul>
    <li>Fournir le service et le support — <em>exécution du contrat</em>.</li>
    <li>Communication et sécurité — <em>intérêt légitime</em>.</li>
    <li>Facturation & obligations légales — <em>obligation légale</em>.</li>
    <li>Mesure d’audience / marketing — <em>consentement</em>.</li>
  </ul>

  <h2 class="h5 mt-4">Où sont stockées vos données</h2>
  <p>Base : <strong>[Hébergeur]</strong>, <strong>[Pays/UE]</strong>. Fichiers : <strong>[Local/S3]</strong> <strong>[Région]</strong>. Sous-traitants : [Email], [Analytics].</p>

  <h2 class="h5 mt-4">Durées de conservation</h2>
  <ul>
    <li>Compte : jusqu’à suppression + 3 ans d’inactivité max.</li>
    <li>Demandes/interventions : 5 ans après clôture.</li>
    <li>Devis/factures : 10 ans.</li>
    <li>Logs techniques : 6 mois.</li>
    <li>Cookies : consentement 6–12 mois ; analytics 13 mois.</li>
  </ul>

  <h2 class="h5 mt-4">Vos droits</h2>
  <p>Accès, rectification, effacement, limitation, opposition, portabilité, retrait du consentement. 
  Contact : <a href="mailto:[email]">[email]</a>. Autorité : CNIL.</p>

  <h2 class="h5 mt-4">Cookies & préférences</h2>
  <p>Modifiez vos préférences à tout moment : <a href="{{ route('cookies') }}">page Cookies</a>.</p>

  <h2 class="h5 mt-4">Transferts hors UE</h2>
  <p>Le cas échéant, nous appliquons des garanties appropriées (CCT, pays adéquat, etc.).</p>

  <h2 class="h5 mt-4">Sécurité</h2>
  <p>Mesures techniques et organisationnelles : chiffrement, contrôle d’accès, sauvegardes, journalisation limitée.</p>

  <p class="text-muted mt-4">Version 1.0 — mise à jour le 09/10/2025</p>
</div>
@endsection
