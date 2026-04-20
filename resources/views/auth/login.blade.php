<x-guest-layout>
    <div class="min-h-screen bg-gradient-to-br from-indigo-900 via-slate-900 to-black flex flex-col items-center justify-center p-6 sm:p-12">
        <div class="w-full max-w-lg">
            <div class="text-center mb-10 space-y-4 animate-in fade-in zoom-in duration-700">
                <div class="bg-white/10 p-4 rounded-2xl inline-block backdrop-blur-xl border border-white/20 shadow-2xl">
                    <x-mary-icon name="o-cube" class="w-12 h-12 text-indigo-400" />
                </div>
                <div class="space-y-1">
                    <h1 class="text-4xl font-extrabold text-white tracking-tight">NCIE Inventory</h1>
                    <p class="text-slate-400 text-lg">Soluciones de control inteligente</p>
                </div>
            </div>

            <x-mary-card class="bg-slate-900/50 backdrop-blur-3xl border-slate-700 shadow-2xl !p-2">
                <div class="p-6 space-y-6">
                    <div class="space-y-2">
                        <h2 class="text-2xl font-bold text-white">Bienvenido</h2>
                        <p class="text-slate-400">Ingresa tus credenciales para continuar</p>
                    </div>

                    <x-mary-errors class="mb-4" />

                    @session('status')
                        <div class="mb-4 font-medium text-sm text-green-400 bg-green-400/10 p-3 rounded-lg border border-green-400/20">
                            {{ $value }}
                        </div>
                    @endsession

                    <form method="POST" action="{{ route('login') }}" class="space-y-5">
                        @csrf

                        <x-mary-input
                            label="Correo Electrónico"
                            name="email"
                            icon="o-envelope"
                            type="email"
                            value="{{ old('email') }}"
                            required
                            autofocus
                            class="input-bordered border-slate-700 bg-slate-800/50 text-white focus:border-indigo-500"
                        />

                        <x-mary-input
                            label="Contraseña"
                            name="password"
                            icon="o-key"
                            type="password"
                            required
                            class="input-bordered border-slate-700 bg-slate-800/50 text-white focus:border-indigo-500"
                        />

                        <div class="flex items-center justify-between text-sm">
                            <x-mary-checkbox id="remember_me" name="remember" label="Mantener sesión" class="checkbox-primary" />

                            @if (Route::has('password.request'))
                                <a class="text-indigo-400 hover:text-indigo-300 transition-colors font-medium" href="{{ route('password.request') }}">
                                    ¿Olvidaste tu contraseña?
                                </a>
                            @endif
                        </div>

                        <div class="pt-2">
                            <x-mary-button 
                                label="Iniciar Sesión" 
                                type="submit" 
                                class="btn-primary w-full py-6 text-lg font-bold shadow-lg shadow-indigo-500/20" 
                                spinner 
                            />
                        </div>
                    </form>
                </div>
            </x-mary-card>

            <p class="text-center mt-8 text-slate-500 text-sm">
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
