<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <!-- Customer Details Card -->
    <div class="card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="m-0">Customer Details</h5>
            <div class="card-tools">
                <a href="<?php echo e(route('admin.customers.index')); ?>" class="btn btn-sm btn-secondary">
                    <i class="fas fa-arrow-left"></i> Back to List
                </a>
            </div>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <p><strong>Customer ID:</strong> <?php echo e($customer->customer_id); ?></p>
                    <p><strong>Name:</strong> <?php echo e($customer->name); ?></p>
                    <p><strong>Email:</strong> <?php echo e($customer->email); ?></p>
                </div>
                <div class="col-md-6">
                    <p><strong>Contact:</strong> <?php echo e($customer->contact); ?></p>
                    <p><strong>Registered Date:</strong> <?php echo e($customer->registered_date->format('M d, Y')); ?></p>
                    <p>
                        <strong>Status:</strong>
                        <?php if($customer->is_archived): ?>
                            <span class="badge bg-secondary">Archived</span>
                            <small class="text-muted">(<?php echo e($customer->archived_at->format('M d, Y')); ?>)</small>
                            <?php if($customer->archive_reason): ?>
                                <br>
                                <small class="text-muted">Reason: <?php echo e($customer->archive_reason); ?></small>
                            <?php endif; ?>
                        <?php else: ?>
                            <span class="badge" style="background-color: #28a745;">Active</span>
                        <?php endif; ?>
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Customer Jobs Card -->
    <div class="card">
        <div class="card-header">
            <h5 class="m-0">Job History</h5>
        </div>
        <div class="card-body">
            <?php if($jobs->count() > 0): ?>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Service</th>
                                <th>Employee</th>
                                <th>Status</th>
                                <th>Scheduled Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__currentLoopData = $jobs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $job): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td><?php echo e($job->service->name); ?></td>
                                    <td>
                                        <?php if($job->employees->count() > 0): ?>
                                            <?php $__currentLoopData = $job->employees; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $employee): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <span class="badge bg-info"><?php echo e($employee->name); ?></span>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        <?php else: ?>
                                            <span class="text-muted">Not Assigned</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <span class="badge bg-<?php echo e($job->status === 'completed' ? 'success' : ($job->status === 'in_progress' ? 'primary' : 'warning')); ?>">
                                            <?php echo e(ucfirst($job->status)); ?>

                                        </span>
                                    </td>
                                    <td><?php echo e($job->scheduled_date->format('M d, Y')); ?></td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                    </table>
                </div>
                <?php echo e($jobs->links()); ?>

            <?php else: ?>
                <p class="text-muted">No jobs found for this customer.</p>
            <?php endif; ?>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?> 
<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /opt/lampp/htdocs/cleaning_service_management_system/resources/views/admin/customers/show.blade.php ENDPATH**/ ?>