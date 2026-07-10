<x-guest-layout>
    <h1 class="text-xl font-bold text-slate-800">Vérification de l&apos;email</h1>
    <p class="mt-4 text-sm text-steel">
        Merci de vérifier votre adresse email en cliquant sur le lien que nous venons de vous envoyer. Si vous n&apos;avez rien reçu, nous pouvons vous en renvoyer un.
    </p>

    @if (session('status') == 'verification-link-sent')
        <div class="mt-4 font-medium text-sm text-emerald-600">
            Un nouveau lien de vérification a été envoyé à l&apos;adresse email fournie.
        </div>
    @endif

    <div class="mt-6 flex items-center justify-between">
        <form method="POST" action="{{ route('verification.send') }}">
            @csrf

            <x-primary-button>
                Renvoyer l&apos;email de vérification
            </x-primary-button>
        </form>

        <form method="POST" action="{{ route('logout') }}">
            @csrf

            <button type="submit" class="text-sm font-medium text-steel hover:text-cofima transition-colors">
                Déconnexion
            </button>
        </form>
    </div>
</x-guest-layout>
