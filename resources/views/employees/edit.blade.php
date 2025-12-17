<x-app-layout>
    <x-slot name="header">
        Edit Karyawan: {{ $employee->name }}
    </x-slot>

    <div class="max-w-4xl mx-auto bg-white p-8 rounded-lg shadow-md">

        @if ($errors->any())
            <div class="mb-4 bg-red-50 border border-red-200 text-red-600 p-4 rounded-lg text-sm">
                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('employees.update', $employee->id) }}" method="POST">
            @csrf
            @method('PUT')

            <h3 class="text-lg font-semibold text-gray-800 border-b pb-2 mb-6">Informasi Akun & Pribadi</h3>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="name" class="block mb-2 text-sm font-medium text-gray-700">Nama Lengkap</label>
                    <input type="text" id="name" name="name" value="{{ old('name', $employee->name) }}" required
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                </div>

                <div>
                    <label for="email" class="block mb-2 text-sm font-medium text-gray-700">Email</label>
                    <input type="email" id="email" name="email" value="{{ old('email', $employee->user->email ?? '') }}" required
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                </div>

                <div>
                    <label for="password" class="block mb-2 text-sm font-medium text-gray-700">Password Baru (Opsional)</label>
                    <input type="password" id="password" name="password"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                        placeholder="Isi hanya jika ingin mengganti password">
                </div>

                <div>
                    <label for="role" class="block mb-2 text-sm font-medium text-gray-700">Role Akses</label>
                    <select id="role" name="role" required
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                        <option value="">-- Pilih Role --</option>
                        @foreach($roles as $roleName)
                            <option value="{{ $roleName }}" {{ (old('role', $userRole) == $roleName) ? 'selected' : '' }}>{{ $roleName }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="position" class="block mb-2 text-sm font-medium text-gray-700">Posisi / Jabatan</label>
                    <input type="text" id="position" name="position" value="{{ old('position', $employee->position) }}" required
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                </div>

                <div>
                    <label for="phone" class="block mb-2 text-sm font-medium text-gray-700">No. Telepon</label>
                    <input type="text" id="phone" name="phone" value="{{ old('phone', $employee->phone) }}"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                </div>

                <div>
                    <label for="join_date" class="block mb-2 text-sm font-medium text-gray-700">Tanggal Bergabung</label>
                    <input type="date" id="join_date" name="join_date" value="{{ old('join_date', $employee->join_date) }}" required
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                </div>

                <div>
                    <label for="status" class="block mb-2 text-sm font-medium text-gray-700">Status Karyawan</label>
                    <select id="status" name="status" required
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                        <option value="Aktif" {{ old('status', $employee->status) == 'Aktif' ? 'selected' : '' }}>Aktif</option>
                        <option value="Nonaktif" {{ old('status', $employee->status) == 'Nonaktif' ? 'selected' : '' }}>Nonaktif (Resign/Keluar)</option>
                    </select>
                    <p class="mt-1 text-xs text-gray-500">Jika Nonaktif, user tidak akan bisa login.</p>
                </div>

                <div class="md:col-span-2">
                    <label for="address" class="block mb-2 text-sm font-medium text-gray-700">Alamat Lengkap</label>
                    <textarea id="address" name="address" rows="3"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">{{ old('address', $employee->address) }}</textarea>
                </div>
            </div>

            <div class="flex items-center justify-end mt-8 pt-4 border-t">
                <a href="{{ route('employees.index') }}" class="text-gray-500 bg-white hover:bg-gray-100 rounded-lg border border-gray-200 text-sm font-medium px-5 py-2.5 mr-3 transition-colors">
                    Batal
                </a>
                <button type="submit" class="text-white bg-blue-600 hover:bg-blue-700 font-medium rounded-lg text-sm px-5 py-2.5 transition-colors">
                    Perbarui Karyawan
                </button>
            </div>
        </form>
    </div>
</x-app-layout>
