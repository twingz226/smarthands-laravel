@extends('layouts.admin')

@section('content')
<div class="main-content">
    <div class="container">
        <div class="row">
            <div class="col-md-12 text-center">
                <h1 class="h2 pt-3 pb-2 mb-3 border-bottom">Cleaning History Report</h1>
            </div>
            <div class="col-md-12 text-right">
                <div class="dropdown">
                    <button class="btn btn-lg btn-secondary dropdown-toggle" type="button" id="exportDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="fas fa-print"></i> Export
                    </button>
                    <ul class="dropdown-menu dropdown-menu-right" aria-labelledby="exportDropdown">
                        <li><a href="#" onclick="window.print(); return false;">
                            <i class="fas fa-print text-secondary"></i> Print
                        </a></li>
                        <li><a href="{{ route('reports.customers.export.cleaning-history.pdf') }}">
                            <i class="fas fa-file-pdf text-danger"></i> PDF
                        </a></li>
                        <li><a href="{{ route('reports.customers.export.cleaning-history.excel') }}">
                            <i class="fas fa-file-excel text-success"></i> Excel
                        </a></li>
                        <li><a href="{{ route('reports.customers.export.cleaning-history.csv') }}">
                            <i class="fas fa-file-csv text-primary"></i> CSV
                        </a></li>
                    </ul>
                </div>
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
@endsection