@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                <h1 class="h2">Edit Employee</h1>
                <div class="btn-toolbar mb-2 mb-md-0">
                    <a href="{{ route('employees.index') }}" class="btn btn-sm btn-secondary">
                        <i class="entypo-back"></i> Back to List
                    </a>
                </div>
            </div>

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <div class="row">
                <div class="col-md-12">
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="card-title mb-0">Basic Information</h5>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('employees.update', $employee->id) }}" method="POST" id="main-update-employee-form" enctype="multipart/form-data">
                                @csrf
                                @method('PUT')

                                <div class="mb-3">
                                    <label for="name" class="form-label">Name</label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror"
                                        id="name" name="name" value="{{ old('name', $employee->name) }}" required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="employee_no" class="form-label">Employee No.</label>
                                    <input type="text" class="form-control @error('employee_no') is-invalid @enderror"
                                        id="employee_no" name="employee_no" value="{{ old('employee_no', $employee->employee_no) }}" placeholder="Optional">
                                    @error('employee_no')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div class="form-text">Leave blank to auto-generate or enter manually</div>
                                </div>

                                <div class="mb-3">
                                    <label for="position" class="form-label">Position</label>
                                    <input type="text" class="form-control @error('position') is-invalid @enderror"
                                        id="position" name="position" value="{{ old('position', $employee->position) }}" placeholder="e.g. Senior Cleaner, Junior Cleaner">
                                    @error('position')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div class="form-text">Employee's job title or role</div>
                                </div>

                                <div class="mb-3">
                                    <label for="phone" class="form-label">Phone</label>
                                    <input type="text" class="form-control @error('phone') is-invalid @enderror"
                                        id="phone" name="phone" value="{{ old('phone', $employee->phone) }}" required>
                                    @error('phone')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="address" class="form-label">Address</label>
                                    <textarea class="form-control @error('address') is-invalid @enderror"
                                        id="address" name="address" rows="3" required>{{ old('address', $employee->address) }}</textarea>
                                    @error('address')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="hire_date" class="form-label">Hire Date</label>
                                    <input type="date" class="form-control @error('hire_date') is-invalid @enderror"
                                        id="hire_date" name="hire_date"
                                        value="{{ old('hire_date', $employee->hire_date->format('Y-m-d')) }}" required>
                                    @error('hire_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Photo Upload Section (merged) -->
                                <div class="card shadow-sm border-primary mb-4">
                                    <div class="card-header bg-primary text-white">
                                        <h5 class="card-title mb-0"><i class="entypo-camera"></i> Photo Upload <span class="badge bg-info ms-2">Optional</span></h5>
                                    </div>
                                    <div class="card-body p-4">
                                        <div class="alert alert-info d-flex align-items-center mb-4" role="alert">
                                            <i class="entypo-info-circled me-2"></i>
                                            <div>
                                                <strong>Tip:</strong> Upload a professional headshot for the employee. You can also add or change the photo later from the employee detail page.
                                            </div>
                                        </div>
                                        <div class="row g-3 mb-4">
                                            <div class="col-md-6">
                                                <label class="form-label fw-bold text-primary">Profile Photo <span class="badge bg-success">Required for Profile</span></label>
                                                <div class="custom-file-upload-wrapper">
                                                    <input type="file" name="profile_photo" id="profile_photo_input" class="form-control border-primary d-none" accept="image/*">
                                                    <button type="button" id="customEmployeePhotoBtn" class="btn btn-secondary">Choose File</button>
                                                    <span id="selectedEmployeeFileName" class="ms-2 text-muted">No file chosen</span>
                                                </div>
                                                <small class="text-muted">Professional headshot (JPEG, PNG, JPG, max 2MB)</small>
                                                @error('profile_photo')
                                                    <div class="text-danger small">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="alert alert-warning d-flex align-items-center mb-4" role="alert">
                                            <i class="entypo-attention me-2"></i>
                                            <div>
                                                <strong>Consent Required:</strong> Please ensure the employee has given consent for photo usage.
                                            </div>
                                        </div>
                                        <div class="row g-3 mb-4">
                                            <div class="col-md-6">
                                                <div class="form-check">
                                                    <input class="form-check-input border-warning" type="checkbox" name="photo_consent_given"
                                                           id="photo_consent_given" value="1" {{ $employee->photo_consent_given ? 'checked' : '' }}>
                                                    <label class="form-check-label text-warning fw-bold" for="photo_consent_given">
                                                        Employee has given consent for photo usage
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- Place Update Employee button here -->
                                        <div class="text-center mt-3">
                                            <button type="button" class="btn btn-primary px-5 mx-auto" id="sidebar-update-employee-btn" style="display:inline-block;">
                                                <i class="entypo-save"></i> Update Employee
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- Photo Management Section -->
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="card-title mb-0">Photo Management</h5>
                            @if($employee->hasPhotos())
                                <span class="badge bg-success">Photos Approved</span>
                            @else
                                <span class="badge bg-secondary">No Photos</span>
                            @endif
                        </div>
                        <div class="card-body p-4">
                            <div class="photo-management-row mb-4">
                                @if($employee->profile_photo_url)
                                    <div class="photo-management-card">
                                        <img src="{{ $employee->profile_photo_url }}" alt="Profile Photo" class="img-thumbnail mb-2" style="max-height: 100px;">
                                        <p class="small text-muted mb-2">Profile Photo</p>
                                        <form action="{{ route('employees.delete-photo', $employee) }}" method="POST" class="d-inline delete-photo-form">
                                            @csrf
                                            @method('DELETE')
                                            <input type="hidden" name="photo_type" value="profile_photo">
                                            <button type="button" class="btn btn-sm btn-danger delete-photo-btn" data-photo-type="profile_photo">
                                                <i class="entypo-trash"></i> Delete
                                            </button>
                                        </form>
                                    </div>
                                @endif
                                @if($employee->id_badge_photo_url)
                                    <div class="photo-management-card">
                                        <img src="{{ $employee->id_badge_photo_url }}" alt="ID Badge Photo" class="img-thumbnail mb-2" style="max-height: 100px;">
                                        <p class="small text-muted mb-2">ID Badge Photo</p>
                                        <form action="{{ route('employees.delete-photo', $employee) }}" method="POST" class="d-inline delete-photo-form">
                                            @csrf
                                            @method('DELETE')
                                            <input type="hidden" name="photo_type" value="id_badge_photo">
                                            <button type="button" class="btn btn-sm btn-danger delete-photo-btn" data-photo-type="id_badge_photo">
                                                <i class="entypo-trash"></i> Delete
                                            </button>
                                        </form>
                                    </div>
                                @endif
                                @if($employee->uniform_photo_url)
                                    <div class="photo-management-card">
                                        <img src="{{ $employee->uniform_photo_url }}" alt="Uniform Photo" class="img-thumbnail mb-2" style="max-height: 100px;">
                                        <p class="small text-muted mb-2">Uniform Photo</p>
                                        <form action="{{ route('employees.delete-photo', $employee) }}" method="POST" class="d-inline delete-photo-form">
                                            @csrf
                                            @method('DELETE')
                                            <input type="hidden" name="photo_type" value="uniform_photo">
                                            <button type="button" class="btn btn-sm btn-danger delete-photo-btn" data-photo-type="uniform_photo">
                                                <i class="entypo-trash"></i> Delete
                                            </button>
                                        </form>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">Confirm Delete</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Are you sure you want to delete this employee? This action cannot be undone.
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <form action="{{ route('employees.destroy', $employee->id) }}" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Delete</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Photo Delete Confirmation Modal -->
<div class="modal fade" id="photoDeleteModal" tabindex="-1" aria-labelledby="photoDeleteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="photoDeleteModalLabel">Confirm Photo Deletion</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Are you sure you want to delete this photo? This action cannot be undone.
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger" id="confirmPhotoDeleteBtn">Delete</button>
            </div>
        </div>
    </div>
</div>

<style>
  .consent-highlight {
    border: 3px solid #dc3545 !important;
    box-shadow: 0 0 8px 2px #dc3545 !important;
    border-radius: 0.25rem;
    transition: border 0.2s, box-shadow 0.2s;
  }
  #photo_consent_given {
    width: 1.5em;
    height: 1.5em;
    min-width: 1.5em;
    min-height: 1.5em;
  }
