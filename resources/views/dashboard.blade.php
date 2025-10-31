<x-app-layout>
    <!-- Mobile Menu Toggle Button -->
    <button data-drawer-target="default-sidebar" data-drawer-toggle="default-sidebar" aria-controls="default-sidebar" type="button" class="inline-flex items-center p-2 mt-2 ms-3 text-sm text-gray-700 rounded-lg sm:hidden hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-200 dark:text-gray-400 dark:hover:bg-gray-700 dark:focus:ring-gray-600">
        <span class="sr-only">Open sidebar</span>
        <svg class="w-6 h-6" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
            <path clip-rule="evenodd" fill-rule="evenodd" d="M2 4.75A.75.75 0 012.75 4h14.5a.75.75 0 010 1.5H2.75A.75.75 0 012 4.75zm0 10.5a.75.75 0 01.75-.75h7.5a.75.75 0 010 1.5h-7.5a.75.75 0 01-.75-.75zM2 10a.75.75 0 01.75-.75h14.5a.75.75 0 010 1.5H2.75A.75.75 0 012 10z"></path>
        </svg>
    </button>

    <!-- Sidebar -->
    <aside id="default-sidebar" class="fixed top-16 sm:top-17 left-0 z-50 sm:z-40 w-52 h-[calc(100vh-4rem)] sm:h-[calc(100vh-7vh)] transition-transform -translate-x-full sm:translate-x-0" aria-label="Sidebar">
        <div class="h-full px-3 py-4 overflow-y-auto bg-gray-200 dark:bg-gray-800">
            <ul class="space-y-2 font-medium">
                <li>
                    <a href="#" data-widget="home" class="widget-link flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group dark:bg-gray-700">
                        <i class="fas fa-house w-5 text-gray-700 transition duration-75 dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-white"></i>
                        <span class="ms-3">Home</span>
                    </a>
                </li>
                <li>
                    <a href="#" data-widget="news" class="widget-link flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group">
                        <i class="fas fa-newspaper w-5 text-gray-700 transition duration-75 dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-white"></i>
                        <span class="flex-1 ms-3 whitespace-nowrap">News</span>
                    </a>
                </li>
                <li>
                    <a href="#" data-widget="weather" class="widget-link flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group">
                        <i class="fas fa-cloud w-5 text-gray-700 transition duration-75 dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-white"></i>
                        <span class="flex-1 ms-3 whitespace-nowrap">Weather</span>
                    </a>
                </li>
                <li>
                    <a href="#" data-widget="currency" class="widget-link flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group">
                        <i class="ps-1 fas fa-dollar-sign w-5 text-gray-700 transition duration-75 dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-white"></i>
                        <span class="flex-1 ms-3 whitespace-nowrap">Currency</span>
                    </a>
                </li>
                <li>
                    <a href="#" data-widget="todo" class="widget-link flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group">
                        <i class="fas fa-list-check w-5 text-gray-700 transition duration-75 dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-white"></i>
                        <span class="flex-1 ms-3 whitespace-nowrap">To-Do List</span>
                    </a>
                </li>
                <li>
                    <a href="#" data-widget="calendar" class="widget-link flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group">
                        <i class="fas fa-calendar w-5 text-gray-700 transition duration-75 dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-white"></i>
                        <span class="flex-1 ms-3 whitespace-nowrap">Calendar</span>
                    </a>
                </li>
                <li>
                    <a href="#" data-widget="bookmarks" class="widget-link flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group">
                        <i class="fas fa-bookmark w-5 text-gray-700 transition duration-75 dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-white"></i>
                        <span class="flex-1 ms-3 whitespace-nowrap">Bookmarks</span>
                    </a>
                </li>

            </ul>
        </div>
    </aside>

    <!-- Main Content Area -->
    <div class="sm:ml-52 overflow-hidden">
        <div class="overflow-hidden">
            <!-- Individual Widget Views -->
            <!-- Home View -->
            <div id="home-widget" class="widget-container hidden">

                <div class="w-full h-[calc(100vh-4rem)] flex items-start justify-start lg:justify-center relative overflow-hidden ">
                    <div class="text-center px-4 sm:px-8 w-full">
                        <!-- Clock (centered on mobile, aligned with weather temp on desktop) -->
                        <div class="flex justify-center mt-20 md:mt-40 me-4">
                            <div class="text-4xl sm:text-3xl rounded-lg py-2 px-4">
                                <div class="flex items-center gap-6 justify-between">
                                    <!-- Date Display -->
                                    <div id="date-display" class="font-bold text-gray-900 dark:text-gray-200">
                                    </div>
                                    <!-- Clock Display -->
                                    <div id="clock-display" class="font-bold text-gray-900 dark:text-gray-200">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <h1 id="home-greeting" class="text-4xl sm:text-6xl md:text-8xl font-bold mb-4 sm:mb-6 text-gray-900 dark:text-white mt-8">

                        </h1>
                        <p class="text-4xl sm:text-6xl md:text-8xl text-gray-700 dark:text-gray-100 font-medium">
                            {{ explode(' ', Auth::user()->name)[0] }}
                        </p>
                        <div class="mt-4 sm:mt-12">
                            <p class="text-xl md:text-2xl text-gray-800 dark:text-gray-400 sm:px-20">
                                Welcome to your own personal space, explore the features available through the sidebar. You may customize your dashboard further in the profile settings.
                            </p>
                        </div>

                    </div>
                </div>
            </div>

            <div id="news-widget" class="widget-container hidden">
                <div class="w-full h-[calc(100vh-4rem)] flex items-center justify-center">
                    <x-services.news :news="$news" />
                </div>
            </div>

            <div id="weather-widget" class="widget-container hidden">
                <div class="w-full h-[calc(100vh-4rem)] flex items-center justify-center">
                    <x-services.weather :weather="$weather" />
                </div>
            </div>

            <div id="currency-widget" class="widget-container hidden">
                <div class="w-full h-[calc(100vh-4rem)] flex items-center justify-center">
                    <x-services.currency :currency="$currency" :availableCurrencies="$availableCurrencies" />
                </div>
            </div>

            <div id="todo-widget" class="widget-container hidden">
                <div class="w-full h-[calc(100vh-4rem)] flex items-center justify-center">
                    <x-services.todo />
                </div>
            </div>

            <div id="calendar-widget" class="widget-container hidden">
                <div class="w-full h-[calc(100vh-8rem)] sm:h-[calc(100vh-4rem)] flex items-start sm:items-center justify-center">
                    <x-services.calendar />
                </div>
            </div>

            <div id="bookmarks-widget" class="widget-container hidden">
                <div class="w-full h-[calc(100vh-4rem)] flex items-center justify-center">
                    <x-services.bookmarks />
                </div>
            </div>


        </div>
    </div>

</x-app-layout>