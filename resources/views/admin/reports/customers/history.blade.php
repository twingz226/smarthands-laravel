@extends('layouts.admin')

@section('content')
<div class="main-content">
    <div class="container">
        <div class="row">
            <div class="col-md-12 text-center">
                <h1 class="h2 pt-3 pb-2 mb-3 border-bottom">Cleaning History Report</h1>
            </div>
        </div>

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
                            <a href="{{ request()->url() }}" class="btn btn-danger w-100 text-white">
                                <i class="fas fa-undo"></i> Reset Filters
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12 text-right">
                <a href="{{ route('reports.customers.export.cleaning-history.pdf', request()->query()) }}" class="btn btn-lg btn-secondary">
                    <i class="fas fa-file-pdf"></i> Export PDF
                </a>
            </div>
        </div>

        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <table class="table table-hover">
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

@push('scripts')
<script>
    // Auto-submit form when date inputs change
    document.addEventListener('DOMContentLoaded', function() {
        const dateInputs = document.querySelectorAll('input[type="date"]');
        dateInputs.forEach(input => {
            input.addEventListener('change', function() {
                this.closest('form').submit();
            });
        });
    });
</script>
@endpush

@endsection