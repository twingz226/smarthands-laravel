@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Edit Contact & About Information</h1>
    </div>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <div class="card">
        <div class="card-body">
            <form action="{{ route('admin.contact.update') }}" method="POST">
                @csrf
                @method('PUT')

                <h4 class="mb-4">Contact Information</h4>

                <div class="mb-3">
                    <label for="email" class="form-label">Email Address</label>
                    <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email', $contactInfo->email ?? '') }}" required>
                    @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="phone" class="form-label">Phone Number</label>
                    <input type="text" class="form-control @error('phone') is-invalid @enderror" id="phone" name="phone" value="{{ old('phone', $contactInfo->phone ?? '') }}" required>
                    @error('phone')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="address" class="form-label">Address</label>
                    <input type="text" class="form-control @error('address') is-invalid @enderror" id="address" name="address" value="{{ old('address', $contactInfo->address ?? '') }}" required>
                    @error('address')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="service_area" class="form-label">Service Area</label>
                    <input type="text" class="form-control @error('service_area') is-invalid @enderror" id="service_area" name="service_area" value="{{ old('service_area', $contactInfo->service_area ?? '') }}" required>
                    @error('service_area')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="business_hours" class="form-label">Business Hours</label>
                    <input type="text" class="form-control @error('business_hours') is-invalid @enderror" id="business_hours" name="business_hours" value="{{ old('business_hours', $contactInfo->business_hours ?? '') }}" required>
                    @error('business_hours')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="facebook_url" class="form-label">Facebook URL</label>
                    <input type="url" class="form-control @error('facebook_url') is-invalid @enderror" id="facebook_url" name="facebook_url" value="{{ old('facebook_url', $contactInfo->facebook_url ?? '') }}">
                    @error('facebook_url')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="instagram_url" class="form-label">Instagram URL</label>
                    <input type="url" class="form-control @error('instagram_url') is-invalid @enderror" id="instagram_url" name="instagram_url" value="{{ old('instagram_url', $contactInfo->instagram_url ?? '') }}">
                    @error('instagram_url')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="google_business_url" class="form-label">Google Business URL</label>
                    <input type="url" class="form-control @error('google_business_url') is-invalid @enderror" id="google_business_url" name="google_business_url" value="{{ old('google_business_url', $contactInfo->google_business_url ?? '') }}">
                    @error('google_business_url')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <h4 class="mt-5 mb-4">About Information</h4>

                <div class="mb-3">
                    <label for="about_content" class="form-label">About Content</label>
                    <textarea class="form-control @error('about_content') is-invalid @enderror" id="about_content" name="about_content" rows="5">{{ old('about_content', $contactInfo->about_content ?? '') }}</textarea>
                    @error('about_content')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="mission" class="form-label">Mission</label>
                    <textarea class="form-control @error('mission') is-invalid @enderror" id="mission" name="mission" rows="3">{{ old('mission', $contactInfo->mission ?? '') }}</textarea>
                    @error('mission')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="vision" class="form-label">Vision</label>
                    <textarea class="form-control @error('vision') is-invalid @enderror" id="vision" name="vision" rows="3">{{ old('vision', $contactInfo->vision ?? '') }}</textarea>
                    @error('vision')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="services_offered" class="form-label">Services Offered</label>
                    <textarea class="form-control @error('services_offered') is-invalid @enderror" id="services_offered" name="services_offered" rows="4">{{ old('services_offered', $contactInfo->services_offered ?? '') }}</textarea>
                    @error('services_offered')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <button type="submit" class="btn btn-primary">Update Information</button>
            </form>
        </div>
    </div>
</div>
@endsection 