<x-app-layout>
    <x-slot name="header">
        Tambah Supplier Baru
    </x-slot>

    <div class="max-w-4xl mx-auto bg-white p-8 rounded-lg shadow-md">
        <form action="{{ route('suppliers.store') }}" method="POST">
            @csrf

            <h3 class="text-lg font-semibold text-gray-800 border-b pb-2 mb-6">Informasi Supplier</h3>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="md:col-span-2">
                    <label for="name" class="block mb-2 text-sm font-medium text-gray-700">Nama Perusahaan</label>
                    <input type="text" id="name" name="name" value="{{ old('name') }}" required
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                        placeholder="Contoh: PT. Pangan Sejahtera">
                </div>

                <div>
                    <label for="contact_person" class="block mb-2 text-sm font-medium text-gray-700">Nama Kontak (PIC)</label>
                    <input type="text" id="contact_person" name="contact_person" value="{{ old('contact_person') }}"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                </div>

                <div>
                    <label for="phone" class="block mb-2 text-sm font-medium text-gray-700">No. Telepon / WA</label>
                    <input type="text" id="phone" name="phone" value="{{ old('phone') }}"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                </div>

                <div>
                    <label for="email" class="block mb-2 text-sm font-medium text-gray-700">Email</label>
                    <input type="email" id="email" name="email" value="{{ old('email') }}"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                </div>

                <div class="md:col-span-2">
                    <label for="address" class="block mb-2 text-sm font-medium text-gray-700">Alamat Lengkap</label>
                    <textarea id="address" name="address" rows="3"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">{{ old('address') }}</textarea>
                </div>
            </div>

            <div class="flex items-center justify-end mt-8 pt-4 border-t gap-3">
                <a href="{{ route('suppliers.index') }}" class="text-gray-500 bg-white hover:bg-gray-100 rounded-lg border border-gray-200 text-sm font-medium px-5 py-2.5 transition-colors">
                    <i class="fas fa-arrow-left mr-1"></i> Batal
                </a>
                <button type="submit" class="text-white bg-blue-600 hover:bg-blue-700 font-medium rounded-lg text-sm px-5 py-2.5 transition-colors">
                    <i class="fas fa-save mr-1"></i> Simpan Supplier
                </button>
            </div>
        </form>
    </div>
</x-app-layout>
