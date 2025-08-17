@extends('layouts.admin')

@section('content')
<div class="container">
    <h2>🧹 Job Completion Report</h2>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <!-- Filters Section -->
    <div class="card mb-4">
        <div class="card-header">
            <h5>Filters</h5>
        </div>
        <div class="card-body">
            <form method="GET" action="">
                <div class="row">
                    <div class="col-md-3">
                        <label for="start_date" class="form-label">From Date</label>
                        <input type="date" class="form-control" id="start_date" name="start_date" 
                            value="{{ $request->start_date }}">
                    </div>
                    <div class="col-md-3">
                        <label for="end_date" class="form-label">To Date</label>
                        <input type="date" class="form-control" id="end_date" name="end_date" 
                            value="{{ $request->end_date }}">
                    </div>
                    <div class="col-md-3 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary me-2">Apply</button>
                        <a href="{{ url()->current() }}" class="btn btn-outline-secondary">Reset</a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card text-white bg-success mb-3">
                <div class="card-body">
                    <h5 class="card-title">Completed Jobs</h5>
                    <h2 class="card-text">{{ number_format($completionStats['completed']) }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-white bg-warning mb-3">
                <div class="card-body">
                    <h5 class="card-title">Pending Jobs</h5>
                    <h2 class="card-text">{{ number_format($completionStats['pending']) }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-white bg-danger mb-3">
                <div class="card-body">
                    <h5 class="card-title">Cancelled Bookings</h5>
                    <p class="card-subtitle mb-2" style="font-size: 0.8rem; opacity: 0.8;">(From Online Booking System)</p>
                    <h2 class="card-text">{{ number_format($completionStats['cancelled']) }}</h2>
                </div>
            </div>
        </div>
    </div>

    <!-- Jobs Table -->
    <div class="card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5>Completed Jobs</h5>
            <span class="badge bg-primary">Total: {{ $jobs->total() }}</span>
        </div>
        <div class="card-body">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Job ID</th>
                        <th>Customer Name</th>
                        <th>Service</th>
                        <th>Cleaner Assigned</th>
                        <th>Date Completed</th>
                        <th>Rating</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($jobs as $job)
                        <tr>
                            <td>J{{ str_pad($job->id, 3, '0', STR_PAD_LEFT) }}</td>
                            <td>{{ $job->customer->name ?? 'N/A' }}</td>
                            <td>{{ $job->service->name ?? 'N/A' }}</td>
                            <td>
                                @if($job->employees->count() > 0)
                                    @foreach($job->employees as $employee)
                                        <span class="badge bg-info">{{ $employee->name }}</span>
                                    @endforeach
                                @else
                                    <span class="text-muted">Not Assigned</span>
                                @endif
                            </td>
                            <td>{{ $job->completed_at ? $job->completed_at->format('M d, Y') : 'N/A' }}</td>
                            <td>
                                @if($job->rating)
                                    <div class="star-rating">
                                        @for($i = 1; $i <= 5; $i++)
                                            @if($i <= $job->rating->rating)
                                                <span class="star filled">★</span>
                                            @else
                                                <span class="star">☆</span>
                                            @endif
                                        @endfor
                                        <span class="rating-value">({{ number_format($job->rating->rating, 1) }})</span>
                                    </div>
                                @else
                                    No rating
                                @endif
                            </td>
                            <td>
                                <span class="badge bg-success">{{ ucfirst($job->status) }}</span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center">No completed jobs found</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            <!-- Pagination -->
            @if($jobs->hasPages())
                <div class="d-flex justify-content-center mt-3">
                    {{ $jobs->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .star-rating {
        display: inline-flex;
        align-items: center;
    }
    .star {
        color: #ddd;
        font-size: 1rem;
    }
    .star.filled {
        color: #ffc107;
    }
    .rating-value {
        margin-left: 5px;
        font-size: 0.8rem;
        color: #666;
    }
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Debug: Log the data to console
        console.log('Cleaner Names:', {!! json_encode($cleanerRatings->pluck('name')->toArray()) !!});
        console.log('Cleaner Ratings:', {!! json_encode($cleanerRatings->pluck('ratings_avg_rating')->toArray()) !!});
    });
</script>
@endpush
