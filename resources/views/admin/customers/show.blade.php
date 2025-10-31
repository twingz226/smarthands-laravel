@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <!-- Customer Details Card -->
    <div class="card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="m-0">Customer Details</h5>
            <div class="card-tools">
                <a href="{{ route('admin.customers.index') }}" class="btn btn-sm btn-secondary">
                    <i class="fas fa-arrow-left"></i> Back to List
                </a>
            </div>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <p><strong>Customer ID:</strong> {{ $customer->customer_id }}</p>
                    <p><strong>Name:</strong> {{ $customer->name }}</p>
                    <p><strong>Email:</strong> {{ $customer->email }}</p>
                </div>
                <div class="col-md-6">
                    <p><strong>Contact:</strong> {{ $customer->contact }}</p>
                    <p><strong>Registered Date:</strong> {{ $customer->registered_date->format('M d, Y') }}</p>
                    <p>
                        <strong>Status:</strong>
                        @if($customer->is_archived)
                            <span class="badge bg-secondary">Archived</span>
                            <small class="text-muted">({{ $customer->archived_at->format('M d, Y') }})</small>
                            @if($customer->archive_reason)
                                <br>
                                <small class="text-muted">Reason: {{ $customer->archive_reason }}</small>
                            @endif
                        @else
                            <span class="badge" style="background-color: #28a745;">Active</span>
                        @endif
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Customer Jobs Card -->
    <div class="card">
        <div class="card-header">
            <h5 class="m-0">Job History</h5>
        </div>
        <div class="card-body">
            @if($jobs->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Service</th>
                                <th>Employee</th>
                                <th>Status</th>
                                <th>Scheduled Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($jobs as $job)
                                <tr>
                                    <td>{{ $job->service->name }}</td>
                                    <td>
                                        @if($job->employees->count() > 0)
                                            @foreach($job->employees as $employee)
                                                <span class="badge bg-info">{{ $employee->name }}</span>
                                            @endforeach
                                        @else
                                            <span class="text-muted">Not Assigned</span>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge bg-{{ $job->status === 'completed' ? 'success' : ($job->status === 'in_progress' ? 'primary' : 'warning') }}">
                                            {{ ucfirst($job->status) }}
                                        </span>
                                    </td>
                                    <td>{{ $job->scheduled_date->format('M d, Y') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                {{ $jobs->links() }}
            @else
                <p class="text-muted">No jobs found for this customer.</p>
            @endif
        </div>
    </div>
</div>
@endsection 