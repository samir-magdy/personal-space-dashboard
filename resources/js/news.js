// News widget functionality
document.addEventListener("DOMContentLoaded", function () {
    const viewMoreBtn = document.getElementById("view-more-btn");
    const loadingSpinner = document.getElementById("loading-spinner");
    const newsContainer = document.getElementById("news-container");

    // View More functionality
    if (viewMoreBtn) {
        viewMoreBtn.addEventListener("click", async function () {
            // Hide button and show loading
            viewMoreBtn.classList.add("hidden");
            loadingSpinner.classList.remove("hidden");

            // Get current language from data attribute
            const currentLang =
                newsContainer.getAttribute("data-language") || "en";

            try {
                const response = await fetch(
                    `/api/news?language=${currentLang}&limit=10`,
                    {
                        method: "GET",
                        headers: {
                            "X-Requested-With": "XMLHttpRequest",
                            Accept: "application/json",
                        },
                    }
                );

                const data = await response.json();

                if (data.success && data.news) {
                    // Clear existing content
                    newsContainer.innerHTML = "";

                    // Add all fetched articles
                    data.news.forEach((article) => {
                        const articleHtml = createArticleHtml(article);
                        newsContainer.innerHTML += articleHtml;
                    });

                    // Hide loading spinner
                    loadingSpinner.classList.add("hidden");
                } else {
                    showError("Failed to load more news");
                }
            } catch (error) {
                console.error("Error fetching news:", error);
                showError("Error loading news");
            }
        });
    }

    // Language toggle functionality
    const languageToggle = document.getElementById("news-language-toggle");

    if (languageToggle) {
        languageToggle.addEventListener("click", async function (e) {
            e.preventDefault();

            const currentLang = this.getAttribute("data-current-lang");
            const newLang = currentLang === "en" ? "ar" : "en";

            // Show loading state
            if (newsContainer && loadingSpinner && viewMoreBtn) {
                newsContainer.style.opacity = "0.5";
                viewMoreBtn.classList.add("hidden");
                loadingSpinner.classList.remove("hidden");
                loadingSpinner.textContent = "Loading news...";
            }

            try {
                const response = await fetch(
                    `/api/news?language=${newLang}&limit=2`,
                    {
                        method: "GET",
                        headers: {
                            "X-Requested-With": "XMLHttpRequest",
                            Accept: "application/json",
                        },
                    }
                );

                const data = await response.json();

                if (data.success && data.news) {
                    // Update language attribute
                    newsContainer.setAttribute("data-language", newLang);

                    // Update button text and data attribute
                    this.setAttribute("data-current-lang", newLang);
                    this.textContent = newLang === "en" ? "العربية" : "English";

                    // Clear and add new content
                    newsContainer.innerHTML = "";
                    data.news.forEach((article) => {
                        const articleHtml = createArticleHtml(article);
                        newsContainer.innerHTML += articleHtml;
                    });

                    // Restore opacity
                    newsContainer.style.opacity = "1";

                    // Show view more button and hide loading
                    loadingSpinner.classList.add("hidden");
                    viewMoreBtn.classList.remove("hidden");
                } else {
                    showError(data.message || "Failed to fetch news data");
                }
            } catch (error) {
                console.error("Error fetching news:", error);
                showError("An error occurred while fetching news data");
            }
        });
    }

    // Helper function to escape HTML to prevent XSS
    function escapeHtml(text) {
        if (!text) return '';
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }

    // Helper function to create article HTML
    function createArticleHtml(article) {
        const imageHtml = article.image
            ? `<div class="w-full h-96 mb-3 overflow-hidden rounded-xl bg-gradient-to-br from-gray-200 to-gray-300 dark:from-gray-700 dark:to-gray-800 flex items-center justify-center">
                   <img src="${escapeHtml(article.image)}" alt="News" class="w-full h-full object-cover transition-transform duration-300" onerror="this.style.display='none'; this.parentElement.innerHTML='<div class=\\'flex flex-col items-center justify-center gap-2 p-8\\'><svg class=\\'w-16 h-16 opacity-30\\' fill=\\'currentColor\\' viewBox=\\'0 0 20 20\\'><path fill-rule=\\'evenodd\\' d=\\'M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z\\' clip-rule=\\'evenodd\\'/></svg><p class=\\'text-sm opacity-60 text-center px-4\\'>The image failed to load from the source.</p></div>';">
               </div>`
            : "";

        const descriptionHtml = article.description
            ? `<p class="text-lg opacity-80 line-clamp-2 mb-2">${escapeHtml(article.description)}</p>`
            : "";

        const publishedAtHtml = article.published_at
            ? `<p class="text-sm opacity-60">${formatDate(article.published_at)}</p>`
            : "";

        return `
            <a href="${escapeHtml(article.url)}" target="_blank" class="border border-gray-300 dark:border-white/10 rounded-2xl p-4 hover:bg-gray-100 dark:hover:bg-white/10 transition-all duration-200 flex flex-col group">
                ${imageHtml}
                <div class="flex flex-col flex-1 justify-between">
                    <div>
                        <h3 class="text-2xl font-semibold line-clamp-2 mb-2">${escapeHtml(article.title)}</h3>
                        ${descriptionHtml}
                    </div>
                    <div class="mt-2">
                        <p class="text-xl opacity-75 font-medium">Source: ${escapeHtml(article.source)}</p>
                        ${publishedAtHtml}
                    </div>
                </div>
            </a>
        `;
    }

    // Helper function to format date
    function formatDate(dateString) {
        const date = new Date(dateString);
        const now = new Date();
        const diffMs = now - date;
        const diffMins = Math.floor(diffMs / 60000);
        const diffHours = Math.floor(diffMs / 3600000);
        const diffDays = Math.floor(diffMs / 86400000);

        if (diffMins < 60) {
            return `${diffMins} minute${diffMins !== 1 ? 's' : ''} ago`;
        } else if (diffHours < 24) {
            return `${diffHours} hour${diffHours !== 1 ? 's' : ''} ago`;
        } else if (diffDays < 7) {
            return `${diffDays} day${diffDays !== 1 ? 's' : ''} ago`;
        } else {
            return date.toLocaleDateString();
        }
    }

    // Helper function to show errors
    function showError(message) {
        if (loadingSpinner) {
            loadingSpinner.textContent = message;
            loadingSpinner.classList.remove("hidden");
            setTimeout(() => {
                loadingSpinner.classList.add("hidden");
                if (viewMoreBtn) {
                    viewMoreBtn.classList.remove("hidden");
                }
                if (newsContainer) {
                    newsContainer.style.opacity = "1";
                }
            }, 2000);
        }
    }
});
