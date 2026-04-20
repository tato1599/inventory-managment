<div>
    {{-- HEADER --}}
    <x-mary-header title="Áreas del Nodo NCIE" subtitle="Zonas de trabajo especializadas: IA, Prototipado y Coworking" separator progress-indicator>
        <x-slot:actions>
            <x-mary-button 
                icon="o-plus" 
                label="Nueva Área / Zona" 
                @click="$wire.createDrawer = true"
                class="btn-primary rounded-xl font-black shadow-lg shadow-primary/20" 
            />
        </x-slot:actions>
    </x-mary-header>

    {{-- FILTERS BAR --}}
    <div class="mb-10 max-w-2xl">
        <x-mary-input 
            wire:model.live.debounce.300ms="search" 
            placeholder="Buscar por nombre, código o descripción..."
            icon="o-magnifying-glass" 
            class="bg-base-100 border-none shadow-sm h-12 rounded-2xl" 
            clearable 
        />
    </div>

    {{-- TABLE CONTAINER --}}
    <x-mary-card shadow class="bg-base-100 border-none rounded-3xl overflow-hidden p-0">
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
                    <div class="w-10 h-10 bg-primary/5 rounded-xl flex items-center justify-center text-primary">
                        <x-mary-icon name="o-map-pin" class="w-5 h-5" />
                    </div>
                    <div>
                        <p class="font-bold text-sm text-base-content leading-tight">{{ $location->name }}</p>
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
                        class="btn-ghost btn-xs text-base-content/10 hover:text-error transition-colors" 
                        wire:confirm="¿Estás seguro de eliminar esta área?"
                        wire:click="delete({{ $location->id }})" 
                    />
                </div>
            @endscope
        </x-mary-table>
    </x-mary-card>

    {{-- Create Location Drawer --}}
    <x-mary-drawer wire:model="createDrawer" title="Configurar Nueva Área" separator right class="w-11/12 lg:w-1/3 p-0">
        <x-mary-form wire:submit="save" class="p-6 space-y-6">
            <div class="space-y-4">
                <div class="bg-base-200 p-8 rounded-3xl text-center flex flex-col items-center mb-4">
                    <x-mary-icon name="o-home-modern" class="w-12 h-12 text-primary opacity-20 mb-2" />
                    <p class="text-[10px] uppercase font-black text-base-content/30 tracking-widest leading-none">Añadir Espacio de Trabajo</p>
                </div>

                <x-mary-input label="Nombre del Área / Zona" wire:model="newLocation.name" icon="o-home" placeholder="Ej. Laboratorio de IA, Zona 3D" />
                <x-mary-input label="Código del Espacio" wire:model="newLocation.code" icon="o-hashtag" placeholder="Ej. L-IA-01" />
                
                <x-mary-textarea label="Descripción de la Zona" wire:model="newLocation.description" placeholder="Uso específico del área y equipamiento disponible..." rows="4" />
            </div>

            <x-slot:actions>
                <x-mary-button label="Cancelar" @click="$wire.createDrawer = false" class="btn-ghost rounded-xl" />
                <x-mary-button label="Guardar Área" icon="o-check" type="submit" class="btn-primary rounded-xl px-6" spinner="save" />
            </x-slot:actions>
        </x-mary-form>
    </x-mary-drawer>
</div>
