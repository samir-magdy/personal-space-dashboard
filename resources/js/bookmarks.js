// --- API Helper Functions ---

/**
 * Get CSRF token from meta tag
 */
function getCsrfToken() {
    return document.querySelector('meta[name="csrf-token"]').content;
}

/**
 * Make API request with proper headers
 */
async function apiRequest(url, options = {}) {
    const defaultOptions = {
        headers: {
            "Content-Type": "application/json",
            "X-CSRF-TOKEN": getCsrfToken(),
            Accept: "application/json",
        },
        ...options,
    };

    try {
        const response = await fetch(url, defaultOptions);

        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }

        // For DELETE requests, return null
        if (response.status === 204) {
            return null;
        }

        return await response.json();
    } catch (error) {
        console.error("API request failed:", error);
        throw error;
    }
}

/**
 * Loads bookmarks from API and renders them
 */
async function loadBookmarks() {
    try {
        const bookmarks = await apiRequest("/api/bookmarks");
        renderBookmarks(bookmarks);
    } catch (error) {
        console.error("Failed to load bookmarks:", error);
        renderBookmarks([]);
    }
}

/**
 * Adds a new bookmark
 */
async function addBookmark() {
    const nameInput = document.getElementById("bookmarkName");
    const urlInput = document.getElementById("bookmarkUrl");

    const name = nameInput.value.trim();
    let url = urlInput.value.trim();

    if (name === "" || url === "") {
        showBookmarkAlert("Please enter both name and URL");
        return;
    }

    // Add https:// if no protocol is specified
    if (!url.startsWith("http://") && !url.startsWith("https://")) {
        url = "https://" + url;
    }

    // Basic URL validation
    if (!isValidUrl(url)) {
        showBookmarkAlert(
            "Please enter a valid URL (e.g., google.com or https://google.com)"
        );
        return;
    }

    try {
        await apiRequest("/api/bookmarks", {
            method: "POST",
            body: JSON.stringify({
                name: name,
                url: url,
            }),
        });

        // Clear inputs
        nameInput.value = "";
        urlInput.value = "";
        nameInput.focus();

        await loadBookmarks();
    } catch (error) {
        console.error("Failed to add bookmark:", error);
        showBookmarkAlert("Failed to add bookmark. Please try again.");
    }
}

/**
 * Deletes a bookmark by ID
 * @param {number} id - The unique ID of the bookmark
 */
async function deleteBookmark(id) {
    try {
        await apiRequest(`/api/bookmarks/${id}`, {
            method: "DELETE",
        });

        await loadBookmarks();
    } catch (error) {
        console.error("Failed to delete bookmark:", error);
        showBookmarkAlert("Failed to delete bookmark. Please try again.");
    }
}

/**
 * Validates URL format
 * @param {string} url - The URL to validate
 * @returns {boolean}
 */
function isValidUrl(url) {
    try {
        const urlObj = new URL(url);
        return urlObj.protocol === "http:" || urlObj.protocol === "https:";
    } catch (e) {
        return false;
    }
}

/**
 * Gets the favicon URL for a given website URL
 * @param {string} url - The website URL
 * @returns {string} - The favicon URL
 */
function getFavicon(url) {
    try {
        const urlObj = new URL(url);
        return `https://www.google.com/s2/favicons?domain=${urlObj.hostname}&sz=64`;
    } catch (e) {
        return "";
    }
}

/**
 * Extracts domain name from URL
 * @param {string} url - The URL
 * @returns {string} - The domain name
 */
function getDomain(url) {
    try {
        const urlObj = new URL(url);
        return urlObj.hostname.replace("www.", "");
    } catch (e) {
        return url;
    }
}

/**
 * Renders bookmarks to the DOM
 * @param {Array<Object>} bookmarks - Array of bookmark objects
 */
