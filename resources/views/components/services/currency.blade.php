<div class="w-full h-full text-gray-900 dark:text-white p-4 pt-0 sm:p-8">

    <div class="z-20 flex flex-col items-center justify-start lg:justify-center min-h-full mx-auto">

        <div class="temp text-6xl sm:text-8xl font-bold">
            <span id="exchange-rate" class="text-gray-900 dark:text-white animate-fadeInScaleUp delay-100">
                {{ $currency && $currency['rate'] ? number_format($currency['rate'], 2) : '--' }}
            </span>
        </div>
        <div class="weather-icon-main my-8 sm:my-12 text-gray-900 dark:text-white">
            <i class="fas fa-coins text-[100px] sm:text-[200px]"></i>
        </div>

        <div id="currency-pair" class="location text-2xl sm:text-4xl opacity-90 mb-4 sm:mb-8 tracking-wide animate-fadeInUp delay-200 text-center">
            {{ $currency['base_currency'] ?? 'USD' }} to {{ $currency['target_currency'] ?? 'EGP' }}
            @if($currency)
            <div class="text-2xl opacity-75 capitalize mt-2">Exchange Rate</div>
            @endif
        </div>

        <!-- Error Message -->
        <div id="currency-error" class="hidden text-red-600 dark:text-red-300 text-xl text-center px-6 py-3 rounded-xl border border-red-400/30">
            Error message
        </div>

        <!-- Currency Search Form -->
        <form id="currency-form" class="w-full max-w-4xl">
            <div class="flex flex-col gap-3">
                <div class="flex flex-col sm:flex-row gap-3 items-center justify-center w-full">
                    <input id="currency_amount" name="currency_amount" type="text" placeholder="Amount to convert.." class="w-full sm:w-72 mx-auto px-4 sm:px-6 py-3 sm:py-4 text-base sm:text-lg rounded-xl border border-gray-300 dark:border-white/20 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-white/60 focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white dark:bg-transparent">

                    <select
                        id="base-currency"
                        name="base_currency"
                        class="w-full sm:w-72 truncate px-4 sm:px-6 py-3 sm:py-4 text-base sm:text-lg rounded-xl border border-gray-300 dark:border-white/20 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500 cursor-pointer bg-white dark:bg-transparent">
                        @if($availableCurrencies && count($availableCurrencies) > 0)
                        @foreach($availableCurrencies as $code => $name)
                        <option value="{{ $code }}" class=" bg-white dark:bg-gray-800 text-gray-900 dark:text-white" {{ request('base_currency', 'USD') == $code ? 'selected' : '' }}>
                            {{ Str::limit($name, 20) }}
                        </option>
                        @endforeach
                        @else
                        <option value="USD" class="bg-white dark:bg-gray-800 text-gray-900 dark:text-white">USD - United States Dollar</option>
                        @endif
                    </select>
                    <svg class="w-5 h-5 text-gray-900 dark:text-white flex-shrink-0 rotate-90 sm:rotate-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                    </svg>
                    <select
                        id="target-currency"
                        name="target_currency"
                        class="w-full sm:w-72 truncate px-4 sm:px-6 py-3 sm:py-4 text-base sm:text-lg rounded-xl border border-gray-300 dark:border-white/20 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500 cursor-pointer bg-white dark:bg-transparent">
                        @if($availableCurrencies && count($availableCurrencies) > 0)
                        @foreach($availableCurrencies as $code => $name)
                        <option value="{{ $code }}" class="bg-white dark:bg-gray-800 text-gray-900 dark:text-white" {{ request('target_currency', 'EGP') == $code ? 'selected' : '' }}>
                            {{ Str::limit($name, 20) }}
                        </option>
                        @endforeach
                        @else
                        <option value="EGP" class="bg-white dark:bg-gray-800 text-gray-900 dark:text-white">EGP - Egyptian Pound</option>
                        @endif
                    </select>
                </div>
                <button
                    id="currency-convert-btn"
                    type="submit"
                    class="w-full mx-auto px-12 sm:px-24 py-3 sm:py-4 text-lg sm:text-xl bg-blue-600 hover:bg-blue-800 disabled:bg-gray-800 disabled:cursor-not-allowed text-white rounded-xl transition-colors duration-200 font-medium mt-1 ">
                    Convert
                </button>
            </div>
        </form>
    </div>
</div>