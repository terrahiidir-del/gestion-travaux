<nav x-data="{ open: false }" class="bg-white border-b border-gray-100 shadow">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-16">

            @php($role = auth()->user()->role ?? null)

            {{-- 🔰 Logo + Liens à gauche --}}
            <div class="flex items-center space-x-19">
                <!-- Logo -->
                <a href="{{ url('/') }}">
                    <img src="{{ asset('images/logo.png') }}" alt="Logo" class="w-auto" style="height: 190px;">
                </a>

                <!-- Accueil / Contact -->
                <div class="flex space-x-8">
                    {{-- Accueil visible uniquement si l'utilisateur n'est PAS admin/technicien --}}
                    @unless (in_array($role, ['admin','technicien']))
                        <a href="{{ route('home') }}"
                           class="text-xl font-bold text-gray-800 hover:text-indigo-600 {{ request()->routeIs('home') ? 'border-b-2 border-indigo-500' : '' }}">
                            Accueil
                        </a>
                    @endunless

                    {{-- Contact (inchangé) : visible pour tous sauf admin connecté, et pour les invités --}}
                    @auth
                        @if($role !== 'admin')
                            <a href="{{ route('contact') }}"
                               class="text-xl font-bold text-gray-800 hover:text-indigo-600 {{ request()->routeIs('contact') ? 'border-b-2 border-indigo-500' : '' }}">
                                Contact
                            </a>
                        @endif
                    @else
                        <a href="{{ route('contact') }}"
                           class="text-xl font-bold text-gray-800 hover:text-indigo-600 {{ request()->routeIs('contact') ? 'border-b-2 border-indigo-500' : '' }}">
                            Contact
                        </a>
                    @endauth
                </div>
            </div>

            {{-- 🔐 Auth / Liens par rôle à droite (desktop) --}}
            <div class="hidden sm:flex items-center space-x-8 ml-8">
                @auth
                    {{-- Liens dynamiques par rôle --}}
                    @if($role === 'client')
                        <div class="flex space-x-8">
                            <x-nav-link :href="route('demandes.index')" :active="request()->routeIs('demandes.*')" class="font-bold">
                                Mes Demandes
                            </x-nav-link>
                        </div>
                    @elseif($role === 'admin')
                        <div class="flex space-x-8">
                            <x-nav-link :href="route('demandes.admin')" :active="request()->routeIs('demandes.admin')" class="font-bold">
                                Demandes à traiter
                            </x-nav-link>
                            <x-nav-link :href="route('demandes.validées')" :active="request()->routeIs('demandes.validées')" class="font-bold">
                                Demandes validées
                            </x-nav-link>
                            <x-nav-link :href="route('interventions.index')" :active="request()->routeIs('interventions.*')" class="font-bold">
                                Interventions
                            </x-nav-link>
                        </div>
                    @elseif($role === 'technicien')
                        <div class="flex space-x-8">
                            <x-nav-link :href="route('interventions.mes')" :active="request()->routeIs('interventions.mes')" class="font-bold">
                                Mes Interventions
                            </x-nav-link>
                        </div>
                    @endif

                    {{-- Dropdown utilisateur --}}
                    <x-dropdown align="right" width="48">
                        <x-slot name="trigger">
                            <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm font-bold rounded-md text-gray-600 bg-white hover:text-gray-900">
                                <div>{{ Auth::user()->name }}</div>
                                <svg class="ml-1 h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"/>
                                </svg>
                            </button>
                        </x-slot>

                        <x-slot name="content">
                            {{-- 👉 Nouveau : lien vers l’édition du profil --}}
                            <x-dropdown-link :href="route('profile.edit')">
                                {{ __('Mes informations') }}
                            </x-dropdown-link>

                            <div class="border-t border-gray-200 my-1"></div>

                            {{-- Déconnexion --}}
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <x-dropdown-link :href="route('logout')" onclick="event.preventDefault(); this.closest('form').submit();">
                                    {{ __('Déconnexion') }}
                                </x-dropdown-link>
                            </form>
                        </x-slot>
                    </x-dropdown>
                @else
                    <div class="flex space-x-8">
                        <a href="{{ route('login') }}"
                           class="text-lg font-bold text-gray-700 hover:text-indigo-600 {{ request()->routeIs('login') ? 'underline text-indigo-700' : '' }}">
                            Connexion
                        </a>
                        <a href="{{ route('register') }}"
                           class="text-lg font-bold text-gray-700 hover:text-indigo-600 {{ request()->routeIs('register') ? 'underline text-indigo-700' : '' }}">
                            Inscription
                        </a>
                    </div>
                @endauth
            </div>

            <!-- 🍔 Menu mobile (bouton) -->
            <div class="-mr-2 flex items-center sm:hidden">
                <button @click="open = ! open"
                        :aria-expanded="open.toString()"
                        aria-controls="mobile-menu"
                        class="p-2 rounded-md text-gray-400 hover:text-gray-500 focus:outline-none">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path :class="{ 'hidden': open, 'inline-flex': !open }" class="inline-flex"
                              stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M4 6h16M4 12h16M4 18h16"/>
                        <path :class="{ 'hidden': !open, 'inline-flex': open }" class="hidden"
                              stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
        </div>
    </div>

    {{-- 📱 Panneau mobile --}}
    <div id="mobile-menu"
         x-cloak
         x-show="open"
         x-transition.origin.top
         class="sm:hidden border-t bg-white shadow-md">
        <div class="px-4 py-3 space-y-2">

            {{-- Liens communs --}}
            @unless (in_array($role, ['admin','technicien']))
                <a href="{{ route('home') }}" class="block py-2 font-semibold hover:text-indigo-600">
                    Accueil
                </a>
            @endunless

            @auth
                @if($role !== 'admin')
                    <a href="{{ route('contact') }}" class="block py-2 font-semibold hover:text-indigo-600">
                        Contact
                    </a>
                @endif
            @else
                <a href="{{ route('contact') }}" class="block py-2 font-semibold hover:text-indigo-600">
                    Contact
                </a>
            @endauth

            {{-- Liens par rôle --}}
            @auth
                @if($role === 'client')
                    <a href="{{ route('demandes.index') }}" class="block py-2 hover:text-indigo-600">
                        Mes Demandes
                    </a>
                @elseif($role === 'admin')
                    <a href="{{ route('demandes.admin') }}" class="block py-2 hover:text-indigo-600">
                        Demandes à traiter
                    </a>
                    <a href="{{ route('demandes.validées') }}" class="block py-2 hover:text-indigo-600">
                        Demandes validées
                    </a>
                    <a href="{{ route('interventions.index') }}" class="block py-2 hover:text-indigo-600">
                        Interventions
                    </a>
                @elseif($role === 'technicien')
                    <a href="{{ route('interventions.mes') }}" class="block py-2 hover:text-indigo-600">
                        Mes Interventions
                    </a>
                @endif

                {{-- Compte / Profil / Déconnexion --}}
                <div class="pt-2 border-t">
                    <div class="text-sm text-gray-500 mb-1">
                        Connecté en tant que <span class="font-semibold text-gray-700">{{ Auth::user()->name }}</span>
                    </div>

                    {{-- 👉 Nouveau : lien “Mes informations” (mobile) --}}
                    <a href="{{ route('profile.edit') }}" class="block py-2 font-semibold hover:text-indigo-600">
                        Mes informations
                    </a>

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="w-full text-left py-2 font-semibold hover:text-red-600">
                            Déconnexion
                        </button>
                    </form>
                </div>
            @else
                <div class="pt-2 border-t">
                    <a href="{{ route('login') }}" class="block py-2 font-semibold hover:text-indigo-600">Connexion</a>
                    <a href="{{ route('register') }}" class="block py-2 font-semibold hover:text-indigo-600">Inscription</a>
                </div>
            @endauth
        </div>
    </div>
</nav>
