@php $consent = request()->cookie('cookie_consent'); @endphp
@if(!$consent)
<div class="position-fixed bottom-0 start-0 end-0 bg-dark text-white p-3 shadow-lg" style="z-index:1050;">
  <div class="container d-flex flex-column flex-md-row align-items-start align-items-md-center gap-2">
    <div class="flex-grow-1">
      Nous utilisons des cookies pour mesurer l’audience et améliorer le service.
      <a class="link-light text-decoration-underline" href="{{ route('privacy') }}">En savoir plus</a>
    </div>
    <form method="POST" action="{{ route('me.cookie-consent') }}" class="d-flex align-items-center gap-3">
      @csrf
      <div class="form-check">
        <input class="form-check-input" type="checkbox" name="analytics" value="1" id="ccAnalytics">
        <label class="form-check-label" for="ccAnalytics">Analytics</label>
      </div>
      <div class="form-check">
        <input class="form-check-input" type="checkbox" name="ads" value="1" id="ccAds">
        <label class="form-check-label" for="ccAds">Publicité</label>
      </div>
      <button type="submit" name="refuse" value="1" class="btn btn-secondary">Refuser</button>
      <button type="submit" class="btn btn-success">Accepter</button>
    </form>
  </div>
</div>
@endif
