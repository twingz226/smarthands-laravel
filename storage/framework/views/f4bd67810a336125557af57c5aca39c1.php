<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Job Details #<?php echo e($job->id); ?></h1>
        <div class="btn-toolbar mb-2 mb-md-0">
            <a href="<?php echo e(route('jobs.tracking')); ?>" class="btn btn-sm btn-secondary">
                <i class="entypo-back"></i> Back to Job Tracking
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <strong>Status:</strong>
                            <span class="badge 
                                <?php if($job->status == 'assigned'): ?> badge-primary
                                <?php elseif($job->status == 'in_progress'): ?> badge-warning
                                <?php elseif($job->status == 'completed'): ?> badge-success
                                <?php else: ?> badge-secondary
                                <?php endif; ?>">
                                <?php echo e(ucfirst(str_replace('_', ' ', $job->status))); ?>

                            </span>
                        </div>
                        <div class="col-md-4">
                            <strong>Scheduled Date:</strong><br>
                            <?php echo e($job->scheduled_date->format('M d, Y h:i A')); ?>

                        </div>
                        <div class="col-md-4">
                            <strong>Created:</strong><br>
                            <?php echo e($job->created_at->format('M d, Y h:i A')); ?>

                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <strong>Customer:</strong><br>
                            <?php echo e($job->customer->name); ?><br>
                            <?php echo e($job->customer->email); ?><br>
                            <?php echo e($job->customer->contact); ?>

                        </div>
                        <div class="col-md-6">
                            <strong>Service:</strong><br>
                            <?php echo e($job->service->name); ?><br>
                            Price: ₱<?php echo e(number_format($job->service->price, 2)); ?>

                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-12">
                            <strong>Address:</strong><br>
                            <?php echo e($job->address); ?>

                        </div>
                    </div>

                    <?php if($job->special_instructions): ?>
                    <div class="row mb-3">
                        <div class="col-md-12">
                            <strong>Special Instructions:</strong><br>
                            <?php echo e($job->special_instructions); ?>

                        </div>
                    </div>
                    <?php endif; ?>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <strong>Assigned Cleaners:</strong><br>
                            <?php if($job->employees->count() > 0): ?>
                                <?php $__currentLoopData = $job->employees; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $employee): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <div class="mb-2">
                                        <span class="badge bg-info"><?php echo e($employee->name); ?></span><br>
                                        <small class="text-muted">
                                            <?php echo e($employee->email); ?><br>
                                            <?php echo e($employee->phone); ?>

                                        </small>
                                    </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            <?php else: ?>
                                <span class="text-danger">Not assigned</span>
                            <?php endif; ?>
                        </div>
                        <div class="col-md-6">
                            <strong>Timeline:</strong><br>
                            <?php if($job->assigned_at): ?>
                                Assigned: <?php echo e($job->assigned_at->format('M d, Y h:i A')); ?><br>
                            <?php endif; ?>
                            <?php if($job->started_at): ?>
                                Started: <?php echo e($job->started_at->format('M d, Y h:i A')); ?><br>
                            <?php endif; ?>
                            <?php if($job->completed_at): ?>
                                Completed: <?php echo e($job->completed_at->format('M d, Y h:i A')); ?>

                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <hr>
                            <div class="btn-group">
                                <?php if(in_array($job->status, ['assigned', 'in_progress'])): ?>
                                    <button class="btn btn-info" data-toggle="modal" data-target="#reassignModal">
                                        <i class="fas fa-user-edit"></i> Reassign
                                    </button>
                                    <form action="<?php echo e(route('jobs.update-status', $job->id)); ?>" method="POST" class="d-inline">
                                        <?php echo csrf_field(); ?>
                                        <?php echo method_field('PUT'); ?>
                                        <?php if($job->status == 'assigned'): ?>
                                            <input type="hidden" name="status" value="in_progress">
                                            <button type="submit" class="btn btn-warning">
                                                <i class="fas fa-play"></i> Start Job
                                            </button>
                                        <?php elseif($job->status == 'in_progress'): ?>
                                            <input type="hidden" name="status" value="completed">
                                            <button type="submit" class="btn btn-success">
                                                <i class="fas fa-check"></i> Complete Job
                                            </button>
                                        <?php endif; ?>
                                    </form>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php if(in_array($job->status, ['assigned', 'in_progress'])): ?>
<!-- Reassign Modal -->
<div class="modal fade" id="reassignModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Reassign Job</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="<?php echo e(route('jobs.reassign', $job->id)); ?>" method="POST">
                <?php echo csrf_field(); ?>
                <?php echo method_field('PUT'); ?>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Current Cleaners</label>
                        <div class="mb-3">
                            <?php $__currentLoopData = $job->employees; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $employee): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <span class="badge bg-info"><?php echo e($employee->name); ?></span>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>New Cleaners</label>
                        <select class="form-control" name="employee_ids[]" multiple required>
                            <option value="">-- Select Cleaners --</option>
                            <?php $__currentLoopData = $availableEmployees ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $employee): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($employee->id); ?>"><?php echo e($employee->name); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                        <small class="form-text text-muted">Hold Ctrl/Cmd to select multiple cleaners</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Reassign</button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php endif; ?>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /opt/lampp/htdocs/cleaning_service_management_system/resources/views/admin/jobs/show.blade.php ENDPATH**/ ?>