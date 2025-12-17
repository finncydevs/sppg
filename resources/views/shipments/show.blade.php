<x-app-layout>
    <x-slot name="header">
        Detail Pengiriman: {{ $shipment->shipment_number }}
    </x-slot>

    <div class="space-y-6">

        <div class="bg-white p-6 rounded-lg shadow-md">
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 border-b pb-4">
                <div>
                    <h3 class="text-xl font-bold text-gray-800">Informasi Pengiriman</h3>
                    <p class="text-sm text-gray-500 mt-1">
                        <i class="far fa-clock mr-1"></i> Berangkat: {{ $shipment->departure_time->format('d M Y, H:i') }} WIB
                    </p>
                </div>
                <div class="mt-4 md:mt-0 text-right">
                    <span class="px-3 py-1 text-sm font-medium rounded-full
                        {{ $shipment->status === 'Completed' ? 'bg-green-100 text-green-800' : 'bg-blue-100 text-blue-800' }}">
                        {{ $shipment->status }}
                    </span>
                    <p class="text-sm font-semibold text-gray-700 mt-2">Driver: {{ $shipment->driver->name }}</p>
                </div>
            </div>

            <div class="space-y-8">
                @foreach($shipment->destinations as $dest)
                    <div class="border rounded-lg p-5 {{ $dest->status === 'Gagal Kirim' ? 'bg-red-50 border-red-200' : 'bg-gray-50 border-gray-200' }}">

                        <div class="flex flex-col md:flex-row justify-between mb-4">
                            <div>
                                <h4 class="text-lg font-bold text-gray-800">{{ $dest->school->name }}</h4>
                                <p class="text-sm text-gray-600">{{ $dest->school->address }}</p>
                            </div>
                            <div class="mt-2 md:mt-0 text-right">
                                @if($dest->status === 'Terkirim')
                                    <span class="text-green-600 font-bold flex items-center justify-end">
                                        <i class="fas fa-check-circle mr-1"></i> Terkirim
                                    </span>
                                    <p class="text-xs text-gray-500 mt-1">
                                        Diterima: {{ $dest->delivered_at ? $dest->delivered_at->format('H:i') : '-' }}
                                    </p>
                                @elseif($dest->status === 'Gagal Kirim')
                                    <span class="text-red-600 font-bold flex items-center justify-end">
                                        <i class="fas fa-times-circle mr-1"></i> Gagal
                                    </span>
                                @else
                                    <span class="text-gray-500 font-bold">Pending</span>
                                @endif
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="space-y-3 text-sm">
                                <div class="flex justify-between border-b border-gray-200 pb-2">
                                    <span class="text-gray-500">Jumlah Muatan</span>
                                    <span class="font-medium">{{ $dest->quantity }} Porsi</span>
                                </div>
                                <div class="flex justify-between border-b border-gray-200 pb-2">
                                    <span class="text-gray-500">Nama Penerima</span>
                                    <span class="font-medium">{{ $dest->receiver_name ?? '-' }}</span>
                                </div>
                                <div>
                                    <span class="text-gray-500 block mb-1">Catatan Driver</span>
                                    <p class="bg-white p-2 rounded border border-gray-200 italic text-gray-600">
                                        "{{ $dest->notes ?? 'Tidak ada catatan' }}"
                                    </p>
                                </div>
                            </div>

                            <div>
                                <span class="text-gray-500 text-sm block mb-2 font-medium">Bukti Foto</span>

                                @if($dest->proofs->isEmpty())
                                    <div class="h-32 bg-gray-200 rounded-lg flex items-center justify-center text-gray-400 text-sm">
                                        Tidak ada foto bukti
                                    </div>
                                @else
                                    <div class="grid grid-cols-2 gap-2">
                                        @foreach($dest->proofs as $proof)
                                            <a href="{{ asset('storage/' . $proof->photo_path) }}" target="_blank" class="block group relative overflow-hidden rounded-lg border border-gray-300">
                                                <img src="{{ asset('storage/' . $proof->photo_path) }}"
                                                     class="w-full h-32 object-cover transition-transform duration-300 group-hover:scale-110"
                                                     alt="Bukti Kirim">
                                                <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-30 transition-opacity flex items-center justify-center">
                                                    <i class="fas fa-search-plus text-white opacity-0 group-hover:opacity-100"></i>
                                                </div>
                                            </a>
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                        </div>

                    </div>
                @endforeach
            </div>
        </div>

        <div class="flex justify-end">
            <a href="{{ route('shipments.index') }}" class="bg-gray-600 text-white px-5 py-2.5 rounded-lg hover:bg-gray-700 transition shadow">
                <i class="fas fa-arrow-left mr-2"></i> Kembali ke Daftar
            </a>
        </div>
    </div>
</x-app-layout>
