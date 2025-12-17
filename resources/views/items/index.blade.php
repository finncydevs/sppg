<x-app-layout>
    <x-slot name="header">
        Data Item (Bahan Baku)
    </x-slot>

    <div class="bg-white p-6 rounded-lg shadow-md">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-semibold text-gray-800">Daftar Item</h3>
            <a href="{{ route('items.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 font-semibold text-sm flex items-center transition-colors">
                <i class="fas fa-plus mr-2"></i> Tambah Item
            </a>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left border-collapse">
                <thead class="text-xs text-gray-700 uppercase bg-gray-100 border-b">
                    <tr>
                        <th class="px-6 py-3">Nama Item</th>
                        <th class="px-6 py-3 text-center">Satuan (Unit)</th>
                        <th class="px-6 py-3 text-right">Harga Estimasi</th>
                        <th class="px-6 py-3 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($items as $item)
                        <tr class="bg-white hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 font-medium text-gray-900">{{ $item->name }}</td>
                            <td class="px-6 py-4 text-center">
                                <span class="bg-gray-100 text-gray-800 text-xs font-medium px-2.5 py-0.5 rounded">{{ $item->unit }}</span>
                            </td>
                            <td class="px-6 py-4 text-right">Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                            <td class="px-6 py-4 text-center space-x-3">
                                <a href="{{ route('items.edit', $item->id) }}" class="text-blue-600 hover:text-blue-900" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>

                                <form action="{{ route('items.destroy', $item->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Yakin ingin menghapus item ini?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-900" title="Hapus">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-4 text-center text-gray-500">Belum ada data item.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $items->links() }}
        </div>
    </div>
</x-app-layout>
