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
    <body class="bg-surface text-on-surface font-body selection:bg-primary-container selection:text-white antialiased">
        <x-banner />

        <div class="min-h-screen">
            <!-- Sidebar Component -->
            @livewire('sidebar')

            <!-- Main Canvas -->
            <main class="md:ml-64 min-h-screen">
                <!-- TopNavBar (Integrated here or in another component) -->
                <header class="flex justify-between items-center w-full px-8 h-16 sticky top-0 z-50 bg-stone-50/85 backdrop-blur-xl font-public-sans tracking-tight">
                    <div class="flex items-center flex-1">
                        <div class="relative w-64 md:w-96 group">
                            <span class="absolute left-3 top-1/2 -translate-y-1/2 material-symbols-outlined text-on-surface-variant text-lg">search</span>
                            <input class="w-full pl-10 pr-4 py-2 bg-stone-100 border-none rounded-full text-sm focus:ring-2 focus:ring-primary/20 transition-all outline-none" placeholder="Search archive..." type="text"/>
                        </div>
                    </div>
                    <div class="flex items-center space-x-6">
                        <div class="hidden lg:flex space-x-4">
                            <button class="text-stone-500 hover:text-primary transition-colors">
                                <span class="material-symbols-outlined">qr_code_scanner</span>
                            </button>
                            <button class="text-stone-500 hover:text-primary transition-colors relative">
                                <span class="material-symbols-outlined">notifications</span>
                                <span class="absolute -top-1 -right-1 w-2 h-2 bg-primary rounded-full"></span>
                            </button>
                            <button class="text-stone-500 hover:text-primary transition-colors">
                                <span class="material-symbols-outlined">help_outline</span>
                            </button>
                        </div>
                        <button class="bg-primary text-on-primary px-4 py-2 rounded-full font-bold text-sm flex items-center space-x-2 shadow-sm active:scale-95 duration-150">
                            <span class="material-symbols-outlined text-sm">add</span>
                            <span>New Loan</span>
                        </button>
                        
                        <!-- Account Dropdown (Simplified for now) -->
                        <div class="w-8 h-8 rounded-full overflow-hidden border border-outline-variant">
                            <img alt="Profile" class="w-full h-full object-cover" src="{{ Auth::user()->profile_photo_url }}" />
                        </div>
                    </div>
                </header>

                <!-- Page Content -->
                <div class="p-8 lg:p-12">
                    {{ $slot }}
                </div>
            </main>
        </div>

        @stack('modals')

        @livewireScripts
    </body>
</html>
