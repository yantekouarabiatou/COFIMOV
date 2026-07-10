<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>{{ config('app.name', 'COFIMA') }} — Gestion des frais de transport</title>
        <meta name="description" content="Application interne COFIMA pour la gestion des demandes de frais de transport." />
        <link rel="icon" type="image/png" href="{{ asset('logocofima.png') }}">

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="bg-slate-50 text-slate-800 antialiased font-sans">
        <div class="min-h-screen flex flex-col">
            <!-- Header -->
            <header class="border-b border-slate-200 bg-white/80 backdrop-blur sticky top-0 z-20">
                <div class="mx-auto max-w-6xl px-4 sm:px-6 h-20 flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <x-application-logo />
                        <div class="leading-tight">
                            <p class="text-xl font-extrabold tracking-tight text-cofima">COFIMA</p>
                            <p class="text-[9px] sm:text-[10px] font-medium tracking-wide text-steel uppercase">Compagnie Fiduciaire de Management et d&apos;Audit</p>
                        </div>
                    </div>

                    @auth
                        <a href="{{ url('/dashboard') }}" class="rounded-lg bg-cofima px-5 py-2.5 text-sm font-semibold text-white hover:bg-cofima-dark transition-colors shadow-sm hover:shadow">
                            Tableau de bord
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="rounded-lg bg-cofima px-5 py-2.5 text-sm font-semibold text-white hover:bg-cofima-dark transition-colors shadow-sm hover:shadow">
                            Se connecter
                        </a>
                    @endauth
                </div>
            </header>

            <!-- Hero -->
            <main class="flex-1">
                <div class="relative overflow-hidden">
                    <!-- Decorative animated blobs -->
                    <div class="pointer-events-none absolute inset-0 overflow-hidden">
                        <div class="animate-blob absolute -top-24 -left-24 h-72 w-72 rounded-full bg-cofima/10 blur-3xl"></div>
                        <div class="animate-blob absolute top-1/3 -right-24 h-80 w-80 rounded-full bg-cofima-light/10 blur-3xl" style="animation-delay: 3s"></div>
                        <div class="animate-blob absolute -bottom-24 left-1/3 h-64 w-64 rounded-full bg-amber-200/20 blur-3xl" style="animation-delay: 6s"></div>
                    </div>

                    <div class="relative mx-auto max-w-6xl px-4 sm:px-6 py-16 sm:py-24 grid gap-12 lg:grid-cols-2 lg:items-center">
                        <div class="animate-fade-in-up">
                            <span class="inline-block rounded-full bg-cofima/10 px-3 py-1 text-xs font-semibold text-cofima mb-5">Espace interne collaborateurs</span>
                            <h1 class="text-4xl sm:text-5xl font-extrabold tracking-tight text-cofima leading-[1.1]">
                                Gestion des demandes de frais de transport
                            </h1>
                            <p class="mt-6 text-base sm:text-lg text-steel leading-relaxed max-w-xl">
                                Soumettez, suivez et centralisez vos demandes de frais de déplacement.
                                Une solution simple et transparente pour les collaborateurs de COFIMA, du dépôt de la demande jusqu&apos;à sa validation par la Direction Générale.
                            </p>
                            <div class="mt-8 flex flex-wrap gap-3">
                                <a href="{{ auth()->check() ? url('/dashboard') : route('login') }}" class="rounded-lg bg-cofima px-6 py-3 text-sm font-semibold text-white hover:bg-cofima-dark transition-all shadow-sm hover:shadow-lg hover:-translate-y-0.5">
                                    Accéder à mon espace
                                </a>
                                <a href="#process" class="rounded-lg border border-slate-300 px-6 py-3 text-sm font-semibold text-slate-700 hover:bg-slate-100 hover:border-slate-400 transition-all">
                                    En savoir plus
                                </a>
                            </div>

                            <!-- Live stats -->
                            <div x-data="{
                                    collaborateurs: 0, demandes: 0, montant: 0,
                                    countUp(prop, target, duration = 900) {
                                        const start = performance.now();
                                        const step = (now) => {
                                            const progress = Math.min(1, (now - start) / duration);
                                            this[prop] = Math.floor(progress * target);
                                            if (progress < 1) requestAnimationFrame(step);
                                        };
                                        requestAnimationFrame(step);
                                    },
                                }"
                                x-init="
                                    countUp('collaborateurs', {{ $stats['collaborateurs'] }});
                                    countUp('demandes', {{ $stats['demandes_traitees'] }});
                                    countUp('montant', {{ (int) $stats['montant_rembourse'] }}, 1200);
                                "
                                class="mt-10 grid grid-cols-3 gap-4 max-w-lg"
                            >
                                <div>
                                    <p class="text-2xl font-extrabold text-cofima" x-text="collaborateurs"></p>
                                    <p class="text-xs text-steel">Collaborateurs actifs</p>
                                </div>
                                <div>
                                    <p class="text-2xl font-extrabold text-cofima" x-text="demandes"></p>
                                    <p class="text-xs text-steel">Demandes traitées</p>
                                </div>
                                <div>
                                    <p class="text-2xl font-extrabold text-cofima" x-text="montant.toLocaleString('fr-FR')"></p>
                                    <p class="text-xs text-steel">FCFA remboursés</p>
                                </div>
                            </div>
                        </div>

                        <!-- Illustration card -->
                        <div class="relative animate-fade-in-up" style="animation-delay: 0.15s">
                            <div class="animate-float rounded-2xl border border-slate-200 bg-white p-6 shadow-lg">
                                <div class="flex items-center justify-between border-b border-slate-100 pb-4">
                                    <p class="text-sm font-semibold text-cofima">Aperçu d&apos;une demande</p>
                                    <span class="relative rounded-full bg-amber-100 px-3 py-1 text-xs font-semibold text-amber-700">
                                        <span class="absolute -left-0.5 -top-0.5 h-2 w-2 rounded-full bg-amber-400 animate-ping"></span>
                                        En attente
                                    </span>
                                </div>
                                <dl class="mt-4 space-y-3 text-sm">
                                    <div class="flex justify-between"><dt class="text-steel">Trajet</dt><dd class="font-medium text-slate-800">Plateau → Cocody</dd></div>
                                    <div class="flex justify-between"><dt class="text-steel">Date</dt><dd class="font-medium text-slate-800">12/06/2026</dd></div>
                                    <div class="flex justify-between"><dt class="text-steel">Transport</dt><dd class="font-medium text-slate-800">Taxi</dd></div>
                                    <div class="flex justify-between"><dt class="text-steel">Coût estimé</dt><dd class="font-semibold text-cofima">8 500 FCFA</dd></div>
                                </dl>
                            </div>
                            <div class="absolute -bottom-6 -left-3 rounded-xl border border-slate-200 bg-cofima px-5 py-3 shadow-lg hidden sm:block">
                                <p class="text-xs text-white/70">Notification automatique</p>
                                <p class="text-sm font-semibold text-white">Direction Générale &amp; Secrétariat</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Process -->
                <div id="process" class="border-t border-slate-200 bg-white">
                    <div class="mx-auto max-w-6xl px-4 sm:px-6 py-16">
                        <h2 class="text-2xl font-bold text-cofima text-center">Un processus en 3 étapes</h2>
                        <p class="mt-2 text-sm text-steel text-center">Simple, rapide et transparent, du dépôt à la validation.</p>
                        <div class="mt-10 grid gap-6 sm:grid-cols-3">
                            <div class="group rounded-xl border border-slate-200 p-6 transition-all hover:-translate-y-1 hover:shadow-lg hover:border-cofima/30">
                                <div class="h-9 w-9 rounded-lg bg-cofima/10 flex items-center justify-center font-bold text-cofima transition-colors group-hover:bg-cofima group-hover:text-white">1</div>
                                <h3 class="mt-4 font-semibold text-slate-800">Créez votre demande</h3>
                                <p class="mt-2 text-sm text-steel leading-relaxed">Renseignez le trajet, le motif, le moyen de transport et le coût estimé.</p>
                            </div>
                            <div class="group rounded-xl border border-slate-200 p-6 transition-all hover:-translate-y-1 hover:shadow-lg hover:border-cofima/30">
                                <div class="h-9 w-9 rounded-lg bg-cofima/10 flex items-center justify-center font-bold text-cofima transition-colors group-hover:bg-cofima group-hover:text-white">2</div>
                                <h3 class="mt-4 font-semibold text-slate-800">Envoi automatique</h3>
                                <p class="mt-2 text-sm text-steel leading-relaxed">Une lettre PDF est générée et transmise par email au DG, en copie au secrétariat.</p>
                            </div>
                            <div class="group rounded-xl border border-slate-200 p-6 transition-all hover:-translate-y-1 hover:shadow-lg hover:border-cofima/30">
                                <div class="h-9 w-9 rounded-lg bg-cofima/10 flex items-center justify-center font-bold text-cofima transition-colors group-hover:bg-cofima group-hover:text-white">3</div>
                                <h3 class="mt-4 font-semibold text-slate-800">Suivi du statut</h3>
                                <p class="mt-2 text-sm text-steel leading-relaxed">Suivez l&apos;état de vos demandes et consultez l&apos;historique des décisions.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </main>

            <footer class="border-t border-slate-200 bg-white">
                <div class="mx-auto max-w-6xl px-4 sm:px-6 py-6 text-center text-xs text-steel">
                    © {{ now()->year }} COFIMA - Compagnie Fiduciaire de Management et d&apos;Audit. Usage interne.
                </div>
            </footer>
        </div>
    </body>
</html>
