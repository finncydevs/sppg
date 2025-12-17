<x-app-layout>
    <x-slot name="header">
        Catat Transaksi Manual
    </x-slot>

    <div class="max-w-2xl mx-auto bg-white p-8 rounded-lg shadow-md">
        <form action="{{ route('transactions.store') }}" method="POST">
            @csrf

            <h3 class="text-lg font-semibold text-gray-800 border-b pb-2 mb-6">Detail Transaksi</h3>

            <div class="space-y-6">
                <div class="grid grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal</label>
                        <input type="date" name="date" value="{{ date('Y-m-d') }}" required class="w-full border-gray-300 rounded-lg">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Tipe Transaksi</label>
                        <select name="type" class="w-full border-gray-300 rounded-lg">
                            <option value="Expense">Pengeluaran (Expense)</option>
                            <option value="Income">Pemasukan (Income)</option>
                        </select>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Kategori</label>
                    <input type="text" name="category" list="categories" required class="w-full border-gray-300 rounded-lg" placeholder="Contoh: Operasional, Listrik, Kas Kecil">
                    <datalist id="categories">
                        <option value="Operasional">
                        <option value="Gaji & Upah">
                        <option value="Transportasi">
                        <option value="Penjualan/Subsidi">
                        <option value="Lain-lain">
                    </datalist>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nominal (Rp)</label>
                    <input type="number" name="amount" required min="0" class="w-full border-gray-300 rounded-lg" placeholder="0">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Keterangan</label>
                    <textarea name="description" rows="3" required class="w-full border-gray-300 rounded-lg" placeholder="Detail transaksi..."></textarea>
                </div>
            </div>

            <div class="flex items-center justify-end mt-8 pt-4 border-t gap-3">
                <a href="{{ route('transactions.index') }}" class="text-gray-500 bg-white hover:bg-gray-100 rounded-lg border border-gray-200 text-sm font-medium px-5 py-2.5">Batal</a>
                <button type="submit" class="text-white bg-blue-600 hover:bg-blue-700 font-medium rounded-lg text-sm px-5 py-2.5">
                    <i class="fas fa-save mr-1"></i> Simpan
                </button>
            </div>
        </form>
    </div>
</x-app-layout>

