@include('admin.partials.header')

<div class="row">
    <div class="col-sm-12">
        <div class="well">
            <div class="container-fluid py-4 px-5">
                <h3>Welcome to <strong> Smarthands Cleaning Service Management System</strong></h3>
            </div>
        </div>
    </div>
</div>

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
                            <td>{{ $job->employee->name ?? 'N/A' }}</td>
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