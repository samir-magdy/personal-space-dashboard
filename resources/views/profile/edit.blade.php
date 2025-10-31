<x-app-layout>


    <div class="pt-5 sm:pb-8 pb-40">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
            <div class="flex justify-between items-center">
                <a href="{{ route('dashboard')}}" class="inline-flex text-gray-200 bg-gray-700 items-center px-3 sm:py-3 py-4 border border-transparent text-sm leading-4 font-medium rounded-md  dark:text-gray-400 dark:bg-gray-800 hover:text-white dark:hover:text-gray-300 focus:outline-none transition ease-in-out duration-150 me-2">
                    <i class="fa-solid fa-arrow-left me-2"></i> Dashboard
                </a>
                <!-- darkmode toggle -->
                <div class="flex gap-3">

                    <button
                        x-cloak
                        @click="darkMode = !darkMode"
                        class="inline-flex items-center px-3 py-2 border border-transparent text-sm text-gray-200 leading-4 font-medium rounded-md dark:text-gray-400 text-gray-200 bg-gray-700 dark:bg-gray-800 hover:text-white dark:hover:text-gray-300 focus:outline-none transition ease-in-out duration-150">
                        <span x-text="darkMode ? 'Light Theme' : 'Dark Theme'"></span>

                        <i x-show="darkMode" class="fa-solid fa-sun ms-2"></i>
                        <svg x-show="!darkMode" class="w-4 h-4 ms-2" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                            <path d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z" />
                        </svg>
                    </button>

                    <!-- clock toggle -->
                    <button
                        id="clock-toggle-mode"
                        class="inline-flex items-center px-3 py-2 border border-transparent text-gray-200 bg-gray-700 text-sm leading-4 font-medium rounded-md text-gray-200 dark:text-gray-400 dark:bg-gray-800 hover:text-white dark:hover:text-gray-300 focus:outline-none transition ease-in-out duration-150">
                        <span id="clock-mode-text">24 Hour Format</span>
                        <svg class="w-4 h-4 ms-2" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd" />
                        </svg>
                    </button>
                </div>
            </div>

            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <div class="max-w-xl">
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>

            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <div class="max-w-xl">
                    @include('profile.partials.update-password-form')
                </div>
            </div>

            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <div class="max-w-xl">
                    @include('profile.partials.delete-user-form')
                </div>
            </div>
        </div>
    </div>
</x-app-layout>