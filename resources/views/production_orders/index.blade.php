<x-app-layout>
    <x-slot name="header">
        Produksi (Work Order)
    </x-slot>

    <div class="bg-white p-6 rounded-lg shadow-md">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-semibold text-gray-800">Daftar Perintah Kerja</h3>
            <a href="{{ route('production-orders.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 font-semibold text-sm flex items-center transition-colors">
                <i class="fas fa-hammer mr-2"></i> Buat WO Baru
            </a>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left border-collapse">
                <thead class="text-xs text-gray-700 uppercase bg-gray-100 border-b">
                    <tr>
                        <th class="px-6 py-3">No. WO</th>
                        <th class="px-6 py-3">Menu (Resep)</th>
                        <th class="px-6 py-3 text-center">Target Porsi</th>
                        <th class="px-6 py-3">Tgl Produksi</th>
                        <th class="px-6 py-3 text-center">Status</th>
                        <th class="px-6 py-3 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($wos as $wo)
                        <tr class="bg-white hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 font-medium text-gray-900">{{ $wo->wo_number }}</td>
                            <td class="px-6 py-4">{{ $wo->recipe->name ?? 'Resep Terhapus' }}</td>
                            <td class="px-6 py-4 text-center font-bold">{{ number_format($wo->target_quantity, 0, ',', '.') }}</td>
                            <td class="px-6 py-4">{{ $wo->production_date->format('d M Y') }}</td>
                            <td class="px-6 py-4 text-center">
                                @if($wo->status === 'Completed')
                                    <span class="px-2.5 py-1 text-xs font-medium rounded-full bg-green-100 text-green-800">
                                        Selesai
                                    </span>
                                @else
                                    <span class="px-2.5 py-1 text-xs font-medium rounded-full bg-blue-100 text-blue-800">
                                        Direncanakan
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-center space-x-2">
                                @if($wo->status !== 'Completed')
                                    <form action="{{ route('production-orders.complete', $wo->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Selesaikan produksi ini? Stok bahan baku akan dikurangi otomatis.');">
                                        @csrf
                                        @method('POST') <button type="submit" class="text-green-600 hover:text-green-900 text-xs font-bold border border-green-600 px-2 py-1 rounded hover:bg-green-50 transition" title="Selesaikan & Potong Stok">
                                            <i class="fas fa-check mr-1"></i> Selesaikan
                                        </button>
                                    </form>

                                    <form action="{{ route('production-orders.destroy', $wo->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Hapus rencana produksi ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-900" title="Hapus">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                @else
                                    <span class="text-gray-400 text-xs italic">Terkunci</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-4 text-center text-gray-500">Belum ada perintah kerja.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $wos->links() }}
        </div>
    </div>
</x-app-layout>
