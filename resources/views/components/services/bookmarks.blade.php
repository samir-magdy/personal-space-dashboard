<div class="w-full h-full text-gray-900 dark:text-white overflow-hidden p-4">
    <div class="z-20 flex flex-col h-full gap-6">

        <!-- Add Bookmark Form -->
        <div class="rounded-xl p-4 shadow-md border border-gray-300 dark:border-white/20">
            <div class="flex items-center gap-3 flex-wrap">
                <input
                    type="text"
                    id="bookmarkUrl"
                    placeholder="URL (e.g., google.com)"
                    class="flex-1 bg-white dark:bg-transparent text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-white/60 text-lg p-3 focus:outline-none focus:ring-2 focus:ring-blue-500 rounded-lg border border-gray-300 dark:border-white/20">
                <input
                    type="text"
                    id="bookmarkName"
                    placeholder="Name"
                    class="flex-1 bg-white dark:bg-transparent text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-white/60 text-lg p-3 focus:outline-none focus:ring-2 focus:ring-blue-500 rounded-lg border border-gray-300 dark:border-white/20">
                <button
                    onclick="addBookmark()"
                    class="flex-1 lg:flex-none bg-blue-600 hover:bg-blue-500 dark:bg-blue-600 dark:hover:bg-blue-500 text-white font-semibold py-3 px-6 md:px-16 text-lg rounded-lg transition duration-200 shadow-md focus:outline-none focus:ring-4 focus:ring-blue-600/50">
                    Add
                </button>
            </div>
        </div>

        <!-- Bookmarks Grid -->
        <div class="flex-1 overflow-y-auto pb-40">
            <div id="bookmarksList" class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-5 gap-4">
                <!-- Bookmarks will be rendered here -->
            </div>
            <div id="emptyState" class="hidden flex items-center justify-center h-full text-center text-gray-800 dark:text-white/70">
                <div>
                    <p class="text-2xl mb-3">No bookmarks yet!</p>
                    <p class="text-lg">Add your favorite links above.</p>
                </div>
            </div>
        </div>

    </div>
</div>

<!-- Alert Modal -->
<div id="bookmarkAlertModal" class="calendar-modal">
    <div class="calendar-modal-content bg-white dark:bg-gray-800 border border-gray-300 dark:border-white/20 text-gray-900 dark:text-white shadow-2xl">
        <span id="closeBookmarkAlert" class="calendar-modal-close text-gray-400 hover:text-black dark:text-gray-400 dark:hover:text-white">&times;</span>
        <h3 class="calendar-modal-title text-gray-900 dark:text-white">Notice</h3>
        <p id="bookmarkAlertMessage" class="calendar-modal-message text-gray-700 dark:text-gray-300"></p>
        <div class="calendar-form-actions-right">
            <button id="bookmarkAlertOkBtn" class="calendar-btn calendar-btn-large calendar-btn-primary">OK</button>
        </div>
    </div>
</div>