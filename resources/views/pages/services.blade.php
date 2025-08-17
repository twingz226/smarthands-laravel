<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Our Services - Smarthands</title>

  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet" />

  <style>
    body {
      font-family: 'Segoe UI', sans-serif;
    }
    .custom-navbar {
      padding-top: 0.3rem;
      padding-bottom: 0.3rem;
      min-height: 70px;
      background-color: #ff9f1c !important;
    }
    .navbar-brand img {
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
    }
    .nav-link.active {
      color: #ffffff !important;
      text-decoration: underline;
      text-underline-offset: 5px;
    }
    .hero-section {
      padding: 80px 5% 40px;
      background-color: #2ec4b6; 
      text-align: center;
    }
    .main-headline {
      font-size: 2.5rem;
      font-weight: 700;
    }
    .tagline {
      font-size: 1.2rem;
      color:  #000000;
    }
    .services-section {
      padding: 60px 5%;
      background-color: #cbf3f0;
    }
    .services-tagline {
      font-size: 2.5rem;
      font-weight: 700;
      text-align: center;
      margin-bottom: 30px;
    }

    .desktop-cards {
      display: grid;
      grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
      gap: 30px;
    }

    .card {
      border: none;
      border-radius: 10px;
      background-color: rgba(254, 254, 254, 0.85);
      box-shadow: 0 8px 20px rgba(0, 0, 0, 0.06);
      padding: 25px;
      transition: all 0.3s ease;
      position: relative;
      overflow: hidden;
      z-index: 1;
    }

    .card::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background-size: cover;
      background-position: center;
      opacity: 0.70;
      z-index: -1;
    }

    .card:nth-child(1)::before {
      background-image: url('{{ asset('images/service1.jpg') }}');
    }

    .card:nth-child(2)::before {
      background-image: url('{{ asset('images/service2.jpg') }}');
    }

    .card:nth-child(3)::before {
      background-image: url('{{ asset('images/service3.jpg') }}');
    }

    .card:nth-child(4)::before {
      background-image: url('{{ asset('images/service4.jpg') }}');
    }

    .card:nth-child(5)::before {
      background-image: url('{{ asset('images/service5.jpg') }}');
    }

    .card:hover {
      transform: translateY(-8px);
      box-shadow: 0 15px 25px rgba(0, 0, 0, 0.1);
    }

    .card h4 {
      font-size: 1.2rem;
      margin-bottom: 15px;
      text-align: center;
      position: relative;
      z-index: 2;
      color:  #000000;
      text-shadow: 1px 1px 2px rgba(255, 255, 255, 0.9);
    }

    .card i {
      font-size: 2.5rem;
      display: block;
      text-align: center;
      margin-bottom: 10px;
      position: relative;
      z-index: 2;
    }

    .card ul {
      position: relative;
      z-index: 2;
      background-color: rgba(255, 255, 255, 0.85);
      padding: 15px;
      border-radius: 8px;
    }

    .card ul li {
      position: relative;
      z-index: 2;
      text-shadow: 0 0 1px rgba(255, 255, 255, 0.9);
    }

    .accordion-item {
      border-radius: 8px;
      margin-bottom: 10px;
    }

    .accordion-button i {
      margin-right: 8px;
    }

    .mobile-accordion {
      display: none;
    }

    @media (max-width: 768px) {
      .desktop-cards {
        display: none;
      }
      .mobile-accordion {
        display: block;
      }
    }
  </style>
