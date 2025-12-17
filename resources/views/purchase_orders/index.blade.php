<x-app-layout>
    <x-slot name="header">
        Pengadaan (Purchase Order)
    </x-slot>

    <div class="bg-white p-6 rounded-lg shadow-md">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-semibold text-gray-800">Riwayat Pesanan Pembelian</h3>
            <a href="{{ route('purchase-orders.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 font-semibold text-sm flex items-center transition-colors">
                <i class="fas fa-cart-plus mr-2"></i> Buat PO Baru
            </a>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left border-collapse">
                <thead class="text-xs text-gray-700 uppercase bg-gray-100 border-b">
                    <tr>
                        <th class="px-6 py-3">No. PO</th>
                        <th class="px-6 py-3">Supplier</th>
                        <th class="px-6 py-3">Tgl Pesan</th>
                        <th class="px-6 py-3 text-right">Total</th>
                        <th class="px-6 py-3 text-center">Status</th>
                        <th class="px-6 py-3 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($pos as $po)
                        <tr class="bg-white hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 font-medium text-gray-900">{{ $po->po_number }}</td>
                            <td class="px-6 py-4">{{ $po->supplier->name ?? 'Unknown' }}</td>
                            <td class="px-6 py-4">{{ $po->order_date->format('d M Y') }}</td>
                            <td class="px-6 py-4 text-right font-semibold">Rp {{ number_format($po->total_amount, 0, ',', '.') }}</td>
                            <td class="px-6 py-4 text-center">
                                @php
                                    $badges = [
                                        'Draft' => 'bg-gray-100 text-gray-800',
                                        'Partial' => 'bg-yellow-100 text-yellow-800',
                                        'Received' => 'bg-green-100 text-green-800',
                                        'Cancelled' => 'bg-red-100 text-red-800'
                                    ];
                                @endphp
                                <span class="px-2.5 py-1 text-xs font-medium rounded-full {{ $badges[$po->status] ?? 'bg-gray-100' }}">
                                    {{ $po->status }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-center space-x-2">
                                @if(!in_array($po->status, ['Received', 'Cancelled']))
                                    <button onclick="openReceiveModal('{{ $po->id }}', '{{ $po->po_number }}', {{ $po->items }})"
                                            class="text-green-600 hover:text-green-900 text-xs font-bold border border-green-600 px-2 py-1 rounded hover:bg-green-50 transition">
                                        Terima Barang
                                    </button>
                                @endif

                                @if($po->status === 'Draft')
                                    <form action="{{ route('purchase-orders.destroy', $po->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Hapus Draft PO ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-900" title="Hapus">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-4 text-center text-gray-500">Belum ada PO dibuat.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-4">
            {{ $pos->links() }}
        </div>
    </div>

    <div x-data="{ open: false, poId: '', poNumber: '', items: [] }"
         @open-receive-modal.window="open = true; poId = $event.detail.id; poNumber = $event.detail.number; items = $event.detail.items"
         x-show="open"
         class="fixed inset-0 z-50 overflow-y-auto"
         style="display: none;">

        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 transition-opacity" aria-hidden="true" @click="open = false">
                <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
            </div>

            <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-4xl sm:w-full">
                <form :action="'/purchase-orders/' + poId + '/receive'" method="POST">
                    @csrf
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <div class="flex justify-between items-center mb-4 border-b pb-2">
                            <h3 class="text-lg leading-6 font-medium text-gray-900">
                                Penerimaan Barang: <span x-text="poNumber"></span>
                            </h3>
                            <button type="button" @click="open = false" class="text-gray-400 hover:text-gray-500">
                                <span class="sr-only">Close</span>
                                <i class="fas fa-times"></i>
                            </button>
                        </div>

                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Item</th>
                                        <th class="px-4 py-2 text-center text-xs font-medium text-gray-500 uppercase">Dipesan</th>
                                        <th class="px-4 py-2 text-center text-xs font-medium text-gray-500 uppercase">Sdh Diterima</th>
                                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase w-32">Jml Terima Skrg</th>
                                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">No. Lot (Batch)</th>
                                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Expired Date</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    <template x-for="(item, index) in items" :key="item.id">
                                        <tr>
                                            <td class="px-4 py-2 whitespace-nowrap text-sm text-gray-900">
                                                <input type="hidden" :name="'items[' + index + '][item_id]'" :value="item.item_id">
                                                <span x-text="'Item ID: ' + item.item_id"></span>
                                            </td>
                                            <td class="px-4 py-2 whitespace-nowrap text-center text-sm text-gray-500" x-text="item.quantity_ordered"></td>
                                            <td class="px-4 py-2 whitespace-nowrap text-center text-sm text-gray-500" x-text="item.quantity_received"></td>
                                            <td class="px-4 py-2 whitespace-nowrap">
                                                <input type="number" :name="'items[' + index + '][qty_received]'"
                                                       class="w-full text-sm border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                                                       :max="item.quantity_ordered - item.quantity_received" min="0" value="0">
                                            </td>
                                            <td class="px-4 py-2 whitespace-nowrap">
                                                <input type="text" :name="'items[' + index + '][lot_number]'"
                                                       class="w-full text-sm border-gray-300 rounded-md shadow-sm"
                                                       placeholder="Auto if empty">
                                            </td>
                                            <td class="px-4 py-2 whitespace-nowrap">
                                                <input type="date" :name="'items[' + index + '][expiry_date]'"
                                                       class="w-full text-sm border-gray-300 rounded-md shadow-sm">
                                            </td>
                                        </tr>
                                    </template>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none sm:ml-3 sm:w-auto sm:text-sm">
                            Simpan Penerimaan
                        </button>
                        <button type="button" @click="open = false" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                            Batal
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function openReceiveModal(id, number, items) {
            // Dispatch event ke AlpineJS
            window.dispatchEvent(new CustomEvent('open-receive-modal', {
                detail: { id: id, number: number, items: items }
            }));
        }
    </script>
</x-app-layout>
