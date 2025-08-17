<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Contact Us - Smarthands</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    /* Navbar styles */
    .custom-navbar {
      padding-top: 0.3rem;
      padding-bottom: 0.3rem;
      min-height: 70px;
      background-color: #ff9f1c !important;
    }
    .navbar-brand img, .logo img {
      height: 80px;
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

    /* Contact Section */
    .contact-section {
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
    .contact-card {
      background-color: rgba(254, 254, 254, 0.85);
      border-radius: 10px;
      padding: 30px;
      margin-bottom: 30px;
      box-shadow: 0 8px 20px rgba(0, 0, 0, 0.06);
      max-width: 600px;
      margin-left: auto;
      margin-right: auto;
    }
    .contact-item {
      margin-bottom: 20px;
      font-size: 1.1rem;
    }
    .contact-item strong {
      color: #111c5d;
      display: block;
      margin-bottom: 5px;
    }
    .social-links {
      display: flex;
      gap: 15px;
      margin-top: 30px;
      justify-content: center;
    }
    .social-links a {
      color: #111c5d;
      text-decoration: none;
      font-weight: bold;
      padding: 8px 15px;
      border: 2px solid #111c5d;
      border-radius: 5px;
      transition: all 0.3s ease;
    }
    .social-links a:hover {
      background-color: #111c5d;
      color: white;
      text-decoration: none;
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
      .contact-card {
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
          <li class="nav-item"><a class="nav-link @if(Route::currentRouteName() == 'services') active @endif" href="{{ route('services') }}">Services</a></li>
          <li class="nav-item"><a class="nav-link @if(Route::currentRouteName() == 'about') active @endif" href="{{ route('about') }}">About Us</a></li>
          <li class="nav-item"><a class="nav-link @if(Route::currentRouteName() == 'contact') active @endif" href="{{ route('contact') }}">Contact</a></li>
        </ul>
      </div>
    </div>
  </nav>

  <!-- Hero Section -->
  <section class="hero-section">
    <h1 class="main-headline">Contact Smarthands</h1>
    <p class="tagline">Get in touch with our professional cleaning team</p>
  </section>

  <!-- Contact Content -->
  <section class="contact-section">
    <h2 class="section-title">Contact Information</h2>
    
    <div class="contact-card">
      <div class="contact-item">
        <strong>Email:</strong>
        {{ $contactInfo->email ?? 'smarthandsbcd@gmail.com' }}
      </div>
      
      <div class="contact-item">
        <strong>Phone:</strong>
        {{ $contactInfo->phone ?? '0953 957 4130' }}
      </div>

      <div class="contact-item">
        <strong>Address:</strong>
        {{ $contactInfo->address ?? 'Bacolod City, Philippines, 6100' }}
      </div>

      <div class="contact-item">
        <strong>Service Area:</strong>
        {{ $contactInfo->service_area ?? 'Silay City, Philippines · Talisay, Philippines · Bacolod City, Philippines' }}
      </div>

      <div class="contact-item">
        <strong>Business Hours:</strong>
        {{ $contactInfo->business_hours ?? 'Always open' }}
      </div>
      
      <div class="social-links">
        @if($contactInfo && $contactInfo->facebook_url)
          <a href="{{ $contactInfo->facebook_url }}" target="_blank">Facebook Page</a>
        @endif
        @if($contactInfo && $contactInfo->google_business_url)
          <a href="{{ $contactInfo->google_business_url }}" target="_blank">Google Business</a>
        @endif
      </div>
    </div>
  </section>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>