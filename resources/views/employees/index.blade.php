<x-app-layout>
    <x-slot name="header">
        Data Karyawan
    </x-slot>

    <div class="bg-white p-6 rounded-lg shadow-md">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-semibold text-gray-800">Daftar Karyawan</h3>
            <a href="{{ route('employees.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 font-semibold text-sm flex items-center transition-colors">
                <i class="fa-solid fa-plus h-5 w-5 mr-2"></i>
                Tambah Karyawan
            </a>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left border-collapse">
                <thead class="text-xs text-gray-700 uppercase bg-gray-100 border-b">
                    <tr>
                        <th class="px-6 py-3">Nama</th>
                        <th class="px-6 py-3">Posisi</th>
                        <th class="px-6 py-3">Email Login</th>
                        <th class="px-6 py-3">Role Sistem</th>
                        <th class="px-6 py-3 text-center">Status</th>
                        <th class="px-6 py-3 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($employees as $employee)
                        <tr class="bg-white hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 font-medium text-gray-900">{{ $employee->name }}</td>
                            <td class="px-6 py-4">{{ $employee->position }}</td>
                            <td class="px-6 py-4 text-gray-500">{{ $employee->user->email ?? '-' }}</td>
                            <td class="px-6 py-4">
                                @if($employee->user && $employee->user->roles->isNotEmpty())
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-indigo-100 text-indigo-800">
                                        {{ $employee->user->roles->first()->name }}
                                    </span>
                                @else
                                    <span class="text-xs text-gray-400">No Role</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-center">
                                @if($employee->status === 'Aktif')
                                    <span class="px-2.5 py-1 text-xs font-medium rounded-full bg-green-100 text-green-800">Aktif</span>
                                @else
                                    <span class="px-2.5 py-1 text-xs font-medium rounded-full bg-red-100 text-red-800">Nonaktif</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-center space-x-3">
                                <a href="{{ route('employees.edit', $employee->id) }}" class="text-blue-600 hover:text-blue-900 font-medium">Edit</a>

                                <form action="{{ route('employees.destroy', $employee->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Apakah Anda yakin ingin menghapus karyawan ini? Akun login juga akan dihapus.');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-900 font-medium">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-4 text-center text-gray-500">Belum ada data karyawan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $employees->links() }}
        </div>
    </div>
</x-app-layout>
