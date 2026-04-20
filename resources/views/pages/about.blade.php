<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="description" content="Learn about Smarthands Cleaning Services — your trusted professional cleaning partner in Bacolod City. Discover our mission, vision, and commitment to spotless results.">
  <meta name="csrf-token" content="{{ csrf_token() }}">

  <!-- Open Graph / Facebook -->
  <meta property="og:type" content="website">
  <meta property="og:url" content="{{ url()->current() }}">
  <meta property="og:title" content="About Us | Smarthands Cleaning Services">
  <meta property="og:description" content="Learn about Smarthands Cleaning Services — your trusted professional cleaning partner in Bacolod City.">
  <meta property="og:image" content="{{ asset('images/og-image.jpg') }}">

  <!-- Twitter -->
  <meta name="twitter:card" content="summary_large_image">
  <meta name="twitter:title" content="About Us | Smarthands Cleaning Services">
  <meta name="twitter:description" content="Learn about Smarthands Cleaning Services — your trusted professional cleaning partner in Bacolod City.">
  <meta name="twitter:image" content="{{ asset('images/og-image.jpg') }}">

  <!-- Canonical URL -->
  <link rel="canonical" href="{{ url()->current() }}" />

  <title>About Us | Smarthands Cleaning Services in Bacolod</title>
  <link rel="icon" href="{{ asset('images/Smarthands.png') }}" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
