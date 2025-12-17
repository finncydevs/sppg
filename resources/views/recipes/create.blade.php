<x-app-layout>
    <x-slot name="header">
        Buat Resep Baru
    </x-slot>

    <div class="max-w-4xl mx-auto bg-white p-8 rounded-lg shadow-md"
         x-data="{
            rows: [{ item_id: '', quantity: '' }]
         }">

        <form action="{{ route('recipes.store') }}" method="POST">
            @csrf

            <h3 class="text-lg font-semibold text-gray-800 border-b pb-2 mb-6">Detail Resep</h3>

            <div class="mb-6">
                <label for="name" class="block mb-2 text-sm font-medium text-gray-700">Nama Menu Masakan</label>
                <input type="text" id="name" name="name" value="{{ old('name') }}" required
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                    placeholder="Contoh: Ayam Goreng Mentega">
            </div>

            <div class="mb-6">
                <div class="flex justify-between items-center mb-2">
                    <label class="block text-sm font-medium text-gray-700">Bahan-bahan (Per Porsi)</label>
                    <button type="button" @click="rows.push({ item_id: '', quantity: '' })"
                            class="text-sm text-blue-600 hover:text-blue-800 font-semibold flex items-center">
                        <i class="fas fa-plus-circle mr-1"></i> Tambah Bahan
                    </button>
                </div>

                <div class="grid grid-cols-12 gap-4 mb-2 text-xs font-semibold text-gray-500 uppercase">
                    <div class="col-span-7">Nama Bahan Baku</div>
                    <div class="col-span-4">Jumlah (Satuan Item)</div>
                    <div class="col-span-1 text-center">Hapus</div>
                </div>

                <template x-for="(row, index) in rows" :key="index">
                    <div class="grid grid-cols-12 gap-4 mb-3 items-center">
                        <div class="col-span-7">
                            <select :name="'items[' + index + '][item_id]'" x-model="row.item_id" required
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                                <option value="">-- Pilih Bahan --</option>
                                @foreach($items as $item)
                                    <option value="{{ $item->id }}">{{ $item->name }} ({{ $item->unit }})</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-span-4">
                            <input type="number" :name="'items[' + index + '][quantity]'" x-model="row.quantity" required step="0.001" min="0"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                                placeholder="0.00">
                        </div>
                        <div class="col-span-1 text-center">
                            <button type="button" @click="rows.length > 1 ? rows.splice(index, 1) : alert('Minimal satu bahan!')"
                                    class="text-red-500 hover:text-red-700">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    </div>
                </template>
            </div>

            <div class="mb-6">
                <label for="instruction" class="block mb-2 text-sm font-medium text-gray-700">Instruksi Memasak (Opsional)</label>
                <textarea id="instruction" name="instruction" rows="4"
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                    placeholder="Tuliskan cara memasak di sini...">{{ old('instruction') }}</textarea>
            </div>

            <div class="flex items-center justify-end mt-8 pt-4 border-t gap-3">
                <a href="{{ route('recipes.index') }}" class="text-gray-500 bg-white hover:bg-gray-100 rounded-lg border border-gray-200 text-sm font-medium px-5 py-2.5 transition-colors">
                    <i class="fas fa-arrow-left mr-1"></i> Batal
                </a>
                <button type="submit" class="text-white bg-blue-600 hover:bg-blue-700 font-medium rounded-lg text-sm px-5 py-2.5 transition-colors">
                    <i class="fas fa-save mr-1"></i> Simpan Resep
                </button>
            </div>
        </form>
    </div>
</x-app-layout>
