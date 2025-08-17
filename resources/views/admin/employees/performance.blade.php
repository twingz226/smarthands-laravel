@include('admin.partials.header')

<div class="container">
    <h2 class="mb-4">Employee Performance</h2>

    <div class="row mb-3">
        <div class="col-md-4">
            <div class="card text-bg-light">
                <div class="card-body">
                    <h5 class="card-title">Average Rating</h5>
                    <p class="display-6">{{ number_format($performanceMetrics['average_rating'], 2) }}/5</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-bg-light">
                <div class="card-body">
                    <h5 class="card-title">Total Completed Jobs</h5>
                    <p class="display-6">{{ $performanceMetrics['total_completed'] }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-bg-light">
                <div class="card-body">
                    <h5 class="card-title">Top Performer</h5>
                    <p class="display-6">{{ $performanceMetrics['top_performer']?->name ?? 'N/A' }}</p>
                </div>
            </div>
        </div>
    </div>

    <table class="table table-bordered">
        <thead class="table-secondary">
            <tr>
                <th>Name</th>
                <th>Completed Jobs</th>
                <th>Active Jobs</th>
                <th>Average Rating</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($employees as $employee)
            <tr>
                <td>{{ $employee->name }}</td>
                <td>{{ $employee->completed_jobs_count }}</td>
                <td>{{ $employee->active_jobs_count }}</td>
                <td>{{ number_format($employee->ratings_avg_rating, 2) ?? 'N/A' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    {{ $employees->links() }}
</div>


@include('admin.partials.scripts')