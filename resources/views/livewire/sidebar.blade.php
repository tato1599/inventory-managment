<div>
    <aside class="hidden md:flex flex-col h-screen w-64 fixed left-0 top-0 overflow-y-auto bg-base-100 p-4 space-y-2 z-50 font-inter text-sm antialiased border-r border-base-300 transition-colors duration-300">
        <div class="mb-8 px-2 py-4">
            <h1 class="font-headline font-bold text-primary text-2xl tracking-tighter">NCIE Hub</h1>
            <div class="flex items-center mt-6 p-3 bg-base-200 rounded-2xl border border-base-300">
                <div class="w-10 h-10 bg-primary rounded-xl flex items-center justify-center shadow-lg shadow-primary/20 text-primary-content">
                    <span class="material-symbols-outlined text-xl">rocket_launch</span>
                </div>
                <div class="ml-3">
                    <p class="font-bold text-base-content leading-tight">Laboratorio</p>
                    <p class="text-[10px] text-base-content/50 uppercase font-medium tracking-widest">Innovación NCIE</p>
                </div>
            </div>
        </div>

        <nav class="space-y-1">
            <a class="flex items-center space-x-3 px-3 py-2.5 {{ request()->routeIs('dashboard') ? 'bg-primary/10 text-primary font-bold' : 'text-base-content/60 hover:bg-base-200 hover:text-primary' }} rounded-xl transition-all duration-200" href="{{ route('dashboard') }}">
                <span class="material-symbols-outlined text-lg">dashboard</span>
                <span>Tablero</span>
            </a>
            
            <a class="flex items-center space-x-3 px-3 py-2.5 {{ request()->routeIs('inventory.index') ? 'bg-primary/10 text-primary font-bold' : 'text-base-content/60 hover:bg-base-200 hover:text-primary' }} rounded-xl transition-all duration-200" href="{{ route('inventory.index') }}">
                <span class="material-symbols-outlined text-lg">memory</span>
                <span>Activos & Hardware</span>
            </a>

            <a class="flex items-center space-x-3 px-3 py-2.5 {{ request()->routeIs('locations.index') ? 'bg-primary/10 text-primary font-bold' : 'text-base-content/60 hover:bg-base-200 hover:text-primary' }} rounded-xl transition-all duration-200" href="{{ route('locations.index') }}">
                <span class="material-symbols-outlined text-lg">map</span>
                <span>Ubicaciones</span>
            </a>
            
            <a class="flex items-center space-x-3 px-3 py-2.5 {{ request()->routeIs('locations.index') ? 'bg-primary/10 text-primary font-bold' : 'text-base-content/60 hover:bg-base-200 hover:text-primary' }} rounded-xl transition-all duration-200" href="{{ route('locations.index') }}">
                <span class="material-symbols-outlined text-lg">map</span>
                <span>Ubicaciones</span>
            </a>
        </nav>

        <div class="mt-auto pt-8">
            <div class="space-y-1">
                <form method="POST" action="{{ route('logout') }}" x-data>
                    @csrf
                    <button type="submit" @click.prevent="$root.submit();" class="flex items-center w-full space-x-3 px-3 py-2 text-base-content/60 hover:text-error transition-colors">
                        <span class="material-symbols-outlined text-lg">logout</span>
                        <span>Cerrar Sesión</span>
                    </button>
                </form>
            </div>
        </div>
    </aside>

    <!-- Mobile BottomNavBar -->
    <nav class="md:hidden fixed bottom-0 left-0 right-0 h-16 bg-base-100 flex justify-around items-center px-4 z-50 border-t border-base-300 shadow-lg">
        <a class="flex flex-col items-center space-y-1 text-primary" href="{{ route('dashboard') }}">
            <span class="material-symbols-outlined text-xl">dashboard</span>
            <span class="text-[10px] font-bold">Inicio</span>
        </a>
        <a class="flex flex-col items-center space-y-1 {{ request()->routeIs('inventory.index') ? 'text-primary' : 'text-base-content/40 hover:text-primary' }}" href="{{ route('inventory.index') }}">
            <span class="material-symbols-outlined text-xl">inventory_2</span>
            <span class="text-[10px] font-bold">Inventario</span>
        </a>
        <a class="flex flex-col items-center space-y-1 {{ request()->routeIs('locations.index') ? 'text-primary' : 'text-base-content/40 hover:text-primary' }}" href="{{ route('locations.index') }}">
            <span class="material-symbols-outlined text-xl">map</span>
            <span class="text-[10px] font-bold">Ubicaciones</span>
        </a>
        <a class="flex flex-col items-center space-y-1 text-base-content/40 hover:text-primary" href="#">
            <span class="material-symbols-outlined text-xl">handshake</span>
            <span class="text-[10px] font-bold">Préstamos</span>
        </a>
        <a class="flex flex-col items-center space-y-1 text-base-content/40 hover:text-primary" href="#">
            <span class="material-symbols-outlined text-xl">settings</span>
            <span class="text-[10px] font-bold">Ajustes</span>
        </a>
    </nav>
</div>
