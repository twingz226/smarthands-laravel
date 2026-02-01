<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <?php
    $prevDate = $date->copy()->subDay()->format('Y-m-d');
    $nextDate = $date->copy()->addDay()->format('Y-m-d');
?>

<h1 class="h2 d-flex align-items-center justify-content-center gap-6 text-center">
    <a href="<?php echo e(route('jobs.daily_schedule', ['date' => $prevDate])); ?>" class="nav-arrow text-decoration-none px-4 py-2 bg-light rounded" data-tooltip="Previous Date">
        <i class="fa-solid fa-arrow-left fa-lg"></i>
    </a>
    <span class="px-6">Daily Schedule - <?php echo e($date->format('F j, Y')); ?></span>
    <a href="<?php echo e(route('jobs.daily_schedule', ['date' => $nextDate])); ?>" class="nav-arrow text-decoration-none px-4 py-2 bg-light rounded" data-tooltip="Next Date">
        <i class="fa-solid fa-arrow-right fa-lg"></i>
    </a>
</h1>
        <div class="btn-toolbar mb-2 mb-md-0"></div>
    </div>

    <?php if(session('success')): ?>
        <div class="alert alert-success alert-dismissible fade in">
            <?php echo e(session('success')); ?>

            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">×</span>
            </button>
        </div>
    <?php endif; ?>

    <!-- Assigned & In Progress Jobs -->
    <div class="card mb-4">
        <div class="card-header bg-primary text-white py-3">
            <h5 class="mb-0">Assigned & In Progress Jobs (<?php echo e($assignedJobs->count()); ?>)</h5>
        </div>
        <div class="card-body">
            <?php if($assignedJobs->count() > 0): ?>
                <div class="clearfix mb-3">
                    <a href="<?php echo e(route('jobs.daily_schedule.export.pdf')); ?>" class="btn btn-lg btn-secondary float-end" style="margin-left: auto;">
                        <i class="fas fa-file-pdf"></i> Export PDF
                    </a>
                </div>
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead class="table-dark">
                            <tr>
                                <th>Customer</th>
                                <th>Address</th>
                                <th>Service</th>
                                <th>Assigned To</th>
                                <th>Status</th>
                                <th>Scheduled Time</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__currentLoopData = $assignedJobs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $job): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td><?php echo e($job->customer ? $job->customer->name : 'N/A'); ?></td>
                                    <td><?php echo e($job->address); ?></td>
                                    <td><?php echo e($job->service ? $job->service->name : 'N/A'); ?></td>
                                    <td>
                                        <?php if($job->employees->count() > 0): ?>
                                            <?php $__currentLoopData = $job->employees; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $employee): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <span class="badge bg-info"><?php echo e($employee->name); ?></span>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        <?php else: ?>
                                            <span class="text-warning">Unassigned</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <span class="badge status-badge bg-<?php echo e($job->status === 'assigned' ? 'primary' : 'info'); ?>">
                                            <?php echo e(ucfirst($job->status)); ?>

                                        </span>
                                    </td>
                                    <td><?php echo e($job->scheduled_date->format('g:i A')); ?></td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <div class="text-center py-4">
                    <p class="text-muted">No assigned or in-progress jobs for today.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Pending Jobs -->
    <div class="card mb-4">
        <div class="card-header bg-warning py-3">
            <h5 class="mb-0">Pending Jobs (<?php echo e($pendingJobs->count()); ?>)</h5>
        </div>
        <div class="card-body">
            <?php if($pendingJobs->count() > 0): ?>
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead class="table-dark">
                            <tr>
                                <th>Customer</th>
                                <th>Address</th>
                                <th>Service</th>
                                <th>Status</th>
                                <th>Scheduled Time</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__currentLoopData = $pendingJobs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $job): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td><?php echo e($job->customer ? $job->customer->name : 'N/A'); ?></td>
                                    <td><?php echo e($job->address); ?></td>
                                    <td><?php echo e($job->service ? $job->service->name : 'N/A'); ?></td>
                                    <td>
                                        <span class="badge bg-warning">Pending</span>
                                    </td>
                                    <td><?php echo e($job->scheduled_date->format('g:i A')); ?></td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <div class="text-center py-4">
                    <p class="text-muted">No pending jobs for today.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Completed Jobs -->
    <div class="card">
        <div class="card-header bg-success text-white py-3">
            <h5 class="mb-0">Completed Jobs (<?php echo e($completedJobs->count()); ?>)</h5>
        </div>
        <div class="card-body">
            <?php if($completedJobs->count() > 0): ?>
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead class="table-dark">
                            <tr>
                                <th>Customer</th>
                                <th>Address</th>
                                <th>Service</th>
                                <th>Assigned To</th>
                                <th>Scheduled Time</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__currentLoopData = $completedJobs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $job): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td><?php echo e($job->customer ? $job->customer->name : 'N/A'); ?></td>
                                    <td><?php echo e($job->address); ?></td>
                                    <td><?php echo e($job->service ? $job->service->name : 'N/A'); ?></td>
                                    <td>
                                        <?php if($job->employees->count() > 0): ?>
                                            <?php $__currentLoopData = $job->employees; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $employee): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <span class="badge bg-info"><?php echo e($employee->name); ?></span>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        <?php else: ?>
                                            <span class="text-muted">Unassigned</span>
                                        <?php endif; ?>
                                    </td>
                                    <td><?php echo e($job->scheduled_date->format('g:i A')); ?></td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <div class="text-center py-4">
                    <p class="text-muted">No completed jobs for today.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('styles'); ?>
<style>
    /* Modern color scheme */
    :root {
        --primary-color: #4a6b8b;
        --warning-color: #ff9f1c;
        --success-color: #2ecc71;
        --text-on-light: #2c3e50;
        --text-on-dark: #ffffff;
    }

    /* Enhanced card header styles */
    .card-header {
        min-height: 60px;
        display: flex;
        align-items: center;
        border: none;
        font-weight: 600;
        letter-spacing: 0.5px;
    }
    
    .bg-primary {
        background-color: var(--primary-color) !important;
        color: var(--text-on-dark) !important;
    }
    
    .bg-warning {
        background-color: var(--warning-color) !important;
        color: var(--text-on-dark) !important;
    }
    
    .bg-success {
        background-color: var(--success-color) !important;
        color: var(--text-on-dark) !important;
    }
    
    /* Add subtle shadow and transition for better interaction */
    .card {
        border: none;
        box-shadow: 0 2px 15px rgba(0, 0, 0, 0.05);
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
    
    .card:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
    }
    
    /* Custom tooltip with zero delay */
    .nav-arrow {
        position: relative;
        display: inline-block;
    }
    
    .nav-arrow::before {
        content: attr(data-tooltip);
        position: absolute;
        bottom: 100%;
        left: 50%;
        transform: translateX(-50%);
        background: #333;
        color: white;
        padding: 4px 8px;
        border-radius: 4px;
        font-size: 12px;
        white-space: nowrap;
        z-index: 1000;
        opacity: 0;
        visibility: hidden;
        pointer-events: none;
        transition: none !important;
    }
    
    .nav-arrow:hover::before {
        opacity: 1;
        visibility: visible;
        transition: none !important;
    }
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
$(document).ready(function() {
    // Initialize tooltips
    $('[data-toggle="tooltip"]').tooltip();
});
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /opt/lampp/htdocs/cleaning_service_management_system/resources/views/admin/jobs/daily_schedule.blade.php ENDPATH**/ ?>