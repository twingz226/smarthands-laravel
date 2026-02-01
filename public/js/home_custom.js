    document.addEventListener('DOMContentLoaded', function() {
    // --- Profile Modal Logic ---
    const profileForm = document.getElementById('profileForm');
    const profileModal = document.getElementById('profileModal');
    const successAlert = document.getElementById('profileSuccessAlert');

    // Optional Debug Mode
    const DEBUG_MODE = true; // Set to true to enable console logs, false to disable

    function logDebug(...args) {
        if (DEBUG_MODE) {
            console.log(...args);
        }
    }

    if (profileForm) {
        profileForm.addEventListener('submit', function (e) {
            e.preventDefault();
            successAlert.classList.add('d-none');
            // Clear errors
            ['name','email','current_password','new_password','new_password_confirmation'].forEach(function(field) {
                const errorElement = document.getElementById('error-' + field);
                let inputElement;
                if (field === 'name' || field === 'email') {
                    inputElement = document.getElementById('profile_' + field);
                } else {
                    inputElement = document.getElementById(field);
                }
                if (errorElement) errorElement.innerText = '';
                if (inputElement) inputElement.classList.remove('is-invalid');
            });

            const formData = new FormData(profileForm);
            fetch(profileForm.action, {
                method: 'POST',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    successAlert.textContent = data.message;
                    successAlert.classList.remove('d-none');
                    setTimeout(function() {
                        const modalInstance = bootstrap.Modal.getInstance(profileModal);
                        if (modalInstance) modalInstance.hide();
                        successAlert.classList.add('d-none');
                        window.location.href = '/';
                    }, 1200);
                } else if (data.errors) {
                    handleFormErrors(data.errors, 'profile');
                }
            })
            .catch(async error => {
                logDebug('Error:', error);
                if (error instanceof Response) {
                    const errData = await error.json();
                    if (errData.errors) {
                        handleFormErrors(errData.errors, 'profile');
                    }
                }
            });
        });
    }

    // --- Reschedule Booking Form Submission ---
    const rescheduleForm = document.getElementById('rescheduleBookingForm');
    if (rescheduleForm) {
        rescheduleForm.addEventListener('submit', function(e) {
            e.preventDefault();

            const bookingToken = rescheduleForm.dataset.bookingToken;

            // Ensure rescheduleForm is not null before accessing its dataset property
            if (!rescheduleForm) {
                console.error("rescheduleForm is null. Cannot access dataset property.");
                return;
            }
            
            const newCleaningDate = document.getElementById('new_cleaning_date').value;
            const newTime = document.getElementById('new_time').value;
            const reason = document.getElementById('reason').value;

            logDebug('Attempting to reschedule booking with token:', bookingToken);

            const formData = new FormData();
            const dateTime = newTime ? `${newCleaningDate} ${newTime}` : newCleaningDate;
            formData.append('new_cleaning_date', dateTime);
            formData.append('reason', reason);

            fetch(`/bookings/${bookingToken}/reschedule`, {
                method: 'POST',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert(data.message);
                    const rescheduleModal = bootstrap.Modal.getInstance(document.getElementById('rescheduleModal'));
                    if (rescheduleModal) rescheduleModal.hide();
                    location.reload(); // Reload the page to show updated bookings
                } else if (data.errors) {
                    let errorMessages = '';
                    for (const key in data.errors) {
                        errorMessages += data.errors[key].join('\n') + '\n';
                    }
                    alert('Validation Error:\n' + errorMessages);
                } else if (data.message) {
                    alert('Error: ' + data.message);
                }
            })
            .catch(error => {
                logDebug('Error:', error);
                alert('An unexpected error occurred during rescheduling.');
            });
        });

        // --- Reschedule Button Click Handler ---
        document.querySelectorAll('.reschedule-btn').forEach(button => {
            button.addEventListener('click', function() {
                const bookingToken = this.dataset.bookingToken;
                const serviceName = this.dataset.serviceName;
                const currentDate = this.dataset.currentDate;
                const currentTime = this.dataset.currentTime;

                const rescheduleModal = new bootstrap.Modal(document.getElementById('rescheduleModal'));
                document.getElementById('rescheduleServiceName').textContent = serviceName;
                document.getElementById('rescheduleCurrentDate').textContent = currentDate;
                document.getElementById('rescheduleCurrentTime').textContent = currentTime;
                rescheduleForm.dataset.bookingToken = bookingToken; // Store booking token on the form
                rescheduleModal.show();
                // Pre-fill with current date and time after modal is shown

            });
        });
    }

    // --- Cancel Button Click Handler ---
    document.querySelectorAll('.cancel-btn').forEach(button => {
        button.addEventListener('click', function() {
            const bookingId = this.dataset.bookingId;
            const serviceName = this.dataset.serviceName;

            // Removed confirm dialog as per user request
            // if (confirm(`Are you sure you want to cancel your booking for ${serviceName}? This action cannot be undone.`)) {
            fetch(`/bookings/${bookingId}/cancel`, {
                method: 'PUT',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // alert(data.message); // Removed alert as per user request
                    location.reload(); // Reload the page to show updated bookings
                } else if (data.message) {
                    // alert('Error: ' + data.message); // Removed alert as per user request
                }
            })
            .catch(error => {
                logDebug('Error:', error);
                // alert('An unexpected error occurred during cancellation.'); // Removed alert as per user request
            });
            // }
        });
    });

    // --- Booking Modal Handlers ---

    // --- Flatpickr for Booking Modal (Disable Fully Booked Dates) ---
    const bookingModalEl = document.getElementById('bookingModal');
    const cleaningDateInput = document.getElementById('cleaning_date');
    const fullyBookedAlert = document.getElementById('fullyBookedAlert');
    const loadingDates = document.getElementById('loadingDates');
    let fullyBookedDates = [];
    let fullyBookedTimes = {}; // { 'YYYY-MM-DD': ['09:00', '10:00', ...] }
    let flatpickrInstance;

    // Only run this logic for the booking modal on the home page
    if (bookingModalEl && cleaningDateInput) {
        async function fetchFullyBookedDates() {
            if (loadingDates) loadingDates.style.display = 'block';
            try {
                const response = await fetch('/fully-booked-dates?context=home');
                if (!response.ok) throw new Error(`HTTP error! status: ${response.status}`);
                const data = await response.json();
                fullyBookedDates = data.fullyBookedDates || [];
                fullyBookedTimes = data.fullyBookedTimes || {};
            } catch (error) {
                console.error('Error fetching fully booked dates:', error);
            } finally {
                if (loadingDates) loadingDates.style.display = 'none';
            }
        }

        // Build/refresh time options for the selected date based on cleaner availability
        function populateTimeOptions(dateStr) {
            const timeSelect = document.getElementById('cleaning_time');
            if (!timeSelect) return;

            // Preserve currently selected value if still available
            const prevValue = timeSelect.value;

            // Clear and rebuild options
            timeSelect.innerHTML = '';
            const placeholder = document.createElement('option');
            placeholder.value = '';
            placeholder.textContent = 'Select Time';
            timeSelect.appendChild(placeholder);

            const bookedList = (fullyBookedTimes && dateStr) ? (fullyBookedTimes[dateStr] || []) : [];

            // Generate options from 09:00 to 15:00 inclusive (hourly)
            for (let hour = 9; hour <= 15; hour++) {
                const time24 = `${String(hour).padStart(2, '0')}:00`;
                const isBooked = bookedList.includes(time24);

                const option = document.createElement('option');
                option.value = time24;
                option.textContent = formatTimeLabel(time24, isBooked);
                if (isBooked) {
                    option.disabled = true; // make unclickable
                }
                timeSelect.appendChild(option);
            }

            // Try to restore selection if still valid and enabled
            if (prevValue && !bookedList.includes(prevValue)) {
                timeSelect.value = prevValue;
            } else {
                timeSelect.value = '';
            }
        }

        // Real-time time slot availability checker
        async function checkTimeSlotAvailability(dateStr, timeStr) {
            const serviceId = document.getElementById('service_id')?.value;
            if (!dateStr || !timeStr || !serviceId) return true;

            try {
                const response = await fetch('/booking/check-timeslot', {
                    method: 'POST',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        cleaning_date: dateStr,
                        cleaning_time: timeStr,
                        service_id: serviceId
                    })
                });

                if (!response.ok) return true; // Assume available if request fails
                
                const data = await response.json();
                return data.available;
            } catch (error) {
                logDebug('Error checking time slot availability:', error);
                return true; // Assume available if request fails
            }
        }

        // Get detailed availability information for a time slot
        async function getTimeSlotDetails(dateStr, timeStr) {
            const serviceId = document.getElementById('service_id')?.value;
            if (!dateStr || !timeStr || !serviceId) return null;

            try {
                const response = await fetch('/booking/check-timeslot', {
                    method: 'POST',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        cleaning_date: dateStr,
                        cleaning_time: timeStr,
                        service_id: serviceId
                    })
                });

                if (!response.ok) return null; // Assume available if request fails
                
                const data = await response.json();
                return data;
            } catch (error) {
                logDebug('Error getting time slot details:', error);
                return null; // Assume available if request fails
            }
        }

        // Update individual time slot availability in real-time
        async function updateTimeSlotAvailability(dateStr) {
            const timeSelect = document.getElementById('cleaning_time');
            if (!timeSelect || !dateStr) return;

            const options = timeSelect.querySelectorAll('option[value]');
            
            for (const option of options) {
                if (option.value === '') continue; // Skip placeholder
                
                const isAvailable = await checkTimeSlotAvailability(dateStr, option.value);
                
                if (!isAvailable) {
                    option.disabled = true;
                    // Update text to show unavailability
                    const currentText = option.textContent;
                    // Remove any existing unavailable text first
                    const cleanText = currentText.replace(/ \(Unavailable[^\)]*\)/, '');
                    
                    if (!cleanText.includes(' (Unavailable)')) {
                        option.textContent = cleanText + ' (Unavailable)';
                    }
                } else {
                    option.disabled = false;
                    // Remove any unavailable text
                    option.textContent = option.textContent.replace(/ \(Unavailable[^\)]*\)/, '');
                }
            }
        }

        // Helper to format 24h "HH:MM" into 12h label and annotate if booked
        function formatTimeLabel(time24, isBooked) {
            try {
                const [h, m] = time24.split(':').map(Number);
                const ampm = h >= 12 ? 'PM' : 'AM';
                const h12 = (h % 12) || 12;
                const label = `${h12}:${String(m).padStart(2, '0')} ${ampm}`;
                return isBooked ? `${label} (Unavailable)` : label;
            } catch (e) {
                return isBooked ? `${time24} (Unavailable)` : time24;
            }
        }

        // Helper to (re)initialize Flatpickr with the latest fullyBookedDates
        function setupFlatpickrWithDisabledDates() {
            if (flatpickrInstance) {
                flatpickrInstance.destroy();
            }
            // Compute minimum selectable date: day-after-tomorrow
            const minSelectable = new Date();
            minSelectable.setHours(0, 0, 0, 0);
            minSelectable.setDate(minSelectable.getDate() + 2);

            // Sync native input min attribute as well (for fallback)
            try {
                const minStr = flatpickr.formatDate(minSelectable, "Y-m-d");
                cleaningDateInput.setAttribute('min', minStr);
            } catch (e) {
                // flatpickr may not be available yet; ignore gracefully
            }

            flatpickrInstance = flatpickr(cleaningDateInput, {
                dateFormat: "Y-m-d",
                // Disable today and tomorrow by setting minDate to day-after-tomorrow
                minDate: minSelectable,
                disable: fullyBookedDates.slice(),
                onDayCreate: function(dObj, dStr, fp, dayElem) {
                    const dateString = flatpickr.formatDate(dayElem.dateObj, "Y-m-d");
                    if (fullyBookedDates.includes(dateString)) {
                        dayElem.classList.add('fully-booked-date');
                    }
                },
                onChange: function(selectedDates, dateStr, instance) {
                    if (fullyBookedDates.includes(dateStr)) {
                        instance.clear();
                        if (fullyBookedAlert) fullyBookedAlert.style.display = 'block';
                    } else {
                        if (fullyBookedAlert) fullyBookedAlert.style.display = 'none';
                    }
                    // Refresh available time options whenever date changes
                    if (dateStr && !fullyBookedDates.includes(dateStr)) {
                        populateTimeOptions(dateStr);
                        // Also check real-time availability for all time slots
                        setTimeout(() => updateTimeSlotAvailability(dateStr), 100);
                    } else {
                        populateTimeOptions(null);
                    }
                }
            });
        }

        // Fetch and initialize on modal show
        if (bookingModalEl) {
            bookingModalEl.addEventListener('show.bs.modal', function() {
                fetchFullyBookedDates().then(() => {
                    setupFlatpickrWithDisabledDates();
                });
            });
        }
        // Also run once on initial load for first open
        fetchFullyBookedDates().then(() => {
            setupFlatpickrWithDisabledDates();
        });

        // Add real-time validation for time selection changes
        const timeSelect = document.getElementById('cleaning_time');
        if (timeSelect) {
            timeSelect.addEventListener('change', async function() {
                const dateInput = document.getElementById('cleaning_date');
                const dateStr = dateInput?.value;
                const timeStr = this.value;
                
                if (dateStr && timeStr) {
                    const isAvailable = await checkTimeSlotAvailability(dateStr, timeStr);
                    if (!isAvailable) {
                        // Show warning and reset selection
                        this.value = '';
                        alert('This time slot has insufficient cleaners available. Please select a different time.');
                    }
                }
            });
        }

        // Prevent form submission if date is fully booked
        const bookingForm = document.getElementById('bookingForm');
        if (bookingForm) {
            bookingForm.addEventListener('submit', async function(e) {
                // Prevent submit if date is fully booked
                const dateVal = cleaningDateInput.value;
                if (fullyBookedDates.includes(dateVal)) {
                    e.preventDefault();
                    cleaningDateInput.value = '';
                    if (fullyBookedAlert) fullyBookedAlert.style.display = 'block';
                    return;
                }

                // Check real-time availability of selected time slot
                const timeSelect = document.getElementById('cleaning_time');
                if (timeSelect && timeSelect.value) {
                    const isAvailable = await checkTimeSlotAvailability(dateVal, timeSelect.value);
                    if (!isAvailable) {
                        e.preventDefault();
                        timeSelect.value = '';
                        alert('The selected time slot has insufficient cleaners available. Please choose a different time.');
                        return;
                    }
                }

                // Prevent submit if chosen timeslot is disabled/booked
                if (timeSelect && timeSelect.value) {
                    const selectedOption = timeSelect.querySelector(`option[value="${timeSelect.value}"]`);
                    if (selectedOption && selectedOption.disabled) {
                        e.preventDefault();
                        // Clear invalid selection to force user to choose another
                        timeSelect.value = '';
                        alert('Please select a different time slot. The selected time has insufficient cleaner availability.');
                        return;
                    }
                }
            });
        }
    }

