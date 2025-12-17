<header class="flex justify-between items-center p-4 bg-white border-b h-16 shadow-sm">
    <div class="flex items-center">
        <button @click="sidebarOpen = !sidebarOpen" class="text-gray-500 focus:outline-none md:hidden mr-4">
            <i class="fa-solid fa-bars h-6 w-6"></i>
        </button>

        <h2 class="text-xl font-semibold text-gray-800">
            @if (isset($header))
                {{ $header }}
            @else
                Dashboard
            @endif
        </h2>
    </div>

    <div class="flex items-center gap-4">
        <div class="text-right hidden sm:block">
            <p class="text-sm font-semibold text-gray-800">{{ Auth::user()->name }}</p>
            <p class="text-xs text-gray-500">
                {{ Auth::user()->roles->pluck('name')->first() ?? 'User' }}
            </p>
        </div>

        <div x-data="{ userOpen: false }" class="relative">
            <button @click="userOpen = !userOpen" class="flex items-center focus:outline-none">
                <div class="h-8 w-8 rounded-full bg-blue-100 flex items-center justify-center text-blue-600 font-bold border border-blue-200">
                    {{ substr(Auth::user()->name, 0, 1) }}
                </div>
            </button>

            <div x-show="userOpen"
                 @click.outside="userOpen = false"
                 class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg py-1 border border-gray-100 z-50"
                 x-transition
                 style="display: none;">

                <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Profil Saya</a>

                <div class="border-t border-gray-100 my-1"></div>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <a href="{{ route('logout') }}"
                       onclick="event.preventDefault(); this.closest('form').submit();"
                       class="block px-4 py-2 text-sm text-red-600 hover:bg-red-50 hover:text-red-700 font-medium">
                        Keluar
                    </a>
                </form>
            </div>
        </div>
    </div>
</header>
