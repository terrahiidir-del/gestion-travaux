<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            {{ __('Informations du profil') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600">
            {{ __("Mettez à jour vos informations personnelles et votre adresse.") }}
        </p>
    </header>

    {{-- Renvoi du mail de vérification si nécessaire --}}
    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    {{-- Formulaire d’édition du profil --}}
    <form method="post" action="{{ route('profil.update') }}" class="mt-6 space-y-6">
        @csrf

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            {{-- Nom --}}
            <div>
                <x-input-label for="name" :value="__('Nom')" />
                <x-text-input id="name" name="name" type="text" class="mt-1 block w-full"
                              :value="old('name', $user->name)" required autofocus autocomplete="name" />
                <x-input-error class="mt-2" :messages="$errors->get('name')" />
            </div>

            {{-- Prénom --}}
            <div>
                <x-input-label for="prenom" :value="__('Prénom')" />
                <x-text-input id="prenom" name="prenom" type="text" class="mt-1 block w-full"
                              :value="old('prenom', $user->prenom)" autocomplete="given-name" />
                <x-input-error class="mt-2" :messages="$errors->get('prenom')" />
            </div>

            {{-- Email --}}
            <div class="md:col-span-2">
                <x-input-label for="email" :value="__('Email')" />
                <x-text-input id="email" name="email" type="email" class="mt-1 block w-full"
                              :value="old('email', $user->email)" required autocomplete="email" />
                <x-input-error class="mt-2" :messages="$errors->get('email')" />

                @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                    <div class="mt-2">
                        <p class="text-sm text-gray-800">
                            {{ __('Votre adresse email n’est pas vérifiée.') }}
                            <button form="send-verification"
                                    class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                {{ __('Cliquez ici pour renvoyer l’email de vérification.') }}
                            </button>
                        </p>
                        @if (session('status') === 'verification-link-sent')
                            <p class="mt-2 font-medium text-sm text-green-600">
                                {{ __('Un nouveau lien de vérification a été envoyé à votre adresse email.') }}
                            </p>
                        @endif
                    </div>
                @endif
            </div>

            {{-- Téléphone --}}
            <div>
                <x-input-label for="telephone" :value="__('Téléphone')" />
                <x-text-input id="telephone" name="telephone" type="text" class="mt-1 block w-full"
                              :value="old('telephone', $user->telephone)" autocomplete="tel" />
                <x-input-error class="mt-2" :messages="$errors->get('telephone')" />
            </div>

            {{-- Adresse postale --}}
            <div class="md:col-span-2">
                <x-input-label for="adresse_postale" :value="__('Adresse postale')" />
                <x-text-input id="adresse_postale" name="adresse_postale" type="text" class="mt-1 block w-full"
                              :value="old('adresse_postale', $user->adresse_postale)" autocomplete="street-address" />
                <x-input-error class="mt-2" :messages="$errors->get('adresse_postale')" />
            </div>

            {{-- Code postal --}}
            <div>
                <x-input-label for="code_postal" :value="__('Code postal')" />
                <x-text-input id="code_postal" name="code_postal" type="text" class="mt-1 block w-full"
                              :value="old('code_postal', $user->code_postal)" />
                <x-input-error class="mt-2" :messages="$errors->get('code_postal')" />
            </div>

            {{-- Ville --}}
            <div>
                <x-input-label for="ville" :value="__('Ville')" />
                <x-text-input id="ville" name="ville" type="text" class="mt-1 block w-full"
                              :value="old('ville', $user->ville)" />
                <x-input-error class="mt-2" :messages="$errors->get('ville')" />
            </div>
        </div>

        <div class="flex items-center gap-4">
            <x-primary-button>{{ __('Enregistrer') }}</x-primary-button>

            @if (session('status') === 'profile-updated')
                <p x-data="{ show: true }" x-show="show" x-transition
                   x-init="setTimeout(() => show = false, 2000)"
                   class="text-sm text-gray-600">{{ __('Modifications enregistrées.') }}</p>
            @endif
        </div>
    </form>
</section>
