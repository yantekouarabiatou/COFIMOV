
<x-guest-layout>
    <h1 class="text-xl font-bold text-slate-800">Confirmation requise</h1>
    <p class="mt-1 text-sm text-steel">
        Ceci est une zone sécurisée de l&apos;application. Merci de confirmer votre mot de passe avant de continuer.
    </p>

    <form method="POST" action="{{ route('password.confirm') }}" class="mt-6">
        @csrf

        <div>
            <x-input-label for="password" value="Mot de passe" />
            <x-text-input id="password" class="block mt-1 w-full"
                            type="password"
                            name="password"
                            required autocomplete="current-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <div class="flex justify-end mt-6">
            <x-primary-button>
                Confirmer
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
