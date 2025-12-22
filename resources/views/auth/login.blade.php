<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'SPPG Super App') }}</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        /* Custom styles for enhanced visual effects */
        input:focus {
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        }

        button:active {
            transform: translateY(0);
        }

        /* NOTE: Applying transitions to '*' can affect performance.
           It is safer to apply to specific elements, but I kept it
           to preserve your desired effect.
        */
        .smooth-transition * {
            transition-property: all;
            transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
            transition-duration: 200ms;
        }

        /* Custom checkbox styling */
        input[type="checkbox"]:checked + div {
            background-color: #2563eb;
        }

        /* Input hover effects */
        input:hover {
            border-color: #93c5fd;
            background-color: white;
        }

        /* Gradient border animation for form container */
        .gradient-border-container {
            position: relative;
        }

        .gradient-border-container::before {
            content: '';
            position: absolute;
            top: -1px;
            left: -1px;
            right: -1px;
            bottom: -1px;
            background: linear-gradient(135deg, #3b82f6, #8b5cf6);
            border-radius: 1rem;
            z-index: -1;
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .gradient-border-container:hover::before {
            opacity: 0.2;
        }
    </style>
</head>
<body class="font-sans text-gray-900 antialiased smooth-transition">

    <div class="min-h-screen flex bg-white">

        <div class="hidden lg:flex lg:w-1/2 relative overflow-hidden bg-blue-900">
            <div class="absolute inset-0 bg-gradient-to-br from-blue-600 to-blue-800">
                <img src="{{ asset('storage/mbg.webp') }}"
                     alt="Background"
                     class="w-full h-full object-cover opacity-30 mix-blend-overlay">
            </div>

            <div class="relative z-10 flex flex-col justify-center px-16 text-white h-full">
                <div class="max-w-md">
                    <div class="mb-8">
                        <div class="w-20 h-20 bg-white/10 backdrop-blur-sm rounded-2xl flex items-center justify-center mb-6 shadow-lg border border-white/20">
                            <img src="{{ asset('storage/bgnlogo.png') }}"
                                 class="w-16 h-16 object-contain drop-shadow-md"
                                 alt="Logo">
                        </div>
                        <h2 class="text-4xl font-bold mb-4 tracking-tight">SPPG Super App</h2>
                        <p class="text-lg text-blue-100 leading-relaxed">Sistem terintegrasi untuk manajemen pembelajaran yang lebih efisien dan modern.</p>
                    </div>

                    <div class="space-y-6">
                        <div class="flex items-center group">
                            <div class="w-12 h-12 rounded-2xl bg-white/10 flex items-center justify-center mr-4 group-hover:bg-white/20 transition-colors border border-white/10">
                                <i class="fa-solid fa-shield-check text-xl text-blue-200"></i>
                            </div>
                            <div>
                                <h4 class="font-semibold text-lg">Keamanan Terjamin</h4>
                                <p class="text-sm text-blue-200">Data Anda dilindungi enkripsi AES-256</p>
                            </div>
                        </div>

                        <div class="flex items-center group">
                            <div class="w-12 h-12 rounded-2xl bg-white/10 flex items-center justify-center mr-4 group-hover:bg-white/20 transition-colors border border-white/10">
                                <i class="fa-solid fa-bolt text-xl text-yellow-200"></i>
                            </div>
                            <div>
                                <h4 class="font-semibold text-lg">Akses Cepat</h4>
                                <p class="text-sm text-blue-200">Server berkecepatan tinggi & responsif</p>
                            </div>
                        </div>

                        <div class="flex items-center group">
                            <div class="w-12 h-12 rounded-2xl bg-white/10 flex items-center justify-center mr-4 group-hover:bg-white/20 transition-colors border border-white/10">
                                <i class="fa-solid fa-chart-line text-xl text-green-200"></i>
                            </div>
                            <div>
                                <h4 class="font-semibold text-lg">Analisis Real-time</h4>
                                <p class="text-sm text-blue-200">Dashboard interaktif untuk monitoring</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="absolute top-0 left-0 w-64 h-64 bg-blue-500 rounded-full mix-blend-multiply filter blur-3xl opacity-20 -translate-x-1/2 -translate-y-1/2 animate-blob"></div>
            <div class="absolute bottom-0 right-0 w-64 h-64 bg-purple-500 rounded-full mix-blend-multiply filter blur-3xl opacity-20 translate-x-1/2 translate-y-1/2 animate-blob animation-delay-2000"></div>
        </div>

        <div class="flex-1 flex flex-col justify-center py-12 px-4 sm:px-6 lg:px-8 xl:px-20 bg-gray-50">
            <div class="mx-auto w-full max-w-sm lg:max-w-md">

                <div class="lg:hidden text-center mb-8">
                    <div class="mx-auto w-16 h-16 bg-gradient-to-br from-blue-600 to-blue-800 rounded-2xl flex items-center justify-center shadow-lg mb-4">
                        <img src="{{ asset('storage/bgnlogo.png') }}"
                             class="w-10 h-10 object-contain"
                             alt="Logo">
                    </div>
                    <h1 class="text-2xl font-bold text-gray-900">SPPG Super App</h1>
                    <p class="text-gray-500 mt-2 text-sm">Silakan login untuk melanjutkan</p>
                </div>

                <div class="hidden lg:block mb-10">
                    <h1 class="text-3xl font-bold text-gray-900 mb-2">Selamat Datang Kembali</h1>
                    <p class="text-gray-500">Masuk ke akun Anda untuk mengakses dashboard</p>
                </div>

                <x-auth-session-status class="mb-4" :status="session('status')" />

                <div class="bg-white py-8 px-6 shadow-xl rounded-2xl sm:px-10 border border-gray-100 gradient-border-container relative z-0">
                    <form method="POST" action="{{ route('login') }}" class="space-y-6">
                        @csrf

                        <div class="space-y-2">
                            <label for="email" class="block text-sm font-medium text-gray-700 ml-1">Email Address</label>
                            <div class="relative group">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                    <i class="fa-solid fa-envelope text-gray-400 group-focus-within:text-blue-500 transition-colors"></i>
                                </div>
                                <input id="email"
                                       type="email"
                                       name="email"
                                       value="{{ old('email') }}"
                                       required
                                       autofocus
                                       autocomplete="username"
                                       class="pl-11 block w-full px-4 py-3.5 border border-gray-200 rounded-xl text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition duration-200 bg-gray-50 hover:bg-white"
                                       placeholder="nama@email.com">
                            </div>
                            <x-input-error :messages="$errors->get('email')" class="mt-1 text-left" />
                        </div>

                        <div class="space-y-2">
                            <label for="password" class="block text-sm font-medium text-gray-700 ml-1">Password</label>
                            <div class="relative group">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                    <i class="fa-solid fa-key text-gray-400 group-focus-within:text-blue-500 transition-colors"></i>
                                </div>
                                <input id="password"
                                       type="password"
                                       name="password"
                                       required
                                       autocomplete="current-password"
                                       class="pl-11 block w-full px-4 py-3.5 border border-gray-200 rounded-xl text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition duration-200 bg-gray-50 hover:bg-white"
                                       placeholder="••••••••">
                            </div>
                            <x-input-error :messages="$errors->get('password')" class="mt-1 text-left" />
                        </div>

                        <div class="flex items-center justify-between pt-2">
                            <label for="remember_me" class="inline-flex items-center cursor-pointer group">
                                <div class="relative">
                                    <input id="remember_me" type="checkbox" class="sr-only peer" name="remember">
                                    <div class="w-9 h-5 bg-gray-200 peer-focus:outline-none peer-focus:ring-2 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-blue-600"></div>
                                </div>
                                <span class="ms-3 text-sm text-gray-600 group-hover:text-gray-900 transition-colors">{{ __('Ingat Saya') }}</span>
                            </label>

                            @if (Route::has('password.request'))
                                <a class="text-sm text-blue-600 hover:text-blue-700 font-semibold hover:underline" href="{{ route('password.request') }}">
                                    {{ __('Lupa Password?') }}
                                </a>
                            @endif
                        </div>

                        <div class="pt-2">
                            <button type="submit" class="group relative w-full flex justify-center py-3.5 px-4 border border-transparent text-sm font-semibold rounded-xl text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 shadow-lg shadow-blue-500/30 transition-all duration-200 hover:-translate-y-0.5">
                                <span class="absolute left-0 inset-y-0 flex items-center pl-3">
                                    <i class="fa-solid fa-right-to-bracket text-blue-200 group-hover:text-white transition-colors"></i>
                                </span>
                                Masuk ke Dashboard
                            </button>
                        </div>
                    </form>

                    <div class="mt-6">
                        <div class="relative">
                            <div class="absolute inset-0 flex items-center">
                                <div class="w-full border-t border-gray-200"></div>
                            </div>
                            <div class="relative flex justify-center text-sm">
                                <span class="px-2 bg-white text-gray-500">Belum punya akun?</span>
                            </div>
                        </div>
                        <div class="mt-6 flex justify-center">
                            @if (Route::has('register'))
                                <a href="{{ route('register') }}" class="font-medium text-blue-600 hover:text-blue-500 hover:underline transition duration-200">
                                    Daftar akun baru
                                </a>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="mt-8 text-center">
                    <p class="text-xs text-gray-400">
                        © {{ date('Y') }} SPPG Super App. Hak Cipta Dilindungi.
                    </p>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
