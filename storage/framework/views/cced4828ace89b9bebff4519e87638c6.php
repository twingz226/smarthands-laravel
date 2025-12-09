<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <div class="profile-container">
        <div class="profile-header">
            <h1><i class="entypo-pencil"></i> Edit Profile</h1>
        </div>

        <?php if(session('success')): ?>
            <div class="alert-success">
                <?php echo e(session('success')); ?>

            </div>
        <?php endif; ?>

        <?php if($errors->any()): ?>
            <div class="alert alert-danger">
                <ul>
                    <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <li><?php echo e($error); ?></li>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </ul>
            </div>
        <?php endif; ?>

        <form action="<?php echo e(route('admin.profile.update')); ?>" method="POST" enctype="multipart/form-data" class="profile-form">
            <?php echo csrf_field(); ?>
            <?php echo method_field('PUT'); ?>

            <!-- Personal Information Section -->
            <div class="form-section">
                <h3 class="section-title" style="font-size: var(--text-2xl); margin: 2rem 0 1.5rem 0; display: flex; align-items: center; gap: 0.75rem; color: var(--text-primary);">
                    <i class="entypo-user" style="font-size: 1.5em; color: var(--primary-color);"></i>
                    <span>Personal Information</span>
                </h3>
                <div class="form-grid">
                    <div class="form-group">
                        <label for="name">Full Name <span class="text-danger">*</span></label>
                        <input type="text"
                               class="form-control <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                               id="name"
                               name="name"
                               value="<?php echo e(old('name', $user->name)); ?>"
                               required>
                        <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <span class="invalid-feedback" role="alert"><?php echo e($message); ?></span>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                </div>
            </div>

            <!-- Profile Photo Section -->
            <div class="form-section">
                <h3 class="section-title">
                    <i class="entypo-camera"></i>
                    Profile Photo
                </h3>
                <div class="file-upload">
                    <div class="current-photo">
                        <img src="<?php echo e($user->avatar_url); ?>"
                             alt="Current Profile Photo"
                             class="photo-preview">
                        <span class="text-sm text-gray-600">Current Photo</span>
                    </div>

                    <div class="file-input-wrapper" style="margin-top: 1rem;">
                        <label for="profile_photo" class="file-input-label" style="
                            display: inline-flex;
                            align-items: center;
                            justify-content: center;
                            padding: 1rem 1.75rem;
                            background-color: var(--primary-color);
                            color: white;
                            border-radius: 0.5rem;
                            font-size: 1.125rem;
                            font-weight: 500;
                            cursor: pointer;
                            transition: all 0.2s ease;
                            border: none;
                            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
                        ">
                            <i class='bx bx-upload' style="font-size: 1.25em; margin-right: 0.5rem;"></i>
                            <span>Choose New Photo</span>
                        </label>
                        <input type="file"
                               class="file-input <?php $__errorArgs = ['profile_photo'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                               style="display: none;"
                               id="profile_photo"
                               name="profile_photo"
                               accept="image/*">
                        <div id="selectedProfileFileName" class="file-name">No file chosen</div>
                        <p class="text-xs text-gray-500 mt-1">
                            Supported formats: JPEG, PNG, JPG, GIF. Maximum size: 2MB.
                        </p>
                        <?php $__errorArgs = ['profile_photo'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <span class="invalid-feedback" role="alert"><?php echo e($message); ?></span>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                </div>
            </div>

            <!-- Change Password Section -->
            <div class="form-section">
                <h3 class="section-title">
                    <i class="entypo-lock"></i>
                    Change Password
                    <span class="text-sm font-normal text-gray-500 ml-2">(Optional)</span>
                </h3>
                <p class="text-gray-600 mb-4">Leave blank if you don't want to change your password.</p>

                <div class="form-grid">
                    <div class="form-group">
                        <label for="current_password">Current Password</label>
                        <div class="password-group">
                            <input type="password"
                                   class="form-control <?php $__errorArgs = ['current_password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                   id="current_password"
                                   name="current_password"
                                   autocomplete="current-password">
                            <button type="button" class="password-toggle" data-target="current_password">
                                <i class='bx bx-hide'></i>
                            </button>
                            <?php $__errorArgs = ['current_password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <span class="invalid-feedback" role="alert"><?php echo e($message); ?></span>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="new_password">New Password</label>
                        <div class="password-group">
                            <input type="password"
                                   class="form-control <?php $__errorArgs = ['new_password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                   id="new_password"
                                   name="new_password"
                                   minlength="8"
                                   autocomplete="new-password">
                            <button type="button" class="password-toggle" data-target="new_password">
                                <i class='bx bx-hide'></i>
                            </button>
                            <?php $__errorArgs = ['new_password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <span class="invalid-feedback" role="alert"><?php echo e($message); ?></span>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                        <div class="text-xs text-gray-500 mt-1">Minimum 8 characters</div>
                    </div>

                    <div class="form-group">
                        <label for="new_password_confirmation">Confirm New Password</label>
                        <div class="password-group">
                            <input type="password"
                                   class="form-control"
                                   id="new_password_confirmation"
                                   name="new_password_confirmation"
                                   autocomplete="new-password">
                            <button type="button" class="password-toggle" data-target="new_password_confirmation">
                                <i class='bx bx-hide'></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Form Actions -->
            <div class="form-actions">
                <a href="<?php echo e(route('admin.profile.show')); ?>" class="btn btn-outline">
                    <i class='bx bx-x'></i> Cancel
                </a>
                <button type="submit" class="btn btn-primary">
                    <i class='bx bx-save'></i> Save Changes
                </button>
            </div>
        </form>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('styles'); ?>
<style>
:root {
    --primary-color: #4f46e5;
    --primary-hover: #4338ca;
    --text-primary: #1f2937;
    --text-secondary: #6b7280;
    --border-color: #e5e7eb;
    --bg-light: #f9fafb;
    --shadow-sm: 0 1px 2px 0 rgb(0 0 0 / 0.05);
    --shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1), 0 2px 4px -2px rgb(0 0 0 / 0.1);
    --radius: 0.5rem;
    --text-base: 1.125rem;      /* 18px */
    --text-lg: 1.25rem;        /* 20px */
    --text-xl: 1.5rem;         /* 24px */
    --text-2xl: 1.875rem;      /* 30px */
    --text-3xl: 2.25rem;       /* 36px */
    --text-4xl: 3rem;          /* 48px */
    --text-5xl: 3.75rem;       /* 60px */
    --line-height-normal: 1.5;
    --line-height-tight: 1.25;
}

/* Base Styles */
.profile-container {
    max-width: 1200px;
    margin: 2rem auto;
    padding: 0 1rem;
}

.profile-header {
    margin-bottom: 2rem;
    padding-bottom: 1rem;
    border-bottom: 1px solid var(--border-color);
}

.profile-header h1 {
    font-size: var(--text-3xl);
    font-weight: 700;
    color: var(--text-primary);
    display: flex;
    align-items: center;
    gap: 0.75rem;
    margin: 0 0 0.5rem 0;
    line-height: 1.2;
}

.profile-header h1 i {
    color: var(--primary-color);
}

/* Form Styles */
.profile-form {
    background: white;
    border-radius: var(--radius);
    box-shadow: var(--shadow-sm);
    overflow: hidden;
    border: 1px solid var(--border-color);
}

.form-section {
    padding: 2rem;
    border-bottom: 1px solid var(--border-color);
}

.form-section:last-child {
    border-bottom: none;
}

.section-title {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    font-size: var(--text-xl);
    font-weight: 600;
    color: var(--text-primary);
    margin-bottom: 1.5rem;
}

.section-title i {
    color: var(--primary-color);
}

/* Grid Layout */
.form-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
    gap: 1.5rem;
}

/* Form Controls */
.form-group {
    margin-bottom: 1.75rem;
}

.form-group label {
    display: block;
    font-weight: 500;
    color: var(--text-secondary);
    margin-bottom: 0.5rem;
    font-size: var(--text-xl);
    line-height: 1.4;
}

input[type="text"],
input[type="email"],
input[type="tel"],
textarea,
input[type="password"] {
    width: 100%;
    padding: 1rem 1.25rem;
    border: 1px solid var(--border-color);
    border-radius: 0.5rem;
    font-size: var(--text-lg);
    line-height: 1.5;
    transition: all 0.2s;
}

.form-control:focus {
    outline: none;
    border-color: var(--primary-color);
    box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.1);
}

