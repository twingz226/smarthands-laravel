<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Daily Schedule - <?php echo e($date); ?></title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            margin: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #333;
            padding-bottom: 10px;
        }
        .header h1 {
            margin: 0;
            color: #333;
        }
        .header p {
            margin: 5px 0 0 0;
            color: #666;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
            font-weight: bold;
        }
        .status-assigned {
            background-color: #e3f2fd;
            color: #1976d2;
        }
        .status-in_progress {
            background-color: #e8f5e8;
            color: #388e3c;
        }
        .no-jobs {
            text-align: center;
            font-style: italic;
            color: #666;
            margin: 50px 0;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Daily Schedule Report</h1>
        <p>Date: <?php echo e($date); ?></p>
        <p>Assigned & In Progress Jobs</p>
    </div>

    <?php if($assignedJobs->count() > 0): ?>
        <table>
            <thead>
                <tr>
                    <th>Customer Name</th>
                    <th>Address</th>
                    <th>Service</th>
                    <th>Assigned Employees</th>
                    <th>Status</th>
                    <th>Scheduled Time</th>
                </tr>
            </thead>
            <tbody>
                <?php $__currentLoopData = $assignedJobs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $job): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr>
                        <td><?php echo e($job->customer ? $job->customer->name : 'N/A'); ?></td>
                        <td><?php echo e($job->address); ?></td>
                        <td><?php echo e($job->service ? $job->service->name : 'N/A'); ?></td>
                        <td>
                            <?php if($job->employees->count() > 0): ?>
                                <?php echo e($job->employees->pluck('name')->implode(', ')); ?>

                            <?php else: ?>
                                Unassigned
                            <?php endif; ?>
                        </td>
                        <td class="status-<?php echo e($job->status); ?>">
                            <?php echo e(ucfirst($job->status)); ?>

                        </td>
                        <td><?php echo e($job->scheduled_date->format('g:i A')); ?></td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
        </table>

        <div class="footer">
            <p><strong>Total Jobs: <?php echo e($assignedJobs->count()); ?></strong></p>
            <p>Generated on: <?php echo e(\Carbon\Carbon::now()->format('F j, Y g:i A')); ?></p>
        </div>
    <?php else: ?>
        <div class="no-jobs">
            <p>No assigned or in-progress jobs for today.</p>
        </div>
    <?php endif; ?>
</body>
</html>
<?php /**PATH /opt/lampp/htdocs/cleaning_service_management_system/resources/views/admin/jobs/exports/daily_schedule_pdf.blade.php ENDPATH**/ ?>