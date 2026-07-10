<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'COFIMA') }}</title>
        <link rel="icon" type="image/png" href="{{ asset('logocofima.png') }}">

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-slate-800 antialiased">
        <div class="min-h-screen bg-slate-100 flex flex-col items-center justify-center px-4 py-12">
            <div class="w-full max-w-md">
                <a href="/" class="flex flex-col items-center gap-3 mb-8">
                    <x-application-logo size="h-14 w-14" />
                    <div class="text-center leading-tight">
                        <p class="text-2xl font-extrabold tracking-tight text-cofima">COFIMA</p>
                        <p class="text-[10px] font-medium tracking-wide text-steel uppercase">Compagnie Fiduciaire de Management et d&apos;Audit</p>
                    </div>
                </a>

                <div class="rounded-2xl border border-slate-200 bg-white p-8 shadow-sm">
                    {{ $slot }}
                </div>
            </div>
        </div>
    </body>
</html>
