<x-app-layout>
    <x-slot name="header">
        Buat Master Rute Baru
    </x-slot>

    <div class="max-w-4xl mx-auto bg-white p-8 rounded-lg shadow-md"
         x-data="{
            destinations: [{ school_id: '', quantity: '' }]
         }">

        <form action="{{ route('route-templates.store') }}" method="POST">
            @csrf

            <h3 class="text-lg font-semibold text-gray-800 border-b pb-2 mb-6">Detail Rute</h3>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                <div class="md:col-span-1">
                    <label for="name" class="block mb-2 text-sm font-medium text-gray-700">Nama Rute</label>
                    <input type="text" name="name" required class="w-full border-gray-300 rounded-lg" placeholder="Contoh: Rute Pagi A">
                </div>

                <div class="md:col-span-1">
                    <label class="block mb-2 text-sm font-medium text-gray-700">Driver Tetap</label>
                    <select name="default_driver_id" required class="w-full border-gray-300 rounded-lg">
                        <option value="">-- Pilih Driver --</option>
                        @foreach($drivers as $driver)
                            <option value="{{ $driver->id }}">{{ $driver->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="md:col-span-1">
                    <label class="block mb-2 text-sm font-medium text-gray-700">Jam Berangkat (Standar)</label>
                    <input type="time" name="default_departure_time" required class="w-full border-gray-300 rounded-lg" value="08:00">
                </div>
            </div>

            <div class="mb-6">
                <div class="flex justify-between items-center mb-2">
                    <label class="block text-sm font-medium text-gray-700">Daftar Sekolah Tujuan</label>
                    <button type="button" @click="destinations.push({ school_id: '', quantity: '' })"
                            class="text-sm text-blue-600 hover:text-blue-800 font-semibold flex items-center">
                        <i class="fas fa-plus-circle mr-1"></i> Tambah Tujuan
                    </button>
                </div>

                <div class="bg-gray-50 p-4 rounded-lg border border-gray-200">
                    <template x-for="(dest, index) in destinations" :key="index">
                        <div class="grid grid-cols-12 gap-4 mb-3 items-center">
                            <div class="col-span-1 text-center font-bold text-gray-500" x-text="index+1"></div>

                            <div class="col-span-6">
                                <select :name="'destinations[' + index + '][school_id]'" x-model="dest.school_id" required
                                    class="w-full text-sm border-gray-300 rounded-lg">
                                    <option value="">-- Pilih Sekolah --</option>
                                    @foreach($schools as $school)
                                        <option value="{{ $school->id }}">{{ $school->name }} ({{ $school->address }})</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-span-4">
                                <input type="number" :name="'destinations[' + index + '][quantity]'" x-model="dest.quantity" min="1" required
                                    class="w-full text-sm border-gray-300 rounded-lg" placeholder="Porsi Standar">
                            </div>

                            <div class="col-span-1 text-center">
                                <button type="button" @click="destinations.length > 1 ? destinations.splice(index, 1) : alert('Minimal satu!')"
                                        class="text-red-500 hover:text-red-700">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                        </div>
                    </template>
                </div>
            </div>

            <div class="flex items-center justify-end mt-8 pt-4 border-t gap-3">
                <a href="{{ route('route-templates.index') }}" class="text-gray-500 bg-white hover:bg-gray-100 rounded-lg border border-gray-200 text-sm font-medium px-5 py-2.5">Batal</a>
                <button type="submit" class="text-white bg-blue-600 hover:bg-blue-700 font-medium rounded-lg text-sm px-5 py-2.5">Simpan Master Rute</button>
            </div>
        </form>
    </div>
</x-app-layout>
