@include('admin.partials.header')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Edit Booking</h3>
                    <p class="mb-0 mt-2">Customer: <strong>{{ $booking->customer->name }}</strong></p>
                    <p class="mb-0">Service: <strong>{{ $booking->service->name }}</strong></p>
                    <div class="card-tools">
                        <a href="{{ route('bookings.index') }}" class="btn btn-sm btn-secondary">
                            <i class="fas fa-arrow-left"></i> Back
                        </a>
                    </div>
                </div>
                <form action="{{ route('bookings.update', $booking) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="card-body">
                        <!-- Hidden inputs for customer_id and service_id -->
                        <input type="hidden" name="customer_id" value="{{ $booking->customer_id }}">
                        <input type="hidden" name="service_id" value="{{ $booking->service_id }}">
                        
                        <div class="row mt-3">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="cleaning_date">Cleaning Date *</label>
                                    <input type="datetime-local" 
                                           name="cleaning_date" 
                                           id="cleaning_date" 
                                           class="form-control @error('cleaning_date') is-invalid @enderror" 
                                           value="{{ old('cleaning_date', $booking->cleaning_date->format('Y-m-d\TH:i')) }}"
                                           required>
                                    @error('cleaning_date')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="status">Status *</label>
                                    <select name="status" id="status" class="form-control @error('status') is-invalid @enderror" required>
                                        <option value="pending" {{ old('status', $booking->status) === 'pending' ? 'selected' : '' }}>Pending</option>
                                        <option value="confirmed" {{ old('status', $booking->status) === 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                                        <option value="completed" {{ old('status', $booking->status) === 'completed' ? 'selected' : '' }}>Completed</option>
                                        <option value="cancelled" {{ old('status', $booking->status) === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                    </select>
                                    @error('status')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Update Booking
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    $(document).ready(function() {
        // Initialize Select2
        $('.select2').select2({
            theme: 'bootstrap4'
        });
    });
</script>
@endpush

@include('admin.partials.scripts') 