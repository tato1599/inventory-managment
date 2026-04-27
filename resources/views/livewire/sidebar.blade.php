<div>
    {{-- MENU BRANDING --}}
    <div class="p-6 pt-3 flex items-center gap-4 border-b border-base-200">
        <div class="w-12 h-12 bg-primary/10 rounded-2xl flex items-center justify-center text-primary shadow-inner">
            <x-mary-icon name="o-bolt" class="w-7 h-7" />
        </div>
        <div>
            <div class="font-black text-xl tracking-tighter text-base-content">
                KIVAROX
            </div>
            <div class="text-[10px] uppercase font-bold text-base-content/40 tracking-widest">
                Gestión de Activos
            </div>
        </div>
    </div>

    {{-- MENU ITEMS --}}
    <x-mary-menu activate-by-route class="p-4 space-y-2">
        <x-mary-menu-item title="Tablero" icon="o-squares-2x2" link="{{ route('dashboard') }}"
            class="rounded-xl font-medium" />

        <x-mary-menu-separator title="Gestión de Activos" />

        <x-mary-menu-item title="Activos & Hardware" icon="o-cpu-chip" link="{{ route('inventory.index') }}"
            class="rounded-xl font-medium" />

        <x-mary-menu-item title="Áreas del Nodo" icon="o-map-pin" link="{{ route('locations.index') }}"
            class="rounded-xl font-medium" />

        <x-mary-menu-separator title="Operaciones" />

        <x-mary-menu-item title="Préstamos" icon="o-arrow-path" link="#"
            class="rounded-xl font-medium opacity-50 cursor-not-allowed" />

        <x-mary-menu-item title="Ajustes" icon="o-cog-6-tooth" link="#"
            class="rounded-xl font-medium opacity-50 cursor-not-allowed" />
    </x-mary-menu>

    {{-- LOGOUT BUTTON --}}
    <div class="mt-auto p-4 border-t border-base-200">
        <form method="POST" action="{{ route('logout') }}" x-data>
            @csrf
            <x-mary-button label="Cerrar Sesión" icon="o-power" type="submit" @click.prevent="$root.submit();"
                class="btn-ghost btn-sm w-full justify-start text-base-content/60 hover:text-error rounded-xl" />
        </form>
    </div>
</div>