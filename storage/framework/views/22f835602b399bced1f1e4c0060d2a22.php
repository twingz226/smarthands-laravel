<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Cookie Policy - Smarthands</title>
  <link rel="icon" href="<?php echo e(asset('images/Smarthands.png')); ?>" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
  <link rel="stylesheet" href="/css/modal-custom.css">
  <!-- Fonts -->
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  <style>
    :root { --bs-font-sans: 'Poppins', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif; }
    body { font-family: 'Poppins', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif; }
    .custom-navbar { padding-top: .2rem; padding-bottom: .2rem; min-height: 50px; background-color: #ff9f1c !important; }
    .navbar-brand img { height: 60px; width: auto; border-radius: 80%; }
    .nav-link { font-weight: 700; color: #000000 !important; padding: .5rem 1rem !important; font-size: 1rem; text-transform: uppercase; letter-spacing: .5px; }
    .nav-link:hover { color: #ffffff !important; transform: translateY(-2px); }
    .nav-link.active { color: #ffffff !important; text-decoration: underline; text-underline-offset: 5px; }
    .content-section { padding: 60px 5%; background-color: #cbf3f0; }
    .hero-section { min-height: 30vh; display:flex; align-items:center; justify-content:center; padding: 60px 5% 40px; background-color:#2ec4b6; text-align:center; }
    .main-headline { font-size: 2.5rem; font-weight: 700; }
    .policy-card { background-color: rgba(254, 254, 254, 0.95); border-radius: 10px; padding: 30px; box-shadow: 0 8px 20px rgba(0,0,0,.06); }
    .policy-card h3 { font-weight: 600; }
  </style>
</head>
<body>
  <nav class="navbar navbar-expand-lg navbar-light custom-navbar shadow-sm sticky-top">
    <div class="container">
<?php
    use App\Models\Setting;
    $companyLogo = Setting::getValue('company_logo');
?>
      <a class="navbar-brand" href="<?php echo e(route('home')); ?>">
        <img src="<?php echo e($companyLogo ? asset('storage/' . $companyLogo) : asset('images/Smarthands.png')); ?>" alt="Logo" onerror="this.src='https://via.placeholder.com/100'">
      </a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav ms-auto">
          <li class="nav-item"><a class="nav-link <?php if(Route::currentRouteName() == 'home'): ?> active <?php endif; ?>" href="<?php echo e(route('home')); ?>">Home</a></li>
          <li class="nav-item"><a class="nav-link <?php if(Route::currentRouteName() == 'services'): ?> active <?php endif; ?>" href="<?php echo e(route('services')); ?>">Services</a></li>
          <li class="nav-item"><a class="nav-link <?php if(Route::currentRouteName() == 'home'): ?> <?php endif; ?>" href="<?php echo e(route('home')); ?>#ratings">Ratings</a></li>
          <li class="nav-item"><a class="nav-link <?php if(Route::currentRouteName() == 'about'): ?> active <?php endif; ?>" href="<?php echo e(route('about')); ?>">About Us</a></li>
          <li class="nav-item"><a class="nav-link <?php if(Route::currentRouteName() == 'contact'): ?> active <?php endif; ?>" href="<?php echo e(route('contact')); ?>">Contact</a></li>
          <?php if(Auth::check()): ?>
            <li class="nav-item">
              <a class="nav-link d-flex align-items-center" href="#" data-bs-toggle="modal" data-bs-target="#myBookingsModal" title="View and manage your bookings" aria-label="My Bookings">
                <i class="bi bi-journal-check me-1" aria-hidden="true"></i>
                <span>My Bookings</span>
              </a>
            </li>
            <li class="nav-item dropdown">
              <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false" title="Account">
                <i class="bi bi-person-circle me-1"></i>
                <span>Account</span>
              </a>
              <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                <li><a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#profileModal"><i class="bi bi-person me-2"></i>Profile</a></li>
                <li><hr class="dropdown-divider"></li>
                <li><a class="dropdown-item text-danger" href="#" data-bs-toggle="modal" data-bs-target="#logoutConfirmModal"><i class="bi bi-box-arrow-right me-2"></i>Logout</a></li>
              </ul>
            </li>
          <?php else: ?>
            <li class="nav-item dropdown">
              <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" id="guestProfileDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false" title="Account">
                <i class="bi bi-person-circle fs-4" aria-hidden="true"></i>
                <span class="visually-hidden">Account</span>
              </a>
              <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="guestProfileDropdown">
                <li><a class="dropdown-item" href="<?php echo e(route('login')); ?>" target="_blank" rel="noopener noreferrer">Login</a></li>
                <li><a class="dropdown-item" href="<?php echo e(route('register')); ?>" target="_blank" rel="noopener noreferrer">Register</a></li>
              </ul>
            </li>
          <?php endif; ?>
        </ul>
      </div>
    </div>
  </nav>

  <section class="hero-section">
    <h1 class="main-headline">Cookie Policy</h1>
  </section>

  <section class="content-section">
    <div class="container">
      <div class="policy-card">
        <p>Last updated: <?php echo e(date('F d, Y')); ?></p>
        <h3>1. What Are Cookies?</h3>
        <p>Cookies are small text files stored on your device to help websites function and improve your experience.</p>
        <h3>2. How We Use Cookies</h3>
        <ul>
          <li>Essential cookies to enable core functionality (e.g., session, security).</li>
          <li>Performance cookies to understand how our site is used and improve it.</li>
          <li>Preference cookies to remember your settings.</li>
        </ul>
        <h3>3. Managing Cookies</h3>
        <p>You can control cookies through your browser settings. Disabling certain cookies may affect site functionality.</p>
        <h3>4. Third-Party Cookies</h3>
        <p>Some cookies may be set by third-party services integrated into our site (e.g., analytics). We do not control these cookies directly.</p>
        <h3>5. Contact</h3>
        <p>If you have questions about our Cookie Policy, contact us at <?php echo e($contactInfo->email ?? 'smarthandsbcd@gmail.com'); ?>.</p>
      </div>
    </div>
  </section>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <?php if (isset($component)) { $__componentOriginal8a8716efb3c62a45938aca52e78e0322 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal8a8716efb3c62a45938aca52e78e0322 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.footer','data' => ['contactInfo' => $contactInfo]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('footer'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['contactInfo' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($contactInfo)]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal8a8716efb3c62a45938aca52e78e0322)): ?>
<?php $attributes = $__attributesOriginal8a8716efb3c62a45938aca52e78e0322; ?>
<?php unset($__attributesOriginal8a8716efb3c62a45938aca52e78e0322); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal8a8716efb3c62a45938aca52e78e0322)): ?>
<?php $component = $__componentOriginal8a8716efb3c62a45938aca52e78e0322; ?>
<?php unset($__componentOriginal8a8716efb3c62a45938aca52e78e0322); ?>
<?php endif; ?>

  <?php if(Auth::check()): ?>
  <div class="modal fade" id="profileModal" tabindex="-1" aria-labelledby="profileModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header custom-orange">
          <h5 class="modal-title" id="profileModalLabel">Your Profile</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <form id="profileForm" method="POST" action="<?php echo e(url('/customer/profile')); ?>">
          <?php echo csrf_field(); ?>
          <div id="profileSuccessAlert" class="alert alert-success d-none" role="alert"></div>
          <div class="modal-body">
              <div class="mb-3">
                  <label for="name" class="form-label">Name</label>
                  <input type="text" class="form-control" id="cookies_name" name="name" value="<?php echo e(Auth::user()->name ?? ''); ?>">
                  <div class="invalid-feedback" id="error-name"></div>
              </div>
              <div class="mb-3">
                  <label for="email" class="form-label">Email</label>
                  <input type="email" class="form-control" id="cookies_email" name="email" value="<?php echo e(Auth::user()->email ?? ''); ?>">
                  <div class="invalid-feedback" id="error-email"></div>
              </div>
              <hr>
              <h6 class="mb-3">Change Password</h6>
              <div class="mb-3">
                  <label for="current_password" class="form-label">Current Password</label>
                  <div class="input-group">
                      <input type="password" class="form-control" id="current_password" name="current_password" autocomplete="current-password">
                      <button class="btn btn-outline-secondary toggle-password" type="button" data-target="#current_password" tabindex="-1">
                        <i class="bi bi-eye" id="icon-current_password"></i>
                      </button>
                  </div>
                  <div class="invalid-feedback" id="error-current_password"></div>
              </div>
              <div class="mb-3">
                  <label for="new_password" class="form-label">New Password</label>
                  <div class="input-group">
                      <input type="password" class="form-control" id="new_password" name="new_password" autocomplete="new-password">
                      <button class="btn btn-outline-secondary toggle-password" type="button" data-target="#new_password" tabindex="-1">
                        <i class="bi bi-eye" id="icon-new_password"></i>
                      </button>
                  </div>
                  <div class="invalid-feedback" id="error-new_password"></div>
              </div>
              <div class="mb-3">
                  <label for="new_password_confirmation" class="form-label">Confirm New Password</label>
                  <div class="input-group">
                      <input type="password" class="form-control" id="new_password_confirmation" name="new_password_confirmation" autocomplete="new-password">
                      <button class="btn btn-outline-secondary toggle-password" type="button" data-target="#new_password_confirmation" tabindex="-1">
                        <i class="bi bi-eye" id="icon-new_password_confirmation"></i>
                      </button>
                  </div>
                  <div class="invalid-feedback" id="error-new_password_confirmation"></div>
              </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            <button type="submit" class="btn" style="background-color: white; color: #ff9f1c; border: 1px solid #ff9f1c; transition: all 0.3s ease; font-weight: 600;" onmouseover="this.style.backgroundColor='#ff9f1c'; this.style.color='white'; this.style.borderColor='#ff9f1c'" onmouseout="this.style.backgroundColor='white'; this.style.color='#ff9f1c'; this.style.borderColor='#ff9f1c'">Save Changes</button>
          </div>
        </form>
      </div>
    </div>
  </div>
  <?php endif; ?>

  <script>
  document.addEventListener('DOMContentLoaded', function () {
      const profileForm = document.getElementById('profileForm');
      const profileModal = document.getElementById('profileModal');
      const successAlert = document.getElementById('profileSuccessAlert');

      if (profileForm) {
          profileForm.addEventListener('submit', function (e) {
              e.preventDefault();
              successAlert.classList.add('d-none');
              ['name','email','current_password','new_password','new_password_confirmation'].forEach(function(field) {
                  const el = document.getElementById(field);
                  const err = document.getElementById('error-' + field);
                  if (el) el.classList.remove('is-invalid');
                  if (err) err.innerText = '';
              });
              const formData = new FormData(profileForm);
              fetch(profileForm.action, {
                  method: 'POST',
                  headers: {
                      'X-Requested-With': 'XMLHttpRequest',
                      'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '<?php echo e(csrf_token()); ?>'
                  },
                  body: formData
              })
              .then(response => response.json())
              .then(data => {
                  if (data.success) {
                      successAlert.textContent = data.message;
                      successAlert.classList.remove('d-none');
                      setTimeout(function() {
                          const modalInstance = bootstrap.Modal.getInstance(profileModal);
                          modalInstance?.hide();
                          successAlert.classList.add('d-none');
                          window.location.href = '/';
                      }, 1200);
                  } else if (data.errors) {
                      Object.keys(data.errors).forEach(function(field) {
                          const el = document.getElementById(field);
                          const err = document.getElementById('error-' + field);
                          if (el) el.classList.add('is-invalid');
                          if (err) err.innerText = data.errors[field][0];
                      });
                  }
              })
              .catch(() => {});
          });
      }

      document.querySelectorAll('.toggle-password').forEach(function(btn) {
          btn.addEventListener('click', function() {
              const input = document.querySelector(this.getAttribute('data-target'));
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
  });
  </script>

  <?php echo $__env->make('partials.my_bookings_modal', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

  <!-- Logout Form for all pages -->
  <form id="logout-form" action="<?php echo e(route('logout')); ?>" method="POST" style="display: none;">
      <?php echo csrf_field(); ?>
  </form>
  
  <!-- Logout Confirmation Modal -->
  <div class="modal fade" id="logoutConfirmModal" tabindex="-1" aria-labelledby="logoutConfirmModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header" style="background-color: #ff9f1c;">
          <h5 class="modal-title fw-bold" id="logoutConfirmModalLabel">Confirm Logout</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div class="d-flex align-items-start">
            <i class="bi bi-box-arrow-right text-warning me-3" style="font-size: 2rem;"></i>
            <div>
              <p class="mb-1">Are you sure you want to log out?</p>
              <small class="text-muted">You can always log back in to manage your bookings and profile.</small>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
          <button type="button" id="confirmLogoutBtn" class="btn btn-success">Yes, Logout</button>
        </div>
      </div>
    </div>
  </div>

  <script>
    document.addEventListener('DOMContentLoaded', function () {
      const confirmBtn = document.getElementById('confirmLogoutBtn');
      if (confirmBtn) {
        confirmBtn.addEventListener('click', function () {
          const form = document.getElementById('logout-form');
          if (form) form.submit();
        });
      }
    });
  </script>
</body>
</html>
<?php /**PATH /opt/lampp/htdocs/cleaning_service_management_system/resources/views/pages/cookies.blade.php ENDPATH**/ ?>