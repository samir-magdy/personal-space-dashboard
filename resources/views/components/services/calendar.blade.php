<div class="calendar-widget-container relative w-full h-full overflow-hidden flex flex-col">
    <div class="calendar-widget relative text-gray-900 dark:text-white p-2 sm:p-6 w-full flex-1">
        <div id='calendar' class="h-full"></div>
    </div>
    <div class="text-center text-gray-800 dark:text-gray-400 text-sm sm:text-base py-6 px-2 md:pt-0">
        Tip: Click on a day to add an event, click on an event to remove it.
    </div>
</div>

<!-- Event Modal -->
<div id="eventModal" class="calendar-modal">
    <div id="modalContent" class="calendar-modal-content bg-white dark:bg-gray-800 border border-gray-300 dark:border-white/20 text-gray-900 dark:text-white shadow-2xl">
        <span id="closeModal" class="calendar-modal-close text-gray-400 hover:text-black dark:text-gray-400 dark:hover:text-white">&times;</span>
        <h3 class="calendar-modal-title text-gray-900 dark:text-white" id="modalTitle">Add New Event</h3>

        <form id="eventForm">
            <label for="eventTitle" class="calendar-form-label text-gray-900 dark:text-white">Event Title:</label>
            <input type="text" id="eventTitle" placeholder="e.g., Team Meeting, Deadline" class="calendar-form-input bg-white dark:bg-gray-700 border border-gray-300 dark:border-white/20 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-white/60 focus:ring-2 focus:ring-purple-500 focus:border-transparent">

            <div class="calendar-form-actions">
                <button type="button" id="cancelBtn" class="calendar-btn calendar-btn-secondary">Cancel</button>
                <button type="submit" id="saveBtn" class="calendar-btn calendar-btn-primary">Save Event</button>
            </div>
        </form>
    </div>
</div>

<!-- Alert Modal -->
<div id="alertModal" class="calendar-modal alert-modal">
    <div id="alertModalContent" class="calendar-modal-content bg-white dark:bg-gray-800 border border-gray-300 dark:border-white/20 text-gray-900 dark:text-white shadow-2xl">
        <span id="closeAlert" class="calendar-modal-close text-gray-400 hover:text-black dark:text-gray-400 dark:hover:text-white">&times;</span>
        <h3 class="calendar-modal-title text-gray-900 dark:text-white">Notice</h3>
        <p id="alertMessage" class="calendar-modal-message text-gray-700 dark:text-gray-300"></p>
        <div class="calendar-form-actions-right">
            <button id="alertOkBtn" class="calendar-btn calendar-btn-large calendar-btn-primary">OK</button>
        </div>
    </div>
</div>

<!-- Confirm Modal -->
<div id="confirmModal" class="calendar-modal confirm-modal min-w-0 break-all">
    <div id="confirmModalContent" class="calendar-modal-content bg-white dark:bg-gray-800 border border-gray-300 dark:border-white/20 text-gray-900 dark:text-white shadow-2xl">
        <span id="closeConfirm" class="calendar-modal-close text-gray-400 hover:text-black dark:text-gray-400 dark:hover:text-white">&times;</span>
        <h3 class="calendar-modal-title text-gray-900 dark:text-white">Confirm Action</h3>
        <p id="confirmMessage" class="calendar-modal-message text-gray-700 dark:text-gray-300"></p>
        <div class="calendar-form-actions-flex">
            <button id="confirmCancelBtn" class="calendar-btn calendar-btn-large calendar-btn-secondary">Cancel</button>
            <button id="confirmOkBtn" class="calendar-btn calendar-btn-large calendar-btn-danger">Delete</button>
        </div>
    </div>
</div>

<!-- Load FullCalendar from CDN -->
<link href='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.css' rel='stylesheet' />

<script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.js'></script>

