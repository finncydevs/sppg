<x-app-layout>
    <x-slot name="header">Tambah Aset Baru</x-slot>
    <div class="max-w-2xl mx-auto bg-white p-8 rounded-lg shadow-md">
        <form action="{{ route('assets.store') }}" method="POST">
            @csrf
            <h3 class="text-lg font-semibold text-gray-800 border-b pb-2 mb-6">Detail Aset</h3>
            <div class="space-y-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nama Aset</label>
                    <input type="text" name="name" required class="w-full border-gray-300 rounded-lg">
                </div>
                <div class="grid grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Kategori</label>
                        <input type="text" name="category" required class="w-full border-gray-300 rounded-lg" placeholder="Elektronik, Kendaraan, dll">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Pembelian</label>
                        <input type="date" name="purchase_date" required class="w-full border-gray-300 rounded-lg">
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Nilai Aset (Rp)</label>
                        <input type="number" name="value" required min="0" class="w-full border-gray-300 rounded-lg">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Kondisi</label>
                        <select name="status" class="w-full border-gray-300 rounded-lg">
                            <option value="Baik">Baik</option>
                            <option value="Perlu Perbaikan">Perlu Perbaikan</option>
                            <option value="Rusak">Rusak</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="flex items-center justify-end mt-8 pt-4 border-t gap-3">
                <a href="{{ route('assets.index') }}" class="text-gray-500 bg-white hover:bg-gray-100 px-5 py-2.5 rounded-lg border">Batal</a>
                <button type="submit" class="text-white bg-blue-600 hover:bg-blue-700 px-5 py-2.5 rounded-lg">Simpan</button>
            </div>
        </form>
    </div>
</x-app-layout>

