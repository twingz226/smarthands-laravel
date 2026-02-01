<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
  <title>Booking Success - Smarthands Cleaning Services</title>
  <link rel="icon" href="<?php echo e(asset('images/Smarthands.png')); ?>" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
  <link rel="stylesheet" href="<?php echo e(asset('css/style.css')); ?>">
  <style>
    :root {
      --bs-font-sans: 'Poppins', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
    }
    body {
      font-family: 'Poppins', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
    }
    .modal-title, h1, h2, h3, h4, h5, h6 {
      font-weight: 600;
    }
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
      color: #333 !important;
      padding: 0.3rem 1rem !important;
    }
    .nav-link:hover {
      color:rgb(253, 253, 253) !important;
    }
    .nav-link.active {
      color:rgb(247, 249, 252) !important;
      text-decoration: underline;
    }
    .navbar-nav {
      align-items: center;
    }
    .logo {
      text-align: center;
      margin: 1rem 0;
    }

    /* Success section styles */
    .success-section {
      min-height: calc(100vh - 80px);
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 40px 5%;
      background-color: #f8f9fa;
    }
    .success-card {
      background: white;
      border-radius: 15px;
      box-shadow: 0 15px 30px rgba(0, 0, 0, 0.1);
      padding: 3rem;
      max-width: 600px;
      width: 100%;
      text-align: center;
    }
    .success-icon {
      font-size: 5rem;
      color: #28a745;
      margin-bottom: 1.5rem;
    }
    .success-title {
      color: #28a745;
      font-size: 2.5rem;
      font-weight: 700;
      margin-bottom: 1rem;
    }
    .success-message {
      color: #666;
      font-size: 1.2rem;
      margin-bottom: 2rem;
    }
    .return-btn {
      padding: 12px 30px;
      font-size: 1.1rem;
      background-color: #ffc044;
      color: #333;
      border: none;
      border-radius: 30px;
      cursor: pointer;
      transition: all 0.3s ease;
      text-decoration: none;
      display: inline-block;
    }
    .return-btn:hover {
      background-color: #111c5d;
      color: white;
      transform: translateY(-2px);
    }

    @media (max-width: 768px) {
      .success-card {
        padding: 2rem;
        margin: 1rem;
      }
      .success-title {
        font-size: 2rem;
      }
      .success-message {
        font-size: 1rem;
      }
    }
  </style>
</head>
<body>
  <!-- Navbar -->
  <nav class="navbar navbar-expand-lg navbar-light custom-navbar shadow-sm sticky-top">
    <div class="container">
      <a class="navbar-brand" href="<?php echo e(route('home')); ?>">
        <img src="<?php echo e(asset('images/Smarthands.png')); ?>" alt="Smarthands Cleaning Services">
      </a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav ms-auto">
          <li class="nav-item"><a class="nav-link <?php if(Route::currentRouteName() == 'home'): ?> active <?php endif; ?>" href="<?php echo e(route('home')); ?>">Home</a></li>
          <li class="nav-item"><a class="nav-link <?php if(Route::currentRouteName() == 'services'): ?> active <?php endif; ?>" href="<?php echo e(route('services')); ?>">Services</a></li>
          <li class="nav-item"><a class="nav-link <?php if(Route::currentRouteName() == 'about'): ?> active <?php endif; ?>" href="<?php echo e(route('about')); ?>">About Us</a></li>
          <li class="nav-item"><a class="nav-link <?php if(Route::currentRouteName() == 'contact'): ?> active <?php endif; ?>" href="<?php echo e(route('contact')); ?>">Contact</a></li>
        </ul>
      </div>
    </div>
  </nav>

  <!-- Success Section -->
  <section class="success-section">
    <div class="success-card">
      <i class="bi bi-check-circle-fill success-icon"></i>
      <h1 class="success-title">We have successfully scheduled your ocular visit.</h1>
      <p class="success-message">To proceed, confirm your booking on the My Bookings page and agree to the Privacy Policy and Terms and Conditions.</p>
      <a href="<?php echo e(route('home')); ?>" class="return-btn">Return to Home</a>
    </div>
  </section>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> <?php /**PATH /opt/lampp/htdocs/cleaning_service_management_system/resources/views/bookings/success.blade.php ENDPATH**/ ?>