textarea.form-control {
    min-height: 100px;
    resize: vertical;
}

/* File Upload */
.file-upload {
    display: flex;
    flex-direction: column;
    gap: 1.5rem;
}

.current-photo {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 0.75rem;
}

.photo-preview {
    width: 120px;
    height: 120px;
    border-radius: 50%;
    object-fit: cover;
    border: 3px solid white;
    box-shadow: var(--shadow);
}

.file-input-wrapper {
    position: relative;
    width: 100%;
}

.file-input-label {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    padding: 0.625rem 1.25rem;
    background-color: white;
    color: var(--text-primary);
    border: 1px solid var(--border-color);
    border-radius: 0.375rem;
    font-size: 0.875rem;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.2s;
}

.file-input-label:hover {
    background-color: var(--bg-light);
}

.file-input {
    position: absolute;
    width: 1px;
    height: 1px;
    padding: 0;
    margin: -1px;
    overflow: hidden;
    clip: rect(0, 0, 0, 0);
    border: 0;
}

.file-name {
    margin-top: 0.5rem;
    font-size: var(--text-base);
    color: var(--text-secondary);
}

/* Password Toggle */
.password-group {
    position: relative;
}

.password-toggle {
    position: absolute;
    right: 0.75rem;
    top: 50%;
    transform: translateY(-50%);
    color: var(--text-secondary);
    cursor: pointer;
    transition: color 0.2s;
    background: none;
    border: none;
    padding: 0.25rem;
    display: flex;
    align-items: center;
    justify-content: center;
}

