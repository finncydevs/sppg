<x-app-layout>
    <x-slot name="header">
        Buat Pesanan Pembelian (PO)
    </x-slot>

    <div class="max-w-5xl mx-auto bg-white p-8 rounded-lg shadow-md"
         x-data="{
            rows: [{ item_id: '', quantity: 1, price: 0 }],
            items: {{ Js::from($items) }}, // Inject data items dari Controller ke JS

            updatePrice(index) {
                let selectedId = this.rows[index].item_id;
                let item = this.items.find(i => i.id == selectedId);
                this.rows[index].price = item ? parseFloat(item.price) : 0;
            },

            calculateTotal() {
                return this.rows.reduce((sum, row) => sum + (row.quantity * row.price), 0);
            },

            formatRupiah(amount) {
                return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR' }).format(amount);
            }
         }">

        <form action="{{ route('purchase-orders.store') }}" method="POST">
            @csrf

            <h3 class="text-lg font-semibold text-gray-800 border-b pb-2 mb-6">Detail Pesanan</h3>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <label for="supplier_id" class="block mb-2 text-sm font-medium text-gray-700">Pilih Supplier</label>
                    <select id="supplier_id" name="supplier_id" required
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                        <option value="">-- Pilih Supplier --</option>
                        @foreach($suppliers as $supplier)
                            <option value="{{ $supplier->id }}">{{ $supplier->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="order_date" class="block mb-2 text-sm font-medium text-gray-700">Tanggal Pesan</label>
                    <input type="date" id="order_date" name="order_date" value="{{ date('Y-m-d') }}" required
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                </div>
            </div>

            <div class="mb-6">
                <div class="flex justify-between items-center mb-2">
                    <label class="block text-sm font-medium text-gray-700">Item Barang</label>
                    <button type="button" @click="rows.push({ item_id: '', quantity: 1, price: 0 })"
                            class="text-sm text-blue-600 hover:text-blue-800 font-semibold flex items-center">
                        <i class="fas fa-plus-circle mr-1"></i> Tambah Item
                    </button>
                </div>

                <div class="border rounded-lg overflow-hidden">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50 text-xs font-semibold text-gray-500 uppercase">
                            <tr>
                                <th class="px-4 py-2 text-left w-1/2">Item</th>
                                <th class="px-4 py-2 text-center w-24">Qty</th>
                                <th class="px-4 py-2 text-right w-32">Harga Satuan</th>
                                <th class="px-4 py-2 text-right w-32">Subtotal</th>
                                <th class="px-4 py-2 text-center w-16">Hapus</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 bg-white">
                            <template x-for="(row, index) in rows" :key="index">
                                <tr> <td class="px-4 py-2">
                                        <select :name="'items[' + index + '][item_id]'" x-model="row.item_id" @change="updatePrice(index)" required
                                            class="w-full text-sm border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                                            <option value="">-- Pilih Item --</option>
                                            <template x-for="item in items">
                                                <option :value="item.id" x-text="item.name + ' (' + item.unit + ')'"></option>
                                            </template>
                                        </select>
                                    </td>
                                    <td class="px-4 py-2">
                                        <input type="number" :name="'items[' + index + '][quantity]'" x-model="row.quantity" min="1" required
                                            class="w-full text-sm border-gray-300 rounded-lg text-center">
                                    </td>
                                    <td class="px-4 py-2 text-right text-gray-600 text-sm">
                                        <span x-text="formatRupiah(row.price)"></span>
                                    </td>
                                    <td class="px-4 py-2 text-right font-medium text-gray-900 text-sm">
                                        <span x-text="formatRupiah(row.quantity * row.price)"></span>
                                    </td>
                                    <td class="px-4 py-2 text-center">
                                        <button type="button" @click="rows.length > 1 ? rows.splice(index, 1) : alert('Minimal satu item!')"
                                                class="text-red-500 hover:text-red-700">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </td>
                                </tr>
                            </template>
                        </tbody>
                        <tfoot class="bg-gray-50">
                            <tr>
                                <td colspan="3" class="px-4 py-3 text-right font-bold text-gray-700">Grand Total</td>
                                <td class="px-4 py-3 text-right font-bold text-blue-600 text-lg">
                                    <span x-text="formatRupiah(calculateTotal())"></span>
                                </td>
                                <td></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>

            <div class="flex items-center justify-end mt-8 pt-4 border-t gap-3">
                <a href="{{ route('purchase-orders.index') }}" class="text-gray-500 bg-white hover:bg-gray-100 rounded-lg border border-gray-200 text-sm font-medium px-5 py-2.5 transition-colors">
                    <i class="fas fa-arrow-left mr-1"></i> Batal
                </a>
                <button type="submit" class="text-white bg-blue-600 hover:bg-blue-700 font-medium rounded-lg text-sm px-5 py-2.5 transition-colors">
                    <i class="fas fa-save mr-1"></i> Simpan Pesanan
                </button>
            </div>
        </form>
    </div>
</x-app-layout>
