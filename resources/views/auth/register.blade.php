@extends('layouts.app')

@section('content')
<style>
    body { background: #000; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
    .register-wrapper { min-height: 100vh; display: flex; align-items: center; justify-content: center; padding: 2rem; }
    .register-card { background-color: #fff; border-radius: 20px; box-shadow: 0 8px 24px rgba(255, 255, 0, 0.2); padding: 40px; width: 100%; max-width: 600px; animation: fadeIn 0.8s ease-in-out; }
    .register-title { font-size: 2rem; font-weight: bold; margin-bottom: 20px; color: #000; text-align: center; }
    .form-label { font-weight: 600; color: #000; }
    .form-control { border-radius: 8px; border: 1px solid #ccc; padding: 10px 14px; }
    .is-invalid { border-color: #dc3545; }
    .invalid-feedback { display:block; color:#dc3545; }
    .btn-yellow { background-color: #ffc107; color: #000; font-weight: bold; border: none; border-radius: 8px; padding: 12px; width: 100%; transition: background-color 0.3s ease; }
    .btn-yellow:hover { background-color: #e0a800; }
    .register-footer { margin-top: 15px; text-align: center; }
    .register-footer a { color: #000; text-decoration: underline; font-weight: 600; }
    @keyframes fadeIn { from { opacity: 0; transform: translateY(20px);} to { opacity: 1; transform: translateY(0);} }
</style>

<div class="register-wrapper">
    <div class="register-card">
        <h2 class="register-title">Créer un compte</h2>

        {{-- 📣 Alerte globale si des erreurs existent --}}
        @if ($errors->any())
            <div class="alert alert-danger">
                <strong>Merci de corriger les champs en rouge :</strong>
                <ul class="mb-0">
                    @foreach ($errors->all() as $err)
                        <li>{{ $err }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('register') }}">
            @csrf

            <div class="row">
                <div class="mb-3 col-md-6">
                    <label for="prenom" class="form-label">Prénom</label>
                    <input id="prenom" type="text" class="form-control @error('prenom') is-invalid @enderror"
                           name="prenom" value="{{ old('prenom') }}" required>
                    @error('prenom') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="mb-3 col-md-6">
                    <label for="nom" class="form-label">Nom</label>
                    <input id="nom" type="text" class="form-control @error('nom') is-invalid @enderror"
                           name="nom" value="{{ old('nom') }}" required>
                    @error('nom') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>

            <div class="mb-3">
                <label for="email" class="form-label">Adresse e-mail</label>
                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror"
                       name="email" value="{{ old('email') }}" required>
                @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="mb-3">
                <label for="telephone" class="form-label">Téléphone</label>
                <input id="telephone" type="text" class="form-control @error('telephone') is-invalid @enderror"
                       name="telephone" value="{{ old('telephone') }}" required>
                @error('telephone') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="mb-3">
                <label for="adresse_postale" class="form-label">Adresse postale</label>
                <input id="adresse_postale" type="text" class="form-control @error('adresse_postale') is-invalid @enderror"
                       name="adresse_postale" value="{{ old('adresse_postale') }}" required>
                @error('adresse_postale') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="row">
                <div class="mb-3 col-md-6">
                    <label for="code_postal" class="form-label">Code postal</label>
                    <input id="code_postal" type="text" class="form-control @error('code_postal') is-invalid @enderror"
                           name="code_postal" value="{{ old('code_postal') }}" required>
                    @error('code_postal') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="mb-3 col-md-6">
                    <label for="ville" class="form-label">Ville</label>
                    <input id="ville" type="text" class="form-control @error('ville') is-invalid @enderror"
                           name="ville" value="{{ old('ville') }}" required>
                    @error('ville') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>

            {{-- Mot de passe avec conditions visibles + contraintes HTML --}}
            <div class="mb-3">
              <label for="password" class="form-label">Mot de passe</label>
              <input id="password" type="password"
                     name="password"
                     class="form-control @error('password') is-invalid @enderror"
                     required autocomplete="new-password"
                     minlength="12"
                     pattern="(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[^A-Za-z0-9]).{12,}"
                     aria-describedby="pwdHelp">
              @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror

              <div id="pwdHelp" class="form-text mt-1">
                Le mot de passe doit contenir :
              </div>
              <ul id="pwdChecklist" class="list-unstyled small mb-0">
                <li data-check="length" class="text-danger">✗ 12 caractères ou plus</li>
                <li data-check="lower"  class="text-danger">✗ Au moins une lettre minuscule</li>
                <li data-check="upper"  class="text-danger">✗ Au moins une lettre majuscule</li>
                <li data-check="digit"  class="text-danger">✗ Au moins un chiffre</li>
                <li data-check="symbol" class="text-danger">✗ Au moins un symbole (ex. ! @ # ?)</li>
              </ul>
            </div>

            <div class="mb-3">
                <label for="password_confirmation" class="form-label">Confirmation mot de passe</label>
                <input id="password_confirmation" type="password" class="form-control"
                       name="password_confirmation" required autocomplete="new-password">
            </div>

            {{-- ✅ Consentements RGPD (Option B — Bootstrap) --}}
            <div class="form-check mt-3">
              <input class="form-check-input" type="checkbox" id="privacy" name="privacy" value="1" required>
              <label class="form-check-label" for="privacy">
                J’ai lu et j’accepte la
                <a href="{{ route('privacy') }}" target="_blank">Politique de confidentialité</a>
                et les
                <a href="{{ route('mentions') }}" target="_blank">Mentions légales</a>.
              </label>
              @error('privacy') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
            </div>

            <div class="form-check mt-2">
              <input class="form-check-input" type="checkbox" id="marketing_optin" name="marketing_optin" value="1"
                     {{ old('marketing_optin') ? 'checked' : '' }}>
              <label class="form-check-label" for="marketing_optin">
                Je souhaite recevoir des informations et offres.
              </label>
              @error('marketing_optin') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
            </div>

            <p class="text-muted small mt-2">
              Vous pouvez gérer vos préférences cookies depuis la page <a href="{{ route('cookies') }}">Cookies</a>.
            </p>

            <button type="submit" class="btn btn-yellow">S'inscrire</button>
        </form>

        <div class="register-footer mt-3">
            <span>Déjà inscrit ?</span>
            <a href="{{ route('login') }}">Se connecter</a>
        </div>
    </div>
</div>

{{-- ✅ Checklist dynamique des conditions de mot de passe --}}
<script>
(function() {
  const pwd = document.getElementById('password');
  if (!pwd) return;

  const checks = {
    length: v => v.length >= 12,
    lower:  v => /[a-z]/.test(v),
    upper:  v => /[A-Z]/.test(v),
    digit:  v => /\d/.test(v),
    symbol: v => /[^A-Za-z0-9]/.test(v),
  };

  const list = document.getElementById('pwdChecklist');
  const items = list ? list.querySelectorAll('li[data-check]') : [];

  function update() {
    const v = pwd.value || '';
    items.forEach(li => {
      const ok = checks[li.dataset.check]?.(v);
      li.classList.toggle('text-success', !!ok);
      li.classList.toggle('text-danger', !ok);
      li.textContent = (ok ? '✓ ' : '✗ ') + li.textContent.replace(/^(\✓|\✗)\s*/, '');
    });
  }

  pwd.addEventListener('input', update);
  update();
})();
</script>

<script>
document.addEventListener('input', () => {
  const p = document.getElementById('password');
  const c = document.getElementById('password_confirmation');
  if (!p || !c) return;
  const ok = !c.value.length || p.value === c.value;
  c.classList.toggle('is-invalid', !ok);
});
</script>

@endsection
