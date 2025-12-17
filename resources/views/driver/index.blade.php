<x-app-layout>
    <x-slot name="header">
        Tugas Pengiriman Hari Ini
    </x-slot>

    <div class="max-w-xl mx-auto space-y-6" x-data="{
        reportModalOpen: false,
        destId: '',
        schoolName: '',
        status: 'Terkirim'
    }">

        <div class="bg-blue-50 border-l-4 border-blue-500 p-4 rounded-r-lg">
            <div class="flex">
                <div class="flex-shrink-0">
                    <i class="fas fa-calendar-alt text-blue-500"></i>
                </div>
                <div class="ml-3">
                    <p class="text-sm text-blue-700">
                        Jadwal Tanggal: <span class="font-bold">{{ \Carbon\Carbon::parse($today)->isoFormat('dddd, D MMMM Y') }}</span>
                    </p>
                </div>
            </div>
        </div>

        @if($shipments->isEmpty())
            <div class="text-center py-10 bg-white rounded-lg shadow-sm">
                <div class="text-gray-300 mb-3">
                    <i class="fas fa-truck fa-3x"></i>
                </div>
                <p class="text-gray-500">Tidak ada jadwal pengiriman untuk Anda hari ini.</p>
            </div>
        @else
            @foreach($shipments as $shipment)
                <div class="flex items-center justify-between px-2">
                    <span class="text-xs font-semibold text-gray-500 uppercase tracking-wider">
                        Trip #{{ $loop->iteration }} • Jam {{ $shipment->departure_time->format('H:i') }}
                    </span>
                    <span class="px-2 py-0.5 rounded text-xs font-medium {{ $shipment->status == 'Completed' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                        {{ $shipment->status }}
                    </span>
                </div>

                <div class="space-y-4">
                    @foreach($shipment->destinations as $dest)
                        <div class="bg-white rounded-lg shadow border-l-4 {{ $dest->status == 'Terkirim' ? 'border-green-500' : ($dest->status == 'Gagal Kirim' ? 'border-red-500' : 'border-gray-300') }} p-4 relative overflow-hidden">

                            <div class="flex justify-between items-start mb-2">
                                <div>
                                    <h3 class="text-lg font-bold text-gray-800">{{ $dest->school->name }}</h3>
                                    <p class="text-sm text-gray-600 flex items-center mt-1">
                                        <i class="fas fa-map-marker-alt mr-1.5 text-red-400 w-4"></i>
                                        {{ Str::limit($dest->school->address, 40) }}
                                    </p>
                                </div>

                                @if($dest->status == 'Pending')
                                    <span class="bg-gray-100 text-gray-600 text-xs font-bold px-2 py-1 rounded">Pending</span>
                                @elseif($dest->status == 'Terkirim')
                                    <span class="bg-green-100 text-green-700 text-xs font-bold px-2 py-1 rounded flex items-center">
                                        <i class="fas fa-check mr-1"></i> Selesai
                                    </span>
                                @else
                                    <span class="bg-red-100 text-red-700 text-xs font-bold px-2 py-1 rounded">Gagal</span>
                                @endif
                            </div>

                            <div class="bg-gray-50 rounded p-3 mb-4 text-sm">
                                <div class="flex justify-between border-b border-gray-200 pb-2 mb-2">
                                    <span class="text-gray-500">Menu</span>
                                    <span class="font-medium text-gray-900">
                                        {{ $dest->productionOrder->recipe->name ?? 'Menu Standar' }}
                                    </span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-500">Jumlah</span>
                                    <span class="font-bold text-blue-600">{{ $dest->quantity }} Porsi</span>
                                </div>
                            </div>

                            @if($dest->status == 'Pending')
                                <button @click="reportModalOpen = true; destId = '{{ $dest->id }}'; schoolName = '{{ $dest->school->name }}'"
                                        class="w-full bg-blue-600 text-white font-bold py-3 rounded-lg hover:bg-blue-700 active:bg-blue-800 transition shadow-sm flex justify-center items-center">
                                    <i class="fas fa-edit mr-2"></i> Laporkan Pengiriman
                                </button>
                            @else
                                <div class="text-xs text-gray-500 bg-gray-50 p-2 rounded border border-gray-100">
                                    <p><strong>Penerima:</strong> {{ $dest->receiver_name ?? '-' }}</p>
                                    <p><strong>Waktu:</strong> {{ $dest->delivered_at ? $dest->delivered_at->format('H:i') : '-' }}</p>
                                    @if($dest->notes)
                                        <p class="mt-1 italic">"{{ $dest->notes }}"</p>
                                    @endif
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
            @endforeach
        @endif

        <div x-show="reportModalOpen"
             class="fixed inset-0 z-50 overflow-y-auto"
             style="display: none;"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0">

            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 transition-opacity" @click="reportModalOpen = false">
                    <div class="absolute inset-0 bg-gray-900 opacity-75"></div>
                </div>

                <div class="inline-block align-bottom bg-white rounded-t-xl sm:rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg w-full fixed bottom-0 sm:relative">
                    <form :action="'/driver/tasks/' + destId" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                            <h3 class="text-lg leading-6 font-bold text-gray-900 mb-4 border-b pb-2">
                                Laporan: <span x-text="schoolName" class="text-blue-600"></span>
                            </h3>

                            <div class="space-y-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Status Pengiriman</label>
                                    <div class="grid grid-cols-2 gap-3">
                                        <label class="cursor-pointer">
                                            <input type="radio" name="status" value="Terkirim" class="peer sr-only" checked x-model="status">
                                            <div class="rounded-lg border-2 border-gray-200 p-3 text-center peer-checked:border-green-500 peer-checked:bg-green-50 transition">
                                                <div class="font-bold text-green-700"><i class="fas fa-check-circle mb-1 block text-xl"></i> Terkirim</div>
                                            </div>
                                        </label>
                                        <label class="cursor-pointer">
                                            <input type="radio" name="status" value="Gagal Kirim" class="peer sr-only" x-model="status">
                                            <div class="rounded-lg border-2 border-gray-200 p-3 text-center peer-checked:border-red-500 peer-checked:bg-red-50 transition">
                                                <div class="font-bold text-red-700"><i class="fas fa-times-circle mb-1 block text-xl"></i> Gagal</div>
                                            </div>
                                        </label>
                                    </div>
                                </div>

                               <div x-show="status == 'Terkirim'" x-transition>
    <div class="mb-4">
        <label for="receiver_name" class="block text-sm font-medium text-gray-700 mb-1">Nama Penerima</label>
        <input type="text" name="receiver_name" id="receiver_name"
               class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500"
               placeholder="Contoh: Ibu Guru Siti">
    </div>

    <div class="mb-4">
        <label for="proof_photos" class="block text-sm font-medium text-gray-700 mb-1">Foto Bukti (Bisa Lebih dari 1)</label>

        <input type="file" name="proof_photos[]" id="proof_photos" accept="image/*" multiple
               class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">

        <p class="mt-1 text-xs text-gray-500">
            * Foto akan otomatis dikompres agar hemat kuota & penyimpanan.
        </p>
    </div>
</div>

                                <div>
                                    <label for="notes" class="block text-sm font-medium text-gray-700 mb-1">Catatan</label>
                                    <textarea name="notes" id="notes" rows="2"
                                              class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500"
                                              placeholder="Keterangan tambahan..."></textarea>
                                </div>
                            </div>
                        </div>

                        <div class="bg-gray-50 px-4 py-3 sm:px-6 flex flex-row-reverse gap-2">
                            <button type="submit" class="w-full inline-flex justify-center rounded-lg border border-transparent shadow-sm px-4 py-3 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none sm:w-auto sm:text-sm">
                                Kirim Laporan
                            </button>
                            <button type="button" @click="reportModalOpen = false" class="w-full inline-flex justify-center rounded-lg border border-gray-300 shadow-sm px-4 py-3 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none sm:w-auto sm:text-sm">
                                Batal
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </div>
</x-app-layout>
