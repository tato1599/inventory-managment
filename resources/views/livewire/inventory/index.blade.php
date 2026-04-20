<div>
    {{-- Editorial Header Section --}}
    <div class="mb-10 flex flex-col md:flex-row md:items-end justify-between gap-6 animate-in fade-in slide-in-from-top-4 duration-700">
        <div>
            <h1 class="text-4xl font-extrabold text-base-content tracking-tight mb-2 font-headline">Inventario de Innovación</h1>
            <p class="text-base-content/60 font-medium italic">Gestión avanzada de activos para IA, Impresión 3D y Prototipado NCIE.</p>
        </div>
        {{-- Actions --}}
        <div class="flex items-center gap-3">
            <x-mary-button 
                icon="o-plus" 
                label="Nuevo Artículo" 
                @click="$wire.createDrawer = true"
                class="btn btn-primary px-6 py-2.5 rounded-xl font-black shadow-xl shadow-primary/20 active:scale-95 border-none" 
            />
        </div>
    </div>

    {{-- Filters Bar --}}
    <div class="grid grid-cols-1 md:grid-cols-12 gap-4 mb-8">
        <div class="md:col-span-8">
            <x-mary-input 
                wire:model.live.debounce.300ms="search" 
                placeholder="Buscar por SKU, nombre o descripción..."
                icon="o-magnifying-glass" 
                class="w-full py-4 border-none rounded-2xl bg-base-100 shadow-sm text-base-content focus:ring-2 focus:ring-primary/20 transition-all" 
                clearable 
            />
        </div>
        <div class="md:col-span-4">
            <x-mary-select 
                wire:model.live="categoryId" 
                :options="$this->categories" 
                option-value="id" 
                option-label="name"
                placeholder="Todos los Departamentos" 
                class="w-full rounded-2xl bg-base-100 border-none shadow-sm h-14" 
            />
        </div>
    </div>

    {{-- Table Container --}}
    <div class="bg-base-100 rounded-3xl shadow-xl shadow-base-content/5 overflow-hidden border border-base-300 transition-colors duration-300">
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
            class="cursor-pointer mary-table-custom"
        >
            {{-- SKU Cell --}}
            @scope('cell_sku', $item)
                <span class="font-mono text-xs text-primary font-black tracking-widest bg-primary/10 px-2 py-1 rounded-md border border-primary/10">
                    {{ $item->sku }}
                </span>
            @endscope

            {{-- Location Cell --}}
            @scope('cell_location.name', $item)
                <span class="text-xs text-base-content/60 italic">
                    {{ $item->location?->name ?: 'Sin asignar' }}
                </span>
            @endscope

            {{-- Status Badge with Pulse --}}
            @scope('cell_status', $item)
                @php
                    $styles = match ($item->status) {
                        'available'   => ['bg' => 'bg-emerald-500/10', 'text' => 'text-emerald-600 dark:text-emerald-400', 'dot' => 'bg-emerald-500', 'label' => 'Disponible'],
                        'loaned'      => ['bg' => 'bg-info/10', 'text' => 'text-info', 'dot' => 'bg-info', 'label' => 'Prestado'],
                        'maintenance' => ['bg' => 'bg-warning/10', 'text' => 'text-warning', 'dot' => 'bg-warning', 'label' => 'Mantenimiento'],
                        'lost'        => ['bg' => 'bg-error/10', 'text' => 'text-error', 'dot' => 'bg-error', 'label' => 'Extraviado'],
                        default       => ['bg' => 'bg-base-200', 'text' => 'text-base-content/60', 'dot' => 'bg-base-content/40', 'label' => $item->status],
                    };
                @endphp
                <div class="flex items-center gap-2 {{ $styles['bg'] }} {{ $styles['text'] }} px-3 py-1.5 rounded-xl w-fit border border-current/5">
                    <span class="w-1.5 h-1.5 rounded-full {{ $styles['dot'] }} {{ $item->status === 'available' ? 'animate-pulse' : '' }}"></span>
                    <span class="text-[10px] font-black uppercase tracking-wider">{{ $styles['label'] }}</span>
                </div>
            @endscope

            {{-- Actions --}}
            @scope('actions', $item)
                <div class="flex justify-end gap-1">
                    <x-mary-button 
                        icon="o-pencil-square" 
                        class="btn-ghost btn-sm text-base-content/40 hover:text-primary transition-colors" 
                        tooltip="Editar artículo"
                    />
                    <x-mary-button 
                        icon="o-trash" 
                        class="btn-ghost btn-sm text-base-content/40 hover:text-error transition-colors" 
                        wire:confirm="¿Estás seguro de eliminar este artículo?"
                        wire:click.stop="delete({{ $item->id }})" 
                        tooltip="Eliminar artículo"
                    />
                </div>
            @endscope

        </x-mary-table>
    </div>

    {{-- Create Item Drawer --}}
    <x-mary-drawer wire:model="createDrawer" title="Nuevo Artículo" separator right class="w-11/12 lg:w-1/3 p-0">
        <x-mary-form wire:submit="saveItem" class="p-6 space-y-6">
            <div class="space-y-4">
                <x-mary-input label="Nombre del Artículo" wire:model="newItem.name" icon="o-cube" placeholder="Ej. Manuscrito Siglo XVII" />
                <x-mary-input label="SKU / Código" wire:model="newItem.sku" icon="o-qr-code" placeholder="Ej. MS-8822" />
                
                <div class="grid grid-cols-2 gap-4">
                    <x-mary-select 
                        label="Departamento" 
                        wire:model="newItem.category_id" 
                        :options="$this->categories" 
                        option-value="id"
                        option-label="name"
                        placeholder="Seleccionar..." 
                    />
                    <x-mary-select 
                        label="Área / Zona" 
                        wire:model="newItem.location_id" 
                        :options="$this->locations" 
                        option-value="id"
                        option-label="name"
                        placeholder="Sin asignar" 
                    />
                </div>

                <x-mary-select 
                    label="Estado Inicial" 
                    wire:model="newItem.status" 
                    :options="[
                        ['id' => 'available', 'name' => 'Disponible'],
                        ['id' => 'loaned', 'name' => 'Prestado'],
                        ['id' => 'maintenance', 'name' => 'Mantenimiento'],
                        ['id' => 'lost', 'name' => 'Extraviado'],
                    ]" 
                />

                <x-mary-textarea label="Descripción" wire:model="newItem.description" placeholder="Detalles sobre el origen o condición del artículo..." rows="4" />
            </div>

            <x-slot:actions>
                <x-mary-button label="Cancelar" @click="$wire.createDrawer = false" class="btn-ghost" />
                <x-mary-button label="Registrar Artículo" icon="o-check" type="submit" class="btn-primary rounded-xl" spinner="saveItem" />
            </x-slot:actions>
        </x-mary-form>
    </x-mary-drawer>

    {{-- Details Drawer --}}
    <x-mary-drawer wire:model="showDrawer" title="Detalles del Activo" separator right class="w-11/12 lg:w-1/3 p-0">
        @if ($selectedItem)
            <div class="p-6 space-y-8">
                {{-- Header Inside Drawer --}}
                <div class="space-y-4">
                    <div class="bg-primary h-32 rounded-3xl flex items-center justify-center shadow-xl shadow-primary/10">
                        <x-mary-icon name="o-cube" class="w-16 h-16 text-primary-content opacity-20" />
                    </div>
                    <div class="pt-2">
                        <h2 class="text-2xl font-black text-base-content capitalize">{{ $selectedItem->name }}</h2>
                        <span class="text-xs font-mono font-bold text-primary uppercase">{{ $selectedItem->sku }}</span>
                    </div>
                </div>

                {{-- Status and Category Info --}}
                <div class="grid grid-cols-2 gap-4">
                    <div class="p-4 bg-base-200 rounded-2xl border border-base-300">
                        <p class="text-[10px] uppercase font-bold text-base-content/40 mb-1">Estado</p>
                        <p class="font-bold text-base-content capitalize">{{ $selectedItem->status }}</p>
                    </div>
                    <div class="p-4 bg-base-200 rounded-2xl border border-base-300">
                        <p class="text-[10px] uppercase font-bold text-base-content/40 mb-1">Ubicación</p>
                        <p class="font-bold text-base-content truncate">{{ $selectedItem->location?->name ?: 'Sin asignar' }}</p>
                    </div>
                </div>

                {{-- Description --}}
                <div class="space-y-2">
                    <p class="text-[10px] uppercase font-bold text-base-content/40">Descripción completa</p>
                    <p class="text-base-content/70 text-sm leading-relaxed bg-base-200/50 p-4 rounded-2xl italic">
                        {{ $selectedItem->description ?: 'Sin descripción detallada.' }}
                    </p>
                </div>

                {{-- Metadata List --}}
                @if($selectedItem->metadata)
                    <div class="space-y-3">
                        <p class="text-[10px] uppercase font-bold text-base-content/40">Ficha Técnica</p>
                        <div class="space-y-2">
                            @foreach($selectedItem->metadata as $key => $value)
                                <div class="flex justify-between items-center text-sm p-3 bg-base-100 rounded-xl border border-base-300">
                                    <span class="text-base-content/50 capitalize">{{ str_replace('_', ' ', $key) }}</span>
                                    <span class="font-bold text-base-content">{{ is_array($value) ? implode(', ', $value) : $value }}</span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                {{-- Bottom Action inside Drawer --}}
                <div class="pt-6 border-t border-base-300">
                    <x-mary-button label="Editar Datos" icon="o-pencil" class="btn-primary w-full h-14 rounded-2xl" />
                </div>
            </div>
        @endif

        <x-slot:actions>
            <x-mary-button label="Cerrar" @click="$wire.showDrawer = false" class="btn-ghost" />
        </x-slot:actions>
    </x-mary-drawer>

    {{-- Bulk Action Button --}}
    @if (count($selectedIds) > 0)
        <div class="fixed bottom-10 left-1/2 -translate-x-1/2 z-50 animate-in slide-in-from-bottom-10 fade-in duration-500">
            <div class="bg-neutral text-neutral-content px-6 py-4 rounded-3xl shadow-2xl flex items-center gap-6 border border-neutral-content/10">
                <span class="text-sm font-bold">{{ count($selectedIds) }} artículos seleccionados</span>
                <div class="h-6 w-px bg-neutral-content/20"></div>
                <div class="flex gap-2">
                    <x-mary-button icon="o-printer" class="btn-sm btn-ghost hover:bg-neutral-focus" tooltip="Imprimir etiquetas" />
                    <x-mary-button icon="o-trash" class="btn-sm btn-ghost hover:text-error" tooltip="Eliminar selección" />
                    <x-mary-button label="Acción Masiva" class="btn-primary btn-sm rounded-xl px-4" />
                </div>
            </div>
        </div>
    @endif
</div>
