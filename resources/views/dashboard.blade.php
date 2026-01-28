<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center">
            <div>
                <h2 class="font-bold text-2xl text-gray-800 leading-tight">
                    {{ __('Dashboard Overview') }}
                </h2>
                <p class="text-sm text-gray-500 mt-1">Welcome back, {{ Auth::user()->name }}!</p>
            </div>
            <div class="mt-4 sm:mt-0">
                <span class="inline-flex rounded-md shadow-sm">
                    <button type="button" class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        <i class="fas fa-download mr-2 text-gray-400"></i> Export Report
                    </button>
                </span>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <div class="bg-white overflow-hidden rounded-xl shadow-sm border border-gray-100 hover:shadow-md transition-shadow duration-300">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="p-3 rounded-full bg-blue-100 text-blue-600">
                                <i class="fa-solid fa-box-open text-xl"></i>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-500">Total Produksi</p>
                                <p class="text-2xl font-bold text-gray-800">1,240</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden rounded-xl shadow-sm border border-gray-100 hover:shadow-md transition-shadow duration-300">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="p-3 rounded-full bg-green-100 text-green-600">
                                <i class="fa-solid fa-truck-fast text-xl"></i>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-500">Pengiriman</p>
                                <p class="text-2xl font-bold text-gray-800">45</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden rounded-xl shadow-sm border border-gray-100 hover:shadow-md transition-shadow duration-300">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="p-3 rounded-full bg-purple-100 text-purple-600">
                                <i class="fa-solid fa-users text-xl"></i>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-500">Karyawan</p>
                                <p class="text-2xl font-bold text-gray-800">86</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden rounded-xl shadow-sm border border-gray-100 hover:shadow-md transition-shadow duration-300">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="p-3 rounded-full bg-orange-100 text-orange-600">
                                <i class="fa-solid fa-triangle-exclamation text-xl"></i>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-500">Stok Rendah</p>
                                <p class="text-2xl font-bold text-gray-800">3</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <div class="bg-white overflow-hidden shadow-sm rounded-xl border border-gray-100">
                    <div class="p-6 border-b border-gray-100">
                        <h3 class="text-lg font-semibold text-gray-800">Aktivitas Terbaru</h3>
                    </div>
                    <div class="p-6 text-gray-600">
                        <p class="text-sm italic text-gray-400">Belum ada aktivitas tercatat hari ini.</p>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm rounded-xl border border-gray-100">
                    <div class="p-6 border-b border-gray-100">
                        <h3 class="text-lg font-semibold text-gray-800">Jadwal Hari Ini</h3>
                    </div>
                    <div class="p-6 text-gray-600">
                         <p class="text-sm italic text-gray-400">Tidak ada jadwal pengiriman mendesak.</p>
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
