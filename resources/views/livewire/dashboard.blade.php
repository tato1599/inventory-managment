<div>
    <div class="space-y-12">
        <!-- Hero Stats: Bento Style -->
        <section class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 gap-6">
            <div class="md:col-span-2 bg-indigo-600 dark:bg-indigo-700 p-8 rounded-2xl relative overflow-hidden group shadow-2xl shadow-indigo-500/20">
                <div class="relative z-10">
                    <p class="text-indigo-100 font-medium opacity-80 uppercase tracking-widest text-[10px]">Vista General de la Colección</p>
                    <h2 class="text-white text-5xl font-headline font-black mt-2 tracking-tighter">{{ number_format($stats['total_items']) }}</h2>
                    <p class="text-indigo-100 mt-4 text-sm max-w-xs leading-relaxed">Artículos catalogados en {{ $stats['categories_count'] }} departamentos. Datos sincronizados en tiempo real.</p>
                </div>
                <div class="absolute right-0 top-0 h-full w-1/3 bg-gradient-to-l from-white/10 to-transparent flex items-center justify-center group-hover:scale-110 transition-transform duration-700">
                    <span class="material-symbols-outlined text-[120px] text-white opacity-10">inventory_2</span>
                </div>
            </div>
            
            <div class="bg-white dark:bg-slate-900 p-8 rounded-2xl shadow-sm border border-slate-100 dark:border-slate-800 flex flex-col justify-between hover:shadow-xl transition-all border-b-4 border-b-blue-500">
                <div>
                    <p class="text-slate-500 dark:text-slate-400 font-bold text-[10px] uppercase tracking-widest">Préstamos Activos</p>
                    <h2 class="text-slate-900 dark:text-slate-50 text-4xl font-headline font-black mt-2">{{ number_format($stats['loaned_items']) }}</h2>
                </div>
                <div class="flex items-center text-blue-600 dark:text-blue-400 text-sm font-bold mt-6">
                    <span class="material-symbols-outlined text-sm mr-2">trending_up</span>
                    <span>Seguimiento de préstamos activado</span>
                </div>
            </div>
            
            <div class="bg-white dark:bg-slate-900 p-8 rounded-2xl shadow-sm border border-slate-100 dark:border-slate-800 flex flex-col justify-between hover:shadow-xl transition-all border-b-4 border-b-amber-500">
                <div>
                    <p class="text-slate-500 dark:text-slate-400 font-bold text-[10px] uppercase tracking-widest">En Mantenimiento</p>
                    <h2 class="text-slate-900 dark:text-slate-50 text-4xl font-headline font-black mt-2">{{ number_format($stats['maintenance_items']) }}</h2>
                </div>
                <div class="flex items-center text-amber-600 dark:text-amber-400 text-sm font-bold mt-6">
                    <span class="material-symbols-outlined text-sm mr-2">warning</span>
                    <span>Artículos fuera de servicio</span>
                </div>
            </div>
        </section>

        <!-- Main Content Grid -->
        <div class="editorial-grid">
            <!-- Left Column: Activity Feed -->
            <section class="space-y-8">
                <div class="flex items-end justify-between border-b border-slate-200 dark:border-slate-800 pb-5">
                    <h3 class="text-2xl font-headline font-black tracking-tight text-slate-900 dark:text-white">Actividad Reciente</h3>
                    <button class="text-indigo-600 dark:text-indigo-400 text-xs font-bold hover:underline underline-offset-4">Ver Bitácora Completa</button>
                </div>
                
                <div class="space-y-4">
                    @forelse($recentItems as $item)
                        <div class="flex items-start p-6 bg-white dark:bg-slate-900 rounded-2xl border border-slate-100 dark:border-slate-800 group hover:border-indigo-500 dark:hover:border-indigo-500 transition-all duration-300 shadow-sm hover:shadow-lg">
                            <div class="w-12 h-12 rounded-xl bg-blue-50 dark:bg-blue-900/30 flex items-center justify-center shrink-0">
                                <span class="material-symbols-outlined text-blue-600 dark:text-blue-400">inventory</span>
                            </div>
                            <div class="ml-6 flex-1">
                                <div class="flex justify-between items-center">
                                    <h4 class="font-bold text-slate-900 dark:text-slate-100">{{ $item->name }}</h4>
                                    <span class="text-[10px] text-slate-400 font-bold uppercase">{{ $item->sku }}</span>
                                </div>
                                <p class="text-sm text-slate-500 dark:text-slate-400 mt-2 leading-relaxed truncate">{{ $item->description }}</p>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-12 bg-white dark:bg-slate-900 rounded-2xl border border-dashed border-slate-200 dark:border-slate-800">
                            <p class="text-slate-400 text-sm">No hay actividad reciente.</p>
                        </div>
                    @endforelse
                </div>

                <!-- Visual Chart Area -->
                <div class="bg-slate-900 dark:bg-black p-8 rounded-2xl shadow-2xl relative overflow-hidden">
                    <div class="flex justify-between items-center mb-10 relative z-10">
                        <div>
                            <h3 class="font-headline font-bold text-xl text-white">Tendencias de Préstamos</h3>
                            <p class="text-slate-400 text-xs mt-1">Análisis de flujo semanal por departamento</p>
                        </div>
                        <div class="flex space-x-6">
                            <span class="flex items-center text-[10px] font-bold text-slate-300 uppercase tracking-widest">
                                <span class="w-3 h-3 bg-indigo-500 rounded-full mr-2"></span> Ciencias
                            </span>
                            <span class="flex items-center text-[10px] font-bold text-slate-300 uppercase tracking-widest">
                                <span class="w-3 h-3 bg-blue-500 rounded-full mr-2 text-blue-500"></span> Artes
                            </span>
                        </div>
                    </div>
                    
                    <div class="h-48 flex items-end justify-between space-x-3 relative z-10">
                        @foreach([24, 32, 40, 36, 44, 28, 38] as $h)
                            <div class="w-full bg-slate-800 rounded-t-xl h-{{ $h }} relative group">
                                <div class="absolute bottom-0 w-full bg-indigo-500 rounded-t-xl h-{{ $h - 6 }} group-hover:bg-indigo-400 transition-all duration-300 shadow-[0_0_20px_rgba(79,70,229,0.3)]"></div>
                                <div class="absolute bottom-0 w-full bg-blue-500/30 rounded-t-xl h-{{ $h - 12 }}"></div>
                            </div>
                        @endforeach
                    </div>
                    
                    <div class="flex justify-between mt-6 text-[10px] uppercase tracking-widest font-black text-slate-500 px-1 border-t border-slate-800 pt-6">
                        <span>Lun</span><span>Mar</span><span>Mie</span><span>Jue</span><span>Vie</span><span>Sab</span><span>Dom</span>
                    </div>
                </div>
            </section>

            <!-- Right Column: Shortcuts & Alerts -->
            <aside class="space-y-8">
                <div class="bg-indigo-900 p-8 rounded-2xl shadow-xl shadow-indigo-900/40">
                    <h4 class="font-headline font-bold text-lg mb-6 text-white">Accesos Directos</h4>
                    <div class="grid grid-cols-2 gap-4">
                        <button class="flex flex-col items-center justify-center p-4 bg-white/5 rounded-xl hover:bg-white/10 transition-all group border border-white/5">
                            <span class="material-symbols-outlined text-3xl text-blue-300 mb-2">qr_code_2</span>
                            <span class="text-[10px] font-bold text-white uppercase tracking-wider">Escanear</span>
                        </button>
                        <button class="flex flex-col items-center justify-center p-4 bg-white/5 rounded-xl hover:bg-white/10 transition-all group border border-white/5">
                            <span class="material-symbols-outlined text-3xl text-indigo-300 mb-2">assignment_add</span>
                            <span class="text-[10px] font-bold text-white uppercase tracking-wider">Masivo</span>
                        </button>
                        <button class="flex flex-col items-center justify-center p-4 bg-white/5 rounded-xl hover:bg-white/10 transition-all group border border-white/5">
                            <span class="material-symbols-outlined text-3xl text-emerald-300 mb-2">verified</span>
                            <span class="text-[10px] font-bold text-white uppercase tracking-wider">Auditar</span>
                        </button>
                        <button class="flex flex-col items-center justify-center p-4 bg-white/5 rounded-xl hover:bg-white/10 transition-all group border border-white/5">
                            <span class="material-symbols-outlined text-3xl text-slate-300 mb-2">print</span>
                            <span class="text-[10px] font-bold text-white uppercase tracking-wider">Etiquetas</span>
                        </button>
                    </div>
                </div>

                <div class="bg-white dark:bg-slate-900 p-8 rounded-2xl border border-slate-100 dark:border-slate-800 space-y-6">
                    <div class="flex justify-between items-center">
                        <h4 class="font-headline font-bold text-slate-900 dark:text-white">Alertas de Estado</h4>
                        <span class="px-2 py-1 bg-red-500 text-[10px] text-white rounded-md font-black">RIESGO ALTO</span>
                    </div>
                    
                    <div class="space-y-4">
                        <div class="bg-slate-50 dark:bg-slate-800/50 p-4 rounded-xl flex items-center space-x-4 border border-slate-100 dark:border-slate-700/50">
                            <div class="w-12 h-12 bg-white dark:bg-slate-700 rounded-lg overflow-hidden shrink-0 shadow-sm">
                                <img alt="Old Book" class="w-full h-full object-cover" src="https://images.unsplash.com/photo-1544947950-fa07a98d237f?auto=format&fit=crop&q=80&w=200"/>
                            </div>
                            <div class="min-w-0">
                                <p class="font-bold text-xs truncate text-slate-900 dark:text-slate-100">Manuscrito Raro MS-88</p>
                                <p class="text-[10px] text-slate-500 dark:text-slate-400 uppercase mt-0.5">Humedad: 62%</p>
                            </div>
                            <span class="material-symbols-outlined text-red-500 text-lg ml-auto">thermostat</span>
                        </div>
                    </div>
                    
                    <button class="w-full py-3 border border-slate-200 dark:border-slate-700 text-slate-500 dark:text-slate-400 text-xs font-bold rounded-xl hover:bg-slate-50 dark:hover:bg-slate-800 transition-all">
                        Gestionar Todas las Alertas
                    </button>
                </div>

                <!-- Branding -->
                <div class="relative h-40 rounded-2xl overflow-hidden group shadow-2xl">
                    <img alt="Library" class="absolute inset-0 w-full h-full object-cover group-hover:scale-105 transition-transform duration-1000" src="https://images.unsplash.com/photo-1507842217343-583bb7270b66?auto=format&fit=crop&q=80&w=600"/>
                    <div class="absolute inset-0 bg-indigo-900/60 mix-blend-multiply"></div>
                    <div class="absolute inset-0 flex flex-col justify-end p-6 bg-gradient-to-t from-indigo-950 to-transparent">
                        <p class="text-[10px] font-black tracking-widest text-white/50 uppercase">Archivo Histórico</p>
                        <p class="text-white font-headline font-bold text-lg leading-tight">Preservando la Excelencia Académica</p>
                    </div>
                </div>
            </aside>
        </div>
    </div>
</div>
