@include('admin.partials.header')

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

    <div class="row">
        <div class="col-md-6">
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
        </div>

        <div class="col-md-6">
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

@include('admin.partials.scripts') 