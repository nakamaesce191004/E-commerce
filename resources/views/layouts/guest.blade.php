<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased text-slate-200">
        <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-slate-950 relative overflow-hidden">
            <!-- Background Radial Glows -->
            <div class="absolute inset-0 z-0 bg-[radial-gradient(ellipse_at_center,_var(--tw-gradient-stops))] from-emerald-950/20 via-slate-950 to-slate-950"></div>
            <div class="absolute top-10 left-10 w-72 h-72 rounded-full bg-emerald-500/5 blur-3xl"></div>
            <div class="absolute bottom-10 right-10 w-72 h-72 rounded-full bg-orange-500/5 blur-3xl"></div>

            <div class="relative z-10 flex flex-col items-center">
                <a href="/" class="flex items-center gap-2 group mb-6">
                    <span class="p-3 rounded-2xl bg-gradient-to-tr from-emerald-600 to-emerald-400 text-slate-950 shadow-md">
                        <i data-lucide="tent" class="h-6 w-6"></i>
                    </span>
                    <span class="font-display font-extrabold text-3xl tracking-tight text-white">
                        Equip<span class="text-emerald-500">Rent</span>
                    </span>
                </a>
            </div>

            <div class="w-full sm:max-w-md mt-6 px-8 py-8 bg-slate-900 border border-slate-800 shadow-2xl overflow-hidden sm:rounded-2xl glass relative z-10">
                {{ $slot }}
            </div>
        </div>
        
        <!-- Lucide Icons CDN -->
        <script src="https://unpkg.com/lucide@latest"></script>
        <script>
            lucide.createIcons();
        </script>
    </body>
</html>
