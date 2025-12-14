@auth
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<style>
    /* Custom Flatpickr styles for a more colorful and user-friendly calendar */
    .flatpickr-calendar {
        border-radius: 8px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
        font-family: 'Arial', sans-serif;
    }

    .flatpickr-months .flatpickr-month {
        background-color: #ffc044;
        color: white;
        fill: white;
    }

    .flatpickr-current-month .flatpickr-monthDropdown-months .flatpickr-monthItem.active,
    .flatpickr-current-month .flatpickr-monthDropdown-months .flatpickr-monthItem:hover {
        background-color: #0056b3;
    }

    .flatpickr-current-month .flatpickr-monthDropdown-months .flatpickr-monthItem {
        color: #333;
    }

    .flatpickr-day.selected,
    .flatpickr-day.startRange,
    .flatpickr-day.endRange,
    .flatpickr-day.selected.inRange,
    .flatpickr-day.startRange.inRange,
    .flatpickr-day.endRange.inRange,
    .flatpickr-day.selected:focus,
    .flatpickr-day.startRange:focus,
    .flatpickr-day.endRange:focus,
    .flatpickr-day.selected:hover,
    .flatpickr-day.startRange:hover,
    .flatpickr-day.endRange:hover,
    .flatpickr-day.selected.prevMonthDay,
    .flatpickr-day.startRange.prevMonthDay,
    .flatpickr-day.endRange.prevMonthDay,
    .flatpickr-day.selected.nextMonthDay,
    .flatpickr-day.startRange.nextMonthDay,
    .flatpickr-day.endRange.nextMonthDay {
        background-color: #007bff;
        border-color: #007bff;
        color: white;
    }

    .flatpickr-day.today {
        border-color: #007bff;
        color: #007bff;
    }

    .flatpickr-day.today:hover,
    .flatpickr-day.today.selected {
        background-color:rgb(255, 166, 0);
        color: white;
    }

    .flatpickr-day.disabled,
    .flatpickr-day.disabled:hover {
        color: #ccc;
        cursor: not-allowed;
    }

    .flatpickr-day.fully-booked-date {
        background-color: #ffcccc; /* Light red background */
        color: #cc0000; /* Darker red text */
        text-decoration: line-through;
        cursor: not-allowed;
    }

    .flatpickr-day.fully-booked-date:hover {
        background-color: #ff9999; /* Slightly darker red on hover */
    }

    .flatpickr-day.inRange {
        background-color: #e6f2ff; /* Light blue for range selection */
        border-color: #e6f2ff;
    }

    .flatpickr-day.flatpickr-disabled {
        color: #d3d3d3;
        cursor: not-allowed;
    }

    .flatpickr-day.flatpickr-disabled:hover {
        background-color: transparent;
    }

    .flatpickr-weekdays {
        background-color: #f8f9fa;
    }

    .flatpickr-weekday {
        color: #555;
        font-weight: bold;
    }

    .flatpickr-time {
        border-top: 1px solid #eee;
    }

    .flatpickr-time input.flatpickr-hour,
    .flatpickr-time input.flatpickr-minute,
    .flatpickr-time input.flatpickr-second {
        font-weight: bold;
    }

    .flatpickr-time .flatpickr-am-pm {
        font-weight: bold;
        color:rgb(255, 145, 0);
    }
    
    .reschedule-btn, .cancel-btn {
        color: #000 !important;
        border: none !important;
    }
    
    .reschedule-btn:hover {
        background-color: #ffc044 !important;
        color: #000 !important;
    }
    
    .cancel-btn:hover {
        background-color: #dc3545 !important;
        color: #fff !important;
    }
