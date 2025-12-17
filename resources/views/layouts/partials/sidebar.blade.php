<aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
       class="sidebar fixed inset-y-0 left-0 bg-white w-64 shadow-lg z-30 flex flex-col transition-transform duration-300 ease-in-out md:relative md:translate-x-0">

    <div class="flex items-center justify-center p-4 border-b flex-shrink-0 h-16">
        <i class="fa-solid fa-cubes-stacked h-8 w-8 text-blue-600"></i>
        <h1 class="text-xl font-bold ml-2 text-gray-800">SPPG Super App</h1>
    </div>

    <nav class="flex-1 overflow-y-auto p-2 space-y-1">

        <a href="{{ route('dashboard') }}"
           class="flex items-center px-4 py-3 rounded-lg transition-colors {{ request()->routeIs('dashboard') ? 'bg-blue-100 text-blue-700 font-semibold' : 'text-gray-600 hover:bg-gray-100' }}">
            <i class="fa-solid fa-gauge-high h-5 w-5"></i>
            <span class="ml-3">Dashboard</span>
        </a>

        <div x-data="{ open: {{ request()->routeIs('recipes.*') || request()->routeIs('purchase-orders.*') || request()->routeIs('production-orders.*') || request()->routeIs('inventory.*') || request()->routeIs('shipments.*') ? 'true' : 'false' }} }" class="mt-2">
            <button @click="open = !open"
                    class="w-full flex items-center justify-between px-4 py-3 text-gray-600 hover:bg-gray-100 rounded-lg focus:outline-none transition-colors"
                    :class="open ? 'bg-gray-50 font-semibold' : ''">
                <span class="flex items-center">
                    <i class="fa-solid fa-gears h-5 w-5"></i>
                    <span class="ml-3">Operasional</span>
                </span>
                <i class="fa-solid fa-chevron-right h-4 w-4 transition-transform duration-200" :class="open ? 'rotate-90' : ''"></i>
            </button>
            <div x-show="open" x-collapse class="space-y-1 mt-1">
                <a href="{{ route('menu-plans.index') }}" class="block pl-12 pr-4 py-2 text-sm rounded-lg {{ request()->routeIs('recipes.*') ? 'text-blue-600 font-medium bg-blue-50' : 'text-gray-500 hover:text-blue-600 hover:bg-gray-50' }}">
                    Perencanaan Menu
                </a>
                <a href="{{ route('purchase-orders.index') }}" class="block pl-12 pr-4 py-2 text-sm rounded-lg {{ request()->routeIs('purchase-orders.*') ? 'text-blue-600 font-medium bg-blue-50' : 'text-gray-500 hover:text-blue-600 hover:bg-gray-50' }}">
                    Pengadaan (PO)
                </a>
                <a href="{{ route('production-orders.index') }}" class="block pl-12 pr-4 py-2 text-sm rounded-lg {{ request()->routeIs('production-orders.*') ? 'text-blue-600 font-medium bg-blue-50' : 'text-gray-500 hover:text-blue-600 hover:bg-gray-50' }}">
                    Produksi (WO)
                </a>
                <a href="{{ route('inventory.index') }}" class="block pl-12 pr-4 py-2 text-sm rounded-lg {{ request()->routeIs('inventory.*') ? 'text-blue-600 font-medium bg-blue-50' : 'text-gray-500 hover:text-blue-600 hover:bg-gray-50' }}">
                    Stok Gudang
                </a>
              <div x-data="{ distOpen: {{ request()->routeIs('shipments.*') || request()->routeIs('route-templates.*') ? 'true' : 'false' }} }" class="block">
    <button @click="distOpen = !distOpen" class="w-full flex items-center justify-between pl-12 pr-4 py-2 text-sm text-gray-500 hover:text-blue-600 hover:bg-gray-50 rounded-lg transition-colors">
        <span>Distribusi</span>
        <i class="fas fa-caret-down text-xs transition-transform" :class="distOpen ? 'rotate-180' : ''"></i>
    </button>
    <div x-show="distOpen" x-collapse class="pl-4 space-y-1 mt-1 border-l-2 border-gray-100 ml-12">
        <a href="{{ route('shipments.index') }}" class="block px-4 py-2 text-xs rounded-lg {{ request()->routeIs('shipments.*') ? 'text-blue-600 font-bold bg-blue-50' : 'text-gray-500 hover:text-blue-600' }}">
            Jadwal Harian
        </a>
        <a href="{{ route('route-templates.index') }}" class="block px-4 py-2 text-xs rounded-lg {{ request()->routeIs('route-templates.*') ? 'text-blue-600 font-bold bg-blue-50' : 'text-gray-500 hover:text-blue-600' }}">
            Master Rute (Template)
        </a>
    </div>
