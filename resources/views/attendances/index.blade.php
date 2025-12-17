<x-app-layout>
    <x-slot name="header">
        Absensi Karyawan
    </x-slot>

    <div class="bg-white p-6 rounded-lg shadow-md">
        
        <div class="flex flex-col md:flex-row justify-between items-center mb-6 gap-4">
            <h3 class="text-lg font-semibold text-gray-800">Input Kehadiran</h3>
            
            <form method="GET" action="{{ route('attendances.index') }}" class="flex items-center gap-2">
                <label class="text-sm text-gray-600">Tanggal:</label>
                <input type="date" name="date" value="{{ $date }}" onchange="this.form.submit()" 
                       class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg p-2">
            </form>
        </div>

        <form action="{{ route('attendances.store') }}" method="POST">
            @csrf
            <input type="hidden" name="date" value="{{ $date }}">

            <div class="overflow-x-auto border rounded-lg">
                <table class="w-full text-sm text-left border-collapse">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-100 border-b">
                        <tr>
                            <th class="px-6 py-3 w-1/3">Nama Karyawan</th>
                            <th class="px-6 py-3 w-1/4">Posisi</th>
                            <th class="px-6 py-3 text-center">Status Kehadiran</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach($employees as $employee)
                            @php
                                // Cek data yang sudah ada di DB (dari controller)
                                $existingStatus = $attendances[$employee->id]->status ?? 'Hadir'; // Default Hadir
                            @endphp
                            <tr class="bg-white hover:bg-gray-50">
                                <td class="px-6 py-4 font-medium text-gray-900">
                                    {{ $employee->name }}
                                </td>
                                <td class="px-6 py-4 text-gray-500">
                                    {{ $employee->position }}
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex justify-center gap-4">
                                        <label class="inline-flex items-center cursor-pointer">
                                            <input type="radio" name="attendance[{{ $employee->id }}]" value="Hadir" 
                                                class="text-green-600 focus:ring-green-500" {{ $existingStatus == 'Hadir' ? 'checked' : '' }}>
                                            <span class="ml-2 text-sm text-gray-700">Hadir</span>
                                        </label>
                                        
                                        <label class="inline-flex items-center cursor-pointer">
                                            <input type="radio" name="attendance[{{ $employee->id }}]" value="Sakit" 
                                                class="text-blue-600 focus:ring-blue-500" {{ $existingStatus == 'Sakit' ? 'checked' : '' }}>
                                            <span class="ml-2 text-sm text-gray-700">Sakit</span>
                                        </label>

                                        <label class="inline-flex items-center cursor-pointer">
                                            <input type="radio" name="attendance[{{ $employee->id }}]" value="Izin" 
                                                class="text-yellow-600 focus:ring-yellow-500" {{ $existingStatus == 'Izin' ? 'checked' : '' }}>
                                            <span class="ml-2 text-sm text-gray-700">Izin</span>
                                        </label>

                                        <label class="inline-flex items-center cursor-pointer">
                                            <input type="radio" name="attendance[{{ $employee->id }}]" value="Alpha" 
                                                class="text-red-600 focus:ring-red-500" {{ $existingStatus == 'Alpha' ? 'checked' : '' }}>
                                            <span class="ml-2 text-sm text-gray-700">Alpha</span>
                                        </label>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="mt-6 flex justify-end">
                <button type="submit" class="bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 font-semibold shadow-md transition-colors flex items-center">
                    <i class="fas fa-save mr-2"></i> Simpan Absensi {{ \Carbon\Carbon::parse($date)->isoFormat('D MMMM Y') }}
                </button>
            </div>
        </form>
    </div>
</x-app-layout>
