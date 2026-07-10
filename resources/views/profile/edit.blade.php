<x-app-layout>
    <x-slot name="header">
        <div>
            <h2 class="text-base sm:text-lg font-bold text-slate-800">Mon profil</h2>
            <p class="text-xs text-steel">Gérez vos informations personnelles et votre mot de passe</p>
        </div>
    </x-slot>

    <div class="py-6 sm:py-8">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
            <div class="rounded-xl border border-slate-200 bg-white p-5 sm:p-8">
                @include('profile.partials.update-profile-information-form')
            </div>

            <div class="rounded-xl border border-slate-200 bg-white p-5 sm:p-8">
                @include('profile.partials.update-password-form')
            </div>
        </div>
    </div>
</x-app-layout>
