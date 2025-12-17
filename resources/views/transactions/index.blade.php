<x-app-layout>
    <x-slot name="header">
        Keuangan (Arus Kas)
    </x-slot>

    <div class="bg-white p-6 rounded-lg shadow-md">
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
            <div class="bg-green-50 p-4 rounded-lg border border-green-200">
                <div class="text-sm text-green-600 font-semibold">Total Pemasukan</div>
                <div class="text-2xl font-bold text-green-700">Rp {{ number_format($summary['income'], 0, ',', '.') }}</div>
            </div>
            <div class="bg-red-50 p-4 rounded-lg border border-red-200">
                <div class="text-sm text-red-600 font-semibold">Total Pengeluaran</div>
                <div class="text-2xl font-bold text-red-700">Rp {{ number_format($summary['expense'], 0, ',', '.') }}</div>
            </div>
            <div class="bg-blue-50 p-4 rounded-lg border border-blue-200">
                <div class="text-sm text-blue-600 font-semibold">Saldo Akhir</div>
                <div class="text-2xl font-bold text-blue-700">Rp {{ number_format($summary['balance'], 0, ',', '.') }}</div>
            </div>
        </div>

        <div class="flex flex-col md:flex-row justify-between items-center mb-4 gap-4">
            <form method="GET" action="{{ route('transactions.index') }}" class="flex items-center gap-2 w-full md:w-auto">
                <input type="month" name="month" value="{{ request('month', date('Y-m')) }}" 
                       class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 p-2">
                
                <select name="type" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 p-2">
                    <option value="">Semua Tipe</option>
                    <option value="Income" {{ request('type') == 'Income' ? 'selected' : '' }}>Pemasukan</option>
                    <option value="Expense" {{ request('type') == 'Expense' ? 'selected' : '' }}>Pengeluaran</option>
                </select>

                <button type="submit" class="bg-gray-600 text-white px-3 py-2 rounded-lg hover:bg-gray-700 text-sm">
                    <i class="fas fa-filter"></i> Filter
                </button>
            </form>

            <a href="{{ route('transactions.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 font-semibold text-sm flex items-center transition-colors">
                <i class="fas fa-plus mr-2"></i> Transaksi Manual
            </a>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left border-collapse">
                <thead class="text-xs text-gray-700 uppercase bg-gray-100 border-b">
                    <tr>
                        <th class="px-6 py-3">Tanggal</th>
                        <th class="px-6 py-3">Kategori</th>
                        <th class="px-6 py-3">Deskripsi</th>
                        <th class="px-6 py-3 text-right">Pemasukan</th>
                        <th class="px-6 py-3 text-right">Pengeluaran</th>
                        <th class="px-6 py-3 text-center">Ref</th>
                        <th class="px-6 py-3 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($transactions as $trx)
                        <tr class="bg-white hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap">{{ $trx->date->format('d M Y') }}</td>
                            <td class="px-6 py-4">
                                <span class="bg-gray-100 text-gray-800 text-xs font-medium px-2.5 py-0.5 rounded">{{ $trx->category }}</span>
                            </td>
                            <td class="px-6 py-4">{{ $trx->description }}</td>
                            <td class="px-6 py-4 text-right text-green-600 font-medium">
                                {{ $trx->type == 'Income' ? 'Rp ' . number_format($trx->amount, 0, ',', '.') : '-' }}
                            </td>
                            <td class="px-6 py-4 text-right text-red-600 font-medium">
                                {{ $trx->type == 'Expense' ? 'Rp ' . number_format($trx->amount, 0, ',', '.') : '-' }}
                            </td>
                            <td class="px-6 py-4 text-center text-xs text-gray-500">
                                {{ $trx->reference_type ?? 'Manual' }}
                            </td>
                            <td class="px-6 py-4 text-center space-x-2">
                                @if($trx->reference_type == 'Manual')
                                    <a href="{{ route('transactions.edit', $trx->id) }}" class="text-blue-600 hover:text-blue-900" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('transactions.destroy', $trx->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Hapus transaksi ini?');">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-900" title="Hapus">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                @else
                                    <i class="fas fa-lock text-gray-400 cursor-not-allowed" title="Otomatis dari Sistem"></i>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-4 text-center text-gray-500">Tidak ada data transaksi.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $transactions->links() }}
        </div>
    </div>
</x-app-layout>
