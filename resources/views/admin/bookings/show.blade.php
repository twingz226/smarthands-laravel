@include('admin.partials.header')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Booking Details</h3>
                    <div class="card-tools">
                        <a href="{{ route('bookings.index') }}" class="btn btn-sm btn-secondary">
                            <i class="fas fa-arrow-left"></i> Back to List
                        </a>
                        <a href="{{ route('bookings.edit', $booking) }}" class="btn btn-sm btn-primary">
                            <i class="fas fa-edit"></i> Edit
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <class="row">
                        <div class="col-md-6">
                            <h5>Customer Information</h5>
                            <table class="table table-bordered">
                                <tr>
                                    <th>Name</th>
                                    <td>{{ $booking->customer->name }}</td>
                                </tr>
                                <tr>
                                    <th>Email</th>
                                    <td>{{ $booking->customer->email }}</td>
                                </tr>
                                <tr>
                                    <th>Contact</th>
                                    <td>{{ $booking->customer->contact }}</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <h5>Service Information</h5>
                            <table class="table table-bordered">
                                <tr>
                                    <th>Service</th>
                                    <td>{{ $booking->service->name }}</td>
                                </tr>
                            </table>
                        </div>
                    

                    
                        <div class="col-md-6">
                            <h5>Booking Details</h5>
                            <table class="table table-bordered">
                                <tr>
                                    <th>Booking ID</th>
                                    <td>{{ $booking->id }}</td>
                                    <th>Status</th>
                                    <td>
                                        <span class="badge badge-{{ $booking->status === 'pending' ? 'warning' : ($booking->status === 'confirmed' ? 'success' : ($booking->status === 'cancelled' ? 'danger' : 'info')) }}">
                                            {{ ucfirst($booking->status) }}
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Cleaning Date</th>
                                    <td>{{ $booking->cleaning_date->setTimezone('Asia/Manila')->format('F j, Y g:i A') }} PHT</td>
                                    <th>Last Updated</th>
                                    <td>{{ $booking->updated_at->setTimezone('Asia/Manila')->format('F j, Y g:i A') }} PHT</td>
                                </tr>
                                <tr>
                                    <th>Created At</th>
                                    <td>{{ $booking->created_at->setTimezone('Asia/Manila')->format('F j, Y g:i A') }} PHT</td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    @if($booking->special_instructions)
                    <div class="row mt-4">
                        <div class="col-12">
                            <h5>Special Instructions</h5>
                            <div class="card">
                                <div class="card-body">
                                    {{ $booking->special_instructions }}
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif

                    @if($booking->admin_notes)
                    <div class="row mt-4">
                        <div class="col-12">
                            <h5>Admin Notes</h5>
                            <div class="card">
                                <div class="card-body">
                                    {{ $booking->admin_notes }}
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
                <div class="card-footer">
                    <div class="btn-group">
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@include('admin.partials.scripts')