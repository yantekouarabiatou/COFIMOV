<section>
    <header>
        <h2 class="text-lg font-semibold text-slate-800">
            Informations du profil
        </h2>

        <p class="mt-1 text-sm text-steel">
            Mettez à jour vos nom, prénom, téléphone et adresse email.
        </p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="mt-6 space-y-6">
        @csrf
        @method('patch')

        <div class="grid gap-4 sm:grid-cols-2">
            <div>
                <x-input-label for="prenom" value="Prénom" />
                <x-text-input id="prenom" name="prenom" type="text" class="mt-1 block w-full" :value="old('prenom', $user->prenom)" required autofocus autocomplete="given-name" />
                <x-input-error class="mt-2" :messages="$errors->get('prenom')" />
            </div>

            <div>
                <x-input-label for="nom" value="Nom" />
                <x-text-input id="nom" name="nom" type="text" class="mt-1 block w-full" :value="old('nom', $user->nom)" required autocomplete="family-name" />
                <x-input-error class="mt-2" :messages="$errors->get('nom')" />
            </div>
        </div>

        <div>
            <x-input-label for="telephone" value="Téléphone" />
            <x-text-input id="telephone" name="telephone" type="text" class="mt-1 block w-full" :value="old('telephone', $user->telephone)" autocomplete="tel" />
            <x-input-error class="mt-2" :messages="$errors->get('telephone')" />
        </div>

        <div>
            <x-input-label for="email" value="Email" />
            <x-text-input id="email" name="email" type="email" class="mt-1 block w-full" :value="old('email', $user->email)" required autocomplete="username" />
            <x-input-error class="mt-2" :messages="$errors->get('email')" />

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div>
                    <p class="text-sm mt-2 text-slate-700">
                        Votre adresse email n&apos;est pas vérifiée.

                        <button form="send-verification" class="underline text-sm text-cofima hover:text-cofima-dark">
                            Cliquez ici pour renvoyer l&apos;email de vérification.
                        </button>
                    </p>

                    @if (session('status') === 'verification-link-sent')
                        <p class="mt-2 font-medium text-sm text-emerald-600">
                            Un nouveau lien de vérification a été envoyé à votre adresse email.
                        </p>
                    @endif
                </div>
            @endif
        </div>

        <div class="flex items-center gap-4">
            <x-primary-button>Enregistrer</x-primary-button>

            @if (session('status') === 'profile-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-emerald-600"
                >Enregistré.</p>
            @endif
        </div>
    </form>
</section>
