<x-guest-layout>
    <div class="min-h-screen flex flex-col items-center justify-center p-6 sm:p-12">
        <div class="w-full max-w-lg">
            <div class="text-center mb-10 space-y-4 animate-in fade-in zoom-in duration-700">
                <div class="bg-primary p-4 rounded-[2rem] inline-block shadow-2xl shadow-primary/20">
                    <x-mary-icon name="o-cube" class="w-12 h-12 text-primary-content" />
                </div>
                <div class="space-y-1">
                    <h1 class="text-4xl font-black text-base-content tracking-tighter font-headline uppercase">KIVAROX
                    </h1>
                    <p class="text-primary text-[11px] font-black uppercase tracking-[0.25em]">Gestión de Activos
                        Tecnológicos</p>
                </div>
            </div>

            <x-mary-card shadow class="bg-base-100 border-none !p-2 rounded-[2.5rem]">
                <div class="p-8 space-y-8">
                    <div class="space-y-2 text-center sm:text-left">
                        <h2 class="text-3xl font-black text-primary tracking-tighter uppercase">Bienvenido</h2>
                        <p class="text-base-content/60 text-xs font-bold uppercase tracking-widest">Ingresa tus
                            credenciales para acceder</p>
                    </div>

                    <x-mary-errors class="mb-4" />

                    @session('status')
                        <div
                            class="mb-4 font-medium text-sm text-success bg-success/10 p-4 rounded-2xl border border-success/20">
                            {{ $value }}
                        </div>
                    @endsession

                    <form method="POST" action="{{ route('login') }}" class="space-y-6">
                        @csrf

                        <x-mary-input 
                            name="email" 
                            icon="o-envelope" 
                            type="email"
                            value="{{ old('email') }}" 
                            required 
                            autofocus
                            class="rounded-2xl h-14 bg-base-200 border-none focus:ring-2 focus:ring-primary text-base-content placeholder-primary/40 font-bold"
                        >
                            <x-slot:label>
                                <span class="text-primary font-black text-[10px] uppercase tracking-widest">Correo Electrónico</span>
                            </x-slot:label>
                        </x-mary-input>

                        <x-mary-input 
                            name="password" 
                            icon="o-key" 
                            type="password" 
                            required
                            class="rounded-2xl h-14 bg-base-200 border-none focus:ring-2 focus:ring-primary text-base-content placeholder-primary/40 font-bold"
                        >
                            <x-slot:label>
                                <span class="text-primary font-black text-[10px] uppercase tracking-widest">Contraseña</span>
                            </x-slot:label>
                        </x-mary-input>

                        <div class="flex items-center justify-between text-[11px] text-primary pt-2">
                            <x-mary-checkbox id="remember_me" name="remember" class="checkbox-primary">
                                <x-slot:label>
                                    <span class="text-primary font-black text-[10px] uppercase tracking-widest ml-2">Recordar mi sesión</span>
                                </x-slot:label>
                            </x-mary-checkbox>

                            @if (Route::has('password.request'))
                                <a class="text-primary hover:text-primary/80 font-black uppercase tracking-tighter"
                                    href="{{ route('password.request') }}">
                                    ¿Olvidaste tu contraseña?
                                </a>
                            @endif
                        </div>

                        <div class="pt-4">
                            <x-mary-button label="Iniciar Sesión" type="submit"
                                class="w-full h-16 text-xl font-black rounded-[1.5rem] uppercase tracking-widest bg-white text-primary border-2 border-primary shadow-xl shadow-primary/10 hover:bg-primary hover:text-white transition-all duration-300"
                                spinner />
                        </div>
                    </form>
                </div>
            </x-mary-card>

            <p class="text-center mt-10 text-base-content/50 text-[10px] font-black uppercase tracking-widest">
                &copy; {{ date('Y') }} KIVAROX Inventory.
            </p>
        </div>
    </div>
</x-guest-layout>