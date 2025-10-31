// State for filtering
let currentFilter = "all"; // 'all', 'active', 'completed'

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

// --- Storage Functions ---

/**
 * Loads todos from API and triggers the render process.
 */
async function loadTodos() {
    try {
        const todos = await apiRequest("/api/todos");
        renderTodos(todos);
    } catch (error) {
        console.error("Failed to load todos:", error);
        renderTodos([]);
    }
}

// --- Todo Action Functions ---

/**
 * Adds a new todo item based on the input field's value.
 */
async function addTodo() {
    const input = document.getElementById("todoInput");
    const text = input.value.trim();

    if (text === "") return;

    try {
        await apiRequest("/api/todos", {
            method: "POST",
            body: JSON.stringify({
                text: text,
                completed: false,
            }),
        });

        input.value = ""; // Clear the input field
        input.focus(); // Keep focus for quick adding
        await loadTodos(); // Reload todos
    } catch (error) {
        console.error("Failed to add todo:", error);
        alert("Failed to add todo. Please try again.");
    }
}

/**
 * Toggles the 'completed' status of a todo by its ID.
 * @param {number} id - The unique ID of the todo to toggle.
 */
async function toggleTodo(id) {
    try {
        // Get current state
        const todos = await apiRequest("/api/todos");
        const todo = todos.find((t) => t.id === id);

        if (!todo) return;

        // Update on server
        await apiRequest(`/api/todos/${id}`, {
            method: "PUT",
            body: JSON.stringify({
                completed: !todo.completed,
            }),
        });

        await loadTodos(); // Reload todos
    } catch (error) {
        console.error("Failed to toggle todo:", error);
        alert("Failed to update todo. Please try again.");
    }
}

/**
 * Deletes a todo item by its ID.
 * @param {number} id - The unique ID of the todo to delete.
 */
async function deleteTodo(id) {
    try {
        await apiRequest(`/api/todos/${id}`, {
            method: "DELETE",
        });

        await loadTodos(); // Reload todos
    } catch (error) {
        console.error("Failed to delete todo:", error);
        alert("Failed to delete todo. Please try again.");
    }
}

/**
 * Clears all completed todos from the list.
 */
async function clearCompleted() {
    try {
        const todos = await apiRequest("/api/todos");
        const completedTodos = todos.filter((t) => t.completed);

        // Delete each completed todo
        await Promise.all(
            completedTodos.map((todo) =>
                apiRequest(`/api/todos/${todo.id}`, {
                    method: "DELETE",
                })
            )
        );

        await loadTodos(); // Reload todos
    } catch (error) {
        console.error("Failed to clear completed todos:", error);
        alert("Failed to clear completed todos. Please try again.");
    }
}

// --- Filtering and Rendering ---

/**
 * Sets the current filter type and re-renders the list.
 * @param {'all' | 'active' | 'completed'} filterType - The filter to apply.
 */
function setFilter(filterType) {
    currentFilter = filterType;

    // Update the visual state of the filter buttons (Tailwind classes)
    document.querySelectorAll(".filter-btn").forEach((btn) => {
        btn.classList.remove(
            "bg-blue-600",
            "dark:bg-blue-600",
            "text-white",
            "hover:bg-blue-700",
            "dark:hover:bg-blue-700"
        );
        btn.classList.add(
            "text-gray-800",
            "dark:text-white/70",
            "hover:bg-gray-200",
            "dark:hover:bg-white/20"
        );
    });
    const activeBtn = document.getElementById(`filter-${filterType}`);
    if (activeBtn) {
        activeBtn.classList.remove(
            "text-gray-800",
            "dark:text-white/70",
            "hover:bg-gray-200",
            "dark:hover:bg-white/20"
        );
        activeBtn.classList.add(
            "bg-blue-600",
            "dark:bg-blue-600",
            "text-white",
            "hover:bg-blue-700",
            "dark:hover:bg-blue-700"
        );
    }

    // Re-render the list based on the new filter
    loadTodos();
}

