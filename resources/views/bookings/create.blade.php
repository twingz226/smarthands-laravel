@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h4>Book Your Cleaning Service</h4>
                    <p class="mb-0">Welcome, {{ Auth::user()->name }}!</p>
                </div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    @if($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('bookings.store') }}" id="bookingForm">
                        @csrf
                        
                        <div class="mb-3">
                            <label for="service_id" class="form-label">Select Service *</label>
                            <select id="service_id" name="service_id" class="form-select @error('service_id') is-invalid @enderror" required>
                                <option value="">Choose a service...</option>
                                @foreach($services as $service)
                                    <option value="{{ $service->id }}" {{ old('service_id') == $service->id ? 'selected' : '' }}>
                                        {{ $service->name }} - ₱{{ $service->price }}
                                    </option>
                                @endforeach
                            </select>
                            @error('service_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="cleaning_date" class="form-label">Cleaning Date *</label>
                            <input type="datetime-local" id="cleaning_date" name="cleaning_date" 
                                   class="form-control @error('cleaning_date') is-invalid @enderror" 
                                   value="{{ old('cleaning_date') }}" 
                                   min="{{ date('Y-m-d\TH:i') }}" required>
                            @error('cleaning_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Times shown in your local timezone</small>
                            <input type="hidden" id="client_timezone_offset" name="client_timezone_offset" value="0">
                        </div>

                        <div class="mb-3">
                            <label for="notes" class="form-label">Special Instructions (Optional)</label>
                            <textarea id="notes" name="notes" class="form-control @error('notes') is-invalid @enderror" 
                                      rows="3" placeholder="Any special instructions or requirements...">{{ old('notes') }}</textarea>
                            @error('notes')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="alert alert-info">
                            <strong>Note:</strong> Your email ({{ Auth::user()->email }}) will be automatically used for this booking.
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary btn-lg" id="submitBtn">
                                Confirm Booking
                            </button>
                            <a href="{{ route('home') }}" class="btn btn-secondary">
                                Back to Home
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const dateInput = document.getElementById('cleaning_date');
    const form = document.getElementById('bookingForm');
    const submitBtn = document.getElementById('submitBtn');
    const clientTimezoneOffsetInput = document.getElementById('client_timezone_offset');
    
    // Set minimum datetime in local timezone
    const now = new Date();
    const timezoneOffsetMinutes = now.getTimezoneOffset(); // Offset in minutes
    const localISOTime = (new Date(now.getTime() - (timezoneOffsetMinutes * 60 * 1000))).toISOString().slice(0, 16);
    dateInput.min = localISOTime;
    
    // Debugging information
    console.log('Browser timezone:', Intl.DateTimeFormat().resolvedOptions().timeZone);
    console.log('Timezone offset (minutes):', timezoneOffsetMinutes);
    console.log('Adjusted minimum datetime:', dateInput.min);
    
    // Form submission handler
    form.addEventListener('submit', function(e) {
        // Set the client timezone offset before submission
        clientTimezoneOffsetInput.value = timezoneOffsetMinutes;

        // Additional validation if needed
        const selectedDate = new Date(dateInput.value);
        if (selectedDate < new Date(now.getTime() - (timezoneOffsetMinutes * 60 * 1000))) {
            e.preventDefault();
            alert('Please select a future date and time');
            return false;
        }
        
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Processing...';
    });
});
</script>
@endpush
@endsection