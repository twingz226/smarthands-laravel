@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Cancel Booking</div>

                <div class="card-body">
                    <p>Are you sure you want to cancel your booking for {{ $booking->cleaning_date->format('Y-m-d') }}?</p>

                    <form method="POST" action="{{ route('bookings.updateCancel', $booking->booking_token) }}">
                        @csrf
                        <button type="submit" class="btn btn-danger">Confirm Cancellation</button>
                        <a href="{{ route('home') }}" class="btn btn-secondary">Go Back</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection