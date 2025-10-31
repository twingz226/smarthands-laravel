@extends('layouts.admin')

@section('content')
<div class="container mt-4">
    <h2>Company Logo</h2>
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    <div class="card">
        <div class="card-body">
            <form action="{{ route('admin.settings.logo.update') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="mb-3">
                    <label for="company_logo" class="form-label">Upload New Logo</label>
                    <div class="custom-file-upload-wrapper">
                        <input type="file" class="form-control d-none" id="company_logo" name="company_logo" accept="image/*">
                        <button type="button" id="customFileBtn" class="btn btn-secondary">Choose File</button>
                        <span id="selectedFileName" class="ms-2 text-muted">No file chosen</span>
                    </div>
                    <small class="form-text text-muted">Supported formats: JPEG, PNG, JPG, GIF. Max size: 2MB.</small>
                    @error('company_logo')
                        <div class="text-danger small">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-3 text-center">
                    <label class="form-label">Current Logo</label><br>
                    @if($logo)
                        <img src="{{ asset('storage/' . $logo) }}" alt="Company Logo" style="max-height: 120px;" class="center-logo-img">
                    @else
                        <img src="{{ asset('images/Smarthands.png') }}" alt="Default Logo" style="max-height: 120px;" class="center-logo-img">
                    @endif
                </div>
                <button type="submit" class="btn btn-primary">Update Logo</button>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const fileInput = document.getElementById('company_logo');
    const customBtn = document.getElementById('customFileBtn');
    const fileNameSpan = document.getElementById('selectedFileName');
    if (customBtn && fileInput) {
        customBtn.addEventListener('click', function() {
            fileInput.click();
        });
        fileInput.addEventListener('change', function() {
            if (fileInput.files && fileInput.files.length > 0) {
                fileNameSpan.textContent = fileInput.files[0].name;
            } else {
                fileNameSpan.textContent = 'No file chosen';
            }
        });
    }
});
</script>
@endpush 