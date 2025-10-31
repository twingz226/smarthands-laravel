<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <h3 class="mb-3"><i class="entypo-calendar"></i> Disabled Dates</h3>

    <?php if(session('success')): ?>
        <div class="alert alert-success"><?php echo e(session('success')); ?></div>
    <?php endif; ?>
    <?php if($errors->any()): ?>
        <div class="alert alert-danger">
            <ul class="mb-0">
                <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <li><?php echo e($error); ?></li>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </ul>
        </div>
    <?php endif; ?>

    <div class="panel panel-default">
        <div class="panel-heading">
            <h4 class="panel-title">Add Disabled Date</h4>
        </div>
        <div class="panel-body">
            <form method="POST" action="<?php echo e(route('admin.disabled_dates.store')); ?>" class="form-inline">
                <?php echo csrf_field(); ?>
                <div class="form-group mb-2 me-2">
                    <label for="date" class="me-2">Date</label>
                    <input type="date" id="date" name="date" class="form-control" required>
                </div>
                <div class="form-group mb-2 me-2" style="min-width: 320px;">
                    <label for="reason" class="me-2">Reason</label>
                    <input type="text" id="reason" name="reason" class="form-control" placeholder="Holiday, maintenance, etc.">
                </div>
                <button type="submit" class="btn btn-primary mb-2">Add</button>
            </form>
        </div>
    </div>

    <div class="panel panel-default">
        <div class="panel-heading">
            <h4 class="panel-title">Disabled Dates List</h4>
        </div>
        <div class="panel-body">
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Reason</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__empty_1 = true; $__currentLoopData = $dates; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $d): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr>
                                <td><?php echo e(\Carbon\Carbon::parse($d->date)->format('M d, Y')); ?></td>
                                <td><?php echo e($d->reason ?? '-'); ?></td>
                                <td>
                                    <span class="label <?php echo e($d->is_active ? 'label-danger' : 'label-default'); ?>">
                                        <?php echo e($d->is_active ? 'Disabled' : 'Inactive'); ?>

                                    </span>
                                </td>
                                <td class="d-flex" style="gap:8px;">
                                    <form method="POST" action="<?php echo e(route('admin.disabled_dates.update', $d)); ?>" class="d-inline">
                                        <?php echo csrf_field(); ?>
                                        <?php echo method_field('PUT'); ?>
                                        <input type="hidden" name="is_active" value="<?php echo e($d->is_active ? 0 : 1); ?>">
                                        <input type="hidden" name="reason" value="<?php echo e($d->reason); ?>">
                                        <button type="submit" class="btn btn-xs <?php echo e($d->is_active ? 'btn-default' : 'btn-success'); ?>">
                                            <?php echo e($d->is_active ? 'Mark Inactive' : 'Activate'); ?>

                                        </button>
                                    </form>
                                    <form method="POST" action="<?php echo e(route('admin.disabled_dates.destroy', $d)); ?>" class="d-inline" onsubmit="return confirm('Remove this disabled date?');">
                                        <?php echo csrf_field(); ?>
                                        <?php echo method_field('DELETE'); ?>
                                        <button type="submit" class="btn btn-xs btn-danger">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr>
                                <td colspan="4" class="text-center text-muted">No disabled dates configured.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /opt/lampp/htdocs/cleaning_service_management_system/resources/views/admin/disabled_dates/index.blade.php ENDPATH**/ ?>