</style>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const updateBtn = document.getElementById('sidebar-update-employee-btn');
    const mainForm = document.getElementById('main-update-employee-form');
    const consentCheckbox = document.getElementById('photo_consent_given');
    const empFileInput = document.getElementById('profile_photo_input');
    const empCustomBtn = document.getElementById('customEmployeePhotoBtn');
    const empFileNameSpan = document.getElementById('selectedEmployeeFileName');

    // Insert warning message placeholder if not already present
    let warning = document.getElementById('consent-warning');
    if (!warning) {
        warning = document.createElement('div');
        warning.id = 'consent-warning';
        warning.className = 'alert alert-warning mt-3';
        warning.style.display = 'none';
        mainForm.appendChild(warning);
    }

    function showWarning(message) {
        warning.innerHTML = message;
        warning.style.display = 'block';
    }

    updateBtn.addEventListener('click', function(e) {
        // File size validation (2MB = 2 * 1024 * 1024 bytes)
        if (empFileInput && empFileInput.files.length > 0) {
            const file = empFileInput.files[0];
            if (file.size > 2 * 1024 * 1024) {
                showWarning('<strong>File Too Large:</strong> The selected photo exceeds the 2MB limit. Please choose a smaller file.');
                empFileInput.value = '';
                empFileInput.focus();
                return;
            }
        }
        // Consent validation
        if (empFileInput && empFileInput.files.length > 0 && !consentCheckbox.checked) {
            showWarning('<strong>Consent Required:</strong> Please check the consent box before updating the employee if a photo is uploaded.');
            consentCheckbox.scrollIntoView({ behavior: 'smooth', block: 'center' });
            consentCheckbox.classList.add('consent-highlight');
            setTimeout(() => {
                consentCheckbox.classList.remove('consent-highlight');
            }, 5000);
            consentCheckbox.focus();
        } else {
            warning.style.display = 'none';
            mainForm.submit();
        }
    });

    let deleteFormToSubmit = null;
    var confirmPhotoDeleteBtn = document.getElementById('confirmPhotoDeleteBtn');
    var $photoDeleteModal = $('#photoDeleteModal');
    document.querySelectorAll('.delete-photo-btn').forEach(function(btn) {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            deleteFormToSubmit = btn.closest('form');
            $photoDeleteModal.modal('show');
        });
    });
    confirmPhotoDeleteBtn.addEventListener('click', function() {
        if (deleteFormToSubmit) {
            deleteFormToSubmit.submit();
            deleteFormToSubmit = null;
            $photoDeleteModal.modal('hide');
        }
    });

    if (empCustomBtn && empFileInput) {
        empCustomBtn.addEventListener('click', function() {
            empFileInput.click();
        });
        empFileInput.addEventListener('change', function() {
            if (empFileInput.files && empFileInput.files.length > 0) {
                empFileNameSpan.textContent = empFileInput.files[0].name;
            } else {
                empFileNameSpan.textContent = 'No file chosen';
            }
        });
    }
});
</script>
@endpush