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

<div class="row">
    <div class="col-sm-12">
        <!-- Summary Cards -->
        <div class="row g-3 mb-4">
            <div class="col-lg-3 col-md-6">
                <div class="card text-white bg-primary dashboard-card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6>Total Customers</h6>
                                <h3>{{ $customerCount }}</h3>
                            </div>
                            <i class="bi bi-people fs-1"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-white bg-success dashboard-card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6>Active Jobs</h6>
                                <h3>{{ $activeJobCount }}</h3>
                            </div>
                            <i class="bi bi-briefcase fs-1"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-white bg-info dashboard-card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6>Total Cleaners</h6>
                                <h3>{{ $cleanerCount }}</h3>
                            </div>
                            <i class="bi bi-person-badge fs-1"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-white bg-warning dashboard-card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6>Pending Bookings</h6>
                                <h3>{{ $pendingBookingCount }}</h3>
                            </div>
                            <i class="bi bi-calendar-check fs-1"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Left Column - Recent Activities -->
            <div class="col-md-8">
                <div class="row">
                    <div class="col-md-12 mb-4">
                        <div class="card">
                            <div class="card-header">
                                <h6>Recent Jobs</h6>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-sm">
                                        <thead>
                                            <tr>
                                                <th>Job ID</th>
                                                <th>Customer</th>
                                                <th>Service</th>
                                                <th>Status</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($recentJobs as $job)
                                                <tr>
                                                    <td>J{{ str_pad($job->id, 3, '0', STR_PAD_LEFT) }}</td>
                                                    <td>{{ $job->customer->name ?? 'N/A' }}</td>
                                                    <td>{{ $job->service->name ?? 'N/A' }}</td>
                                                    <td>
                                                        <span class="badge bg-{{ $job->status === 'completed' ? 'success' : ($job->status === 'in_progress' ? 'primary' : 'warning') }}">
                                                            {{ ucfirst($job->status) }}
                                                        </span>
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="4" class="text-center">No recent jobs</td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-12 mb-4">
                        <div class="card">
                            <div class="card-header">
                                <h6>Recent Bookings</h6>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-sm">
                                        <thead>
                                            <tr>
                                                <th>Booking ID</th>
                                                <th>Customer</th>
                                                <th>Service</th>
                                                <th>Status</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($recentBookings as $booking)
                                                <tr>
                                                    <td>B{{ str_pad($booking->id, 3, '0', STR_PAD_LEFT) }}</td>
                                                    <td>{{ $booking->customer->name ?? 'N/A' }}</td>
                                                    <td>{{ $booking->service->name ?? 'N/A' }}</td>
                                                    <td>
                                                        <span class="badge bg-{{ $booking->status === 'confirmed' ? 'success' : ($booking->status === 'pending' ? 'warning' : 'danger') }}">
                                                            {{ ucfirst($booking->status) }}
                                                        </span>
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="4" class="text-center">No recent bookings</td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Cleaner Ratings Chart -->
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header">
                                <h6>Cleaner Ratings</h6>
                            </div>
                            <div class="card-body" style="height: 400px;">
                                <canvas id="cleanerRatingsChart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column - Jobs Status Chart -->
            <div class="col-md-4">
                <!-- Add invisible card with same height as Recent Jobs to push Job Status down -->
                <div class="card mb-4 invisible" style="height: 265px;">
                </div>
                <div class="card">
                    <div class="card-header">
                        <h6>Jobs Status</h6>
                    </div>
                    <div class="card-body d-flex align-items-center" style="height: 300px;">
                        <canvas id="jobStatusChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@include('admin.partials.scripts')

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Job Status Chart
        const jobStatusCtx = document.getElementById('jobStatusChart').getContext('2d');
        new Chart(jobStatusCtx, {
            type: 'doughnut',
            data: {
                labels: ['Pending', 'Assigned', 'In Progress', 'Completed', 'Cancelled'],
                datasets: [{
                    data: [
                        {{ $jobStatusCounts['pending'] }},
                        {{ $jobStatusCounts['assigned'] }},
                        {{ $jobStatusCounts['in_progress'] }},
                        {{ $jobStatusCounts['completed'] }},
                        {{ $jobStatusCounts['cancelled'] }}
                    ],
                    backgroundColor: [
                        '#ffc107',  // Warning - Pending
                        '#17a2b8',  // Info - Assigned
                        '#007bff',  // Primary - In Progress
                        '#28a745',  // Success - Completed
                        '#dc3545'   // Danger - Cancelled
                    ]
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false
            }
        });

        // Cleaner Ratings Chart
        const cleanerRatingsCtx = document.getElementById('cleanerRatingsChart');
        if (cleanerRatingsCtx) {
            const names = {!! json_encode($cleanerRatings->pluck('name')->toArray()) !!};
            const ratings = {!! json_encode($cleanerRatings->pluck('ratings_avg_rating')->toArray()) !!};

            new Chart(cleanerRatingsCtx, {
                type: 'bar',
                data: {
                    labels: names,
                    datasets: [{
                        label: 'Average Rating',
                        data: ratings,
                        backgroundColor: names.map((_, index) => {
                            const colors = [
                                'rgba(255, 99, 132, 0.7)',   // Red
                                'rgba(54, 162, 235, 0.7)',   // Blue
                                'rgba(255, 206, 86, 0.7)',   // Yellow
                                'rgba(75, 192, 192, 0.7)',   // Teal
                                'rgba(153, 102, 255, 0.7)',  // Purple
                                'rgba(255, 159, 64, 0.7)',   // Orange
                                'rgba(56, 193, 114, 0.7)',   // Green
                                'rgba(232, 62, 140, 0.7)',   // Pink
                                'rgba(96, 165, 250, 0.7)',   // Light Blue
                                'rgba(107, 114, 128, 0.7)'   // Gray
                            ];
                            return colors[index % colors.length];
                        }),
                        borderColor: names.map((_, index) => {
                            const colors = [
                                'rgba(255, 99, 132, 1)',   // Red
                                'rgba(54, 162, 235, 1)',   // Blue
                                'rgba(255, 206, 86, 1)',   // Yellow
                                'rgba(75, 192, 192, 1)',   // Teal
                                'rgba(153, 102, 255, 1)',  // Purple
                                'rgba(255, 159, 64, 1)',   // Orange
                                'rgba(56, 193, 114, 1)',   // Green
                                'rgba(232, 62, 140, 1)',   // Pink
                                'rgba(96, 165, 250, 1)',   // Light Blue
                                'rgba(107, 114, 128, 1)'   // Gray
                            ];
                            return colors[index % colors.length];
                        }),
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: true,
                    scales: {
                        y: {
                            beginAtZero: true,
                            max: 5,
                            ticks: {
                                stepSize: 0.5,
                                font: {
                                    size: 12
                                }
                            }
                        },
                        x: {
                            ticks: {
                                font: {
                                    size: 12
                                }
                            }
                        }
                    },
                    plugins: {
                        legend: {
                            labels: {
                                font: {
                                    size: 14
                                }
                            }
                        }
                    }
                }
            });
        }
    });
</script>

<script>
    // Live updates using Pusher (free tier)
    Echo.channel('bookings')
        .listen('NewBooking', (booking) => {
            Toastify({
                text: `New booking from ${booking.customer.name}`,
                duration: 5000,
                newWindow: true,
                onClick: () => window.location.href = `/admin/bookings/${booking.id}`
            }).showToast();
            
            // Update counter
            const counter = document.getElementById('pendingBookingsCount');
            counter.innerText = parseInt(counter.innerText) + 1;
        });
</script>