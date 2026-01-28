<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'SPPG Super App') }}</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        [x-cloak] { display: none !important; }
        .sidebar::-webkit-scrollbar { width: 5px; }
        .sidebar::-webkit-scrollbar-track { background: #f1f1f1; }
        .sidebar::-webkit-scrollbar-thumb { background: #d1d5db; border-radius: 10px; }
        .sidebar::-webkit-scrollbar-thumb:hover { background: #9ca3af; }
        /* Transisi Sidebar */
        .sidebar { transition: transform 0.3s ease-in-out; }
    </style>
</head>
<body class="bg-slate-50 text-slate-800 font-sans antialiased" x-data="{ sidebarOpen: false }" x-cloak>
    <div class="flex h-screen overflow-hidden">

        <div x-show="sidebarOpen"
             class="fixed inset-0 bg-black bg-opacity-50 z-20 md:hidden"
             @click="sidebarOpen = false"
             x-transition.opacity></div>

        @include('layouts.partials.sidebar')

        <div class="flex-1 flex flex-col overflow-hidden">

            @include('layouts.partials.header')

            <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-50 p-6">
                @if (session('success'))
                    <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                        <span class="block sm:inline">{{ session('success') }}</span>
                    </div>
                @endif
                @if (session('error'))
                    <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                        <span class="block sm:inline">{{ session('error') }}</span>
                    </div>
                @endif

                {{ $slot }}
            </main>

            @include('layouts.partials.footer')
        </div>
    </div>
<script src="https://kit.fontawesome.com/e686fa0059.js" crossorigin="anonymous"></script>
</body>
</html>
