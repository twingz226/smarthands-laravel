<?php if($fullyBookedDates->isEmpty()): ?>
    <p>No fully booked dates found.</p>
<?php else: ?>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Date</th>
                <th>Number of Bookings</th>
            </tr>
        </thead>
        <tbody>
            <?php $__currentLoopData = $fullyBookedDates; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $date): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr>
                    <td><?php echo e(\Carbon\Carbon::parse($date->booking_date)->format('F d, Y')); ?></td>
                    <td><?php echo e($date->booking_count); ?></td>
                </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </tbody>
    </table>
<?php endif; ?>
<?php /**PATH /opt/lampp/htdocs/cleaning_service_management_system/resources/views/admin/bookings/fully/booked/dates.blade.php ENDPATH**/ ?>