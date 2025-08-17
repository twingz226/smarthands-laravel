@include('admin.partials.header')

    <!-- Main Content -->
    <div class="main-content">
        <div class="container">
        <h2>⭐ Customer Feedback & Ratings Report</h2>

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
                            <label for="rating" class="form-label">Minimum Rating</label>
                            <select class="form-select" id="rating" name="rating">
                                <option value="">All Ratings</option>
                                @for($i = 5; $i >= 1; $i--)
                                    <option value="{{ $i }}" {{ request('rating') == $i ? 'selected' : '' }}>
                                        {{ $i }}+ Stars
                                    </option>
                                @endfor
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="start_date" class="form-label">From Date</label>
                            <input type="date" class="form-control" id="start_date" name="start_date" 
                                value="{{ request('start_date') }}">
                        </div>
                        <div class="col-md-3">
                            <label for="end_date" class="form-label">To Date</label>
                            <input type="date" class="form-control" id="end_date" name="end_date" 
                                value="{{ request('end_date') }}">
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
                <div class="card text-white bg-primary mb-3">
                    <div class="card-body">
                        <h5 class="card-title">Average Rating</h5>
                        <h2 class="card-text">{{ number_format($averageRating, 1) }} / 5.0</h2>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card text-white bg-success mb-3">
                    <div class="card-body">
                        <h5 class="card-title">Total Feedback</h5>
                        <h2 class="card-text">{{ number_format($totalFeedback) }}</h2>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card text-white bg-info mb-3">
                    <div class="card-body">
                        <h5 class="card-title">Recent Feedback</h5>
                        <h2 class="card-text">{{ number_format($recentFeedback) }}</h2>
                        <p class="card-text"><small>(Last 30 days)</small></p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Feedback Table -->
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5>Customer Feedback</h5>
                <span class="badge bg-primary">Total: {{ $ratings->total() }}</span>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
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
                            <td>{{ $rating->employee->name ?? 'N/A' }}</td>
                            <td>
                                <div class="star-rating" title="{{ $rating->rating }} out of 5">
                                    @for($i = 1; $i <= 5; $i++)
                                                @if($i <= $rating->rating)
                                            <span class="star filled">★</span>
                                        @else
                                            <span class="star">☆</span>
                                        @endif
                                    @endfor
                                    <span class="rating-value">({{ number_format($rating->rating, 1) }})</span>
                                </div>
                            </td>
                                    <td>{{ $rating->comments ?? 'No feedback provided' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center">No feedback records found</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
                </div>

                <!-- Pagination -->
            @if($ratings->hasPages())
                <div class="d-flex justify-content-center mt-3">
                    {{ $ratings->links() }}
                </div>
            @endif
            </div>
        </div>
        </div>
    </div>

@push('styles')
<style>
    .star-rating {
        display: inline-flex;
        align-items: center;
    }
    .star {
        color: #ddd;
        font-size: 1.2rem;
    }
    .star.filled {
        color: #ffc107;
    }
    .star.half {
        position: relative;
    }
    .star.half:before {
        position: absolute;
        content: '★';
        width: 50%;
        overflow: hidden;
        color: #ffc107;
    }
    .rating-value {
        margin-left: 5px;
        font-size: 0.9rem;
        color: #666;
    }
</style>
@endpush

@include('admin.partials.scripts')