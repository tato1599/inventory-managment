<div>
    {{-- PREMIUM HEADER --}}
    <x-mary-header title="Tablero de Mando" subtitle="Análisis en tiempo real y control de activos operativos" separator progress-indicator>
        <x-slot:actions>
            <x-mary-button 
                icon="o-arrow-path" 
                @click="location.reload()"
                class="btn-ghost rounded-xl" 
                tooltip="Refrescar datos"
            />
            <x-mary-button 
                icon="o-plus" 
                label="Nuevo Préstamo" 
                link="{{ route('inventory.index') }}"
                class="btn-primary rounded-xl font-black shadow-lg shadow-primary/20 px-6" 
            />
        </x-slot:actions>
    </x-mary-header>

    <div class="space-y-10">
        {{-- ADVANCED STATS GRID --}}
        <section class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <div class="bg-base-100 p-6 rounded-[2rem] shadow-sm flex items-center gap-6 group hover:shadow-xl hover:shadow-primary/5 transition-all duration-500 border border-transparent hover:border-primary/10">
                <div class="w-16 h-16 bg-primary/10 rounded-2xl flex items-center justify-center text-primary group-hover:scale-110 transition-transform">
                    <x-mary-icon name="o-archive-box" class="w-8 h-8" />
                </div>
                <div>
                    <p class="text-[10px] uppercase font-black text-base-content/30 tracking-[0.2em] mb-1">Stock Total</p>
                    <h3 class="text-3xl font-black tracking-tighter leading-none">{{ number_format($this->stats['total_items']) }}</h3>
                </div>
            </div>

            <div class="bg-base-100 p-6 rounded-[2rem] shadow-sm flex items-center gap-6 group hover:shadow-xl hover:shadow-info/5 transition-all duration-500 border border-transparent hover:border-info/10">
                <div class="w-16 h-16 bg-info/10 rounded-2xl flex items-center justify-center text-info group-hover:scale-110 transition-transform">
                    <x-mary-icon name="o-arrow-up-right" class="w-8 h-8" />
                </div>
                <div>
                    <p class="text-[10px] uppercase font-black text-base-content/30 tracking-[0.2em] mb-1">En Préstamo</p>
                    <h3 class="text-3xl font-black tracking-tighter leading-none text-info">{{ number_format($this->stats['loaned_items']) }}</h3>
                </div>
            </div>

            <div class="bg-base-100 p-6 rounded-[2rem] shadow-sm flex items-center gap-6 group hover:shadow-xl hover:shadow-warning/5 transition-all duration-500 border border-transparent hover:border-warning/10">
                <div class="w-16 h-16 bg-warning/10 rounded-2xl flex items-center justify-center text-warning group-hover:scale-110 transition-transform">
                    <x-mary-icon name="o-wrench" class="w-8 h-8" />
                </div>
                <div>
                    <p class="text-[10px] uppercase font-black text-base-content/30 tracking-[0.2em] mb-1">Mantenimiento</p>
                    <h3 class="text-3xl font-black tracking-tighter leading-none text-warning">{{ number_format($this->stats['maintenance_items']) }}</h3>
                </div>
            </div>

            <div class="bg-base-100 p-6 rounded-[2rem] shadow-sm flex items-center gap-6 group hover:shadow-xl hover:shadow-error/5 transition-all duration-500 border border-transparent hover:border-error/10">
                <div class="w-16 h-16 bg-error/10 rounded-2xl flex items-center justify-center text-error group-hover:scale-110 transition-transform {{ $this->stats['overdue_loans'] > 0 ? 'animate-pulse' : '' }}">
                    <x-mary-icon name="o-clock" class="w-8 h-8" />
                </div>
                <div>
                    <p class="text-[10px] uppercase font-black text-base-content/30 tracking-[0.2em] mb-1">Retrasados</p>
                    <h3 class="text-3xl font-black tracking-tighter leading-none text-error">{{ number_format($this->stats['overdue_loans']) }}</h3>
                </div>
            </div>
        </section>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-10">
            {{-- LIVE ACTIVITY FEED --}}
            <section class="lg:col-span-8 space-y-6">
                <div class="flex items-center justify-between px-2">
                    <h2 class="text-xl font-black tracking-tight text-base-content">Actividad Reciente</h2>
                    <x-mary-button label="Bitácora Completa" link="{{ route('inventory.adjustments') }}" icon="o-list-bullet" class="btn-ghost btn-xs text-[10px] font-black uppercase tracking-widest opacity-50" />
                </div>
                
                <div class="bg-base-100 rounded-[2.5rem] shadow-sm overflow-hidden border border-base-200/50">
                    <div class="divide-y divide-base-200/50">
                        @forelse($this->recentActivity as $adj)
                            <div class="p-6 flex items-start gap-6 hover:bg-base-200/30 transition-all group">
                                <div class="w-12 h-12 bg-base-200 rounded-2xl flex items-center justify-center text-base-content/40 group-hover:bg-primary group-hover:text-white transition-all duration-500 shadow-sm">
                                    <x-mary-icon name="o-arrow-path" class="w-6 h-6" />
                                </div>
                                <div class="flex-1 min-w-0">
                                    <div class="flex justify-between items-start mb-1">
                                        <p class="font-black text-sm text-base-content group-hover:text-primary transition-colors">
                                            {{ $adj->item->name }}
                                        </p>
                                        <span class="text-[10px] font-mono text-base-content/30">{{ $adj->created_at->diffForHumans() }}</span>
                                    </div>
                                    <p class="text-xs text-base-content/60 leading-relaxed italic mb-3">"{{ $adj->notes }}"</p>
                                    <div class="flex items-center gap-3">
                                        <div class="flex items-center gap-1.5 bg-base-200/50 px-2 py-1 rounded-md">
                                            <div class="w-4 h-4 bg-primary/20 rounded-full flex items-center justify-center text-primary text-[8px] font-black">
                                                {{ substr($adj->user->name, 0, 1) }}
                                            </div>
                                            <span class="text-[9px] font-black uppercase text-base-content/40 tracking-widest">{{ $adj->user->name }}</span>
                                        </div>
                                        @if($adj->newLocation)
                                            <div class="flex items-center gap-1 text-[9px] font-black uppercase text-info opacity-70 tracking-widest">
                                                <x-mary-icon name="o-map-pin" class="w-3 h-3" />
                                                {{ $adj->newLocation->name }}
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="p-20 text-center flex flex-col items-center">
                                <x-mary-icon name="o-inbox" class="w-12 h-12 text-base-content/10 mb-4" />
                                <p class="text-xs font-bold text-base-content/30 uppercase tracking-widest">No hay movimientos recientes</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </section>

            {{-- SIDEBAR ALERTS & DISTRIBUTION --}}
            <aside class="lg:col-span-4 space-y-10">
                {{-- QUICK ACTION CARD --}}
                <div class="bg-gradient-to-br from-primary to-secondary p-8 rounded-[3rem] text-white shadow-2xl shadow-primary/20 relative overflow-hidden group">
                    <div class="absolute -right-10 -top-10 w-40 h-40 bg-white/10 rounded-full blur-3xl group-hover:scale-150 transition-transform duration-1000"></div>
                    <div class="relative z-10">
                        <h3 class="text-2xl font-black tracking-tighter mb-2">Asistente Operativo</h3>
                        <p class="text-xs text-white/70 font-medium leading-relaxed mb-8">Acciones rápidas para el control de inventario físico.</p>
                        <div class="grid grid-cols-2 gap-4">
                            <x-mary-button icon="o-qr-code" label="Escanear" class="btn-ghost bg-white/10 hover:bg-white/20 border-none rounded-2xl h-auto py-4 flex-col gap-2 text-[10px] text-white uppercase font-black" />
                            <x-mary-button icon="o-magnifying-glass" label="Buscar" link="{{ route('inventory.index') }}" class="btn-ghost bg-white/10 hover:bg-white/20 border-none rounded-2xl h-auto py-4 flex-col gap-2 text-[10px] text-white uppercase font-black" />
                        </div>
                    </div>
                </div>

                {{-- DISTRIBUTION --}}
                <div class="bg-base-100 p-8 rounded-[2.5rem] shadow-sm border border-base-200/50">
                    <h3 class="text-xs font-black uppercase tracking-widest text-base-content/30 mb-6 flex items-center gap-2">
                        <x-mary-icon name="o-chart-pie" class="w-4 h-4" />
                        Distribución por Área
                    </h3>
                    <div class="space-y-6">
                        @foreach($this->topLocations as $loc)
                            <div class="space-y-2">
                                <div class="flex justify-between items-end">
                                    <span class="text-xs font-black text-base-content/70 uppercase tracking-tight">{{ $loc->name }}</span>
                                    <span class="text-[10px] font-bold text-primary">{{ $loc->items_count }} act.</span>
                                </div>
                                <div class="h-2 bg-base-200 rounded-full overflow-hidden">
                                    <div class="h-full bg-primary rounded-full transition-all duration-1000" style="width: {{ ($loc->items_count / max(1, $this->stats['total_items'])) * 100 }}%"></div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                {{-- CRITICAL ALERTS --}}
                @if($this->alertItems->count() > 0)
                    <div class="space-y-4">
                        <h3 class="text-[10px] font-black uppercase tracking-[0.2em] text-error/60 px-2">Atención Requerida</h3>
                        @foreach($this->alertItems as $item)
                            <div class="bg-error/5 p-4 rounded-2xl border border-error/10 flex items-center gap-4 animate-in slide-in-from-right duration-500" style="animation-delay: {{ $loop->index * 100 }}ms">
                                <div class="w-10 h-10 bg-error/10 rounded-xl flex items-center justify-center text-error">
                                    <x-mary-icon name="o-exclamation-circle" class="w-6 h-6" />
                                </div>
                                <div class="min-w-0">
                                    <p class="text-xs font-black text-base-content truncate">{{ $item->name }}</p>
                                    <p class="text-[9px] font-black uppercase text-error opacity-60 tracking-tighter">
                                        {{ $item->isLoanOverdue() ? 'PRÉSTAMO VENCIDO' : 'ACTIVO EXTRAVIADO' }}
                                    </p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </aside>
        </div>

        {{-- WEEKLY TREND CHART --}}
        <section>
            <x-mary-card shadow class="rounded-[3rem] border-none overflow-hidden p-8">
                <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-10">
                    <div>
                        <h3 class="text-xl font-black tracking-tight text-base-content">Tendencia de Préstamos</h3>
                        <p class="text-xs text-base-content/40 font-medium">Flujo de salida de equipos en los últimos 7 días</p>
                    </div>
                    <div class="flex items-center gap-2">
                        <div class="flex items-center gap-1.5 bg-primary/5 px-3 py-1.5 rounded-full">
                            <div class="w-2 h-2 bg-primary rounded-full"></div>
                            <span class="text-[9px] font-black uppercase text-primary tracking-widest">Actividad Local</span>
                        </div>
                    </div>
                </div>

                <div class="h-56 flex items-end justify-between space-x-3 px-4">
                    @php $maxData = max(1, ...$this->chartData); @endphp
                    @foreach($this->chartData as $val)
                        <div class="flex-1 bg-base-200 rounded-2xl relative group transition-all duration-500 hover:bg-base-300" style="height: 100%">
                            <div class="absolute bottom-0 w-full bg-gradient-to-t from-primary to-primary-focus rounded-2xl transition-all duration-1000 shadow-[0_-4px_20px_rgba(var(--p),0.2)]" 
                                 style="height: {{ ($val / $maxData) * 100 }}%">
                                <div class="opacity-0 group-hover:opacity-100 absolute -top-8 left-1/2 -translate-x-1/2 bg-base-content text-base-100 text-[10px] font-black py-1 px-2 rounded-lg transition-all duration-300 pointer-events-none">
                                    {{ $val }}
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                <div class="flex justify-between mt-8 px-4 text-[9px] font-black text-base-content/30 tracking-[0.3em] uppercase">
                    <span>Lun</span><span>Mar</span><span>Mie</span><span>Jue</span><span>Vie</span><span>Sab</span><span>Dom</span>
                </div>
            </x-mary-card>
        </section>
    </div>
</div>
