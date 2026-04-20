<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-theme="light">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="bg-base-200 text-base-content font-body selection:bg-primary selection:text-primary-content antialiased transition-colors duration-300">
        <x-mary-toast />
        
        <x-banner />

        <div class="min-h-screen">
            <!-- Sidebar Component -->
            @livewire('sidebar')

            <!-- Main Canvas -->
            <main class="md:ml-64 min-h-screen">
                <!-- TopNavBar -->
                <header class="flex justify-between items-center w-full px-8 h-18 sticky top-0 z-40 bg-base-100/80 backdrop-blur-xl border-b border-base-300 font-headline">
                    <div class="flex items-center flex-1">
                        <div class="relative w-64 md:w-96 group">
                            <span class="absolute left-3 top-1/2 -translate-y-1/2 material-symbols-outlined text-base-content/40 text-lg">search</span>
                            <input 
                                class="w-full pl-10 pr-4 py-2 bg-base-200 border-none rounded-xl text-sm focus:ring-2 focus:ring-primary/20 transition-all outline-none text-base-content" 
                                placeholder="Buscar en el archivo..." 
                                type="text"
                            />
                        </div>
                    </div>

                    <div class="flex items-center space-x-6">
                        <div class="hidden lg:flex space-x-4">
                            <x-mary-theme-toggle class="btn btn-ghost btn-sm text-base-content/60 hover:text-primary" />
                        </div>

                        <div class="h-8 w-px bg-base-300 hidden sm:block"></div>
                        
                        <!-- Account -->
                        @auth
                        <div class="flex items-center space-x-3 pl-2 border-l border-base-300">
                            <div class="text-right hidden sm:block">
                                <p class="text-xs font-bold text-base-content">{{ Auth::user()->name }}</p>
                                <p class="text-[10px] text-base-content/50 uppercase tracking-tighter">Administrador</p>
                            </div>
                            <div class="w-10 h-10 rounded-xl overflow-hidden border border-base-300 p-0.5 bg-base-100 shadow-sm">
                                <img alt="Profile" class="w-full h-full object-cover rounded-lg" src="{{ Auth::user()->profile_photo_url }}" />
                            </div>
                        </div>
                        @endauth
                    </div>
                </header>

                <!-- Page Content -->
                <div class="p-8 lg:p-12">
                    {{ $slot }}
                </div>
            </main>
        </div>

        @stack('modals')
    </body>
</html>
