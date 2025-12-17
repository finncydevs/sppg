<x-app-layout>
    <x-slot name="header">
        Jadwal Distribusi & Pengiriman
    </x-slot>

    <div class="bg-white p-6 rounded-lg shadow-md">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-semibold text-gray-800">Daftar Pengiriman</h3>
            <div class="flex justify-between items-center mb-4">
    <div class="flex space-x-2">
        <h3 class="text-lg font-semibold text-gray-800">Daftar Pengiriman</h3>

        <a href="{{ route('shipments.index') }}"
           class="px-3 py-1 text-xs font-medium rounded-full {{ !request('filter') ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-700 hover:bg-gray-300' }}">
           Semua
        </a>
        <a href="{{ route('shipments.index', ['filter' => 'today']) }}"
           class="px-3 py-1 text-xs font-medium rounded-full {{ request('filter') == 'today' ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-700 hover:bg-gray-300' }}">
           Hari Ini
        </a>
    </div>

    <a href="{{ route('shipments.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 font-semibold text-sm flex items-center transition-colors">
        <i class="fas fa-truck mr-2"></i> Jadwal Baru
    </a>
</div>
            <a href="{{ route('shipments.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 font-semibold text-sm flex items-center transition-colors">
                <i class="fas fa-truck mr-2"></i> Jadwal Baru
            </a>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left border-collapse">
                <thead class="text-xs text-gray-700 uppercase bg-gray-100 border-b">
                    <tr>
                        <th class="px-6 py-3">No. Resi</th>
                        <th class="px-6 py-3">Driver & Waktu</th>
                        <th class="px-6 py-3">Tujuan</th>
                        <th class="px-6 py-3 text-center">Total Muatan</th>
                        <th class="px-6 py-3 text-center">Status</th>
                        <th class="px-6 py-3 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($shipments as $shipment)
                        <tr class="bg-white hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 font-medium text-gray-900">
                                {{ $shipment->shipment_number }}
                            </td>
                            <td class="px-6 py-4">
                                <div class="font-semibold text-gray-800">{{ $shipment->driver->name ?? 'Tanpa Driver' }}</div>
                                <div class="text-xs text-gray-500">
                                    <i class="far fa-clock mr-1"></i> {{ $shipment->departure_time->format('d M Y, H:i') }}
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-xs text-gray-600">
                                    @foreach($shipment->destinations->take(2) as $dest)
                                        <div>• {{ $dest->school->name }} ({{ $dest->quantity }})</div>
                                    @endforeach
                                    @if($shipment->destinations->count() > 2)
                                        <div class="italic text-gray-400">+{{ $shipment->destinations->count() - 2 }} lainnya</div>
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-4 text-center font-bold">
                                {{ $shipment->destinations->sum('quantity') }} Porsi
                            </td>
                            <td class="px-6 py-4 text-center">
                                @php
                                    $statusClasses = [
                                        'Planned' => 'bg-gray-100 text-gray-800',
                                        'In Transit' => 'bg-blue-100 text-blue-800',
                                        'Completed' => 'bg-green-100 text-green-800',
                                        'Cancelled' => 'bg-red-100 text-red-800',
                                    ];
                                @endphp
                                <span class="px-2.5 py-1 text-xs font-medium rounded-full {{ $statusClasses[$shipment->status] ?? 'bg-gray-100' }}">
                                    {{ $shipment->status }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-center space-x-2">
                                <a href="{{ route('shipments.print', $shipment->id) }}" target="_blank" class="text-green-600 hover:text-green-900" title="Cetak Surat Jalan">
                                    <i class="fas fa-print fa-lg"></i>
                                </a>

                                <a href="{{ route('shipments.edit', $shipment->id) }}" class="text-blue-600 hover:text-blue-900" title="Edit">
                                    <i class="fas fa-edit fa-lg"></i>
                                </a>
<a href="{{ route('shipments.show', $shipment->id) }}" class="text-indigo-600 hover:text-indigo-900" title="Lihat Detail & Bukti">
    <i class="fas fa-eye fa-lg"></i>
</a>
                                <form action="{{ route('shipments.destroy', $shipment->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Hapus jadwal pengiriman ini?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-900" title="Hapus">
                                        <i class="fas fa-trash fa-lg"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-4 text-center text-gray-500">Belum ada jadwal pengiriman.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $shipments->links() }}
        </div>
    </div>
</x-app-layout>
