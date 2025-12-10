




<?php $__env->startSection('content'); ?>
<link rel="stylesheet" href="<?php echo e(asset('css/login.css')); ?>" />
<div class="container" style="height: auto; padding: 48px; background: white; border-radius: 20px; box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1); border: 1px solid rgba(0, 0, 0, 0.05);">
    <h1 class="login-title" style="color: #1a1a1a; margin-bottom: 12px;">Forgot Your Password?</h1>
    <p class="text-center mb-6" style="color: #6b7280; font-size: 16px; line-height: 1.5;">Enter your email address below and we'll send you a link to reset your password.</p>

            <?php if(session('status')): ?>
                <div class="success-message" style="background: #10b981; color: white; padding: 12px 16px; border-radius: 8px; margin-bottom: 20px; text-align: center;">
                    <?php echo e(session('status')); ?>

                </div>
            <?php endif; ?>
    
    <form method="POST" action="<?php echo e(route('password.email')); ?>">
        <?php echo csrf_field(); ?>
                        <section class="input-box" style="margin-bottom: 24px;">
                            <input id="email" type="email" class="" name="email" value="" required="" autocomplete="email" autofocus="" placeholder="Email Address" style="background: #f9fafb; border: 1px solid #e5e7eb; color: #1a1a1a;">
                            <i class="bx bxs-user" style="color: #6b7280;"></i>
                                                    </section>

                        <button type="submit" class="login-button" style="margin-top: 12px; background: #3b82f6; color: white; padding: 12px 24px; border: none; border-radius: 8px; font-weight: 600; font-size: 16px; cursor: pointer; transition: all 0.2s;">
                            Send Reset Link
                        </button>
                    </form>
</div>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /opt/lampp/htdocs/cleaning_service_management_system/resources/views/auth/passwords/email.blade.php ENDPATH**/ ?>