<x-app-layout>
    <x-slot name="header">
        Manajemen Aset
    </x-slot>

    <div class="bg-white p-6 rounded-lg shadow-md">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-semibold text-gray-800">Daftar Aset & Inventaris</h3>
            <a href="{{ route('assets.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 font-semibold text-sm flex items-center transition-colors">
                <i class="fas fa-plus mr-2"></i> Tambah Aset
            </a>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left border-collapse">
                <thead class="text-xs text-gray-700 uppercase bg-gray-100 border-b">
                    <tr>
                        <th class="px-6 py-3">Nama Aset</th>
                        <th class="px-6 py-3">Kategori</th>
                        <th class="px-6 py-3">Tgl Beli</th>
                        <th class="px-6 py-3 text-right">Nilai Aset</th>
                        <th class="px-6 py-3 text-center">Status</th>
                        <th class="px-6 py-3 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($assets as $asset)
                        <tr class="bg-white hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 font-medium text-gray-900">{{ $asset->name }}</td>
                            <td class="px-6 py-4">{{ $asset->category }}</td>
                            <td class="px-6 py-4">{{ $asset->purchase_date->format('d M Y') }}</td>
                            <td class="px-6 py-4 text-right">Rp {{ number_format($asset->value, 0, ',', '.') }}</td>
                            <td class="px-6 py-4 text-center">
                                @php
                                    $statusColor = match($asset->status) {
                                        'Baik' => 'bg-green-100 text-green-800',
                                        'Perlu Perbaikan' => 'bg-yellow-100 text-yellow-800',
                                        'Rusak' => 'bg-red-100 text-red-800',
                                        default => 'bg-gray-100 text-gray-800'
                                    };
                                @endphp
                                <span class="px-2.5 py-1 text-xs font-medium rounded-full {{ $statusColor }}">
                                    {{ $asset->status }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-center space-x-2">
                                <a href="{{ route('assets.edit', $asset->id) }}" class="text-blue-600 hover:text-blue-900"><i class="fas fa-edit"></i></a>
                                <form action="{{ route('assets.destroy', $asset->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Hapus aset ini?');">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-900"><i class="fas fa-trash"></i></button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="px-6 py-4 text-center text-gray-500">Belum ada data aset.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-4">{{ $assets->links() }}</div>
    </div>
</x-app-layout>