</div>
                   <a href="{{ route('driver.index') }}" class="block pl-12 pr-4 py-2 text-sm rounded-lg {{ request()->routeIs('shipments.*') ? 'text-blue-600 font-medium bg-blue-50' : 'text-gray-500 hover:text-blue-600 hover:bg-gray-50' }}">
                    Laporan Driver
                </a>
            </div>
        </div>

        <div x-data="{ open: {{ request()->routeIs('transactions.*') || request()->routeIs('assets.*') || request()->routeIs('salaries.*') || request()->routeIs('attendances.*') ? 'true' : 'false' }} }" class="mt-2">
            <button @click="open = !open"
                    class="w-full flex items-center justify-between px-4 py-3 text-gray-600 hover:bg-gray-100 rounded-lg focus:outline-none transition-colors"
                    :class="open ? 'bg-gray-50 font-semibold' : ''">
                <span class="flex items-center">
                    <i class="fa-solid fa-wallet h-5 w-5"></i>
                    <span class="ml-3">Administrasi & SDM</span>
                </span>
                <i class="fa-solid fa-chevron-right h-4 w-4 transition-transform duration-200" :class="open ? 'rotate-90' : ''"></i>
            </button>
            <div x-show="open" x-collapse class="space-y-1 mt-1">
                <a href="{{ route('transactions.index') }}" class="block pl-12 pr-4 py-2 text-sm rounded-lg {{ request()->routeIs('transactions.*') ? 'text-blue-600 font-medium bg-blue-50' : 'text-gray-500 hover:text-blue-600 hover:bg-gray-50' }}">
                    Keuangan
                </a>
                <a href="{{ route('assets.index') }}" class="block pl-12 pr-4 py-2 text-sm rounded-lg {{ request()->routeIs('assets.*') ? 'text-blue-600 font-medium bg-blue-50' : 'text-gray-500 hover:text-blue-600 hover:bg-gray-50' }}">
                    Aset
                </a>
                <a href="{{ route('attendances.index') }}" class="block pl-12 pr-4 py-2 text-sm rounded-lg {{ request()->routeIs('attendances.*') ? 'text-blue-600 font-medium bg-blue-50' : 'text-gray-500 hover:text-blue-600 hover:bg-gray-50' }}">
                    Absensi
                </a>
                <a href="{{ route('salaries.index') }}" class="block pl-12 pr-4 py-2 text-sm rounded-lg {{ request()->routeIs('salaries.*') ? 'text-blue-600 font-medium bg-blue-50' : 'text-gray-500 hover:text-blue-600 hover:bg-gray-50' }}">
                    Gaji
                </a>
            </div>
        </div>

        <div x-data="{ open: {{ request()->routeIs('employees.*') || request()->routeIs('suppliers.*') || request()->routeIs('items.*') || request()->routeIs('schools.*') ? 'true' : 'false' }} }" class="mt-2">
            <button @click="open = !open"
                    class="w-full flex items-center justify-between px-4 py-3 text-gray-600 hover:bg-gray-100 rounded-lg focus:outline-none transition-colors"
                    :class="open ? 'bg-gray-50 font-semibold' : ''">
                <span class="flex items-center">
                    <i class="fa-solid fa-database h-5 w-5"></i>
                    <span class="ml-3">Master Data</span>
                </span>
                <i class="fa-solid fa-chevron-right h-4 w-4 transition-transform duration-200" :class="open ? 'rotate-90' : ''"></i>
            </button>
            <div x-show="open" x-collapse class="space-y-1 mt-1">
                <a href="{{ route('employees.index') }}" class="block pl-12 pr-4 py-2 text-sm rounded-lg {{ request()->routeIs('employees.*') ? 'text-blue-600 font-medium bg-blue-50' : 'text-gray-500 hover:text-blue-600 hover:bg-gray-50' }}">
                    Data Karyawan
                </a>
                <a href="{{ route('suppliers.index') }}" class="block pl-12 pr-4 py-2 text-sm rounded-lg {{ request()->routeIs('suppliers.*') ? 'text-blue-600 font-medium bg-blue-50' : 'text-gray-500 hover:text-blue-600 hover:bg-gray-50' }}">
                    Data Supplier
                </a>
                <a href="{{ route('items.index') }}" class="block pl-12 pr-4 py-2 text-sm rounded-lg {{ request()->routeIs('items.*') ? 'text-blue-600 font-medium bg-blue-50' : 'text-gray-500 hover:text-blue-600 hover:bg-gray-50' }}">
                    Data Item
                </a>
                <a href="{{ route('schools.index') }}" class="block pl-12 pr-4 py-2 text-sm rounded-lg {{ request()->routeIs('schools.*') ? 'text-blue-600 font-medium bg-blue-50' : 'text-gray-500 hover:text-blue-600 hover:bg-gray-50' }}">
                    Data Sekolah
                </a>
            </div>
        </div>

    </nav>
</aside>
