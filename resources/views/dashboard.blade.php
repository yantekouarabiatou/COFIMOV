
<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
                        <div>
                <h2 class="text-base sm:text-lg font-bold text-white">Tableau de bord</h2>
                <p class="text-xs text-white">Vue d&apos;ensemble de vos demandes</p>
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
            @elseif (session('status') === 'demande-modifiee')
                <div class="rounded-lg bg-emerald-50 border border-emerald-200 px-4 py-3 text-sm text-emerald-700 flex items-center gap-2">
                    <svg class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" /></svg>
                    Votre demande a été modifiée avec succès.
                </div>
            @elseif (session('status') === 'demande-annulee')
                <div class="rounded-lg bg-slate-50 border border-slate-200 px-4 py-3 text-sm text-slate-700 flex items-center gap-2">
                    <svg class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" /></svg>
                    Votre demande a été annulée.
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
                <div class="px-5 py-4 border-b border-slate-100 flex flex-wrap items-center justify-between gap-3">
                    <div class="flex items-center gap-3">
                        <h2 class="font-semibold text-cofima">Mes demandes</h2>
                        <span class="text-xs text-steel">{{ $demandes->count() }} demande(s)</span>
                    </div>
                    <div class="flex flex-wrap items-center gap-2">
                        <div class="flex items-center gap-1.5 rounded-lg border border-slate-200 bg-slate-50 px-2 py-1.5">
                            <svg class="h-4 w-4 text-steel shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5" /></svg>
                            <input type="date" id="demandes-du" class="rounded-md border border-slate-300 bg-white px-2 py-1 text-xs outline-none focus:border-cofima focus:ring-2 focus:ring-cofima/20">
                            <span class="text-steel text-xs">→</span>
                            <input type="date" id="demandes-au" class="rounded-md border border-slate-300 bg-white px-2 py-1 text-xs outline-none focus:border-cofima focus:ring-2 focus:ring-cofima/20">
                            <button type="button" id="demandes-reset" title="Réinitialiser la période" class="ml-0.5 rounded-md p-1 text-steel hover:text-cofima hover:bg-white transition-colors">
                                <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" /></svg>
                            </button>
                        </div>
                        <a href="{{ route('demandes.export.pdf') }}" id="demandes-export-pdf" class="inline-flex items-center gap-1.5 rounded-lg border border-slate-300 bg-white px-3 py-1.5 text-xs font-semibold text-slate-700 hover:bg-slate-100 hover:border-slate-400 transition-colors">
                            <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" /></svg>
                            PDF
                        </a>
                        <a href="{{ route('demandes.export.excel') }}" id="demandes-export-excel" class="inline-flex items-center gap-1.5 rounded-lg border border-emerald-300 bg-emerald-50 px-3 py-1.5 text-xs font-semibold text-emerald-700 hover:bg-emerald-100 hover:border-emerald-400 transition-colors">
                            <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 17.25v1.007a3 3 0 0 1-.879 2.122L7.5 21h9l-.621-.621A3 3 0 0 1 15 18.257V17.25m6-12V15a2.25 2.25 0 0 1-2.25 2.25H5.25A2.25 2.25 0 0 1 3 15V5.25m18 0A2.25 2.25 0 0 0 18.75 3H5.25A2.25 2.25 0 0 0 3 5.25m18 0V12a2.25 2.25 0 0 1-2.25 2.25H5.25A2.25 2.25 0 0 1 3 12V5.25" /></svg>
                            Excel
                        </a>
                    </div>
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
                                <th class="dt-orderable-false">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($demandes as $demande)
                                <tr data-date="{{ $demande->date_debut->format('Y-m-d') }}">
                                    <td data-order="{{ $demande->date_debut->format('Y-m-d') }}" class="whitespace-nowrap text-slate-700">
                                        @if ($demande->date_debut->isSameDay($demande->date_fin))
                                            {{ $demande->date_debut->format('d/m/Y') }}
                                        @else
                                            {{ $demande->date_debut->format('d/m/Y') }} - {{ $demande->date_fin->format('d/m/Y') }}
                                        @endif
                                    </td>
                                    <td class="text-slate-700 max-w-[16rem] truncate" title="{{ $demande->trajets->map(fn ($t) => $t->lieu_depart.' → '.$t->lieu_arrivee)->implode(', ') }}">
                                        {{ $demande->trajets->map(fn ($t) => $t->lieu_depart.' → '.$t->lieu_arrivee)->implode(', ') }}
                                        @if ($demande->trajets->count() > 1)
                                            <span class="text-xs text-steel">({{ $demande->trajets->count() }} trajets)</span>
                                        @endif
                                    </td>
                                    <td class="text-slate-700">{{ $demande->trajets->pluck('moyen_transport')->unique()->implode(', ') }}</td>
                                    <td data-order="{{ $demande->cout_estime }}" class="whitespace-nowrap font-medium text-cofima">{{ number_format($demande->cout_estime, 0, ',', ' ') }} FCFA</td>
                                    <td>
                                        @forelse ($demande->justificatifs as $justificatif)
                                            <a href="{{ asset('storage/'.$justificatif->chemin) }}" target="_blank" class="inline-flex items-center gap-1 rounded-lg border border-cofima/30 bg-cofima/5 px-2.5 py-1 text-xs font-semibold text-cofima hover:bg-cofima/10 hover:border-cofima/50 transition-colors">
                                                <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" /><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" /></svg>
                                                Voir
                                            </a>
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
                                            'bg-slate-100 text-slate-600' => $demande->statut === 'annulee',
                                        ])>
                                            {{ match ($demande->statut) {
                                                'en_attente' => 'En attente',
                                                'validee' => 'Validée',
                                                'rejetee' => 'Rejetée',
                                                'annulee' => 'Annulée',
                                            } }}
                                        </span>
                                    </td>
                                    <td>
                                        <a href="{{ route('demandes.pdf', $demande) }}" class="inline-flex items-center gap-1 text-cofima hover:underline">
                                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 3v12m0 0 4-4m-4 4-4-4M4 17v2a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2v-2" /></svg>
                                            PDF
                                        </a>
                                    </td>
                                    <td>
                                        @if ($demande->statut === 'en_attente')
                                            <div class="flex items-center gap-1.5" x-data="">
                                                <button type="button" x-on:click="$dispatch('open-modal', 'modifier-{{ $demande->id }}')" class="inline-flex items-center gap-1 rounded-lg border border-slate-300 bg-white px-2.5 py-1 text-xs font-semibold text-slate-700 hover:bg-slate-100 hover:border-slate-400 transition-colors">
                                                    <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125" /></svg>
                                                    Modifier
                                                </button>
                                                <button type="button" x-on:click="$dispatch('open-modal', 'annuler-{{ $demande->id }}')" class="inline-flex items-center gap-1 rounded-lg border border-red-300 bg-red-50 px-2.5 py-1 text-xs font-semibold text-red-700 hover:bg-red-100 hover:border-red-400 transition-colors">
                                                    <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" /></svg>
                                                    Annuler
                                                </button>
                                            </div>
                                        @else
                                            <span class="text-steel">—</span>
                                        @endif
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
                    <h2 class="font-semibold text-cofima">Historique des validations / rejets</h2>
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

        <form method="POST" action="{{ route('demandes.store') }}" enctype="multipart/form-data" class="px-6 py-5 space-y-4"
              x-data="trajetsForm({{ old('trajets') ? json_encode(old('trajets')) : 'null' }})">
            @csrf

            <div>
                <div class="flex items-center justify-between mb-2">
                    <x-input-label value="Trajets *" class="mb-0" />
                    <button type="button" x-on:click="addTrajet()" class="text-xs font-semibold text-cofima hover:text-cofima-dark inline-flex items-center gap-1">
                        <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" /></svg>
                        Ajouter un trajet
                    </button>
                </div>

                <template x-for="(trajet, index) in trajets" :key="trajet.id">
                    <div class="rounded-lg border border-slate-200 p-4 mb-3 relative">
                        <button type="button" x-show="trajets.length > 1" x-on:click="removeTrajet(trajet.id)"
                                class="absolute top-2 right-2 text-steel hover:text-red-600 text-lg leading-none">&times;</button>

                        <p class="text-xs font-semibold text-steel mb-3">Trajet <span x-text="index + 1"></span></p>

                        <div class="grid gap-3 sm:grid-cols-2">
                            <div>
                                <x-input-label value="Lieu de départ *" class="text-xs" />
                                <input type="text" :name="`trajets[${trajet.id}][lieu_depart]`" x-model="trajet.lieu_depart" required placeholder="Ex : COFIMA"
                                       class="w-full rounded-lg border border-slate-300 px-3.5 py-2 text-sm outline-none focus:border-cofima focus:ring-2 focus:ring-cofima/20 mt-1" />
                            </div>
                            <div>
                                <x-input-label value="Lieu d'arrivée *" class="text-xs" />
                                <input type="text" :name="`trajets[${trajet.id}][lieu_arrivee]`" x-model="trajet.lieu_arrivee" required placeholder="Ex : Godomey"
                                       class="w-full rounded-lg border border-slate-300 px-3.5 py-2 text-sm outline-none focus:border-cofima focus:ring-2 focus:ring-cofima/20 mt-1" />
                            </div>
                        </div>

                        <div class="grid gap-3 sm:grid-cols-3 mt-3">
                            <div>
                                <x-input-label value="Date *" class="text-xs" />
                                <input type="date" :name="`trajets[${trajet.id}][date_deplacement]`" x-model="trajet.date_deplacement" required
                                       class="w-full rounded-lg border border-slate-300 px-3.5 py-2 text-sm outline-none focus:border-cofima focus:ring-2 focus:ring-cofima/20 mt-1" />
                            </div>
                            <div>
                                <x-input-label value="Moyen de transport *" class="text-xs" />
                                <select :name="`trajets[${trajet.id}][moyen_transport]`" x-model="trajet.moyen_transport" required
                                        class="js-select2-trajet w-full mt-1">
                                    <option value="">Sélectionner…</option>
                                    @foreach (['Taxi', 'Moto', 'Véhicule personnel', 'Location', 'Autre'] as $option)
                                        <option value="{{ $option }}">{{ $option }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <x-input-label value="Coût estimé (FCFA) *" class="text-xs" />
                                <input type="number" min="0" step="100" :name="`trajets[${trajet.id}][cout_estime]`" x-model="trajet.cout_estime" required placeholder="Ex : 8500"
                                       class="w-full rounded-lg border border-slate-300 px-3.5 py-2 text-sm outline-none focus:border-cofima focus:ring-2 focus:ring-cofima/20 mt-1" />
                            </div>
                        </div>
                    </div>
                </template>

                <x-input-error :messages="$errors->get('trajets')" class="mt-2" />

                <div class="flex items-center justify-end gap-2 text-sm mt-2 pt-2 border-t border-slate-100">
                    <span class="text-steel">Coût total estimé :</span>
                    <span class="font-bold text-cofima" x-text="formatFCFA(total)"></span>
                </div>
            </div>

            <div>
                <x-input-label for="motif" value="Motif du déplacement *" />
                <x-text-input id="motif" name="motif" class="block mt-1 w-full" :value="old('motif')" required placeholder="Ex : Rendez-vous client, mission d'audit…" />
                <x-input-error :messages="$errors->get('motif')" class="mt-2" />
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

    <!-- Modales modifier / annuler (une par demande en attente) -->
    @foreach ($demandes->where('statut', 'en_attente') as $demande)
        <x-modal name="modifier-{{ $demande->id }}" :show="$errors->any() && (int) old('_edit_demande') === $demande->id" focusable>
            <div class="flex items-center justify-between border-b border-slate-100 px-6 py-4">
                <h2 class="text-lg font-bold text-slate-800">Modifier la demande</h2>
                <button type="button" x-on:click="$dispatch('close-modal', 'modifier-{{ $demande->id }}')" class="text-steel hover:text-slate-800 text-2xl leading-none">&times;</button>
            </div>

            <form method="POST" action="{{ route('demandes.update', $demande) }}" enctype="multipart/form-data" class="px-6 py-5 space-y-4"
                  x-data="trajetsForm({{ (int) old('_edit_demande') === $demande->id && old('trajets') ? json_encode(old('trajets')) : json_encode($demande->trajets->map(fn ($t) => [
                      'lieu_depart' => $t->lieu_depart,
                      'lieu_arrivee' => $t->lieu_arrivee,
                      'date_deplacement' => $t->date_deplacement->format('Y-m-d'),
                      'moyen_transport' => $t->moyen_transport,
                      'cout_estime' => (string) $t->cout_estime,
                  ])->values()) }})">
                @csrf
                @method('PUT')
                <input type="hidden" name="_edit_demande" value="{{ $demande->id }}">

                <div>
                    <div class="flex items-center justify-between mb-2">
                        <x-input-label value="Trajets *" class="mb-0" />
                        <button type="button" x-on:click="addTrajet()" class="text-xs font-semibold text-cofima hover:text-cofima-dark inline-flex items-center gap-1">
                            <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" /></svg>
                            Ajouter un trajet
                        </button>
                    </div>

                    <template x-for="(trajet, index) in trajets" :key="trajet.id">
                        <div class="rounded-lg border border-slate-200 p-4 mb-3 relative">
                            <button type="button" x-show="trajets.length > 1" x-on:click="removeTrajet(trajet.id)"
                                    class="absolute top-2 right-2 text-steel hover:text-red-600 text-lg leading-none">&times;</button>

                            <p class="text-xs font-semibold text-steel mb-3">Trajet <span x-text="index + 1"></span></p>

                            <div class="grid gap-3 sm:grid-cols-2">
                                <div>
                                    <x-input-label value="Lieu de départ *" class="text-xs" />
                                    <input type="text" :name="`trajets[${trajet.id}][lieu_depart]`" x-model="trajet.lieu_depart" required placeholder="Ex : COFIMA"
                                           class="w-full rounded-lg border border-slate-300 px-3.5 py-2 text-sm outline-none focus:border-cofima focus:ring-2 focus:ring-cofima/20 mt-1" />
                                </div>
                                <div>
                                    <x-input-label value="Lieu d'arrivée *" class="text-xs" />
                                    <input type="text" :name="`trajets[${trajet.id}][lieu_arrivee]`" x-model="trajet.lieu_arrivee" required placeholder="Ex : Godomey"
                                           class="w-full rounded-lg border border-slate-300 px-3.5 py-2 text-sm outline-none focus:border-cofima focus:ring-2 focus:ring-cofima/20 mt-1" />
                                </div>
                            </div>

                            <div class="grid gap-3 sm:grid-cols-3 mt-3">
                                <div>
                                    <x-input-label value="Date *" class="text-xs" />
                                    <input type="date" :name="`trajets[${trajet.id}][date_deplacement]`" x-model="trajet.date_deplacement" required
                                           class="w-full rounded-lg border border-slate-300 px-3.5 py-2 text-sm outline-none focus:border-cofima focus:ring-2 focus:ring-cofima/20 mt-1" />
                                </div>
                                <div>
                                    <x-input-label value="Moyen de transport *" class="text-xs" />
                                    <select :name="`trajets[${trajet.id}][moyen_transport]`" x-model="trajet.moyen_transport" required
                                            class="js-select2-trajet w-full mt-1">
                                        <option value="">Sélectionner…</option>
                                        @foreach (['Taxi', 'Moto', 'Véhicule personnel', 'Location', 'Autre'] as $option)
                                            <option value="{{ $option }}">{{ $option }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <x-input-label value="Coût estimé (FCFA) *" class="text-xs" />
                                    <input type="number" min="0" step="100" :name="`trajets[${trajet.id}][cout_estime]`" x-model="trajet.cout_estime" required placeholder="Ex : 8500"
                                           class="w-full rounded-lg border border-slate-300 px-3.5 py-2 text-sm outline-none focus:border-cofima focus:ring-2 focus:ring-cofima/20 mt-1" />
                                </div>
                            </div>
                        </div>
                    </template>

                    <x-input-error :messages="$errors->get('trajets')" class="mt-2" />

                    <div class="flex items-center justify-end gap-2 text-sm mt-2 pt-2 border-t border-slate-100">
                        <span class="text-steel">Coût total estimé :</span>
                        <span class="font-bold text-cofima" x-text="formatFCFA(total)"></span>
                    </div>
                </div>

                <div>
                    <x-input-label for="motif-{{ $demande->id }}" value="Motif du déplacement *" />
                    <x-text-input id="motif-{{ $demande->id }}" name="motif" class="block mt-1 w-full" :value="(int) old('_edit_demande') === $demande->id ? old('motif') : $demande->motif" required placeholder="Ex : Rendez-vous client, mission d'audit…" />
                    <x-input-error :messages="$errors->get('motif')" class="mt-2" />
                </div>

                <div>
                    <x-input-label for="justificatif-{{ $demande->id }}" value="Ajouter un justificatif (optionnel)" />
                    <input id="justificatif-{{ $demande->id }}" name="justificatif" type="file" accept=".jpg,.jpeg,.png,.pdf" class="w-full rounded-lg border border-slate-300 px-3.5 py-2 text-sm outline-none focus:border-cofima focus:ring-2 focus:ring-cofima/20 mt-1" />
                    <p class="mt-1 text-xs text-steel">JPG, PNG ou PDF, 5 Mo maximum. Les justificatifs déjà envoyés restent conservés.</p>
                    <x-input-error :messages="$errors->get('justificatif')" class="mt-2" />
                </div>

                <div>
                    <x-input-label for="commentaire-{{ $demande->id }}" value="Commentaire (optionnel)" />
                    <textarea id="commentaire-{{ $demande->id }}" name="commentaire" rows="2" class="w-full rounded-lg border border-slate-300 px-3.5 py-2.5 text-sm outline-none focus:border-cofima focus:ring-2 focus:ring-cofima/20 mt-1" placeholder="Précisions éventuelles…">{{ (int) old('_edit_demande') === $demande->id ? old('commentaire') : $demande->commentaire }}</textarea>
                    <x-input-error :messages="$errors->get('commentaire')" class="mt-2" />
                </div>

                <div class="flex items-center justify-end gap-3 pt-2">
                    <x-secondary-button type="button" x-on:click="$dispatch('close-modal', 'modifier-{{ $demande->id }}')">
                        Annuler
                    </x-secondary-button>
                    <x-primary-button>
                        Enregistrer les modifications
                    </x-primary-button>
                </div>
            </form>
        </x-modal>

        <x-modal name="annuler-{{ $demande->id }}" maxWidth="md">
            <div class="flex items-center justify-between border-b border-slate-100 px-6 py-4">
                <h2 class="text-lg font-bold text-slate-800">Annuler la demande</h2>
                <button type="button" x-on:click="$dispatch('close-modal', 'annuler-{{ $demande->id }}')" class="text-steel hover:text-slate-800 text-2xl leading-none">&times;</button>
            </div>
            <div class="px-6 py-5">
                <p class="text-sm text-steel">
                    Êtes-vous sûr de vouloir annuler la demande
                    « {{ $demande->trajets->map(fn ($t) => $t->lieu_depart.' → '.$t->lieu_arrivee)->implode(', ') }} » ?
                    Cette action est irréversible.
                </p>
                <form method="POST" action="{{ route('demandes.annuler', $demande) }}" class="flex items-center justify-end gap-3 mt-5">
                    @csrf
                    <x-secondary-button type="button" x-on:click="$dispatch('close-modal', 'annuler-{{ $demande->id }}')">
                        Retour
                    </x-secondary-button>
                    <button type="submit" class="rounded-lg bg-red-600 px-5 py-2.5 text-sm font-semibold text-white hover:bg-red-700 transition-colors">
                        Confirmer l&apos;annulation
                    </button>
                </form>
            </div>
        </x-modal>
    @endforeach

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                window.initPeriodFilter('demandes-table', 'demandes-du', 'demandes-au', 'demandes-reset');
                window.wireExportLinks('demandes-du', 'demandes-au', ['demandes-export-pdf', 'demandes-export-excel']);

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

                window.initTrajetSelect2();
            });
        </script>
    @endpush
</x-app-layout>
