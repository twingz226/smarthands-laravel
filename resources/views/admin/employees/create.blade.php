@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Create New Employee</h1>
        <div class="btn-toolbar mb-2 mb-md-0">
            <a href="{{ route('employees.index') }}" class="btn btn-sm btn-secondary">
                <i class="entypo-left-thin"></i> Back to List
            </a>
        </div>
    </div>

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="row">
        <div class="col-md-8">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">Basic Information</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('employees.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        
                        <div class="mb-3">
                            <label for="name" class="form-label">Name</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                id="name" name="name" value="{{ old('name') }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="employee_no" class="form-label">Employee No.</label>
                            <input type="text" class="form-control @error('employee_no') is-invalid @enderror" 
                                id="employee_no" name="employee_no" value="{{ old('employee_no') }}" placeholder="Optional">
                            @error('employee_no')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">Leave blank to auto-generate or enter manually</div>
                        </div>

                        <div class="mb-3">
                            <label for="position" class="form-label">Position</label>
                            <input type="text" class="form-control @error('position') is-invalid @enderror" 
                                id="position" name="position" value="{{ old('position') }}" placeholder="e.g. Senior Cleaner, Junior Cleaner">
                            @error('position')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">Employee's job title or role</div>
                        </div>

                        <div class="mb-3">
                            <label for="phone" class="form-label">Phone</label>
                            <input type="text" class="form-control @error('phone') is-invalid @enderror" 
                                id="phone" name="phone" value="{{ old('phone') }}" required>
                            @error('phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="address" class="form-label">Address</label>
                            <textarea class="form-control @error('address') is-invalid @enderror" 
                                id="address" name="address" rows="3" required>{{ old('address') }}</textarea>
                            @error('address')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="hire_date" class="form-label">Hire Date</label>
                            <input type="date" class="form-control @error('hire_date') is-invalid @enderror" 
                                id="hire_date" name="hire_date" value="{{ old('hire_date', date('Y-m-d')) }}" required>
                            @error('hire_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary">Create Employee</button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Photo Upload Section removed and replaced with guidance text -->
            <div class="alert alert-info mt-4">
                <strong>Note:</strong> If you want to upload a profile photo for this employee, please create the employee first. After creation, go to the <strong>Edit Employee</strong> page to upload and manage photos.
            </div>
        </div>

        <div class="col-md-4">
            <!-- Additional Info or Sidebar Content -->
            
            <div class="card mt-3">
                <div class="card-header">
                    <h5 class="card-title mb-0">Tips</h5>
                </div>
                <div class="card-body">
                    <ul class="small text-muted mb-0">
                        <li>Fill in all required fields marked with *</li>
                        <li>Photos are optional but recommended for customer trust</li>
                        <li>You can add photos later from the employee detail page</li>
                        <li>Ensure employee consent before uploading photos</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 