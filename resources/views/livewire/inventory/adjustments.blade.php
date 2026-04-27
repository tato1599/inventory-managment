<div>
    {{-- HEADER --}}
    <x-mary-header title="Ajustes de Inventario" subtitle="Registro histórico de cambios, traslados y correcciones manuales" separator progress-indicator>
        <x-slot:actions>
            <x-mary-button 
                icon="o-printer" 
                label="Exportar Log" 
                class="btn-ghost rounded-xl font-bold border-base-300" 
            />
        </x-slot:actions>
    </x-mary-header>

    {{-- FILTERS --}}
    <div class="mb-8 flex flex-col lg:flex-row gap-4 items-center bg-base-200/40 p-4 rounded-3xl border border-base-300/50">
        <div class="flex-1 w-full">
            <x-mary-input 
                wire:model.live.debounce.300ms="search" 
                placeholder="Buscar por activo, SKU o motivo..."
                icon="o-magnifying-glass" 
                class="border-none shadow-sm rounded-2xl h-14" 
                inline
                clearable 
            />
        </div>
        <x-mary-button 
            icon="o-arrow-path" 
            wire:click="clearFilters" 
            class="btn-ghost bg-base-100 shadow-sm rounded-2xl h-14 w-14" 
            tooltip="Limpiar filtros" 
        />
    </div>

    {{-- LOG TABLE --}}
    <x-mary-card shadow class="bg-base-100 border-none rounded-3xl overflow-hidden p-0">
        <x-mary-table 
            :headers="$headers" 
            :rows="$rows" 
            with-pagination 
            :sort-by="$sortBy"
            class="mary-table-custom"
        >
            {{-- Item Cell --}}
            @scope('cell_item.name', $adj)
                <div class="flex flex-col">
                    <span class="font-bold text-sm text-base-content">{{ $adj->item->name }}</span>
                    <span class="text-[10px] font-mono text-base-content/40 uppercase tracking-widest">{{ $adj->item->sku }}</span>
                </div>
            @endscope

            {{-- User Cell --}}
            @scope('cell_user.name', $adj)
                <div class="flex items-center gap-2">
                    <div class="w-6 h-6 bg-primary/10 rounded-full flex items-center justify-center text-primary text-[10px] font-black">
                        {{ substr($adj->user->name, 0, 1) }}
                    </div>
                    <span class="text-xs font-medium">{{ $adj->user->name }}</span>
                </div>
            @endscope

            {{-- Type Cell --}}
            @scope('cell_type', $adj)
                @php
                    $types = [
                        'status_change' => ['label' => 'Estado', 'color' => 'badge-info'],
                        'location_transfer' => ['label' => 'Traslado', 'color' => 'badge-warning'],
                        'correction' => ['label' => 'Corrección', 'color' => 'badge-neutral']
                    ];
                    $type = $types[$adj->type] ?? ['label' => $adj->type, 'color' => 'badge-ghost'];
                @endphp
                <div class="badge {{ $type['color'] }} badge-outline text-[9px] font-black uppercase tracking-tighter py-3 px-3 rounded-xl border-2">
                    {{ $type['label'] }}
                </div>
            @endscope

            {{-- Notes Cell --}}
            @scope('cell_notes', $adj)
                <div class="max-w-xs">
                    <p class="text-xs italic text-base-content/70 line-clamp-2">{{ $adj->notes }}</p>
                    <div class="mt-1 flex flex-wrap gap-1">
                        @if($adj->old_status != $adj->new_status)
                            <span class="text-[8px] font-bold text-primary/40 uppercase">{{ $adj->old_status }} → {{ $adj->new_status }}</span>
                        @endif
                        @if($adj->old_location_id != $adj->new_location_id)
                            <span class="text-[8px] font-bold text-info/40 uppercase">{{ $adj->oldLocation?->name ?: 'N/A' }} → {{ $adj->newLocation?->name }}</span>
                        @endif
                    </div>
                </div>
            @endscope

            {{-- Date Cell --}}
            @scope('cell_created_at', $adj)
                <div class="flex flex-col text-right pr-4">
                    <span class="text-xs font-bold">{{ $adj->created_at->format('d/m/Y') }}</span>
                    <span class="text-[10px] font-mono text-base-content/30">{{ $adj->created_at->format('H:i') }}</span>
                </div>
            @endscope
        </x-mary-table>
    </x-mary-card>
</div>
