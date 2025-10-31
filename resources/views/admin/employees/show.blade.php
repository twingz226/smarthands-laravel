@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Employee Details</h1>
        <div class="btn-toolbar mb-2 mb-md-0">
            <a href="{{ route('employees.index') }}" class="btn btn-sm btn-secondary me-2">
                <i class="entypo-back"></i> Back to List
            </a>
            <a href="{{ route('employees.edit', $employee->id) }}" class="btn btn-sm btn-primary">
                <i class="entypo-pencil"></i> Edit Employee
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
                    <table class="table table-bordered">
                        <tr>
                            <th width="30%">Name</th>
                            <td>{{ $employee->name }}</td>
                        </tr>
                        <tr>
                            <th>Employee No.</th>
                            <td>
                                @if($employee->employee_no)
                                    {{ $employee->employee_no }}
                                @else
                                    <span class="text-muted">Not assigned</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th>Position</th>
                            <td>
                                @if($employee->position)
                                    {{ $employee->position }}
                                @else
                                    <span class="text-muted">Not specified</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th>Phone</th>
                            <td>{{ $employee->phone }}</td>
                        </tr>
                        <tr>
                            <th>Address</th>
                            <td>{{ $employee->address }}</td>
                        </tr>
                        <tr>
                            <th>Hire Date</th>
                            <td>{{ $employee->hire_date->format('M d, Y') }}</td>
                        </tr>
                    </table>
                </div>
            </div>

            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">Performance Overview</h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="border rounded p-3">
                                <h6 class="text-muted mb-1">Total Jobs</h6>
                                <h4 class="mb-0">{{ $employee->jobs_count ?? 0 }}</h4>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="border rounded p-3">
                                <h6 class="text-muted mb-1">Average Rating</h6>
                                <h4 class="mb-0">
                                    @if($employee->ratings_avg_rating)
                                        {{ number_format($employee->ratings_avg_rating, 1) }} / 5.0
                                    @else
                                        No ratings yet
                                    @endif
                                </h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">Photo Management</h5>
                    @if($employee->hasPhotos())
                        <span class="badge bg-success"><i class="entypo-check"></i> Photos Approved</span>
                    @else
                        <span class="badge bg-secondary"><i class="entypo-camera"></i> No Photos</span>
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

            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Recent Jobs</h5>
                </div>
                <div class="card-body">
                    @if($recent_jobs->count() > 0)
                        <div class="list-group">
                            @foreach($recent_jobs as $job)
                                <div class="list-group-item">
                                    <div class="d-flex w-100 justify-content-between">
                                        <h6 class="mb-1">{{ $job->service->name }}</h6>
                                        <small>{{ $job->completed_at ? $job->completed_at->format('M d, Y') : 'In Progress' }}</small>
                                    </div>
                                    <p class="mb-1">{{ $job->customer->name }}</p>
                                    <small class="text-muted">Status: {{ ucfirst($job->status) }}</small>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-muted mb-0">No recent jobs found.</p>
                    @endif
                </div>
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
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Photo Management
    const photoInput = document.getElementById('photo-input');
    const photoPreview = document.getElementById('photo-preview');
    const deletePhotoBtns = document.querySelectorAll('.delete-photo-btn');
    const confirmPhotoDeleteBtn = document.getElementById('confirmPhotoDeleteBtn');
    const photoDeleteModal = new bootstrap.Modal(document.getElementById('photoDeleteModal'));

    let deleteFormToSubmit = null;

    // Photo preview functionality
    if (photoInput) {
        photoInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    photoPreview.src = e.target.result;
                    photoPreview.style.display = 'block';
                };
                reader.readAsDataURL(file);
            }
        });
    }

    // Delete photo functionality
    document.querySelectorAll('.delete-photo-btn').forEach(function(btn) {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            deleteFormToSubmit = btn.closest('form');
            photoDeleteModal.show();
        });
    });

    if (confirmPhotoDeleteBtn) {
        confirmPhotoDeleteBtn.addEventListener('click', function() {
            if (deleteFormToSubmit) {
                deleteFormToSubmit.submit();
                deleteFormToSubmit = null;
                photoDeleteModal.hide();
            }
        });
    }
});
</script>
@endpush