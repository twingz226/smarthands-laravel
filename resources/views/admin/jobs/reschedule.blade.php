
@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card mt-4">
                <div class="card-header">
                    <div class="card-tools">
                        <a href="{{ route('jobs.tracking') }}" class="btn btn-sm btn-secondary">
                            <i class="fas fa-arrow-left"></i> Back
                        </a>
                    </div>
                    Reschedule Job
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Service:</label>
                        <p class="text-muted">{{ $job->booking->service->name }}</p>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Current Date:</label>
                        <p class="text-muted">{{ $job->scheduled_date->format('Y-m-d') }}</p>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Current Time:</label>
                        <p class="text-muted">{{ $job->scheduled_date->format('g:i A') }}</p>
                    </div>
                    <form method="POST" action="{{ route('jobs.update-reschedule', $job->id) }}">
                        @csrf
                        @method('PUT')
                        <div class="mb-3">
                            <label class="form-label fw-bold">New Date and Time:</label>
                            <div class="row g-2">
                                <div class="col-md-6">
                                    <input type="text" class="form-control flatpickr-input @error('new_cleaning_date') is-invalid @enderror" id="new_cleaning_date" name="new_cleaning_date" placeholder="Select New Cleaning Date" required readonly>
                                    <div class="form-text">Select new date</div>
                                    <div id="fullyBookedAlert" class="alert alert-warning mt-2" role="alert" style="display:none !important;">
                                        <i class="bi bi-exclamation-triangle-fill"></i> This date is fully booked. Please select another date.
                                    </div>
                                    <div id="loadingDates" class="mt-2 text-info" style="display:none !important;">
                                        <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Loading available dates...
                                    </div>
                                    @error('new_cleaning_date')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <select id="new_cleaning_time" name="new_cleaning_time" class="form-select @error('new_cleaning_time') is-invalid @enderror" required>
                                        <option value="">Select Time</option>
                                        <option value="09:00">9:00 AM</option>
                                        <option value="10:00">10:00 AM</option>
                                        <option value="11:00">11:00 AM</option>
                                        <option value="12:00">12:00 PM</option>
                                        <option value="13:00">1:00 PM</option>
                                        <option value="14:00">2:00 PM</option>
                                        <option value="15:00">3:00 PM</option>
                                    </select>
                                    <div class="form-text">Select new time</div>
                                    @error('new_cleaning_time')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="rescheduleReason" class="form-label">Reason for Rescheduling (Optional):</label>
                            <textarea class="form-control" id="rescheduleReason" name="reason" rows="3" placeholder="Please let us know why you need to reschedule..."></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary mt-2">Reschedule</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Load fully booked dates and initialize Flatpickr
        fetch('{{ route("bookings.fully.booked.dates.json") }}')
            .then(response => response.json())
            .then(fullyBookedDates => {
                // Initialize Flatpickr with disabled dates
                flatpickr("#new_cleaning_date", {
                    enableTime: false,
                    dateFormat: "Y-m-d",
                    minDate: "today",
                    disable: fullyBookedDates,
                    onChange: function(selectedDates, dateStr, instance) {
                        // Check if selected date is fully booked
                        if (fullyBookedDates.includes(dateStr)) {
                            document.getElementById('fullyBookedAlert').style.display = 'block';
                        } else {
                            document.getElementById('fullyBookedAlert').style.display = 'none';
                        }
                        // Update the time options when date changes
                        updateTimeOptions();
                    },
                    onDayCreate: function(dObj, dStr, fp, dayElem) {
                        // Add styling for fully booked dates
                        if (fullyBookedDates.includes(dStr)) {
                            dayElem.classList.add('fully-booked');
                        }
                    }
                });
            })
            .catch(error => {
                console.error('Error loading fully booked dates:', error);
                // Fallback: initialize Flatpickr without disabled dates
                flatpickr("#new_cleaning_date", {
                    enableTime: false,
                    dateFormat: "Y-m-d",
                    minDate: "today",
                    onChange: function(selectedDates, dateStr, instance) {
                        updateTimeOptions();
                    }
                });
            });

        // Function to update time options based on selected date
        function updateTimeOptions() {
            const selectedDate = document.getElementById('new_cleaning_date').value;
            const timeSelect = document.getElementById('new_cleaning_time');
            
            if (!selectedDate) {
                // Reset time options if no date selected
                resetTimeOptions();
                return;
            }

            // Show loading state
            document.getElementById('loadingDates').style.display = 'block';
            
            // Fetch booked time slots for the selected date
            fetch(`{{ route('bookings.booked.time.slots') }}?date=${encodeURIComponent(selectedDate)}`)
                .then(response => response.json())
                .then(bookedTimeSlots => {
                    // Reset all time options first
                    resetTimeOptions();
                    
                    // Disable booked time slots
                    bookedTimeSlots.forEach(bookedTime => {
                        const option = timeSelect.querySelector(`option[value="${bookedTime}"]`);
                        if (option) {
                            option.disabled = true;
                            option.textContent = option.textContent + ' (Booked)';
                        }
                    });
                    
                    // Hide loading state
                    document.getElementById('loadingDates').style.display = 'none';
                    
                    // Show warning if all time slots are booked
                    const availableOptions = Array.from(timeSelect.options).filter(option => !option.disabled && option.value);
                    if (availableOptions.length === 0) {
                        alert('All time slots are booked for this date. Please select another date.');
                    }
                })
                .catch(error => {
                    console.error('Error loading booked time slots:', error);
                    document.getElementById('loadingDates').style.display = 'none';
                    // Fallback: enable all time slots
                    resetTimeOptions();
                });
        }

        // Function to reset time options to default state
        function resetTimeOptions() {
            const timeSelect = document.getElementById('new_cleaning_time');
            const allOptions = timeSelect.querySelectorAll('option');
            
            const timeLabels = {
                '09:00': '9:00 AM',
                '10:00': '10:00 AM', 
                '11:00': '11:00 AM',
                '12:00': '12:00 PM',
                '13:00': '1:00 PM',
                '14:00': '2:00 PM',
                '15:00': '3:00 PM'
            };
            
            allOptions.forEach(option => {
                if (option.value) {
                    option.disabled = false;
                    // Reset text to original format
                    option.textContent = timeLabels[option.value] || option.value;
                }
            });
        }

        // Handle form submission
        document.querySelector('form').addEventListener('submit', function(e) {
            // Let the form submit normally for server-side processing
            // Remove AJAX handling to match standard Laravel form submission
        });
    });
</script>

<style>
/* Style for fully booked dates in calendar */
.flatpickr-calendar .fully-booked {
    background-color: #ffebee !important;
    color: #d32f2f !important;
    cursor: not-allowed !important;
    text-decoration: line-through !important;
}

.flatpickr-calendar .fully-booked:hover {
    background-color: #ffcdd2 !important;
}

/* Style for booked time slots */
select#new_cleaning_time option:disabled {
    color: #999 !important;
    background-color: #f5f5f5 !important;
    font-style: italic !important;
    cursor: not-allowed !important;
}

select#new_cleaning_time option:not(:disabled):hover {
    background-color: #e3f2fd !important;
}
</style>
@endpush