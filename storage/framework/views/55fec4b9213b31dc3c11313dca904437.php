<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Customer Retention Report</h1>
    </div>

    <?php if(session('success')): ?>
        <div class="alert alert-success alert-dismissible fade show">
            <?php echo e(session('success')); ?>

            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

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
                    <h2 class="display-5 fw-bold mb-0"><?php echo e(number_format($totalCustomers)); ?></h2>
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
                    <h2 class="display-5 fw-bold mb-1"><?php echo e(number_format($repeatCustomers)); ?></h2>
                    <p class="mb-0" style="color: rgba(255, 255, 255, 0.8);">
                        <?php echo e($totalCustomers > 0 ? number_format(($repeatCustomers/$totalCustomers)*100, 1) : 0); ?>% of total customers
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
                    <h2 class="display-5 fw-bold mb-0"><?php echo e(number_format($newCustomersLastMonth)); ?></h2>
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
                                <?php $__currentLoopData = $customersByJobCount; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $range => $count): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr>
                                        <td><?php echo e($range); ?></td>
                                        <td><?php echo e(number_format($count)); ?></td>
                                        <td>
                                            <?php echo e($totalCustomers > 0 ? number_format(($count/$totalCustomers)*100, 1) : 0); ?>%
                                        </td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
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
                        <?php $__empty_1 = true; $__currentLoopData = $topCustomers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $customer): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr>
                                <td><?php echo e($customer->name); ?></td>
                                <td><span class="badge bg-primary"><?php echo e($customer->jobs_count); ?></span></td>
                                <td><?php echo e($customer->first_booking); ?></td>
                                <td><?php echo e($customer->last_booking); ?></td>
                                <td>
                                    <?php if($customer->first_booking != 'N/A' && $customer->last_booking != 'N/A'): ?>
                                        <?php
                                            $first = \Carbon\Carbon::parse($customer->first_booking);
                                            $last = \Carbon\Carbon::parse($customer->last_booking);
                                            $daysBetween = $first->diffInDays($last);
                                            $frequency = $daysBetween > 0 ? round($customer->jobs_count / ($daysBetween/30), 1) : $customer->jobs_count;
                                        ?>
                                        <span class="badge bg-info"><?php echo e($frequency); ?> jobs/month</span>
                                    <?php else: ?>
                                        <span class="text-muted">N/A</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr>
                                <td colspan="5" class="text-center text-muted">No repeat customers found</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('styles'); ?>
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
<?php $__env->stopPush(); ?>

<?php $__env->startPush('scripts'); ?>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Job Count Distribution Chart
    const jobCountCtx = document.getElementById('jobCountChart').getContext('2d');
    const jobCountChart = new Chart(jobCountCtx, {
        type: 'doughnut',
        data: {
            labels: <?php echo json_encode(array_keys($customersByJobCount)); ?>,
            datasets: [{
                data: <?php echo json_encode(array_values($customersByJobCount)); ?>,
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
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /opt/lampp/htdocs/cleaning_service_management_system/resources/views/admin/reports/customers/retention.blade.php ENDPATH**/ ?>