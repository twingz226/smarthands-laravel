@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Edit Service</h3>
                    <div class="card-tools">
                        <a href="{{ route('services.index') }}" class="btn btn-sm btn-primary">Back to Services</a>
                    </div>
                </div>
                <div class="card-body">
                    <form action="{{ route('services.update', $service->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        {{-- Service Name --}}
                        <div class="form-group mb-3">
                            <label for="name">Service Name</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $service->name) }}" required>
                            @error('name')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        {{-- Description --}}
                        <div class="form-group mb-3">
                            <label for="description">Description</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="3">{{ old('description', $service->description) }}</textarea>
                            @error('description')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        {{-- Pricing Type --}}
                        <div class="form-group mb-3">
                            <label for="pricing_type">Pricing Type</label>
                            <select name="pricing_type" id="pricing_type" class="form-select @error('pricing_type') is-invalid @enderror" required>
                                <option value="sqm" {{ old('pricing_type', $service->pricing_type) === 'sqm' ? 'selected' : '' }}>Per Square Meter (₱/sqm)</option>
                                <option value="duration" {{ old('pricing_type', $service->pricing_type) === 'duration' ? 'selected' : '' }}>Per Hour (₱/hr)</option>
                            </select>
                            @error('pricing_type')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        {{-- Price --}}
                        <div class="form-group mb-3">
                            <label for="price">Price</label>
                            <div class="input-group">
                                <span class="input-group-text">₱</span>
                                <input type="number" step="0.01" class="form-control @error('price') is-invalid @enderror" id="price" name="price" value="{{ old('price', $service->price) }}" required>
                                <span class="input-group-text" id="price-unit">{{ $service->pricing_type === 'sqm' ? '/sqm' : '/hr' }}</span>
                                @error('price')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        {{-- Duration --}}
                        <div class="form-group mb-3" id="duration-group" style="{{ $service->pricing_type === 'sqm' ? 'display: none;' : '' }}">
                            <label for="duration_minutes">Duration (minutes)</label>
                            <div class="input-group">
                                <input type="number" class="form-control @error('duration_minutes') is-invalid @enderror" id="duration_minutes" name="duration_minutes" value="{{ old('duration_minutes', $service->duration_minutes) }}" {{ $service->pricing_type === 'duration' ? 'required' : '' }}>
                                <span class="input-group-text">minutes</span>
                                @error('duration_minutes')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">Update Service</button>
                            <a href="{{ route('services.index') }}" class="btn btn-secondary">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- JavaScript to toggle fields --}}
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const pricingType = document.getElementById('pricing_type');
        const durationGroup = document.getElementById('duration-group');
        const priceUnit = document.getElementById('price-unit');
        const durationInput = document.getElementById('duration_minutes');

        pricingType.addEventListener('change', function () {
            if (this.value === 'sqm') {
                durationGroup.style.display = 'none';
                priceUnit.textContent = '/sqm';
                durationInput.value = '';
                durationInput.removeAttribute('required');
                durationInput.value = '';
            }
        } else if (this.value === 'duration') {
            durationGroup.style.display = 'block';
            priceUnit.textContent = '/hr';
            if (durationInput) {
                durationInput.setAttribute('required', 'required');
            }
        }
    });
});
</script>
@endsection