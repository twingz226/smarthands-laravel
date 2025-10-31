@include('admin.partials.header')

<div class="container-fluid">
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Customer Feedback Management</h1>
        <div class="btn-toolbar mb-2 mb-md-0">
            <a href="{{ route('admin.feedback.analytics') }}" class="btn btn-sm btn-info">
                <i class="entypo-chart"></i> Analytics
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h4 class="card-title">{{ $stats['total'] }}</h4>
                            <p class="card-text">Total Feedback</p>
                        </div>
                        <div class="align-self-center">
                            <i class="entypo-chat" style="font-size: 2rem;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-warning text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h4 class="card-title">{{ $stats['pending'] }}</h4>
                            <p class="card-text">Pending Review</p>
                        </div>
                        <div class="align-self-center">
                            <i class="entypo-clock" style="font-size: 2rem;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h4 class="card-title">{{ $stats['positive'] }}</h4>
                            <p class="card-text">Positive (4-5★)</p>
                        </div>
                        <div class="align-self-center">
                            <i class="entypo-thumbs-up" style="font-size: 2rem;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-info text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h4 class="card-title">{{ number_format($stats['average_rating'], 1) }}</h4>
                            <p class="card-text">Average Rating</p>
                        </div>
                        <div class="align-self-center">
                            <i class="entypo-star" style="font-size: 2rem;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" class="row g-3">
                <div class="col-md-2">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-select">
                        <option value="">All Status</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="reviewed" {{ request('status') == 'reviewed' ? 'selected' : '' }}>Reviewed</option>
                        <option value="responded" {{ request('status') == 'responded' ? 'selected' : '' }}>Responded</option>
                        <option value="resolved" {{ request('status') == 'resolved' ? 'selected' : '' }}>Resolved</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label">Rating</label>
                    <select name="rating" class="form-select">
                        <option value="">All Ratings</option>
                        <option value="positive" {{ request('rating') == 'positive' ? 'selected' : '' }}>Positive (4-5★)</option>
                        <option value="negative" {{ request('rating') == 'negative' ? 'selected' : '' }}>Negative (1-2★)</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label">Start Date</label>
                    <input type="date" name="start_date" class="form-control" value="{{ request('start_date') }}">
                </div>
                <div class="col-md-2">
                    <label class="form-label">End Date</label>
                    <input type="date" name="end_date" class="form-control" value="{{ request('end_date') }}">
                </div>
                <div class="col-md-4 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary me-2">Filter</button>
                    <a href="{{ route('admin.feedback.index') }}" class="btn btn-secondary">Clear</a>
                </div>
            </form>
        </div>
    </div>

    <!-- Feedback Table -->
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th>ID</th>
                            <th>Customer</th>
                            <th>Job</th>
                            <th>Rating</th>
                            <th>Comments</th>
                            <th>Status</th>
                            <th>Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($feedback as $item)
                            <tr>
                                <td>#{{ $item->id }}</td>
                                <td>
                                    @if($item->is_anonymous)
                                        <span class="text-muted">Anonymous</span>
                                    @else
                                        {{ $item->customer->name }}
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('admin.jobs.show', $item->job_id) }}" class="text-decoration-none">
                                        Job #{{ $item->job_id }}
                                    </a>
                                    <br>
                                    <small class="text-muted">{{ $item->job->service->name }}</small>
                                </td>
                                <td>
                                    <div class="text-warning">
                                        @for($i = 1; $i <= 5; $i++)
                                            @if($i <= $item->overall_rating)
                                                ★
                                            @else
                                                ☆
                                            @endif
                                        @endfor
                                    </div>
                                    <small class="text-muted">{{ $item->overall_rating }}/5</small>
                                </td>
                                <td>
                                    @if($item->comments)
                                        <span class="text-truncate d-inline-block" style="max-width: 200px;" title="{{ $item->comments }}">
                                            {{ Str::limit($item->comments, 50) }}
                                        </span>
                                    @else
                                        <span class="text-muted">No comments</span>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge bg-{{ $item->status === 'pending' ? 'warning' : ($item->status === 'reviewed' ? 'info' : ($item->status === 'responded' ? 'primary' : 'success')) }}">
                                        {{ ucfirst($item->status) }}
                                    </span>
                                </td>
                                <td>{{ $item->created_at->format('M d, Y H:i') }}</td>
                                <td>
                                    <a href="{{ route('admin.feedback.show', $item) }}" class="btn btn-sm btn-info">
                                        <i class="entypo-eye"></i> View
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center">No feedback found</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="d-flex justify-content-center mt-4">
                {{ $feedback->links() }}
            </div>
        </div>
    </div>
</div>

@include('admin.partials.scripts') 