</head>
<body>

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

  <section class="hero-section">
    <h1 class="main-headline">Cleaning Services</h1>
    <p class="tagline">Where Simplicity Meets Spotless Results</p>
  </section>

  <section class="services-section">
    <!-- Desktop cards -->
    <div class="desktop-cards">
      <div class="card">
        <i class="bi bi-sliders2 text-warning"></i>
        <h4>Customized Deep Cleaning</h4>
        <ul class="list-unstyled">
          <li><strong>Hour/s:</strong> Minimum of 6 hours</li>
          <li><strong>Price:</strong> ₱299/hr</li>
          <li><strong>Discounts:</strong> -</li>
          <li><strong>Inclusion:</strong> Includes folding clothes, organizing closets, ironing, decluttering, and other customizable cleaning needs.</li>
        </ul>
      </div>

      <div class="card">
        <i class="bi bi-house-door text-info"></i>
        <h4>Apartment Deep Cleaning</h4>
        <ul class="list-unstyled">
          <li><strong>Hour/s:</strong> Minimum of 6 hours</li>
          <li><strong>Price:</strong> ₱299/hr</li>
          <li><strong>Discounts:</strong> -</li>
          <li><strong>Inclusion:</strong> This service covers the entire area of your unit with thorough cleaning. It includes free dry vacuuming of all couches, beds, and pillows and free cleaning of CRs at no additional cost.</li>
        </ul>
      </div>

      <div class="card">
        <i class="bi bi-building text-danger"></i>
        <h4>2-Story/Bungalow House Deep Cleaning</h4>
        <ul class="list-unstyled">
          <li><strong>Hour/s:</strong> -</li>
          <li><strong>Price:</strong> ₱75/Sqm.</li>
          <li><strong>Discounts:</strong> 5% off for 50-90 SQM.<br>10% off for 100 SQM. and above.</li>
          <li><strong>Inclusion:</strong> This includes thoroughly cleaning upstairs and downstairs, free dry vacuuming of all couches, beds, and pillows, and cleaning electric fans, windows, garages, and toilets, plus backyard grass removal at no extra cost.</li>
        </ul>
      </div>

      <div class="card">
        <i class="bi bi-box-arrow-left text-primary"></i>
        <h4>Move-in/Move-out Cleaning</h4>
        <ul class="list-unstyled">
          <li><strong>Hour/s:</strong> Minimum of 6 hours</li>
          <li><strong>Price:</strong> Not more than 35 sqm.</li>
          <li><strong>Discounts:</strong> -</li>
          <li><strong>Inclusion:</strong> This service is ideal for Airbnb, rental units, or spaces that need to be guest-ready or enhanced before moving in or out. We will cover all necessary cleaning tasks.</li>
        </ul>
      </div>

      <div class="card">
        <i class="bi bi-hammer text-success"></i>
        <h4>Post-Construction/Renovation/<br>Commercial Cleaning</h4>
        <ul class="list-unstyled">
          <li><strong>Hour/s:</strong> -</li>
          <li><strong>Price:</strong> ₱75/Sqm.</li>
          <li><strong>Discounts:</strong> 5% off for 50-90 SQM.<br>10% off for 100 SQM. and above.</li>
          <li><strong>Inclusion:</strong> Removing construction dust, paint marks, cement residues, and debris to ensure the area is clean and ready for move-in.</li>
        </ul>
      </div>
    </div>

    <!-- Mobile accordion -->
    <div class="accordion mobile-accordion mt-4" id="servicesAccordion">
      <div class="accordion-item">
        <h2 class="accordion-header" id="headingOne">
          <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne">
            <i class="bi bi-sliders2 text-warning"></i> Customized Deep Cleaning
          </button>
        </h2>
        <div id="collapseOne" class="accordion-collapse collapse show" data-bs-parent="#servicesAccordion">
          <div class="accordion-body">
            <strong>Hour/s:</strong> Minimum of 6 hours<br>
            <strong>Price:</strong> ₱299/hr<br>
            <strong>Discounts:</strong> -<br>
            <strong>Inclusion:</strong> Includes folding clothes, organizing closets, ironing, decluttering, and other customizable cleaning needs.
          </div>
        </div>
      </div>

      <div class="accordion-item">
        <h2 class="accordion-header" id="headingTwo">
          <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo">
            <i class="bi bi-house-door text-info"></i> Apartment Deep Cleaning
          </button>
        </h2>
        <div id="collapseTwo" class="accordion-collapse collapse" data-bs-parent="#servicesAccordion">
          <div class="accordion-body">
            <strong>Hour/s:</strong> Minimum of 6 hours<br>
            <strong>Price:</strong> ₱299/hr<br>
            <strong>Discounts:</strong> -<br>
            <strong>Inclusion:</strong> This service covers the entire area of your unit with thorough cleaning. It includes free dry vacuuming of all couches, beds, and pillows and free cleaning of CRs at no additional cost.
          </div>
        </div>
      </div>

      <div class="accordion-item">
        <h2 class="accordion-header" id="headingThree">
          <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree">
            <i class="bi bi-building text-danger"></i> 2-Story/Bungalow House Cleaning
          </button>
        </h2>
        <div id="collapseThree" class="accordion-collapse collapse" data-bs-parent="#servicesAccordion">
          <div class="accordion-body">
            <strong>Hour/s:</strong> -<br>
            <strong>Price:</strong> ₱75/Sqm<br>
            <strong>Discounts:</strong> 5% off for 50-90 SQM.<br>10% off for 100 SQM. and above.<br>
            <strong>Inclusion:</strong> This includes thoroughly cleaning upstairs and downstairs, free dry vacuuming of all couches, beds, and pillows, and cleaning electric fans, windows, garages, and toilets, plus backyard grass removal at no extra cost.
          </div>
        </div>
      </div>

      <div class="accordion-item">
        <h2 class="accordion-header" id="headingFour">
          <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFour">
            <i class="bi bi-box-arrow-left text-primary"></i> Move-in/Move-out Cleaning
          </button>
        </h2>
        <div id="collapseFour" class="accordion-collapse collapse" data-bs-parent="#servicesAccordion">
          <div class="accordion-body">
            <strong>Hour/s:</strong> Minimum of 6 hours<br>
            <strong>Price:</strong> Not more than 35 sqm.<br>
            <strong>Discounts:</strong> -<br>
            <strong>Inclusion:</strong> This service is ideal for Airbnb, rental units, or spaces that need to be guest-ready or enhanced before moving in or out. We will cover all necessary cleaning tasks.
          </div>
        </div>
      </div>

      <div class="accordion-item">
        <h2 class="accordion-header" id="headingFive">
          <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFive">
            <i class="bi bi-hammer text-success"></i> Post-Construction/Renovation/Commercial
          </button>
        </h2>
        <div id="collapseFive" class="accordion-collapse collapse" data-bs-parent="#servicesAccordion">
          <div class="accordion-body">
            <strong>Hour/s:</strong> -<br>
            <strong>Price:</strong> ₱75/Sqm<br>
            <strong>Discounts:</strong> 5% off for 50-90 SQM.<br>10% off for 100 SQM. and above.<br>
            <strong>Inclusion:</strong> Removing construction dust, paint marks, cement residues, and debris to ensure the area is clean and ready for move-in.
          </div>
        </div>
      </div>
    </div>

    <div class="mt-5 text-center">
      <p class="fw-bold mb-2">All services include free cleaning materials.</p>
      <p class="fw-bold text-danger">Note: For areas outside Bacolod, a ₱300 fuel charge applies.</p>
    </div>
  </section>

  <!-- Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
