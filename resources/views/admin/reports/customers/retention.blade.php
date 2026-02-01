@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Customer Retention Report</h1>
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

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Retention Metrics Cards -->
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card text-white border-0 shadow-sm" style="background: linear-gradient(135deg, #4F46E5 0%, #7C3AED 100%);">
                <div class="card-body d-flex flex-column">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="card-title mb-0"><i class="fas fa-users me-2"></i>Total Customers</h5>
                        <div style="background-color: rgba(255, 255, 255, 0.15); backdrop-filter: blur(4px);" class="p-2 rounded-circle">
                            <i class="fas fa-users"></i>
                        </div>
                    </div>
                    <h2 class="display-5 fw-bold mb-0">{{ number_format($totalCustomers) }}</h2>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card text-white border-0 shadow-sm" style="background: linear-gradient(135deg, #0891B2 0%, #06B6D4 100%);">
                <div class="card-body d-flex flex-column">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="card-title mb-0"><i class="fas fa-redo me-2"></i>Repeat Customers</h5>
                        <div style="background-color: rgba(255, 255, 255, 0.15); backdrop-filter: blur(4px);" class="p-2 rounded-circle">
                            <i class="fas fa-redo"></i>
                        </div>
                    </div>
                    <h2 class="display-5 fw-bold mb-1">{{ number_format($repeatCustomers) }}</h2>
                    <p class="mb-0" style="color: rgba(255, 255, 255, 0.8);">
                        {{ $totalCustomers > 0 ? number_format(($repeatCustomers/$totalCustomers)*100, 1) : 0 }}% of total customers
                    </p>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card text-white border-0 shadow-sm" style="background: linear-gradient(135deg, #DB2777 0%, #EC4899 100%);">
                <div class="card-body d-flex flex-column">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="card-title mb-0"><i class="fas fa-user-plus me-2"></i>New Customers</h5>
                        <div style="background-color: rgba(255, 255, 255, 0.15); backdrop-filter: blur(4px);" class="p-2 rounded-circle">
                            <i class="fas fa-user-plus"></i>
                        </div>
                    </div>
                    <h2 class="display-5 fw-bold mb-0">{{ number_format($newCustomersLastMonth) }}</h2>
                    <p class="mb-0" style="color: rgba(255, 255, 255, 0.8);">Last 30 days</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Customer Distribution by Job Count -->
    <div class="card mb-4">
        <div class="card-header">
            <h4 class="mb-0">Customer Distribution by Number of Jobs</h4>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Job Count Range</th>
                                    <th>Customers</th>
                                    <th>Percentage</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($customersByJobCount as $range => $count)
                                    <tr>
                                        <td>{{ $range }}</td>
                                        <td>{{ number_format($count) }}</td>
                                        <td>
                                            {{ $totalCustomers > 0 ? number_format(($count/$totalCustomers)*100, 1) : 0 }}%
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="col-md-6">
                    <canvas id="jobCountChart" height="250"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Export Button -->
    <div class="row mb-3">
        <div class="col-md-12 text-right">
            <a href="{{ route('reports.customers.export.retention.pdf', request()->query()) }}" class="btn btn-lg btn-secondary">
                <i class="fas fa-file-pdf"></i> Export PDF
            </a>
        </div>
    </div>

    <!-- Detailed Customer List -->
    <div class="card">
        <div class="card-header">
            <h4 class="mb-1">Top Repeat Customers</h4>
            <p class="mb-0 text-muted">Showing customers with the most bookings</p>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Customer</th>
                            <th>Total Bookings</th>
                            <th>First Booking</th>
                            <th>Last Booking</th>
                            <th>Booking Frequency</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($topCustomers as $customer)
                            <tr>
                                <td>{{ $customer->name }}</td>
                                <td><span class="badge bg-primary">{{ $customer->jobs_count }}</span></td>
                                <td>{{ $customer->first_booking }}</td>
                                <td>{{ $customer->last_booking }}</td>
                                <td>
                                    @if($customer->first_booking != 'N/A' && $customer->last_booking != 'N/A')
                                        @php
                                            $first = \Carbon\Carbon::parse($customer->first_booking);
                                            $last = \Carbon\Carbon::parse($customer->last_booking);
                                            $daysBetween = $first->diffInDays($last);
                                            $frequency = $daysBetween > 0 ? round($customer->jobs_count / ($daysBetween/30), 1) : $customer->jobs_count;
                                        @endphp
                                        <span class="badge bg-info">{{ $frequency }} jobs/month</span>
                                    @else
                                        <span class="text-muted">N/A</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted">No repeat customers found</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .card {
        border-radius: 0.75rem;
        transition: transform 0.2s, box-shadow 0.2s;
    }

    .card:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
    }

    .card-title {
        font-weight: 600;
        color: #1e293b;
    }

    .table {
        --bs-table-hover-bg: rgba(0, 0, 0, 0.02);
    }

    .table thead th {
        background-color: #f8fafc;
        color: #64748b;
        font-weight: 600;
        text-transform: uppercase;
        font-size: 0.8rem;
        letter-spacing: 0.5px;
        border-bottom: 2px solid #e2e8f0;
    }

    .table tbody td {
        vertical-align: middle;
        border-bottom: 1px solid #edf2f7;
    }

    .badge {
        font-weight: 500;
        padding: 0.4em 0.8em;
    }

    /* Responsive adjustments */
    @media (max-width: 768px) {
        .table thead {
            display: none;
        }

        .table, .table tbody, .table tr, .table td {
            display: block;
            width: 100%;
        }

        .table tr {
            margin-bottom: 1rem;
            border: 1px solid #e2e8f0;
            border-radius: 0.5rem;
            overflow: hidden;
        }

        .table td {
            padding: 0.75rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            text-align: right;
        }

        .table td::before {
            content: attr(data-label);
            font-weight: 600;
            color: #64748b;
            margin-right: 1rem;
            text-transform: capitalize;
            flex: 1;
        }

        .table td:first-child {
            background-color: #f8fafc;
            font-weight: 600;
        }
    }
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Auto-submit form when date inputs change
    const dateInputs = document.querySelectorAll('input[type="date"]');
    dateInputs.forEach(input => {
        input.addEventListener('change', function() {
            this.closest('form').submit();
        });
    });

    // Job Count Distribution Chart
    const jobCountCtx = document.getElementById('jobCountChart').getContext('2d');
    const jobCountChart = new Chart(jobCountCtx, {
        type: 'doughnut',
        data: {
            labels: {!! json_encode(array_keys($customersByJobCount)) !!},
            datasets: [{
                data: {!! json_encode(array_values($customersByJobCount)) !!},
                backgroundColor: [
                    'rgba(79, 70, 229, 0.7)',
                    'rgba(8, 145, 178, 0.7)',
                    'rgba(219, 39, 119, 0.7)',
                    'rgba(245, 158, 11, 0.7)',
                    'rgba(34, 197, 94, 0.7)'
                ],
                borderColor: [
                    'rgba(79, 70, 229, 1)',
                    'rgba(8, 145, 178, 1)',
                    'rgba(219, 39, 119, 1)',
                    'rgba(245, 158, 11, 1)',
                    'rgba(34, 197, 94, 1)'
                ],
                borderWidth: 2
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'right',
                    labels: {
                        padding: 20,
                        usePointStyle: true,
                        font: {
                            size: 12
                        }
                    }
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            const total = context.dataset.data.reduce((a, b) => a + b, 0);
                            const value = context.raw;
                            const percentage = Math.round((value / total) * 100);
                            return `${context.label}: ${value} customers (${percentage}%)`;
                        }
                    }
                }
            },
            cutout: '60%'
        }
    });
});
</script>
@endpush