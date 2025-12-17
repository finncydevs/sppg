<x-app-layout>
    <x-slot name="header">
        Stok Gudang (Inventory)
    </x-slot>

    <div class="bg-white p-6 rounded-lg shadow-md" x-data="{
            adjustmentModalOpen: false,
            lotModalOpen: false,
            selectedItem: null,
            adjustmentType: 'increase', // 'increase' or 'decrease'
            selectedLots: []
         }">

        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-semibold text-gray-800">Posisi Stok Saat Ini</h3>

            <button @click="adjustmentModalOpen = true; adjustmentType = 'increase'; selectedItem = null"
                    class="bg-gray-600 text-white px-4 py-2 rounded-lg hover:bg-gray-700 font-semibold text-sm flex items-center transition-colors">
                <i class="fas fa-sliders-h mr-2"></i> Penyesuaian Stok (Opname)
            </button>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left border-collapse">
                <thead class="text-xs text-gray-700 uppercase bg-gray-100 border-b">
                    <tr>
                        <th class="px-6 py-3">Nama Barang</th>
                        <th class="px-6 py-3 text-center">Total Stok</th>
                        <th class="px-6 py-3 text-center">Satuan</th>
                        <th class="px-6 py-3">Exp Terdekat</th>
                        <th class="px-6 py-3 text-center">Detail</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($stocks as $item)
                        <tr class="bg-white hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 font-medium text-gray-900">{{ $item->name }}</td>
                            <td class="px-6 py-4 text-center font-bold {{ $item->total_stock <= 0 ? 'text-red-600' : 'text-green-600' }}">
                                {{ number_format($item->total_stock ?? 0, 2, ',', '.') }}
                            </td>
                            <td class="px-6 py-4 text-center">{{ $item->unit }}</td>
                            <td class="px-6 py-4 text-xs text-gray-500">
                                @if($item->inventoryLots->isNotEmpty())
                                    {{ $item->inventoryLots->first()->expiry_date ? $item->inventoryLots->first()->expiry_date->format('d M Y') : '-' }}
                                @else
                                    -
                                @endif
                            </td>
                            <td class="px-6 py-4 text-center">
                                <button @click="lotModalOpen = true; selectedLots = {{ Js::from($item->inventoryLots) }}; selectedItem = '{{ $item->name }}'"
                                        class="text-blue-600 hover:underline text-xs font-semibold">
                                    Lihat {{ $item->inventoryLots->count() }} Lot
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-4 text-center text-gray-500">Belum ada data stok.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div x-show="lotModalOpen" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
            <div class="flex items-center justify-center min-h-screen px-4 text-center sm:block sm:p-0">
                <div class="fixed inset-0 transition-opacity" @click="lotModalOpen = false">
                    <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
                </div>
                <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-3xl sm:w-full">
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">
                            Detail Batch: <span x-text="selectedItem"></span>
                        </h3>
                        <div class="overflow-x-auto border rounded-lg">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-4 py-2 text-xs font-medium text-gray-500 uppercase">No. Lot</th>
                                        <th class="px-4 py-2 text-xs font-medium text-gray-500 uppercase">Sisa Stok</th>
                                        <th class="px-4 py-2 text-xs font-medium text-gray-500 uppercase">Tgl Masuk</th>
                                        <th class="px-4 py-2 text-xs font-medium text-gray-500 uppercase">Expired</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    <template x-for="lot in selectedLots" :key="lot.id">
                                        <tr>
                                            <td class="px-4 py-2 text-sm text-gray-900" x-text="lot.lot_number"></td>
                                            <td class="px-4 py-2 text-sm font-bold text-gray-900" x-text="lot.quantity_current"></td>
                                            <td class="px-4 py-2 text-sm text-gray-500" x-text="new Date(lot.received_date).toLocaleDateString('id-ID')"></td>
                                            <td class="px-4 py-2 text-sm text-red-500" x-text="lot.expiry_date ? new Date(lot.expiry_date).toLocaleDateString('id-ID') : '-'"></td>
                                        </tr>
                                    </template>
                                    <tr x-show="selectedLots.length === 0">
                                        <td colspan="4" class="px-4 py-2 text-center text-sm text-gray-500">Stok Kosong</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <button type="button" @click="lotModalOpen = false" class="w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 sm:ml-3 sm:w-auto sm:text-sm">
                            Tutup
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <div x-show="adjustmentModalOpen" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
            <div class="flex items-center justify-center min-h-screen px-4 text-center sm:block sm:p-0">
                <div class="fixed inset-0 transition-opacity" @click="adjustmentModalOpen = false">
                    <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
                </div>

                <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                    <form action="{{ route('inventory.adjust') }}" method="POST">
                        @csrf
                        <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Penyesuaian Stok Manual</h3>

                            <div class="flex space-x-4 mb-4">
                                <label class="flex items-center cursor-pointer">
                                    <input type="radio" name="type" value="increase" x-model="adjustmentType" class="form-radio text-green-600">
                                    <span class="ml-2 text-sm font-medium text-green-700">Penambahan (+)</span>
                                </label>
                                <label class="flex items-center cursor-pointer">
                                    <input type="radio" name="type" value="decrease" x-model="adjustmentType" class="form-radio text-red-600">
                                    <span class="ml-2 text-sm font-medium text-red-700">Pengurangan (-)</span>
                                </label>
                            </div>

                            <div class="space-y-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Nama Barang</label>
                                    <select name="item_id" required class="w-full border-gray-300 rounded-md shadow-sm text-sm" x-model="selectedItem">
                                        <option value="">-- Pilih Barang --</option>
                                        @foreach($stocks as $stock)
                                            <option value="{{ $stock->id }}">{{ $stock->name }} (Sisa: {{ $stock->total_stock }})</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div x-show="adjustmentType === 'decrease'">
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Pilih Batch/Lot (Wajib untuk Pengurangan)</label>
                                    <select name="lot_id" class="w-full border-gray-300 rounded-md shadow-sm text-sm">
                                        <option value="">-- Pilih Lot --</option>
                                        @foreach($stocks as $stock)
                                            <optgroup label="{{ $stock->name }}" x-show="selectedItem == {{ $stock->id }}">
                                                @foreach($stock->inventoryLots as $lot)
                                                    <option value="{{ $lot->id }}">{{ $lot->lot_number }} (Sisa: {{ $lot->quantity_current }})</option>
                                                @endforeach
                                            </optgroup>
                                        @endforeach
                                    </select>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Jumlah</label>
                                    <input type="number" name="quantity" required step="0.01" min="0.01" class="w-full border-gray-300 rounded-md shadow-sm text-sm">
                                </div>

                                <div x-show="adjustmentType === 'increase'">
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Kedaluwarsa (Opsional)</label>
                                    <input type="date" name="expiry_date" class="w-full border-gray-300 rounded-md shadow-sm text-sm">
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Catatan / Alasan</label>
                                    <textarea name="notes" rows="2" class="w-full border-gray-300 rounded-md shadow-sm text-sm" placeholder="Contoh: Barang rusak, Stok awal, dll"></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                            <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 sm:ml-3 sm:w-auto sm:text-sm">
                                Simpan
                            </button>
                            <button type="button" @click="adjustmentModalOpen = false" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                                Batal
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </div>
</x-app-layout>
