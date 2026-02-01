<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12 text-center">
            <h1 class="h2 pt-3 pb-2 mb-3 border-bottom">Customer List Report</h1>
        </div>
    </div>

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

    <div class="row">
        <div class="col-md-12 text-right">
            <a href="<?php echo e(route('reports.customers.export.pdf', request()->query())); ?>" class="btn btn-lg btn-secondary">
                <i class="fas fa-file-pdf"></i> Export PDF
            </a>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-hover data-table">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Contact</th>
                            <th>Total Jobs</th>
                            <th>Last Service Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__currentLoopData = $customers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $customer): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <td><?php echo e($customer->name); ?></td>
                            <td><?php echo e($customer->email); ?></td>
                            <td><?php echo e($customer->contact); ?></td>
                            <td><?php echo e($customer->jobs_count); ?></td>
                            <td><?php echo e($customer->jobs_max_scheduled_date ? \Carbon\Carbon::parse($customer->jobs_max_scheduled_date)->format('M d, Y') : 'N/A'); ?></td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<style>
@media print {
    body * {
        visibility: hidden;
    }
    
    .h2, .h2 *, .card-body, .card-body * {
        visibility: visible;
    }
    
    .h2 {
        position: absolute;
        left: 0;
        top: 0;
        width: 100%;
        padding: 10px 20px 0 20px;
        box-sizing: border-box;
        text-align: center;
        font-size: 18px;
        font-weight: bold;
        border-bottom: 2px solid #333 !important;
        margin-bottom: 0;
        z-index: 10;
    }
    
    .card-body {
        position: absolute;
        left: 0;
        top: 40px;
        width: 100%;
        padding: 5px 20px 20px 20px;
        box-sizing: border-box;
        z-index: 5;
    }
    
    .table-responsive {
        overflow-x: visible;
    }
    
    body {
        font-size: 12px;
        margin: 0;
        padding: 0;
    }
    
    .table {
        width: 100%;
        margin: 0 auto;
        border-collapse: collapse;
    }
    
    .table th, .table td {
        padding: 8px 12px;
        text-align: left;
        border: 1px solid #ddd;
    }
    
    .table th {
        background-color: #f5f5f5 !important;
        -webkit-print-color-adjust: exact;
        print-color-adjust: exact;
        font-weight: bold;
    }
    
    .table td {
        vertical-align: top;
    }
    
    @page {
        margin: 0.3in;
        size: auto;
    }
    
    @page :header {
        display: none;
    }
    
    @page :footer {
        display: none;
    }
    
    header, footer {
        display: none !important;
    }
}
</style>

<?php $__env->startPush('scripts'); ?>
<script>
    // Auto-submit form when date inputs change
    document.addEventListener('DOMContentLoaded', function() {
        const dateInputs = document.querySelectorAll('input[type="date"]');
        dateInputs.forEach(input => {
            input.addEventListener('change', function() {
                this.closest('form').submit();
            });
        });
    });
</script>
<?php $__env->stopPush(); ?>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /opt/lampp/htdocs/cleaning_service_management_system/resources/views/admin/reports/customers/list.blade.php ENDPATH**/ ?>