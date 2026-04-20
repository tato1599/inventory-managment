<div>
    <div class="space-y-12">
        <!-- Hero Stats: Bento Style -->
        <section class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 gap-6">
            <div class="md:col-span-2 bg-primary p-8 rounded-2xl relative overflow-hidden group shadow-2xl shadow-primary/20">
                <div class="relative z-10">
                    <p class="text-primary-content/80 font-medium uppercase tracking-widest text-[10px]">Capacidad Instalada</p>
                    <h2 class="text-primary-content text-5xl font-headline font-black mt-2 tracking-tighter">{{ number_format($this->stats['total_items']) }}</h2>
                    <p class="text-primary-content/70 mt-4 text-sm max-w-xs leading-relaxed">Activos tecnológicos distribuidos en {{ $this->stats['categories_count'] }} especialidades y {{ $this->stats['locations_count'] }} áreas del nodo.</p>
                </div>
                <div class="absolute right-0 top-0 h-full w-1/3 bg-gradient-to-l from-primary-content/10 to-transparent flex items-center justify-center group-hover:scale-110 transition-transform duration-700">
                    <span class="material-symbols-outlined text-[120px] text-primary-content opacity-10">precision_manufacturing</span>
                </div>
            </div>
            
            <div class="bg-base-100 p-8 rounded-2xl shadow-sm border border-base-300 flex flex-col justify-between hover:shadow-xl transition-all border-b-4 border-b-info">
                <div>
                    <p class="text-base-content/50 font-bold text-[10px] uppercase tracking-widest">En Uso / Préstamo</p>
                    <h2 class="text-base-content text-4xl font-headline font-black mt-2">{{ number_format($this->stats['loaned_items']) }}</h2>
                </div>
                <div class="flex items-center text-info text-sm font-bold mt-6">
                    <span class="material-symbols-outlined text-sm mr-2">trending_up</span>
                    <span>Proyectos en curso</span>
                </div>
            </div>
            
            <div class="bg-base-100 p-8 rounded-2xl shadow-sm border border-base-300 flex flex-col justify-between hover:shadow-xl transition-all border-b-4 border-b-warning">
                <div>
                    <p class="text-base-content/50 font-bold text-[10px] uppercase tracking-widest">Mantenimiento</p>
                    <h2 class="text-base-content text-4xl font-headline font-black mt-2">{{ number_format($this->stats['maintenance_items']) }}</h2>
                </div>
                <div class="flex items-center text-warning text-sm font-bold mt-6">
                    <span class="material-symbols-outlined text-sm mr-2">build_circle</span>
                    <span>Calibración pendiente</span>
                </div>
            </div>
        </section>

        <!-- Main Content Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
            <!-- Left Column: Activity Feed -->
            <section class="lg:col-span-8 space-y-8">
                <div class="flex items-end justify-between border-b border-base-300 pb-5">
                    <h3 class="text-2xl font-headline font-black tracking-tight text-base-content">Canal de Innovación</h3>
                    <a href="{{ route('inventory.index') }}" class="text-primary text-xs font-bold hover:underline underline-offset-4">Ver Activos</a>
                </div>
                
                <div class="space-y-4">
                    @forelse($this->recentItems as $item)
                        <div class="flex items-start p-6 bg-base-100 rounded-2xl border border-base-300 group hover:border-primary transition-all duration-300 shadow-sm hover:shadow-lg">
                            <div class="w-12 h-12 rounded-xl bg-primary/10 flex items-center justify-center shrink-0">
                                <span class="material-symbols-outlined text-primary">memory</span>
                            </div>
                            <div class="ml-6 flex-1">
                                <div class="flex justify-between items-center">
                                    <h4 class="font-bold text-base-content">{{ $item->name }}</h4>
                                    <span class="text-[10px] text-base-content/40 font-bold uppercase tracking-widest">{{ $item->sku }}</span>
                                </div>
                                <div class="flex items-center gap-4 mt-2">
                                    <span class="text-xs text-base-content/60 flex items-center border-r border-base-300 pr-4">
                                        <span class="material-symbols-outlined text-sm mr-1.5 opacity-40">category</span>
                                        {{ $item->category->name }}
                                    </span>
                                    <span class="text-xs text-base-content/60 flex items-center">
                                        <span class="material-symbols-outlined text-sm mr-1.5 opacity-40">hub</span>
                                        {{ $item->location?->name ?: 'Sin área asignada' }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-20 bg-base-100 rounded-3xl border border-dashed border-base-300">
                            <div class="w-16 h-16 bg-base-200 rounded-full flex items-center justify-center mx-auto mb-4">
                                <span class="material-symbols-outlined text-base-content/20 text-3xl">biotech</span>
                            </div>
                            <p class="text-base-content/40 text-sm font-medium">El laboratorio está en espera de nuevos proyectos.</p>
                        </div>
                    @endforelse
                </div>

                <!-- Visual Chart Area -->
                <div class="bg-neutral p-8 rounded-3xl shadow-2xl relative overflow-hidden">
                    <div class="flex justify-between items-center mb-10 relative z-10">
                        <div>
                            <h3 class="font-headline font-bold text-xl text-neutral-content">Flujo de Prototipado</h3>
                            <p class="text-neutral-content/60 text-xs mt-1">Estimación de uso de hardware por periodos</p>
                        </div>
                    </div>
                    
                    <div class="h-48 flex items-end justify-between space-x-3 relative z-10 px-4">
                        @foreach($this->chartData as $h)
                            <div class="w-full bg-neutral-content/5 rounded-t-xl relative group" style="height: 100%">
                                <div class="absolute bottom-0 w-full bg-primary rounded-t-xl group-hover:bg-primary-focus transition-all duration-700 shadow-[0_0_20px_rgba(var(--p),0.3)]" style="height: {{ $h }}%"></div>
                                <div class="absolute bottom-0 w-full bg-info/20 rounded-t-xl" style="height: {{ max(0, $h - 15) }}%"></div>
                            </div>
                        @endforeach
                    </div>
                    
                    <div class="flex justify-between mt-6 text-[10px] uppercase tracking-widest font-black text-neutral-content/30 px-1 border-t border-neutral-content/10 pt-6">
                        <span>LUN</span><span>MAR</span><span>MIE</span><span>JUE</span><span>VIE</span><span>SAB</span><span>DOM</span>
                    </div>
                </div>
            </section>

            <!-- Right Column: Shortcuts & Alerts -->
            <aside class="lg:col-span-4 space-y-8">
                <div class="bg-neutral p-8 rounded-3xl shadow-xl">
                    <h4 class="font-headline font-bold text-lg mb-6 text-neutral-content">Herramientas</h4>
                    <div class="grid grid-cols-2 gap-4">
                        <x-mary-button icon="o-qr-code" label="Escanear" class="btn btn-ghost bg-neutral-content/5 hover:bg-neutral-content/10 text-neutral-content border-none flex-col h-auto py-6 rounded-2xl gap-3" />
                        <x-mary-button icon="o-document-duplicate" label="Masivo" class="btn btn-ghost bg-neutral-content/5 hover:bg-neutral-content/10 text-neutral-content border-none flex-col h-auto py-6 rounded-2xl gap-3" />
                        <x-mary-button icon="o-check-badge" label="Auditar" class="btn btn-ghost bg-neutral-content/5 hover:bg-neutral-content/10 text-neutral-content border-none flex-col h-auto py-6 rounded-2xl gap-3" />
                        <x-mary-button icon="o-printer" label="Tickets" class="btn btn-ghost bg-neutral-content/5 hover:bg-neutral-content/10 text-neutral-content border-none flex-col h-auto py-6 rounded-2xl gap-3" />
                    </div>
                </div>

                <div class="bg-base-100 p-8 rounded-3xl border border-base-300 space-y-6">
                    <div class="flex justify-between items-center">
                        <h4 class="font-headline font-bold text-base-content uppercase text-xs tracking-widest opacity-60">Status de Hardware</h4>
                        @if($this->alertItems->count() > 0)
                            <span class="badge badge-error badge-sm font-black text-white px-2">ATENCIÓN</span>
                        @endif
                    </div>
                    
                    <div class="space-y-4">
                        @forelse($this->alertItems as $item)
                            <div class="bg-base-200 p-4 rounded-2xl flex items-center space-x-4 border border-base-300 hover:border-error transition-all group">
                                <div class="w-12 h-12 bg-base-300 rounded-xl flex items-center justify-center shrink-0 shadow-sm">
                                    <span class="material-symbols-outlined text-error opacity-60 group-hover:scale-110 transition-transform">warning</span>
                                </div>
                                <div class="min-w-0 flex-1">
                                    <p class="font-bold text-xs text-base-content truncate">{{ $item->name }}</p>
                                    <p class="text-[10px] text-error font-medium uppercase mt-0.5">{{ $item->status === 'maintenance' ? 'En Mantenimiento' : 'Fuera de Línea' }}</p>
                                </div>
                                <x-mary-button icon="o-eye" class="btn-ghost btn-xs text-base-content/20" />
                            </div>
                        @empty
                            <div class="text-center py-8">
                                <span class="material-symbols-outlined text-success opacity-30 text-4xl mb-2">check_circle</span>
                                <p class="text-[10px] uppercase font-bold text-base-content/40 tracking-widest">Sistemas operativos</p>
                            </div>
                        @endforelse
                    </div>
                    
                    <a href="{{ route('inventory.index') }}" class="btn btn-ghost btn-sm w-full font-bold h-auto py-3 rounded-xl border-base-300 text-[10px] uppercase tracking-widest">
                        Gestionar Hardware
                    </a>
                </div>

                <!-- Branding / Info -->
                <div class="relative h-48 rounded-3xl overflow-hidden group shadow-2xl">
                    <img alt="3D Printing Lab" class="absolute inset-0 w-full h-full object-cover group-hover:scale-110 transition-transform duration-1000" src="https://images.unsplash.com/photo-1581092160562-40aa08e78837?auto=format&fit=crop&q=80&w=600"/>
                    <div class="absolute inset-0 bg-primary/80 mix-blend-multiply"></div>
                    <div class="absolute inset-0 flex flex-col justify-end p-6 bg-gradient-to-t from-primary to-transparent">
                        <p class="text-[10px] font-black tracking-widest text-primary-content/50 uppercase">Nodo de Innovación</p>
                        <p class="text-primary-content font-bold text-lg leading-tight">Explora el futuro del prototipado y la IA.</p>
                    </div>
                </div>
            </aside>
        </div>
    </div>
</div>
