<x-guest-layout>
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <div class="text-center mb-8">
        <h1 class="text-3xl font-bold text-gray-800">SPPG Super App</h1>
        <p class="mt-2 text-gray-500">Silakan login untuk melanjutkan</p>
    </div>

    <form method="POST" action="{{ route('login') }}" class="space-y-6">
        @csrf

        <div>
            <label for="email" class="sr-only">Email</label>
            <input id="email"
                   type="email"
                   name="email"
                   value="{{ old('email') }}"
                   required
                   autofocus
                   autocomplete="username"
                   class="relative block w-full px-3 py-3 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                   placeholder="Email Address">
            <x-input-error :messages="$errors->get('email')" class="mt-2 text-left" />
        </div>

        <div>
            <label for="password" class="sr-only">Password</label>
            <input id="password"
                   type="password"
                   name="password"
                   required
                   autocomplete="current-password"
                   class="relative block w-full px-3 py-3 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                   placeholder="Password">
            <x-input-error :messages="$errors->get('password')" class="mt-2 text-left" />
        </div>

        <div class="flex items-center justify-between">
            <label for="remember_me" class="inline-flex items-center">
                <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-blue-600 shadow-sm focus:ring-blue-500" name="remember">
                <span class="ms-2 text-sm text-gray-600">{{ __('Ingat Saya') }}</span>
            </label>

            @if (Route::has('password.request'))
                <a class="text-sm text-blue-600 hover:text-blue-500 font-medium" href="{{ route('password.request') }}">
                    {{ __('Lupa Password?') }}
                </a>
            @endif
        </div>

        <div>
            <button type="submit" class="group relative flex justify-center w-full px-4 py-3 text-sm font-medium text-white bg-blue-600 border border-transparent rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-150 ease-in-out">
                <span class="absolute left-0 inset-y-0 flex items-center pl-3">
                    <i class="fa-solid fa-lock h-5 w-5 text-blue-500 group-hover:text-blue-400"></i>
                </span>
                Masuk
            </button>
        </div>
    </form>
</x-guest-layout>
