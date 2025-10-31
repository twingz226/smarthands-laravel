<?php $__env->startSection('content'); ?>
<div class="main-content">
    <div class="container">
        <h3>🧼 Cleaning History Report</h3>

        <?php if(session('success')): ?>
            <div class="alert alert-success">
                <?php echo e(session('success')); ?>

            </div>
        <?php endif; ?>

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
                <?php $__empty_1 = true; $__currentLoopData = $customers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $customer): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <?php $__currentLoopData = $customer->jobs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $job): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <td><?php echo e($customer->name); ?></td>
                            <td><?php echo e($job->service->name ?? 'N/A'); ?></td>
                            <td>
                                <?php if($job->employees->count() > 0): ?>
                                    <?php $__currentLoopData = $job->employees; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $employee): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <span class="badge bg-info"><?php echo e($employee->name); ?></span>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                <?php else: ?>
                                    <span class="text-muted">Not Assigned</span>
                                <?php endif; ?>
                            </td>
                            <td><?php echo e($job->created_at->format('Y-m-d')); ?></td>
                            <td>
                                <span class="badge bg-success"><?php echo e($job->status); ?></span>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="5" class="text-center">No completed jobs found</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>

        <?php if($customers->hasPages()): ?>
            <div class="d-flex justify-content-center mt-3">
                <?php echo e($customers->links()); ?>

            </div>
        <?php endif; ?>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /opt/lampp/htdocs/cleaning_service_management_system/resources/views/admin/reports/customers/history.blade.php ENDPATH**/ ?>