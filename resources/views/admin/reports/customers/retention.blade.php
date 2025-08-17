@include('admin.partials.header')


    <!-- Main Content -->
    <div class="main-content">
        <div class="container">
            <h3>🔁 Retention Report</h3>
            
            @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            <!-- Retention Metrics Cards -->
            <div class="row mb-4">
                <div class="col-md-4">
                    <div class="card text-white bg-primary mb-3">
                        <div class="card-body">
                            <h5 class="card-title">Total Customers</h5>
                            <h2 class="card-text">{{ number_format($totalCustomers) }}</h2>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card text-white bg-success mb-3">
                        <div class="card-body">
                            <h5 class="card-title">Repeat Customers</h5>
                            <h2 class="card-text">{{ number_format($repeatCustomers) }}</h2>
                            <p class="card-text">
                                {{ $totalCustomers > 0 ? number_format(($repeatCustomers/$totalCustomers)*100, 1) : 0 }}% of total
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card text-white bg-info mb-3">
                        <div class="card-body">
                            <h5 class="card-title">New Customers (Last 30 Days)</h5>
                            <h2 class="card-text">{{ number_format($newCustomersLastMonth) }}</h2>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Customer Distribution by Job Count -->
            <div class="card mb-4">
                <div class="card-header">
                    <h4>Customer Distribution by Number of Jobs</h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-bordered">
                                <thead class="thead-light">
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
                        <div class="col-md-6">
                            <canvas id="jobCountChart" height="250"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Detailed Customer List -->
            <div class="card">
                <div class="card-header">
                    <h4>Top Repeat Customers</h4>
                    <p class="mb-0">Showing customers with the most bookings</p>
                </div>
                <div class="card-body">
                    <table class="table table-bordered">
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
                                    <td>{{ $customer->jobs_count }}</td>
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
                                            {{ $frequency }} jobs/month
                                        @else
                                            N/A
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center">No repeat customers found</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>


@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Job Count Distribution Chart
        const jobCountCtx = document.getElementById('jobCountChart').getContext('2d');
        const jobCountChart = new Chart(jobCountCtx, {
            type: 'doughnut',
            data: {
                labels: {!! json_encode(array_keys($customersByJobCount)) !!},
                datasets: [{
                    data: {!! json_encode(array_values($customersByJobCount)) !!},
                    backgroundColor: [
                        'rgba(54, 162, 235, 0.7)',
                        'rgba(75, 192, 192, 0.7)',
                        'rgba(255, 159, 64, 0.7)'
                    ],
                    borderColor: [
                        'rgba(54, 162, 235, 1)',
                        'rgba(75, 192, 192, 1)',
                        'rgba(255, 159, 64, 1)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'right',
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                const value = context.raw;
                                const percentage = Math.round((value / total) * 100);
                                return `${context.label}: ${value} (${percentage}%)`;
                            }
                        }
                    }
                }
            }
        });
    });
</script>
@endpush

@include('admin.partials.scripts')