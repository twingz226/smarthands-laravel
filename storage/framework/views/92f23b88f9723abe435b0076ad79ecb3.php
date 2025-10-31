<?php $__env->startSection('content'); ?>
<div class="container">
    <h2 class="mb-4">Employee Performance</h2>

    <div class="row mb-3">
        <div class="col-md-4">
            <div class="card text-bg-light">
                <div class="card-body">
                    <h5 class="card-title">Average Rating</h5>
                    <p class="display-6"><?php echo e(number_format($performanceMetrics['average_rating'], 2)); ?>/5</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-bg-light">
                <div class="card-body">
                    <h5 class="card-title">Total Completed Jobs</h5>
                    <p class="display-6"><?php echo e($performanceMetrics['total_completed']); ?></p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-bg-light">
                <div class="card-body">
                    <h5 class="card-title">Top Performer</h5>
                    <p class="display-6"><?php echo e($performanceMetrics['top_performer']?->name ?? 'N/A'); ?></p>
                </div>
            </div>
        </div>
    </div>

    <table class="table table-bordered">
        <thead class="table-secondary">
            <tr>
                <th>Name</th>
                <th>Completed Jobs</th>
                <th>Active Jobs</th>
                <th>Average Rating</th>
            </tr>
        </thead>
        <tbody>
            <?php $__currentLoopData = $employees; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $employee): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <tr>
                <td><?php echo e($employee->name); ?></td>
                <td><?php echo e($employee->completed_jobs_count); ?></td>
                <td><?php echo e($employee->active_jobs_count); ?></td>
                <td><?php echo e(number_format($employee->ratings_avg_rating, 2) ?? 'N/A'); ?></td>
            </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </tbody>
    </table>

    <?php echo e($employees->links()); ?>

</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /opt/lampp/htdocs/cleaning_service_management_system/resources/views/admin/employees/performance.blade.php ENDPATH**/ ?>