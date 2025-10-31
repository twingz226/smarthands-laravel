@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card mt-4">
                <div class="card-header">
                    <div class="card-tools">
                        <a href="{{ route('bookings.index') }}" class="btn btn-sm btn-secondary">
                            <i class="fas fa-arrow-left"></i> Back
                        </a>
                    </div>
                    Reschedule Booking
                </div>
                <div class="card-body">
                    <p><strong>Current Scheduled Date:</strong> {{ $booking->cleaning_date->format('M d, Y h:i A') }}</p>
                    <form method="POST" action="{{ route('bookings.admin.update-reschedule', ['booking' => $booking->id]) }}">
                        @csrf
                        @method('PATCH')
                        <div class="row g-2 mb-3">
                            <div class="col-md-2">
                                <label for="new_cleaning_date" class="form-label fw-bold">Select new date:</label>
                                <input type="text" class="form-control @error('new_cleaning_date') is-invalid @enderror" id="new_cleaning_date" name="new_cleaning_date" placeholder="Select new date" required>
                                <div id="fullyBookedAlert" class="alert alert-warning mt-2" role="alert" style="display:none !important;">
                                    <i class="fas fa-exclamation-triangle"></i> This date is fully booked. Please select another date.
                                </div>
                                <div id="loadingDates" class="mt-2 text-info" style="display:none !important;">
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
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize Flatpickr for date selection
        flatpickr("#new_cleaning_date", {
            enableTime: true,
            dateFormat: "Y-m-d H:i",
            minDate: "today",
            time_24hr: false,
            disable: [],
            onChange: function(selectedDates, dateStr, instance) {
                // Update the time options when date changes
                updateTimeOptions();
            }
        });

        // Initialize Select2 for employee selection
        $('#new_employee_ids').select2({
            theme: 'bootstrap4',
            placeholder: 'Select employees...',
            allowClear: true
        });

        // Function to update time options based on selected date
        function updateTimeOptions() {
            // Implementation would go here
        }

        // Handle form submission
        document.getElementById('rescheduleForm').addEventListener('submit', function(e) {
            e.preventDefault();

            // Get form data
            const formData = new FormData(this);

            // Show loading state
            const submitBtn = this.querySelector('button[type="submit"]');
            const originalText = submitBtn.innerHTML;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Rescheduling...';
            submitBtn.disabled = true;

            // Submit form
            fetch(this.action, {
                method: this.method,
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Show success message and redirect
                    alert('Booking rescheduled successfully!');
                    window.location.href = data.redirect;
                } else {
                    // Show error message
                    alert('Error rescheduling booking: ' + data.message);
                    submitBtn.innerHTML = originalText;
                    submitBtn.disabled = false;
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred while rescheduling the booking.');
                submitBtn.innerHTML = originalText;
                submitBtn.disabled = false;
            });
        });
    });
</script>
@endpush