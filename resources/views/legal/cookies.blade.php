@extends('layouts.app')

@section('content')
<div class="container py-4">
  <h1 class="h3 mb-3">Cookies</h1>
  <p>Nous utilisons des cookies nécessaires au fonctionnement du site. Les cookies d’analytics et de publicité sont déposés uniquement avec votre consentement.</p>

  <h2 class="h5 mt-4">Préférences</h2>
  <form method="POST" action="{{ route('me.cookie-consent') }}" class="d-flex align-items-center gap-3">
    @csrf
    @php $consent = json_decode(request()->cookie('cookie_consent','{}'), true); @endphp
    <div class="form-check">
      <input class="form-check-input" type="checkbox" name="analytics" value="1" id="cc2Analytics"
             @checked(($consent['analytics'] ?? false) === true)>
      <label class="form-check-label" for="cc2Analytics">Analytics</label>
    </div>
    <div class="form-check">
      <input class="form-check-input" type="checkbox" name="ads" value="1" id="cc2Ads"
             @checked(($consent['ads'] ?? false) === true)>
      <label class="form-check-label" for="cc2Ads">Publicité</label>
    </div>
    <button type="submit" class="btn btn-primary">Enregistrer</button>
  </form>
</div>
@endsection
