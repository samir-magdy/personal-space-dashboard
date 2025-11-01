// Widget switching and navigation functionality
document.addEventListener("DOMContentLoaded", function () {
    const widgetLinks = document.querySelectorAll(
        ".widget-link, .widget-link-mobile"
    );
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
            // Remove active states from sidebar links
            l.classList.remove("bg-white", "dark:bg-gray-700");
            // Remove active states from mobile bottom nav
            l.classList.remove(
                "bg-gray-200",
                "dark:bg-gray-600",
                "text-gray-900",
                "dark:text-white"
            );
            // Reset to default colors for mobile
            if (l.classList.contains("widget-link-mobile")) {
                l.classList.add("text-gray-700", "dark:text-gray-300");
            }
        });

        // Add active class to the corresponding links (both sidebar and mobile)
        const activeLinks = document.querySelectorAll(
            `[data-widget="${widgetName}"]`
        );
        activeLinks.forEach((link) => {
            if (link.classList.contains("widget-link-mobile")) {
                // Mobile bottom nav active state
                link.classList.add("bg-gray-200", "dark:bg-gray-600");
                link.classList.remove("text-gray-700", "dark:text-gray-300");
                link.classList.add("text-gray-900", "dark:text-white");
            } else {
                // Sidebar active state
                link.classList.add("bg-white", "dark:bg-gray-700");
            }
        });

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
