<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Job Tracking & Assignments</h1>
        <div class="btn-toolbar mb-2 mb-md-0"></div>
    </div>

    <?php if(session('success')): ?>
        <div class="alert alert-success alert-dismissible fade show">
            <?php echo e(session('success')); ?>

            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th>Customer</th>
                            <th>Address</th>
                            <th>Service</th>
                            <th>Assigned To</th>
                            <th>Status</th>
                            <th>Scheduled Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__empty_1 = true; $__currentLoopData = $jobs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $job): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
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
                                    <span class="badge status-badge bg-<?php echo e($job->status === 'pending' ? 'warning' : ($job->status === 'assigned' ? 'primary' : ($job->status === 'in_progress' ? 'info' : ($job->status === 'cancelled' ? 'danger' : 'success')))); ?>">
                                        <?php echo e(ucfirst($job->status)); ?>

                                    </span>
                                </td>
                                <td><?php echo e($job->scheduled_date ? $job->scheduled_date->format('M d, Y h:i A') : 'N/A'); ?></td>
                                <td>
                                    <div class="d-flex flex-column" style="gap: 5px">
                                        <div class="d-flex" style="gap: 5px">
                                            <a href="<?php echo e(route('jobs.show', $job->id)); ?>" class="btn btn-info btn-circle" style="width: 32px; height: 32px; padding: 0; display: inline-flex; align-items: center; justify-content: center;" data-toggle="tooltip" title="View Details">
                                                <i class="entypo-eye" style="font-size: 16px; line-height: 1;"></i>
                                            </a>
                                            <?php if($job->status === 'pending'): ?>
                                                <button type="button" class="btn btn-primary btn-circle" style="width: 32px; height: 32px; padding: 0; display: inline-flex; align-items: center; justify-content: center;" data-toggle="modal" data-target="#assignModal<?php echo e($job->id); ?>" title="Assign Cleaners">
                                                    <i class="entypo-user-add" style="font-size: 16px; line-height: 1;"></i>
                                                </button>
                                            <?php elseif(in_array($job->status, ['assigned', 'in_progress'])): ?>
                                                <form action="<?php echo e(route('jobs.update-status', $job)); ?>" method="POST" style="display: inline-block;" id="statusForm<?php echo e($job->id); ?>">
                                                    <?php echo csrf_field(); ?> <?php echo method_field('PUT'); ?>
                                                    <input type="hidden" name="status" value="<?php echo e($job->status === 'assigned' ? 'in_progress' : 'completed'); ?>">
                                                    <button type="submit" class="btn <?php echo e($job->status === 'assigned' ? 'btn-success' : 'btn-primary'); ?> btn-circle" style="width: 32px; height: 32px; padding: 0; display: inline-flex; align-items: center; justify-content: center;" data-toggle="tooltip" title="<?php echo e($job->status === 'assigned' ? 'Start Job' : 'Mark as Complete'); ?>">
                                                        <i class="entypo-<?php echo e($job->status === 'assigned' ? 'play' : 'check'); ?>" style="font-size: 16px; line-height: 1;"></i>
                                                    </button>
                                                </form>
                                            <?php else: ?>
                                                <div style="width: 32px; height: 32px;"></div>
                                            <?php endif; ?>
                                        </div>
                                        <?php if($job->status != 'completed' && $job->status != 'cancelled'): ?>
                                            <div class="d-flex" style="gap: 5px">
                                                <a href="<?php echo e(route('jobs.reschedule', $job->id)); ?>" class="btn btn-warning btn-circle" style="width: 32px; height: 32px; padding: 0; display: inline-flex; align-items: center; justify-content: center;" data-toggle="tooltip" title="Reschedule">
                                                    <i class="entypo-back-in-time" style="font-size: 16px; line-height: 1;"></i>
                                                </a>
                                                <form action="<?php echo e(route('jobs.cancel', $job)); ?>" method="POST" style="display: inline-block;" id="cancelForm<?php echo e($job->id); ?>">
                                                    <?php echo csrf_field(); ?> <?php echo method_field('PATCH'); ?>
                                                    <button type="button" class="btn btn-danger btn-circle cancel-job-btn" style="width: 32px; height: 32px; padding: 0; display: inline-flex; align-items: center; justify-content: center;" data-toggle="tooltip" title="Cancel" data-job-id="<?php echo e($job->id); ?>" data-form-id="cancelForm<?php echo e($job->id); ?>">
                                                        <i class="entypo-block" style="font-size: 16px; line-height: 1;"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr>
                                <td colspan="8" class="text-center">No jobs found</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Assignment Modals -->
    <?php $__currentLoopData = $jobs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $job): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <?php if($job->status === 'pending'): ?>
            <div class="modal fade" id="assignModal<?php echo e($job->id); ?>" tabindex="-1" role="dialog" aria-labelledby="assignModalLabel<?php echo e($job->id); ?>" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <form action="<?php echo e(route('jobs.assign', $job)); ?>" method="POST" id="assignForm<?php echo e($job->id); ?>">
                            <?php echo csrf_field(); ?>
                            <input type="hidden" name="job_id" value="<?php echo e($job->id); ?>">
                            <div class="modal-header">
                                <h5 class="modal-title" id="assignModalLabel<?php echo e($job->id); ?>">
                                    <i class="entypo-users"></i> Assign Cleaners to Job #<?php echo e($job->id); ?>

                                </h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <div class="form-group mb-3">
                                    <label class="form-label">
                                        <i class="entypo-user"></i> Select Cleaners
                                    </label>
                                    <div class="cleaner-list" style="max-height: 300px; overflow-y: auto;">
                                        <?php $__currentLoopData = $availableEmployees ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $employee): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <div class="form-check mb-2">
                                                <input type="checkbox" 
                                                    class="form-check-input cleaner-checkbox" 
                                                    name="employee_ids[]" 
                                                    value="<?php echo e($employee->id ?? ''); ?>"
                                                    id="employee<?php echo e($job->id ?? ''); ?>_<?php echo e($employee->id ?? ''); ?>"
                                                    data-form-id="assignForm<?php echo e($job->id); ?>">
                                                <label class="form-check-label" for="employee<?php echo e($job->id ?? ''); ?>_<?php echo e($employee->id ?? ''); ?>">
                                                    <?php echo e($employee ? $employee->name : 'N/A'); ?> - <?php echo e($employee ? $employee->phone : 'N/A'); ?>

                                                </label>
                                            </div>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </div>
                                    <div class="form-text text-muted mt-2">
                                        <i class="entypo-info-circled"></i> Check the boxes to select multiple cleaners
                                    </div>
                                    <div class="alert alert-danger mt-2" id="assignError<?php echo e($job->id); ?>" style="display: none;">
                                        Please select at least one cleaner.
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">
                                    <i class="entypo-cancel"></i> Cancel
                                </button>
                                <button type="submit" class="btn btn-primary" id="assignSubmitBtn<?php echo e($job->id); ?>">
                                    <i class="entypo-check"></i> Assign Cleaners
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
$(document).ready(function() {
    console.log('Document ready - initializing cancel job functionality');

    var cancelTargetForm = null;
    var $modal = $('#cancelJobModal');

    $(document).on('click', '.cancel-job-btn', function(e) {
        e.preventDefault();
        cancelTargetForm = $(this).closest('form');
        var jobId = $(this).data('job-id');

        // Get the table row containing the button to extract customer and service details
        var $row = $(this).closest('tr');
        var customerName = $row.find('td:nth-child(1)').text().trim();
        var serviceName = $row.find('td:nth-child(3)').text().trim();

        $('#cancelJobMessage').text('Are you sure you want to cancel the job for ' + customerName + ' (' + serviceName + ')?');
        $('#confirmCancelJobBtn').prop('disabled', false).removeClass('disabled')
            .html('<i class="entypo-block"></i> Yes, Cancel Job');
        try {
            $modal.modal('show');
        } catch (error) {
            if (confirm('Are you sure you want to cancel the job for ' + customerName + ' (' + serviceName + ')?')) {
                cancelTargetForm.submit();
            }
        }
    });

    $(document).on('click', '#confirmCancelJobBtn', function(e) {
        e.preventDefault();
        if (!cancelTargetForm) return;
        var $btn = $(this);
        $btn.prop('disabled', true).addClass('disabled');
        $btn.html('<i class="entypo-hourglass"></i> Cancelling...');
        cancelTargetForm.submit();
    });
});
</script>

<!-- Cancel Job confirmation Modal -->
<div class="modal fade" id="cancelJobModal" tabindex="-1" role="dialog" aria-labelledby="cancelJobModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="cancelJobModalLabel">Cancel Job</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span>&times;</span></button>
            </div>
            <div class="modal-body">
                <p id="cancelJobMessage">Are you sure you want to cancel this job?</p>
                <p class="text-muted mt-2">
                    This action cannot be undone. The customer will be notified about the cancellation.
                </p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Keep Job</button>
                <button type="button" class="btn btn-danger" id="confirmCancelJobBtn">
                    <i class="entypo-block"></i> Yes, Cancel Job
                </button>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /opt/lampp/htdocs/cleaning_service_management_system/resources/views/admin/jobs/tracking.blade.php ENDPATH**/ ?>