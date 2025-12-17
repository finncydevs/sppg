<x-app-layout>
    <x-slot name="header">
        Tambah Item Baru
    </x-slot>

    <div class="max-w-2xl mx-auto bg-white p-8 rounded-lg shadow-md">
        <form action="{{ route('items.store') }}" method="POST">
            @csrf

            <h3 class="text-lg font-semibold text-gray-800 border-b pb-2 mb-6">Detail Item</h3>

            <div class="space-y-6">
                <div>
                    <label for="name" class="block mb-2 text-sm font-medium text-gray-700">Nama Item</label>
                    <input type="text" id="name" name="name" value="{{ old('name') }}" required
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                        placeholder="Contoh: Beras Premium, Telur Ayam">
                </div>

                <div class="grid grid-cols-2 gap-6">
                    <div>
                        <label for="unit" class="block mb-2 text-sm font-medium text-gray-700">Satuan (Unit Dasar)</label>
                        <input type="text" id="unit" name="unit" value="{{ old('unit') }}" required
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                            placeholder="kg, liter, pcs">
                        <p class="mt-1 text-xs text-gray-500">Gunakan satuan terkecil yang dipakai di resep.</p>
                    </div>

                    <div>
                        <label for="price" class="block mb-2 text-sm font-medium text-gray-700">Harga Estimasi (Rp)</label>
                        <input type="number" id="price" name="price" value="{{ old('price', 0) }}" required min="0"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                    </div>
                </div>
            </div>

            <div class="flex items-center justify-end mt-8 pt-4 border-t gap-3">
                <a href="{{ route('items.index') }}" class="text-gray-500 bg-white hover:bg-gray-100 rounded-lg border border-gray-200 text-sm font-medium px-5 py-2.5 transition-colors">
                    <i class="fas fa-arrow-left mr-1"></i> Batal
                </a>
                <button type="submit" class="text-white bg-blue-600 hover:bg-blue-700 font-medium rounded-lg text-sm px-5 py-2.5 transition-colors">
                    <i class="fas fa-save mr-1"></i> Simpan Item
                </button>
            </div>
        </form>
    </div>
</x-app-layout>
