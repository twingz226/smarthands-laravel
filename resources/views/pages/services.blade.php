<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Our Services - Smarthands</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    /* Navbar styles */
    .custom-navbar {
      padding-top: 0.5rem;
      padding-bottom: 0.5rem;
      min-height: 80px;
      background-color: #ffc044 !important;
    }
    .navbar-brand img, .logo img {
      height: 100px;
      width: auto;
      border-radius: 80%;
    }
    .nav-link {
      font-weight: 700;
      color: #333 !important;
      padding: 0.5rem 1rem !important;
    }
    .nav-link:hover {
      color: #0d6efd !important;
    }
    .nav-link.active {
      color: #0d6efd !important;
      text-decoration: underline;
    }
    .navbar-nav {
      align-items: center;
    }
    .logo {
      text-align: center;
      margin: 1rem 0;
    }

    /* Services Section */
    .services-section {
      padding: 60px 5%;
      background-color: white;
    }
    .services-tagline {
      font-size: 2rem;
      font-weight: 700;
      color: #000;
      text-align: center;
      margin-bottom: 40px;
    }
    .service-cards {
      display: grid;
      grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
      gap: 30px;
      margin-bottom: 30px;
    }
    .card {
      border: none;
      border-radius: 8px;
      box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
      transition: all 0.3s ease;
      padding: 25px;
    }
    .card:hover {
      transform: translateY(-10px);
      box-shadow: 0 15px 30px rgba(0, 0, 0, 0.1);
    }
    .light-bg {
      background-color: #f8f9fa;
    }
    .card h4 {
      font-size: 1.3rem;
      margin-bottom: 15px;
      color: #111c5d;
    }
    .card p {
      font-size: 0.95rem;
      color: #555;
      line-height: 1.6;
    }

    /* Hero Section */
    .hero-section {
      height: auto;
      min-height: 50vh;
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      padding: 80px 5% 40px;
      position: relative;
      z-index: 1;
      background-color: #f8f9fa;
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
      color: #666;
      line-height: 1.4;
      margin-bottom: 2rem;
    }

    /* Responsive Styles */
    @media (min-width: 768px) {
      .services-tagline {
        font-size: 2.5rem;
        margin-bottom: 60px;
      }
      .main-headline {
        font-size: 3rem;
      }
      .tagline {
        font-size: 1.3rem;
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
  </style>
</head>
<body>
  <!-- Navbar -->
  <nav class="navbar navbar-expand-lg navbar-light custom-navbar shadow-sm sticky-top">
    <div class="container">
      <a class="navbar-brand" href="{{ route('home') }}">
        <img src="{{ asset('images/Smarthand.png') }}" alt="Logo">
      </a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav ms-auto">
          <li class="nav-item"><a class="nav-link @if(Route::currentRouteName() == 'home') active @endif" href="{{ route('home') }}">Home</a></li>
          <li class="nav-item"><a class="nav-link @if(Route::currentRouteName() == 'services') active @endif" href="{{ route('services') }}">Service</a></li>
          <li class="nav-item"><a class="nav-link @if(Route::currentRouteName() == 'about') active @endif" href="{{ route('about') }}">About Us</a></li>
          <li class="nav-item"><a class="nav-link @if(Route::currentRouteName() == 'contact') active @endif" href="{{ route('contact') }}">Contact</a></li>
        </ul>
      </div>
    </div>
  </nav>

  <!-- Hero Section -->
  <section class="hero-section">
    <h1 class="main-headline">Our Cleaning Services</h1>
    <p class="tagline">Where Simplicity Meets Spotless Results</p>
  </section>

  <!-- Services Content -->
  <section class="services-section">
    <h2 class="services-tagline">Our Services</h2>
    
    <div class="service-cards">
      <div class="card light-bg">
        <h4>Customized Deep Cleaning</h4>
        <p>Includes folding clothes, organizing closets, ironing, decluttering, and other customizable cleaning needs.</p>
        <p><strong>Price: ₱299/hr minimum of 6 hours</strong></p>
      </div>
      
      <div class="card light-bg">
        <h4>Apartment Deep Cleaning</h4>
        <p>This service covers the entire area of your unit with thorough cleaning. It includes free dry vacuuming of all couches, beds, and pillows and free cleaning of CRs at no additional cost.</p>
        <p><strong>Price: ₱299/hr minimum of 6 hours</strong></p>
      </div>
      
      <div class="card light-bg">
        <h4>Move-in/Move-out Cleaning</h4>
        <p>This service is ideal for Airbnb, rental units, or spaces that need to be guest-ready or enhanced before moving in or out. We will cover all necessary cleaning tasks.</p>
        <p><strong>Price: ₱75/sqm minimum of 6 hours</strong></p>
      </div>
      
      <div class="card light-bg">
        <h4>2-Story/Bungalow House Deep Cleaning</h4>
        <p>This includes thoroughly cleaning upstairs and downstairs, free dry vacuuming of all couches, beds, and pillows, and cleaning electric fans, windows, garages, and toilets, plus backyard grass removal at no extra cost.</p>
        <p><strong>Price: ₱75/sqm</strong></p>
      </div>
      
      <div class="card light-bg">
        <h4>Post-Construction/Renovation/ Commercial Cleaning</h4>
        <p>Removing construction dust, paint marks, cement residues, and debris to ensure the area is clean and ready for move-in.</p>
        <p><strong>Price: ₱75/sqm</strong></p>
      </div>
    </div>
  </section>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>