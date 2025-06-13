@include('admin.partials.header')

<!-- Main Content -->
<div class="container-fluid py-4 px-5">
    <!-- Welcome Banner -->
    <div class="row">
        <div class="col-sm-12">
            <div class="well">
                <h3>Welcome to <strong>Smarthands Cleaning Service Management System</strong></h3>
            </div>
        </div>
    </div>

    <!-- Dashboard Cards -->
    <div class="row">
        <!-- Upcoming Cleaning Card -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    Upcoming Cleaning
                </div>
                <div class="card-body">
                    @if($nextBooking)
                        <h5>{{ $nextBooking->service->name }}</h5>
                        <p><strong>Date:</strong> {{ $nextBooking->cleaning_date->format('l, F j, Y \a\t g:i A') }}</p>
                        <p><strong>Cleaner:</strong> {{ $nextBooking->cleaner->name ?? 'Not assigned yet' }}</p>
                        <a href="#" class="btn btn-sm btn-warning">Reschedule</a>
                    @else
                        <p>No upcoming bookings</p>
                       
                    @endif
                </div>
            </div>
        </div>
        
        <!-- Recent Activity Card -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-header bg-success text-white">
                    Recent Activity
                </div>
                <div class="card-body">
                    <ul class="list-group list-group-flush">
                        @foreach($recentActivities as $activity)
                            <li class="list-group-item">{{ $activity->description }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Calendar Section -->
    <div class="card mt-4">
        <div class="card-header bg-info text-white">
            Cleaning Calendar
        </div>
        <div class="card-body">
            <div id="calendar"></div>
        </div>
    </div>

    <!-- Tab Content -->
    <div class="tab-content mt-4">
        <!-- Bookings Tab -->
        <div class="tab-pane fade" id="bookings">
            <h2 class="mb-4">Your Bookings</h2>
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Service</th>
                            <th>Status</th>
                            <th>Cleaner</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($bookings as $booking)
                            <tr>
                                <td>{{ $booking->cleaning_date->format('M j, Y g:i A') }}</td>
                                <td>{{ $booking->service->name }}</td>
                                <td><span class="badge bg-{{ $booking->status == 'confirmed' ? 'success' : 'warning' }}">{{ ucfirst($booking->status) }}</span></td>
                                <td>{{ $booking->cleaner->name ?? 'Not assigned' }}</td>
                                <td>
                                    <button class="btn btn-sm btn-outline-primary">Details</button>
                                    <button class="btn btn-sm btn-outline-warning">Reschedule</button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Quick Booking Tab -->
        <div class="tab-pane fade" id="quick-booking">
            <h2 class="mb-4">Book a Cleaning</h2>
            <div class="row">
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-body">
                            <form action="/" method="POST">
                                @csrf
                                <div class="mb-3">
                                    <label for="service" class="form-label">Service Type</label>
                                    <select class="form-select" id="service" name="service_id" required>
                                        <option value="">Select a service</option>
                                        @foreach($services as $service)
                                            <option value="{{ $service->id }}">{{ $service->name }} - ${{ $service->price }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="date" class="form-label">Date & Time</label>
                                    <input type="datetime-local" class="form-control" id="date" name="cleaning_date" required>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="notes" class="form-label">Special Instructions</label>
                                    <textarea class="form-control" id="notes" name="notes" rows="3"></textarea>
                                </div>
                                
                                <button type="submit" class="btn btn-primary">Book Now</button>
                            </form>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            Available Time Slots
                        </div>
                        <div class="card-body">
                            <div id="time-slots">
                                <p class="text-muted">Select a date to see available time slots</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
<!-- Scripts -->
@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.js"></script>
<script>
    // Initialize calendar
    document.addEventListener('DOMContentLoaded', function() {
        var calendarEl = document.getElementById('calendar');
        var calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth',
            events: @json($calendarEvents),
            eventClick: function(info) {
                alert('Booking: ' + info.event.title);
            }
        });
        calendar.render();
    });

    // Time slot loading (example)
    document.getElementById('date').addEventListener('change', function() {
        const date = this.value;
        if (date) {
            document.getElementById('time-slots').innerHTML = `
                <div class="d-flex justify-content-center">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </div>`;
            
            // Simulate AJAX call
            setTimeout(() => {
                document.getElementById('time-slots').innerHTML = `
                    <div class="btn-group-vertical w-100">
                        <button type="button" class="btn btn-outline-primary mb-2">9:00 AM - 11:00 AM</button>
                        <button type="button" class="btn btn-outline-primary mb-2">1:00 PM - 3:00 PM</button>
                        <button type="button" class="btn btn-outline-primary">4:00 PM - 6:00 PM</button>
                    </div>`;
            }, 800);
        }
    });
</script>
@endpush

@include('admin.partials.scripts')