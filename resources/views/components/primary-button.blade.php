<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center px-5 py-2.5 bg-gradient-to-r from-blue-600 to-blue-700 border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:from-blue-700 hover:to-blue-800 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 shadow-lg shadow-blue-500/30 transition ease-in-out duration-150 transform hover:-translate-y-0.5']) }}>
    {{ $slot }}
</button>
