<x-app-layout>
    <x-slot name="header">Penggajian (Payroll)</x-slot>

    <div class="bg-white p-6 rounded-lg shadow-md">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-semibold text-gray-800">Riwayat Gaji Karyawan</h3>
            <a href="{{ route('salaries.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 font-semibold text-sm flex items-center">
                <i class="fas fa-file-invoice-dollar mr-2"></i> Input Gaji Baru
            </a>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left border-collapse">
                <thead class="text-xs text-gray-700 uppercase bg-gray-100 border-b">
                    <tr>
                        <th class="px-6 py-3">Periode</th>
                        <th class="px-6 py-3">Nama Karyawan</th>
                        <th class="px-6 py-3 text-right">Gaji Pokok</th>
                        <th class="px-6 py-3 text-right">Tunjangan</th>
                        <th class="px-6 py-3 text-right">Potongan</th>
                        <th class="px-6 py-3 text-right">Gaji Bersih (Net)</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($salaries as $salary)
                        <tr class="bg-white hover:bg-gray-50">
                            <td class="px-6 py-4 font-bold text-gray-600">{{ $salary->period }}</td>
                            <td class="px-6 py-4 font-medium text-gray-900">{{ $salary->employee->name }}</td>
                            <td class="px-6 py-4 text-right text-gray-500">Rp {{ number_format($salary->basic_salary, 0, ',', '.') }}</td>
                            <td class="px-6 py-4 text-right text-green-600">+ {{ number_format($salary->allowance, 0, ',', '.') }}</td>
                            <td class="px-6 py-4 text-right text-red-600">- {{ number_format($salary->deduction, 0, ',', '.') }}</td>
                            <td class="px-6 py-4 text-right font-bold text-blue-700">Rp {{ number_format($salary->net_salary, 0, ',', '.') }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="px-6 py-4 text-center text-gray-500">Belum ada data gaji.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-4">{{ $salaries->links() }}</div>
    </div>
</x-app-layout>
