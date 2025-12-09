<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
  <title><?php echo e(config('app.name', 'Laravel')); ?></title>

  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

  
</head>
<body>
  
  
  <main>
    <?php echo $__env->yieldContent('content'); ?>
  </main>

  <script src="<?php echo e(asset('js/fade-effect.js')); ?>"></script>

  <!-- Bootstrap JS Bundle with Popper -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

  <!-- Logout Form for all pages -->
  <form id="logout-form" action="<?php echo e(route('logout')); ?>" method="POST" style="display: none;">
      <?php echo csrf_field(); ?>
  </form>
</body>
</html><?php /**PATH /opt/lampp/htdocs/cleaning_service_management_system/resources/views/layouts/app.blade.php ENDPATH**/ ?>