</style>
<div class="container py-5">
    <h2 class="mb-4"><i class="bi bi-journal-check me-2"></i>My Bookings</h2>
    @php
        $bookings = $user->bookings;
        $pending = $bookings->where('status', 'pending');
        $confirmed = $bookings->where('status', 'confirmed');
        $rescheduled = $bookings->where('status', 'rescheduled');
        $completed = $bookings->where('status', 'completed');
        $cancelled = $bookings->where('status', 'cancelled');
    @endphp
    @if($user->bookings && $user->bookings->count())
        @if($pending->count())
            <h4 class="mt-4 mb-2 text-warning"><i class="bi bi-hourglass-split me-1"></i> Pending Bookings</h4>
            <div class="table-responsive mb-4">
                <table class="table table-bordered align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Date & Time</th>
                            <th>Service</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($pending as $booking)
                            <tr>
                                <td>{{ $booking->cleaning_date->format('M d, Y h:i A') }}</td>
                                <td>{{ $booking->service->name ?? 'N/A' }}</td>
                                <td><span class="badge bg-warning">Pending</span></td>
                                <td>
                                    @if ($booking->customer_reschedule_count < 3)
                                         <button type="button" class="btn btn-sm btn-outline-info me-2 reschedule-btn" 
                                                 data-booking-id="{{ $booking->id }}" 
                                                 data-booking-token="{{ $booking->booking_token }}" 
                                                 data-current-date="{{ $booking->cleaning_date->format('Y-m-d') }}" 
                                                 data-current-time="{{ $booking->cleaning_date->format('H:i') }}" 
                                                 data-service-name="{{ $booking->service->name ?? 'N/A' }}">
                                             <i class="bi bi-calendar-event me-1"></i>Reschedule
                                          </button>
                                     @else
                                         <span class="text-muted">Reschedule limit reached ({{ $booking->customer_reschedule_count }} times)</span>
                                     @endif
                                     <button type="button" class="btn btn-sm btn-outline-danger cancel-btn" 
                                              data-booking-id="{{ $booking->id }}" 
                                              data-booking-token="{{ $booking->booking_token }}" 
                                              data-service-name="{{ $booking->service->name ?? 'N/A' }}">
                                         <i class="bi bi-x-circle me-1"></i>Cancel
                                     </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
        @if($confirmed->count())
            <h4 class="mt-4 mb-2 text-success"><i class="bi bi-check-circle me-1"></i> Confirmed Bookings</h4>
            <div class="table-responsive mb-4">
                <table class="table table-bordered align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Date & Time</th>
                            <th>Service</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($confirmed as $booking)
                            <tr>
                                <td>{{ $booking->cleaning_date->format('M d, Y h:i A') }}</td>
                                <td>{{ $booking->service->name ?? 'N/A' }}</td>
                                <td><span class="badge bg-success">Confirmed</span></td>
                                <td>
                                    @if ($booking->customer_reschedule_count < 3)
                                         <button type="button" class="btn btn-sm btn-outline-info me-2 reschedule-btn" 
                                                 data-booking-id="{{ $booking->id }}" 
                                                 data-booking-token="{{ $booking->booking_token }}" 
                                                 data-current-date="{{ $booking->cleaning_date->format('Y-m-d') }}" 
                                                 data-current-time="{{ $booking->cleaning_date->format('H:i') }}" 
                                                 data-service-name="{{ $booking->service->name ?? 'N/A' }}">
                                             <i class="bi bi-calendar-event me-1"></i>Reschedule
                                         </button>
                                     @else
                                         <span class="text-muted">Reschedule limit reached ({{ $booking->customer_reschedule_count }} times)</span>
                                     @endif
                                     <button type="button" class="btn btn-sm btn-outline-danger cancel-btn" 
                                              data-booking-id="{{ $booking->id }}" 
                                              data-booking-token="{{ $booking->booking_token }}" 
                                              data-service-name="{{ $booking->service->name ?? 'N/A' }}">
                                         <i class="bi bi-x-circle me-1"></i>Cancel
                                     </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
        @if($rescheduled->count())
            <h4 class="mt-4 mb-2 text-primary"><i class="bi bi-arrow-repeat me-1"></i> Rescheduled Bookings</h4>
            <div class="table-responsive mb-4">
                <table class="table table-bordered align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Date & Time</th>
                            <th>Service</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($rescheduled as $booking)
                            <tr>
                                <td>{{ $booking->cleaning_date->format('M d, Y h:i A') }}</td>
                                <td>{{ $booking->service->name ?? 'N/A' }}</td>
                                <td><span class="badge bg-primary">Rescheduled</span></td>
                                <td>
                                    @if ($booking->customer_reschedule_count < 3)
                                         <button type="button" class="btn btn-sm btn-outline-info me-2 reschedule-btn" 
                                                 data-booking-id="{{ $booking->id }}" 
                                                 data-booking-token="{{ $booking->booking_token }}" 
                                                 data-current-date="{{ $booking->cleaning_date->format('Y-m-d') }}" 
                                                 data-current-time="{{ $booking->cleaning_date->format('H:i') }}" 
                                                 data-service-name="{{ $booking->service->name ?? 'N/A' }}">
                                             <i class="bi bi-calendar-event me-1"></i>Reschedule
                                         </button>
                                         <span class="text-muted ms-2">Customer rescheduled ({{ $booking->customer_reschedule_count }} {{ Str::plural('time', $booking->customer_reschedule_count) }})</span>
                                     @else
                                         <span class="text-muted">Reschedule limit reached ({{ $booking->customer_reschedule_count }} times)</span>
                                     @endif
                                    @if($booking->reschedule_reason)
                                        <div class="mt-1">
                                            <small class="text-info">
                                                <i class="bi bi-info-circle me-1"></i>
                                                <strong>Reason:</strong> {{ $booking->reschedule_reason }}
                                            </small>
                                        </div>
                                    @else
                                        <div class="mt-1">
                                            <small class="text-muted">
                                                <i class="bi bi-info-circle me-1"></i>
                                                No reason provided
                                            </small>
                                        </div>
                                    @endif
                                    <button type="button" class="btn btn-sm btn-outline-danger cancel-btn" 
                                            data-booking-id="{{ $booking->id }}" 
                                            data-booking-token="{{ $booking->booking_token }}" 
                                            data-service-name="{{ $booking->service->name ?? 'N/A' }}">
                                        <i class="bi bi-x-circle me-1"></i>Cancel
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
        @if($completed->count())
            <h4 class="mt-4 mb-2" style="color: #28a745;"><i class="bi bi-check-circle-fill me-1"></i> Completed Bookings</h4>
            <div class="table-responsive mb-4">
                <table class="table table-bordered align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Date & Time</th>
                            <th>Service</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($completed as $booking)
                            <tr>
                                <td>{{ $booking->cleaning_date->format('M d, Y h:i A') }}</td>
                                <td>{{ $booking->service->name ?? 'N/A' }}</td>
                                <td><span class="badge" style="background-color: #28a745;">Completed</span></td>
                                <td>
                                    @if($booking->job && $booking->job->rating_token)
                                        @php
                                            $hasRating = $booking->job->ratings()->where('customer_id', $user->customer->id ?? null)->exists();
                                        @endphp
                                        @if($hasRating)
                                            <span class="text-success"><i class="bi bi-star-fill me-1"></i>Rated</span>
                                        @else
                                            <a href="{{ route('public.rating.form', ['ratingToken' => $booking->job->rating_token]) }}" 
                                               class="btn btn-sm btn-outline-warning" 
                                               target="_blank">
                                                <i class="bi bi-star me-1"></i>Rate Service
                                            </a>
                                        @endif
                                    @else
                                        <span class="text-muted">Service completed</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
        @if($cancelled->count())
            <h4 class="mt-4 mb-2 text-danger"><i class="bi bi-x-circle me-1"></i> Cancelled Bookings</h4>
            <div class="table-responsive mb-4">
                <table class="table table-bordered align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Date & Time</th>
                            <th>Service</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($cancelled as $booking)
                            <tr>
                                <td>{{ $booking->cleaning_date->format('M d, Y h:i A') }}</td>
                                <td>{{ $booking->service->name ?? 'N/A' }}</td>
                                <td><span class="badge bg-danger">Cancelled</span></td>
                                <td><span class="text-muted">No actions available</span></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    @else
        <div class="alert alert-info">You have no bookings yet. <a href="{{ route('home') }}#services" class="alert-link" onclick="var modal = bootstrap.Modal.getInstance(document.getElementById('myBookingsModal')); if(modal) modal.hide();">Book a service now</a>!</div>
    @endif
