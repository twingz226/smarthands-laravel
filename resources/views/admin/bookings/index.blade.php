@include('admin.partials.header')

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
                            <th>Duration (hrs)</th>
                            <th>Price</th>
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
                            <td>{{ $booking->cleaning_date->format('M d, Y H:i') }}</td>
                            <td>{{ $booking->duration }}</td>
                            <td>₱{{ number_format($booking->price, 2) }}</td>
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
                                       class="btn btn-info" title="View">
                                        <i class="bi bi-eye"></i>
                                    </a>

                                    @if($booking->status == 'pending')
                                    <form action="{{ route('bookings.confirm', $booking->id) }}" 
                                          method="POST" class="d-inline">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="btn btn-success" title="Confirm">
                                            <i class="bi bi-check-circle"></i>
                                        </button>
                                    </form>
                                    @endif

                                    @if($booking->status != 'cancelled')
                                    <form action="{{ route('bookings.cancel', $booking->id) }}" 
                                          method="POST" class="d-inline">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="btn btn-danger" 
                                                title="Cancel" 
                                                onclick="return confirm('Are you sure?')">
                                            <i class="bi bi-x-circle"></i>
                                        </button>
                                    </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center">No bookings found</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@include('admin.partials.scripts')