/**
 * Renders the filtered list of todos to the DOM, and updates the footer/empty state.
 * @param {Array<Object>} allTodos - The complete array of todo objects.
 */
function renderTodos(allTodos) {
    const todoList = document.getElementById("todoList");
    const emptyState = document.getElementById("emptyState");
    const todoFooter = document.getElementById("todoFooter");

    // 1. Apply the current filter
    let filteredTodos = allTodos;
    if (currentFilter === "active") {
        filteredTodos = allTodos.filter((t) => !t.completed);
    } else if (currentFilter === "completed") {
        filteredTodos = allTodos.filter((t) => t.completed);
    }

    // 2. Handle Empty State
    if (allTodos.length === 0) {
        todoList.innerHTML = "";
        emptyState.classList.remove("hidden");
        todoFooter.classList.add("hidden");
        return;
    } else {
        emptyState.classList.add("hidden");
        todoFooter.classList.remove("hidden");
    }

    // 3. Render the list items
    if (filteredTodos.length === 0) {
        // If the *filtered* list is empty
        todoList.innerHTML = `
            <div class="text-center py-8 text-gray-600 dark:text-white/70 text-lg">
                No ${
                    currentFilter === "active" ? "active" : "completed"
                } tasks found.
            </div>
        `;
    } else {
        todoList.innerHTML = filteredTodos
            .map((todo) => {
                return `
                    <li class="flex items-center gap-4 border border-gray-300 dark:border-white/20 rounded-lg p-3 shadow-sm hover:bg-gray-100 dark:hover:bg-white/10 transition-all duration-200">
                        <input
                            id="todo-${todo.id}"
                            type="checkbox"
                            ${todo.completed ? "checked" : ""}
                            onchange="toggleTodo(${todo.id})"
                            data-checkbox-ignore="true"
                            class="w-5 h-5 rounded border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-cyan-600 focus:ring-2 focus:ring-cyan-500 focus:ring-offset-0 cursor-pointer transition-colors">
                        <label for="todo-${
                            todo.id
                        }" class="min-w-0 break-all flex-1 text-gray-900 dark:text-white text-lg leading-snug cursor-pointer transition-all ${
                    todo.completed ? "todo-completed" : "todo-active"
                }">
                            ${escapeHtml(todo.text)}
                        </label>
                        <button
                            onclick="deleteTodo(${todo.id})"
                            aria-label="Delete todo"
                            class="text-red-500 dark:text-white hover:text-red-600 dark:hover:text-red-300 transition-colors duration-200 text-2xl p-1 leading-none">
                            &times;
                        </button>
                    </li>
                `;
            })
            .join("");
    }

    // 4. Update the Footer count
    const itemsLeft = allTodos.filter((t) => !t.completed).length;
    document.getElementById("itemsLeft").textContent = `${itemsLeft} item${
        itemsLeft !== 1 ? "s" : ""
    } left`;
}

/**
 * Escapes HTML content to prevent XSS (Cross-Site Scripting) attacks.
 * @param {string} text - The raw text to escape.
 * @returns {string} The HTML-safe text.
 */
function escapeHtml(text) {
    const div = document.createElement("div");
    div.textContent = text;
    return div.innerHTML;
}

// Load todos and set initial filter when page loads
document.addEventListener("DOMContentLoaded", () => {
    // Only run if todo elements exist on the page
    const todoList = document.getElementById("todoList");

    if (todoList) {
        // Set the initial filter state visually
        setFilter(currentFilter);
        // Load and render todos
        loadTodos();
    }
});

// Expose functions to global scope for inline onclick handlers
window.addTodo = addTodo;
window.toggleTodo = toggleTodo;
window.deleteTodo = deleteTodo;
window.clearCompleted = clearCompleted;
window.setFilter = setFilter;
