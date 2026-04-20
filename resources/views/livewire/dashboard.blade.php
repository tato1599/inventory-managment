<div>
    {{-- HEADER --}}
    <x-mary-header title="Tablero de Control" subtitle="Resumen ejecutivo del estado del hardware en el Nodo NCIE" separator progress-indicator>
        <x-slot:actions>
            <x-mary-button icon="o-plus" label="Registro Rápido" class="btn-primary" link="{{ route('inventory.index') }}" />
        </x-slot:actions>
    </x-mary-header>

    <div class="space-y-12">
        {{-- STATS GRID --}}
        <section class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <x-mary-stat
                title="Capacidad Total"
                value="{{ number_format($this->stats['total_items']) }}"
                description="Activos en {{ $this->stats['categories_count'] }} áreas"
                icon="o-cpu-chip"
                class="bg-base-100 shadow-sm border-none rounded-2xl"
                color="text-primary"
            />

            <x-mary-stat
                title="En Uso"
                value="{{ number_format($this->stats['loaned_items']) }}"
                description="Proyectos activos"
                icon="o-rocket-launch"
                class="bg-base-100 shadow-sm border-none rounded-2xl"
                color="text-info"
            />

            <x-mary-stat
                title="En Mantenimiento"
                value="{{ number_format($this->stats['maintenance_items']) }}"
                description="Calibración y ajustes"
                icon="o-wrench-screwdriver"
                class="bg-base-100 shadow-sm border-none rounded-2xl"
                color="text-warning"
            />
            
            <x-mary-stat
                title="Áreas del Nodo"
                value="{{ $this->stats['locations_count'] }}"
                description="Zonas configuradas"
                icon="o-map-pin"
                class="bg-base-100 shadow-sm border-none rounded-2xl"
                color="text-success"
            />
        </section>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
            {{-- ACTIVITY FEED --}}
            <section class="lg:col-span-8">
                <x-mary-card title="Canal de Innovación" subtitle="Activos registrados recientemente" separator shadow>
                    <div class="space-y-2">
                        @forelse($this->recentItems as $item)
                            <div class="flex items-center p-4 hover:bg-base-200/50 rounded-2xl transition-colors group">
                                <div class="w-10 h-10 rounded-xl bg-primary/5 flex items-center justify-center text-primary shrink-0">
                                    <x-mary-icon name="o-cpu-chip" class="w-5 h-5" />
                                </div>
                                <div class="ml-4 flex-1">
                                    <div class="flex justify-between items-center">
                                        <p class="font-bold text-sm text-base-content">{{ $item->name }}</p>
                                        <span class="text-[10px] font-mono text-base-content/30">{{ $item->sku }}</span>
                                    </div>
                                    <p class="text-[10px] text-base-content/50 uppercase tracking-widest font-bold mt-0.5">
                                        {{ $item->category->name }} • {{ $item->location?->name ?: 'Sin área' }}
                                    </p>
                                </div>
                            </div>
                        @empty
                            <div class="py-12 text-center text-base-content/30 italic text-sm">
                                No hay actividad reciente registrada en el laboratorio.
                            </div>
                        @endforelse
                    </div>

                    <x-slot:actions>
                        <x-mary-button label="Ver todo el inventario" link="{{ route('inventory.index') }}" icon="o-arrow-right" class="btn-ghost btn-sm" />
                    </x-slot:actions>
                </x-mary-card>
            </section>

            {{-- ALERTS & SHORTCUTS --}}
            <aside class="lg:col-span-4 space-y-6">
                <!-- Operational Shortcuts -->
                <x-mary-card title="Herramientas Rápidas" shadow class="bg-neutral text-neutral-content rounded-3xl">
                    <div class="grid grid-cols-2 gap-3 mt-4">
                        <x-mary-button icon="o-qr-code" label="Escanear" class="btn-ghost bg-white/5 hover:bg-white/10 flex-col h-auto py-5 gap-2 border-none rounded-2xl text-[10px]" />
                        <x-mary-button icon="o-printer" label="Tickets" class="btn-ghost bg-white/5 hover:bg-white/10 flex-col h-auto py-5 gap-2 border-none rounded-2xl text-[10px]" />
                        <x-mary-button icon="o-check-badge" label="Auditar" class="btn-ghost bg-white/5 hover:bg-white/10 flex-col h-auto py-5 gap-2 border-none rounded-2xl text-[10px]" />
                        <x-mary-button icon="o-document-chart-bar" label="Reportes" class="btn-ghost bg-white/5 hover:bg-white/10 flex-col h-auto py-5 gap-2 border-none rounded-2xl text-[10px]" />
                    </div>
                </x-mary-card>

                <!-- Critical Alerts -->
                <x-mary-card title="Alertas Críticas" shadow separator @class(['border-t-4 border-t-error' => $this->alertItems->count() > 0])>
                    <div class="space-y-4">
                        @forelse($this->alertItems as $item)
                            <div class="flex items-center gap-3 p-3 bg-error/5 rounded-xl border border-error/10">
                                <x-mary-icon name="o-exclamation-triangle" class="text-error w-5 h-5 flex-shrink-0" />
                                <div class="min-w-0">
                                    <p class="font-bold text-xs truncate">{{ $item->name }}</p>
                                    <p class="text-[9px] text-error font-medium uppercase">{{ $item->status === 'maintenance' ? 'Revisión técnica' : 'Extraviado' }}</p>
                                </div>
                            </div>
                        @empty
                            <div class="flex flex-col items-center py-6 text-center">
                                <x-mary-icon name="o-shield-check" class="text-success w-8 h-8 opacity-20 mb-2" />
                                <p class="text-[10px] uppercase font-bold text-base-content/40 tracking-widest leading-tight">Estado nominal:<br>Sin alertas reportadas</p>
                            </div>
                        @endforelse
                    </div>
                </x-mary-card>
            </aside>
        </div>

        {{-- VISUAL TREND CARD --}}
        <x-mary-card title="Flujo de Prototipado" subtitle="Estimación de uso semanal de hardware" shadow>
            <div class="h-48 flex items-end justify-between space-x-2 px-2">
                @foreach($this->chartData as $index => $h)
                    <div class="w-full bg-base-200 rounded-t-xl relative group overflow-hidden" style="height: 100%">
                        <div class="absolute bottom-0 w-full bg-primary rounded-t-xl group-hover:brightness-110 transition-all duration-700" style="height: {{ $h }}%"></div>
                        <div class="absolute bottom-0 w-full bg-info/20 rounded-t-xl" style="height: {{ max(0, $h - 20) }}%"></div>
                    </div>
                @endforeach
            </div>
            <div class="flex justify-between mt-6 px-1 text-[10px] font-black text-base-content/30 tracking-widest border-t border-base-200 pt-6">
                <span>LUN</span><span>MAR</span><span>MIE</span><span>JUE</span><span>VIE</span><span>SAB</span><span>DOM</span>
            </div>
        </x-mary-card>
    </div>
</div>