.password-toggle:hover {
    color: var(--primary-color);
}

/* Buttons */
.form-actions {
    display: flex;
    justify-content: flex-end;
    gap: 1rem;
    padding: 1.5rem 2rem;
    background-color: var(--bg-light);
    border-top: 1px solid var(--border-color);
}

.btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    padding: 0.875rem 1.5rem;
    border-radius: 0.5rem;
    font-weight: 500;
    font-size: var(--text-base);
    cursor: pointer;
    transition: all 0.2s;
    gap: 0.5rem;
    border: 1px solid transparent;
}

.btn-primary {
    background-color: var(--primary-color);
    color: white;
    border: none;
}

.btn-primary:hover {
    background-color: var(--primary-hover);
}

.btn-outline {
    background-color: white;
    color: var(--text-primary);
    border-color: var(--border-color);
}

.btn-outline:hover {
    background-color: var(--bg-light);
}

/* Error States */
.is-invalid {
    border-color: #ef4444 !important;
}

.invalid-feedback {
    margin-top: 0.5rem;
    font-size: var(--text-base);
    color: #ef4444;
}

/* Success Message */
.alert-success {
    padding: 1rem;
    margin-bottom: 1.5rem;
    border-radius: 0.375rem;
    background-color: #ecfdf5;
    color: #047857;
    border: 1px solid #a7f3d0;
}

/* Responsive Adjustments */
@media (max-width: 768px) {
    .form-grid {
        grid-template-columns: 1fr;
    }

    .file-upload {
        flex-direction: column;
        align-items: flex-start;
    }

    .current-photo {
        margin-bottom: 1.5rem;
    }

    .profile-header h1 {
        font-size: var(--text-2xl);
    }

    .section-title {
        font-size: var(--text-lg);
    }
}

/* Smooth transitions for better UX */
.form-control, .btn, .file-input-label {
    transition: all 0.2s ease-in-out;
}

/* Focus styles for better accessibility */
.form-control:focus, .btn:focus, .file-input:focus + .file-input-label {
    outline: none;
    box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.3);
}

/* Loading state for submit button */
.btn.is-loading {
    position: relative;
    color: transparent;
    pointer-events: none;
}

.btn.is-loading:after {
    content: '';
    position: absolute;
    width: 16px;
    height: 16px;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    margin: auto;
    border: 2px solid transparent;
    border-top-color: currentColor;
    border-radius: 50%;
    animation: button-loading-spinner 0.7s ease infinite;
}

