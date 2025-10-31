// Home page greeting functionality
document.addEventListener("DOMContentLoaded", function () {
    const homeGreeting = document.getElementById("home-greeting");
    const homeDate = document.getElementById("home-date");

    function updateHomeGreeting() {
        const now = new Date();
        const hours = now.getHours();

        let greeting = "";
        if (hours >= 5 && hours < 12) {
            greeting = "Good Morning,";
        } else if (hours >= 12 && hours < 17) {
            greeting = "Good Afternoon,";
        } else {
            greeting = "Good Evening,";
        }

        if (homeGreeting) {
            homeGreeting.textContent = greeting;
        }
    }

    function updateHomeDate() {
        const now = new Date();
        const months = [
            "January",
            "February",
            "March",
            "April",
            "May",
            "June",
            "July",
            "August",
            "September",
            "October",
            "November",
            "December",
        ];
        const days = [
            "Sunday",
            "Monday",
            "Tuesday",
            "Wednesday",
            "Thursday",
            "Friday",
            "Saturday",
        ];

        const dayName = days[now.getDay()];
        const monthName = months[now.getMonth()];
        const date = now.getDate();
        const year = now.getFullYear();

        if (homeDate) {
            homeDate.textContent = `${dayName}, ${monthName} ${date}, ${year}`;
        }
    }

    // Initialize home page
    updateHomeGreeting();
    updateHomeDate();

    // Update greeting and date every minute
    setInterval(() => {
        updateHomeGreeting();
        updateHomeDate();
    }, 60000);
});