// --- END Flatpickr for Booking Modal ---
    function extractHourlyRate(text) {
        if (!text) return null;
        const cleaned = text.replace(/,/g, '');
        const match = cleaned.match(/(\d+(?:\.\d+)?)/);
        if (!match) return null;
        const value = parseFloat(match[1]);
        return Number.isNaN(value) ? null : value;
    }

    function extractMinimumHours(text) {
        if (!text) return 6;
        const lower = text.toLowerCase();
        const explicitMatch = lower.match(/minimum\s+of\s+(\d+)\s*hour/);
        if (explicitMatch) {
            const value = parseInt(explicitMatch[1], 10);
            if (!Number.isNaN(value) && value > 0) {
                return value;
            }
        }
        const genericMatch = lower.match(/(\d+)\s*hour/);
        if (genericMatch) {
            const value = parseInt(genericMatch[1], 10);
            if (!Number.isNaN(value) && value > 0) {
                return value;
            }
        }
        return 6;
    }

    function formatCurrency(value) {
        if (typeof value !== 'number' || Number.isNaN(value)) return '';
        const formatted = value.toLocaleString('en-PH', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
        return '₱' + formatted.replace(/\.00$/, '');
    }

    document.querySelectorAll('.book-btn[data-service-id]').forEach(function(btn) {
        btn.addEventListener('click', function() {
            const serviceId = this.getAttribute('data-service-id');
            const serviceBox = this.closest('.service-box');
            const serviceTitle = serviceBox?.querySelector('.service-title')?.textContent || '';
            const servicePrice = serviceBox?.querySelector('.service-price')?.textContent || '';
            const serviceType = this.getAttribute('data-service-type');
            const normalizedTitle = serviceTitle.trim().toLowerCase();
            const isMoveInMoveOut = normalizedTitle === 'move-in/move-out cleaning';
            
            document.getElementById('service_id').value = serviceId;
            const bookingModal = document.getElementById('bookingModal');
            
            if (bookingModal) {
                // Persist service type on the modal for any later logic if needed
                bookingModal.dataset.serviceType = serviceType || '';

                const serviceTitleElem = bookingModal.querySelector('.service-title');
                const servicePriceElem = bookingModal.querySelector('.service-price');
                const selectedServiceNameElem = bookingModal.querySelector('.selected-service-name');
                const totalInitialPriceAlert = bookingModal.querySelector('#totalInitialPriceAlert');
                if (serviceTitleElem) serviceTitleElem.textContent = serviceTitle;
                if (servicePriceElem) servicePriceElem.textContent = servicePrice;
                if (selectedServiceNameElem) selectedServiceNameElem.textContent = serviceTitle;

                // Dynamically set pricing message based on service type
                if (totalInitialPriceAlert) {
                    if (isMoveInMoveOut) {
                        totalInitialPriceAlert.innerHTML = '<strong>Rate:</strong> ₱75 per sqm (final price to be set by admin after inspection)';
                        totalInitialPriceAlert.classList.remove('d-none');
                    } else if (serviceType === 'hourly') {
                        const hourlyRate = extractHourlyRate(servicePrice);
                        if (hourlyRate !== null) {
                            const minimumHours = extractMinimumHours(servicePrice);
                            const totalInitialPrice = hourlyRate * minimumHours;
                            const rateLabel = formatCurrency(hourlyRate);
                            const totalLabel = formatCurrency(totalInitialPrice);
                            const hoursLabel = minimumHours + ' hour' + (minimumHours === 1 ? '' : 's');
                            totalInitialPriceAlert.innerHTML = '';
                            totalInitialPriceAlert.classList.add('d-none');
                        } else {
                            totalInitialPriceAlert.classList.add('d-none');
                            totalInitialPriceAlert.innerHTML = '';
                        }
                    } else if (serviceType === 'sqm') {
                        // For per-sqm services, show rate only and note that final price is set by admin
                        totalInitialPriceAlert.innerHTML = '<strong>Rate:</strong> ₱75 per sqm (final price to be set by admin after inspection)';
                        totalInitialPriceAlert.classList.remove('d-none');
                    } else {
                        // Unknown type: hide the alert
                        totalInitialPriceAlert.classList.add('d-none');
                        totalInitialPriceAlert.innerHTML = '';
                    }

                    // After setting the main price content, update fuel charge note based on city
                    updateFuelChargeNote();
                }
            }
        });
    });

    // Generate Booking Token on Modal Show
    const bookingModal = document.getElementById('bookingModal');
    if (bookingModal) {
        bookingModal.addEventListener('show.bs.modal', function () {
            const token = 'BK-' + Date.now() + '-' + Math.random().toString(36).substr(2, 8);
            document.getElementById('booking_token').value = token;
            // Do not force-hide the price alert here; it will be set appropriately on click based on service type
        });
    }

    // --- Fuel Charge Note Logic ---
    function updateFuelChargeNote() {
        const bookingModal = document.getElementById('bookingModal');
        if (!bookingModal) return;
        const totalInitialPriceAlert = bookingModal.querySelector('#totalInitialPriceAlert');
        if (!totalInitialPriceAlert || totalInitialPriceAlert.classList.contains('d-none')) return;

        const citySelect = document.getElementById('city');
        const fuelNoteId = 'fuelChargeNote';
        let fuelNoteElem = totalInitialPriceAlert.querySelector('#' + fuelNoteId);

        const isOutsideBacolod = citySelect && citySelect.value && citySelect.value !== 'Bacolod';

        if (isOutsideBacolod) {
            if (!fuelNoteElem) {
                fuelNoteElem = document.createElement('div');
                fuelNoteElem.id = fuelNoteId;
                fuelNoteElem.className = 'mt-2 small text-danger';
                fuelNoteElem.textContent = 'Note: For areas outside Bacolod, a ₱300 fuel charge applies.';
                totalInitialPriceAlert.appendChild(fuelNoteElem);
            }
        } else {
            if (fuelNoteElem) fuelNoteElem.remove();
        }
    }

    // --- Complete Barangays Data ---
    const barangaysByCity = {
        "Bacolod": [
            "Alangilan", "Alijis", "Banago", "Bata", "Cabug", "Estefania", "Felisa", "Granada",
            "Handumanan", "Mandalagan", "Mansilingan", "Montevista", "Pahanocoy", "Punta Taytay",
            "Singcang-Airport", "Sum-ag", "Taculing", "Tangub", "Villamonte", "Vista Alegre",
            "Barangay 1", "Barangay 2", "Barangay 3", "Barangay 4", "Barangay 5", "Barangay 6",
            "Barangay 7", "Barangay 8", "Barangay 9", "Barangay 10", "Barangay 11", "Barangay 12",
            "Barangay 13", "Barangay 14", "Barangay 15", "Barangay 16", "Barangay 17", "Barangay 18",
            "Barangay 19", "Barangay 20", "Barangay 21", "Barangay 22", "Barangay 23", "Barangay 24",
            "Barangay 25", "Barangay 26", "Barangay 27", "Barangay 28", "Barangay 29",
            "Barangay 30", "Barangay 31", "Barangay 32", "Barangay 33", "Barangay 34",
            "Barangay 35", "Barangay 36", "Barangay 37", "Barangay 38", "Barangay 39",
            "Barangay 40", "Barangay 41"
        ],
        "Bago": [
            "Abuanan", "Alianza", "Atipuluan", "Bacong-Montilla", "Bagroy", "Balingasag",
            "Binubuhan", "Busay", "Calumangan", "Caridad", "Dulao", "Ilijan", "Lag-Asan",
            "Ma-ao", "Jorge L. Araneta", "Mailum", "Malingin", "Napoles", "Pacol", "Poblacion",
            "Sagasa", "Tabunan", "Taloc", "Sampinit"
        ],
        "Binalbagan": [
            "Amontay", "Bagroy", "Biao", "Bi-ao", "Canmoros", "Enclaro", "Marcong", "Paglaum",
            "Poblacion", "Progreso", "Remedios", "San Jose", "San Pedro", "San Teodoro",
            "San Vicente", "Santo Rosario", "Sibucao", "Tuyom"
        ],
        "Cadiz": [
            "Andres Bonifacio", "Banquerohan", "Barangay 1 Pob. (Zone 1)", "Barangay 2 Pob. (Zone 2)",
            "Barangay 3 Pob. (Zone 3)", "Barangay 4 Pob. (Zone 4)", "Barangay 5 Pob. (Zone 5)",
            "Barangay 6 Pob. (Zone 6)", "Burgos", "Cabahug", "Cadiz Viejo", "Caduha-an",
            "Celestino Villacin", "Daga", "V. F. Gustilo", "Jerusalem", "Luna", "Mabini",
            "Magsaysay", "Sicaba", "Tiglawigan", "Tinampa-an"
        ],
        "E.B. Magalona": [
            "Alacaygan", "Alicante", "Batea", "Canlusong", "Consing", "Cudangdang", "Damgo",
            "Gahit", "Latasan", "Madalag", "Manta-angan", "Nanca", "Pasil",
            "Poblacion I", "Poblacion II", "Poblacion III", "San Isidro", "San Jose",
            "Santo Niño", "Tabigue", "Tanza", "Tomongtong", "Tuburan"
        ],
        "Escalante": [
            "Alimango", "Balintawak", "Binaguiohan", "Buenavista", "Cervantes", "Dian-ay",
            "Hacienda Fe", "Japitan", "Jonobjonob", "Langub", "Libertad", "Mabini", "Magsaysay",
            "Malasibog", "Old Poblacion", "Paitan", "Pinapugasan", "Rizal", "Tamlang",
            "Udtongan", "Washington"
        ],
        "Hinigaran": [
            "Anahaw", "Aranda", "Baga-as", "Barangay I (Poblacion)", "Barangay II (Poblacion)",
            "Barangay III (Poblacion)", "Barangay IV (Poblacion)", "Bato", "Calapi",
            "Camalobalo", "Camba-og", "Cambugsa", "Candumarao", "Gargato", "Himaya",
            "Miranda", "Nanunga", "Narauis", "Palayog", "Paticui", "Pilar", "Quiwi",
            "Tagda", "Tuguis"
        ],
        "Manapla": [
            "Barangay I (Poblacion)", "Barangay I-A (Poblacion)", "Barangay I-B (Poblacion)",
            "Barangay I-C (Poblacion)", "Barangay II (Poblacion)", "Chamber", "Punta Mesa",
            "Punta Salong", "San Pablo", "Santa Teresa", "Purisima", "Tortosa"
        ],
        "Pontevedra": [
            "Antipolo", "Barangay I (Poblacion)", "Barangay II (Poblacion)", "Barangay III (Poblacion)",
            "Buenavista Gibong", "Buenavista Rizal", "Burgos", "Cambarus", "Canroma",
            "Don Salvador Benedicto", "General Malvar", "Gomez", "M. H. del Pilar",
            "Mabini", "Miranda", "Pandan", "Recreo", "San Isidro", "San Juan", "Zamora"
        ],
        "Sagay City": [
            "Andres Bonifacio", "Bato", "Baviera", "Bul anon", "Campo Himoga-an", "Campo Santiago",
            "Colonia Divina", "Fabrica", "General Luna", "Himoga-an Baybay", "Lopez Jaena",
            "Malubon", "Maquiling", "Molocaboc", "Old Sagay", "Paraiso", "Plaridel",
            "Poblacion I (Barangay 1)", "Poblacion II (Barangay 2)", "Puey", "Rafaela Barrera",
            "Rizal", "Taba-ao", "Tadlong", "Vito"
        ],
        "Pulupandan": [
            "Barangay Zone 1-A", "Barangay Zone 4-A", "Barangay Zone 1", "Barangay Zone 2",
            "Barangay Zone 3", "Barangay Zone 4", "Barangay Zone 5", "Barangay Zone 6",
            "Barangay Zone 7", "Canjusa", "Crossing Pulupandan", "Culo", "Mabini",
            "Pag-ayon", "Palaka Norte", "Palaka Sur", "Patic", "Tapong", "Ubay", "Utod"
        ],
        "San Enrique": [
            "Bagonawa", "Baliwagan", "Batuan", "Guintorilan", "Nayon", "Poblacion",
            "Sibucao", "Tabao Baybay", "Tabao Rizal", "Tibsok"
        ],
        "Silay": [
            "Balaring", "Bagtic", "Barangay I (Poblacion)", "Barangay II (Poblacion)",
            "Barangay III (Cinco de Noviembre)", "Barangay IV (Poblacion)",
            "Barangay V (Poblacion)", "Barangay VI (Poblacion – Hawaiian)",
            "Guinhalaran", "Mambulac", "Rizal", "Eustaquio Lopez", "Guimbala-on",
            "Kapitan Ramon", "Lantad", "Patag"
        ],
        "Talisay": [
            "Bubog", "Cabatangan", "Zone 4-A (Poblacion)", "Zone 4 (Poblacion)",
            "Concepcion", "Dos Hermanas", "Efigenio Lizares", "Zone 7 (Poblacion)",
            "Zone 14-B (Poblacion)", "Zone 12-A (Poblacion)", "Zone 10 (Poblacion)",
            "Zone 5 (Poblacion)", "Zone 16 (Poblacion)", "Matab-ang", "Zone 9 (Poblacion)",
            "Zone 6 (Poblacion)", "Zone 14 (Poblacion)", "San Fernando", "Zone 15 (Poblacion)",
            "Zone 14-A (Poblacion)", "Zone 11 (Poblacion)", "Zone 8 (Poblacion)",
            "Zone 12 (Poblacion)", "Zone 1 (Poblacion)", "Zone 2 (Poblacion)",
            "Zone 3 (Poblacion)", "Katilingban"
        ],
        "Valladolid": [
            "Alijis", "Ayungon", "Bagumbayan", "Batuan", "Bayabas", "Central Tabao",
            "Doldol", "Guintorilan", "Lacaron", "Mabini", "Pacol", "Palaka", "Paloma",
            "Poblacion", "Sagua Banua", "Tabao Proper"
        ],
        "Victorias": [
            "Barangay I (Poblacion)", "Barangay II (Quezon; Poblacion)",
            "Barangay III (Poblacion)", "Barangay IV (Poblacion)", "Barangay V (Poblacion)",
            "Barangay VI (Estrella Village/Salvacion; Poblacion)",
            "Barangay VI-A (Boulevard/Villa Miranda/Sitio Cubay/Pasil)",
            "Barangay VII (Poblacion)", "Barangay VIII (Old Simboryo)",
            "Barangay IX (Daan Banwa)", "Barangay X (Estado)", "Barangay XI (Gawahon)",
            "Barangay XII (Dacumon)", "Barangay XIII (Gloryville)", "Barangay XIV (Sayding)",
            "Barangay XV West Caticlan", "Barangay XV-A East Caticlan", "Barangay XVI (Millsite)",
            "Barangay XVI-A (New Barrio)", "Barangay XVII (Garden)", "Barangay XVIII (Palma)",
            "Barangay XVIII-A (Golf)", "Barangay XIX (Bacayan)",
            "Barangay XIX-A (Canetown Subdivision)", "Barangay XX (Cuaycong)",
            "Barangay XXI (Relocation)"
        ]
    };

    // --- Populate Barangays Function ---
    function populateBarangays(cityValue) {
        const barangaySelect = document.getElementById('barangay');
        if (!barangaySelect) return;
        
        barangaySelect.innerHTML = '<option value="">Select Barangay *</option>';
        if (cityValue && barangaysByCity[cityValue]) {
            barangaySelect.disabled = false;
            barangaysByCity[cityValue].forEach(barangay => {
                const option = document.createElement('option');
                option.value = barangay;
                option.textContent = barangay;
                barangaySelect.appendChild(option);
            });
        } else {
            barangaySelect.disabled = true;
        }
    }

    // --- Validation Functions ---
    function validateName(value) {
        const isValid = value.trim().length >= 2;
        return {
            isValid,
            message: isValid ? '' : 'Name must be at least 2 characters'
        };
    }

    function validateEmail(value) {
        const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        const isValid = re.test(value);
        return {
            isValid,
            message: isValid ? '' : 'Please enter a valid email address'
        };
    }

    function validatePhone(value) {
        const re = /^[0-9]{10,11}$/;
        const isValid = re.test(value);
        return {
            isValid,
            message: isValid ? '' : 'Please enter a valid phone number (10-11 digits)'
        };
    }

    function validateZipCode(value) {
        const isValid = /^\d{4}$/.test(value);
        return {
            isValid,
            message: isValid ? '' : 'Zip code must be 4 digits'
        };
    }

    function validateDate(value) {
        const selectedDate = new Date(value);
        // Check if the date is valid
        if (isNaN(selectedDate.getTime())) {
            return {
                isValid: false,
                message: 'Invalid date format'
            };
        }

        // Minimum selectable date is day-after-tomorrow (disable today and tomorrow)
        const minSelectable = new Date();
        minSelectable.setHours(0, 0, 0, 0);
        minSelectable.setDate(minSelectable.getDate() + 2);

        // Compare dates without time component
        const isValid = selectedDate.setHours(0, 0, 0, 0) >= minSelectable.getTime();
        return {
            isValid,
            message: isValid ? '' : 'Date must be at least 2 days from today'
        };
    }

    function updateCompleteAddress() {
        const block = document.getElementById('block')?.value || '';
        const lot = document.getElementById('lot')?.value || '';
        const street = document.getElementById('street')?.value || '';
        const subdivision = document.getElementById('subdivision')?.value || '';
        const city = document.getElementById('city')?.value || '';
        const barangay = document.getElementById('barangay')?.value || '';
        const zip = document.getElementById('zip_code')?.value || '';
        
        const address = [
            block && `Block: ${block}`,
            lot && `Lot: ${lot}`,
            street && `Street: ${street}`,
            subdivision && `Subdivision: ${subdivision}`,
            barangay && `Barangay: ${barangay}`,
            city && `City: ${city}`,
            zip && `Zip: ${zip}`
        ].filter(Boolean).join(', ');
        
        const addressField = document.getElementById('address');
        if (addressField) addressField.value = address;
    }

    // Function to handle form errors (consolidated)
    function handleFormErrors(errors, formType = 'profile') {
        if (formType === 'booking') {
            const errorList = document.getElementById('validationErrorList');
            if (errorList) {
                errorList.innerHTML = ''; // Clear previous errors
                for (const field in errors) {
                    errors[field].forEach(errorMessage => {
                        const li = document.createElement('li');
                        li.textContent = errorMessage;
                        errorList.appendChild(li);
                    });
                }
                const validationErrorModal = new bootstrap.Modal(document.getElementById('validationErrorModal'));
                validationErrorModal.show();
            }
        }

        Object.keys(errors).forEach(function(field) {
            const errorElement = document.getElementById('error-' + field);
            let inputElement;
            if (formType === 'profile' && (field === 'name' || field === 'email')) {
                inputElement = document.getElementById('profile_' + field);
            } else {
                inputElement = document.getElementById(field);
            }
            if (errorElement) errorElement.innerText = errors[field][0];
            if (inputElement) inputElement.classList.add('is-invalid');
        });
    }

    // --- Field Validations ---
    const fields = {
        'name': { validate: validateName, required: true },
        'email': { validate: validateEmail, required: true },
        'contact': { validate: validatePhone, required: true },
        'street': { 
            validate: value => ({ 
                isValid: value.trim().length > 0, 
                message: 'Street Name is required' 
            }), 
            required: true 
        },
        'city': { 
            validate: value => ({ 
                isValid: value && value.trim().length > 0, 
                message: 'Please select a City/Municipality' 
            }), 
            required: true 
        },
        'barangay': { 
            validate: value => ({ 
                isValid: value && value.trim().length > 0, 
                message: 'Please select a Barangay' 
            }), 
            required: true 
        },
        'zip_code': { validate: validateZipCode, required: true },
        'cleaning_date': { validate: validateDate, required: true }
    };

    function validateField(field, validator) {
        if (!field) return false;
        
        const feedbackDiv = field.nextElementSibling;
        const result = validator.validate(field.value);
        
        if (!field.value.trim() && validator.required) {
            field.classList.add('is-invalid');
            if (feedbackDiv?.classList?.contains('invalid-feedback')) {
                feedbackDiv.textContent = `${field.placeholder || field.id} is required`;
            }
            return false;
        } else if (!result.isValid) {
            field.classList.add('is-invalid');
            if (feedbackDiv?.classList?.contains('invalid-feedback')) {
                feedbackDiv.textContent = result.message;
            }
            return false;
        } else {
            field.classList.remove('is-invalid');
            if (feedbackDiv?.classList?.contains('invalid-feedback')) {
                feedbackDiv.textContent = '';
            }
            return true;
        }
    }

    // Initialize field validations
    for (const [fieldId, validator] of Object.entries(fields)) {
        const field = document.getElementById(fieldId);
        if (field) {
            field.addEventListener('input', function() {
                validateField(this, validator);
                if (addressFields.includes(fieldId)) updateCompleteAddress();
            });
            field.addEventListener('blur', function() {
                validateField(this, validator);
            });
        }
    }

    // --- Address Fields Handling ---
    const addressFields = ['block', 'lot', 'street', 'subdivision', 'barangay', 'city', 'zip_code'];
    addressFields.forEach(fieldId => {
        const field = document.getElementById(fieldId);
        if (field) {
            field.addEventListener('input', updateCompleteAddress);
            field.addEventListener('blur', updateCompleteAddress);
        }
    });

    // City/Barangay event listeners
    const citySelect = document.getElementById('city');
    const barangaySelect = document.getElementById('barangay');
    
    if (citySelect) {
        citySelect.addEventListener('change', function() {
            populateBarangays(this.value);
            validateField(this, fields['city']);
            updateCompleteAddress();
            // Update fuel charge note visibility when city changes
            updateFuelChargeNote();
        });
    }
    
    if (barangaySelect) {
        barangaySelect.addEventListener('change', function() {
            validateField(this, fields['barangay']);
            updateCompleteAddress();
        });
    }

    // --- Booking Form Submission ---
    const bookingForm = document.getElementById('bookingForm');
    if (bookingForm) {
        bookingForm.addEventListener('submit', function(e) {
            logDebug('Booking form submit handler called!');
            logDebug('Booking form submit event triggered.');

            // Allow normal form submission instead of AJAX
            // This will let the form submit to the server normally
            return true;
        });
    }
});