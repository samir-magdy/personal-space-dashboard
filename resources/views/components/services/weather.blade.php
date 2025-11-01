<div class="z-20 flex flex-col justify-start items-center lg:justify-center min-h-full p-4 pt-8 pt-0 sm:p-8">

    <div class="temp text-6xl sm:text-8xl font-bold">
        <span class="mx-auto text-gray-900 dark:text-white animate-fadeInScaleUp delay-100" id="temperature">
            {{ $weather ? round($weather['temp']) : 'City not found.' }}°C
        </span>
    </div>
    <div class="weather-icon-main mx-auto">
        @if($weather && isset($weather['icon']))
        <img src="https://openweathermap.org/img/wn/{{ $weather['icon'] }}@4x.png" alt="Weather icon" class="animate-fadeInScaleUp delay-150">
        @endif
    </div>

    <div class="location text-2xl sm:text-4xl opacity-90 mb-4 sm:mb-8 tracking-wide animate-fadeInUp delay-200 text-center text-gray-900 dark:text-white" id="location">
        {{ $weather['city'] ?? 'Unknown' }}, {{ $weather['country'] ?? '' }}
        @if($weather && isset($weather['description']))
        <div class="text-lg sm:text-2xl opacity-75 capitalize mt-2">{{ $weather['description'] }}</div>
        @endif
    </div>
    <!-- City Search Form -->
    <form id="weather-search-form" class="w-64 max-w-2xl mt-4">
        <div class="flex flex-col gap-3">
            <div class="flex flex-col gap-3 sm:gap-4">
                <!-- Custom Autocomplete Container -->
                <div class="relative flex-1 w-full">
                    <input
                        type="text"
                        id="city-input"
                        name="city"
                        placeholder="Search city..."
                        autocomplete="off"
                        class="w-full px-6 sm:px-4 py-3 sm:py-4 text-lg sm:text-xl rounded-xl bg-white dark:bg-transparent border border-gray-300 dark:border-white/20 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-white/60 focus:outline-none focus:border-blue-500 dark:focus:border-blue-400 transition-colors">
                    <button
                        type="button"
                        id="dropdown-toggle"
                        class="absolute right-3 top-1/2 -translate-y-1/2 p-2 text-gray-600 dark:text-white/60 hover:text-blue-600 dark:hover:text-blue-400 transition-colors">
                        <svg class="w-5 h-5 transition-transform duration-200" id="arrow-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>
                    <!-- Custom Dropdown -->
                    <div id="city-dropdown" class="absolute z-50 w-full bottom-full mb-2 bg-white dark:bg-gray-800 dark:text-white border border-gray-300 dark:border-white/20 rounded-xl shadow-lg max-h-60 overflow-y-auto hidden"> <!-- Options will be inserted here -->
                    </div>
                </div>

                <button
                    type="submit"
                    id="weather-search-btn"
                    class="w-full sm:w-auto px-12 sm:px-10 py-3 sm:py-4 text-lg sm:text-xl bg-blue-600 hover:bg-blue-800 disabled:bg-gray-800 disabled:cursor-not-allowed text-white rounded-xl transition-colors duration-200 font-medium">
                    Search
                </button>
            </div>
            <div id="weather-error" class="text-base sm:text-xl text-orange-800 dark:text-orange-800 hidden text-center"></div>
        </div>
    </form>
</div>