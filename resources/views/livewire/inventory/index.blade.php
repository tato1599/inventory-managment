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
    <div class="mb-8 flex flex-col lg:flex-row gap-4 items-center bg-base-200/40 p-4 rounded-3xl border border-base-300/50">
        <div class="flex-1 w-full">
            <x-mary-input 
                wire:model.live.debounce.300ms="search" 
                placeholder="Nombre, SKU o descripción..."
                icon="o-magnifying-glass" 
                class="border-none shadow-sm rounded-2xl h-14" 
                inline
                clearable 
            />
        </div>
        <div class="w-full lg:w-72">
            <x-mary-choices 
                wire:model.live="categoryId" 
                :options="$this->categories" 
                placeholder="Categoría..." 
                icon="o-tag" 
                single
                class="border-none shadow-sm rounded-2xl"
            />
        </div>
        <div class="flex gap-2 shrink-0">
            <x-mary-button 
                icon="o-arrow-path" 
                wire:click="clearFilters" 
                class="btn-ghost bg-base-100 shadow-sm rounded-2xl h-14 w-14" 
                tooltip="Limpiar filtros" 
            />
            <x-mary-button 
                icon="o-funnel" 
                class="btn-primary shadow-lg shadow-primary/20 rounded-2xl h-14 px-6 font-bold" 
                label="Filtrar" 
            />
        </div>
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
                <div class="flex items-center gap-4 py-2">
                    <div class="w-12 h-12 bg-primary/10 rounded-2xl flex items-center justify-center text-primary group-hover:scale-110 transition-transform duration-300">
                        <x-mary-icon name="o-cpu-chip" class="w-6 h-6" />
                    </div>
                    <div>
                        <p class="font-bold text-sm leading-tight text-base-content group-hover:text-primary transition-colors">{{ $item->name }}</p>
                        <p class="text-[10px] text-base-content/40 uppercase font-black tracking-widest mt-0.5">{{ $item->category->name }}</p>
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
                <div class="badge {{ $colors[$item->status] }} badge-outline font-bold text-[10px] py-3 px-4 border-2">
                    {{ $labels[$item->status] }}
                </div>
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
        <div class="fixed bottom-10 left-1/2 -translate-x-1/2 z-50 animate-in slide-in-from-bottom-10 duration-500">
            <div class="bg-gradient-to-r from-primary to-secondary text-white px-10 py-5 rounded-[2.5rem] shadow-2xl flex items-center gap-8 border-none backdrop-blur-2xl">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-white/20 rounded-full flex items-center justify-center">
                        <span class="text-sm font-black">{{ count($selectedIds) }}</span>
                    </div>
                    <p class="text-xs uppercase font-black tracking-widest">Activos seleccionados</p>
                </div>
                <div class="h-8 w-px bg-white/20"></div>
                <div class="flex items-center gap-3">
                    <x-mary-button icon="o-printer" label="Imprimir" class="btn-ghost btn-sm text-xs font-bold bg-white/10 hover:bg-white/20 border-none rounded-2xl" />
                    <x-mary-button icon="o-trash" label="Eliminar" class="btn-ghost btn-sm text-xs font-bold bg-error/20 hover:bg-error/40 border-none rounded-2xl text-white" wire:confirm="¿Eliminar seleccionados?" wire:click="deleteBatch" />
                </div>
            </div>
        </div>
    @endif

    {{-- Create Item Drawer --}}
    <x-mary-drawer wire:model="createDrawer" title="Nuevo Artículo" separator right class="w-11/12 lg:w-1/3 p-0">
        <div class="p-6 space-y-6">
            {{-- Header section --}}
            <div class="flex items-center gap-4 bg-base-200 p-6 rounded-2xl">
                <div class="w-12 h-12 bg-primary rounded-xl flex items-center justify-center text-white shadow-lg">
                    <x-mary-icon name="o-rocket-launch" class="w-6 h-6" />
                </div>
                <div>
                    <h3 class="text-lg font-black tracking-tight">Registro de Hardware</h3>
                    <p class="text-[10px] uppercase font-bold text-base-content/40 tracking-widest">Añadir al inventario del Nodo</p>
                </div>
            </div>

            <div class="space-y-6">
                <x-mary-input 
                    label="Nombre del Artículo" 
                    wire:model="newItem.name" 
                    icon="o-pencil-square" 
                    placeholder="Ej. Estación de Trabajo GPU" 
                    class="rounded-xl"
                />

                <x-mary-input 
                    label="SKU / Identificador" 
                    wire:model="newItem.sku" 
                    icon="o-hashtag" 
                    placeholder="Ej. NCIE-IA-042" 
                    class="rounded-xl font-mono"
                />
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <x-mary-choices 
                        label="Categoría" 
                        wire:model="newItem.category_id" 
                        :options="$this->categories" 
                        icon="o-tag" 
                        placeholder="Elegir..." 
                        single
                    />
                    <x-mary-choices 
                        label="Ubicación" 
                        wire:model="newItem.location_id" 
                        :options="$this->locations" 
                        icon="o-map-pin" 
                        placeholder="Elegir..." 
                        single
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
                    class="rounded-xl"
                />

                <x-mary-textarea 
                    label="Descripción Técnica" 
                    wire:model="newItem.description" 
                    placeholder="Detalles del hardware..." 
                    rows="4" 
                    class="rounded-xl" 
                />
            </div>
        </div>

        <x-slot:actions>
            <x-mary-button 
                label="Cancelar" 
                @click="$wire.createDrawer = false" 
                class="btn-ghost rounded-xl font-bold" 
            />
            <x-mary-button 
                label="Registrar Activo" 
                icon="o-check" 
                wire:click="saveItem" 
                class="btn-primary rounded-xl font-black shadow-lg shadow-primary/20 px-8" 
                spinner="saveItem" 
            />
        </x-slot:actions>
    </x-mary-drawer>

    {{-- Details Drawer --}}
    <x-mary-drawer wire:model="showDrawer" title="Expediente del Activo" separator right class="w-11/12 lg:w-1/3 p-0">
        @if ($selectedItem)
            <div class="p-8 space-y-8">
                {{-- Hero Header Inside Drawer --}}
                <div class="relative overflow-hidden bg-gradient-to-br from-primary to-secondary p-8 rounded-[2.5rem] text-white shadow-2xl shadow-primary/20">
                    <div class="relative z-10">
                        <div class="w-14 h-14 bg-white/20 backdrop-blur-md rounded-2xl flex items-center justify-center mb-4">
                            <x-mary-icon name="o-cpu-chip" class="w-8 h-8 text-white" />
                        </div>
                        <h2 class="text-3xl font-black tracking-tighter leading-none">{{ $selectedItem->name }}</h2>
                        <div class="flex items-center gap-2 mt-3">
                            <span class="text-[10px] font-mono bg-white/20 px-2 py-1 rounded-md uppercase tracking-widest">{{ $selectedItem->sku }}</span>
                            <span class="text-[10px] font-black uppercase tracking-widest bg-white text-primary px-3 py-1 rounded-full">{{ $selectedItem->category->name }}</span>
                        </div>
                    </div>
                    {{-- Decorative Circle --}}
                    <div class="absolute -right-10 -top-10 w-40 h-40 bg-white/10 rounded-full blur-3xl"></div>
                </div>

                {{-- Status & Location Grid --}}
                <div class="grid grid-cols-2 gap-4">
                    <div class="bg-base-200/50 p-6 rounded-[2rem] border border-base-300/50">
                        <p class="text-[10px] uppercase font-black text-base-content/30 tracking-widest mb-2">Estado</p>
                        @php
                            $statusColors = [
                                'available' => 'text-success',
                                'loaned' => 'text-info',
                                'maintenance' => 'text-warning',
                                'lost' => 'text-error'
                            ];
                        @endphp
                        <span class="text-sm font-black {{ $statusColors[$selectedItem->status] }} uppercase tracking-tighter">
                            {{ $selectedItem->status }}
                        </span>
                    </div>
                    <div class="bg-base-200/50 p-6 rounded-[2rem] border border-base-300/50">
                        <p class="text-[10px] uppercase font-black text-base-content/30 tracking-widest mb-2">Ubicación</p>
                        <span class="text-sm font-black text-base-content uppercase tracking-tighter">{{ $selectedItem->location?->name ?: 'ÁREA GENERAL' }}</span>
                    </div>
                </div>

                {{-- Description Section --}}
                <div class="space-y-4">
                    <x-mary-hr label="Ficha Técnica" class="opacity-50" />
                    <div class="bg-base-100 border border-base-300 rounded-[2rem] p-8 shadow-sm">
                        <p class="text-sm leading-relaxed text-base-content/70 italic">
                            {{ $selectedItem->description ?: 'Sin especificaciones técnicas registradas para este activo.' }}
                        </p>
                    </div>
                </div>

                {{-- Footer Info --}}
                <div class="flex items-center justify-between p-6 bg-base-200/30 rounded-2xl border border-dashed border-base-300">
                    <div class="flex items-center gap-3">
                        <x-mary-icon name="o-calendar" class="w-5 h-5 text-base-content/30" />
                        <div>
                            <p class="text-[9px] uppercase font-black text-base-content/30 tracking-widest">Fecha de Registro</p>
                            <p class="text-xs font-bold">{{ $selectedItem->created_at->format('d/m/Y') }}</p>
                        </div>
                    </div>
                    <x-mary-button icon="o-pencil" class="btn-ghost btn-sm rounded-xl" tooltip="Editar información" />
                </div>
            </div>
        @endif

        <x-slot:actions>
            <div class="px-4 w-full">
                <x-mary-button label="Cerrar Expediente" @click="$wire.showDrawer = false" class="btn-ghost rounded-2xl font-black text-xs uppercase tracking-widest w-full py-4" />
            </div>
        </x-slot:actions>
    </x-mary-drawer>
</div>
