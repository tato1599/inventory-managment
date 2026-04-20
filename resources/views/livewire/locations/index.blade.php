<div>
    {{-- Editorial Header Section --}}
    <div class="mb-10 flex flex-col md:flex-row md:items-end justify-between gap-6 animate-in fade-in slide-in-from-top-4 duration-700">
        <div>
            <h1 class="text-4xl font-extrabold text-base-content tracking-tight mb-2 font-headline">Áreas del Nodo NCIE</h1>
            <p class="text-base-content/60 font-medium italic">Define y organiza los espacios del laboratorio: IA, Impresión 3D, Coworking y Prototipado.</p>
        </div>
        {{-- Actions --}}
        <div class="flex items-center gap-3">
            <x-mary-button 
                icon="o-plus" 
                label="Nueva Área / Zona" 
                @click="$wire.createDrawer = true"
                class="btn btn-primary px-6 py-2.5 rounded-xl font-black shadow-xl shadow-primary/20 active:scale-95 border-none" 
            />
        </div>
    </div>

    {{-- Filters Bar --}}
    <div class="mb-8 max-w-2xl">
        <x-mary-input 
            wire:model.live.debounce.300ms="search" 
            placeholder="Buscar por nombre, código o descripción de área..."
            icon="o-magnifying-glass" 
            class="w-full py-4 border-none rounded-2xl bg-base-100 shadow-sm text-base-content focus:ring-2 focus:ring-primary/20 transition-all" 
            clearable 
        />
    </div>

    {{-- Table Container --}}
    <div class="bg-base-100 rounded-3xl shadow-xl shadow-base-content/5 overflow-hidden border border-base-300">
        <x-mary-table 
            :headers="$this->headers" 
            :rows="$this->locations" 
            with-pagination 
            per-page="perPage"
            :sort-by="$sortBy"
            class="mary-table-custom"
        >
            {{-- Name Cell --}}
            @scope('cell_name', $location)
                <div class="flex items-center gap-3 py-2">
                    <div class="w-10 h-10 bg-base-200 rounded-lg flex items-center justify-center text-primary">
                        <span class="material-symbols-outlined">hub</span>
                    </div>
                    <div>
                        <p class="font-bold text-base-content text-sm">{{ $location->name }}</p>
                        <p class="text-[10px] text-base-content/40 italic truncate max-w-xs">{{ $location->description ?: 'Sin descripción' }}</p>
                    </div>
                </div>
            @endscope

            {{-- Code Cell --}}
            @scope('cell_code', $location)
                <span class="badge badge-neutral font-mono text-[10px] tracking-widest px-2 py-3 rounded-lg border-none opacity-60">
                    {{ $location->code ?: 'S/C' }}
                </span>
            @endscope

            {{-- Items Count Cell --}}
            @scope('cell_items_count', $location)
                <div class="flex flex-col items-center">
                    <span class="text-lg font-black text-primary">{{ $location->items_count }}</span>
                    <span class="text-[9px] uppercase font-bold text-base-content/30 tracking-tight">Activos</span>
                </div>
            @endscope

            {{-- Actions --}}
            @scope('actions', $location)
                <div class="flex justify-end pr-4">
                    <x-mary-button 
                        icon="o-trash" 
                        class="btn-ghost btn-sm text-base-content/20 hover:text-error transition-colors" 
                        wire:confirm="¿Estás seguro de eliminar esta área?"
                        wire:click="delete({{ $location->id }})" 
                        tooltip="Eliminar área"
                    />
                </div>
            @endscope

        </x-mary-table>
    </div>

    {{-- Create Location Drawer --}}
    <x-mary-drawer wire:model="createDrawer" title="Configurar Nueva Área" separator right class="w-11/12 lg:w-1/3 p-0">
        <x-mary-form wire:submit="save" class="p-6 space-y-6">
            <div class="space-y-4">
                <div class="bg-base-200 p-6 rounded-3xl border border-base-300 text-center mb-4">
                    <x-mary-icon name="o-rocket-launch" class="w-12 h-12 text-primary opacity-20 mb-2" />
                    <p class="text-xs font-bold text-base-content/40 uppercase tracking-widest">Añadir Espacio de Trabajo</p>
                </div>

                <x-mary-input label="Nombre del Área / Zona" wire:model="newLocation.name" icon="o-home" placeholder="Ej. Laboratorio de IA, Zona 3D, Área de Soldadura" />
                <x-mary-input label="Código del Espacio" wire:model="newLocation.code" icon="o-hashtag" placeholder="Ej. L-IA-01" />
                
                <x-mary-textarea label="Descripción de la Zona" wire:model="newLocation.description" placeholder="Uso específico del área y equipamiento disponible..." rows="4" />
            </div>

            <x-slot:actions>
                <x-mary-button label="Cancelar" @click="$wire.createDrawer = false" class="btn-ghost" />
                <x-mary-button label="Guardar Área" icon="o-check" type="submit" class="btn-primary rounded-xl px-6" spinner="save" />
            </x-slot:actions>
        </x-mary-form>
    </x-mary-drawer>
</div>
