<x-app-layout>
    <x-slot name="header">
        <div>
            <h2 class="text-base sm:text-lg font-bold text-slate-800">Validation des demandes</h2>
            <p class="text-xs text-steel">Demandes de frais de transport en attente</p>
        </div>
    </x-slot>

    <div class="py-6 sm:py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            @if (session('status') === 'demande-validee')
                <div class="rounded-lg bg-emerald-50 border border-emerald-200 px-4 py-3 text-sm text-emerald-700 flex items-center gap-2">
                    <svg class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" /></svg>
                    Demande validée avec succès.
                </div>
            @elseif (session('status') === 'demande-rejetee')
                <div class="rounded-lg bg-red-50 border border-red-200 px-4 py-3 text-sm text-red-700 flex items-center gap-2">
                    <svg class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" /></svg>
                    Demande rejetée.
                </div>
            @endif

            <div class="rounded-xl border border-slate-200 bg-white overflow-hidden">
                <div class="px-5 py-4 border-b border-slate-100 flex flex-wrap items-center justify-between gap-3">
                    <div class="flex items-center gap-3">
                        <h2 class="font-semibold text-slate-800">Demandes en attente</h2>
                        <span class="text-xs text-steel">{{ $demandes->count() }} demande(s)</span>
                    </div>
                    <div class="flex flex-wrap items-center gap-2">
                        <div class="flex items-center gap-1.5 rounded-lg border border-slate-200 bg-slate-50 px-2 py-1.5">
                            <svg class="h-4 w-4 text-steel shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5" /></svg>
                            <input type="date" id="validation-du" class="rounded-md border border-slate-300 bg-white px-2 py-1 text-xs outline-none focus:border-cofima focus:ring-2 focus:ring-cofima/20">
                            <span class="text-steel text-xs">→</span>
                            <input type="date" id="validation-au" class="rounded-md border border-slate-300 bg-white px-2 py-1 text-xs outline-none focus:border-cofima focus:ring-2 focus:ring-cofima/20">
                            <button type="button" id="validation-reset" title="Réinitialiser la période" class="ml-0.5 rounded-md p-1 text-steel hover:text-cofima hover:bg-white transition-colors">
                                <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" /></svg>
                            </button>
                        </div>
                        <a href="{{ route('validation.export.pdf') }}" id="validation-export-pdf" title="Exporter toutes les demandes en PDF" class="inline-flex items-center gap-1.5 rounded-lg border border-slate-300 bg-white px-3 py-1.5 text-xs font-semibold text-slate-700 hover:bg-slate-100 hover:border-slate-400 transition-colors">
                            <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" /></svg>
                            PDF
                        </a>
                        <a href="{{ route('validation.export.excel') }}" id="validation-export-excel" title="Exporter toutes les demandes en Excel" class="inline-flex items-center gap-1.5 rounded-lg border border-emerald-300 bg-emerald-50 px-3 py-1.5 text-xs font-semibold text-emerald-700 hover:bg-emerald-100 hover:border-emerald-400 transition-colors">
                            <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 17.25v1.007a3 3 0 0 1-.879 2.122L7.5 21h9l-.621-.621A3 3 0 0 1 15 18.257V17.25m6-12V15a2.25 2.25 0 0 1-2.25 2.25H5.25A2.25 2.25 0 0 1 3 15V5.25m18 0A2.25 2.25 0 0 0 18.75 3H5.25A2.25 2.25 0 0 0 3 5.25m18 0V12a2.25 2.25 0 0 1-2.25 2.25H5.25A2.25 2.25 0 0 1 3 12V5.25" /></svg>
                            Excel
                        </a>
                    </div>
                </div>

                <div class="overflow-x-auto p-5">
                    <table id="validation-table" class="w-full text-sm">
                        <thead>
                            <tr>
                                <th>Collaborateur</th>
                                <th>Trajet</th>
                                <th>Date</th>
                                <th>Transport</th>
                                <th>Coût</th>
                                <th>Motif</th>
                                <th>Justificatif</th>
                                <th class="dt-orderable-false">Lettre</th>
                                <th class="dt-orderable-false">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($demandes as $demande)
                                <tr data-date="{{ $demande->date_debut->format('Y-m-d') }}">
                                    <td>
                                        <p class="font-medium text-slate-800">{{ $demande->user->full_name }}</p>
                                        <p class="text-xs text-steel">{{ $demande->user->email }}</p>
                                    </td>
                                    <td class="text-slate-700 max-w-[16rem] truncate" title="{{ $demande->trajets->map(fn ($t) => $t->lieu_depart.' → '.$t->lieu_arrivee)->implode(', ') }}">
                                        {{ $demande->trajets->map(fn ($t) => $t->lieu_depart.' → '.$t->lieu_arrivee)->implode(', ') }}
                                        @if ($demande->trajets->count() > 1)
                                            <span class="text-xs text-steel">({{ $demande->trajets->count() }} trajets)</span>
                                        @endif
                                    </td>
                                    <td data-order="{{ $demande->date_debut->format('Y-m-d') }}" class="whitespace-nowrap text-slate-700">
                                        @if ($demande->date_debut->isSameDay($demande->date_fin))
                                            {{ $demande->date_debut->format('d/m/Y') }}
                                        @else
                                            {{ $demande->date_debut->format('d/m/Y') }} - {{ $demande->date_fin->format('d/m/Y') }}
                                        @endif
                                    </td>
                                    <td class="text-slate-700">{{ $demande->trajets->pluck('moyen_transport')->unique()->implode(', ') }}</td>
                                    <td data-order="{{ $demande->cout_estime }}" class="whitespace-nowrap font-semibold text-cofima">{{ number_format($demande->cout_estime, 0, ',', ' ') }} FCFA</td>
                                    <td class="text-slate-700 max-w-[16rem] truncate" title="{{ $demande->motif }}">{{ $demande->motif }}</td>
                                    <td>
                                        @forelse ($demande->justificatifs as $justificatif)
                                            <a href="{{ asset('storage/'.$justificatif->chemin) }}" target="_blank" class="text-cofima hover:underline">Voir</a>
                                        @empty
                                            <span class="text-steel">—</span>
                                        @endforelse
                                    </td>
                                    <td>
                                        <a href="{{ route('demandes.pdf', $demande) }}" class="inline-flex items-center gap-1 text-cofima hover:underline">
                                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 3v12m0 0 4-4m-4 4-4-4M4 17v2a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2v-2" /></svg>
                                            PDF
                                        </a>
                                    </td>
                                    <td>
                                        <div class="flex items-center gap-2" x-data="">
                                            <form method="POST" action="{{ route('validation.valider', $demande) }}">
                                                @csrf
                                                <button type="submit" class="rounded-lg bg-emerald-600 px-3 py-1.5 text-xs font-semibold text-white hover:bg-emerald-700 transition-colors">
                                                    Valider
                                                </button>
                                            </form>
                                            <button type="button" x-on:click="$dispatch('open-modal', 'rejeter-{{ $demande->id }}')" class="rounded-lg border border-red-300 px-3 py-1.5 text-xs font-semibold text-red-600 hover:bg-red-50 transition-colors">
                                                Rejeter
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    @foreach ($demandes as $demande)
        <x-modal name="rejeter-{{ $demande->id }}" maxWidth="md">
            <div class="flex items-center justify-between border-b border-slate-100 px-6 py-4">
                <h2 class="text-lg font-bold text-slate-800">Rejeter la demande</h2>
                <button type="button" x-on:click="$dispatch('close-modal', 'rejeter-{{ $demande->id }}')" class="text-steel hover:text-slate-800 text-2xl leading-none">&times;</button>
            </div>

            <form method="POST" action="{{ route('validation.rejeter', $demande) }}" class="px-6 py-5 space-y-4">
                @csrf
                <p class="text-sm text-steel">
                    Demande de <span class="font-medium text-slate-800">{{ $demande->user->full_name }}</span>
                    ({{ $demande->trajets->map(fn ($t) => $t->lieu_depart.' → '.$t->lieu_arrivee)->implode(', ') }})
                </p>
                <div>
                    <x-input-label for="motif_rejet_{{ $demande->id }}" value="Motif du rejet *" />
                    <textarea id="motif_rejet_{{ $demande->id }}" name="motif_rejet" rows="3" required class="w-full rounded-lg border border-slate-300 px-3.5 py-2.5 text-sm outline-none focus:border-cofima focus:ring-2 focus:ring-cofima/20 mt-1" placeholder="Expliquez la raison du rejet…"></textarea>
                </div>
                <div class="flex items-center justify-end gap-3 pt-2">
                    <x-secondary-button type="button" x-on:click="$dispatch('close-modal', 'rejeter-{{ $demande->id }}')">
                        Annuler
                    </x-secondary-button>
                    <button type="submit" class="rounded-lg bg-red-600 px-5 py-2.5 text-sm font-semibold text-white hover:bg-red-700 transition-colors">
                        Confirmer le rejet
                    </button>
                </div>
            </form>
        </x-modal>
    @endforeach

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                window.initPeriodFilter('validation-table', 'validation-du', 'validation-au', 'validation-reset');
                window.wireExportLinks('validation-du', 'validation-au', ['validation-export-pdf', 'validation-export-excel']);

                new DataTable('#validation-table', {
                    order: [[2, 'asc']],
                    language: window.dtFrench,
                    columnDefs: [{ targets: 'dt-orderable-false', orderable: false }],
                    responsive: true,
                });
            });
        </script>
    @endpush
</x-app-layout>