<link rel="stylesheet" href="/css/modal-custom.css">
  <!-- Fonts -->
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  <style>
    :root {
      --bs-font-sans: 'Poppins', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
    }
    body {
      font-family: 'Poppins', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
    }
    /* Navbar styles */
    .custom-navbar {
      padding-top: 0.2rem;
      padding-bottom: 0.2rem;
      min-height: 40px;
      background-color: #ff9f1c !important;
    }
    .navbar-brand img, .logo img {
      height: 60px;
      width: auto;
      border-radius: 80%;
    }
    .nav-link {
      font-weight: 700;
      color: #000000 !important;
      padding: 0.5rem 1rem !important;
      font-size: 1rem;
      text-transform: uppercase;
      letter-spacing: 0.5px;
    }
    .nav-link:hover {
      color: #ffffff !important;
      transform: translateY(-2px);
    }
    .nav-link.active {
      color: #ffffff !important;
      text-decoration: underline;
      text-underline-offset: 5px;
    }
    .navbar-nav {
      align-items: center;
    }
    .logo {
      text-align: center;
      margin: 1rem 0;
    }

    /* About Section */
    .about-section {
      padding: 60px 5%;
      background-color: #cbf3f0;
    }
    .section-title {
      font-size: 2.5rem;
      font-weight: 700;
      color: #000;
      text-align: center;
      margin-bottom: 40px;
    }
    .info-card {
      background-color: rgba(254, 254, 254, 0.85);
      border-radius: 10px;
      padding: 30px;
      margin-bottom: 30px;
      box-shadow: 0 8px 20px rgba(0, 0, 0, 0.06);
    }
    .info-item {
      margin-bottom: 20px;
      font-size: 1.1rem;
    }
    .info-item strong {
      color: #111c5d;
      display: block;
      margin-bottom: 5px;
    }

    /* Hero Section */
    .hero-section {
      height: auto;
      min-height: 30vh;
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      padding: 60px 5% 40px;
      position: relative;
      z-index: 1;
      background-color: #2ec4b6;
      text-align: center;
    }
    .main-headline {
      font-size: 2.5rem;
      font-weight: 700;
      line-height: 1.1;
      margin-bottom: 1.5rem;
    }
    .tagline {
      font-size: 1.2rem;
      color: #000000;
      line-height: 1.4;
      margin-bottom: 2rem;
    }

    /* Responsive Styles */
    @media (min-width: 768px) {
      .section-title {
        font-size: 2.5rem;
        margin-bottom: 60px;
      }
      .main-headline {
        font-size: 3rem;
      }
      .tagline {
        font-size: 1.3rem;
      }
      .info-card {
        padding: 40px;
      }
    }

    @media (min-width: 1200px) {
      .main-headline {
        font-size: 3.5rem;
      }
      .tagline {
        font-size: 1.5rem;
      }
    }

    /* Floating Book Now Button */
    @keyframes floatingButton {
      0% { transform: translateY(0); }
      50% { transform: translateY(-10px); }
      100% { transform: translateY(0); }
    }

    .floating-book-btn {
      position: fixed;
      bottom: 30px;
      right: 30px;
      background: linear-gradient(135deg, #ff9f1c, #ff6b35);
      color: #fff;
      padding: 14px 24px;
      border-radius: 50px;
      text-decoration: none;
      font-weight: 600;
      font-size: 1rem;
      display: flex;
      align-items: center;
      gap: 8px;
      box-shadow: 0 6px 20px rgba(255, 159, 28, 0.4);
      transition: all 0.3s ease;
      z-index: 1050;
      animation: floatingButton 3s ease-in-out infinite;
    }
    .floating-book-btn:hover {
      background: linear-gradient(135deg, #ff6b35, #ff9f1c);
      color: #fff;
      animation-play-state: paused;
      transform: translateY(-3px) scale(1.02);
      box-shadow: 0 10px 30px rgba(255, 159, 28, 0.5);
    }
    @media (max-width: 768px) {
      .floating-book-btn span { display: none; }
      .floating-book-btn { padding: 14px; border-radius: 50%; }
      .floating-book-btn i { font-size: 1.3rem; }
    }
  </style>
</head>
<body>
  <!-- Skip to main content link for screen readers -->
  <a href="#main-content" class="visually-hidden-focusable position-absolute top-0 start-0 p-2 bg-light text-dark" style="z-index: 9999;">
    Skip to main content
  </a>
  <!-- Navbar -->
  <nav class="navbar navbar-expand-lg navbar-light custom-navbar shadow-sm sticky-top">
    <div class="container">
      <a class="navbar-brand" href="{{ route('home') }}">
        @php
            use App\Models\Setting;
            $companyLogo = Setting::getValue('company_logo');
        @endphp
        <img src="{{ $companyLogo ? asset('storage/' . $companyLogo) : asset('images/Smarthands.png') }}" alt="Smarthands Cleaning Services Logo" width="200" height="60" loading="lazy" onerror="this.src='https://via.placeholder.com/100'" class="img-fluid">
      </a>
      <button class="navbar-toggler position-relative" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
        <span class="navbar-toggler-icon"></span>
        @if(Auth::check() && Auth::user()->bookings)
          @php
            $pendingConfirmations = Auth::user()->bookings->where('status', 'pending')->where('customer_confirmed', false)->count();
          @endphp
          @if($pendingConfirmations > 0)
            <span class="position-absolute top-0 start-100 translate-middle p-2 bg-danger border border-light rounded-circle" style="animation: pulseBadge 2s infinite; margin-left: -5px; margin-top: 5px;">
              <span class="visually-hidden">pending confirmations</span>
            </span>
          @endif
        @endif
      </button>
      <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav ms-auto">
          <li class="nav-item"><a class="nav-link @if(Route::currentRouteName() == 'home') active @endif" href="{{ route('home') }}">Home</a></li>
          <li class="nav-item"><a class="nav-link @if(Route::currentRouteName() == 'services') active @endif" href="{{ route('services') }}">Services</a></li>
          <li class="nav-item"><a class="nav-link @if(Route::currentRouteName() == 'home') @endif" href="{{ route('home') }}#ratings">Ratings</a></li>
          <li class="nav-item"><a class="nav-link @if(Route::currentRouteName() == 'about') active @endif" href="{{ route('about') }}">About Us</a></li>
          <li class="nav-item"><a class="nav-link @if(Route::currentRouteName() == 'contact') active @endif" href="{{ route('contact') }}">Contact</a></li>
          @if(Auth::check())
            <li class="nav-item">
  <a class="nav-link d-flex align-items-center" href="#" data-bs-toggle="modal" data-bs-target="#myBookingsModal" title="View and manage your bookings" aria-label="My Bookings">
    <i class="bi bi-journal-check me-1" aria-hidden="true"></i>
    <span class="position-relative" style="padding-right: 8px;">
      My Bookings
      @php
        $pendingConfirmations = Auth::user()->bookings->where('status', 'pending')->where('customer_confirmed', false)->count();
      @endphp
      @if($pendingConfirmations > 0)
        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" style="font-size: 0.65rem; padding: 0.25em 0.5em; animation: pulseBadge 2s infinite; box-shadow: 0 0 0 0 rgba(220, 53, 69, 0.7); margin-top: 5px; margin-left: -2px;">
          {{ $pendingConfirmations }}
          <span class="visually-hidden">pending confirmations</span>
        </span>
        <style>
          @keyframes pulseBadge {
            0% { transform: translate(-50%, -50%) scale(0.95); box-shadow: 0 0 0 0 rgba(220, 53, 69, 0.7); }
            70% { transform: translate(-50%, -50%) scale(1); box-shadow: 0 0 0 6px rgba(220, 53, 69, 0); }
            100% { transform: translate(-50%, -50%) scale(0.95); box-shadow: 0 0 0 0 rgba(220, 53, 69, 0); }
          }
        </style>
      @endif
    </span>
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
          @else
            <li class="nav-item dropdown">
              <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" id="guestProfileDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false" title="Account">
                <i class="bi bi-person-circle fs-4" aria-hidden="true"></i>
                <span class="visually-hidden">Account</span>
              </a>
              <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="guestProfileDropdown">
                <li><a class="dropdown-item" href="{{ route('login') }}" target="_blank" rel="noopener noreferrer">Login</a></li>
                <li><a class="dropdown-item" href="{{ route('register') }}" target="_blank" rel="noopener noreferrer">Register</a></li>
              </ul>
            </li>
          @endif
        </ul>
      </div>
    </div>
  </nav>


  <main id="main-content" tabindex="-1">
  <!-- Hero Section -->
  <section class="hero-section">
    <h1 class="main-headline">About Smarthands</h1>
    <p class="tagline">Professional Cleaning Services in Bacolod City</p>
  </section>

  <!-- About Content -->
  <section class="about-section">
    
    
    <div class="container">
      <div class="row justify-content-center">
        <div class="col-lg-8">
          <div class="info-card mb-4">
            <div class="about-content">
              {{ $contactInfo->about_content ?? 'Welcome to Smarthands Cleaning Services, your trusted partner in professional cleaning solutions.' }}
            </div>
          </div>

          <div class="info-card mb-4">
            <h3 class="mb-4">Our Mission</h3>
            <div class="mission-content">
              {{ $contactInfo->mission ?? 'To provide exceptional cleaning services that enhance the quality of life for our clients while maintaining the highest standards of professionalism and customer satisfaction.' }}
            </div>
          </div>

          <div class="info-card mb-4">
            <h3 class="mb-4">Our Vision</h3>
            <div class="vision-content">
              {{ $contactInfo->vision ?? 'To be the leading cleaning service provider in Bacolod City, known for our reliability, quality, and commitment to excellence.' }}
            </div>
          </div>

          <div class="info-card mb-4">
            <h3 class="mb-4">Services We Offer</h3>
            <div class="services-content">
              {{ $contactInfo->services_offered ?? 'We offer a comprehensive range of cleaning services including residential cleaning, commercial cleaning, deep cleaning, and specialized cleaning solutions.' }}
            </div>
          </div>

          <div class="info-card">
            <h3 class="mb-4">Contact Information</h3>
            <div class="info-item">
              <strong>Address:</strong>
              {{ $contactInfo->address ?? 'Site 3 Blk 3 Lot 33, Brgy. 13 Villa Victorias, Victorias City, Negros Occidental, Philippines' }}
            </div>
            
            <div class="info-item">
              <strong>Service Area:</strong>
              {{ $contactInfo->service_area ?? 'Silay City, Philippines · Talisay, Philippines · Bacolod City, Philippines' }}
            </div>
            
            <div class="info-item">
              <strong>Mobile:</strong>
              {{ $contactInfo->phone ?? '0953 957 4130' }}
            </div>
            
            <div class="info-item">
              <strong>Email:</strong>
              {{ $contactInfo->email ?? 'smarthandsbcd@gmail.com' }}
            </div>
            
            <div class="info-item">
              <strong>Hours:</strong>
              {{ $contactInfo->business_hours ?? 'Always open' }}
            </div>
          </div>
          </div>
        </div>
      </div>
    </div>
  </section>
  </main>

  <!-- Structured Data -->
  <script type="application/ld+json">
  {!! json_encode([
    '@context' => 'https://schema.org',
    '@type' => 'AboutPage',
    'name' => 'About Smarthands Cleaning Services',
    'description' => 'Learn about Smarthands Cleaning Services, your trusted professional cleaning partner in Bacolod City.',
    'url' => url()->current(),
    'mainEntity' => [
      '@type' => 'LocalBusiness',
      'name' => 'Smarthands Cleaning Services',
      'image' => asset('images/Smarthands.png'),
      'url' => url('/'),
      'telephone' => $contactInfo->phone ?? '0953 957 4130',
      'email' => $contactInfo->email ?? 'smarthandsbcd@gmail.com',
      'address' => [
        '@type' => 'PostalAddress',
        'addressLocality' => 'Bacolod',
        'addressRegion' => 'Negros Occidental',
        'postalCode' => '6100',
        'addressCountry' => 'PH'
      ]
    ]
  ], JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) !!}
  </script>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script src="{{ asset('js/app.js') }}"></script>
  
  <!-- Footer -->
  <x-footer :contactInfo="$contactInfo" />

  @if(Auth::check())
<div class="modal fade" id="profileModal" tabindex="-1" aria-labelledby="profileModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header custom-orange">
        <h5 class="modal-title" id="profileModalLabel">Your Profile</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form id="profileForm" method="POST" action="{{ url('/customer/profile') }}">
        @csrf
        <div id="profileSuccessAlert" class="alert alert-success d-none" role="alert"></div>
        <div class="modal-body">
            <div class="mb-3">
                <label for="name" class="form-label">Name</label>
                <input type="text" class="form-control" id="about_name" name="name" value="{{ Auth::user()->name ?? '' }}">
                <div class="invalid-feedback" id="error-name"></div>
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" id="about_email" name="email" value="{{ Auth::user()->email ?? '' }}">
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
          <button type="submit" class="btn" style="background-color: white; color: #ff9f1c; border: 1px solid #ff9f1c; transition: all 0.3s ease;" onmouseover="this.style.backgroundColor='#ff9f1c'; this.style.color='white'; this.style.borderColor='#ff9f1c'" onmouseout="this.style.backgroundColor='white'; this.style.color='#ff9f1c'; this.style.borderColor='#ff9f1c'">Save Changes</button>
        </div>
      </form>
    </div>
  </div>
</div>
@endif
<script>
document.addEventListener('DOMContentLoaded', function () {
    const profileForm = document.getElementById('profileForm');
    const profileModal = document.getElementById('profileModal');
    const successAlert = document.getElementById('profileSuccessAlert');

    if (profileForm) {
        profileForm.addEventListener('submit', function (e) {
            e.preventDefault();
            successAlert.classList.add('d-none');
            // Clear errors
            ['name','email','current_password','new_password','new_password_confirmation'].forEach(function(field) {
                document.getElementById('error-' + field).innerText = '';
                document.getElementById(field).classList.remove('is-invalid');
            });
            const formData = new FormData(profileForm);
            fetch(profileForm.action, {
                method: 'POST',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
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
                        modalInstance.hide();
                        successAlert.classList.add('d-none');
                        window.location.href = '/';
                    }, 1200);
                } else if (data.errors) {
                    Object.keys(data.errors).forEach(function(field) {
                        document.getElementById('error-' + field).innerText = data.errors[field][0];
                        document.getElementById(field).classList.add('is-invalid');
                    });
                }
            })
            .catch(async error => {
                if (error instanceof Response) {
                    const errData = await error.json();
                    if (errData.errors) {
                        Object.keys(errData.errors).forEach(function(field) {
                            document.getElementById('error-' + field).innerText = errData.errors[field][0];
                            document.getElementById(field).classList.add('is-invalid');
                        });
                    }
                }
            });
        });
    }

    // Password visibility toggle for Account modal
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
@include('partials.my_bookings_modal')

<!-- Logout Form for all pages -->
<form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
    @csrf
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
<!-- Floating Book Now Button -->
<a href="{{ route('home') }}#services" class="floating-book-btn" title="Book a cleaning service" aria-label="Book Now">
  <i class="bi bi-calendar-check"></i>
  <span>Book Now</span>
</a>
</body>
</html>