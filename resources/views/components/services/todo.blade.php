<div class="w-full h-full text-gray-900 dark:text-white p-2 md:p-4 flex flex-col">

    <div class="z-20 flex flex-col flex-1 gap-3 sm:gap-6 justify-start min-h-0">



        <div class="rounded-xl p-2 sm:p-4 border border-gray-300 dark:border-white/20 shadow-md">
            <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-2 sm:gap-3">
                <input
                    type="text"
                    id="todoInput"
                    placeholder="Create a new task..."
                    class="flex-1 bg-white dark:bg-transparent text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-white/60 text-lg p-2 sm:p-3 focus:outline-none focus:ring-2 focus:ring-blue-500 rounded-lg border border-gray-300 dark:border-white/20"
                    onkeydown="if(event.key === 'Enter') addTodo()">
                <button
                    onclick="addTodo()"
                    class="bg-blue-600 hover:bg-blue-800 dark:bg-blue-600 dark:hover:bg-blue-800 text-white font-semibold py-2 sm:py-3 px-4 sm:px-6 lg:px-24 text-lg rounded-lg transition duration-200 shadow-md focus:outline-none focus:ring-4 focus:ring-blue-600/50 w-full sm:w-auto">
                    Add
                </button>
            </div>
        </div>

        <div class="flex-1 rounded-xl overflow-hidden border border-gray-300 dark:border-white/20 flex flex-col min-h-0 max-h-[55vh] sm:max-h-full">

            <ul id="todoList" class="flex-1 min-h-0 p-2 sm:p-4 space-y-2 sm:space-y-3 overflow-y-auto">
            </ul>

            <div id="emptyState" class="hidden pb-32 flex-1 flex items-start justify-center text-center text-gray-800 dark:text-white/70">
                <div>
                    <p class="text-3xl mb-1 sm:mb-3">You're All Caught Up!</p>
                    <p class="text-xl">Start by adding a new task above.</p>
                </div>
            </div>

            <div id="todoFooter" class="flex-shrink-0 flex justify-between gap-1 sm:gap-2 text-base text-gray-800 dark:text-white/70 p-2 sm:p-4 border-t border-gray-300 dark:border-white/20 hidden items-center">

                <span id="itemsLeft" class="text-md whitespace-nowrap">0 left</span>

                <div class="gap-1 border border-gray-300 dark:border-white/20 rounded-lg p-1 sm:p-1.5">
                    <button id="filter-all" onclick="setFilter('all')" class="filter-btn text-md font-semibold py-1 px-1.5 sm:px-4 rounded-md transition-all duration-150 text-gray-800 dark:text-white/70 hover:bg-gray-200 dark:hover:bg-white/20">All</button>
                    <button id="filter-active" onclick="setFilter('active')" class="filter-btn text-md font-semibold py-1 px-1.5 sm:px-4 rounded-md transition-all duration-150 text-gray-800 dark:text-white/70 hover:bg-gray-200 dark:hover:bg-white/20">Active</button>
                    <button id="filter-completed" onclick="setFilter('completed')" class="filter-btn text-md font-semibold py-1 px-1.5 sm:px-4 rounded-md transition-all duration-150 text-gray-800 dark:text-white/70 hover:bg-gray-200 dark:hover:bg-white/20">Done</button>
                </div>

                <button
                    onclick="clearCompleted()"
                    class="text-md font-semibold py-2 md:py-3 sm:py-1.5 px-2 sm:px-4 rounded-md bg-red-600 hover:bg-red-700 text-white transition-all duration-150 whitespace-nowrap">
                    Clear Marked
                </button>
            </div>
        </div>

    </div>

</div>