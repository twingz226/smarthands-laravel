<?php $__env->startSection('title', 'Trash Bin'); ?>

<?php $__env->startSection('content'); ?>
<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title">Trash Bin</h3>
                <p class="text-muted" style="margin: 0;">Manage deleted contact messages.</p>
            </div>
            <div class="panel-body">

                <h4 class="mb-3">Deleted Contact Messages</h4>
                <?php if($deletedMessages->isEmpty()): ?>
                    <div class="alert alert-info">
                        <i class="entypo-info"></i> No deleted contact messages found.
                    </div>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>From</th>
                                    <th>Email</th>
                                    <th>Message</th>
                                    <th>Deleted At</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__currentLoopData = $deletedMessages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $message): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr>
                                        <td><?php echo e($message->name); ?></td>
                                        <td><?php echo e($message->email); ?></td>
                                        <td><?php echo e(Str::limit($message->message, 50)); ?></td>
                                        <td><?php echo e($message->deleted_at->format('F j, Y g:i A')); ?></td>
                                        <td>
                                            <div class="btn-group">
                                                <form action="<?php echo e(route('admin.contact_messages.restore', $message->id)); ?>" 
                                                      method="POST" 
                                                      style="display: inline-block;">
                                                    <?php echo csrf_field(); ?>
                                                    <?php echo method_field('POST'); ?>
                                                    <button type="submit" 
                                                            class="btn btn-xs btn-warning"
                                                            title="Restore">
                                                        <i class="entypo-ccw"></i>
                                                    </button>
                                                </form>
                                                <form id="force-delete-form-<?php echo e($message->id); ?>" 
                                                      action="<?php echo e(route('admin.contact_messages.force_delete', $message->id)); ?>" 
                                                      method="POST" 
                                                      style="display: inline-block;">
                                                    <?php echo csrf_field(); ?>
                                                    <?php echo method_field('DELETE'); ?>
                                                </form>
                                                <button type="button" 
                                                        class="btn btn-xs btn-danger"
                                                        title="Permanently Delete"
                                                        data-delete-form="force-delete-form-<?php echo e($message->id); ?>">
                                                    <i class="entypo-trash"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- Permanent Delete Confirmation Modal -->
<div class="modal fade" id="confirmDeleteModal" tabindex="-1" role="dialog" aria-labelledby="confirmDeleteLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header bg-danger text-white">
        <h5 class="modal-title" id="confirmDeleteLabel">
          <i class="entypo-trash"></i> Confirm Permanent Delete
        </h5>
        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <p><strong>Warning:</strong> Are you sure you want to permanently delete this message?</p>
        <p class="text-danger">This action cannot be undone and the message will be removed from the trash bin forever.</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">
          <i class="entypo-cancel"></i> Cancel
        </button>
        <button type="button" class="btn btn-danger" id="confirmDeleteBtn">
          <i class="entypo-trash"></i> Permanently Delete
        </button>
      </div>
    </div>
  </div>
</div>

<script>
$(document).ready(function() {
    var deleteForm = null;
    
    // Handle delete buttons with data-delete-form attribute
    $('[data-delete-form]').on('click', function(e) {
        e.preventDefault();
        var formId = $(this).data('delete-form');
        deleteForm = $('#' + formId);
        $('#confirmDeleteModal').modal('show');
    });
    
    // Handle form submission when delete is confirmed
    $('#confirmDeleteBtn').on('click', function() {
        if (deleteForm) {
            deleteForm.submit();
        }
    });
    
    // Reset form reference when modal is closed
    $('#confirmDeleteModal').on('hidden.bs.modal', function() {
        deleteForm = null;
    });
});
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /opt/lampp/htdocs/cleaning_service_management_system/resources/views/admin/trash/index.blade.php ENDPATH**/ ?>