@keyframes button-loading-spinner {
    from { transform: rotate(0turn); }
    to { transform: rotate(1turn); }
}

/* Additional styles for better mobile experience */
@media (max-width: 768px) {
    :root {
        --text-base: 1.125rem;  /* 18px */
        --text-lg: 1.25rem;    /* 20px */
        --text-xl: 1.5rem;     /* 24px */
        --text-2xl: 1.75rem;   /* 28px */
        --text-3xl: 2.25rem;   /* 36px */
        --text-4xl: 2.5rem;    /* 40px */
        --text-5xl: 3rem;      /* 48px */
    }

    .form-grid {
        grid-template-columns: 1fr;
    }

    .file-upload {
        flex-direction: column;
        align-items: flex-start;
    }

    .current-photo {
        margin-bottom: 1.5rem;
    }

    .profile-header h1 {
        font-size: var(--text-2xl);
    }

    .section-title {
        font-size: var(--text-lg);
    }
}
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Password toggle functionality
    document.querySelectorAll('.password-toggle').forEach(button => {
        button.addEventListener('click', function() {
            const targetId = this.getAttribute('data-target');
            const passwordField = document.getElementById(targetId);
            const icon = this.querySelector('i');

            // Toggle password visibility
            const isPassword = passwordField.type === 'password';
            passwordField.type = isPassword ? 'text' : 'password';

            // Toggle icon
            if (isPassword) {
                icon.classList.remove('bx-hide');
                icon.classList.add('bx-show');
                this.setAttribute('title', 'Hide password');
            } else {
                icon.classList.remove('bx-show');
                icon.classList.add('bx-hide');
                this.setAttribute('title', 'Show password');
            }
        });
    });

    // File upload handling
    const fileInput = document.getElementById('profile_photo');
    const fileNameSpan = document.getElementById('selectedProfileFileName');

    if (fileInput) {
        fileInput.addEventListener('change', function() {
            if (this.files && this.files.length > 0) {
                const fileName = this.files[0].name;
                fileNameSpan.textContent = fileName;

                // Preview the image if it's an image file
                if (this.files[0].type.startsWith('image/')) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        const preview = document.querySelector('.photo-preview');
                        if (preview) {
                            preview.src = e.target.result;
                        }
                    };
                    reader.readAsDataURL(this.files[0]);
                }
            } else {
                fileNameSpan.textContent = 'No file chosen';
            }
        });
    }

    // Form validation
    const form = document.querySelector('.profile-form');
    if (form) {
        form.addEventListener('submit', function(e) {
            const newPassword = document.getElementById('new_password').value;
            const confirmPassword = document.getElementById('new_password_confirmation').value;
            const currentPassword = document.getElementById('current_password').value;

            // Only validate passwords if at least one password field has a value
            if (newPassword || confirmPassword || currentPassword) {
                // If any password field is empty when at least one has a value
                if (!currentPassword) {
                    e.preventDefault();
                    alert('Please enter your current password to make changes.');
                    document.getElementById('current_password').focus();
                    return false;
                }

                // If trying to set a new password
                if (newPassword || confirmPassword) {
                    if (!newPassword) {
                        e.preventDefault();
                        alert('Please enter a new password.');
                        document.getElementById('new_password').focus();
                        return false;
                    }

                    if (!confirmPassword) {
                        e.preventDefault();
                        alert('Please confirm your new password.');
                        document.getElementById('new_password_confirmation').focus();
                        return false;
                    }

                    // Check if new passwords match
                    if (newPassword !== confirmPassword) {
                        e.preventDefault();
                        alert('New password and confirm password do not match.');
                        return false;
                    }

                    // Check password strength
                    if (newPassword.length < 8) {
                        e.preventDefault();
                        alert('Password must be at least 8 characters long.');
                        return false;
                    }
                }
            }

            // If we get here, form is valid
            return true;
        });
    }
});
</script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /opt/lampp/htdocs/cleaning_service_management_system/resources/views/admin/profile/edit.blade.php ENDPATH**/ ?>