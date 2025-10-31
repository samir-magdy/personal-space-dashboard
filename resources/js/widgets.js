// Widget switching and navigation functionality
document.addEventListener("DOMContentLoaded", function () {
    const widgetLinks = document.querySelectorAll(".widget-link");
    const widgetContainers = document.querySelectorAll(".widget-container");

    // Function to switch to a widget
    function switchToWidget(widgetName) {
        // Hide all widget containers
        widgetContainers.forEach((container) => {
            container.classList.add("hidden");
        });

        // Show the selected widget
        const targetWidget = document.getElementById(widgetName + "-widget");
        if (targetWidget) {
            targetWidget.classList.remove("hidden");
        }

        // Update active state on links
        widgetLinks.forEach((l) => {
            l.classList.remove("bg-white", "dark:bg-gray-700");
        });

        // Add active class to the corresponding link
        const activeLink = document.querySelector(
            `[data-widget="${widgetName}"]`
        );
        if (activeLink) {
            activeLink.classList.add("bg-white", "dark:bg-gray-700");
        }

        // Save to localStorage
        localStorage.setItem("activeWidget", widgetName);
    }

    // Restore last active widget on page load
    const savedWidget = localStorage.getItem("activeWidget") || "home";
    switchToWidget(savedWidget);

    widgetLinks.forEach((link) => {
        link.addEventListener("click", function (e) {
            e.preventDefault();

            // Get the widget to display
            const widgetName = this.getAttribute("data-widget");

            // Switch to the widget
            switchToWidget(widgetName);

            // Close mobile sidebar after selecting a widget
            const sidebar = document.getElementById("default-sidebar");
            if (sidebar && window.innerWidth < 640) {
                sidebar.classList.add("-translate-x-full");
            }
        });
    });

    // Mobile menu toggle
    const toggleButton = document.querySelector(
        '[data-drawer-toggle="default-sidebar"]'
    );
    const sidebar = document.getElementById("default-sidebar");

    if (toggleButton && sidebar) {
        toggleButton.addEventListener("click", function () {
            sidebar.classList.toggle("-translate-x-full");
        });

        // Close sidebar when clicking outside on mobile
        document.addEventListener("click", function (e) {
            if (window.innerWidth < 640) {
                if (
                    !sidebar.contains(e.target) &&
                    !toggleButton.contains(e.target)
                ) {
                    sidebar.classList.add("-translate-x-full");
                }
            }
        });
    }
});
