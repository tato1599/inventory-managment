<div>
    {{-- Editorial Header Section --}}
    <div class="mb-10 flex flex-col md:flex-row md:items-end justify-between gap-6 animate-in fade-in slide-in-from-top-4 duration-700">
        <div>
            <h1 class="text-4xl font-extrabold text-slate-900 dark:text-white tracking-tight mb-2 font-headline">Inventario de Archivo</h1>
            <p class="text-slate-500 dark:text-slate-400 font-medium italic">Control inteligente y gestión de activos históricos y académicos.</p>
        </div>
        {{-- Actions --}}
        <div class="flex items-center gap-3">
            <x-mary-button 
                icon="o-arrow-path" 
                label="Sincronizar" 
                wire:click="$refresh"
                class="bg-white dark:bg-slate-800 px-5 py-2.5 rounded-xl text-slate-700 dark:text-slate-200 font-bold hover:bg-slate-100 dark:hover:bg-slate-700 transition-all duration-200 border-none shadow-sm" 
            />
            <x-mary-button 
                icon="o-plus" 
                label="Nuevo Artículo" 
                class="bg-indigo-600 px-6 py-2.5 rounded-xl text-white font-black hover:bg-indigo-700 transition-all duration-300 shadow-xl shadow-indigo-500/20 active:scale-95 border-none" 
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
                class="w-full py-4 border-none rounded-2xl bg-white dark:bg-slate-900 shadow-sm text-slate-600 dark:text-slate-300 focus:ring-2 focus:ring-indigo-500/20 transition-all" 
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
                class="w-full rounded-2xl bg-white dark:bg-slate-900 border-none shadow-sm h-14" 
            />
        </div>
    </div>

    {{-- Table Container --}}
    <div class="bg-white dark:bg-slate-900 rounded-3xl shadow-xl shadow-slate-200/50 dark:shadow-none overflow-hidden border border-slate-100 dark:border-slate-800 transition-colors duration-300">
        <x-mary-table 
            :headers="$this->headers" 
            :rows="$this->items" 
            with-pagination 
            per-page="perPage"
            :sort-by="$sortBy" 
            selectable 
            selectable-key="id" 
            wire:model.live="selectedIds"
            @row-click="$wire.showDetails($event.detail.id)" 
            class="cursor-pointer mary-table-custom"
        >
            {{-- SKU Cell --}}
            @scope('cell_sku', $item)
                <span class="font-mono text-xs text-indigo-600 dark:text-indigo-400 font-black tracking-widest bg-indigo-50 dark:bg-indigo-900/30 px-2 py-1 rounded-md border border-indigo-100 dark:border-indigo-800/50">
                    {{ $item->sku }}
                </span>
            @endscope

            {{-- Name and Details --}}
            @scope('cell_name', $item)
            <div class="flex flex-col py-1">
                <span class="font-bold text-slate-900 dark:text-white">{{ $item->name }}</span>
                @if($item->description)
                    <span class="text-[11px] text-slate-500 dark:text-slate-400 line-clamp-1 max-w-[250px] italic">
                        {{ $item->description }}
                    </span>
                @endif
            </div>
            @endscope

            {{-- Category Badge --}}
            @scope('cell_category.name', $item)
                <span class="text-xs font-medium text-slate-600 dark:text-slate-300 flex items-center gap-1.5">
                    <span class="w-1.5 h-1.5 rounded-full bg-slate-300 dark:bg-slate-600"></span>
                    {{ $item->category->name }}
                </span>
            @endscope

            {{-- Status Badge with Pulse --}}
            @scope('cell_status', $item)
                @php
                    $styles = match ($item->status) {
                        'available'   => ['bg' => 'bg-emerald-100 dark:bg-emerald-900/30', 'text' => 'text-emerald-700 dark:text-emerald-400', 'dot' => 'bg-emerald-500', 'label' => 'Disponible'],
                        'loaned'      => ['bg' => 'bg-blue-100 dark:bg-blue-900/30', 'text' => 'text-blue-700 dark:text-blue-400', 'dot' => 'bg-blue-500', 'label' => 'Prestado'],
                        'maintenance' => ['bg' => 'bg-amber-100 dark:bg-amber-900/30', 'text' => 'text-amber-700 dark:text-amber-400', 'dot' => 'bg-amber-500', 'label' => 'Mantenimiento'],
                        'lost'        => ['bg' => 'bg-red-100 dark:bg-red-900/30', 'text' => 'text-red-700 dark:text-red-400', 'dot' => 'bg-red-500', 'label' => 'Extraviado'],
                        default       => ['bg' => 'bg-slate-100 dark:bg-slate-800', 'text' => 'text-slate-700 dark:text-slate-400', 'dot' => 'bg-slate-500', 'label' => $item->status],
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
                        class="btn-ghost btn-sm text-slate-400 hover:text-indigo-600 transition-colors" 
                        tooltip="Editar artículo"
                    />
                    <x-mary-button 
                        icon="o-trash" 
                        class="btn-ghost btn-sm text-slate-400 hover:text-red-600 transition-colors" 
                        wire:confirm="¿Estás seguro de eliminar este artículo?"
                        wire:click.stop="delete({{ $item->id }})" 
                        tooltip="Eliminar artículo"
                    />
                </div>
            @endscope

        </x-mary-table>
    </div>

    {{-- Details Drawer --}}
    <x-mary-drawer wire:model="showDrawer" title="Detalles del Artículo" separator right class="w-11/12 lg:w-1/3 p-0">
        @if ($selectedItem)
            <div class="p-6 space-y-8">
                {{-- Header Inside Drawer --}}
                <div class="space-y-4">
                    <div class="bg-indigo-600 h-32 rounded-3xl flex items-center justify-center shadow-xl shadow-indigo-500/10">
                        <x-mary-icon name="o-cube" class="w-16 h-16 text-white opacity-20" />
                    </div>
                    <div class="pt-2">
                        <h2 class="text-2xl font-black text-slate-900 dark:text-white capitalize">{{ $selectedItem->name }}</h2>
                        <span class="text-xs font-mono font-bold text-indigo-600 uppercase">{{ $selectedItem->sku }}</span>
                    </div>
                </div>

                {{-- Status and Category Info --}}
                <div class="grid grid-cols-2 gap-4">
                    <div class="p-4 bg-slate-50 dark:bg-slate-800/50 rounded-2xl border border-slate-100 dark:border-slate-800">
                        <p class="text-[10px] uppercase font-bold text-slate-400 mb-1">Estado</p>
                        <p class="font-bold text-slate-900 dark:text-white capitalize">{{ $selectedItem->status }}</p>
                    </div>
                    <div class="p-4 bg-slate-50 dark:bg-slate-800/50 rounded-2xl border border-slate-100 dark:border-slate-800">
                        <p class="text-[10px] uppercase font-bold text-slate-400 mb-1">Departamento</p>
                        <p class="font-bold text-slate-900 dark:text-white">{{ $selectedItem->category->name }}</p>
                    </div>
                </div>

                {{-- Description --}}
                <div class="space-y-2">
                    <p class="text-[10px] uppercase font-bold text-slate-400">Descripción completa</p>
                    <p class="text-slate-600 dark:text-slate-300 text-sm leading-relaxed bg-slate-50 dark:bg-slate-800/20 p-4 rounded-2xl italic">
                        {{ $selectedItem->description ?: 'Sin descripción detallada.' }}
                    </p>
                </div>

                {{-- Metadata List --}}
                @if($selectedItem->metadata)
                    <div class="space-y-3">
                        <p class="text-[10px] uppercase font-bold text-slate-400">Ficha Técnica</p>
                        <div class="space-y-2">
                            @foreach($selectedItem->metadata as $key => $value)
                                <div class="flex justify-between items-center text-sm p-3 bg-white dark:bg-slate-900 rounded-xl border border-slate-100 dark:border-slate-800">
                                    <span class="text-slate-500 capitalize">{{ str_replace('_', ' ', $key) }}</span>
                                    <span class="font-bold text-slate-900 dark:text-white">{{ is_array($value) ? implode(', ', $value) : $value }}</span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                {{-- Bottom Action inside Drawer --}}
                <div class="pt-6 border-t border-slate-100 dark:border-slate-800">
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
            <div class="bg-slate-900 dark:bg-white text-white dark:text-slate-900 px-6 py-4 rounded-3xl shadow-2xl flex items-center gap-6 border border-slate-700 dark:border-slate-200">
                <span class="text-sm font-bold">{{ count($selectedIds) }} artículos seleccionados</span>
                <div class="h-6 w-px bg-slate-700 dark:bg-slate-200"></div>
                <div class="flex gap-2">
                    <x-mary-button icon="o-printer" class="btn-sm btn-ghost hover:bg-slate-800 dark:hover:bg-slate-100" tooltip="Imprimir etiquetas" />
                    <x-mary-button icon="o-trash" class="btn-sm btn-ghost hover:text-red-500" tooltip="Eliminar selección" />
                    <x-mary-button label="Acción Masiva" class="btn-primary btn-sm rounded-xl px-4" />
                </div>
            </div>
        </div>
    @endif
</div>
