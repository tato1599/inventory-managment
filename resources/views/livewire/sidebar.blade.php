<div>
    <aside class="hidden md:flex flex-col h-screen w-64 fixed left-0 top-0 overflow-y-auto bg-stone-100 p-4 space-y-2 z-40 font-inter text-sm antialiased border-r border-stone-200">
        <div class="mb-8 px-2 py-4">
            <h1 class="font-headline font-bold text-red-900 text-xl tracking-tighter">NCIE Inventory</h1>
            <div class="flex items-center mt-6 space-x-3">
                <div class="w-10 h-10 bg-surface-container-highest rounded-lg flex items-center justify-center">
                    <span class="material-symbols-outlined text-primary">account_balance</span>
                </div>
                <div>
                    <p class="font-bold text-on-surface leading-tight">Control Panel</p>
                    <p class="text-xs text-on-surface-variant">Admin Portal</p>
                </div>
            </div>
        </div>
        <nav class="space-y-1">
            <a class="flex items-center space-x-3 px-3 py-2.5 bg-white text-red-900 font-semibold rounded-lg shadow-sm active:translate-x-1 transition-all duration-200" href="{{ route('dashboard') }}">
                <span class="material-symbols-outlined">dashboard</span>
                <span>Dashboard</span>
            </a>
            <a class="flex items-center space-x-3 px-3 py-2.5 text-stone-600 hover:bg-stone-200 hover:text-red-800 rounded-lg transition-all duration-200" href="#">
                <span class="material-symbols-outlined">inventory_2</span>
                <span>Inventory</span>
            </a>
            <a class="flex items-center space-x-3 px-3 py-2.5 text-stone-600 hover:bg-stone-200 hover:text-red-800 rounded-lg transition-all duration-200" href="#">
                <span class="material-symbols-outlined">handshake</span>
                <span>Loan Management</span>
            </a>
            <a class="flex items-center space-x-3 px-3 py-2.5 text-stone-600 hover:bg-stone-200 hover:text-red-800 rounded-lg transition-all duration-200" href="#">
                <span class="material-symbols-outlined">analytics</span>
                <span>Archive Reports</span>
            </a>
            <a class="flex items-center space-x-3 px-3 py-2.5 text-stone-600 hover:bg-stone-200 hover:text-red-800 rounded-lg transition-all duration-200" href="#">
                <span class="material-symbols-outlined">settings</span>
                <span>Settings</span>
            </a>
        </nav>
        <div class="mt-auto pt-8">
            <button class="w-full bg-primary text-on-primary py-3 rounded-lg font-bold flex items-center justify-center space-x-2 shadow-lg shadow-primary/20 hover:scale-[1.02] active:scale-95 transition-transform">
                <span class="material-symbols-outlined">bolt</span>
                <span>Rapid Audit</span>
            </button>
            <div class="mt-6 space-y-1">
                <a class="flex items-center space-x-3 px-3 py-2 text-stone-500 hover:text-on-surface transition-colors" href="#">
                    <span class="material-symbols-outlined text-lg">contact_support</span>
                    <span>Support</span>
                </a>
                <form method="POST" action="{{ route('logout') }}" x-data>
                    @csrf
                    <button type="submit" @click.prevent="$root.submit();" class="flex items-center w-full space-x-3 px-3 py-2 text-stone-500 hover:text-on-surface transition-colors">
                        <span class="material-symbols-outlined text-lg">logout</span>
                        <span>Logout</span>
                    </button>
                </form>
            </div>
        </div>
    </aside>

    <!-- Mobile BottomNavBar (md:hidden) -->
    <nav class="md:hidden fixed bottom-0 left-0 right-0 h-16 bg-white flex justify-around items-center px-4 z-50 shadow-[0_-4px_20px_rgba(0,0,0,0.05)]">
        <a class="flex flex-col items-center space-y-1 text-primary" href="{{ route('dashboard') }}">
            <span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 1;">dashboard</span>
            <span class="text-[10px] font-bold">Home</span>
        </a>
        <a class="flex flex-col items-center space-y-1 text-on-surface-variant" href="#">
            <span class="material-symbols-outlined">inventory_2</span>
            <span class="text-[10px] font-bold">Inventory</span>
        </a>
        <a class="flex flex-col items-center space-y-1 text-on-surface-variant" href="#">
            <span class="material-symbols-outlined">handshake</span>
            <span class="text-[10px] font-bold">Loans</span>
        </a>
        <a class="flex flex-col items-center space-y-1 text-on-surface-variant" href="#">
            <span class="material-symbols-outlined">settings</span>
            <span class="text-[10px] font-bold">Settings</span>
        </a>
    </nav>
</div>
