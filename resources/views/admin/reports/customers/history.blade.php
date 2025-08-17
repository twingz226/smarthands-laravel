@include('admin.partials.header')

<div class="main-content">
    <div class="container">
        <h3>🧼 Cleaning History Report</h3>

        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Customer</th>
                    <th>Service</th>
                    <th>Employee</th>
                    <th>Date</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($customers as $customer)
                    @foreach($customer->jobs as $job)
                        <tr>
                            <td>{{ $customer->name }}</td>
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
                            <td>{{ $job->created_at->format('Y-m-d') }}</td>
                            <td>
                                <span class="badge bg-success">{{ $job->status }}</span>
                            </td>
                        </tr>
                    @endforeach
                @empty
                    <tr>
                        <td colspan="5" class="text-center">No completed jobs found</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        @if($customers->hasPages())
            <div class="d-flex justify-content-center mt-3">
                {{ $customers->links() }}
            </div>
        @endif
    </div>
</div>

@include('admin.partials.scripts')