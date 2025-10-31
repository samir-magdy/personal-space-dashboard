// Weather widget functionality
document.addEventListener("DOMContentLoaded", function () {
    const weatherForm = document.getElementById("weather-search-form");

    if (weatherForm) {
        weatherForm.addEventListener("submit", async function (e) {
            e.preventDefault();

            const cityInput = document.getElementById("city-input");
            const searchBtn = document.getElementById("weather-search-btn");
            const errorDiv = document.getElementById("weather-error");
            const city = cityInput.value.trim();

            if (!city) {
                showError("Please enter a city name");
                return;
            }

            // Disable button and show loading state
            searchBtn.disabled = true;
            searchBtn.textContent = "Loading...";
            errorDiv.classList.add("hidden");

            try {
                const response = await fetch(
                    `/api/weather?city=${encodeURIComponent(city)}`,
                    {
                        method: "GET",
                        headers: {
                            "X-Requested-With": "XMLHttpRequest",
                            Accept: "application/json",
                        },
                    }
                );

                const data = await response.json();

                if (data.success && data.weather) {
                    updateWeatherDisplay(data.weather);
                } else {
                    showError(data.message || "Failed to fetch weather data");
                }
            } catch (error) {
                console.error("Error fetching weather:", error);
                showError("An error occurred while fetching weather data");
            } finally {
                // Re-enable button
                searchBtn.disabled = false;
                searchBtn.textContent = "Search";
            }
        });
    }

    function updateWeatherDisplay(weather) {
        // Update temperature
        const temperatureEl = document.getElementById("temperature");
        if (temperatureEl && weather.temp) {
            temperatureEl.textContent = `${Math.round(weather.temp)}°C`;
        }

        // Update location and description
        const locationEl = document.getElementById("location");
        if (locationEl) {
            let locationHTML = `${weather.city || "Unknown"}, ${
                weather.country || ""
            }`;
            if (weather.description) {
                locationHTML += `<div class="text-sm opacity-75 capitalize">${weather.description}</div>`;
            }
            locationEl.innerHTML = locationHTML;
        }

        // Update weather icon
        const iconImg = document.querySelector(".weather-icon-main img");
        if (iconImg && weather.icon) {
            iconImg.src = `https://openweathermap.org/img/wn/${weather.icon}@4x.png`;
            iconImg.alt = weather.description || "Weather icon";
        }
    }

    function showError(message) {
        const errorDiv = document.getElementById("weather-error");
        if (errorDiv) {
            errorDiv.textContent = message;
            errorDiv.classList.remove("hidden");
        }
    }
});

let cities = [];
let currentFocus = -1;

// Load Egyptian cities from JSON file
fetch("/data/egypt-cities.json")
    .then((response) => response.json())
    .then((data) => {
        cities = data;
    })
    .catch((error) => {
        console.error("Error loading cities:", error);
    });

const input = document.getElementById("city-input");
const dropdown = document.getElementById("city-dropdown");
const toggleBtn = document.getElementById("dropdown-toggle");
const arrowIcon = document.getElementById("arrow-icon");

// Only add event listeners if the weather widget elements exist
if (input && dropdown) {
    // Show filtered cities
    input.addEventListener("input", function () {
        const value = this.value.toLowerCase();
        dropdown.innerHTML = "";
        currentFocus = -1;

        if (!value) {
            dropdown.classList.add("hidden");
            return;
        }

        const filtered = cities.filter((city) =>
            city.toLowerCase().includes(value)
        );

        if (filtered.length === 0) {
            dropdown.classList.add("hidden");
            return;
        }

        filtered.forEach((city, index) => {
            const div = document.createElement("div");
            div.className = "dropdown-option";
            div.textContent = city;
            div.addEventListener("click", function () {
                input.value = city;
                dropdown.classList.add("hidden");
            });
            dropdown.appendChild(div);
        });

        dropdown.classList.remove("hidden");
    });

    // Keyboard navigation
    input.addEventListener("keydown", function (e) {
        const options = dropdown.querySelectorAll(".dropdown-option");

        if (e.key === "ArrowDown") {
            e.preventDefault();
            currentFocus++;
            if (currentFocus >= options.length) currentFocus = 0;
            setActive(options);
        } else if (e.key === "ArrowUp") {
            e.preventDefault();
            currentFocus--;
            if (currentFocus < 0) currentFocus = options.length - 1;
            setActive(options);
        } else if (e.key === "Enter") {
            e.preventDefault();
            if (currentFocus > -1 && options[currentFocus]) {
                options[currentFocus].click();
            }
        } else if (e.key === "Escape") {
            dropdown.classList.add("hidden");
        }
    });

    function setActive(options) {
        options.forEach((option, index) => {
            if (index === currentFocus) {
                option.classList.add("highlighted");
                option.scrollIntoView({
                    block: "nearest",
                });
            } else {
                option.classList.remove("highlighted");
            }
        });
    }

    // Close dropdown when clicking outside
    document.addEventListener("click", function (e) {
        if (!input.contains(e.target) && !dropdown.contains(e.target)) {
            dropdown.classList.add("hidden");
        }
    });

    // Toggle dropdown with arrow
    if (toggleBtn && arrowIcon) {
        toggleBtn.addEventListener("click", function (e) {
            e.stopPropagation();

            if (dropdown.classList.contains("hidden")) {
                // Show all cities
                dropdown.innerHTML = "";
                cities.forEach((city) => {
                    const div = document.createElement("div");
                    div.className = "dropdown-option";
                    div.textContent = city;
                    div.addEventListener("click", function () {
                        input.value = city;
                        dropdown.classList.add("hidden");
                        arrowIcon.style.transform = "rotate(0deg)";
                    });
                    dropdown.appendChild(div);
                });
                dropdown.classList.remove("hidden");
                arrowIcon.style.transform = "rotate(180deg)";
            } else {
                dropdown.classList.add("hidden");
                arrowIcon.style.transform = "rotate(0deg)";
            }
        });

        // Rotate arrow when dropdown opens/closes
        input.addEventListener("input", function () {
            if (!dropdown.classList.contains("hidden")) {
                arrowIcon.style.transform = "rotate(180deg)";
            } else {
                arrowIcon.style.transform = "rotate(0deg)";
            }
        });
    }
}
