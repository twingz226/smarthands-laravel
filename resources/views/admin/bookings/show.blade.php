@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <div>
                        <h3 class="card-title mb-0">
                            <i class="fas fa-calendar-check text-primary mr-2"></i>
                            Booking #{{ $booking->id }}
                        </h3>
                        <div class="text-muted mt-1">
                            Created: {{ $booking->created_at->setTimezone('Asia/Manila')->format('M j, Y h:i A') }} PHT
                        </div>
                    </div>
                    <div class="card-tools">
                        <span class="badge badge-lg badge-{{ $booking->status === 'pending' ? 'warning' : ($booking->status === 'confirmed' ? 'success' : ($booking->status === 'cancelled' ? 'danger' : 'info')) }} px-3 py-2">
                            {{ ucfirst($booking->status) }}
                        </span>
                    </div>
                </div>
                
                <div class="card-body">
                    <!-- Top Action Buttons -->
                    <div class="d-flex justify-content-between mb-4">
                        <a href="{{ route('bookings.index') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-arrow-left mr-2"></i>Back to List
                        </a>
                        <div>
                            <a href="{{ route('bookings.edit', $booking) }}" class="btn btn-primary">
                                <i class="fas fa-edit mr-2"></i>Edit Booking
                            </a>
                        </div>
                    </div>

                    <!-- Main Content -->
                    <div class="row">
                        <!-- Left Column - Customer & Service Info -->
                        <div class="col-lg-6 mb-4">
                            <!-- Customer Information -->
                            <div class="card mb-4">
                                <div class="card-header bg-light">
                                    <h5 class="mb-0">
                                        <i class="fas fa-user-circle text-primary mr-2"></i>
                                        Customer Information
                                    </h5>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-borderless mb-0">
                                            <tr>
                                                <th class="text-muted font-weight-normal" style="width: 120px;">Name</th>
                                                <td class="text-dark">{{ $booking->customer_name }}</td>
                                            </tr>
                                            <tr>
                                                <th class="text-dark font-weight-normal">Email</th>
                                                <td class="text-dark">
                                                    <a href="mailto:{{ $booking->customer_email }}" class="text-dark">
                                                        {{ $booking->customer_email }}
                                                    </a>
                                                </td>
                                            </tr>
                                            <tr>
                                                <th class="text-dark font-weight-normal">Contact</th>
                                                <td class="text-dark">
                                                    <a href="tel:{{ $booking->customer_contact }}" class="text-dark">
                                                        {{ $booking->customer_contact }}
                                                    </a>
                                                </td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                            </div>

                            <!-- Service Information -->
                            <div class="card">
                                <div class="card-header bg-light">
                                    <h5 class="mb-0">
                                        <i class="fas fa-broom text-primary mr-2"></i>
                                        Service Details
                                    </h5>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-borderless mb-0">
                                            <tr>
                                                <th class="text-dark font-weight-normal" style="width: 120px;">Service</th>
                                                <td class="text-dark">{{ $booking->service->name }}</td>
                                            </tr>
                                            <tr>
                                                <th class="text-dark font-weight-normal">Date & Time</th>
                                                <td class="text-dark">
                                                    <div class="font-weight-bold">
                                                        {{ $booking->cleaning_date->setTimezone('Asia/Manila')->format('l, F j, Y') }}
                                                    </div>
                                                    <div class="text-dark">
                                                        {{ $booking->cleaning_date->setTimezone('Asia/Manila')->format('h:i A') }} PHT
                                                    </div>
                                                </td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Right Column - Booking Details & Notes -->
                        <div class="col-lg-6">
                            <!-- Booking Timeline -->
                            <div class="card mb-4">
                                <div class="card-header bg-light">
                                    <h5 class="mb-0">
                                        <i class="fas fa-history text-primary mr-2"></i>
                                        Booking Timeline
                                    </h5>
                                </div>
                                <div class="card-body">
                                    <div class="timeline">
                                        <div class="timeline-item">
                                            <div class="timeline-marker bg-primary"></div>
                                            <div class="timeline-content">
                                                <h6 class="mb-1">Booking Created</h6>
                                                <p class="text-muted small mb-0">
                                                    {{ $booking->created_at->setTimezone('Asia/Manila')->format('M j, Y h:i A') }} PHT
                                                </p>
                                            </div>
                                        </div>
                                        @if($booking->updated_at->gt($booking->created_at))
                                        <div class="timeline-item">
                                            <div class="timeline-marker bg-success"></div>
                                            <div class="timeline-content">
                                                <h6 class="mb-1">Last Updated</h6>
                                                <p class="text-muted small mb-0">
                                                    {{ $booking->updated_at->setTimezone('Asia/Manila')->format('M j, Y h:i A') }} PHT
                                                </p>
                                            </div>
                                        </div>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <!-- Special Instructions -->
                            @if($booking->special_instructions)
                            <div class="card mb-4">
                                <div class="card-header bg-light">
                                    <h5 class="mb-0">
                                        <i class="fas fa-sticky-note text-primary mr-2"></i>
                                        Special Instructions
                                    </h5>
                                </div>
                                <div class="card-body">
                                    <div class="p-3 bg-light rounded">
                                        {!! nl2br(e($booking->special_instructions)) !!}
                                    </div>
                                </div>
                            </div>
                            @endif

                            <!-- Cancellation Reason -->
                            @if($booking->status === 'cancelled')
                            <div class="card mb-4">
                                <div class="card-header bg-light">
                                    <h5 class="mb-0">
                                        <i class="fas fa-times-circle text-danger mr-2"></i>
                                        Cancellation Reason
                                    </h5>
                                </div>
                                <div class="card-body">
                                    @if($booking->cancellation_reason)
                                        <div class="p-3 bg-light rounded">
                                            {!! nl2br(e($booking->cancellation_reason)) !!}
                                        </div>
                                    @else
                                        <div class="text-muted">
                                            <i class="fas fa-info-circle mr-2"></i>
                                            No cancellation reason provided by customer.
                                        </div>
                                    @endif
                                </div>
                            </div>
                            @endif

                            <!-- Admin Notes -->
                            @if($booking->admin_notes)
                            <div class="card">
                                <div class="card-header bg-light">
                                    <h5 class="mb-0">
                                        <i class="fas fa-clipboard-check text-primary mr-2"></i>
                                        Admin Notes
                                    </h5>
                                </div>
                                <div class="card-body">
                                    <div class="p-3 bg-light rounded">
                                        {!! nl2br(e($booking->admin_notes)) !!}
                                    </div>
                                    <div class="text-right mt-2">
                                        <small class="text-muted">Last updated: {{ $booking->updated_at->setTimezone('Asia/Manila')->format('M j, Y h:i A') }} PHT</small>
                                    </div>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Footer Actions -->
                <div class="card-footer bg-white d-flex justify-content-between">
                    <div>
                        <span class="text-muted small">
                            <i class="fas fa-info-circle mr-1"></i>
                            Booking ID: {{ $booking->id }}
                        </span>
                    </div>
                    <div>
                        <a href="{{ route('bookings.edit', $booking) }}" class="btn btn-outline-primary btn-sm">
                            <i class="fas fa-edit mr-1"></i> Edit
                        </a>
                        <a href="{{ route('bookings.index') }}" class="btn btn-outline-secondary btn-sm ml-2">
                            <i class="fas fa-list mr-1"></i> View All Bookings
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .timeline {
        position: relative;
        padding-left: 2rem;
    }
    .timeline-item {
        position: relative;
        padding-bottom: 1.5rem;
    }
    .timeline-marker {
        position: absolute;
        left: -2rem;
        width: 1rem;
        height: 1rem;
        border-radius: 50%;
        top: 0.25rem;
    }
    .timeline-content {
        padding-left: 1rem;
    }
    .card-header {
        border-bottom: 1px solid rgba(0,0,0,.05);
    }
    /* Hide the card footer with Booking ID and action buttons */
    .card-footer.bg-white.d-flex.justify-content-between {
        display: none !important;
    }
</style>
@endpush