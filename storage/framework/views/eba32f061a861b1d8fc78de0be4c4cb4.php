<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Checklists</h5>
            <a href="<?php echo e(route('checklists.add')); ?>" class="btn btn-primary btn-sm">
                <i class="entypo-plus"></i> Add New
            </a>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover datatable">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Description</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__currentLoopData = $checklists; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $checklist): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <td><?php echo e($checklist->name); ?></td>
                            <td><?php echo e(Str::limit($checklist->description, 50)); ?></td>
                            <td>
                                <span class="badge badge-<?php echo e($checklist->is_active ? 'success' : 'danger'); ?>">
                                    <?php echo e($checklist->is_active ? 'Active' : 'Inactive'); ?>

                                </span>
                            </td>
                            <td>
                                <div class="btn-group btn-group-sm">
                                    <a href="<?php echo e(route('checklists.edit', $checklist->id)); ?>"
                                       class="btn btn-warning rounded-circle"
                                       data-tooltip="Edit">
                                        <i class="entypo-pencil"></i>
                                    </a>
                                    <form action="<?php echo e(route('checklists.destroy', $checklist->id)); ?>" method="POST" class="d-inline">
                                        <?php echo csrf_field(); ?>
                                        <?php echo method_field('DELETE'); ?>
                                        <button type="submit"
                                                class="btn btn-danger rounded-circle"
                                                data-tooltip="Delete"
                                                data-action="delete-checklist"
                                                data-checklist-name="<?php echo e($checklist->name); ?>">
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
            <div class="mt-3">
                <?php echo e($checklists->links()); ?>

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
                    <p id="confirmDeleteMessage">Are you sure you want to delete this item? This action cannot be undone.</p>
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
    $(document).on('click', 'button[data-action="delete-checklist"]', function(e){
        e.preventDefault();
        formToSubmit = $(this).closest('form');
        var name = $(this).data('checklist-name');
        var msg = 'Are you sure you want to delete this checklist? This action cannot be undone.';
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
<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /opt/lampp/htdocs/cleaning_service_management_system/resources/views/admin/checklists/index.blade.php ENDPATH**/ ?>