function renderBookmarks(bookmarks) {
    const bookmarksList = document.getElementById("bookmarksList");
    const emptyState = document.getElementById("emptyState");

    if (bookmarks.length === 0) {
        bookmarksList.innerHTML = "";
        emptyState.classList.remove("hidden");
        return;
    }

    emptyState.classList.add("hidden");

    bookmarksList.innerHTML = bookmarks
        .map((bookmark) => {
            const favicon = getFavicon(bookmark.url);
            const domain = getDomain(bookmark.url);

            return `
                <div class="relative group border border-gray-300 dark:border-white/20 rounded-xl transition-all duration-200 flex flex-col items-center justify-center gap-3 p-6">
                    <button
                        onclick="deleteBookmark(${bookmark.id})"
                        class="absolute top-2 right-2 z-50 text-gray-500 hover:text-black dark:text-gray-400  dark:hover:text-white text-2xl p-1 rounded-full"
                        style="position: absolute; top: 0.5rem; right: 1rem;"
                        aria-label="Delete bookmark">
                        &times;
                    </button>
                    <a href="${escapeHtml(
                        bookmark.url
                    )}" target="_blank" class="flex flex-col items-center justify-center gap-3 cursor-pointer">
                        <div class="w-16 h-16 flex items-center justify-center rounded-xl bg-gray-100 dark:bg-white/10 overflow-hidden">
                            ${
                                favicon
                                    ? `<img src="${favicon}" alt="${escapeHtml(
                                          bookmark.name
                                      )}" class="w-12 h-12 object-contain" onerror="this.style.display='none'; this.nextElementSibling.style.display='block';">`
                                    : ""
                            }
                            <div class="text-3xl" style="${
                                favicon ? "display:none;" : ""
                            }">🔗</div>
                        </div>
                        <div class="text-center">
                            <div class="font-semibold text-lg text-gray-900 dark:text-white truncate max-w-full">${escapeHtml(
                                bookmark.name
                            )}</div>
                            <div class="text-sm text-gray-600 dark:text-gray-400 truncate max-w-full">${escapeHtml(
                                domain
                            )}</div>
                        </div>
                    </a>
                </div>
            `;
        })
        .join("");
}

/**
 * Escapes HTML to prevent XSS
 * @param {string} text - The text to escape
 * @returns {string} - The escaped text
 */
function escapeHtml(text) {
    const div = document.createElement("div");
    div.textContent = text;
    return div.innerHTML;
}

// Load bookmarks when page loads
document.addEventListener("DOMContentLoaded", () => {
    // Only run if bookmark elements exist on the page
    const bookmarkUrlInput = document.getElementById("bookmarkUrl");
    const bookmarkNameInput = document.getElementById("bookmarkName");

    if (bookmarkUrlInput && bookmarkNameInput) {
        loadBookmarks();

        // Allow Enter key to submit form from both inputs
        bookmarkUrlInput.addEventListener("keydown", (e) => {
            if (e.key === "Enter") {
                addBookmark();
            }
        });

        bookmarkNameInput.addEventListener("keydown", (e) => {
            if (e.key === "Enter") {
                addBookmark();
            }
        });
    }

    // Initialize modal event listeners
    const bookmarkAlertModal = document.getElementById("bookmarkAlertModal");
    const closeBookmarkAlert = document.getElementById("closeBookmarkAlert");
    const bookmarkAlertOkBtn = document.getElementById("bookmarkAlertOkBtn");

    if (closeBookmarkAlert) {
        closeBookmarkAlert.addEventListener("click", () => {
            bookmarkAlertModal.style.display = "none";
        });
    }

    if (bookmarkAlertOkBtn) {
        bookmarkAlertOkBtn.addEventListener("click", () => {
            bookmarkAlertModal.style.display = "none";
        });
    }

    // Close modal when clicking outside
    if (bookmarkAlertModal) {
        window.addEventListener("click", (e) => {
            if (e.target === bookmarkAlertModal) {
                bookmarkAlertModal.style.display = "none";
            }
        });
    }
});

/**
 * Show alert modal with a message
 * @param {string} message - The message to display
 */
function showBookmarkAlert(message) {
    const modal = document.getElementById("bookmarkAlertModal");
    const messageEl = document.getElementById("bookmarkAlertMessage");

    if (modal && messageEl) {
        messageEl.textContent = message;
        modal.style.display = "block";
    }
}

// Expose functions to global scope for inline onclick handlers
window.addBookmark = addBookmark;
window.deleteBookmark = deleteBookmark;
