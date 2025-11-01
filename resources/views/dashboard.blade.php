<x-app-layout>

    <!-- Sidebar -->
    <aside id="default-sidebar" class="dark:text-white fixed top-16 sm:top-17 left-0 z-50 sm:z-40 w-52 h-[calc(100vh-8rem)] sm:h-[calc(100vh-7vh)] transition-transform -translate-x-full sm:translate-x-0" aria-label="Sidebar">
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

    <!-- Bottom Mobile Navigation -->
    <nav class="fixed bottom-0 left-0 right-0 z-50 bg-gray-300 dark:bg-gray-700 border-t border-gray-200 dark:border-gray-600 sm:hidden shadow-lg">
        <div class="grid grid-cols-4 h-16">
            <a href="#" data-widget="home" class="widget-link-mobile flex flex-col items-center justify-center text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-600 transition-all duration-200 active:scale-95">
                <i class="fas fa-house text-xl mb-0.5"></i>
                <span class="text-xs font-medium">Home</span>
            </a>
            <a href="#" data-widget="news" class="widget-link-mobile flex flex-col items-center justify-center text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-600 transition-all duration-200 active:scale-95">
                <i class="fas fa-newspaper text-xl mb-0.5"></i>
                <span class="text-xs font-medium">News</span>
            </a>
            <a href="#" data-widget="todo" class="widget-link-mobile flex flex-col items-center justify-center text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-600 transition-all duration-200 active:scale-95">
                <i class="fas fa-list-check text-xl mb-0.5"></i>
                <span class="text-xs font-medium">Tasks</span>
            </a>
            <button data-drawer-toggle="default-sidebar" type="button" class="flex flex-col items-center justify-center text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-600 transition-all duration-200 active:scale-95">
                <i class="fas fa-bars text-xl mb-0.5"></i>
                <span class="text-xs font-medium">More</span>
            </button>
        </div>
    </nav>

    <!-- Main Content Area -->
    <div class="sm:ml-52 overflow-hidden">
        <div class="overflow-hidden">
            <!-- Individual Widget Views -->
            <!-- Home View -->
            <div id="home-widget" class="widget-container hidden">

                <div class="w-full h-[calc(100vh-8rem)] sm:h-[calc(100vh-4rem)] flex items-start justify-start lg:justify-center relative overflow-hidden ">
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
                <div class="w-full h-[calc(100vh-8rem)] sm:h-[calc(100vh-4rem)] flex items-center justify-center">
                    <x-services.news :news="$news" />
                </div>
            </div>

            <div id="weather-widget" class="widget-container hidden">
                <div class="w-full h-[calc(100vh-8rem)] sm:h-[calc(100vh-4rem)] flex items-center justify-center">
                    <x-services.weather :weather="$weather" />
                </div>
            </div>

            <div id="currency-widget" class="widget-container hidden">
                <div class="w-full h-[calc(100vh-8rem)] sm:h-[calc(100vh-4rem)] flex items-center justify-center">
                    <x-services.currency :currency="$currency" :availableCurrencies="$availableCurrencies" />
                </div>
            </div>

            <div id="todo-widget" class="widget-container hidden">
                <div class="w-full h-[calc(100vh-8rem)] sm:h-[calc(100vh-4rem)] flex items-center justify-center">
                    <x-services.todo />
                </div>
            </div>

            <div id="calendar-widget" class="widget-container hidden">
                <div class="w-full h-[calc(100vh-8rem)] sm:h-[calc(100vh-4rem)] flex items-start sm:items-center justify-center">
                    <x-services.calendar />
                </div>
            </div>

            <div id="bookmarks-widget" class="widget-container hidden">
                <div class="w-full h-[calc(100vh-8rem)] sm:h-[calc(100vh-4rem)] flex items-center justify-center">
                    <x-services.bookmarks />
                </div>
            </div>


        </div>
    </div>

</x-app-layout>