<x-guest-layout>
    <h1 class="text-xl font-bold text-slate-800">Mot de passe oublié</h1>
    <p class="mt-1 text-sm text-steel">
        Indiquez votre adresse email professionnelle, nous vous enverrons un lien pour choisir un nouveau mot de passe.
    </p>

    <!-- Session Status -->
    <x-auth-session-status class="mt-4" :status="session('status')" />

    <form method="POST" action="{{ route('password.email') }}" class="mt-6">
        @csrf

        <div>
            <x-input-label for="email" value="Email professionnel" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" placeholder="prenom.nom@cofima.cc" :value="old('email')" required autofocus />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div class="flex items-center justify-end mt-6">
            <x-primary-button>
                Envoyer le lien de réinitialisation
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
