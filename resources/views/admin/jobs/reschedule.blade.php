
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
                    <p><strong>Current Scheduled Date:</strong> {{ $job->scheduled_date->format('M d, Y h:i A') }}</p>
                    <form method="POST" action="{{ route('jobs.update-reschedule', $job->id) }}">
                        @csrf
                        @method('PUT')
                        <div class="row g-2 mb-3">
                            <div class="col-md-2">
                                <label for="new_cleaning_date" class="form-label fw-bold">Select new date:</label>
                                <input type="text" class="form-control @error('new_cleaning_date') is-invalid @enderror" id="new_cleaning_date" name="new_cleaning_date" placeholder="Select new date" required>
                                <div id="fullyBookedAlert" class="alert alert-warning mt-2" role="alert" style="display:none;">
                                    <i class="fas fa-exclamation-triangle"></i> This date is fully booked. Please select another date.
                                </div>
                                <div id="loadingDates" class="mt-2 text-info" style="display:none;">
                                    <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Loading available dates...
                                </div>
                                @error('new_cleaning_date')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="new_cleaning_time" class="form-label fw-bold">Select new time:</label>
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
                                @error('new_cleaning_time')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
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
document.addEventListener('DOMContentLoaded', async function() {
    const newCleaningDateInput = document.getElementById('new_cleaning_date');
    const newCleaningTimeSelect = document.getElementById('new_cleaning_time');

    let fullyBookedDates = [];
    let fullyBookedTimes = {};

    async function fetchFullyBookedData() {
        try {
            const response = await fetch("{{ route('fully.booked.dates') }}");
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            const data = await response.json();
            fullyBookedDates = data.fullyBookedDates || [];
            fullyBookedTimes = data.fullyBookedTimes || {};
        } catch (error) {
            console.error('Error fetching fully booked dates:', error);
        }
    }

    // Initialize Flatpickr
    if (newCleaningDateInput) {
        await fetchFullyBookedData();

        flatpickr(newCleaningDateInput, {
            dateFormat: "Y-m-d",
            minDate: "today",
            disable: [function(date) {
                const formattedDate = flatpickr.formatDate(date, "Y-m-d");
                return fullyBookedDates.includes(formattedDate);
            }],
            onChange: function(selectedDates, dateStr, instance) {
                if (selectedDates.length > 0) {
                    const selectedDay = dateStr;
                    const timesForSelectedDay = fullyBookedTimes[selectedDay] || [];

                    // Enable all time options first
                    Array.from(newCleaningTimeSelect.options).forEach(option => {
                        if (option.value !== "") {
                            option.disabled = false;
                        }
                    });

                    // Disable times that are fully booked for the selected day
                    timesForSelectedDay.forEach(time => {
                        const option = newCleaningTimeSelect.querySelector(`option[value="${time}"]`);
                        if (option) {
                            option.disabled = true;
                        }
                    });

                    // If the currently selected time is disabled, reset to default
                    if (newCleaningTimeSelect.value && newCleaningTimeSelect.options[newCleaningTimeSelect.selectedIndex].disabled) {
                        newCleaningTimeSelect.value = "";
                    }
                }
            }
        });

        // Set initial date if available
        const initialDateValue = "{{ old('new_cleaning_date', $job->scheduled_date->format('Y-m-d')) }}";
        if (initialDateValue) {
            newCleaningDateInput.value = initialDateValue;
        }

        // Set the initial value of the time select from the backend (if any)
        const initialTime = "{{ old('new_cleaning_time', $job->scheduled_date->format('H:i')) }}";
        if (initialTime) {
            newCleaningTimeSelect.value = initialTime;
        }

        // Refresh disabled dates when focusing input
        newCleaningDateInput.addEventListener('focus', async () => {
            await fetchFullyBookedData();
            if (fpInstance) {
                fpInstance.set('disable', [function(date) { return fullyBookedDates.includes(flatpickr.formatDate(date, 'Y-m-d')); }]);
                fpInstance.redraw();
            }
        });
    });
});
</script>
@endpush