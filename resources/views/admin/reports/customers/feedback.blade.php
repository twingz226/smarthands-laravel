@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Customer Feedback & Ratings Report</h1>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Filters Section -->
    <div class="card mb-4">
        <div class="card-header">
            <h5 class="mb-0">Filter Results</h5>
        </div>
        <div class="card-body">
            <form method="GET" action="">
                <div class="row g-3">
                    <!-- Rating Filter -->
                    <div class="col-md-3">
                        <label for="rating" class="form-label">Star Rating</label>
                        <select class="form-select" id="rating" name="rating">
                            <option value="">All Ratings</option>
                            @for($i = 5; $i >= 1; $i--)
                                <option value="{{ $i }}" {{ request('rating') == $i ? 'selected' : '' }}>
                                    @for($j = 1; $j <= $i; $j++) ★ @endfor
                                    @if($i < 5)
                                        @for($j = $i + 1; $j <= 5; $j++) ☆ @endfor
                                    @endif
                                </option>
                            @endfor
                        </select>
                    </div>

                    <!-- Date Range -->
                    <div class="col-md-4">
                        <label class="form-label">Date Range</label>
                        <div class="input-group">
                            <input type="date" class="form-control" id="start_date" name="start_date"
                                value="{{ request('start_date') }}" placeholder="From">
                            <span class="input-group-text">to</span>
                            <input type="date" class="form-control" id="end_date" name="end_date"
                                value="{{ request('end_date') }}" placeholder="To">
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="col-md-5 d-flex align-items-end">
                        <div class="d-flex gap-2 w-100">
                            <button type="submit" class="btn btn-primary flex-fill">
                                <i class="fas fa-filter me-2"></i> Apply Filters
                            </button>
                            <a href="{{ request()->url() }}" class="btn btn-outline-secondary flex-fill">
                                <i class="fas fa-undo me-2"></i> Reset Filters
                            </a>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="row mb-4">
        <!-- Average Rating Card -->
        <div class="col-md-4">
            <div class="card text-white border-0 shadow-sm" style="background: linear-gradient(135deg, #4F46E5 0%, #7C3AED 100%);">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="card-title mb-0"><i class="fas fa-star me-2"></i>Average Rating</h5>
                        <div style="background-color: rgba(255, 255, 255, 0.15); backdrop-filter: blur(4px);" class="p-2 rounded-circle">
                            <i class="fas fa-chart-line"></i>
                        </div>
                    </div>
                    <h2 class="display-6 fw-bold mb-2">{{ number_format($averageRating, 1) }}<small class="fs-6 fw-normal">/5.0</small></h2>
                    <div class="d-flex align-items-center">
                        @for($i = 1; $i <= 5; $i++)
                            @if($i <= floor($averageRating))
                                <i class="fas fa-star text-warning me-1"></i>
                            @elseif($i - 0.5 <= $averageRating)
                                <i class="fas fa-star-half-alt text-warning me-1"></i>
                            @else
                                <i class="far fa-star me-1" style="color: rgba(255, 255, 255, 0.5);"></i>
                            @endif
                        @endfor
                        <span class="ms-2 small">({{ $ratings->count() }} ratings)</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Feedback Card -->
        <div class="col-md-4">
            <div class="card text-white border-0 shadow-sm" style="background: linear-gradient(135deg, #0891B2 0%, #06B6D4 100%);">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="card-title mb-0"><i class="fas fa-comment-dots me-2"></i>Total Feedback</h5>
                        <div style="background-color: rgba(255, 255, 255, 0.15); backdrop-filter: blur(4px);" class="p-2 rounded-circle">
                            <i class="fas fa-comments"></i>
                        </div>
                    </div>
                    <h2 class="display-6 fw-bold mb-2">{{ number_format($totalFeedback) }}</h2>
                    <div class="progress" style="height: 6px; background-color: rgba(255, 255, 255, 0.2);">
                        <div class="progress-bar" style="background-color: rgba(255, 255, 255, 0.8);" role="progressbar"
                             style="width: {{ ($recentFeedback / max($totalFeedback, 1)) * 100 }}%"
                             aria-valuenow="{{ $recentFeedback }}" aria-valuemin="0" aria-valuemax="{{ $totalFeedback }}">
                        </div>
                    </div>
                    <div class="d-flex justify-content-between mt-2">
                        <small style="color: rgba(255, 255, 255, 0.8);">{{ round(($recentFeedback / max($totalFeedback, 1)) * 100) }}% recent</small>
                        <small style="color: rgba(255, 255, 255, 0.8);">Last 30 days</small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Activity Card -->
        <div class="col-md-4">
            <div class="card text-white border-0 shadow-sm" style="background: linear-gradient(135deg, #DB2777 0%, #EC4899 100%);">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="card-title mb-0"><i class="fas fa-clock me-2"></i>Recent Activity</h5>
                        <div style="background-color: rgba(255, 255, 255, 0.15); backdrop-filter: blur(4px);" class="p-2 rounded-circle">
                            <i class="fas fa-bolt"></i>
                        </div>
                    </div>
                    <h2 class="display-6 fw-bold mb-2">{{ number_format($recentFeedback) }}</h2>
                    <p class="mb-2" style="color: rgba(255, 255, 255, 0.9);">New feedback in last 30 days</p>
                    @php
                        $trend = $recentFeedback > 0 ? (($recentFeedback / max($totalFeedback - $recentFeedback, 1)) * 100) : 0;
                        $isPositive = $trend >= 0;
                    @endphp
                    <div class="d-flex align-items-center">
                        <span class="badge me-2" style="background-color: rgba(255, 255, 255, 0.2); color: white;">
                            <i class="fas {{ $isPositive ? 'fa-arrow-up' : 'fa-arrow-down' }} me-1"></i>
                            {{ abs(round($trend)) }}%
                        </span>
                        <small style="color: rgba(255, 255, 255, 0.8);">vs previous period</small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Export Button -->
    <div class="row mb-3">
        <div class="col-md-12 text-right">
            <a href="{{ route('reports.customers.export.feedback.pdf', request()->query()) }}" class="btn btn-lg btn-secondary">
                <i class="fas fa-file-pdf"></i> Export PDF
            </a>
        </div>
    </div>

    <!-- Feedback Table -->
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Customer Feedback</h5>
            <span class="badge badge-primary">{{ $ratings->total() }} Reviews</span>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Customer</th>
                            <th>Service</th>
                            <th>Cleaner</th>
                            <th>Rating</th>
                            <th>Feedback</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($ratings as $rating)
                            <tr>
                                <td>{{ $rating->created_at->format('M d, Y') }}</td>
                                <td>{{ $rating->customer->name ?? 'N/A' }}</td>
                                <td>{{ $rating->job->service->name ?? 'N/A' }}</td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        @php
                                            $photoUrl = $rating->employee ? $rating->employee->getPrimaryPhotoUrl() : null;
                                        @endphp
                                        @if($photoUrl)
                                            <img src="{{ $photoUrl }}" alt="{{ $rating->employee->name }}" class="rounded-circle me-2" style="width: 32px; height: 32px; object-fit: cover;">
                                        @else
                                            <img src="{{ asset('images/default-avatar.png') }}" alt="No Photo" class="rounded-circle me-2" style="width: 32px; height: 32px; object-fit: cover;">
                                        @endif
                                        <span>{{ $rating->employee->name ?? 'N/A' }}</span>
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        @for($i = 1; $i <= 5; $i++)
                                            @if($i <= $rating->rating)
                                                <i class="fas fa-star text-warning me-1"></i>
                                            @else
                                                <i class="far fa-star text-muted me-1"></i>
                                            @endif
                                        @endfor
                                        <span class="ms-2 fw-bold">{{ number_format($rating->rating, 1) }}</span>
                                    </div>
                                </td>
                                <td>{{ $rating->comments ?? 'No feedback provided' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted">No feedback records found</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($ratings->hasPages())
                <div class="d-flex justify-content-center mt-4">
                    {{ $ratings->appends(request()->query())->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .card {
        border-radius: 0.75rem;
        transition: transform 0.2s, box-shadow 0.2s;
    }

    .card:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
    }

    .card-title {
        font-weight: 600;
        color: #1e293b;
    }

    .progress {
        border-radius: 10px;
        overflow: hidden;
    }

    .badge {
        font-weight: 500;
        padding: 0.4em 0.8em;
    }

    .table {
        --bs-table-hover-bg: rgba(0, 0, 0, 0.02);
    }

    .table thead th {
        background-color: #f8fafc;
        color: #64748b;
        font-weight: 600;
        text-transform: uppercase;
        font-size: 0.8rem;
        letter-spacing: 0.5px;
        border-bottom: 2px solid #e2e8f0;
    }

    .table tbody td {
        vertical-align: middle;
        border-bottom: 1px solid #edf2f7;
    }

    /* Star Rating */
    .fa-star.text-warning {
        color: #f59e0b !important;
        text-shadow: 0 1px 2px rgba(0, 0, 0, 0.1);
    }

    .fa-star.text-muted {
        color: #cbd5e1 !important;
    }

    /* Responsive adjustments */
    @media (max-width: 768px) {
        .table thead {
            display: none;
        }

        .table, .table tbody, .table tr, .table td {
            display: block;
            width: 100%;
        }

        .table tr {
            margin-bottom: 1rem;
            border: 1px solid #e2e8f0;
            border-radius: 0.5rem;
            overflow: hidden;
        }

        .table td {
            padding: 0.75rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            text-align: right;
        }

        .table td::before {
            content: attr(data-label);
            font-weight: 600;
            color: #64748b;
            margin-right: 1rem;
            text-transform: capitalize;
            flex: 1;
        }

        .table td:first-child {
            background-color: #f8fafc;
            font-weight: 600;
        }
    }
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Form submission handler (can be extended if needed)
    document.querySelector('form').addEventListener('submit', function(e) {
        // Form will submit normally
    });
});
</script>
@endpush