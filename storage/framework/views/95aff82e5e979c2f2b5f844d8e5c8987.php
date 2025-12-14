<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <div class="well">
                <div class="container-fluid py-4 px-5">
                    <h3>Welcome to <strong>Smarthands Cleaning Service Management System</strong></h3>
                </div>
            </div>
        </div>
    </div>

<div class="row">
    <div class="col-sm-12">
        <!-- Summary Cards -->
        <div class="row g-3 mb-4">
            <div class="col-lg-3 col-md-6">
                <div class="card text-white h-100 border-0 shadow-sm" style="background: linear-gradient(135deg, #4F46E5 0%, #7C3AED 100%);">
                    <div class="card-body d-flex flex-column">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h5 class="card-title mb-0"><i class="fas fa-users me-2"></i>Total Customers</h5>
                            <div class="bg-white-20 p-2 rounded-circle">
                                <i class="fas fa-users"></i>
                            </div>
                        </div>
                        <h2 class="display-5 fw-bold mb-0"><?php echo e(number_format($customerCount)); ?></h2>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-white h-100 border-0 shadow-sm" style="background: linear-gradient(135deg, #10B981 0%, #34D399 100%);">
                    <div class="card-body d-flex flex-column">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h5 class="card-title mb-0"><i class="fas fa-briefcase me-2"></i>Active Jobs</h5>
                            <div class="bg-white-20 p-2 rounded-circle">
                                <i class="fas fa-briefcase"></i>
                            </div>
                        </div>
                        <h2 class="display-5 fw-bold mb-0"><?php echo e(number_format($activeJobCount)); ?></h2>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-white h-100 border-0 shadow-sm" style="background: linear-gradient(135deg, #3B82F6 0%, #60A5FA 100%);">
                    <div class="card-body d-flex flex-column">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h5 class="card-title mb-0"><i class="fas fa-user-shield me-2"></i>Total Cleaners</h5>
                            <div class="bg-white-20 p-2 rounded-circle">
                                <i class="fas fa-user-shield"></i>
                            </div>
                        </div>
                        <h2 class="display-5 fw-bold mb-0"><?php echo e(number_format($cleanerCount)); ?></h2>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-white h-100 border-0 shadow-sm" style="background: linear-gradient(135deg, #F59E0B 0%, #FBBF24 100%);">
                    <div class="card-body d-flex flex-column">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h5 class="card-title mb-0"><i class="fas fa-calendar-alt me-2"></i>Pending Bookings</h5>
                            <div class="bg-white-20 p-2 rounded-circle">
                                <i class="fas fa-calendar-alt"></i>
                            </div>
                        </div>
                        <h2 class="display-5 fw-bold mb-0"><?php echo e(number_format($pendingBookingCount)); ?></h2>
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
                                            <?php $__empty_1 = true; $__currentLoopData = $recentJobs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $job): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                                <tr>
                                                    <td>J<?php echo e(str_pad($job->id, 3, '0', STR_PAD_LEFT)); ?></td>
                                                    <td><?php echo e($job->customer->name ?? 'N/A'); ?></td>
                                                    <td><?php echo e($job->service->name ?? 'N/A'); ?></td>
                                                    <td>
                                                        <span class="badge bg-<?php echo e($job->status === 'completed' ? 'success' : ($job->status === 'in_progress' ? 'primary' : 'warning')); ?>">
                                                            <?php echo e(ucfirst($job->status)); ?>

                                                        </span>
                                                    </td>
                                                </tr>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                                <tr>
                                                    <td colspan="4" class="text-center">No recent jobs</td>
                                                </tr>
                                            <?php endif; ?>
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
                                            <?php $__empty_1 = true; $__currentLoopData = $recentBookings; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $booking): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                                <tr>
                                                    <td>B<?php echo e(str_pad($booking->id, 3, '0', STR_PAD_LEFT)); ?></td>
                                                    <td><?php echo e($booking->customer->name ?? 'N/A'); ?></td>
                                                    <td><?php echo e($booking->service->name ?? 'N/A'); ?></td>
                                                    <td>
                                                        <span class="badge bg-<?php echo e($booking->status === 'confirmed' ? 'success' : ($booking->status === 'pending' ? 'warning' : 'danger')); ?>">
                                                            <?php echo e(ucfirst($booking->status)); ?>

                                                        </span>
                                                    </td>
                                                </tr>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                                <tr>
                                                    <td colspan="4" class="text-center">No recent bookings</td>
                                                </tr>
                                            <?php endif; ?>
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

                    <!-- Top Customers by Jobs Chart -->
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header">
                                <h6>Top Customers by Jobs</h6>
                            </div>
                            <div class="card-body" style="height: 400px;">
                                <canvas id="topCustomersChart"></canvas>
                            </div>
                        </div>
                    </div>

                    <!-- Cleaning History Chart -->
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header">
                                <h6>Completed Jobs by Customer</h6>
                            </div>
                            <div class="card-body" style="height: 400px;">
                                <canvas id="cleaningHistoryChart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column - Booking & Jobs Status Charts -->
            <div class="col-md-4">
                <!-- Online Booking Status Chart -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h6>Online Booking Status</h6>
                    </div>
                    <div class="card-body d-flex align-items-center" style="height: 300px;">
                        <canvas id="bookingStatusChart"></canvas>
                    </div>
                </div>
                <!-- Jobs Status Chart -->
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

</div> <!-- Close container-fluid -->
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Debug: Check if data is available
        console.log('Job Status Counts:', {
            pending: '<?php echo e($jobStatusCounts['pending']); ?>',
            assigned: '<?php echo e($jobStatusCounts['assigned']); ?>',
            in_progress: '<?php echo e($jobStatusCounts['in_progress']); ?>',
            completed: '<?php echo e($jobStatusCounts['completed']); ?>',
            cancelled: '<?php echo e($jobStatusCounts['cancelled']); ?>'
        });

        console.log('Cleaner Ratings Data:', <?php echo json_encode($cleanerRatings->pluck('name', 'ratings_avg_rating')->toArray()); ?>);
        
        console.log('Booking Status Counts:', {
            pending: '<?php echo e($bookingStatusCounts['pending']); ?>',
            confirmed: '<?php echo e($bookingStatusCounts['confirmed']); ?>',
            rescheduled: '<?php echo e($bookingStatusCounts['rescheduled']); ?>',
            completed: '<?php echo e($bookingStatusCounts['completed']); ?>',
            cancelled: '<?php echo e($bookingStatusCounts['cancelled']); ?>'
        });

        // Online Booking Status Chart
        const bookingStatusCtx = document.getElementById('bookingStatusChart');
        if (bookingStatusCtx) {
            const bookingStatusChart = new Chart(bookingStatusCtx, {
                type: 'doughnut',
                data: {
                    labels: ['Pending', 'Confirmed', 'Rescheduled', 'Completed', 'Cancelled'],
                    datasets: [{
                        data: [
                            parseInt('<?php echo e($bookingStatusCounts['pending']); ?>') || 0,
                            parseInt('<?php echo e($bookingStatusCounts['confirmed']); ?>') || 0,
                            parseInt('<?php echo e($bookingStatusCounts['rescheduled']); ?>') || 0,
                            parseInt('<?php echo e($bookingStatusCounts['completed']); ?>') || 0,
                            parseInt('<?php echo e($bookingStatusCounts['cancelled']); ?>') || 0
                        ],
                        backgroundColor: [
                            '#ffc107',  // Pending
                            '#17a2b8',  // Confirmed
                            '#6f42c1',  // Rescheduled
                            '#28a745',  // Completed
                            '#dc3545'   // Cancelled
                        ],
                        borderWidth: 0
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                padding: 20,
                                usePointStyle: true,
                                font: {
                                    size: 12
                                }
                            }
                        }
                    }
                }
            });
        }

        // Job Status Chart
        const jobStatusCtx = document.getElementById('jobStatusChart');
        if (jobStatusCtx) {
            const jobStatusChart = new Chart(jobStatusCtx, {
                type: 'doughnut',
                data: {
                    labels: ['Pending', 'Assigned', 'In Progress', 'Completed', 'Cancelled'],
                    datasets: [{
                        data: [
                            parseInt('<?php echo e($jobStatusCounts['pending']); ?>') || 0,
                            parseInt('<?php echo e($jobStatusCounts['assigned']); ?>') || 0,
                            parseInt('<?php echo e($jobStatusCounts['in_progress']); ?>') || 0,
                            parseInt('<?php echo e($jobStatusCounts['completed']); ?>') || 0,
                            parseInt('<?php echo e($jobStatusCounts['cancelled']); ?>') || 0
                        ],
                        backgroundColor: [
                            '#ffc107',  // Warning - Pending
                            '#17a2b8',  // Info - Assigned
                            '#007bff',  // Primary - In Progress
                            '#28a745',  // Success - Completed
                            '#dc3545'   // Danger - Cancelled
                        ],
                        borderWidth: 0
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                padding: 20,
                                usePointStyle: true,
                                font: {
                                    size: 12
                                }
                            }
                        }
                    }
                }
            });
        }

        // Cleaner Ratings Chart
        const cleanerRatingsCtx = document.getElementById('cleanerRatingsChart');
        if (cleanerRatingsCtx) {
            const names = <?php echo json_encode($cleanerRatings->pluck('name')->toArray()); ?>;
            const ratings = <?php echo json_encode($cleanerRatings->pluck('ratings_avg_rating')->toArray()); ?>;

            console.log('Chart Names:', names);
            console.log('Chart Ratings:', ratings);

            // Filter out employees with no ratings or zero ratings
            const validData = names.map((name, index) => ({
                name,
                rating: parseFloat(ratings[index]) || 0
            })).filter(item => item.rating > 0);

            if (validData.length > 0) {
                const filteredNames = validData.map(item => item.name);
                const filteredRatings = validData.map(item => item.rating);

                const cleanerRatingsChart = new Chart(cleanerRatingsCtx, {
                    type: 'bar',
                    data: {
                        labels: filteredNames,
                        datasets: [{
                            label: 'Average Rating',
                            data: filteredRatings,
                            backgroundColor: filteredNames.map((_, index) => {
                                const colors = [
                                    'rgba(16, 185, 129, 0.8)',  // Green from Active Jobs
                                    'rgba(59, 130, 246, 0.8)',  // Blue from Total Cleaners
                                    'rgba(245, 158, 11, 0.8)',  // Orange from Pending Bookings
                                    'rgba(79, 70, 229, 0.8)',   // Purple from Total Customers
                                    'rgba(52, 211, 153, 0.8)',  // Light Green from Active Jobs
                                    'rgba(96, 165, 250, 0.8)',  // Light Blue from Total Cleaners
                                    'rgba(251, 191, 36, 0.8)',  // Light Orange from Pending Bookings
                                    'rgba(124, 58, 237, 0.8)',  // Light Purple from Total Customers
                                    'rgba(34, 197, 94, 0.8)',   // Medium Green
                                    'rgba(37, 99, 235, 0.8)'    // Medium Blue
                                ];
                                return colors[index % colors.length];
                            }),
                            borderColor: filteredNames.map((_, index) => {
                                const colors = [
                                    'rgba(16, 185, 129, 1)',  // Green from Active Jobs
                                    'rgba(59, 130, 246, 1)',  // Blue from Total Cleaners
                                    'rgba(245, 158, 11, 1)',  // Orange from Pending Bookings
                                    'rgba(79, 70, 229, 1)',   // Purple from Total Customers
                                    'rgba(52, 211, 153, 1)',  // Light Green from Active Jobs
                                    'rgba(96, 165, 250, 1)',  // Light Blue from Total Cleaners
                                    'rgba(251, 191, 36, 1)',  // Light Orange from Pending Bookings
                                    'rgba(124, 58, 237, 1)',  // Light Purple from Total Customers
                                    'rgba(34, 197, 94, 1)',   // Medium Green
                                    'rgba(37, 99, 235, 1)'    // Medium Blue
                                ];
                                return colors[index % colors.length];
                            }),
                            borderWidth: 1,
                            borderRadius: 4,
                            borderSkipped: false
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                display: false
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                max: 5,
                                grid: {
                                    display: true,
                                    color: 'rgba(0, 0, 0, 0.1)'
                                },
                                ticks: {
                                    stepSize: 0.5,
                                    font: {
                                        size: 11
                                    }
                                }
                            },
                            x: {
                                grid: {
                                    display: false
                                },
                                ticks: {
                                    font: {
                                        size: 11
                                    },
                                    maxRotation: 45,
                                    minRotation: 0
                                }
                            }
                        }
                    }
                });
            } else {
                // No valid ratings data, show a message
                cleanerRatingsCtx.getContext('2d').fillStyle = '#6c757d';
                cleanerRatingsCtx.getContext('2d').font = '16px Arial';
                cleanerRatingsCtx.getContext('2d').textAlign = 'center';
                cleanerRatingsCtx.getContext('2d').fillText('No ratings data available', cleanerRatingsCtx.width / 2, cleanerRatingsCtx.height / 2);
            }
        }

        // Top Customers by Jobs Chart
        const topCustomersCtx = document.getElementById('topCustomersChart');
        if (topCustomersCtx) {
            const customerNames = <?php echo json_encode($topCustomersByJobs->pluck('name')->toArray()); ?>;
            const jobCounts = <?php echo json_encode($topCustomersByJobs->pluck('jobs_count')->toArray()); ?>;

            console.log('Top Customers:', customerNames);
            console.log('Job Counts:', jobCounts);

            if (customerNames.length > 0) {
                const topCustomersChart = new Chart(topCustomersCtx, {
                    type: 'bar',
                    data: {
                        labels: customerNames,
                        datasets: [{
                            label: 'Number of Jobs',
                            data: jobCounts,
                            backgroundColor: customerNames.map((_, index) => {
                                const colors = [
                                    'rgba(79, 70, 229, 0.8)',   // Purple
                                    'rgba(16, 185, 129, 0.8)',  // Green
                                    'rgba(59, 130, 246, 0.8)',  // Blue
                                    'rgba(245, 158, 11, 0.8)',  // Orange
                                    'rgba(236, 72, 153, 0.8)',  // Pink
                                    'rgba(124, 58, 237, 0.8)',  // Light Purple
                                    'rgba(52, 211, 153, 0.8)',  // Light Green
                                    'rgba(96, 165, 250, 0.8)',  // Light Blue
                                    'rgba(251, 191, 36, 0.8)',  // Light Orange
                                    'rgba(244, 114, 182, 0.8)'  // Light Pink
                                ];
                                return colors[index % colors.length];
                            }),
                            borderColor: customerNames.map((_, index) => {
                                const colors = [
                                    'rgba(79, 70, 229, 1)',
                                    'rgba(16, 185, 129, 1)',
                                    'rgba(59, 130, 246, 1)',
                                    'rgba(245, 158, 11, 1)',
                                    'rgba(236, 72, 153, 1)',
                                    'rgba(124, 58, 237, 1)',
                                    'rgba(52, 211, 153, 1)',
                                    'rgba(96, 165, 250, 1)',
                                    'rgba(251, 191, 36, 1)',
                                    'rgba(244, 114, 182, 1)'
                                ];
                                return colors[index % colors.length];
                            }),
                            borderWidth: 1,
                            borderRadius: 4,
                            borderSkipped: false
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                display: false
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                grid: {
                                    display: true,
                                    color: 'rgba(0, 0, 0, 0.1)'
                                },
                                ticks: {
                                    stepSize: 1,
                                    font: {
                                        size: 11
                                    }
                                }
                            },
                            x: {
                                grid: {
                                    display: false
                                },
                                ticks: {
                                    font: {
                                        size: 11
                                    },
                                    maxRotation: 45,
                                    minRotation: 0
                                }
                            }
                        }
                    }
                });
            } else {
                // No customer data, show a message
                topCustomersCtx.getContext('2d').fillStyle = '#6c757d';
                topCustomersCtx.getContext('2d').font = '16px Arial';
                topCustomersCtx.getContext('2d').textAlign = 'center';
                topCustomersCtx.getContext('2d').fillText('No customer data available', topCustomersCtx.width / 2, topCustomersCtx.height / 2);
            }
        }

        // Cleaning History Chart (Completed Jobs by Customer)
        const cleaningHistoryCtx = document.getElementById('cleaningHistoryChart');
        if (cleaningHistoryCtx) {
            const historyCustomerNames = <?php echo json_encode($completedJobsByCustomer->pluck('name')->toArray()); ?>;
            const completedJobCounts = <?php echo json_encode($completedJobsByCustomer->pluck('jobs_count')->toArray()); ?>;

            console.log('Cleaning History Customers:', historyCustomerNames);
            console.log('Completed Job Counts:', completedJobCounts);

            if (historyCustomerNames.length > 0) {
                const cleaningHistoryChart = new Chart(cleaningHistoryCtx, {
                    type: 'bar',
                    data: {
                        labels: historyCustomerNames,
                        datasets: [{
                            label: 'Completed Jobs',
                            data: completedJobCounts,
                            backgroundColor: historyCustomerNames.map((_, index) => {
                                const colors = [
                                    'rgba(16, 185, 129, 0.8)',  // Green
                                    'rgba(59, 130, 246, 0.8)',  // Blue
                                    'rgba(245, 158, 11, 0.8)',  // Orange
                                    'rgba(139, 92, 246, 0.8)',  // Violet
                                    'rgba(236, 72, 153, 0.8)',  // Pink
                                    'rgba(20, 184, 166, 0.8)',  // Teal
                                    'rgba(251, 146, 60, 0.8)',  // Light Orange
                                    'rgba(168, 85, 247, 0.8)',  // Purple
                                    'rgba(244, 114, 182, 0.8)',  // Light Pink
                                    'rgba(14, 165, 233, 0.8)'   // Sky Blue
                                ];
                                return colors[index % colors.length];
                            }),
                            borderColor: historyCustomerNames.map((_, index) => {
                                const colors = [
                                    'rgba(16, 185, 129, 1)',
                                    'rgba(59, 130, 246, 1)',
                                    'rgba(245, 158, 11, 1)',
                                    'rgba(139, 92, 246, 1)',
                                    'rgba(236, 72, 153, 1)',
                                    'rgba(20, 184, 166, 1)',
                                    'rgba(251, 146, 60, 1)',
                                    'rgba(168, 85, 247, 1)',
                                    'rgba(244, 114, 182, 1)',
                                    'rgba(14, 165, 233, 1)'
                                ];
                                return colors[index % colors.length];
                            }),
                            borderWidth: 1,
                            borderRadius: 4,
                            borderSkipped: false
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                display: false
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                grid: {
                                    display: true,
                                    color: 'rgba(0, 0, 0, 0.1)'
                                },
                                ticks: {
                                    stepSize: 1,
                                    font: {
                                        size: 11
                                    }
                                }
                            },
                            x: {
                                grid: {
                                    display: false
                                },
                                ticks: {
                                    font: {
                                        size: 11
                                    },
                                    maxRotation: 45,
                                    minRotation: 0
                                }
                            }
                        }
                    }
                });
            } else {
                // No cleaning history data, show a message
                cleaningHistoryCtx.getContext('2d').fillStyle = '#6c757d';
                cleaningHistoryCtx.getContext('2d').font = '16px Arial';
                cleaningHistoryCtx.getContext('2d').textAlign = 'center';
                cleaningHistoryCtx.getContext('2d').fillText('No cleaning history data available', cleaningHistoryCtx.width / 2, cleaningHistoryCtx.height / 2);
            }
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
<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /opt/lampp/htdocs/cleaning_service_management_system/resources/views/admin/dashboard.blade.php ENDPATH**/ ?>