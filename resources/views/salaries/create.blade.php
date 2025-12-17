<x-app-layout>
    <x-slot name="header">Input Gaji Karyawan</x-slot>

    <div class="max-w-2xl mx-auto bg-white p-8 rounded-lg shadow-md">
        <form action="{{ route('salaries.store') }}" method="POST">
            @csrf
            <h3 class="text-lg font-semibold text-gray-800 border-b pb-2 mb-6">Rincian Gaji</h3>

            <div class="space-y-6">
                <div class="grid grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Karyawan</label>
                        <select name="employee_id" required class="w-full border-gray-300 rounded-lg">
                            <option value="">-- Pilih Karyawan --</option>
                            @foreach($employees as $emp)
                                <option value="{{ $emp->id }}">{{ $emp->name }} ({{ $emp->position }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Periode (Bulan)</label>
                        <input type="month" name="period" required value="{{ date('Y-m') }}" class="w-full border-gray-300 rounded-lg">
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Gaji Pokok (Rp)</label>
                    <input type="number" name="basic_salary" required min="0" class="w-full border-gray-300 rounded-lg font-bold">
                </div>

                <div class="grid grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Total Tunjangan (+)</label>
                        <input type="number" name="allowance" value="0" min="0" class="w-full border-gray-300 rounded-lg text-green-700">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Total Potongan (-)</label>
                        <input type="number" name="deduction" value="0" min="0" class="w-full border-gray-300 rounded-lg text-red-700">
                    </div>
                </div>
            </div>

            <div class="flex items-center justify-end mt-8 pt-4 border-t gap-3">
                <a href="{{ route('salaries.index') }}" class="text-gray-500 bg-white hover:bg-gray-100 px-5 py-2.5 rounded-lg border">Batal</a>
                <button type="submit" class="text-white bg-blue-600 hover:bg-blue-700 px-5 py-2.5 rounded-lg">Simpan & Hitung</button>
            </div>
        </form>
    </div>
</x-app-layout>
