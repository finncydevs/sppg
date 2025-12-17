<x-app-layout>
    <x-slot name="header">
        Dashboard
    </x-slot>

    <div class="space-y-6">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900">
                <h3 class="text-lg font-bold text-gray-800">Selamat Datang, {{ Auth::user()->name }}!</h3>
                <p class="text-gray-600">Berikut adalah ringkasan operasional hari ini.</p>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">

            <div class="bg-white p-5 rounded-lg shadow border-l-4 border-blue-500">
                <div class="flex items-center justify-between">
                    <div>
                        <h4 class="text-sm font-medium text-gray-500">Porsi Terdistribusi</h4>
                        <p class="text-3xl font-bold mt-1 text-gray-800">{{ number_format($totalPorsi ?? 0, 0, ',', '.') }}</p>
                        <p class="text-xs text-gray-500 mt-1">hari ini</p>
                    </div>
                    <div class="p-3 rounded-full bg-blue-100 text-blue-600">
                        <i class="fa-solid fa-box w-8 h-8"></i>
                    </div>
                </div>
            </div>

            <div class="bg-white p-5 rounded-lg shadow border-l-4 border-green-500">
                <div class="flex items-center justify-between">
                    <div>
                        <h4 class="text-sm font-medium text-gray-500">Kehadiran Karyawan</h4>
                        <p class="text-3xl font-bold mt-1 text-gray-800">{{ $attendanceRate ?? 0 }}%</p>
                        <p class="text-xs text-gray-500 mt-1">
                            {{ $hadirHariIni ?? 0 }} dari {{ $totalKaryawan ?? 0 }} hadir
                        </p>
                    </div>
                    <div class="p-3 rounded-full bg-green-100 text-green-600">
                        <i class="fa-solid fa-users w-8 h-8"></i>
                    </div>
                </div>
            </div>

            <div class="bg-white p-5 rounded-lg shadow border-l-4 border-yellow-500">
                <div class="flex items-center justify-between">
                    <div>
                        <h4 class="text-sm font-medium text-gray-500">Total Pengadaan</h4>
                        <p class="text-3xl font-bold mt-1 text-gray-800">Rp {{ number_format($totalPengadaan ?? 0, 0, ',', '.') }}</p>
                        <p class="text-xs text-gray-500 mt-1">bulan ini</p>
                    </div>
                    <div class="p-3 rounded-full bg-yellow-100 text-yellow-600">
                        <i class="fa-solid fa-cart-shopping w-8 h-8"></i>
                    </div>
                </div>
            </div>

            <div class="bg-white p-5 rounded-lg shadow border-l-4 border-red-500">
                <div class="flex items-center justify-between">
                    <div>
                        <h4 class="text-sm font-medium text-gray-500">Total Pengeluaran</h4>
                        <p class="text-3xl font-bold mt-1 text-gray-800">Rp {{ number_format($totalExpense ?? 0, 0, ',', '.') }}</p>
                        <p class="text-xs text-gray-500 mt-1">bulan ini (All-in)</p>
                    </div>
                    <div class="p-3 rounded-full bg-red-100 text-red-600">
                        <i class="fa-solid fa-money-bill-wave w-8 h-8"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
