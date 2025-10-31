// Clock widget functionality
document.addEventListener("DOMContentLoaded", function () {
    const clockDisplay = document.getElementById("clock-display");
    const dateDisplay = document.getElementById("date-display");
    const modeBtn = document.getElementById("clock-toggle-mode");
    const modeText = document.getElementById("clock-mode-text");

    // Load saved preference from localStorage
    let twelve_mode = localStorage.getItem("clock_twelve_mode") === "true";

    function updateBtn() {
        const newText = !twelve_mode ? "24 Hour Format" : "12 Hour Format";

        // Update button text if text element exists, otherwise update button directly
        if (modeText) {
            modeText.innerText = newText;
        } else if (modeBtn) {
            modeBtn.innerText = newText;
        }
    }

    // Initialize button if it exists
    if (modeBtn) {
        updateBtn();

        // Add event listener
        modeBtn.addEventListener("click", function () {
            twelve_mode = !twelve_mode;
            // Save preference to localStorage
            localStorage.setItem("clock_twelve_mode", twelve_mode);
            updateBtn();
            // Only update clock if it exists on the page
            if (clockDisplay) {
                updateClock();
            }
        });
    }

    // Function to update date display
    function updateDate() {
        if (!dateDisplay) {
            return;
        }

        const date = new Date();
        const months = [
            "Jan",
            "Feb",
            "Mar",
            "Apr",
            "May",
            "Jun",
            "Jul",
            "Aug",
            "Sep",
            "Oct",
            "Nov",
            "Dec",
        ];
        const month = months[date.getMonth()];
        const day = date.getDate();

        dateDisplay.innerHTML = `${month} ${day}&nbsp;`;
    }

    // Only run clock functionality if clock display exists
    if (!clockDisplay) {
        return;
    }

    function updateClock() {
        let period = "";
        let time = new Date();
        let hours, minutes, seconds;

        if (!twelve_mode) {
            hours = time.getHours().toString().padStart(2, "0");
        } else {
            hours = time.getHours();
            if (hours >= 12) {
                period = "PM";
            } else {
                period = "AM";
            }
            hours = hours % 12;
            if (hours === 0) {
                hours = 12;
            }
            hours = hours.toString().padStart(2, "0");
        }

        minutes = time.getMinutes().toString().padStart(2, "0");
        seconds = time.getSeconds().toString().padStart(2, "0");

        let time_formatted = `${hours}:${minutes}`;
        if (twelve_mode) {
            time_formatted = `${hours}:${minutes} ${period}`;
        }

        clockDisplay.innerText = time_formatted;
    }

    // Initial update
    updateClock();
    updateDate();

    // Update every second (date will update at midnight)
    setInterval(() => {
        updateClock();
        updateDate();
    }, 1000);
});
