<div>
    <aside class="hidden md:flex flex-col h-screen w-64 fixed left-0 top-0 overflow-y-auto bg-white dark:bg-slate-900 p-4 space-y-2 z-50 font-inter text-sm antialiased border-r border-slate-200 dark:border-slate-800 transition-colors duration-300">
        <div class="mb-8 px-2 py-4">
            <h1 class="font-headline font-bold text-indigo-600 dark:text-indigo-400 text-2xl tracking-tighter">NCIE Inventory</h1>
            <div class="flex items-center mt-6 p-3 bg-slate-50 dark:bg-slate-800/50 rounded-2xl border border-slate-100 dark:border-slate-700/50">
                <div class="w-10 h-10 bg-indigo-600 rounded-xl flex items-center justify-center shadow-lg shadow-indigo-500/20">
                    <span class="material-symbols-outlined text-white text-xl">account_balance</span>
                </div>
                <div class="ml-3">
                    <p class="font-bold text-slate-900 dark:text-slate-100 leading-tight">Panel Central</p>
                    <p class="text-[10px] text-slate-500 dark:text-slate-400 uppercase font-medium">Portal de Archivo</p>
                </div>
            </div>
        </div>

        <nav class="space-y-1">
            <a class="flex items-center space-x-3 px-3 py-2.5 {{ request()->routeIs('dashboard') ? 'bg-indigo-50 dark:bg-indigo-900/30 text-indigo-700 dark:text-indigo-300 font-bold' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800' }} rounded-xl transition-all duration-200" href="{{ route('dashboard') }}">
                <span class="material-symbols-outlined text-lg">dashboard</span>
                <span>Tablero</span>
            </a>
            
            <a class="flex items-center space-x-3 px-3 py-2.5 {{ request()->routeIs('inventory.index') ? 'bg-indigo-50 dark:bg-indigo-900/30 text-indigo-700 dark:text-indigo-300 font-bold' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800' }} rounded-xl transition-all duration-200" href="{{ route('inventory.index') }}">
                <span class="material-symbols-outlined text-lg">inventory_2</span>
                <span>Inventario</span>
            </a>
            
            <a class="flex items-center space-x-3 px-3 py-2.5 text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800 hover:text-indigo-600 dark:hover:text-indigo-400 rounded-xl transition-all duration-200" href="#">
                <span class="material-symbols-outlined text-lg">handshake</span>
                <span>Gestión de Préstamos</span>
            </a>
            
            <a class="flex items-center space-x-3 px-3 py-2.5 text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800 hover:text-indigo-600 dark:hover:text-indigo-400 rounded-xl transition-all duration-200" href="#">
                <span class="material-symbols-outlined text-lg">analytics</span>
                <span>Reportes de Archivo</span>
            </a>
            
            <a class="flex items-center space-x-3 px-3 py-2.5 text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800 hover:text-indigo-600 dark:hover:text-indigo-400 rounded-xl transition-all duration-200" href="#">
                <span class="material-symbols-outlined text-lg">settings</span>
                <span>Configuración</span>
            </a>
        </nav>

        <div class="mt-auto pt-8">
            <button class="w-full bg-indigo-600 hover:bg-indigo-700 text-white py-3 rounded-xl font-bold flex items-center justify-center space-x-2 shadow-lg shadow-indigo-500/20 active:scale-95 transition-all">
                <span class="material-symbols-outlined text-lg">bolt</span>
                <span>Auditoría Rápida</span>
            </button>
            
            <div class="mt-6 space-y-1">
                <a class="flex items-center space-x-3 px-3 py-2 text-slate-500 hover:text-slate-900 dark:hover:text-slate-200 transition-colors" href="#">
                    <span class="material-symbols-outlined text-lg">contact_support</span>
                    <span>Soporte Técnico</span>
                </a>
                
                <form method="POST" action="{{ route('logout') }}" x-data>
                    @csrf
                    <button type="submit" @click.prevent="$root.submit();" class="flex items-center w-full space-x-3 px-3 py-2 text-slate-500 hover:text-red-600 dark:hover:text-red-400 transition-colors">
                        <span class="material-symbols-outlined text-lg">logout</span>
                        <span>Cerrar Sesión</span>
                    </button>
                </form>
            </div>
        </div>
    </aside>

    <!-- Mobile BottomNavBar -->
    <nav class="md:hidden fixed bottom-0 left-0 right-0 h-16 bg-white dark:bg-slate-900 flex justify-around items-center px-4 z-50 border-t border-slate-200 dark:border-slate-800 shadow-lg">
        <a class="flex flex-col items-center space-y-1 text-indigo-600 dark:text-indigo-400" href="{{ route('dashboard') }}">
            <span class="material-symbols-outlined text-xl">dashboard</span>
            <span class="text-[10px] font-bold">Inicio</span>
        </a>
        <a class="flex flex-col items-center space-y-1 text-slate-500 dark:text-slate-400" href="#">
            <span class="material-symbols-outlined text-xl">inventory_2</span>
            <span class="text-[10px] font-bold">Inventario</span>
        </a>
        <a class="flex flex-col items-center space-y-1 text-slate-500 dark:text-slate-400" href="#">
            <span class="material-symbols-outlined text-xl">handshake</span>
            <span class="text-[10px] font-bold">Préstamos</span>
        </a>
        <a class="flex flex-col items-center space-y-1 text-slate-500 dark:text-slate-400" href="#">
            <span class="material-symbols-outlined text-xl">settings</span>
            <span class="text-[10px] font-bold">Ajustes</span>
        </a>
    </nav>
</div>
