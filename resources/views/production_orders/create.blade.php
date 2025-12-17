<x-app-layout>
    <x-slot name="header">
        Buat Perintah Kerja (WO)
    </x-slot>

    <div class="max-w-2xl mx-auto bg-white p-8 rounded-lg shadow-md">
        <form action="{{ route('production-orders.store') }}" method="POST">
            @csrf

            <h3 class="text-lg font-semibold text-gray-800 border-b pb-2 mb-6">Detail Produksi</h3>

            <div class="space-y-6">
                <div>
                    <label for="recipe_id" class="block mb-2 text-sm font-medium text-gray-700">Menu yang akan dimasak</label>
                    <select id="recipe_id" name="recipe_id" required
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                        <option value="">-- Pilih Resep --</option>
                        @foreach($recipes as $recipe)
                            <option value="{{ $recipe->id }}" {{ old('recipe_id') == $recipe->id ? 'selected' : '' }}>
                                {{ $recipe->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="target_quantity" class="block mb-2 text-sm font-medium text-gray-700">Target Produksi (Porsi)</label>
                    <input type="number" id="target_quantity" name="target_quantity" value="{{ old('target_quantity') }}" required min="1"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                        placeholder="Contoh: 500">
                    <p class="mt-1 text-xs text-gray-500">Stok bahan baku akan dikalkulasi berdasarkan jumlah ini.</p>
                </div>

                <div>
                    <label for="production_date" class="block mb-2 text-sm font-medium text-gray-700">Rencana Tanggal Produksi</label>
                    <input type="date" id="production_date" name="production_date" value="{{ old('production_date', date('Y-m-d')) }}" required
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                </div>
            </div>

            <div class="flex items-center justify-end mt-8 pt-4 border-t gap-3">
                <a href="{{ route('production-orders.index') }}" class="text-gray-500 bg-white hover:bg-gray-100 rounded-lg border border-gray-200 text-sm font-medium px-5 py-2.5 transition-colors">
                    <i class="fas fa-arrow-left mr-1"></i> Batal
                </a>
                <button type="submit" class="text-white bg-blue-600 hover:bg-blue-700 font-medium rounded-lg text-sm px-5 py-2.5 transition-colors">
                    <i class="fas fa-save mr-1"></i> Simpan WO
                </button>
            </div>
        </form>
    </div>
</x-app-layout>
