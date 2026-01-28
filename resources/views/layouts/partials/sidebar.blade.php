<aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
       class="sidebar fixed inset-y-0 left-0 bg-white w-64 border-r border-gray-200 z-30 flex flex-col transition-transform duration-300 ease-in-out md:relative md:translate-x-0">

    <a href="dashboard" class="flex items-center justify-center h-20 border-b border-gray-100 bg-white">
        <img src="{{ asset('storage/bgnlogo.png') }}" class="w-10 h-10 object-contain drop-shadow-sm" alt="Logo">
        <h1 class="text-xl font-extrabold ml-3 bg-clip-text text-transparent bg-gradient-to-r from-blue-600 to-indigo-600">
            SPPG App
        </h1>
    </a>

    <nav class="flex-1 overflow-y-auto py-4 px-3 space-y-1">

        <a href="{{ route('dashboard') }}"
           class="flex items-center px-4 py-3 rounded-xl transition-all duration-200 group {{ request()->routeIs('dashboard') ? 'bg-gradient-to-r from-blue-600 to-blue-500 text-white shadow-md shadow-blue-500/30' : 'text-gray-600 hover:bg-blue-50 hover:text-blue-600' }}">
            <i class="fa-solid fa-gauge-high h-5 w-5 {{ request()->routeIs('dashboard') ? 'text-white' : 'text-gray-400 group-hover:text-blue-600' }}"></i>
            <span class="ml-3 font-medium">Dashboard</span>
        </a>

        <div x-data="{ open: {{ request()->routeIs('recipes.*') || request()->routeIs('purchase-orders.*') || request()->routeIs('production-orders.*') || request()->routeIs('inventory.*') || request()->routeIs('shipments.*') ? 'true' : 'false' }} }" class="mt-2">
            <button @click="open = !open"
                    class="w-full flex items-center justify-between px-4 py-3 text-gray-600 hover:bg-gray-50 hover:text-gray-900 rounded-xl focus:outline-none transition-colors group">
                <span class="flex items-center">
                    <div class="p-1.5 rounded-lg bg-gray-100 group-hover:bg-orange-100 transition-colors">
                        <i class="fa-solid fa-gears h-4 w-4 text-gray-500 group-hover:text-orange-600"></i>
                    </div>
                    <span class="ml-3 font-medium">Operasional</span>
                </span>
                <i class="fa-solid fa-chevron-right h-3 w-3 text-gray-400 transition-transform duration-200" :class="open ? 'rotate-90' : ''"></i>
            </button>
            <div x-show="open" x-collapse class="space-y-1 mt-1 ml-2 border-l-2 border-gray-100 pl-2">
                <a href="{{ route('menu-plans.index') }}" class="block pl-4 pr-4 py-2 text-sm rounded-lg transition-colors {{ request()->routeIs('menu-plans.*') ? 'text-blue-600 font-semibold bg-blue-50' : 'text-gray-500 hover:text-blue-600 hover:bg-gray-50' }}">
                    Perencanaan Menu
                </a>
                <a href="{{ route('purchase-orders.index') }}" class="block pl-4 pr-4 py-2 text-sm rounded-lg transition-colors {{ request()->routeIs('purchase-orders.*') ? 'text-blue-600 font-semibold bg-blue-50' : 'text-gray-500 hover:text-blue-600 hover:bg-gray-50' }}">
                    Pengadaan (PO)
                </a>
                <a href="{{ route('production-orders.index') }}" class="block pl-4 pr-4 py-2 text-sm rounded-lg transition-colors {{ request()->routeIs('production-orders.*') ? 'text-blue-600 font-semibold bg-blue-50' : 'text-gray-500 hover:text-blue-600 hover:bg-gray-50' }}">
                    Produksi (WO)
                </a>
                <a href="{{ route('inventory.index') }}" class="block pl-4 pr-4 py-2 text-sm rounded-lg transition-colors {{ request()->routeIs('inventory.*') ? 'text-blue-600 font-semibold bg-blue-50' : 'text-gray-500 hover:text-blue-600 hover:bg-gray-50' }}">
                    Stok Gudang
                </a>

                <div x-data="{ distOpen: {{ request()->routeIs('shipments.*') || request()->routeIs('route-templates.*') ? 'true' : 'false' }} }" class="block mt-1">
                    <button @click="distOpen = !distOpen" class="w-full flex items-center justify-between pl-4 pr-4 py-2 text-sm text-gray-500 hover:text-blue-600 hover:bg-gray-50 rounded-lg transition-colors">
                        <span class="font-medium">Distribusi</span>
                        <i class="fas fa-caret-down text-xs transition-transform" :class="distOpen ? 'rotate-180' : ''"></i>
                    </button>
                    <div x-show="distOpen" x-collapse class="pl-3 space-y-1 mt-1 border-l border-gray-200 ml-4">
                        <a href="{{ route('shipments.index') }}" class="block px-3 py-1.5 text-xs rounded-md {{ request()->routeIs('shipments.*') ? 'text-blue-600 font-bold bg-blue-50' : 'text-gray-500 hover:text-blue-600' }}">
                            Jadwal Harian
                        </a>
                        <a href="{{ route('route-templates.index') }}" class="block px-3 py-1.5 text-xs rounded-md {{ request()->routeIs('route-templates.*') ? 'text-blue-600 font-bold bg-blue-50' : 'text-gray-500 hover:text-blue-600' }}">
                            Master Rute
                        </a>
                    </div>
                </div>

                <a href="{{ route('driver.index') }}" class="block pl-4 pr-4 py-2 text-sm rounded-lg transition-colors {{ request()->routeIs('driver.*') ? 'text-blue-600 font-semibold bg-blue-50' : 'text-gray-500 hover:text-blue-600 hover:bg-gray-50' }}">
                    Laporan Driver
                </a>
            </div>
        </div>

        <div x-data="{ open: {{ request()->routeIs('transactions.*') || request()->routeIs('assets.*') || request()->routeIs('salaries.*') || request()->routeIs('attendances.*') ? 'true' : 'false' }} }" class="mt-2">
            <button @click="open = !open"
                    class="w-full flex items-center justify-between px-4 py-3 text-gray-600 hover:bg-gray-50 hover:text-gray-900 rounded-xl focus:outline-none transition-colors group">
                <span class="flex items-center">
                    <div class="p-1.5 rounded-lg bg-gray-100 group-hover:bg-green-100 transition-colors">
                        <i class="fa-solid fa-wallet h-4 w-4 text-gray-500 group-hover:text-green-600"></i>
                    </div>
                    <span class="ml-3 font-medium">Administrasi</span>
                </span>
                <i class="fa-solid fa-chevron-right h-3 w-3 text-gray-400 transition-transform duration-200" :class="open ? 'rotate-90' : ''"></i>
            </button>
            <div x-show="open" x-collapse class="space-y-1 mt-1 ml-2 border-l-2 border-gray-100 pl-2">
                <a href="{{ route('transactions.index') }}" class="block pl-4 pr-4 py-2 text-sm rounded-lg transition-colors {{ request()->routeIs('transactions.*') ? 'text-blue-600 font-semibold bg-blue-50' : 'text-gray-500 hover:text-blue-600 hover:bg-gray-50' }}">
                    Keuangan
                </a>
                <a href="{{ route('assets.index') }}" class="block pl-4 pr-4 py-2 text-sm rounded-lg transition-colors {{ request()->routeIs('assets.*') ? 'text-blue-600 font-semibold bg-blue-50' : 'text-gray-500 hover:text-blue-600 hover:bg-gray-50' }}">
                    Aset
                </a>
                <a href="{{ route('attendances.index') }}" class="block pl-4 pr-4 py-2 text-sm rounded-lg transition-colors {{ request()->routeIs('attendances.*') ? 'text-blue-600 font-semibold bg-blue-50' : 'text-gray-500 hover:text-blue-600 hover:bg-gray-50' }}">
                    Absensi
                </a>
                <a href="{{ route('salaries.index') }}" class="block pl-4 pr-4 py-2 text-sm rounded-lg transition-colors {{ request()->routeIs('salaries.*') ? 'text-blue-600 font-semibold bg-blue-50' : 'text-gray-500 hover:text-blue-600 hover:bg-gray-50' }}">
                    Gaji
                </a>
            </div>
        </div>

        <div x-data="{ open: {{ request()->routeIs('employees.*') || request()->routeIs('suppliers.*') || request()->routeIs('items.*') || request()->routeIs('schools.*') ? 'true' : 'false' }} }" class="mt-2">
            <button @click="open = !open"
                    class="w-full flex items-center justify-between px-4 py-3 text-gray-600 hover:bg-gray-50 hover:text-gray-900 rounded-xl focus:outline-none transition-colors group">
                <span class="flex items-center">
                    <div class="p-1.5 rounded-lg bg-gray-100 group-hover:bg-purple-100 transition-colors">
                        <i class="fa-solid fa-database h-4 w-4 text-gray-500 group-hover:text-purple-600"></i>
                    </div>
                    <span class="ml-3 font-medium">Master Data</span>
                </span>
                <i class="fa-solid fa-chevron-right h-3 w-3 text-gray-400 transition-transform duration-200" :class="open ? 'rotate-90' : ''"></i>
            </button>
            <div x-show="open" x-collapse class="space-y-1 mt-1 ml-2 border-l-2 border-gray-100 pl-2">
                <a href="{{ route('employees.index') }}" class="block pl-4 pr-4 py-2 text-sm rounded-lg transition-colors {{ request()->routeIs('employees.*') ? 'text-blue-600 font-semibold bg-blue-50' : 'text-gray-500 hover:text-blue-600 hover:bg-gray-50' }}">
                    Data Karyawan
                </a>
                <a href="{{ route('suppliers.index') }}" class="block pl-4 pr-4 py-2 text-sm rounded-lg transition-colors {{ request()->routeIs('suppliers.*') ? 'text-blue-600 font-semibold bg-blue-50' : 'text-gray-500 hover:text-blue-600 hover:bg-gray-50' }}">
                    Data Supplier
                </a>
                <a href="{{ route('items.index') }}" class="block pl-4 pr-4 py-2 text-sm rounded-lg transition-colors {{ request()->routeIs('items.*') ? 'text-blue-600 font-semibold bg-blue-50' : 'text-gray-500 hover:text-blue-600 hover:bg-gray-50' }}">
                    Data Item
                </a>
                <a href="{{ route('schools.index') }}" class="block pl-4 pr-4 py-2 text-sm rounded-lg transition-colors {{ request()->routeIs('schools.*') ? 'text-blue-600 font-semibold bg-blue-50' : 'text-gray-500 hover:text-blue-600 hover:bg-gray-50' }}">
                    Data Sekolah
                </a>
            </div>
        </div>

    </nav>
</aside>