</div>

<!-- Reschedule Modal -->
<div class="modal fade" id="rescheduleModal" tabindex="-1" aria-labelledby="rescheduleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="rescheduleModalLabel">
                    <i class="bi bi-calendar-event me-2"></i>Reschedule Booking
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="rescheduleBookingForm" method="POST">
                @csrf
                <input type="hidden" id="reschedule_booking_id" name="booking_id">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Service:</label>
                        <p id="rescheduleServiceName" class="text-muted"></p>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Current Date:</label>
                        <p id="rescheduleCurrentDate" class="text-muted"></p>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Current Time:</label>
                        <p id="rescheduleCurrentTime" class="text-muted"></p>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">New Date and Time:</label>
                        <div class="row g-2">
                            <div class="col-md-6">
                                <input type="text" class="form-control" id="reschedule_cleaning_date" name="new_cleaning_date" placeholder="Select New Cleaning Date" required>
                                 <div class="form-text">Select new date</div>
                                 <div id="fullyBookedAlertReschedule" class="alert alert-warning mt-2" role="alert" style="display:none;">
                                  <i class="bi bi-exclamation-triangle-fill"></i>
                                  This date is fully booked. Please select another date.
                                </div>
                                <div id="loadingDatesReschedule" class="mt-2 text-info" style="display:none;">
                                  <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                                  Loading available dates...
                                </div>
                            </div>
                            <div class="col-md-6">
                                        <select class="form-control" id="reschedule_cleaning_time" name="reschedule_cleaning_time">
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
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="rescheduleReason" class="form-label">Reason for Rescheduling (Optional):</label>
                        <textarea class="form-control" id="rescheduleReason" name="reason" rows="3" placeholder="Please let us know why you need to reschedule..."></textarea>
                        <div class="form-text text-danger">
                            <i class="bi bi-info-circle me-1"></i> You can only reschedule your booking once.
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn" style="background-color: #ffc044; border-color: #ffc044; color: #000; transition: background-color 0.2s;" onmouseover="this.style.backgroundColor='#f9b32a'; this.style.borderColor='#f9b32a'" onmouseout="this.style.backgroundColor='#ffc044'; this.style.borderColor='#ffc044'">
                        <i class="bi bi-calendar-check me-1"></i>Reschedule Booking
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Cancel Modal -->
<div class="modal fade" id="cancelModal" tabindex="-1" aria-labelledby="cancelModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="cancelModalLabel">
                    <i class="bi bi-x-circle me-2"></i>Cancel Booking
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="cancelForm" method="POST">
                @csrf
                @method('POST')
                <div class="modal-body">
                    <div class="alert alert-warning">
                        <i class="bi bi-exclamation-triangle me-2"></i>
                        <strong>Are you sure you want to cancel this booking?</strong>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Service:</label>
                        <p id="cancelServiceName" class="text-muted"></p>
                    </div>
                    <div class="mb-3">
                        <label for="cancelReason" class="form-label">Reason for Cancellation (Optional):</label>
                        <textarea class="form-control" id="cancelReason" name="reason" rows="3" placeholder="Please let us know why you're cancelling..."></textarea>
                    </div>
                    <div class="alert alert-info">
                        <small><i class="bi bi-info-circle me-1"></i>This action cannot be undone. You can always book a new service later.</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Keep Booking</button>
                    <button type="submit" class="btn btn-danger">
                        <i class="bi bi-x-circle me-1"></i>Cancel Booking
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Success Modal -->
<div class="modal fade" id="successModal" tabindex="-1" aria-labelledby="successModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title" id="successModalLabel"><i class="bi bi-check-circle me-2"></i>Booking Rescheduled!</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center">
                <p class="mb-0 fs-5">Your booking has been successfully rescheduled.</p>
            </div>
            <div class="modal-footer justify-content-center">
                <button type="button" class="btn btn-success" data-bs-dismiss="modal">OK</button>
            </div>
        </div>
    </div>
