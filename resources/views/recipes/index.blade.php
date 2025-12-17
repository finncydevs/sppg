<x-app-layout>
    <x-slot name="header">
        Perencanaan Menu (Resep)
    </x-slot>

    <div class="bg-white p-6 rounded-lg shadow-md">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-semibold text-gray-800">Daftar Resep Masakan</h3>
            <a href="{{ route('recipes.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 font-semibold text-sm flex items-center transition-colors">
                <i class="fas fa-plus mr-2"></i> Buat Resep Baru
            </a>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left border-collapse">
                <thead class="text-xs text-gray-700 uppercase bg-gray-100 border-b">
                    <tr>
                        <th class="px-6 py-3">Nama Menu</th>
                        <th class="px-6 py-3">Jumlah Bahan</th>
                        <th class="px-6 py-3">Instruksi</th>
                        <th class="px-6 py-3 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($recipes as $recipe)
                        <tr class="bg-white hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 font-medium text-gray-900">{{ $recipe->name }}</td>
                            <td class="px-6 py-4">
                                <span class="bg-blue-100 text-blue-800 text-xs font-semibold px-2.5 py-0.5 rounded">
                                    {{ $recipe->items->count() }} Jenis Bahan
                                </span>
                            </td>
                            <td class="px-6 py-4 truncate max-w-xs text-gray-500">
                                {{ Str::limit($recipe->instruction, 50) ?? 'Tidak ada instruksi khusus' }}
                            </td>
                            <td class="px-6 py-4 text-center space-x-3">
                                <form action="{{ route('recipes.destroy', $recipe->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Yakin ingin menghapus resep ini?');">
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
                            <td colspan="4" class="px-6 py-4 text-center text-gray-500">Belum ada resep. Silakan buat baru.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $recipes->links() }}
        </div>
    </div>
</x-app-layout>
    