<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <h1 class="mb-4">Contact Messages</h1>
    <?php if(session('success')): ?>
        <div class="alert alert-success"><?php echo e(session('success')); ?></div>
    <?php endif; ?>
    <div class="card">
        <div class="card-body p-0">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Message</th>
                        <th>Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                <?php $__empty_1 = true; $__currentLoopData = $messages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $msg): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr>
                        <td><?php echo e($msg->name); ?></td>
                        <td><?php echo e($msg->email); ?></td>
                        <td><?php echo e($msg->phone); ?></td>
                        <td><?php echo e(Str::limit($msg->message, 40)); ?></td>
                        <td><?php echo e($msg->created_at->format('Y-m-d H:i')); ?></td>
                        <td>
                            <a href="<?php echo e(route('admin.contact_messages.show', $msg->id)); ?>" class="btn btn-sm btn-info">View</a>
                            <form id="delete-form-<?php echo e($msg->id); ?>" action="<?php echo e(route('admin.contact_messages.destroy', $msg->id)); ?>" method="POST" style="display:none;">
                                <?php echo csrf_field(); ?>
                                <?php echo method_field('DELETE'); ?>
                            </form>
                            <button type="button" class="btn btn-sm btn-danger" data-delete-form="delete-form-<?php echo e($msg->id); ?>">
                                <i class="entypo-trash"></i> Delete
                            </button>
                        </td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr><td colspan="6" class="text-center">No messages found.</td></tr>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
    <div class="mt-3">
        <?php echo e($messages->links()); ?>

    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /opt/lampp/htdocs/cleaning_service_management_system/resources/views/admin/contact_messages/index.blade.php ENDPATH**/ ?>