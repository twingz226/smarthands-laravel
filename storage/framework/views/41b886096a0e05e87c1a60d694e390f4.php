<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Rate Our Service</title>
    <link rel="icon" href="<?php echo e(asset('images/Smarthand.png')); ?>" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        .star-rating {
            display: flex;
            flex-direction: row-reverse;
            justify-content: flex-start;
            margin-bottom: 1rem;
        }
        
        .star-rating input[type="radio"] {
            display: none;
        }
        
        .star-rating label {
            font-size: 2.5rem;
            color: #ddd;
            cursor: pointer;
            padding: 0 0.2em;
        }
        
        .star-rating label:hover,
        .star-rating label:hover ~ label,
        .star-rating input[type="radio"]:checked ~ label {
            color: #ffc107;
        }
        
        .star-rating label:hover,
        .star-rating label:hover ~ label {
            transform: scale(1.1);
        }
    </style>
</head>
<body class="bg-light">
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow">
                    <div class="card-header bg-primary text-white">
                        <h4 class="mb-0">Rate Our Service</h4>
                    </div>
                    <div class="card-body">
                        <?php if(session('success')): ?>
                            <div class="alert alert-success">
                                <?php echo e(session('success')); ?>

                                <p class="mt-3 mb-0">You can now close this window.</p>
                            </div>
                        <?php elseif(session('error')): ?>
                            <div class="alert alert-danger">
                                <?php echo e(session('error')); ?>

                            </div>
                        <?php elseif(isset($alreadyRated) && $alreadyRated): ?>
                            <div class="alert alert-info">
                                <i class="fas fa-info-circle"></i> This job has already been rated. Thank you for your feedback!
                                <p class="mt-3 mb-0">You can now close this window.</p>
                            </div>
                        <?php else: ?>
                            <div class="mb-4">
                                <h5>Job Details</h5>
                                <p><strong>Service:</strong> <?php echo e($job->service->name); ?></p>
                                <p><strong>Date:</strong> <?php echo e($job->completed_at->format('M d, Y')); ?></p>
                            </div>

                            <?php if($errors->any()): ?>
                                <div class="alert alert-danger">
                                    <strong>Please fix the following errors:</strong>
                                    <ul class="mb-0 mt-2">
                                        <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <li><?php echo e($error); ?></li>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </ul>
                                </div>
                            <?php endif; ?>

                            <form action="<?php echo e(route('public.rating.submit', ['ratingToken' => $job->rating_token])); ?>" method="POST" id="ratingForm">
                                <?php echo csrf_field(); ?>
                                
                                <?php $__currentLoopData = $job->employees; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $employee): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div class="mb-4 text-center">
                                    <?php
                                        $photoUrl = $employee->getPrimaryPhotoUrl();
                                    ?>
                                    <?php if($photoUrl): ?>
                                        <img src="<?php echo e($photoUrl); ?>" alt="<?php echo e($employee->name); ?>" class="img-thumbnail mb-2" style="max-height: 140px; max-width: 140px; border-radius: 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.08);">
                                    <?php else: ?>
                                        <img src="<?php echo e(asset('images/default-avatar.png')); ?>" alt="No Photo" class="img-thumbnail mb-2" style="max-height: 140px; max-width: 140px; border-radius: 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.08);">
                                    <?php endif; ?>
                                    <h5>Rate <?php echo e($employee->name); ?></h5>
                                    <div class="star-rating">
                                        <?php for($i = 5; $i >= 1; $i--): ?>
                                            <input type="radio" 
                                                   id="star<?php echo e($employee->id); ?>_<?php echo e($i); ?>" 
                                                   name="ratings[<?php echo e($employee->id); ?>]" 
                                                   value="<?php echo e($i); ?>" 
                                                   required>
                                            <label for="star<?php echo e($employee->id); ?>_<?php echo e($i); ?>" title="<?php echo e($i); ?> stars">
                                                <i class="fas fa-star"></i>
                                            </label>
                                        <?php endfor; ?>
                                    </div>
                                    <?php $__errorArgs = ["ratings.{$employee->id}"];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <div class="text-danger"><?php echo e($message); ?></div>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                                <div class="mb-4">
                                    <label for="comments" class="form-label">Your Overall Feedback (Optional)</label>
                                    <textarea class="form-control" id="comments" name="comments" rows="4" 
                                        placeholder="Tell us about your experience..."><?php echo e(old('comments')); ?></textarea>
                                    <?php $__errorArgs = ['comments'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <div class="text-danger"><?php echo e($message); ?></div>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>

                                <button type="submit" class="btn btn-primary btn-lg w-100" id="submitBtn">
                                    <i class="fas fa-paper-plane"></i> Submit Ratings
                                </button>
                            </form>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('ratingForm');
            const submitBtn = document.getElementById('submitBtn');
            
            if (form && submitBtn) {
                form.addEventListener('submit', function(e) {
                    // Check if at least one rating is selected for each employee
                    const employees = document.querySelectorAll('[name^="ratings["]');
                    const employeeIds = new Set();
                    
                    employees.forEach(input => {
                        const match = input.name.match(/ratings\[(\d+)\]/);
                        if (match) {
                            employeeIds.add(match[1]);
                        }
                    });
                    
                    let allRated = true;
                    employeeIds.forEach(employeeId => {
                        const selected = document.querySelector(`input[name="ratings[${employeeId}]"]:checked`);
                        if (!selected) {
                            allRated = false;
                        }
                    });
                    
                    if (!allRated) {
                        e.preventDefault();
                        alert('Please rate all cleaners before submitting.');
                        return false;
                    }
                    
                    // Disable button and show loading state
                    submitBtn.disabled = true;
                    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Submitting...';
                });
            }
        });
    </script>
</body>
</html> <?php /**PATH /opt/lampp/htdocs/cleaning_service_management_system/resources/views/public/ratings/form.blade.php ENDPATH**/ ?>