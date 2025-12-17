<x-app-layout>
    <x-slot name="header">
        Data Supplier
    </x-slot>

    <div class="bg-white p-6 rounded-lg shadow-md">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-semibold text-gray-800">Daftar Supplier</h3>
            <a href="{{ route('suppliers.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 font-semibold text-sm flex items-center transition-colors">
                <i class="fas fa-plus mr-2"></i> Tambah Supplier
            </a>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left border-collapse">
                <thead class="text-xs text-gray-700 uppercase bg-gray-100 border-b">
                    <tr>
                        <th class="px-6 py-3">Nama Perusahaan</th>
                        <th class="px-6 py-3">Kontak Person</th>
                        <th class="px-6 py-3">Telepon</th>
                        <th class="px-6 py-3">Email</th>
                        <th class="px-6 py-3 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($suppliers as $supplier)
                        <tr class="bg-white hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 font-medium text-gray-900">{{ $supplier->name }}</td>
                            <td class="px-6 py-4">{{ $supplier->contact_person ?? '-' }}</td>
                            <td class="px-6 py-4">{{ $supplier->phone ?? '-' }}</td>
                            <td class="px-6 py-4 text-blue-600">{{ $supplier->email ?? '-' }}</td>
                            <td class="px-6 py-4 text-center space-x-3">
                                <a href="{{ route('suppliers.edit', $supplier->id) }}" class="text-blue-600 hover:text-blue-900" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>

                                <form action="{{ route('suppliers.destroy', $supplier->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Yakin ingin menghapus supplier ini?');">
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
                            <td colspan="5" class="px-6 py-4 text-center text-gray-500">Belum ada data supplier.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $suppliers->links() }}
        </div>
    </div>
</x-app-layout>
