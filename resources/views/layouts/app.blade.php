<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'KIVAROX') }}</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@100..900&family=Inter:wght@100..900&display=swap"
        rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-screen font-sans antialiased bg-base-200/50">

    {{-- MARRYUI TOAST --}}
    <x-mary-toast />

    {{-- NAVIGATION --}}
    <x-mary-nav sticky full-width class="lg:hidden bg-base-100 border-b border-base-300">
        <x-slot:brand>
            <div class="flex items-center gap-2">
                <x-mary-icon name="o-bolt" class="text-primary" />
                <div class="font-bold text-lg tracking-tight">KIVAROX</div>
            </div>
        </x-slot:brand>
        <x-slot:actions>
            <label for="main-drawer" class="lg:hidden btn btn-ghost btn-sm">
                <x-mary-icon name="o-bars-3" />
            </label>
        </x-slot:actions>
    </x-mary-nav>

    {{-- MAIN LAYOUT --}}
    <x-mary-main full-width>
        {{-- SIDEBAR SLOT --}}
        <x-slot:sidebar drawer="main-drawer" collapsible class="bg-base-100 border-r border-base-300">
            @livewire('sidebar')
        </x-slot:sidebar>

        {{-- CONTENT --}}
        <x-slot:content>
            {{-- Search and User bar hidden in mobile, visible in desktop --}}
            <div class="hidden lg:flex items-center justify-between mb-8">
                <div class="w-96">
                    <x-mary-input placeholder="Buscar activos..." icon="o-magnifying-glass"
                        class="bg-base-100 border-none shadow-sm h-12 rounded-2xl" />
                </div>
                <div class="flex items-center gap-4">
                    <x-mary-theme-toggle class="btn btn-ghost" />
                    <div class="h-8 w-px bg-base-300"></div>
                    @auth
                        <x-mary-button label="{{ Auth::user()->name }}" icon="o-user" class="btn-ghost" />
                    @endauth
                </div>
            </div>

            {{-- Main view slot --}}
            <div class="animate-in fade-in duration-500">
                {{ $slot }}
            </div>
        </x-slot:content>
    </x-mary-main>

    @stack('modals')
</body>

</html>