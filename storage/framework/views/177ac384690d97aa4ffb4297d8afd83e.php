<?php $__env->startSection('content'); ?>
<div class="container">
    <h2>🧹 Job Completion Report</h2>

    <?php if(session('success')): ?>
        <div class="alert alert-success">
            <?php echo e(session('success')); ?>

        </div>
    <?php endif; ?>

    <!-- Filters Section -->
    <div class="card mb-4">
        <div class="card-header">
            <h5>Filters</h5>
        </div>
        <div class="card-body">
            <form method="GET" action="">
                <div class="row">
                    <div class="col-md-3">
                        <label for="start_date" class="form-label">From Date</label>
                        <input type="date" class="form-control" id="start_date" name="start_date" 
                            value="<?php echo e($request->start_date); ?>">
                    </div>
                    <div class="col-md-3">
                        <label for="end_date" class="form-label">To Date</label>
                        <input type="date" class="form-control" id="end_date" name="end_date" 
                            value="<?php echo e($request->end_date); ?>">
                    </div>
                    <div class="col-md-3 d-flex align-items-end">
                        <a href="<?php echo e(request()->url()); ?>" class="btn btn-danger w-100 text-white">
                            <i class="fas fa-undo"></i> Reset Filters
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card text-white h-100 border-0 shadow-sm" style="background: linear-gradient(135deg, #10B981 0%, #34D399 100%);">
                <div class="card-body d-flex flex-column">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="card-title mb-0"><i class="fas fa-check-circle me-2"></i>Completed Jobs</h5>
                        <div class="bg-white-20 p-2 rounded-circle">
                            <i class="fas fa-check-circle"></i>
                        </div>
                    </div>
                    <h2 class="display-5 fw-bold mb-0"><?php echo e(number_format($completionStats['completed'])); ?></h2>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-white h-100 border-0 shadow-sm" style="background: linear-gradient(135deg, #F59E0B 0%, #FBBF24 100%);">
                <div class="card-body d-flex flex-column">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="card-title mb-0"><i class="fas fa-clock me-2"></i>Pending Jobs</h5>
                        <div class="bg-white-20 p-2 rounded-circle">
                            <i class="fas fa-clock"></i>
                        </div>
                    </div>
                    <h2 class="display-5 fw-bold mb-0"><?php echo e(number_format($completionStats['pending'])); ?></h2>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-white h-100 border-0 shadow-sm" style="background: linear-gradient(135deg, #EF4444 0%, #F87171 100%);">
                <div class="card-body d-flex flex-column">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <h5 class="card-title mb-0"><i class="fas fa-times-circle me-2"></i>Cancelled Bookings</h5>
                        <div class="bg-white-20 p-2 rounded-circle">
                            <i class="fas fa-times-circle"></i>
                        </div>
                    </div>
                    <h2 class="display-5 fw-bold mb-1"><?php echo e(number_format($completionStats['cancelled'])); ?></h2>
                    <p class="mb-0 text-white-80" style="font-size: 0.8rem;">(From Online Booking System)</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Jobs Table -->
    <div class="card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5>Completed Jobs</h5>
            <span class="badge bg-primary">Total: <?php echo e($jobs->total()); ?></span>
        </div>
        <div class="card-body">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Customer Name</th>
                        <th>Service</th>
                        <th>Cleaner Assigned</th>
                        <th>Date Completed</th>
                        <th>Rating</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $jobs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $job): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr>
                            <td><?php echo e($job->customer->name ?? 'N/A'); ?></td>
                            <td><?php echo e($job->service->name ?? 'N/A'); ?></td>
                            <td>
                                <?php if($job->employees->count() > 0): ?>
                                    <?php $__currentLoopData = $job->employees; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $employee): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <span class="badge bg-info"><?php echo e($employee->name); ?></span>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                <?php else: ?>
                                    <span class="text-muted">Not Assigned</span>
                                <?php endif; ?>
                            </td>
                            <td><?php echo e($job->completed_at ? $job->completed_at->format('M d, Y') : 'N/A'); ?></td>
                            <td>
                                <?php if($job->rating): ?>
                                    <div class="star-rating">
                                        <?php for($i = 1; $i <= 5; $i++): ?>
                                            <?php if($i <= $job->rating->rating): ?>
                                                <span class="star filled">★</span>
                                            <?php else: ?>
                                                <span class="star">☆</span>
                                            <?php endif; ?>
                                        <?php endfor; ?>
                                        <span class="rating-value">(<?php echo e(number_format($job->rating->rating, 1)); ?>)</span>
                                    </div>
                                <?php else: ?>
                                    No rating
                                <?php endif; ?>
                            </td>
                            <td>
                                <span class="badge bg-success"><?php echo e(ucfirst($job->status)); ?></span>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="8" class="text-center">No completed jobs found</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>

            <!-- Pagination -->
            <?php if($jobs->hasPages()): ?>
                <div class="d-flex justify-content-center mt-3">
                    <?php echo e($jobs->links()); ?>

                </div>
            <?php endif; ?>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('styles'); ?>
<style>
    .star-rating {
        display: inline-flex;
        align-items: center;
    }
    .star {
        color: #ddd;
        font-size: 1rem;
    }
    .star.filled {
        color: #ffc107;
    }
    .rating-value {
        margin-left: 5px;
        font-size: 0.8rem;
        color: #666;
    }
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startPush('scripts'); ?>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Auto-submit form when date inputs change
    document.addEventListener('DOMContentLoaded', function() {
        const dateInputs = document.querySelectorAll('input[type="date"]');
        dateInputs.forEach(input => {
            input.addEventListener('change', function() {
                this.closest('form').submit();
            });
        });
        
        // Debug: Log the data to console
        console.log('Cleaner Names:', <?php echo json_encode($cleanerRatings->pluck('name')->toArray()); ?>);
        console.log('Cleaner Ratings:', <?php echo json_encode($cleanerRatings->pluck('ratings_avg_rating')->toArray()); ?>);
    });
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /opt/lampp/htdocs/cleaning_service_management_system/resources/views/admin/reports/jobs/completion.blade.php ENDPATH**/ ?>