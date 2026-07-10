
<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-base sm:text-lg font-bold text-slate-800">Tableau de bord</h2>
                <p class="text-xs text-steel">Vue d&apos;ensemble de vos demandes</p>
            </div>
            <button type="button" x-data="" x-on:click="$dispatch('open-modal', 'nouvelle-demande')" class="inline-flex items-center gap-2 rounded-lg bg-cofima px-4 py-2.5 text-sm font-semibold text-white hover:bg-cofima-dark transition-colors shadow-sm hover:shadow">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" /></svg>
                Nouvelle demande
            </button>
        </div>
    </x-slot>

    <div class="py-6 sm:py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            @if (session('status') === 'demande-soumise')
                <div class="rounded-lg bg-emerald-50 border border-emerald-200 px-4 py-3 text-sm text-emerald-700 flex items-center gap-2">
                    <svg class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" /></svg>
                    Votre demande a été soumise avec succès.
                </div>
            @endif

            <!-- Stats -->
            <div class="grid gap-4 grid-cols-2 lg:grid-cols-4">
                <div class="rounded-xl border border-slate-200 bg-white p-5 flex items-start justify-between hover:shadow-md transition-shadow">
                    <div>
                        <p class="text-xs font-medium text-steel">Demandes en attente</p>
                        <p class="mt-2 text-2xl font-extrabold text-amber-500">{{ $stats['en_attente'] }}</p>
                    </div>
                    <div class="h-10 w-10 rounded-lg bg-amber-100 flex items-center justify-center shrink-0">
                        <svg class="h-5 w-5 text-amber-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6l4 2" /><circle cx="12" cy="12" r="9" stroke-linecap="round" stroke-linejoin="round" /></svg>
                    </div>
                </div>
                <div class="rounded-xl border border-slate-200 bg-white p-5 flex items-start justify-between hover:shadow-md transition-shadow">
                    <div>
                        <p class="text-xs font-medium text-steel">Demandes validées</p>
                        <p class="mt-2 text-2xl font-extrabold text-emerald-600">{{ $stats['validee'] }}</p>
                    </div>
                    <div class="h-10 w-10 rounded-lg bg-emerald-100 flex items-center justify-center shrink-0">
                        <svg class="h-5 w-5 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" /></svg>
                    </div>
                </div>
                <div class="rounded-xl border border-slate-200 bg-white p-5 flex items-start justify-between hover:shadow-md transition-shadow">
                    <div>
                        <p class="text-xs font-medium text-steel">Demandes rejetées</p>
                        <p class="mt-2 text-2xl font-extrabold text-red-600">{{ $stats['rejetee'] }}</p>
                    </div>
                    <div class="h-10 w-10 rounded-lg bg-red-100 flex items-center justify-center shrink-0">
                        <svg class="h-5 w-5 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" /></svg>
                    </div>
                </div>
                <div class="rounded-xl border border-slate-200 bg-white p-5 flex items-start justify-between hover:shadow-md transition-shadow">
                    <div>
                        <p class="text-xs font-medium text-steel">Montant total du mois</p>
                        <p class="mt-2 text-2xl font-extrabold text-cofima">{{ number_format($stats['total_mois'], 0, ',', ' ') }} FCFA</p>
                    </div>
                    <div class="h-10 w-10 rounded-lg bg-cofima/10 flex items-center justify-center shrink-0">
                        <svg class="h-5 w-5 text-cofima" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m-4-5.5c0 1.38 1.79 2.5 4 2.5s4-1.12 4-2.5-1.79-2.5-4-2.5-4-1.12-4-2.5S9.79 5 12 5s4 1.12 4 2.5" /></svg>
                    </div>
                </div>
            </div>

            <!-- Table -->
            <div class="rounded-xl border border-slate-200 bg-white overflow-hidden">
                <div class="px-5 py-4 border-b border-slate-100 flex items-center justify-between">
                    <h2 class="font-semibold text-slate-800">Mes demandes</h2>
                    <span class="text-xs text-steel">{{ $demandes->count() }} demande(s)</span>
                </div>

                <div class="overflow-x-auto p-5">
                    <table id="demandes-table" class="w-full text-sm">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Trajet</th>
                                <th>Transport</th>
                                <th>Coût</th>
                                <th>Justificatif</th>
                                <th>Statut</th>
                                <th class="dt-orderable-false">Lettre</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($demandes as $demande)
                                <tr>
                                    <td data-order="{{ $demande->date_deplacement->format('Y-m-d') }}" class="whitespace-nowrap text-slate-700">{{ $demande->date_deplacement->format('d/m/Y') }}</td>
                                    <td class="text-slate-700">{{ $demande->lieu_depart }} → {{ $demande->lieu_arrivee }}</td>
                                    <td class="text-slate-700">{{ $demande->moyen_transport }}</td>
                                    <td data-order="{{ $demande->cout_estime }}" class="whitespace-nowrap font-medium text-cofima">{{ number_format($demande->cout_estime, 0, ',', ' ') }} FCFA</td>
                                    <td>
                                        @forelse ($demande->justificatifs as $justificatif)
                                            <a href="{{ asset('storage/'.$justificatif->chemin) }}" target="_blank" class="text-cofima hover:underline">Voir</a>
                                        @empty
                                            <span class="text-steel">—</span>
                                        @endforelse
                                    </td>
                                    <td>
                                        <span @class([
                                            'inline-block rounded-full px-2.5 py-1 text-xs font-semibold',
                                            'bg-amber-100 text-amber-700' => $demande->statut === 'en_attente',
                                            'bg-emerald-100 text-emerald-700' => $demande->statut === 'validee',
                                            'bg-red-100 text-red-700' => $demande->statut === 'rejetee',
                                        ])>
                                            {{ match ($demande->statut) {
                                                'en_attente' => 'En attente',
                                                'validee' => 'Validée',
                                                'rejetee' => 'Rejetée',
                                            } }}
                                        </span>
                                    </td>
                                    <td>
                                        <a href="{{ route('demandes.pdf', $demande) }}" class="inline-flex items-center gap-1 text-cofima hover:underline">
                                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 3v12m0 0 4-4m-4 4-4-4M4 17v2a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2v-2" /></svg>
                                            PDF
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Historique des validations/rejets -->
            <div class="rounded-xl border border-slate-200 bg-white overflow-hidden">
                <div class="px-5 py-4 border-b border-slate-100 flex items-center justify-between">
                    <h2 class="font-semibold text-slate-800">Historique des validations / rejets</h2>
                    <span class="text-xs text-steel">{{ $historiques->count() }} événement(s)</span>
                </div>

                @if ($historiques->isEmpty())
                    <div class="px-5 py-12 text-center text-sm text-steel">
                        Aucune demande n&apos;a encore été traitée par la Direction Générale.
                    </div>
                @else
                    <div class="overflow-x-auto p-5">
                        <table id="historique-table" class="w-full text-sm">
                            <thead>
                                <tr>
                                    <th>Date de traitement</th>
                                    <th>Trajet</th>
                                    <th>Coût</th>
                                    <th>Statut</th>
                                    <th>Traité par</th>
                                    <th>Commentaire</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($historiques as $historique)
                                    <tr>
                                        <td data-order="{{ $historique->created_at->format('Y-m-d H:i:s') }}" class="whitespace-nowrap text-slate-700">{{ $historique->created_at->format('d/m/Y H:i') }}</td>
                                        <td class="text-slate-700">{{ $historique->demandeTransport->lieu_depart }} → {{ $historique->demandeTransport->lieu_arrivee }}</td>
                                        <td data-order="{{ $historique->demandeTransport->cout_estime }}" class="whitespace-nowrap font-medium text-cofima">{{ number_format($historique->demandeTransport->cout_estime, 0, ',', ' ') }} FCFA</td>
                                        <td>
                                            <span @class([
                                                'inline-block rounded-full px-2.5 py-1 text-xs font-semibold',
                                                'bg-emerald-100 text-emerald-700' => $historique->statut === 'validee',
                                                'bg-red-100 text-red-700' => $historique->statut === 'rejetee',
                                            ])>
                                                {{ $historique->statut === 'validee' ? 'Validée' : 'Rejetée' }}
                                            </span>
                                        </td>
                                        <td class="text-slate-700">{{ $historique->user?->full_name ?? '—' }}</td>
                                        <td class="text-slate-700 max-w-[20rem] truncate" title="{{ $historique->commentaire }}">{{ $historique->commentaire ?: '—' }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Modal nouvelle demande -->
    <x-modal name="nouvelle-demande" :show="$errors->any()" focusable>
        <div class="flex items-center justify-between border-b border-slate-100 px-6 py-4">
            <h2 class="text-lg font-bold text-slate-800">Nouvelle demande de transport</h2>
            <button type="button" x-on:click="$dispatch('close-modal', 'nouvelle-demande')" class="text-steel hover:text-slate-800 text-2xl leading-none">&times;</button>
        </div>

        <form method="POST" action="{{ route('demandes.store') }}" enctype="multipart/form-data" class="px-6 py-5 space-y-4">
            @csrf

            <div class="grid gap-4 sm:grid-cols-2">
                <div>
                    <x-input-label for="lieu_depart" value="Lieu de départ *" />
                    <x-text-input id="lieu_depart" name="lieu_depart" class="block mt-1 w-full" :value="old('lieu_depart')" required placeholder="Ex : COFIMA" />
                    <x-input-error :messages="$errors->get('lieu_depart')" class="mt-2" />
                </div>
                <div>
                    <x-input-label for="lieu_arrivee" value="Lieu d'arrivée *" />
                    <x-text-input id="lieu_arrivee" name="lieu_arrivee" class="block mt-1 w-full" :value="old('lieu_arrivee')" required placeholder="Ex : Godomey
                    " />
                    <x-input-error :messages="$errors->get('lieu_arrivee')" class="mt-2" />
                </div>
            </div>

            <div class="grid gap-4 sm:grid-cols-2">
                <div>
                    <x-input-label for="date_deplacement" value="Date du déplacement *" />
                    <x-text-input id="date_deplacement" name="date_deplacement" type="date" class="block mt-1 w-full" :value="old('date_deplacement')" required />
                    <x-input-error :messages="$errors->get('date_deplacement')" class="mt-2" />
                </div>
                <div>
                    <x-input-label for="moyen_transport" value="Moyen de transport *" />
                    <select id="moyen_transport" name="moyen_transport" required class="js-select2 w-full mt-1">
                        <option value="">Sélectionner…</option>
                        @foreach (['Taxi', 'Moto', 'Véhicule personnel', 'Location', 'Autre'] as $option)
                            <option value="{{ $option }}" @selected(old('moyen_transport') === $option)>{{ $option }}</option>
                        @endforeach
                    </select>
                    <x-input-error :messages="$errors->get('moyen_transport')" class="mt-2" />
                </div>
            </div>

            <div>
                <x-input-label for="motif" value="Motif du déplacement *" />
                <x-text-input id="motif" name="motif" class="block mt-1 w-full" :value="old('motif')" required placeholder="Ex : Rendez-vous client, mission d'audit…" />
                <x-input-error :messages="$errors->get('motif')" class="mt-2" />
            </div>

            <div>
                <x-input-label for="cout_estime" value="Coût estimé (FCFA) *" />
                <x-text-input id="cout_estime" name="cout_estime" type="number" min="0" step="100" class="block mt-1 w-full" :value="old('cout_estime')" required placeholder="Ex : 8500" />
                <x-input-error :messages="$errors->get('cout_estime')" class="mt-2" />
            </div>

            <div>
                <x-input-label for="justificatif" value="Justificatif (optionnel)" />
                <input id="justificatif" name="justificatif" type="file" accept=".jpg,.jpeg,.png,.pdf" class="w-full rounded-lg border border-slate-300 px-3.5 py-2 text-sm outline-none focus:border-cofima focus:ring-2 focus:ring-cofima/20 mt-1" />
                <p class="mt-1 text-xs text-steel">JPG, PNG ou PDF, 5 Mo maximum.</p>
                <x-input-error :messages="$errors->get('justificatif')" class="mt-2" />
            </div>

            <div>
                <x-input-label for="commentaire" value="Commentaire (optionnel)" />
                <textarea id="commentaire" name="commentaire" rows="2" class="w-full rounded-lg border border-slate-300 px-3.5 py-2.5 text-sm outline-none focus:border-cofima focus:ring-2 focus:ring-cofima/20 mt-1" placeholder="Précisions éventuelles…">{{ old('commentaire') }}</textarea>
                <x-input-error :messages="$errors->get('commentaire')" class="mt-2" />
            </div>

            <div class="flex items-center justify-end gap-3 pt-2">
                <x-secondary-button type="button" x-on:click="$dispatch('close-modal', 'nouvelle-demande')">
                    Annuler
                </x-secondary-button>
                <x-primary-button>
                    Soumettre la demande
                </x-primary-button>
            </div>
        </form>
    </x-modal>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                new DataTable('#demandes-table', {
                    order: [[0, 'desc']],
                    language: window.dtFrench,
                    responsive: true,
                });

                if (document.getElementById('historique-table')) {
                    new DataTable('#historique-table', {
                        order: [[0, 'desc']],
                        language: window.dtFrench,
                        responsive: true,
                    });
                }

                $('.js-select2').select2({
                    width: '100%',
                    placeholder: 'Sélectionner…',
                    language: {
                        noResults: () => 'Aucun résultat trouvé',
                    },
                });
            });
        </script>
    @endpush
</x-app-layout>
