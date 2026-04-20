<div>
    {{-- HEADER --}}
    <x-mary-header title="Inventario de Innovación" subtitle="Hardware avanzado para IA, Impresión 3D y Prototipado" separator progress-indicator>
        <x-slot:actions>
            <x-mary-button 
                icon="o-plus" 
                label="Registrar Activo" 
                @click="$wire.createDrawer = true"
                class="btn-primary rounded-xl font-black shadow-lg shadow-primary/20" 
            />
        </x-slot:actions>
    </x-mary-header>

    {{-- FILTERS BAR --}}
    <div class="mb-10 flex flex-col md:flex-row gap-4 items-center">
        <div class="flex-1 w-full">
            <x-mary-input 
                wire:model.live.debounce.300ms="search" 
                placeholder="Filtrar por nombre, SKU o descripción..."
                icon="o-magnifying-glass" 
                class="bg-base-100 border-none shadow-sm rounded-2xl h-12" 
                clearable 
            />
        </div>
        <div class="min-w-[200px] w-full md:w-auto">
            <x-mary-select 
                wire:model.live="categoryId" 
                :options="$this->categories" 
                placeholder="Todas las categorías" 
                icon="o-tag" 
                class="bg-base-100 border-none shadow-sm rounded-2xl h-12"
            />
        </div>
        <x-mary-button icon="o-funnel" class="btn-ghost bg-base-100 shadow-sm rounded-2xl h-12" />
    </div>

    {{-- TABLE CONTAINER --}}
    <x-mary-card shadow class="bg-base-100 border-none rounded-3xl overflow-hidden p-0">
        <x-mary-table 
            :headers="$this->headers" 
            :rows="$this->items" 
            with-pagination 
            per-page="perPage"
            :sort-by="$sortBy"
            selectable 
            selectable-key="id" 
            wire:model.live="selectedIds"
            @row-click="$wire.showDrawer = true; $wire.showDetails($event.detail.id)" 
            class="mary-table-custom"
        >
            {{-- SKU Cell --}}
            @scope('cell_sku', $item)
                <span class="font-mono text-[10px] tracking-widest text-base-content/40 bg-base-200 px-2 py-1 rounded-md">
                    {{ $item->sku }}
                </span>
            @endscope

            {{-- Name Cell --}}
            @scope('cell_name', $item)
                <div class="flex items-center gap-3 py-1">
                    <div class="w-10 h-10 bg-primary/5 rounded-xl flex items-center justify-center text-primary">
                        <x-mary-icon name="o-cpu-chip" class="w-5 h-5" />
                    </div>
                    <div>
                        <p class="font-bold text-sm leading-tight">{{ $item->name }}</p>
                        <p class="text-[10px] text-base-content/40 uppercase font-black tracking-tighter">{{ $item->category->name }}</p>
                    </div>
                </div>
            @endscope

            {{-- Location Cell --}}
            @scope('cell_location.name', $item)
                <div class="flex items-center gap-2 text-base-content/60">
                    <x-mary-icon name="o-map-pin" class="w-3 h-3" />
                    <span class="text-xs font-semibold">{{ $item->location?->name ?: 'Sin área' }}</span>
                </div>
            @endscope

            {{-- Status Cell --}}
            @scope('cell_status', $item)
                @php
                    $colors = [
                        'available' => 'badge-success',
                        'loaned' => 'badge-info',
                        'maintenance' => 'badge-warning',
                        'lost' => 'badge-error'
                    ];
                    $labels = [
                        'available' => 'Disponible',
                        'loaned' => 'En Uso',
                        'maintenance' => 'Revisión',
                        'lost' => 'Extraviado'
                    ];
                @endphp
                <span class="badge {{ $colors[$item->status] }} badge-sm font-bold text-[10px] py-3 px-3 border-none bg-opacity-20 text-current">
                    {{ $labels[$item->status] }}
                </span>
            @endscope

            {{-- Actions --}}
            @scope('actions', $item)
                <div class="flex justify-end pr-4">
                    <x-mary-button 
                        icon="o-trash" 
                        class="btn-ghost btn-xs text-base-content/10 hover:text-error transition-colors" 
                        wire:confirm="¿Estás seguro de eliminar este activo?"
                        wire:click="delete({{ $item->id }})" 
                    />
                </div>
            @endscope
        </x-mary-table>
    </x-mary-card>

    @if (count($selectedIds) > 0)
        <div class="fixed bottom-6 left-1/2 -translate-x-1/2 z-50 animate-in slide-in-from-bottom-8 duration-500">
            <div class="bg-neutral text-neutral-content px-8 py-4 rounded-3xl shadow-2xl flex items-center gap-6 border border-white/10 backdrop-blur-xl bg-opacity-90">
                <p class="text-sm font-black tracking-tighter">{{ count($selectedIds) }} activos seleccionados</p>
                <div class="h-6 w-px bg-white/20"></div>
                <div class="flex items-center gap-2">
                    <x-mary-button icon="o-printer" label="Etiquetas" class="btn-ghost btn-sm text-xs font-bold" />
                    <x-mary-button icon="o-trash" label="Eliminar" class="btn-error btn-sm text-xs font-bold" wire:confirm="¿Eliminar seleccionados?" wire:click="deleteBatch" />
                </div>
            </div>
        </div>
    @endif

    {{-- Create Item Drawer --}}
    <x-mary-drawer wire:model="createDrawer" title="Nuevo Artículo" separator right class="w-11/12 lg:w-1/3 p-0">
        <x-mary-form wire:submit="saveItem" class="p-6 space-y-6">
            <div class="space-y-4">
                <div class="bg-base-200 p-8 rounded-3xl text-center flex flex-col items-center">
                    <x-mary-icon name="o-rocket-launch" class="w-12 h-12 text-primary opacity-20 mb-2" />
                    <p class="text-[10px] uppercase font-black text-base-content/30 tracking-widest leading-none">Añadir Activo al Nodo</p>
                </div>

                <x-mary-input label="Nombre del Artículo" wire:model="newItem.name" icon="o-cube" placeholder="Ej. GPU RTX 4090" />
                <x-mary-input label="SKU / Identificador" wire:model="newItem.sku" icon="o-hashtag" placeholder="Ej. HW-IA-001" />
                
                <div class="grid grid-cols-2 gap-4">
                    <x-mary-select 
                        label="Categoría" 
                        wire:model="newItem.category_id" 
                        :options="$this->categories" 
                        icon="o-tag" 
                        placeholder="Seleccionar..." 
                    />
                    <x-mary-select 
                        label="Ubicación / Área" 
                        wire:model="newItem.location_id" 
                        :options="$this->locations" 
                        icon="o-map-pin" 
                        placeholder="Seleccionar..." 
                    />
                </div>

                <x-mary-select 
                    label="Estado Inicial" 
                    wire:model="newItem.status" 
                    :options="[
                        ['id' => 'available', 'name' => 'Disponible'],
                        ['id' => 'loaned', 'name' => 'En Uso'],
                        ['id' => 'maintenance', 'name' => 'Mantenimiento']
                    ]" 
                    icon="o-check-circle"
                />

                <x-mary-textarea label="Descripción Técnica" wire:model="newItem.description" placeholder="Detalles de hardware, garantía, componentes..." rows="4" />
            </div>

            <x-slot:actions>
                <x-mary-button label="Cancelar" @click="$wire.createDrawer = false" class="btn-ghost rounded-xl" />
                <x-mary-button label="Registrar Activo" icon="o-check" type="submit" class="btn-primary rounded-xl px-6" spinner="saveItem" />
            </x-slot:actions>
        </x-mary-form>
    </x-mary-drawer>

    {{-- Details Drawer --}}
    <x-mary-drawer wire:model="showDrawer" title="Detalles del Activo" separator right class="w-11/12 lg:w-1/3 p-0">
        @if ($selectedItem)
            <div class="p-6 space-y-8">
                {{-- Header Inside Drawer --}}
                <div class="flex items-start justify-between">
                    <div class="flex items-center gap-4">
                        <div class="w-16 h-16 bg-primary/10 rounded-2xl flex items-center justify-center text-primary">
                            <x-mary-icon name="o-cpu-chip" class="w-8 h-8" />
                        </div>
                        <div>
                            <h2 class="text-2xl font-black tracking-tighter leading-none">{{ $selectedItem->name }}</h2>
                            <p class="text-[10px] font-mono text-base-content/40 mt-1 uppercase">{{ $selectedItem->sku }}</p>
                        </div>
                    </div>
                </div>

                {{-- Key Stats Inside Drawer --}}
                <div class="grid grid-cols-2 gap-4">
                    <div class="bg-base-200 p-4 rounded-2xl">
                        <p class="text-[10px] uppercase font-bold text-base-content/40 tracking-widest mb-1">Estado Actual</p>
                        <span class="text-xs font-black text-primary">{{ strtoupper($selectedItem->status) }}</span>
                    </div>
                    <div class="bg-base-200 p-4 rounded-2xl">
                        <p class="text-[10px] uppercase font-bold text-base-content/40 tracking-widest mb-1">Ubicación</p>
                        <span class="text-xs font-black text-base-content">{{ $selectedItem->location?->name ?: 'GENERAL' }}</span>
                    </div>
                </div>

                {{-- Detail Content --}}
                <div class="space-y-6">
                    <x-mary-card title="Especificaciones" shadow class="bg-base-200/50 border-none">
                        <p class="text-sm leading-relaxed text-base-content/70">
                            {{ $selectedItem->description ?: 'No hay especificaciones técnicas registradas para este activo.' }}
                        </p>
                    </x-mary-card>

                    <div class="bg-base-100 border border-base-300 rounded-3xl p-6 flex items-center gap-4">
                        <x-mary-icon name="o-calendar" class="w-8 h-8 text-base-content/20" />
                        <div>
                            <p class="text-[10px] uppercase font-bold text-base-content/40 tracking-widest">Ingreso al Nodo</p>
                            <p class="text-sm font-bold">{{ $selectedItem->created_at->format('d M, Y') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <x-slot:actions>
            <x-mary-button label="Cerrar" @click="$wire.showDrawer = false" class="btn-ghost rounded-xl" />
        </x-slot:actions>
    </x-mary-drawer>
</div>
