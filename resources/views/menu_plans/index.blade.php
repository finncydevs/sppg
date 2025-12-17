<x-app-layout>
    <x-slot name="header">
        Kalender Menu
    </x-slot>

    <div x-data="{
            modalOpen: false,
            dateStr: '',
            formattedDate: ''
         }">

        <div class="bg-white p-6 rounded-lg shadow-md">

            <div class="flex flex-col md:flex-row justify-between items-center mb-6 gap-4">

                <form action="{{ route('menu-plans.index') }}" method="GET" class="flex items-center gap-2">
                    <select name="month" onchange="this.form.submit()" class="bg-gray-50 border border-gray-300 text-sm rounded-lg p-2.5">
                        @for($m=1; $m<=12; $m++)
                            <option value="{{ $m }}" {{ $m == $month ? 'selected' : '' }}>
                                {{ date('F', mktime(0, 0, 0, $m, 10)) }}
                            </option>
                        @endfor
                    </select>
                    <select name="year" onchange="this.form.submit()" class="bg-gray-50 border border-gray-300 text-sm rounded-lg p-2.5">
                        @for($y=date('Y')-1; $y<=date('Y')+2; $y++)
                            <option value="{{ $y }}" {{ $y == $year ? 'selected' : '' }}>{{ $y }}</option>
                        @endfor
                    </select>
                </form>

                <form action="{{ route('menu-plans.publish') }}" method="POST" onsubmit="return confirm('Terbitkan semua draft menu bulan ini menjadi WO resmi?');">
                    @csrf
                    <input type="hidden" name="month" value="{{ $month }}">
                    <input type="hidden" name="year" value="{{ $year }}">
                    <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 font-semibold text-sm flex items-center shadow">
                        <i class="fas fa-check-double mr-2"></i> Terbitkan ke Produksi (WO)
                    </button>
                </form>
            </div>

            <div class="border border-gray-200 rounded-lg overflow-hidden">
                <div class="grid grid-cols-7 bg-gray-100 border-b border-gray-200">
                    @foreach(['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'] as $day)
                        <div class="py-3 text-center text-sm font-semibold text-gray-600">{{ $day }}</div>
                    @endforeach
                </div>

                <div class="grid grid-cols-7 auto-rows-fr bg-gray-200 gap-px border-l border-gray-200">
                    @php
                        $daysInMonth = cal_days_in_month(CAL_GREGORIAN, $month, $year);
                        $firstDayOfWeek = date('w', strtotime("$year-$month-01")); // 0 (Minggu) - 6 (Sabtu)
                    @endphp

                    @for($i = 0; $i < $firstDayOfWeek; $i++)
                        <div class="bg-gray-50 min-h-[120px]"></div>
                    @endfor

                    @for($day = 1; $day <= $daysInMonth; $day++)
                        @php
                            $currentDate = "$year-" . str_pad($month, 2, '0', STR_PAD_LEFT) . "-" . str_pad($day, 2, '0', STR_PAD_LEFT);
                            $dayPlans = $plans[$currentDate] ?? collect();
                            $isToday = $currentDate == date('Y-m-d');
                        @endphp

                        <div class="bg-white min-h-[120px] p-2 relative hover:bg-blue-50 transition group">
                            <div class="flex justify-between items-start">
                                <span class="text-sm font-semibold {{ $isToday ? 'bg-blue-600 text-white w-6 h-6 rounded-full flex items-center justify-center' : 'text-gray-700' }}">
                                    {{ $day }}
                                </span>

                                <button @click="modalOpen = true; dateStr = '{{ $currentDate }}'; formattedDate = '{{ \Carbon\Carbon::parse($currentDate)->translatedFormat('l, d F Y') }}'"
                                        class="text-gray-400 hover:text-blue-600 opacity-0 group-hover:opacity-100 transition">
                                    <i class="fas fa-plus-circle"></i>
                                </button>
                            </div>

                            <div class="mt-2 space-y-1">
                                @foreach($dayPlans as $plan)
                                    <div class="text-xs p-1.5 rounded border flex justify-between items-center group/item
                                        {{ $plan->is_published ? 'bg-green-50 border-green-200 text-green-800' : 'bg-yellow-50 border-yellow-200 text-yellow-800' }}">

                                        <div class="truncate">
                                            <span class="font-bold block">{{ $plan->meal_time }}</span>
                                            {{ $plan->recipe->name }}
                                        </div>

                                        @if(!$plan->is_published)
                                            <form action="{{ route('menu-plans.destroy', $plan->id) }}" method="POST" class="hidden group-hover/item:block">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-500 hover:text-red-700" title="Hapus">
                                                    <i class="fas fa-times"></i>
                                                </button>
                                            </form>
                                        @else
                                            <i class="fas fa-lock text-green-400 text-[10px]" title="Sudah Terbit"></i>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endfor
                </div>
            </div>
        </div>

        <div x-show="modalOpen" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
            <div class="flex items-center justify-center min-h-screen px-4 text-center sm:block sm:p-0">
                <div class="fixed inset-0 transition-opacity" @click="modalOpen = false">
                    <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
                </div>

                <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                    <form action="{{ route('menu-plans.store') }}" method="POST">
                        @csrf
                        <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">
                                Jadwalkan Menu: <span x-text="formattedDate" class="text-blue-600"></span>
                            </h3>

                            <input type="hidden" name="planned_date" x-model="dateStr">

                            <div class="space-y-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Waktu Makan</label>
                                    <select name="meal_time" class="w-full border-gray-300 rounded-md shadow-sm text-sm">
                                        <option value="Siang">Makan Siang</option>
                                        <option value="Pagi">Sarapan</option>
                                        <option value="Sore">Makan Sore</option>
                                        <option value="Snack">Snack / Kudapan</option>
                                    </select>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Pilih Resep</label>
                                    <select name="recipe_id" required class="w-full border-gray-300 rounded-md shadow-sm text-sm">
                                        <option value="">-- Pilih Menu --</option>
                                        @foreach($recipes as $recipe)
                                            <option value="{{ $recipe->id }}">{{ $recipe->name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Rencana Porsi</label>
                                    <input type="number" name="portion_target" value="500" required min="1" class="w-full border-gray-300 rounded-md shadow-sm text-sm">
                                    <p class="text-xs text-gray-500 mt-1">Estimasi jumlah yang akan dimasak.</p>
                                </div>
                            </div>
                        </div>
                        <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                            <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 sm:ml-3 sm:w-auto sm:text-sm">
                                Simpan
                            </button>
                            <button type="button" @click="modalOpen = false" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                                Batal
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </div>
</x-app-layout>
