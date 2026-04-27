<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Impresión de Etiquetas - KIVAROX</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @media print {
            .no-print { display: none; }
            body { background: white; padding: 0; }
            .label-card { 
                break-inside: avoid; 
                border: 1px solid #e5e7eb; 
                margin-bottom: 0.5rem;
            }
            .grid { display: block; }
            .label-card { width: 45%; display: inline-block; margin: 2%; vertical-align: top; }
        }
        @font-face {
            font-family: 'Inter';
            src: url('https://fonts.googleapis.com/css2?family=Inter:wght@400;700;900&display=swap');
        }
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="bg-slate-50 p-4 md:p-10">
    <div class="max-w-5xl mx-auto">
        <div class="no-print mb-10 flex flex-col md:flex-row justify-between items-center bg-white p-8 rounded-[2rem] shadow-xl shadow-blue-900/5 border border-blue-50">
            <div class="mb-4 md:mb-0">
                <div class="flex items-center gap-3 mb-2">
                    <div class="w-10 h-10 bg-blue-600 rounded-xl flex items-center justify-center text-white shadow-lg shadow-blue-600/30">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z" />
                        </svg>
                    </div>
                    <h1 class="text-2xl font-black tracking-tighter text-slate-900">Generador de Etiquetas</h1>
                </div>
                <p class="text-slate-500 text-sm font-medium">Se han preparado <span class="text-blue-600 font-bold">{{ count($items) }}</span> etiquetas para impresión láser o térmica.</p>
            </div>
            <div class="flex gap-3">
                <button onclick="window.close()" class="px-6 py-3 rounded-2xl font-bold text-slate-500 hover:bg-slate-100 transition-all">
                    Cerrar
                </button>
                <button onclick="window.print()" class="bg-blue-600 text-white px-8 py-3 rounded-2xl font-black shadow-xl shadow-blue-600/20 hover:bg-blue-700 hover:scale-[1.02] active:scale-95 transition-all flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M5 4v3H4a2 2 0 00-2 2v3a2 2 0 002 2h1v2a2 2 0 002 2h6a2 2 0 002-2v-2h1a2 2 0 002-2V9a2 2 0 00-2-2h-1V4a2 2 0 00-2-2H7a2 2 0 00-2 2zm8 0H7v3h6V4zm0 8H7v4h6v-4z" clip-rule="evenodd" />
                    </svg>
                    Imprimir Etiquetas
                </button>
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            @foreach($items as $item)
                <div class="label-card bg-white p-6 rounded-3xl shadow-sm border border-slate-200 flex flex-col items-center relative overflow-hidden group">
                    {{-- Decorative background --}}
                    <div class="absolute top-0 right-0 w-16 h-16 bg-blue-50 rounded-bl-[3rem] -mr-4 -mt-4 transition-transform group-hover:scale-150 duration-500"></div>
                    
                    <div class="relative z-10 w-full flex flex-col items-center">
                        <div class="bg-white p-2 rounded-2xl shadow-inner border border-slate-100 mb-4">
                            {!! $writer->writeString(route('inventory.index', ['search' => $item->sku])) !!}
                        </div>
                        
                        <div class="w-full">
                            <h3 class="text-sm font-black text-slate-900 uppercase tracking-tight leading-none mb-1 truncate px-2">{{ $item->name }}</h3>
                            <div class="flex items-center justify-center gap-2 mb-4">
                                <span class="text-[10px] font-mono text-slate-400 bg-slate-50 px-2 py-0.5 rounded-md">{{ $item->sku }}</span>
                                <span class="text-[8px] font-bold text-blue-500 uppercase tracking-widest">{{ $item->category->name }}</span>
                            </div>
                            
                            <div class="border-t border-dashed border-slate-200 pt-3 flex items-center justify-between px-2">
                                <span class="text-[7px] font-black text-slate-300 uppercase tracking-[0.2em]">KIVAROX SYSTEM</span>
                                <div class="flex gap-0.5">
                                    <div class="w-1 h-1 bg-blue-600 rounded-full"></div>
                                    <div class="w-1 h-1 bg-blue-400 rounded-full"></div>
                                    <div class="w-1 h-1 bg-blue-200 rounded-full"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="no-print mt-12 text-center">
            <p class="text-slate-400 text-xs font-medium uppercase tracking-widest">Fin del reporte de etiquetas</p>
        </div>
    </div>
</body>
</html>
