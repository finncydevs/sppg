<x-app-layout>
    <x-slot name="header">
        Master Rute (Template)
    </x-slot>

    <div class="bg-white p-6 rounded-lg shadow-md" x-data="{ generateModalOpen: false, selectedTemplateId: '', selectedTemplateName: '' }">
        <div class="flex justify-between items-center mb-4">
            <div>
                <h3 class="text-lg font-semibold text-gray-800">Daftar Template Rute</h3>
                <p class="text-sm text-gray-500">Buat template sekali, generate jadwal berkali-kali.</p>
            </div>
            <a href="{{ route('route-templates.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 font-semibold text-sm flex items-center transition-colors">
                <i class="fas fa-plus mr-2"></i> Buat Master Rute
            </a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($templates as $template)
                <div class="border rounded-lg p-5 hover:shadow-md transition bg-gray-50">
                    <div class="flex justify-between items-start mb-3">
                        <h4 class="font-bold text-lg text-gray-800">{{ $template->name }}</h4>
                        <form action="{{ route('route-templates.destroy', $template->id) }}" method="POST" onsubmit="return confirm('Hapus template ini?');">
                            @csrf @method('DELETE')
                            <button type="submit" class="text-red-400 hover:text-red-600"><i class="fas fa-trash"></i></button>
                        </form>
                    </div>

                    <div class="text-sm space-y-2 mb-4">
                        <div class="flex items-center text-gray-600">
                            <i class="fas fa-user-tie w-6 text-center mr-2"></i>
                            {{ $template->driver->name ?? 'Tanpa Driver' }}
                        </div>
                        <div class="flex items-center text-gray-600">
                            <i class="far fa-clock w-6 text-center mr-2"></i>
                            Berangkat: {{ \Carbon\Carbon::parse($template->default_departure_time)->format('H:i') }}
                        </div>
                        <div class="flex items-center text-gray-600">
                            <i class="fas fa-map-marker-alt w-6 text-center mr-2"></i>
                            {{ $template->destinations->count() }} Sekolah Tujuan
                        </div>
                    </div>

                    <button @click="generateModalOpen = true; selectedTemplateId = '{{ $template->id }}'; selectedTemplateName = '{{ $template->name }}'"
                            class="w-full bg-green-600 text-white py-2 rounded-lg font-semibold text-sm hover:bg-green-700 transition flex justify-center items-center">
                        <i class="fas fa-magic mr-2"></i> Generate Jadwal
                    </button>
                </div>
            @empty
                <div class="col-span-3 text-center py-10 text-gray-500 bg-gray-50 rounded-lg">
                    Belum ada master rute. Silakan buat baru.
                </div>
            @endforelse
        </div>

        <div class="mt-4">
            {{ $templates->links() }}
        </div>

        <div x-show="generateModalOpen" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
            <div class="flex items-center justify-center min-h-screen px-4 text-center sm:block sm:p-0">
                <div class="fixed inset-0 transition-opacity" @click="generateModalOpen = false">
                    <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
                </div>

                <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                    <form action="{{ route('route-templates.generate') }}" method="POST">
                        @csrf
                        <input type="hidden" name="template_id" x-model="selectedTemplateId">

                        <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                            <h3 class="text-lg font-bold text-gray-900 mb-4 border-b pb-2">
                                Generate Jadwal: <span x-text="selectedTemplateName" class="text-blue-600"></span>
                            </h3>

                            <div class="space-y-4">
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Dari Tanggal</label>
                                        <input type="date" name="start_date" required class="w-full border-gray-300 rounded-lg text-sm" value="{{ date('Y-m-01') }}">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Sampai Tanggal</label>
                                        <input type="date" name="end_date" required class="w-full border-gray-300 rounded-lg text-sm" value="{{ date('Y-m-t') }}">
                                    </div>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Pilih Hari Operasional</label>
                                    <div class="grid grid-cols-2 gap-2">
                                        @foreach(['1'=>'Senin', '2'=>'Selasa', '3'=>'Rabu', '4'=>'Kamis', '5'=>'Jumat', '6'=>'Sabtu', '7'=>'Minggu'] as $val => $day)
                                            <label class="inline-flex items-center bg-gray-50 p-2 rounded border">
                                                <input type="checkbox" name="days[]" value="{{ $val }}" class="rounded border-gray-300 text-blue-600 shadow-sm" {{ $val <= 5 ? 'checked' : '' }}>
                                                <span class="ml-2 text-sm text-gray-600">{{ $day }}</span>
                                            </label>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                            <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 sm:ml-3 sm:w-auto sm:text-sm">
                                Proses Generate
                            </button>
                            <button type="button" @click="generateModalOpen = false" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                                Batal
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </div>
</x-app-layout>
