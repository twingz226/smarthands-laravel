@include('admin.partials.header')

<div class="container mt-5">
    <h2 class="mb-4">Add New Service</h2>

    {{-- Validation Errors --}}
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('services.store') }}" method="POST">
        @csrf

        {{-- Service Name --}}
        <div class="mb-3">
            <label for="name" class="form-label">Service Name</label>
            <input type="text" name="name" id="name" class="form-control" placeholder="Enter service name" required>
        </div>

        {{-- Description --}}
        <div class="mb-3">
            <label for="description" class="form-label">Service Description</label>
            <textarea name="description" id="description" class="form-control" rows="4" placeholder="Enter service description (optional)"></textarea>
        </div>

        {{-- Pricing Type --}}
        <div class="mb-3">
            <label for="pricing_type" class="form-label">Pricing Type</label>
            <select name="pricing_type" id="pricing_type" class="form-select" required>
                <option value="" disabled selected>Select pricing type</option>
                <option value="sqm">Per Square Meter (₱/sqm)</option>
                <option value="duration">Per Time Duration (₱/duration)</option>
            </select>
        </div>

        {{-- Price --}}
        <div class="mb-3">
            <label for="price" class="form-label">Price</label>
            <div class="input-group">
                <span class="input-group-text">₱</span>
                <input type="number" name="price" id="price" class="form-control" step="0.01" min="0" required>
                <span class="input-group-text" id="price-unit">/sqm</span>
            </div>
        </div>

        {{-- Duration (only visible if pricing_type is "duration") --}}
        <div class="mb-3" id="duration-group" style="display: none;">
            <label for="duration_minutes" class="form-label">Duration</label>
            <div class="input-group">
                <input type="number" name="duration_minutes" id="duration_minutes" class="form-control" min="1">
                <span class="input-group-text">minutes</span>
            </div>
        </div>

        {{-- Submit Button --}}
        <button type="submit" class="btn btn-primary">Create Service</button>
        <a href="{{ route('services.index') }}" class="btn btn-secondary">Cancel</a>
    </form>
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
            } else if (this.value === 'duration') {
                durationGroup.style.display = 'block';
                priceUnit.textContent = '/duration';
            }
        });
    });
</script>

@include('admin.partials.scripts')
