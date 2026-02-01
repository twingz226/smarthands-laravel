<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title>Create Account - Smarthands Cleaning Services</title>
    <link rel="icon" href="<?php echo e(asset('images/Smarthands.png')); ?>" />
    
    <!-- Fonts & Icons -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    
    <!-- reCAPTCHA v2 -->
    <?php if(config('services.recaptcha.key')): ?>
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    <?php endif; ?>
    
    <style>
        :root {
            --primary: #ff9f1c;
            --primary-hover: #e68a00;
            --secondary: #2ec4b6;
            --dark: #2b2d42;
            --light: #f8f9fa;
            --gray: #6c757d;
            --light-gray: #e9ecef;
            --border-radius: 8px;
            --box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            --transition: all 0.3s ease;
        }
        
        body {
            font-family: 'Poppins', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            background-color: #f8f9fa;
            color: #333;
            line-height: 1.6;
        }
        
        .auth-container {
            min-height: 100vh;
            display: flex;
            align-items: center;
            padding: 2rem 1rem;
        }
        
        .auth-card {
            width: 100%;
            max-width: 480px;
            margin: 0 auto;
            background: white;
            border-radius: var(--border-radius);
            box-shadow: var(--box-shadow);
            overflow: hidden;
            transition: var(--transition);
        }
        
        .auth-header {
            padding: 2rem 2rem 1.5rem;
            text-align: center;
            background: linear-gradient(135deg, var(--primary), #ffbf69);
            color: white;
        }
        
        .auth-logo {
            width: 80px;
            height: auto;
            margin-bottom: 1rem;
        }
        
        .auth-title {
            font-size: 1.75rem;
            font-weight: 700;
            margin: 0 0 0.5rem;
        }
        
        .auth-subtitle {
            font-size: 1rem;
            opacity: 0.9;
            margin: 0;
        }
        
        .auth-body {
            padding: 2rem;
        }
        
        .form-group {
            margin-bottom: 1.5rem;
            position: relative;
        }
        
        .form-label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 500;
            color: var(--dark);
        }
        
        .form-control {
            width: 100%;
            padding: 0.75rem 1rem;
            font-size: 1rem;
            line-height: 1.5;
            color: #495057;
            background-color: #fff;
            background-clip: padding-box;
            border: 1px solid #ced4da;
            border-radius: var(--border-radius);
            transition: var(--transition);
        }
        
        .form-control:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 0.2rem rgba(255, 159, 28, 0.25);
        }

        .form-control:focus + .form-label,
        .form-control:not(:placeholder-shown) + .form-label {
            transform: translateY(-1.5rem) scale(0.85);
            color: #667eea;
        }

        .password-toggle {
            color: var(--text-muted);
            transition: var(--transition);
            padding: 0.25rem;
        }
        
        .password-toggle:hover {
            color: var(--text-dark);
        }

        .password-toggle:hover {
            color: var(--text-dark);
        }

        .auth-btn {
            width: 100%;
            padding: 1rem;
            border: none;
            border-radius: 2rem;
            background: var(--secondary-gradient);
            color: white;
            font-weight: 600;
            font-size: 1rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            cursor: pointer;
            transition: var(--transition);
        }

        .auth-btn:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-md);
        }

        .social-divider {
            position: relative;
            margin: 1.5rem 0;
            text-align: center;
        }

        .social-divider::before {
            content: "";
            position: absolute;
            top: 50%;
            left: 0;
            right: 0;
            height: 1px;
            background: var(--border-color);
            z-index: 1;
        }

        .social-divider-text {
            position: relative;
            display: inline-block;
            background: white;
            padding: 0 1rem;
            z-index: 2;
            color: var(--text-muted);
            font-size: 0.75rem;
        }

        .social-buttons {
            display: flex;
            justify-content: center;
            gap: 1rem;
            margin-bottom: 1.5rem;
        }

        .social-btn {
            width: 2.5rem;
            height: 2.5rem;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            border: 1px solid var(--border-color);
            color: var(--text-dark);
            transition: var(--transition);
        }

        .social-btn:hover {
            background: #f7fafc;
            transform: translateY(-2px);
        }

        .invalid-feedback {
            display: block;
            color: var(--error-color);
            font-size: 0.75rem;
            margin-top: 0.25rem;
            padding-left: 1.25rem;
        }

        /* Error States */
        .is-invalid {
            border-color: #dc3545;
            padding-right: calc(1.5em + 0.75rem);
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' fill='none' stroke='%23dc3545' viewBox='0 0 12 12'%3e%3ccircle cx='6' cy='6' r='4.5'/%3e%3cpath stroke-linejoin='round' d='M5.8 3.6h.4L6 6.5z'/%3e%3ccircle cx='6' cy='8.2' r='.6' fill='%23dc3545' stroke='none'/%3e%3c/svg%3e");
            background-repeat: no-repeat;
            background-position: right calc(0.375em + 0.1875rem) center;
            background-size: calc(0.75em + 0.375rem) calc(0.75em + 0.375rem);
        }
        
        .invalid-feedback {
            display: none;
            width: 100%;
            margin-top: 0.25rem;
            font-size: 0.875em;
            color: #dc3545;
        }
        
        .was-validated .form-control:invalid ~ .invalid-feedback,
        .was-validated .form-control:invalid ~ .invalid-tooltip,
        .form-control.is-invalid ~ .invalid-feedback,
        .form-control.is-invalid ~ .invalid-tooltip {
            display: block;
        }
        
        /* Password Strength Meter */
        .password-strength {
            height: 4px;
            background-color: #e9ecef;
            border-radius: 2px;
            margin-top: 0.5rem;
            overflow: hidden;
        }
        
        .password-strength-bar {
            height: 100%;
            width: 0;
            transition: width 0.3s ease;
        }
        
        .strength-weak { background-color: #dc3545; }
        .strength-medium { background-color: #ffc107; }
        .strength-strong { background-color: #28a745; }
        
        /* Toggle Password Visibility */
        .password-toggle {
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: var(--gray);
            cursor: pointer;
            padding: 0.25rem;
            border-radius: 50%;
            transition: var(--transition);
        }
        
        .password-toggle:hover {
            color: var(--dark);
            background-color: var(--light-gray);
        }
        
        /* Buttons */
        .btn-primary {
            background-color: var(--primary);
            border-color: var(--primary);
            color: white;
            font-weight: 500;
            padding: 0.75rem 1.5rem;
            border-radius: var(--border-radius);
            transition: var(--transition);
            width: 100%;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            font-size: 0.9rem;
        }
        
        .btn-primary:hover, .btn-primary:focus {
            background-color: var(--primary-hover);
            border-color: var(--primary-hover);
            box-shadow: 0 4px 12px rgba(255, 159, 28, 0.3);
            transform: translateY(-1px);
        }
        
        
        
        /* Divider */
        .divider {
            display: flex;
            align-items: center;
            text-align: center;
            margin: 1.5rem 0;
            color: var(--gray);
            font-size: 0.875rem;
        }
        
        .divider::before,
        .divider::after {
            content: '';
            flex: 1;
            border-bottom: 1px solid #dee2e6;
        }
        
        .divider:not(:empty)::before {
            margin-right: 1rem;
        }
        
        .divider:not(:empty)::after {
            margin-left: 1rem;
        }
        
        /* Links */
        .auth-link {
            color: var(--primary);
            text-decoration: none;
            font-weight: 500;
            transition: var(--transition);
        }
        
        .auth-link:hover {
            color: var(--primary-hover);
            text-decoration: underline;
        }
        
        /* Responsive Adjustments */
        @media (max-width: 576px) {
            .auth-container {
                padding: 1rem;
            }
            
            .auth-card {
                border-radius: var(--border-radius);
            }
            
            .auth-body {
                padding: 1.5rem;
            }
            
            .auth-title {
                font-size: 1.5rem;
            }
        }
        </style>
</head>
<body>
    <div class="auth-container">
        <div class="auth-card">
            <div class="auth-header">
                <img src="<?php echo e(asset('images/Smarthands.png')); ?>" alt="Smarthands Logo" class="auth-logo" onerror="this.src='https://via.placeholder.com/80?text=Smarthands'">
                <h1 class="auth-title">Create Your Account</h1>
                <p class="auth-subtitle">Join Smarthands today for a cleaner tomorrow</p>
            </div>

            <div class="auth-body">
                <form method="POST" action="<?php echo e(route('register.store')); ?>" class="needs-validation" novalidate>
                    <?php echo csrf_field(); ?>

                    <!-- Full Name -->
                    <div class="form-group">
                        <label for="name" class="form-label">Full Name</label>
                        <input id="name" type="text" 
                               class="form-control <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                               name="name" value="<?php echo e(old('name')); ?>"
                               required autocomplete="name" autofocus
                               placeholder="Enter your full name">
                        <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <div class="invalid-feedback">
                                <?php echo e($message); ?>

                            </div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <!-- Email -->
                    <div class="form-group">
                        <label for="email" class="form-label">Email Address</label>
                        <input id="email" type="email" 
                               class="form-control <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                               name="email" value="<?php echo e(old('email')); ?>"
                               required autocomplete="email"
                               placeholder="Enter your email address">
                        <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <div class="invalid-feedback">
                                <?php echo e($message); ?>

                            </div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <!-- Password -->
                    <div class="form-group">
                        <div class="d-flex justify-content-between">
                            <label for="password" class="form-label">Password</label>
                            <small class="text-muted">At least 8 characters</small>
                        </div>
                        <div style="position: relative; width: 100%;">
                            <input id="password" type="password" 
                                   class="form-control <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                   name="password" 
                                   required autocomplete="new-password"
                                   placeholder="Create a password"
                                   style="padding-right: 3rem;">
                            <button type="button" class="password-toggle" data-target="#password" style="position: absolute; right: 1.5rem; top: 50%; transform: translateY(-50%); background: none; border: none; cursor: pointer;">
                                <i class="bi bi-eye"></i>
                            </button>
                            <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="invalid-feedback">
                                    <?php echo e($message); ?>

                                </div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                        <div class="password-strength mt-2">
                            <div class="password-strength-bar"></div>
                        </div>
                        <small class="form-text mt-1 d-block" style="color: #dc3545; font-size: 0.75rem;">
                            💡 Tip: Create a strong password using letters, numbers, and symbols
                        </small>
                    </div>

                    <!-- Confirm Password -->
                    <div class="form-group">
                        <label for="password-confirm" class="form-label">Confirm Password</label>
                        <div style="position: relative; width: 100%;">
                            <input id="password-confirm" type="password" 
                                   class="form-control" 
                                   name="password_confirmation" 
                                   required 
                                   autocomplete="new-password"
                                   placeholder="Confirm your password"
                                   style="padding-right: 3rem;">
                            <button type="button" class="password-toggle" data-target="#password-confirm" style="position: absolute; right: 1.5rem; top: 50%; transform: translateY(-50%); background: none; border: none; cursor: pointer;">
                                <i class="bi bi-eye"></i>
                            </button>
                        </div>
                    </div>

                    <!-- reCAPTCHA v2 (only if configured) -->
                    <?php if(config('services.recaptcha.key')): ?>
                    <div class="form-group">
                        <div class="g-recaptcha" data-sitekey="<?php echo e(config('services.recaptcha.key')); ?>"></div>
                        <?php $__errorArgs = ['g-recaptcha-response'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <div class="invalid-feedback d-block">
                                <?php echo e($message); ?>

                            </div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                    <?php endif; ?>
                    <button type="submit" class="btn btn-primary btn-lg mb-4">
                        Create Account
                    </button>

                    <!-- Login Link -->
                    <p class="text-center mt-4 mb-0">
                        Already have an account? 
                        <a href="<?php echo e(route('login')); ?>" class="auth-link">Sign in</a>
                    </form>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        // Toggle password visibility
        document.querySelectorAll('.password-toggle').forEach(function(button) {
            button.addEventListener('click', function() {
                const targetId = this.getAttribute('data-target');
                const input = document.querySelector(targetId);
                const icon = this.querySelector('i');
                
                if (input && icon) {
                    if (input.type === 'password') {
                        input.type = 'text';
                        icon.classList.remove('bi-eye');
                        icon.classList.add('bi-eye-slash');
                    } else {
                        input.type = 'password';
                        icon.classList.remove('bi-eye-slash');
                        icon.classList.add('bi-eye');
                    }
                }
            });
        });

        // Password strength meter
        const passwordInput = document.getElementById('password');
        const strengthBar = document.querySelector('.password-strength-bar');
        
        if (passwordInput && strengthBar) {
            passwordInput.addEventListener('input', function() {
                const strength = calculatePasswordStrength(this.value);
                strengthBar.style.width = strength + '%';
                
                // Update strength bar color
                strengthBar.className = 'password-strength-bar';
                if (strength < 30) {
                    strengthBar.classList.add('strength-weak');
                } else if (strength < 70) {
                    strengthBar.classList.add('strength-medium');
                } else {
                    strengthBar.classList.add('strength-strong');
                }
                
                // Show/hide password strength meter
                const strengthMeter = this.closest('.form-group').querySelector('.password-strength');
                if (strengthMeter) {
                    strengthMeter.style.opacity = this.value ? '1' : '0';
                }
            });
        }
        
        function calculatePasswordStrength(password) {
            if (!password) return 0;
            
            let strength = 0;
            const length = password.length;
            
            // Length check
            if (length > 0) strength += 10;
            if (length >= 8) strength += 20;
            if (length >= 12) strength += 10;
            
            // Character type checks
            if (/[A-Z]/.test(password)) strength += 15; // Uppercase
            if (/[a-z]/.test(password)) strength += 15; // Lowercase
            if (/[0-9]/.test(password)) strength += 20;  // Numbers
            if (/[^A-Za-z0-9]/.test(password)) strength += 10; // Special chars (optional bonus)
            
            // Deduct points for common patterns
            if (/(.)\1{2,}/.test(password)) strength -= 15; // Repeated chars
            if (/^[0-9]+$/.test(password)) strength -= 10;  // Numbers only
            if (/^[a-zA-Z]+$/.test(password)) strength -= 10; // Letters only
            
            return Math.max(0, Math.min(100, strength));
        }
        
        // Form validation
        (function() {
            'use strict';
            
            // Fetch all the forms we want to apply custom Bootstrap validation styles to
            const forms = document.querySelectorAll('.needs-validation');
            
            // Loop over them and prevent submission
            Array.from(forms).forEach(function(form) {
                form.addEventListener('submit', function(event) {
                    // Check if reCAPTCHA is configured
                    const recaptchaSiteKey = '<?php echo e(config("services.recaptcha.key")); ?>';
                    
                    if (recaptchaSiteKey) {
                        // For reCAPTCHA v2, check if the response is filled
                        const recaptchaResponse = form.querySelector('[name="g-recaptcha-response"]');
                        if (recaptchaResponse && !recaptchaResponse.value) {
                            // reCAPTCHA not completed
                            event.preventDefault();
                            event.stopPropagation();
                            
                            // Show error message if not already shown
                            const existingError = form.querySelector('.g-recaptcha + .invalid-feedback');
                            if (!existingError) {
                                const errorDiv = document.createElement('div');
                                errorDiv.className = 'invalid-feedback d-block';
                                errorDiv.textContent = 'Please complete the reCAPTCHA verification.';
                                form.querySelector('.g-recaptcha').parentNode.appendChild(errorDiv);
                            }
                            
                            form.classList.add('was-validated');
                            return;
                        }
                    }
                    
                    // Check form validity
                    if (!form.checkValidity()) {
                        event.preventDefault();
                        event.stopPropagation();
                        form.classList.add('was-validated');
                        return;
                    }
                    
                    // Form is valid, allow submission
                }, false);
                
                // Real-time validation for password match
                const password = form.querySelector('#password');
                const confirmPassword = form.querySelector('#password-confirm');
                
                if (password && confirmPassword) {
                    const checkPasswordMatch = function() {
                        if (password.value !== confirmPassword.value) {
                            confirmPassword.setCustomValidity('Passwords do not match');
                        } else {
                            confirmPassword.setCustomValidity('');
                        }
                    };
                    
                    password.addEventListener('input', checkPasswordMatch);
                    confirmPassword.addEventListener('input', checkPasswordMatch);
                }
            });
        })();
    </script>
</body>
</html><?php /**PATH /opt/lampp/htdocs/cleaning_service_management_system/resources/views/auth/register.blade.php ENDPATH**/ ?>