<script>
    (function() {
        'use strict';

        let calendarInstance = null;
        let selectedDate = null;
        let isInitialized = false;
        let editingEvent = null;

        // Get CSRF token from meta tag
        function getCsrfToken() {
            return document.querySelector('meta[name="csrf-token"]').content;
        }

        // Make API request with proper headers
        async function apiRequest(url, options = {}) {
            const defaultOptions = {
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': getCsrfToken(),
                    'Accept': 'application/json',
                },
                ...options,
            };

            try {
                const response = await fetch(url, defaultOptions);

                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }

                if (response.status === 204) {
                    return null;
                }

                return await response.json();
            } catch (error) {
                console.error('API request failed:', error);
                throw error;
            }
        }

        // Load events from API
        async function loadEvents() {
            try {
                const events = await apiRequest('/api/calendar-events');
                return events || [];
            } catch (e) {
                console.error('Error loading events:', e);
                return [];
            }
        }

        // Save event to API
        async function saveEvent(event) {
            try {
                const response = await apiRequest('/api/calendar-events', {
                    method: 'POST',
                    body: JSON.stringify(event),
                });
                return response;
            } catch (e) {
                console.error('Error saving event:', e);
                throw e;
            }
        }

        // Update event in API
        async function updateEvent(eventId, updates) {
            try {
                const response = await apiRequest(`/api/calendar-events/${eventId}`, {
                    method: 'PUT',
                    body: JSON.stringify(updates),
                });
                return response;
            } catch (e) {
                console.error('Error updating event:', e);
                throw e;
            }
        }

        // Delete event from API
        async function deleteEvent(eventId) {
            try {
                await apiRequest(`/api/calendar-events/${eventId}`, {
                    method: 'DELETE',
                });
            } catch (e) {
                console.error('Error deleting event:', e);
                throw e;
            }
        }

        // Format date nicely
        function formatDate(dateStr) {
            const date = new Date(dateStr + 'T00:00:00');
            const options = {
                weekday: 'long',
                year: 'numeric',
                month: 'long',
                day: 'numeric'
            };
            return date.toLocaleDateString('en-US', options);
        }

        // Custom Alert Modal
        function showAlert(message) {
            return new Promise(function(resolve) {
                const modal = document.getElementById('alertModal');
                const messageEl = document.getElementById('alertMessage');
                const okBtn = document.getElementById('alertOkBtn');
                const closeBtn = document.getElementById('closeAlert');
                const modalContent = document.getElementById('alertModalContent');

                messageEl.textContent = message;
                modal.style.display = 'block';

                function close() {
                    modal.style.display = 'none';
                    resolve();
                }

                okBtn.onclick = close;
                closeBtn.onclick = close;

                modal.onclick = function(e) {
                    if (e.target === modal) {
                        close();
                    }
                };

                modalContent.onclick = function(e) {
                    e.stopPropagation();
                };

                // ESC key support
                function handleEsc(e) {
                    if (e.key === 'Escape') {
                        close();
                        document.removeEventListener('keydown', handleEsc);
                    }
                }
                document.addEventListener('keydown', handleEsc);
            });
        }

        // Custom Confirm Modal
        function showConfirm(message) {
            return new Promise(function(resolve) {
                const modal = document.getElementById('confirmModal');
                const messageEl = document.getElementById('confirmMessage');
                const okBtn = document.getElementById('confirmOkBtn');
                const cancelBtn = document.getElementById('confirmCancelBtn');
                const closeBtn = document.getElementById('closeConfirm');
                const modalContent = document.getElementById('confirmModalContent');

                messageEl.textContent = message;
                modal.style.display = 'block';

                function close(result) {
                    modal.style.display = 'none';
                    resolve(result);
                    document.removeEventListener('keydown', handleEsc);
                }

                okBtn.onclick = function() {
                    close(true);
                };
                cancelBtn.onclick = function() {
                    close(false);
                };
                closeBtn.onclick = function() {
                    close(false);
                };

                modal.onclick = function(e) {
                    if (e.target === modal) {
                        close(false);
                    }
                };

                modalContent.onclick = function(e) {
                    e.stopPropagation();
                };

                // ESC key support
                function handleEsc(e) {
                    if (e.key === 'Escape') {
                        close(false);
                    }
                }
                document.addEventListener('keydown', handleEsc);
            });
        }

        function closeModal() {
            const modal = document.getElementById('eventModal');
            const form = document.getElementById('eventForm');
            if (modal) modal.style.display = 'none';
            if (form) form.reset();
            editingEvent = null;
        }

        function openEventModal(date, event = null) {
            const modal = document.getElementById('eventModal');
            const titleInput = document.getElementById('eventTitle');
            const modalTitle = document.getElementById('modalTitle');
            const saveBtn = document.getElementById('saveBtn');

            if (event) {
                // Edit mode
                editingEvent = event;
                modalTitle.textContent = 'Edit Event';
                titleInput.value = event.title;
                saveBtn.textContent = 'Update Event';
            } else {
                // Add mode
                editingEvent = null;
                modalTitle.textContent = 'Add New Event';
                titleInput.value = '';
                saveBtn.textContent = 'Save Event';
            }

            selectedDate = event ? event.startStr : date;
            modal.style.display = 'block';
            setTimeout(function() {
                titleInput.focus();
            }, 100);
        }

        async function initializeCalendar() {
            // Prevent multiple initializations
            if (isInitialized || !window.FullCalendar) return;

            const calendarEl = document.getElementById('calendar');
            if (!calendarEl) return;

            const modal = document.getElementById('eventModal');
            const closeBtn = document.getElementById('closeModal');
            const cancelBtn = document.getElementById('cancelBtn');
            const modalContent = document.getElementById('modalContent');
            const form = document.getElementById('eventForm');
            const titleInput = document.getElementById('eventTitle');

            if (!modal || !closeBtn || !form || !titleInput) {
                console.error('Missing modal elements');
                return;
            }

            // Load events from API
            const events = await loadEvents();

            // Create calendar
            calendarInstance = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                headerToolbar: {
                    left: '',
                    center: 'title',
                    right: 'today prev,next'
                },
                buttonText: {
                    today: 'Today'
                },
                showNonCurrentDates: false,
                fixedWeekCount: false,
                height: '100%',
                events: events,
                editable: false,
                selectable: true,

                dateClick: function(info) {
                    openEventModal(info.dateStr);
                },

                eventClick: function(info) {
                    info.jsEvent.preventDefault();

                    // Show options: Edit or Delete
                    const eventTitle = info.event.title;
                    const eventId = info.event.id;

                    // Ctrl+Click or Cmd+Click = Edit, Regular click = Delete
                    if (info.jsEvent.ctrlKey || info.jsEvent.metaKey || info.jsEvent.button === 2) {
                        openEventModal(info.event.startStr, info.event);
                    } else {
                        // Regular click = delete
                        showConfirm('Delete "' + eventTitle + '"?').then(async function(confirmed) {
                            if (confirmed && eventId) {
                                try {
                                    await deleteEvent(eventId);
                                    info.event.remove();
                                } catch (error) {
                                    showAlert('Failed to delete event. Please try again.');
                                }
                            }
                        });
                    }
                },

                eventDidMount: function(info) {
                    // Add tooltip
                    info.el.title = 'Click to delete, Ctrl+Click to edit';
                }
            });

            calendarInstance.render();
            isInitialized = true;

            // Close modal handlers
            closeBtn.onclick = function(e) {
                e.stopPropagation();
                closeModal();
            };

            cancelBtn.onclick = function(e) {
                e.preventDefault();
                closeModal();
            };

            modal.onclick = function(e) {
                if (e.target === modal) {
                    closeModal();
                }
            };

            modalContent.onclick = function(e) {
                e.stopPropagation();
            };

            // ESC key to close modal
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape' && modal.style.display === 'block') {
                    closeModal();
                }
            });

            // Form submission
            form.onsubmit = async function(e) {
                e.preventDefault();

                const title = titleInput.value.trim();
                if (!title || !selectedDate) {
                    showAlert('Please enter an event title.');
                    return;
                }

                try {
                    if (editingEvent) {
                        // Update existing event
                        const eventId = editingEvent.id;
                        if (eventId) {
                            await updateEvent(eventId, {
                                title: title
                            });
                            editingEvent.setProp('title', title);
                        }
                    } else {
                        // Create new event
                        const newEventData = {
                            title: title,
                            start: selectedDate,
                            allDay: true,
                            backgroundColor: '#7c3aed',
                            borderColor: '#7c3aed'
                        };

                        const savedEvent = await saveEvent(newEventData);

                        if (savedEvent && savedEvent.id) {
                            calendarInstance.addEvent({
                                id: savedEvent.id,
                                title: savedEvent.title,
                                start: savedEvent.start,
                                allDay: savedEvent.allDay,
                                backgroundColor: savedEvent.backgroundColor,
                                borderColor: savedEvent.borderColor
                            });
                        }
                    }

                    closeModal();
                    selectedDate = null;
                } catch (error) {
                    showAlert('Failed to save event. Please try again.');
                    console.error('Error saving event:', error);
                }
            };
        }

        // Wait for FullCalendar to load
        function waitForFullCalendar() {
            if (typeof FullCalendar !== 'undefined') {
                setupCalendarWidget();
            } else {
                setTimeout(waitForFullCalendar, 100);
            }
        }

        function setupCalendarWidget() {
            function checkWidget() {
                const widget = document.getElementById('calendar-widget');
                if (!widget) {
                    setTimeout(checkWidget, 100);
                    return;
                }

                const observer = new MutationObserver(function(mutations) {
                    const widget = document.getElementById('calendar-widget');
                    if (widget && !widget.classList.contains('hidden') && !isInitialized) {
                        setTimeout(initializeCalendar, 200);
                    }
                });

                observer.observe(widget, {
                    attributes: true,
                    attributeFilter: ['class']
                });

                if (!widget.classList.contains('hidden')) {
                    setTimeout(initializeCalendar, 200);
                }
            }

            checkWidget();
        }

        // Start when DOM is ready
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', waitForFullCalendar);
        } else {
            waitForFullCalendar();
        }
    })();
</script>