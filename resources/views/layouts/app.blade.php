<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.googleapis.com/css2?family=Public+Sans:wght@400;700;800;900&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet"/>
        <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <!-- Styles -->
        @livewireStyles
        <style>
            .material-symbols-outlined {
                font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
            }
            .editorial-grid {
                display: grid;
                grid-template-columns: 2fr 1fr;
                gap: 2rem;
            }
            @media (max-width: 1024px) {
                .editorial-grid {
                    grid-template-columns: 1fr;
                }
            }
        </style>
    </head>
    <body class="bg-slate-50 dark:bg-slate-950 text-slate-900 dark:text-slate-100 font-body selection:bg-indigo-100 dark:selection:bg-indigo-900 antialiased transition-colors duration-300">
        <x-banner />

        <div class="min-h-screen">
            <!-- Sidebar Component -->
            @livewire('sidebar')

            <!-- Main Canvas -->
            <main class="md:ml-64 min-h-screen">
                <!-- TopNavBar -->
                <header class="flex justify-between items-center w-full px-8 h-18 sticky top-0 z-40 bg-slate-50/80 dark:bg-slate-900/80 backdrop-blur-xl border-b border-slate-200 dark:border-slate-800 font-headline">
                    <div class="flex items-center flex-1">
                        <div class="relative w-64 md:w-96 group">
                            <span class="absolute left-3 top-1/2 -translate-y-1/2 material-symbols-outlined text-slate-400 text-lg">search</span>
                            <input 
                                class="w-full pl-10 pr-4 py-2 bg-slate-100 dark:bg-slate-800 border-none rounded-xl text-sm focus:ring-2 focus:ring-indigo-500/20 transition-all outline-none text-slate-600 dark:text-slate-300 placeholder-slate-400" 
                                placeholder="Buscar en el archivo..." 
                                type="text"
                            />
                        </div>
                    </div>

                    <div class="flex items-center space-x-6">
                        <div class="hidden lg:flex space-x-2">
                            <button class="p-2 text-slate-500 hover:text-indigo-600 dark:hover:text-indigo-400 hover:bg-slate-200 dark:hover:bg-slate-800 rounded-lg transition-all">
                                <span class="material-symbols-outlined">qr_code_scanner</span>
                            </button>
                            <button class="p-2 text-slate-500 hover:text-indigo-600 dark:hover:text-indigo-400 hover:bg-slate-200 dark:hover:bg-slate-800 rounded-lg transition-all relative">
                                <span class="material-symbols-outlined">notifications</span>
                                <span class="absolute top-2 right-2 w-2 h-2 bg-indigo-500 rounded-full border-2 border-slate-50 dark:border-slate-900"></span>
                            </button>
                            <x-mary-theme-toggle class="btn btn-ghost btn-sm text-slate-500 hover:text-indigo-600" />
                            <button class="p-2 text-slate-500 hover:text-indigo-600 dark:hover:text-indigo-400 hover:bg-slate-200 dark:hover:bg-slate-800 rounded-lg transition-all">
                                <span class="material-symbols-outlined">help_outline</span>
                            </button>
                        </div>

                        <div class="h-8 w-px bg-slate-200 dark:bg-slate-800 hidden sm:block"></div>

                        <button class="bg-indigo-600 hover:bg-indigo-700 text-white px-5 py-2.5 rounded-xl font-bold text-sm flex items-center space-x-2 shadow-lg shadow-indigo-500/20 active:scale-95 transition-all">
                            <span class="material-symbols-outlined text-lg">add</span>
                            <span>Nuevo Préstamo</span>
                        </button>
                        
                        <!-- Account -->
                        <div class="flex items-center space-x-3 pl-2 border-l border-slate-200 dark:border-slate-800">
                            <div class="text-right hidden sm:block">
                                <p class="text-xs font-bold text-slate-900 dark:text-slate-100">{{ Auth::user()->name }}</p>
                                <p class="text-[10px] text-slate-500 dark:text-slate-400 uppercase tracking-tighter">Administrador</p>
                            </div>
                            <div class="w-10 h-10 rounded-xl overflow-hidden border border-slate-200 dark:border-slate-700 p-0.5 bg-white dark:bg-slate-800 shadow-sm">
                                <img alt="Profile" class="w-full h-full object-cover rounded-lg" src="{{ Auth::user()->profile_photo_url }}" />
                            </div>
                        </div>
                    </div>
                </header>

                <!-- Page Content -->
                <div class="p-8 lg:p-12 animate-in fade-in duration-500">
                    {{ $slot }}
                </div>
            </main>
        </div>

        @stack('modals')

        @livewireScripts

        <style>
            @keyframes fade-in { from { opacity: 0; } to { opacity: 1; } }
            .animate-in { animation: fade-in 0.3s ease-out; }
        </style>
    </body>
</html>
