<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Employee Database</h1>
        <div class="btn-toolbar mb-2 mb-md-0">
            <a href="<?php echo e(route('employees.create')); ?>" class="btn btn-primary">
                <i class="entypo-plus"></i> Add New Cleaner
            </a>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-hover data-table">
                    <thead>
                        <tr>
                            <th>Employee No.</th>
                            <th>Name</th>
                            <th>Position</th>
                            <th>Phone</th>
                            <th>Hire Date</th>
                            <th>Rating</th>
                            <th>Photo Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__currentLoopData = $employees; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $employee): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <td>
                                <?php if($employee->employee_no): ?>
                                    <?php echo e($employee->employee_no); ?>

                                <?php else: ?>
                                    <span class="text-muted">Not assigned</span>
                                <?php endif; ?>
                            </td>
                            <td><?php echo e($employee->name); ?></td>
                            <td>
                                <?php if($employee->position): ?>
                                    <?php echo e($employee->position); ?>

                                <?php else: ?>
                                    <span class="text-muted">Not specified</span>
                                <?php endif; ?>
                            </td>
                            <td><?php echo e($employee->phone); ?></td>
                            <td><?php echo e($employee->hire_date->format('M d, Y')); ?></td>
                            <td>
                                <?php if($employee->ratings_avg_rating): ?>
                                    <?php echo e(number_format($employee->ratings_avg_rating, 1)); ?> / 5.0
                                <?php else: ?>
                                    No ratings yet
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if($employee->hasPhotos()): ?>
                                    <span class="badge bg-success">
                                        <i class="entypo-check"></i> Approved
                                    </span>
                                <?php else: ?>
                                    <span class="badge bg-secondary">
                                        <i class="entypo-camera"></i> No Photos
                                    </span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <div class="btn-group btn-group-sm">
                                    <a href="<?php echo e(route('employees.show', $employee->id)); ?>"
                                       class="btn btn-info rounded-circle"
                                       data-tooltip="View">
                                        <i class="entypo-eye"></i>
                                    </a>
                                    <a href="<?php echo e(route('employees.edit', $employee->id)); ?>"
                                       class="btn btn-warning rounded-circle"
                                       data-tooltip="Edit">
                                        <i class="entypo-pencil"></i>
                                    </a>
                                    <form action="<?php echo e(route('employees.destroy', $employee->id)); ?>" method="POST" class="d-inline">
                                        <?php echo csrf_field(); ?>
                                        <?php echo method_field('DELETE'); ?>
                                        <button type="submit"
                                                class="btn btn-danger rounded-circle"
                                                data-tooltip="Delete"
                                                data-action="delete-employee"
                                                data-employee-name="<?php echo e($employee->name); ?>">
                                            <i class="entypo-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div class="modal fade" id="confirmDeleteModal" tabindex="-1" role="dialog" aria-labelledby="confirmDeleteLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="confirmDeleteLabel">Confirm Deletion</h4>
                </div>
                <div class="modal-body">
                    <p id="confirmDeleteMessage">Are you sure you want to delete this employee? This action cannot be undone.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-danger" id="confirmDeleteBtn">Delete</button>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('styles'); ?>
<style>
.btn.rounded-circle {
    width: 32px;
    height: 32px;
    padding: 0;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    margin: 0 2px;
    position: relative;
    border-radius: 50% !important;
    transition: all 0.3s ease;
}

.btn.rounded-circle:hover {
    transform: scale(1.1);
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
}

.btn.rounded-circle i {
    margin: 0;
    font-size: 14px;
}

[data-tooltip] {
    position: relative;
}

[data-tooltip]:before {
    content: attr(data-tooltip);
    position: absolute;
    bottom: 100%;
    left: 50%;
    transform: translateX(-50%);
    padding: 4px 8px;
    background: rgba(0, 0, 0, 0.8);
    color: white;
    border-radius: 4px;
    font-size: 12px;
    white-space: nowrap;
    visibility: hidden;
    opacity: 0;
    transition: opacity 0.3s ease;
    z-index: 1000;
}

[data-tooltip]:hover:before {
    visibility: visible;
    opacity: 1;
}
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
(function(){
    var formToSubmit = null;
    $(document).on('click', 'button[data-action="delete-employee"]', function(e){
        e.preventDefault();
        formToSubmit = $(this).closest('form');
        var name = $(this).data('employee-name');
        var msg = 'Are you sure you want to delete this employee? This action cannot be undone.';
        if (name) {
            msg = 'Are you sure you want to delete \'' + name + '\'? This action cannot be undone.';
        }
        $('#confirmDeleteMessage').text(msg);
        $('#confirmDeleteModal').modal('show');
    });

    $('#confirmDeleteBtn').on('click', function(){
        if (formToSubmit) {
            formToSubmit.trigger('submit');
            formToSubmit = null;
        }
        $('#confirmDeleteModal').modal('hide');
    });
})();
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /opt/lampp/htdocs/cleaning_service_management_system/resources/views/admin/employees/index.blade.php ENDPATH**/ ?>