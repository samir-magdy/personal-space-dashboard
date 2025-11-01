// Currency widget functionality
document.addEventListener("DOMContentLoaded", function () {
    const currencyForm = document.getElementById("currency-form");

    if (currencyForm) {
        currencyForm.addEventListener("submit", async function (e) {
            e.preventDefault();

            const baseCurrencySelect = document.getElementById("base-currency");
            const targetCurrencySelect =
                document.getElementById("target-currency");
            const amountInput = document.getElementById("currency_amount");
            const convertBtn = document.getElementById("currency-convert-btn");
            const errorDiv = document.getElementById("currency-error");

            const baseCurrency = baseCurrencySelect.value;
            const targetCurrency = targetCurrencySelect.value;

            // Get amount and validate - default to 1 if empty or invalid
            let amount = parseFloat(amountInput.value);
            if (
                isNaN(amount) ||
                amount <= 0 ||
                amountInput.value.trim() === ""
            ) {
                amount = 1;
            }

            if (!baseCurrency || !targetCurrency) {
                showError("Please select both currencies");
                return;
            }

            // Disable button and show loading state
            convertBtn.disabled = true;
            convertBtn.textContent = "Loading...";
            if (errorDiv) {
                errorDiv.classList.add("hidden");
            }

            try {
                const response = await fetch(
                    `/api/currency?base_currency=${encodeURIComponent(
                        baseCurrency
                    )}&target_currency=${encodeURIComponent(targetCurrency)}`,
                    {
                        method: "GET",
                        headers: {
                            "X-Requested-With": "XMLHttpRequest",
                            Accept: "application/json",
                        },
                    }
                );

                const data = await response.json();

                if (data.success && data.currency) {
                    updateCurrencyDisplay(data.currency, amount);
                } else {
                    showError(data.message || "Failed to fetch currency data");
                }
            } catch (error) {
                console.error("Error fetching currency:", error);
                showError("An error occurred while fetching currency data");
            } finally {
                // Re-enable button
                convertBtn.disabled = false;
                convertBtn.textContent = "Convert";
            }
        });
    }

    function updateCurrencyDisplay(currency, amount = 1) {
        // Calculate converted amount
        const convertedAmount = amount * parseFloat(currency.rate);

        // Update exchange rate display with converted amount
        const rateEl = document.getElementById("exchange-rate");
        if (rateEl && currency.rate) {
            rateEl.textContent = convertedAmount.toLocaleString("en-US", {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2,
            });
        }

        // Update currency pair display
        const currencyPairEl = document.getElementById("currency-pair");
        if (currencyPairEl) {
            currencyPairEl.innerHTML = `${amount} ${
                currency.base_currency || "USD"
            } to ${currency.target_currency || "EGP"}`;
        }
    }

    function showError(message) {
        const errorDiv = document.getElementById("currency-error");
        if (errorDiv) {
            errorDiv.textContent = message;
            errorDiv.classList.remove("hidden");
        }
    }
});
