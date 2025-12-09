<?php $__env->startSection('content'); ?>
<div class="container mt-5">
    <h2 class="mb-4">Add New Service</h2>

    
    <?php if($errors->any()): ?>
        <div class="alert alert-danger">
            <ul class="mb-0">
                <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <li><?php echo e($error); ?></li>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </ul>
        </div>
    <?php endif; ?>

    <form action="<?php echo e(route('services.store')); ?>" method="POST">
        <?php echo csrf_field(); ?>

        
        <div class="mb-3">
            <label for="name" class="form-label">Service Name</label>
            <input type="text" name="name" id="name" class="form-control" placeholder="Enter service name" required>
        </div>

        
        <div class="mb-3">
            <label for="description" class="form-label">Service Description</label>
            <textarea name="description" id="description" class="form-control" rows="4" placeholder="Enter service description (optional)"></textarea>
        </div>

        
        <div class="mb-3">
            <label for="pricing_type" class="form-label">Pricing Type</label>
            <select name="pricing_type" id="pricing_type" class="form-select" required>
                <option value="" disabled selected>Select pricing type</option>
                <option value="sqm">Per Square Meter (₱/sqm)</option>
                <option value="duration">Per Hour (₱/hr)</option>
            </select>
        </div>

        
        <div class="mb-3">
            <label for="price" class="form-label">Price</label>
            <div class="input-group">
                <span class="input-group-text">₱</span>
                <input type="number" name="price" id="price" class="form-control" step="0.01" min="0" required>
                <span class="input-group-text" id="price-unit">/sqm</span>
            </div>
        </div>

        
        <div class="mb-3" id="duration-group" style="display: none;">
            <label for="duration_minutes" class="form-label">Duration</label>
            <div class="input-group">
                <input type="number" name="duration_minutes" id="duration_minutes" class="form-control" min="1">
                <span class="input-group-text">minutes</span>
            </div>
        </div>

        
        <button type="submit" class="btn btn-primary">Create Service</button>
        <a href="<?php echo e(route('services.index')); ?>" class="btn btn-secondary">Cancel</a>
    </form>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const pricingType = document.getElementById('pricing_type');
    const durationGroup = document.getElementById('duration-group');
    const priceUnit = document.getElementById('price-unit');
    const durationInput = document.getElementById('duration_minutes');

    pricingType.addEventListener('change', function () {
        if (this.value === 'sqm') {
            durationGroup.style.display = 'none';
            priceUnit.textContent = '/sqm';
        } else if (this.value === 'duration') {
            durationGroup.style.display = 'block';
            priceUnit.textContent = '/hr';
        }
    });
});
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /opt/lampp/htdocs/cleaning_service_management_system/resources/views/admin/services/create.blade.php ENDPATH**/ ?>