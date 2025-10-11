@extends('layouts.app')

@section('content')
<div class="container py-4">
  <h1 class="h3 mb-3">Mentions légales — EtetiSolution</h1>

  <h2 class="h5 mt-4">Éditeur du site</h2>
  <p>EtetiSolution — [forme juridique] — [adresse complète] — [SIREN/SIRET]  
  Contact : <a href="mailto:[email]">[email]</a> — Tél : [numéro]</p>

  <h2 class="h5 mt-4">Directeur de la publication</h2>
  <p>[Nom, fonction]</p>

  <h2 class="h5 mt-4">Hébergement</h2>
  <p>[Hébergeur] — [adresse] — [pays] — [site web]</p>

  <h2 class="h5 mt-4">Propriété intellectuelle</h2>
  <p>Le contenu (textes, visuels, logos) est protégé. Toute reproduction sans autorisation est interdite.</p>

  <h2 class="h5 mt-4">Données personnelles</h2>
  <p>Voir notre <a href="{{ route('privacy') }}">Politique de confidentialité</a>. DPO/contact : <a href="mailto:[email]">[email]</a>.</p>

  <h2 class="h5 mt-4">Responsabilité</h2>
  <p>Nous mettons tout en œuvre pour assurer l’exactitude des informations. Le site peut contenir des liens externes.</p>

  <p class="text-muted mt-4">Mise à jour le 09/10/2025</p>
</div>
@endsection
