@include('admin.partials.header')

<style>
.btn.rounded-circle {
    width: 32px;
    height: 32px;
    padding: 0;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    margin: 0 2px;
    position: relative;
    border-radius: 50% !important;
    transition: all 0.3s ease;
}

/* Badge colors */
.badge.bg-success {
    background-color: #28a745 !important;
}

.badge.bg-danger {
    background-color: #dc3545 !important;
}

.badge.bg-warning {
    background-color: #ffc107 !important;
    color: #212529 !important;
}

.badge.bg-primary {
    background-color: #0d6efd !important;
}

.badge.bg-info {
    background-color: #17a2b8 !important;
}

.btn.rounded-circle:hover {
    transform: scale(1.1);
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
}

.btn.rounded-circle i {
    margin: 0;
    font-size: 14px;
}

/* Instant CSS Tooltips */
[data-tooltip] {
    position: relative;
}

[data-tooltip]:before {
    content: attr(data-tooltip);
    position: absolute;
    bottom: 100%;
    left: 50%;
    transform: translateX(-50%);
    padding: 4px 8px;
    background: rgba(0, 0, 0, 0.8);
    color: white;
    border-radius: 4px;
    font-size: 12px;
    white-space: nowrap;
    visibility: hidden;
    z-index: 1000;
    opacity: 0;
    transition: opacity 0.3s ease;
}

[data-tooltip]:hover:before {
    visibility: visible;
    opacity: 1;
}

/* Button colors */
.btn-info.rounded-circle {
    background-color: #17a2b8;
    border-color: #17a2b8;
    color: white;
}

.btn-success.rounded-circle {
    background-color: #28a745;
    border-color: #28a745;
    color: white;
}

.btn-danger.rounded-circle {
    background-color: #dc3545;
    border-color: #dc3545;
    color: white;
}

.btn-warning.rounded-circle {
    background-color: #ffc107;
    border-color: #ffc107;
    color: #212529;
}
</style>

<div class="container-fluid">
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Online Bookings</h1>
        <div class="btn-toolbar mb-2 mb-md-0">
            
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-hover data-table">
                    <thead class="table-dark">
                        <tr>
                            <th>ID</th>
                            <th>Customer</th>
                            <th>Service</th>
                            <th>Date & Time</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($bookings as $booking)
                        <tr>
                            <td>{{ $booking->id }}</td>
                            <td>{{ $booking->customer->name }}</td>
                            <td>{{ $booking->service->name }}</td>
                            <td>{{ $booking->cleaning_date->format('M d, Y h:i A') }}</td>
                            <td>
                                <span class="badge bg-{{ 
                                    $booking->status == 'confirmed' ? 'success' : 
                                    ($booking->status == 'cancelled' ? 'danger' : 
                                    ($booking->status == 'completed' ? 'primary' : 'warning')) 
                                }}">
                                    {{ ucfirst($booking->status) }}
                                </span>
                            </td>
                            <td>
                                <div class="btn-group btn-group-sm">
                                    <a href="{{ route('bookings.show', $booking->id) }}" 
                                       class="btn btn-info rounded-circle" 
                                       data-tooltip="View">
                                        <i class="entypo-eye"></i>
                                    </a>
                                    @if($booking->status == 'pending')
                                        <form action="{{ route('bookings.confirm', $booking->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" 
                                                    class="btn btn-success rounded-circle" 
                                                    data-tooltip="Confirm">
                                                <i class="entypo-thumbs-up"></i>
                                            </button>
                                        </form>
                                        <form action="{{ route('bookings.admin.cancel', $booking->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" 
                                                    class="btn btn-danger rounded-circle" 
                                                    data-toggle="tooltip"
                                                    data-placement="top"
                                                    title="Cancel">
                                                <i class="entypo-block"></i>
                                            </button>
                                        </form>
                                        <a href="{{ route('bookings.edit', $booking->id) }}" 
                                           class="btn btn-warning rounded-circle" 
                                           data-tooltip="Reschedule">
                                            <i class="entypo-back-in-time"></i>
                                        </a>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center">No bookings found.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@include('admin.partials.scripts')

<script>
    // Initialize tooltips with zero delay and performance optimizations
    const tooltipTriggerList = document.querySelectorAll('[title]');
    const tooltipList = [...tooltipTriggerList].map(tooltipTriggerEl => new bootstrap.Tooltip(tooltipTriggerEl, {
        delay: { show: 0, hide: 0 }, // Instant show/hide
        animation: false, // Disable fade animation for instant display
        trigger: 'hover', // Only trigger on hover
        container: 'body' // Improve rendering performance
    }));
</script>