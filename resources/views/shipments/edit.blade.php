<x-app-layout>
    <x-slot name="header">
        Edit Pengiriman: {{ $shipment->shipment_number }}
    </x-slot>

    <div class="max-w-4xl mx-auto bg-white p-8 rounded-lg shadow-md"
         x-data="{
            destinations: {{ Js::from($shipment->destinations->map(fn($d) => ['school_id' => $d->school_id, 'quantity' => $d->quantity])) }}
         }">

        <form action="{{ route('shipments.update', $shipment->id) }}" method="POST">
            @csrf
            @method('PUT')

            <h3 class="text-lg font-semibold text-gray-800 border-b pb-2 mb-6">Edit Jadwal</h3>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                <div>
                    <label for="driver_id" class="block mb-2 text-sm font-medium text-gray-700">Driver</label>
                    <select id="driver_id" name="driver_id" required
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                        @foreach($drivers as $driver)
                            <option value="{{ $driver->id }}" {{ old('driver_id', $shipment->driver_id) == $driver->id ? 'selected' : '' }}>
                                {{ $driver->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="departure_time" class="block mb-2 text-sm font-medium text-gray-700">Waktu Keberangkatan</label>
                    <input type="datetime-local" id="departure_time" name="departure_time"
                        value="{{ old('departure_time', $shipment->departure_time->format('Y-m-d\TH:i')) }}" required
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                </div>

                <div>
                    <label for="status" class="block mb-2 text-sm font-medium text-gray-700">Status Pengiriman</label>
                    <select id="status" name="status" required
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                        @foreach(['Planned', 'In Transit', 'Completed', 'Cancelled'] as $st)
                            <option value="{{ $st }}" {{ old('status', $shipment->status) == $st ? 'selected' : '' }}>{{ $st }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="mb-6">
                <div class="flex justify-between items-center mb-2">
                    <label class="block text-sm font-medium text-gray-700">Tujuan Sekolah</label>
                    <button type="button" @click="destinations.push({ school_id: '', quantity: '' })"
                            class="text-sm text-blue-600 hover:text-blue-800 font-semibold flex items-center">
                        <i class="fas fa-plus-circle mr-1"></i> Tambah Tujuan
                    </button>
                </div>

                <div class="bg-gray-50 p-4 rounded-lg border border-gray-200">
                    <template x-for="(dest, index) in destinations" :key="index">
                        <div class="grid grid-cols-12 gap-4 mb-3 items-center">
                            <div class="col-span-1 text-center text-gray-500 font-bold" x-text="index + 1 + '.'"></div>

                            <div class="col-span-6">
                                <select :name="'destinations[' + index + '][school_id]'" x-model="dest.school_id" required
                                    class="w-full text-sm border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                                    <option value="">-- Pilih Sekolah --</option>
                                    @foreach($schools as $school)
                                        <option value="{{ $school->id }}">{{ $school->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-span-4">
                                <input type="number" :name="'destinations[' + index + '][quantity]'" x-model="dest.quantity" min="1" required
                                    class="w-full text-sm border-gray-300 rounded-lg" placeholder="Jml Porsi">
                            </div>

                            <div class="col-span-1 text-center">
                                <button type="button" @click="destinations.length > 1 ? destinations.splice(index, 1) : alert('Minimal satu tujuan!')"
                                        class="text-red-500 hover:text-red-700">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                        </div>
                    </template>
                </div>
            </div>

            <div class="flex items-center justify-end mt-8 pt-4 border-t gap-3">
                <a href="{{ route('shipments.index') }}" class="text-gray-500 bg-white hover:bg-gray-100 rounded-lg border border-gray-200 text-sm font-medium px-5 py-2.5 transition-colors">
                    <i class="fas fa-arrow-left mr-1"></i> Batal
                </a>
                <button type="submit" class="text-white bg-blue-600 hover:bg-blue-700 font-medium rounded-lg text-sm px-5 py-2.5 transition-colors">
                    <i class="fas fa-save mr-1"></i> Perbarui Jadwal
                </button>
            </div>
        </form>
    </div>
</x-app-layout>
