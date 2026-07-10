<x-guest-layout>
    <h1 class="text-xl font-bold text-slate-800">Connexion à votre espace</h1>
    <p class="mt-1 text-sm text-steel">Accédez à vos demandes de frais de transport.</p>

    <!-- Session Status -->
    <x-auth-session-status class="mt-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}" class="mt-6">
        @csrf

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Email professionnel')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" placeholder="prenom.nom@cofima.cc" :value="old('email')" required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Mot de passe')" />

            <x-text-input id="password" class="block mt-1 w-full"
                            type="password"
                            name="password"
                            required autocomplete="current-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Remember Me -->
        <div class="block mt-4">
            <label for="remember_me" class="inline-flex items-center">
                <input id="remember_me" type="checkbox" class="rounded border-slate-300 text-cofima shadow-sm focus:ring-cofima/20" name="remember">
                <span class="ms-2 text-sm text-steel">{{ __('Se souvenir de moi') }}</span>
            </label>
        </div>

        <div class="flex items-center justify-between mt-6 gap-3">
            @if (Route::has('password.request'))
                <a class="text-sm font-medium text-steel hover:text-cofima transition-colors" href="{{ route('password.request') }}">
                    {{ __('Mot de passe oublié ?') }}
                </a>
            @endif

            <x-primary-button class="ms-auto">
                {{ __('Se connecter') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