</div>

<!-- Cancellation Success Modal -->
<div class="modal fade" id="cancelSuccessModal" tabindex="-1" aria-labelledby="cancelSuccessModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title" id="cancelSuccessModalLabel"><i class="bi bi-check-circle me-2"></i>Booking Cancelled!</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center">
                <p class="mb-0 fs-5">Your booking has been successfully cancelled.</p>
            </div>
            <div class="modal-footer justify-content-center">
                <button type="button" class="btn btn-success" data-bs-dismiss="modal">OK</button>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Helper function to format time
    // Helper function to format time (24-hour to 12-hour with AM/PM)
    function formatTime(time24) {
        if (!time24) return 'N/A';
        const [hours, minutes] = time24.split(':');
        const hour = parseInt(hours);
        const ampm = hour >= 12 ? 'PM' : 'AM';
        const hour12 = hour % 12 || 12;
        return `${hour12}:${minutes} ${ampm}`;
    }
    let fpInstanceReschedule; // Declare flatpickr instance globally within this scope
  // Declare flatpickr instance for time picker
    let fullyBookedDatesReschedule = [];
        let fullyBookedTimesReschedule = {}; // Array to store fully booked dates for reschedule
    console.log('Initial fullyBookedTimesReschedule:', fullyBookedTimesReschedule);

    const newDateInput = document.getElementById('reschedule_cleaning_date');
    const loadingDatesReschedule = document.getElementById('loadingDatesReschedule');
    const fullyBookedAlertReschedule = document.getElementById('fullyBookedAlertReschedule');

    // Function to fetch fully booked dates for reschedule
    async function fetchFullyBookedDatesReschedule() {
      if (loadingDatesReschedule) {
        loadingDatesReschedule.style.display = 'block';
      }
      try {
        const response = await fetch('{{ route('fully.booked.dates', ['context' => 'reschedule']) }}');
        console.log('Fetch response:', response);
        if (!response.ok) {
          throw new Error(`HTTP error! status: ${response.status}`);
        }
        const data = await response.json();
        console.log('Fetch data:', data);
        fullyBookedDatesReschedule = data.fullyBookedDates || [];
        fullyBookedTimesReschedule = data.fullyBookedTimes || {};
        console.log('fullyBookedDatesReschedule after fetch:', fullyBookedDatesReschedule);
        console.log('fullyBookedTimesReschedule after fetch:', fullyBookedTimesReschedule);



        if (fpInstanceReschedule) {
          // Use array of date strings to disable, same approach as booking modal
          fpInstanceReschedule.set('disable', fullyBookedDatesReschedule.slice());
          fpInstanceReschedule.redraw();
        }
      } catch (error) {
        console.error('Error fetching fully booked dates for reschedule:', error);
        // Optionally, show an error message to the user
      } finally {
        if (loadingDatesReschedule) {
          loadingDatesReschedule.style.display = 'none';
        }
      }
    }

    // Handle reschedule button clicks
    document.querySelectorAll('.reschedule-btn').forEach(function(btn) {
        btn.addEventListener('click', async function() {
            const bookingId = this.getAttribute('data-booking-id');
            const bookingToken = this.getAttribute('data-booking-token');
            const currentDate = this.getAttribute('data-current-date');
            const currentTime = this.getAttribute('data-current-time');
            const serviceName = this.getAttribute('data-service-name');
            
            // Populate reschedule modal
            document.getElementById('rescheduleServiceName').textContent = serviceName;
            document.getElementById('rescheduleCurrentDate').textContent = currentDate;
            document.getElementById('rescheduleCurrentTime').textContent = formatTime(currentTime);
            document.getElementById('rescheduleBookingForm').action = `/bookings/${bookingToken}/reschedule`;
            document.getElementById('reschedule_booking_id').value = bookingId;
            
            // Reset form fields
            newDateInput.value = '';
            document.getElementById('reschedule_cleaning_time').value = '';
            document.getElementById('rescheduleReason').value = '';

            // Hide alert initially
            if (fullyBookedAlertReschedule) {
              fullyBookedAlertReschedule.style.display = 'none';
            }

            // Fetch fully booked dates before initializing flatpickr
            await fetchFullyBookedDatesReschedule();



            // Compute minimum selectable date: day-after-tomorrow (disable today and tomorrow)
            const minSelectableReschedule = new Date();
            minSelectableReschedule.setHours(0, 0, 0, 0);
            minSelectableReschedule.setDate(minSelectableReschedule.getDate() + 2);

            // Initialize flatpickr if not already initialized
            if (!fpInstanceReschedule) {
              fpInstanceReschedule = flatpickr(newDateInput, {
                dateFormat: 'Y-m-d',
                minDate: minSelectableReschedule,
                // Disable using date strings for consistency and reliability
                disable: fullyBookedDatesReschedule.slice(),
                onChange: function(selectedDates, dateStr, instance) {
                  if (fullyBookedAlertReschedule) {
                    fullyBookedAlertReschedule.style.display = 'none';
                  }
                  if (fullyBookedDatesReschedule.includes(dateStr)) {
                    instance.clear();
                    if (fullyBookedAlertReschedule) {
                      fullyBookedAlertReschedule.style.display = 'block';
                    }
                  }

                  // Populate time select based on selected date
                  const timeSelect = document.getElementById('reschedule_cleaning_time');
                  timeSelect.innerHTML = '<option value="">Select Time</option>'; // Clear previous options

                  console.log('Selected Dates:', selectedDates);
                  console.log('Date String:', dateStr);

                  if (selectedDates.length > 0) {
                    const selectedDate = instance.formatDate(selectedDates[0], "Y-m-d");
                    const bookedTimes = fullyBookedTimesReschedule[selectedDate] || [];
                    console.log('Selected Date (formatted):', selectedDate);
                    console.log('Booked Times for selected date:', bookedTimes);

                    // Generate time options from 9 AM to 5 PM, in hourly increments
                    for (let hour = 9; hour <= 15; hour++) {
                        const time24 = `${String(hour).padStart(2, '0')}:00`;
                        const time12 = formatTime(time24);

                        if (!bookedTimes.includes(time24)) {
                            const option = document.createElement('option');
                            option.value = time24;
                            option.textContent = time12;
                            timeSelect.appendChild(option);
                        }
                    }
                  }
                },
                onDayCreate: function(dObj, dStr, fp, dayElem) {
                  if (fullyBookedDatesReschedule.includes(flatpickr.formatDate(dayElem.dateObj, 'Y-m-d'))) {
                    dayElem.classList.add('fully-booked-date');
                    dayElem.title = 'Unavailable';
                  }
                }
              });
            } else {
              // Update flatpickr instance with new minDate and disabled dates and redraw
              fpInstanceReschedule.set('minDate', minSelectableReschedule);
              fpInstanceReschedule.set('disable', fullyBookedDatesReschedule.slice());
              fpInstanceReschedule.redraw();
            }
            
            // Show modal
            new bootstrap.Modal(document.getElementById('rescheduleModal')).show();
        });
    });

    // Handle cancel form submission
    document.getElementById('cancelForm').addEventListener('submit', function(e) {
        e.preventDefault();
        const form = this;
        const formData = new FormData(form);
        const bookingId = form.action.split('/').pop(); // Extract booking ID from form action

        fetch(form.action, {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const cancelModal = bootstrap.Modal.getInstance(document.getElementById('cancelModal'));
                if (cancelModal) {
                    cancelModal.hide();
                }
                const cancelSuccessModal = new bootstrap.Modal(document.getElementById('cancelSuccessModal'));
                cancelSuccessModal.show();
                // Optionally, reload the page or update the UI after a short delay
                setTimeout(() => {
                    window.location.reload();
                }, 2000); // Reload after 2 seconds
            } else {
                alert('Error: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred while cancelling the booking.');
        });
    });


    


    // Handle cancel button clicks
    document.querySelectorAll('.cancel-btn').forEach(function(btn) {
        btn.addEventListener('click', function() {
            const bookingId = this.getAttribute('data-booking-id');
            const bookingToken = this.getAttribute('data-booking-token');
            const serviceName = this.getAttribute('data-service-name');
            
            // Populate cancel modal
            document.getElementById('cancelServiceName').textContent = serviceName;
            document.getElementById('cancelForm').action = `/bookings/${bookingToken}/cancel`;
            
            // Show modal
            new bootstrap.Modal(document.getElementById('cancelModal')).show();
        });
    });

    // Handle success modal close and page reload
    const cancelSuccessModalElement = document.getElementById('cancelSuccessModal');
    if (cancelSuccessModalElement) {
        cancelSuccessModalElement.addEventListener('hidden.bs.modal', function () {
            window.location.reload();
        });
    }
    
            // Handle reschedule form submission
            document.getElementById('rescheduleBookingForm').addEventListener('submit', function(e) {
                e.preventDefault();

                const form = this;
                const formData = new FormData(form);
                const bookingToken = form.action.split('/').pop(); // Extract booking token from action URL

                // Debug logging - log all form data
                console.log('Form data before submission:');
                for (let pair of formData.entries()) {
                    console.log(pair[0] + ': ' + pair[1]);
                }

                // Get new date and time values
                const newDate = document.getElementById('reschedule_cleaning_date').value;
                const newTime = document.getElementById('reschedule_cleaning_time').value;

                // Combine date and time into a single field for the backend
                formData.set('new_cleaning_date', `${newDate} ${newTime}`);
                // No need to delete new_date or new_time as they are not separate inputs now

                // Debug logging - log form data after date/time combination
                console.log('Form data after date/time combination:');
                for (let pair of formData.entries()) {
                    console.log(pair[0] + ': ' + pair[1]);
                }

                // Remove _method field if it exists, as we are explicitly setting method to POST
                if (formData.has('_method')) {
                    formData.delete('_method');
                }

                fetch(form.action, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                })
                .then(response => {
                    if (!response.ok) {
                        return response.json().then(err => { throw err; });
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.success) {
                        bootstrap.Modal.getInstance(document.getElementById('rescheduleModal')).hide();
                        new bootstrap.Modal(document.getElementById('successModal')).show();
                        // Reload the page after a short delay to allow success modal to be seen
                        setTimeout(() => {
                            window.location.reload();
                        }, 1500);
                    } else {
                        throw new Error(data.message || 'Failed to reschedule booking');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    let errorMessage = 'An error occurred while rescheduling the booking';

                    if (error.message) {
                        errorMessage = error.message;
                    }

                    if (error.errors) {
                        errorMessage = Object.values(error.errors).flat().join('\n');
                    }

                    // alert('Error: ' + errorMessage); // Removed alert as per user request to avoid intermediate prompts
            // console.error('Displaying error to user:', errorMessage);
                });
            });
    
    const rescheduleModalElement = document.getElementById('rescheduleModal');
    if (rescheduleModalElement) {
        rescheduleModalElement.addEventListener('hidden.bs.modal', function () {
            // Remove any lingering modal backdrops
            const backdrops = document.querySelectorAll('.modal-backdrop');
            backdrops.forEach(backdrop => backdrop.remove());
        });
    }

});
</script>
@endauth
@guest
<div class="container py-5">
    <div class="alert alert-warning">Please <a href="{{ route('login') }}" class="alert-link">login</a> to view your bookings.</div>
</div>
@endguest
