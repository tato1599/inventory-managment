<div>
    {{-- HEADER --}}
    <x-mary-header title="Inventario de Activos" subtitle="Gestión integral de hardware, herramientas y equipamiento" separator progress-indicator>
        <x-slot:actions>
            <x-mary-button 
                icon="o-clipboard-document-check" 
                label="{{ $auditMode ? 'Finalizar Auditoría' : 'Inventario Cíclico' }}" 
                wire:click="toggleAuditMode"
                class="{{ $auditMode ? 'btn-warning' : 'btn-ghost border-base-300' }} rounded-xl font-bold" 
            />
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
            class="mary-table-custom {{ $auditMode ? 'mary-table-audit' : '' }}"
        >
            {{-- Audit Cell --}}
            @if($auditMode)
                @scope('cell_id', $item)
                    <div class="flex justify-center">
                        @if(isset($auditData[$item->id]))
                            <div class="w-8 h-8 bg-success/20 text-success rounded-full flex items-center justify-center animate-in zoom-in duration-300">
                                <x-mary-icon name="o-check" class="w-5 h-5" />
                            </div>
                        @else
                            <x-mary-button 
                                icon="o-check" 
                                wire:click.stop="markAsVerified({{ $item->id }})" 
                                class="btn-ghost btn-circle btn-sm border-base-300 hover:bg-success hover:text-white" 
                            />
                        @endif
                    </div>
                @endscope
            @endif
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
                    <span class="text-xs font-semibold uppercase tracking-tighter">{{ $item->location?->name ?: 'Área General' }}</span>
                </div>
            @endscope

            {{-- Loanable Cell --}}
            @scope('cell_is_loanable', $item)
                <div class="flex justify-center">
                    @if($item->is_loanable)
                        <div class="badge badge-success badge-outline gap-1 py-3 px-3 rounded-xl border-2">
                            <x-mary-icon name="o-check" class="w-3 h-3" />
                            <span class="text-[9px] font-black uppercase tracking-tighter">SÍ</span>
                        </div>
                    @else
                        <div class="badge badge-ghost gap-1 py-3 px-3 rounded-xl opacity-30">
                            <x-mary-icon name="o-x-mark" class="w-3 h-3" />
                            <span class="text-[9px] font-black uppercase tracking-tighter">NO</span>
                        </div>
                    @endif
                </div>
            @endscope

            {{-- Status Cell --}}
            @scope('cell_status', $item)
                @php
                    $statusStyles = [
                        'available' => 'badge-success',
                        'loaned' => 'badge-info',
                        'maintenance' => 'badge-warning',
                        'lost' => 'badge-error'
                    ];
                    $isOverdue = $item->isLoanOverdue();
                @endphp
                <div class="flex items-center gap-2">
                    <div class="badge {{ $statusStyles[$item->status] }} badge-outline font-black text-[10px] uppercase tracking-widest py-3 px-4 rounded-xl border-2 {{ $isOverdue ? 'border-error text-error animate-pulse' : '' }}">
                        {{ $item->status }}
                    </div>
                    @if($isOverdue)
                        <x-mary-icon name="o-clock" class="w-4 h-4 text-error" tooltip="¡PRÉSTAMO VENCIDO!" />
                    @endif
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
                    <x-mary-button icon="o-printer" label="Imprimir" wire:click="printLabels" class="btn-ghost btn-sm text-xs font-bold bg-white/10 hover:bg-white/20 border-none rounded-2xl" />
                    <x-mary-button icon="o-trash" label="Eliminar" class="btn-ghost btn-sm text-xs font-bold bg-error/20 hover:bg-error/40 border-none rounded-2xl text-white" wire:confirm="¿Eliminar seleccionados?" wire:click="deleteBatch" />
                </div>
            </div>
        </div>
    @endif

    {{-- Create Item Drawer --}}
    <x-mary-drawer wire:model="createDrawer" title="Nuevo Artículo" separator right class="w-11/12 lg:w-1/3 p-0">
        <div class="p-6 space-y-6">
            <div class="flex items-center gap-4 bg-base-200 p-6 rounded-2xl">
                <div class="w-12 h-12 bg-primary rounded-xl flex items-center justify-center text-white shadow-lg">
                    <x-mary-icon name="o-rocket-launch" class="w-6 h-6" />
                </div>
                <div>
                    <h3 class="text-lg font-black tracking-tight">Registro de Hardware</h3>
                    <p class="text-[10px] uppercase font-black text-primary tracking-[0.2em]">Gestión de Activos Tecnológicos</p>
                </div>
            </div>

            <div class="space-y-6">
                <x-mary-input label="Nombre del Artículo" wire:model="newItem.name" icon="o-pencil-square" placeholder="Ej. Estación de Trabajo GPU" class="rounded-xl" />
                <x-mary-input label="SKU / Identificador" wire:model="newItem.sku" icon="o-hashtag" placeholder="Ej. INV-001" class="rounded-xl font-mono" />
                <x-mary-input label="Existencia Inicial" type="number" wire:model="newItem.quantity" icon="o-archive-box" class="rounded-xl" />
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <x-mary-choices label="Categoría" wire:model="newItem.category_id" :options="$this->categories" icon="o-tag" placeholder="Elegir..." single />
                    <x-mary-choices label="Ubicación" wire:model="newItem.location_id" :options="$this->locations" icon="o-map-pin" placeholder="Elegir..." single />
                </div>

                <x-mary-select label="Estado Inicial" wire:model="newItem.status" :options="[['id' => 'available', 'name' => 'Disponible'], ['id' => 'loaned', 'name' => 'En Uso'], ['id' => 'maintenance', 'name' => 'Mantenimiento']]" icon="o-check-circle" class="rounded-xl" />

                <div class="bg-base-200/50 p-4 rounded-[2rem] border border-base-300/50 space-y-4">
                    <x-mary-toggle label="Habilitar para Préstamo" wire:model.live="newItem.is_loanable" tight class="font-bold text-primary" />
                    @if($newItem['is_loanable'])
                        <div class="space-y-4 animate-in slide-in-from-top-2 duration-300">
                            <x-mary-select label="Tipo de Uso" wire:model.live="newItem.loan_type" :options="[['id' => 'daily', 'name' => 'Días (Uso Prolongado)'], ['id' => 'hourly', 'name' => 'Horas (Equipo Especializado)']]" icon="o-clock" class="rounded-xl" />
                            <x-mary-input label="Duración Máxima" wire:model="newItem.max_loan_duration" type="number" placeholder="Ej. 48" suffix="{{ $newItem['loan_type'] == 'daily' ? 'Días' : 'Horas' }}" class="rounded-xl" />
                        </div>
                    @endif
                </div>

                <x-mary-textarea label="Descripción Técnica" wire:model="newItem.description" placeholder="Detalles del hardware..." rows="4" class="rounded-xl" />
            </div>
        </div>

        <x-slot:actions>
            <x-mary-button label="Cancelar" @click="$wire.createDrawer = false" class="btn-ghost rounded-xl font-bold" />
            <x-mary-button label="Registrar Activo" icon="o-check" wire:click="saveItem" class="btn-primary rounded-xl font-black shadow-lg shadow-primary/20 px-8" spinner="saveItem" />
        </x-slot:actions>
    </x-mary-drawer>

    {{-- Details Drawer --}}
    <x-mary-drawer wire:model="showDrawer" title="Expediente del Activo" separator right class="w-11/12 lg:w-1/3 p-0">
        @if ($selectedItem)
            <div class="p-8 space-y-8">
                {{-- Adjustment Action --}}
                <div class="flex justify-end">
                    <x-mary-button 
                        icon="o-adjustments-horizontal" 
                        label="Ajustar Inventario" 
                        wire:click="openAdjustment"
                        class="btn-ghost btn-sm text-[10px] font-black uppercase tracking-widest text-primary hover:bg-primary/5 rounded-xl" 
                    />
                </div>

                {{-- Hero Header --}}
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
                    <div class="absolute -right-10 -top-10 w-40 h-40 bg-white/10 rounded-full blur-3xl"></div>
                </div>

                {{-- Status Grid --}}
                <div class="grid grid-cols-3 gap-4">
                    <div class="bg-base-200/50 p-6 rounded-[2rem] border border-base-300/50">
                        <p class="text-[10px] uppercase font-black text-base-content/30 tracking-widest mb-2">Estado</p>
                        @php $statusColors = ['available' => 'text-success', 'loaned' => 'text-info', 'maintenance' => 'text-warning', 'lost' => 'text-error']; @endphp
                        <span class="text-sm font-black {{ $statusColors[$selectedItem->status] }} uppercase tracking-tighter">{{ $selectedItem->status }}</span>
                    </div>
                    <div class="bg-base-200/50 p-6 rounded-[2rem] border border-base-300/50">
                        <p class="text-[10px] uppercase font-black text-base-content/30 tracking-widest mb-2">Existencia</p>
                        <span class="text-sm font-black text-primary tracking-tighter">{{ $selectedItem->quantity }} unid.</span>
                    </div>
                    <div class="bg-base-200/50 p-6 rounded-[2rem] border border-base-300/50">
                        <p class="text-[10px] uppercase font-black text-base-content/30 tracking-widest mb-2">Ubicación</p>
                        <span class="text-sm font-black text-base-content uppercase tracking-tighter">{{ $selectedItem->location?->name ?: 'GENERAL' }}</span>
                    </div>
                </div>

                @if($selectedItem->is_loanable)
                    <div class="bg-primary/5 p-8 rounded-[2.5rem] border border-primary/20 shadow-inner">
                        <div class="flex items-center gap-4 mb-6">
                            <div class="w-10 h-10 bg-primary rounded-xl flex items-center justify-center text-white"><x-mary-icon name="o-arrows-right-left" class="w-5 h-5" /></div>
                            <div>
                                <h4 class="text-xs font-black uppercase tracking-widest text-primary">Política de Préstamo</h4>
                                <p class="text-[10px] text-primary/50 font-bold uppercase tracking-tighter">Habilitado para salida</p>
                            </div>
                        </div>
                        <div class="grid grid-cols-2 gap-8">
                            <div class="space-y-1">
                                <p class="text-[9px] uppercase font-black text-base-content/30 tracking-widest">Modalidad</p>
                                <div class="flex items-center gap-2"><x-mary-icon name="{{ $selectedItem->loan_type == 'daily' ? 'o-calendar' : 'o-clock' }}" class="w-4 h-4 text-primary/60" /><p class="text-sm font-black text-primary uppercase">{{ $selectedItem->loan_type == 'daily' ? 'Por Días' : 'Por Horas' }}</p></div>
                            </div>
                            <div class="space-y-1">
                                <p class="text-[9px] uppercase font-black text-base-content/30 tracking-widest">Tiempo Límite</p>
                                <div class="flex items-center gap-2"><x-mary-icon name="o-variable" class="w-4 h-4 text-primary/60" /><p class="text-sm font-black text-primary uppercase">{{ $selectedItem->max_loan_duration ?: 'Ilimitado' }} {{ $selectedItem->loan_type == 'daily' ? 'Días' : 'Horas' }}</p></div>
                            </div>
                        </div>
                    </div>

                    @if($selectedItem->status == 'available')
                        <div class="bg-base-200/50 p-8 rounded-[2.5rem] border border-base-300 space-y-6">
                            <div class="flex items-center gap-3"><x-mary-icon name="o-user-plus" class="w-5 h-5 text-primary" /><h4 class="text-xs font-black uppercase tracking-widest">Asignar Préstamo</h4></div>
                            <x-mary-input label="Nombre del Beneficiario" wire:model="loanData.borrower_name" placeholder="Ej. Juan Pérez" icon="o-user" class="rounded-xl" />
                            <x-mary-input label="Matrícula / ID" wire:model="loanData.borrower_id_number" placeholder="Ej. L0123456" icon="o-identification" class="rounded-xl" />
                            <x-mary-button label="Registrar Salida" wire:click="registerLoan" class="btn-primary w-full rounded-2xl font-black shadow-lg shadow-primary/20" spinner="registerLoan" />
                        </div>
                    @elseif($selectedItem->status == 'loaned')
                        <div class="bg-error/5 p-8 rounded-[2.5rem] border border-error/20 space-y-6">
                            <div class="flex items-center gap-4">
                                <div class="w-12 h-12 bg-error rounded-2xl flex items-center justify-center text-white shadow-lg"><x-mary-icon name="o-user-circle" class="w-7 h-7" /></div>
                                <div><h4 class="text-[10px] font-black uppercase tracking-widest text-error opacity-70">En posesión de:</h4><p class="text-xl font-black text-base-content leading-none">{{ $selectedItem->current_loan?->borrower_name }}</p></div>
                            </div>
                            <div class="flex items-center justify-between p-4 bg-white/50 dark:bg-base-100/50 rounded-2xl border border-dashed border-error/20">
                                <div><p class="text-[9px] uppercase font-black text-base-content/30 tracking-widest">Fecha de Salida</p><p class="text-xs font-bold">{{ $selectedItem->current_loan?->loaned_at->format('d/m/Y H:i') }}</p></div>
                                <x-mary-button label="Recibir Equipo" wire:click="returnItem" class="btn-error btn-sm rounded-xl font-black text-white" spinner="returnItem" />
                            </div>
                        </div>
                    @endif
                @endif

                {{-- Quick Consumption (for materials) --}}
                <div class="bg-amber-50/50 p-8 rounded-[2.5rem] border border-amber-200/50 space-y-4">
                    <div class="flex items-center gap-3">
                        <x-mary-icon name="o-beaker" class="w-5 h-5 text-amber-600" />
                        <h4 class="text-xs font-black uppercase tracking-widest text-amber-900">Consumo de Material</h4>
                    </div>
                    <div class="flex gap-2">
                        <x-mary-input type="number" wire:model="consumeAmount" placeholder="Cant." class="rounded-xl w-24" />
                        <x-mary-button label="Descontar Stock" wire:click="consumeStock" class="btn-warning flex-1 rounded-xl font-black" spinner="consumeStock" />
                    </div>
                    <p class="text-[9px] text-amber-600/60 font-bold uppercase tracking-tighter">Úsalo para registrar el gasto de materiales consumibles.</p>
                </div>

                <div class="space-y-4">
                    <x-mary-hr label="Ficha Técnica" class="opacity-50" />
                    <p class="text-xs text-base-content/70 leading-relaxed bg-base-200/30 p-6 rounded-[2rem] border border-base-300/30 italic">{{ $selectedItem->description ?: 'No hay descripción adicional para este activo.' }}</p>
                </div>

                <div class="space-y-4">
                    <x-mary-hr label="Historial de Auditoría" class="opacity-50" />
                    <div class="space-y-3">
                        @forelse($selectedItem->adjustments->sortByDesc('created_at') as $adj)
                            <div class="bg-base-200/50 p-4 rounded-2xl border border-base-300/50 group hover:border-primary/30 transition-colors">
                                <div class="flex justify-between items-center mb-2">
                                    <span class="text-[9px] font-black uppercase text-primary tracking-widest">{{ $adj->user->name }}</span>
                                    <span class="text-[9px] font-mono text-base-content/30">{{ $adj->created_at->format('d/m/Y H:i') }}</span>
                                </div>
                                <p class="text-xs font-bold text-base-content leading-tight mb-2">{{ $adj->notes }}</p>
                                <div class="flex flex-wrap gap-2">
                                    @if($adj->old_quantity != $adj->new_quantity)<div class="badge badge-ghost text-[8px] font-black uppercase py-2 tracking-tighter">Stock: {{ $adj->old_quantity }} → {{ $adj->new_quantity }}</div>@endif
                                    @if($adj->old_status != $adj->new_status)<div class="badge badge-ghost text-[8px] font-black uppercase py-2 tracking-tighter">{{ $adj->old_status }} → {{ $adj->new_status }}</div>@endif
                                    @if($adj->old_location_id != $adj->new_location_id)<div class="badge badge-ghost text-[8px] font-black uppercase py-2 tracking-tighter">{{ $adj->oldLocation?->name ?: 'N/A' }} → {{ $adj->newLocation?->name }}</div>@endif
                                </div>
                            </div>
                        @empty
                            <div class="py-8 text-center bg-base-200/20 rounded-[2rem] border border-dashed border-base-300"><p class="text-[10px] uppercase font-bold text-base-content/20 tracking-widest">Sin ajustes registrados</p></div>
                        @endforelse
                    </div>
                </div>
            </div>
        @endif
    </x-mary-drawer>

    {{-- Adjustment Drawer --}}
    <x-mary-drawer wire:model="showAdjustmentDrawer" title="Ajuste Manual de Inventario" separator right class="w-11/12 lg:w-1/3 p-0">
        @if($selectedItem)
            <x-mary-form wire:submit="makeAdjustment" class="p-6 space-y-6">
                <div class="bg-primary/5 p-8 rounded-[2.5rem] mb-4 border border-primary/10 flex flex-col items-center text-center">
                    <x-mary-icon name="o-adjustments-vertical" class="w-10 h-10 text-primary opacity-20 mb-3" />
                    <p class="text-[10px] uppercase font-black text-primary/40 tracking-widest mb-1">Ajustando Activo</p>
                    <h3 class="text-lg font-black text-base-content leading-tight">{{ $selectedItem->name }}</h3>
                    <p class="text-xs font-mono text-base-content/30">{{ $selectedItem->sku }}</p>
                </div>
                <x-mary-select label="Cambiar Estado" wire:model="adjustmentData.new_status" :options="[['id' => 'available', 'name' => 'Disponible'], ['id' => 'loaned', 'name' => 'En Uso'], ['id' => 'maintenance', 'name' => 'Mantenimiento'], ['id' => 'lost', 'name' => 'Extraviado/Baja']]" icon="o-arrow-path" class="rounded-xl" />
                <x-mary-input label="Ajustar Cantidad" type="number" wire:model="adjustmentData.new_quantity" icon="o-archive-box" class="rounded-xl" />
                <x-mary-choices label="Mover a Ubicación" wire:model="adjustmentData.new_location_id" :options="$this->locations" icon="o-map-pin" placeholder="Elegir..." single />
                <x-mary-textarea label="Motivo del Ajuste" wire:model="adjustmentData.notes" placeholder="Ej. Traslado a taller, reporte de daño físico..." rows="4" class="rounded-xl" />
                <x-slot:actions>
                    <x-mary-button label="Cancelar" @click="$wire.showAdjustmentDrawer = false" class="btn-ghost rounded-xl" />
                    <x-mary-button label="Confirmar Ajuste" icon="o-check" type="submit" class="btn-primary rounded-xl px-8 font-black shadow-lg shadow-primary/20" spinner="makeAdjustment" />
                </x-slot:actions>
            </x-mary-form>
        @endif
    </x-mary-drawer>
</div>
