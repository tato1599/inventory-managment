<x-guest-layout>
    <div class="min-h-screen bg-slate-50 dark:bg-slate-950 flex flex-col items-center justify-center p-6 sm:p-12 transition-colors duration-300">
        <div class="w-full max-w-lg">
            <div class="text-center mb-10 space-y-4 animate-in fade-in zoom-in duration-700">
                <div class="bg-indigo-600 p-4 rounded-3xl inline-block shadow-2xl shadow-indigo-500/40">
                    <x-mary-icon name="o-cube" class="w-12 h-12 text-white" />
                </div>
                <div class="space-y-1">
                    <h1 class="text-4xl font-extrabold text-slate-900 dark:text-white tracking-tight font-headline">NCIE Inventory</h1>
                    <p class="text-slate-500 dark:text-slate-400 text-lg">Soluciones de control inteligente</p>
                </div>
            </div>

            <x-mary-card shadow class="bg-white dark:bg-slate-900 border-none !p-2 rounded-3xl">
                <div class="p-8 space-y-8">
                    <div class="space-y-2 text-center sm:text-left">
                        <h2 class="text-3xl font-black text-slate-900 dark:text-white font-headline">Bienvenido</h2>
                        <p class="text-slate-500 dark:text-slate-400">Ingresa tus credenciales para continuar</p>
                    </div>

                    <x-mary-errors class="mb-4" />

                    @session('status')
                        <div class="mb-4 font-medium text-sm text-emerald-600 dark:text-emerald-400 bg-emerald-50 dark:bg-emerald-900/20 p-4 rounded-2xl border border-emerald-100 dark:border-emerald-800/50">
                            {{ $value }}
                        </div>
                    @endsession

                    <form method="POST" action="{{ route('login') }}" class="space-y-6">
                        @csrf

                        <x-mary-input
                            label="Correo Electrónico"
                            name="email"
                            icon="o-envelope"
                            type="email"
                            value="{{ old('email') }}"
                            required
                            autofocus
                            class="input-bordered border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-800/50 text-slate-900 dark:text-white focus:border-indigo-500 rounded-2xl h-14"
                        />

                        <x-mary-input
                            label="Contraseña"
                            name="password"
                            icon="o-key"
                            type="password"
                            required
                            class="input-bordered border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-800/50 text-slate-900 dark:text-white focus:border-indigo-500 rounded-2xl h-14"
                        />

                        <div class="flex items-center justify-between text-sm pt-2">
                            <x-mary-checkbox id="remember_me" name="remember" label="Mantener sesión iniciada" class="checkbox-primary rounded-lg" />

                            @if (Route::has('password.request'))
                                <a class="text-indigo-600 dark:text-indigo-400 hover:text-indigo-700 dark:hover:text-indigo-300 transition-colors font-bold underline underline-offset-4" href="{{ route('password.request') }}">
                                    ¿Olvidaste tu contraseña?
                                </a>
                            @endif
                        </div>

                        <div class="pt-4">
                            <x-mary-button 
                                label="Iniciar Sesión Ahora" 
                                type="submit" 
                                class="btn-primary w-full h-14 text-lg font-black shadow-xl shadow-indigo-500/30 rounded-2xl uppercase tracking-widest" 
                                spinner 
                            />
                        </div>
                    </form>
                </div>
            </x-mary-card>

            <p class="text-center mt-10 text-slate-400 dark:text-slate-500 text-xs font-bold uppercase tracking-widest">
                &copy; {{ date('Y') }} NCIE Inventory. Todos los derechos reservados.
            </p>
        </div>
    </div>

    <style>
        @keyframes fade-in { from { opacity: 0; } to { opacity: 1; } }
        @keyframes zoom-in { from { transform: scale(0.95); } to { transform: scale(1); } }
        .animate-in { animation: fade-in 0.5s ease-out, zoom-in 0.5s ease-out; }
    </style>
</x-guest-layout>
