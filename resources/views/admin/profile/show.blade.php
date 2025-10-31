@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="profile-container">
        <div class="profile-header">
            <h1><i class="entypo-user"></i> My Profile</h1>
        </div>

        @if(session('success'))
            <div class="alert-success">
                {{ session('success') }}
            </div>
        @endif

        <div class="profile-card">
            <div class="profile-content">
                <div class="profile-grid">
                    <div class="profile-sidebar">
                        <!-- Debug: Profile Photo URL: {{ $user->avatar_url }} -->
                        <img src="{{ $user->avatar_url }}" 
                             alt="Profile Photo" 
                             class="profile-avatar"
                             onerror="console.error('Failed to load image:', this.src)">
                        <h3>{{ $user->name }}</h3>
                        <p class="text-muted">Administrator</p>
                    </div>

                    <div class="profile-details">
                        <div class="detail-item">
                            <span class="detail-label">Full Name</span>
                            <span class="detail-value">{{ $user->name }}</span>
                        </div>

                        <div class="detail-item">
                            <span class="detail-label">Member Since</span>
                            <span class="detail-value">{{ $user->created_at->format('F j, Y') }}</span>
                        </div>

                        <div class="detail-item">
                            <span class="detail-label">Last Updated</span>
                            <span class="detail-value">{{ $user->updated_at->format('F j, Y \a\t g:i A') }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="profile-actions">
                <a href="{{ route('admin.dashboard') }}" class="btn btn-outline">
                    <i class='bx bx-arrow-back'></i> Back to Dashboard
                </a>
                <a href="{{ route('admin.profile.edit') }}" class="btn btn-primary">
                    <i class='bx bx-edit-alt'></i> Edit Profile
                </a>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
:root {
    --primary-color: #4f46e5;
    --primary-hover: #4338ca;
    --text-primary: #1f2937;
    --text-secondary: #6b7280;
    --border-color: #e5e7eb;
    --bg-light: #f9fafb;
    --shadow-sm: 0 1px 2px 0 rgb(0 0 0 / 0.05);
    --shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1), 0 2px 4px -2px rgb(0 0 0 / 0.1);
    --radius: 0.5rem;
    --text-base: 1.125rem;      /* 18px */
    --text-lg: 1.25rem;        /* 20px */
    --text-xl: 1.5rem;         /* 24px */
    --text-2xl: 1.875rem;      /* 30px */
    --text-3xl: 2.25rem;       /* 36px */
    --text-4xl: 3rem;          /* 48px */
    --text-5xl: 3.75rem;       /* 60px */
    --line-height-normal: 1.5;
    --line-height-tight: 1.25;
}

/* Base Styles */
.profile-container {
    max-width: 1200px;
    margin: 2rem auto;
    padding: 0 1rem;
}

.profile-header {
    margin-bottom: 2rem;
    padding-bottom: 1rem;
    border-bottom: 1px solid var(--border-color);
}

.profile-header h1 {
    font-size: var(--text-4xl);
    font-weight: 700;
    color: var(--text-primary);
    display: flex;
    align-items: center;
    gap: 0.75rem;
    margin: 0 0 0.5rem 0;
    line-height: 1.2;
}

.profile-header h1 i {
    color: var(--primary-color);
}

.profile-card {
    background: white;
    border-radius: var(--radius);
    box-shadow: var(--shadow-sm);
    border: 1px solid var(--border-color);
    overflow: hidden;
}

.profile-content {
    padding: 2rem;
}

.profile-grid {
    display: grid;
    grid-template-columns: minmax(250px, 1fr) 2fr;
    gap: 2rem;
}

.profile-sidebar {
    display: flex;
    flex-direction: column;
    align-items: center;
    padding: 1.5rem;
    border-right: 1px solid var(--border-color);
}

.profile-sidebar h3 {
    font-size: var(--text-2xl);
    margin: 0.5rem 0;
    color: var(--text-primary);
    font-weight: 600;
    line-height: 1.2;
}

.profile-sidebar p {
    color: var(--text-secondary);
    margin: 0;
    font-size: var(--text-base);
    line-height: var(--line-height-normal);
}

.profile-avatar {
    width: 200px;
    height: 200px;
    border-radius: 50%;
    object-fit: cover;
    border: 4px solid white;
    box-shadow: var(--shadow);
    margin-bottom: 1.5rem;
}

.profile-details {
    flex: 1;
    width: 100%;
}

.detail-item {
    padding: 1.25rem 0;
    border-bottom: 1px solid var(--border-color);
    line-height: var(--line-height-normal);
}

.detail-item:last-child {
    border-bottom: none;
}

.detail-label {
    display: block;
    font-size: var(--text-lg);
    font-weight: 500;
    color: var(--text-secondary);
    margin-bottom: 0.5rem;
    letter-spacing: 0.01em;
}

.detail-value {
    font-size: var(--text-xl);
    font-weight: 500;
    color: var(--text-primary);
    line-height: 1.5;
}

.detail-value.empty {
    color: var(--text-secondary);
    font-style: italic;
}

.profile-actions {
    display: flex;
    justify-content: flex-end;
    gap: 1rem;
    padding: 1.5rem 2rem;
    background-color: var(--bg-light);
    border-top: 1px solid var(--border-color);
}

.btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    padding: 0.75rem 1.5rem;
    border-radius: 0.5rem;
    font-weight: 500;
    font-size: var(--text-base);
    cursor: pointer;
    transition: all 0.2s;
    gap: 0.5rem;
    text-decoration: none;
    line-height: 1.5;
}

.btn-primary {
    background-color: var(--primary-color);
    color: white;
    border: none;
}

.btn-primary:hover {
    background-color: var(--primary-hover);
}

.btn-outline {
    background-color: white;
    color: var(--text-primary);
    border: 1px solid var(--border-color);
}

.btn-outline:hover {
    background-color: var(--bg-light);
}

/* Success Message */
.alert-success {
    padding: 1rem;
    margin-bottom: 1.5rem;
    border-radius: 0.375rem;
    background-color: #ecfdf5;
    color: #047857;
    border: 1px solid #a7f3d0;
}

/* Responsive Adjustments */
@media (max-width: 768px) {
    :root {
        --text-base: 1.125rem;  /* 18px */
        --text-lg: 1.25rem;    /* 20px */
        --text-xl: 1.5rem;     /* 24px */
        --text-2xl: 1.75rem;   /* 28px */
        --text-3xl: 2.25rem;   /* 36px */
        --text-4xl: 2.5rem;    /* 40px */
        --text-5xl: 3rem;      /* 48px */
    }
    .profile-grid {
        grid-template-columns: 1fr;
    }

    .profile-sidebar {
        border-right: none;
        border-bottom: 1px solid var(--border-color);
        padding-bottom: 2rem;
    }

    .profile-actions {
        flex-direction: column;
    }

    .btn {
        width: 100%;
    }
}
</style>
@endpush

