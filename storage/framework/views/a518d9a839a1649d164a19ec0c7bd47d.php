

<?php $__env->startSection('content'); ?>
<div class="container mt-4">
    <h2>Company Logo</h2>
    <?php if(session('success')): ?>
        <div class="alert alert-success"><?php echo e(session('success')); ?></div>
    <?php endif; ?>
    <div class="card">
        <div class="card-body">
            <form action="<?php echo e(route('admin.settings.logo.update')); ?>" method="POST" enctype="multipart/form-data">
                <?php echo csrf_field(); ?>
                <div class="mb-3">
                    <label for="company_logo" class="form-label">Upload New Logo</label>
                    <div class="custom-file-upload-wrapper">
                        <input type="file" class="form-control d-none" id="company_logo" name="company_logo" accept="image/*">
                        <button type="button" id="customFileBtn" class="btn btn-secondary">Choose File</button>
                        <span id="selectedFileName" class="ms-2 text-muted">No file chosen</span>
                    </div>
                    <small class="form-text text-muted">Supported formats: JPEG, PNG, JPG, GIF. Max size: 2MB.</small>
                    <?php $__errorArgs = ['company_logo'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <div class="text-danger small"><?php echo e($message); ?></div>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
                <div class="mb-3 text-center">
                    <label class="form-label">Current Logo</label><br>
                    <?php if($logo): ?>
                        <img src="<?php echo e(asset('storage/' . $logo)); ?>" alt="Company Logo" style="max-height: 120px;" class="center-logo-img">
                    <?php else: ?>
                        <img src="<?php echo e(asset('images/Smarthands.png')); ?>" alt="Default Logo" style="max-height: 120px;" class="center-logo-img">
                    <?php endif; ?>
                </div>
                <button type="submit" class="btn btn-primary">Update Logo</button>
            </form>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const fileInput = document.getElementById('company_logo');
    const customBtn = document.getElementById('customFileBtn');
    const fileNameSpan = document.getElementById('selectedFileName');
    if (customBtn && fileInput) {
        customBtn.addEventListener('click', function() {
            fileInput.click();
        });
        fileInput.addEventListener('change', function() {
            if (fileInput.files && fileInput.files.length > 0) {
                fileNameSpan.textContent = fileInput.files[0].name;
            } else {
                fileNameSpan.textContent = 'No file chosen';
            }
        });
    }
});
</script>
<?php $__env->stopPush(); ?> 
<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /opt/lampp/htdocs/cleaning_service_management_system/resources/views/admin/settings/edit.blade.php ENDPATH**/ ?>