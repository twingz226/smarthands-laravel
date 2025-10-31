@auth
<div class="container py-5">
    <h2 class="mb-4"><i class="bi bi-journal-check me-2"></i>My Bookings</h2>
    @php
        $customer = \App\Models\Customer::where('email', Auth::user()->email)->first();
        $bookings = $customer ? $customer->bookings : collect();
        $pending = $bookings->where('status', 'pending');
        $confirmed = $bookings->where('status', 'confirmed');
        $rescheduled = $bookings->where('status', 'rescheduled');
        $cancelled = $bookings->where('status', 'cancelled');
    @endphp
    @if($bookings && $bookings->count())
        @if($pending->count())
            <h4 class="mt-4 mb-2 text-warning"><i class="bi bi-hourglass-split me-1"></i> Pending Bookings</h4>
            <div class="table-responsive mb-4">
                <table class="table table-bordered align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Date</th>
                            <th>Service</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($pending as $booking)
                            <tr>
                                <td>{{ $booking->cleaning_date->format('Y-m-d') }}</td>
                                <td>{{ $booking->service->name ?? 'N/A' }}</td>
                                <td><span class="badge bg-warning">Pending</span></td>
                                <td>
                                    <a href="{{ route('bookings.reschedule', $booking->booking_token) }}" class="btn btn-sm btn-outline-info me-2">Reschedule</a>
                                    <a href="{{ route('bookings.cancel', $booking->booking_token) }}" class="btn btn-sm btn-outline-danger">Cancel</a>
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
                            <th>Date</th>
                            <th>Service</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($confirmed as $booking)
                            <tr>
                                <td>{{ $booking->cleaning_date->format('Y-m-d') }}</td>
                                <td>{{ $booking->service->name ?? 'N/A' }}</td>
                                <td><span class="badge bg-success">Confirmed</span></td>
                                <td>
                                    <a href="{{ route('bookings.reschedule', $booking->booking_token) }}" class="btn btn-sm btn-outline-info me-2">Reschedule</a>
                                    <a href="{{ route('bookings.cancel', $booking->booking_token) }}" class="btn btn-sm btn-outline-danger">Cancel</a>
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
                            <th>Date</th>
                            <th>Service</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($rescheduled as $booking)
                            <tr>
                                <td>{{ $booking->cleaning_date->format('Y-m-d') }}</td>
                                <td>{{ $booking->service->name ?? 'N/A' }}</td>
                                <td><span class="badge bg-primary">Rescheduled</span></td>
                                <td>
                                    <span class="text-muted">No actions available</span>
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
                            <th>Date</th>
                            <th>Service</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($cancelled as $booking)
                            <tr>
                                <td>{{ $booking->cleaning_date->format('Y-m-d') }}</td>
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
        <div class="alert alert-info">You have no bookings yet. <a href="{{ route('home') }}" class="alert-link">Book a service now</a>!</div>
    @endif
</div>
@endauth
@guest
<div class="container py-5">
    <div class="alert alert-warning">Please <a href="{{ route('login') }}" class="alert-link">login</a> to view your bookings.</div>
</div>
@endguest
