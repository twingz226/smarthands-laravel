@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Reschedule Booking</div>

                <div class="card-body">
                    <p>Current Cleaning Date: {{ $booking->cleaning_date->format('Y-m-d') }}</p>

                    <form id="rescheduleBookingForm" method="POST" action="{{ route('bookings.reschedule', $booking->booking_token) }}">
                        @csrf


                        <div class="form-group mb-3">
                            <label for="new_cleaning_date">New Cleaning Date:</label>
                            <input type="date" class="form-control" id="new_cleaning_date" name="new_cleaning_date" min="{{ date('Y-m-d') }}" required>
                        </div>

                        <div class="form-group mb-3">
                            <label for="new_time">New Cleaning Time:</label>
                            <select id="new_time" name="new_time" class="form-select" required>
                                <option value="">Select Time</option>
                                <option value="09:00">9:00 AM</option>
                                <option value="10:00">10:00 AM</option>
                                <option value="11:00">11:00 AM</option>
                                <option value="12:00">12:00 PM</option>
                                <option value="13:00">1:00 PM</option>
                                <option value="14:00">2:00 PM</option>
                                <option value="15:00">3:00 PM</option>
                            </select>
                        </div>

                        <div class="form-group mb-3">
                            <label for="reason">Reason for Rescheduling (Optional):</label>
                            <textarea class="form-control" id="reason" name="reason" rows="3" placeholder="Please let us know why you need to reschedule..."></textarea>
                        </div>

                        <button type="submit" class="btn btn-primary">Reschedule</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.getElementById('rescheduleBookingForm').addEventListener('submit', function(event) {
        event.preventDefault(); // Prevent default form submission

        const dateInput = document.getElementById('new_cleaning_date').value;
        const timeInput = document.getElementById('new_time').value;

        if (dateInput && timeInput) {
            const combinedDateTime = dateInput + ' ' + timeInput;

            // Create a hidden input field to send the combined date and time
            const hiddenInput = document.createElement('input');
            hiddenInput.type = 'hidden';
            hiddenInput.name = 'new_cleaning_date'; // Name must match the backend's expected parameter
            hiddenInput.value = combinedDateTime;

            // Append the hidden input to the form
            this.appendChild(hiddenInput);

            // Create FormData object
            const formData = new FormData(this);

            // Ensure _method field is not 'PUT' as the route is POST
            if (formData.has('_method') && formData.get('_method') === 'PUT') {
                formData.delete('_method');
            }

            // Send the form data using fetch API
            fetch(this.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest' // Indicate an AJAX request
                }
            })
            .then(response => {
                // Check if the response is a redirect
                if (response.redirected) {
                    window.location.href = response.url; // Follow the redirect
                } else if (response.ok) {
                    // Handle successful response (e.g., show success message, redirect)
                    return response.json(); // Or response.text() depending on backend
                } else {
                    // Handle errors (e.g., show error message)
                    return response.json().then(errorData => {
                        throw new Error(errorData.message || 'Form submission failed.');
                    });
                }
            })
            .then(data => {
                // If not redirected, process JSON response (e.g., for API calls)
                console.log('Success:', data);
                alert('Booking rescheduled successfully!');
                // Optionally, redirect or update UI based on data
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error rescheduling booking: ' + error.message);
            });
        } else {
            alert('Please select both a new cleaning date and time.');
        }
    });
</script>
@endsection
