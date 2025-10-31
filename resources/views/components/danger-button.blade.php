<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center justify-center px-5 py-2.5 bg-red-600 hover:bg-red-700 disabled:bg-gray-800 disabled:cursor-not-allowed text-white rounded-xl transition-colors duration-200 font-medium text-base border-none focus:outline-none focus:ring-2 focus:ring-red-500']) }}>
    {{ $slot }}
</button>
