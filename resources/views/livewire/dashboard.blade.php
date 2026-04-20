<div>
    <div class="space-y-12">
        <!-- Hero Stats: Bento Style -->
        <section class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 gap-6">
            <div class="md:col-span-2 bg-primary-container p-8 rounded-xl relative overflow-hidden group">
                <div class="relative z-10">
                    <p class="text-on-primary-container font-medium opacity-80 uppercase tracking-widest text-xs">Collection Overview</p>
                    <h2 class="text-white text-5xl font-headline font-black mt-2 tracking-tighter">12,842</h2>
                    <p class="text-on-primary-container mt-4 text-sm max-w-xs">Cataloged items across 14 departments. Recent audit shows 99.4% data integrity.</p>
                </div>
                <div class="absolute right-0 top-0 h-full w-1/3 bg-gradient-to-l from-primary/40 to-transparent flex items-center justify-center group-hover:scale-110 transition-transform duration-700">
                    <span class="material-symbols-outlined text-[120px] text-primary opacity-20">book_5</span>
                </div>
            </div>
            <div class="bg-surface-container-lowest p-8 rounded-xl shadow-sm border-b-4 border-secondary flex flex-col justify-between">
                <div>
                    <p class="text-on-surface-variant font-medium text-xs uppercase tracking-widest">Active Loans</p>
                    <h2 class="text-on-surface text-4xl font-headline font-black mt-2">1,204</h2>
                </div>
                <div class="flex items-center text-secondary text-sm font-bold mt-4">
                    <span class="material-symbols-outlined text-sm mr-1">trending_up</span>
                    <span>12% from last month</span>
                </div>
            </div>
            <div class="bg-surface-container-lowest p-8 rounded-xl shadow-sm border-b-4 border-tertiary flex flex-col justify-between">
                <div>
                    <p class="text-on-surface-variant font-medium text-xs uppercase tracking-widest">Pending Returns</p>
                    <h2 class="text-on-surface text-4xl font-headline font-black mt-2">48</h2>
                </div>
                <div class="flex items-center text-error text-sm font-bold mt-4">
                    <span class="material-symbols-outlined text-sm mr-1">warning</span>
                    <span>8 Overdue items</span>
                </div>
            </div>
        </section>

        <!-- Main Content Grid -->
        <div class="editorial-grid">
            <!-- Left Column: Activity Feed & Inventory Health -->
            <section class="space-y-8">
                <div class="flex items-end justify-between border-b-2 border-stone-100 pb-4">
                    <h3 class="text-2xl font-headline font-extrabold tracking-tight">Recent Activity</h3>
                    <button class="text-primary text-sm font-bold hover:underline">View Journal</button>
                </div>
                <div class="space-y-4">
                    <div class="flex items-start p-6 bg-surface-container-low rounded-xl group hover:bg-white hover:shadow-xl transition-all duration-300">
                        <div class="w-12 h-12 rounded-full bg-secondary-fixed flex items-center justify-center shrink-0">
                            <span class="material-symbols-outlined text-on-secondary-fixed">outbound</span>
                        </div>
                        <div class="ml-6 flex-1">
                            <div class="flex justify-between">
                                <h4 class="font-bold text-on-surface">Loan Processed: Item #SKU-293</h4>
                                <span class="text-xs text-on-surface-variant font-medium">2 mins ago</span>
                            </div>
                            <p class="text-sm text-on-surface-variant mt-1 leading-relaxed">High-fidelity anatomical model checked out to Biology Dept.</p>
                        </div>
                    </div>
                    <div class="flex items-start p-6 bg-surface-container-low rounded-xl group hover:bg-white hover:shadow-xl transition-all duration-300">
                        <div class="w-12 h-12 rounded-full bg-tertiary-fixed flex items-center justify-center shrink-0">
                            <span class="material-symbols-outlined text-on-tertiary-fixed">keyboard_return</span>
                        </div>
                        <div class="ml-6 flex-1">
                            <div class="flex justify-between">
                                <h4 class="font-bold text-on-surface">Return Logged: Archives-04</h4>
                                <span class="text-xs text-on-surface-variant font-medium">15 mins ago</span>
                            </div>
                            <p class="text-sm text-on-surface-variant mt-1 leading-relaxed">Victorian-era map collection returned by History Dept.</p>
                        </div>
                    </div>
                </div>

                <!-- Visual Chart Area -->
                <div class="bg-surface-container p-8 rounded-xl transition-all hover:shadow-lg">
                    <div class="flex justify-between items-center mb-8">
                        <h3 class="font-headline font-bold text-lg">Loan Trends</h3>
                        <div class="flex space-x-4">
                            <span class="flex items-center text-xs font-bold text-on-surface-variant">
                                <span class="w-3 h-3 bg-primary rounded-full mr-2"></span> Science
                            </span>
                            <span class="flex items-center text-xs font-bold text-on-surface-variant">
                                <span class="w-3 h-3 bg-secondary rounded-full mr-2"></span> Arts
                            </span>
                        </div>
                    </div>
                    <div class="h-48 flex items-end justify-between space-x-2">
                        @foreach([24, 32, 40, 36, 44, 28, 32] as $h)
                            <div class="w-full bg-primary/10 rounded-t-lg h-{{ $h }} relative group overflow-hidden">
                                <div class="absolute bottom-0 w-full bg-{{ $loop->index % 2 == 0 ? 'primary' : 'secondary' }} rounded-t-lg h-{{ $h - 8 }} group-hover:h-{{ $h }} transition-all duration-300"></div>
                            </div>
                        @endforeach
                    </div>
                    <div class="flex justify-between mt-4 text-[10px] uppercase tracking-widest font-bold text-on-surface-variant px-1 border-t border-stone-200 pt-4">
                        <span>Mon</span><span>Tue</span><span>Wed</span><span>Thu</span><span>Fri</span><span>Sat</span><span>Sun</span>
                    </div>
                </div>
            </section>

            <!-- Right Column: Quick Actions & Alerts -->
            <aside class="space-y-8">
                <div class="bg-inverse-surface text-inverse-on-surface p-8 rounded-xl shadow-xl">
                    <h4 class="font-headline font-bold text-lg mb-6">Archive Shortcuts</h4>
                    <div class="grid grid-cols-2 gap-4">
                        <button class="flex flex-col items-center justify-center p-4 bg-white/5 rounded-lg hover:bg-white/10 transition-colors group">
                            <span class="material-symbols-outlined text-3xl text-secondary-container mb-2">qr_code_2</span>
                            <span class="text-xs font-bold">Scan Item</span>
                        </button>
                        <button class="flex flex-col items-center justify-center p-4 bg-white/5 rounded-lg hover:bg-white/10 transition-colors">
                            <span class="material-symbols-outlined text-3xl text-primary-container mb-2">assignment_add</span>
                            <span class="text-xs font-bold">Bulk Ingest</span>
                        </button>
                        <button class="flex flex-col items-center justify-center p-4 bg-white/5 rounded-lg hover:bg-white/10 transition-colors">
                            <span class="material-symbols-outlined text-3xl text-tertiary-container mb-2">verified</span>
                            <span class="text-xs font-bold">Audit Mode</span>
                        </button>
                        <button class="flex flex-col items-center justify-center p-4 bg-white/5 rounded-lg hover:bg-white/10 transition-colors">
                            <span class="material-symbols-outlined text-3xl text-on-surface-variant mb-2">print</span>
                            <span class="text-xs font-bold">Tags</span>
                        </button>
                    </div>
                </div>

                <div class="bg-surface-container-low p-8 rounded-xl space-y-6">
                    <div class="flex justify-between items-center">
                        <h4 class="font-headline font-bold">Condition Alerts</h4>
                        <span class="px-2 py-1 bg-primary text-[10px] text-white rounded font-black">HIGH RISK</span>
                    </div>
                    <div class="space-y-4">
                        <div class="bg-surface-container-lowest p-4 rounded-lg flex items-center space-x-4 border border-stone-200">
                            <div class="w-12 h-12 bg-stone-100 rounded-lg overflow-hidden shrink-0">
                                <img alt="Old Book" class="w-full h-full object-cover" src="https://images.unsplash.com/photo-1544947950-fa07a98d237f?auto=format&fit=crop&q=80&w=200"/>
                            </div>
                            <div class="min-w-0">
                                <p class="font-bold text-xs truncate">Rare Manuscript MS-88</p>
                                <p class="text-[10px] text-on-surface-variant uppercase mt-0.5">Humidity Alert: 62%</p>
                            </div>
                            <span class="material-symbols-outlined text-primary text-sm ml-auto">thermostat</span>
                        </div>
                    </div>
                    <button class="w-full py-3 border-2 border-outline-variant text-on-surface-variant text-xs font-bold rounded-lg hover:bg-surface-container-highest transition-colors">
                        Manage All Alerts
                    </button>
                </div>

                <!-- Branding -->
                <div class="relative h-40 rounded-xl overflow-hidden group shadow-lg">
                    <img alt="Library" class="absolute inset-0 w-full h-full object-cover group-hover:scale-105 transition-transform duration-1000" src="https://images.unsplash.com/photo-1507842217343-583bb7270b66?auto=format&fit=crop&q=80&w=600"/>
                    <div class="absolute inset-0 bg-primary/60 mix-blend-multiply"></div>
                    <div class="absolute inset-0 flex flex-col justify-end p-6 bg-gradient-to-t from-primary to-transparent">
                        <p class="text-[10px] font-black tracking-widest text-white/60 uppercase">Legacy Archive</p>
                        <p class="text-white font-headline font-bold text-lg leading-tight">Preserving Scholarly Excellence</p>
                    </div>
                </div>
            </aside>
        </div>
    </div>
</div>
