<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>Smarthands Cleaning Services</title>
  <link rel="icon" href="{{ asset('images/Smarthands.png') }}" />
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
  <link rel="stylesheet" href="{{ asset('css/style.css') }}">
  <style>
    :root {
      --bs-font-sans: 'Poppins', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
    }
    body {
      font-family: 'Poppins', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
    }
    #totalInitialPriceAlert {
      display: none !important;
    }
    .modal-title, h1, h2, h3, h4, h5, h6 {
      font-weight: 600;
    }
    .fully-booked-date {
      background-color: #ffcccc !important;
      color: #666 !important;
      cursor: not-allowed !important;
    }
    .fully-booked-date:hover {
      background-color: #ffcccc !important;
      color: #666 !important;
      cursor: not-allowed !important;
    }
    
    /* About & Contact Section Styles */
    .about-section, .contact-section {
      padding: 60px 5%;
    }
    .about-section { background-color: #f8f9fa; }
    .contact-section { background-color: #cbf3f0; }
    .section-title {
      font-size: 2.5rem;
      font-weight: 700;
      text-align: center;
      margin-bottom: 40px;
    }
    .info-card, .contact-card {
      background-color: rgba(254, 254, 254, 0.95);
      border-radius: 10px;
      padding: 30px;
      margin-bottom: 30px;
      box-shadow: 0 8px 20px rgba(0, 0, 0, 0.06);
      transition: transform 0.3s ease;
    }
    .info-card:hover, .contact-card:hover {
      transform: translateY(-5px);
    }
    .contact-item {
      margin-bottom: 20px;
      font-size: 1.1rem;
    }
    .contact-item strong {
      color: #2c3e50;
      display: block;
      margin-bottom: 5px;
      font-weight: 600;
    }
    /* Contact section social links */
    #contact .social-links {
      display: flex;
      gap: 15px;
      margin-top: 30px;
      justify-content: center;
    }
    #contact .social-links a {
      color: #2c3e50;
      text-decoration: none;
      font-weight: bold;
      padding: 8px 15px;
      border: 2px solid #2c3e50;
      border-radius: 5px;
      transition: all 0.3s ease;
    }
    #contact .social-links a:hover {
      background-color: #2c3e50;
      color: white;
    }
    .btn-secondary:hover {
      background-color: #2c3e50 !important;
      border-color: #2c3e50 !important;
      color: white !important;
    }

    /* Ratings Section Styles */
    .ratings-section {
      background-color: #cbf3f0;
      padding: 60px 5%;
    }
    .ratings-intro {
      max-width: 680px;
      margin: 0 auto 40px;
      text-align: center;
    }
    .ratings-summary {
      display: inline-flex;
      align-items: center;
      gap: 16px;
      padding: 18px 28px;
      border-radius: 12px;
      background: linear-gradient(135deg, rgba(255, 159, 28, 0.15), rgba(23, 162, 184, 0.15));
      margin-bottom: 18px;
      font-weight: 500;
      color: #2c3e50;
    }
    .ratings-summary .score {
      font-size: 2.5rem;
      font-weight: 700;
      color: #ff9f1c;
      line-height: 1;
    }
    .ratings-summary .details {
      text-align: left;
    }
    .ratings-grid {
      display: grid;
      gap: 24px;
      grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    }
    .rating-card {
      background: rgba(255, 255, 255, 0.95);
      border-radius: 14px;
      padding: 24px;
      box-shadow: 0 12px 28px rgba(0, 0, 0, 0.08);
      border: 1px solid rgba(0, 0, 0, 0.04);
      position: relative;
      overflow: hidden;
    }
    .rating-card:before {
      content: "";
      position: absolute;
      inset: 0;
      background: linear-gradient(135deg, rgba(255, 159, 28, 0.12), rgba(0, 0, 0, 0));
      opacity: 0;
      transition: opacity 0.25s ease;
    }
    .rating-card:hover:before {
      opacity: 1;
    }
    .rating-card .rating-stars {
      color: #ffc107;
      font-size: 1.1rem;
      letter-spacing: 2px;
      margin-bottom: 10px;
    }
    .rating-card .rating-value {
      font-weight: 600;
      color: #ff9f1c;
    }
    .rating-card .service-name {
      font-weight: 600;
      margin-bottom: 6px;
      color: #2c3e50;
    }
    .rating-card .rating-comment {
      color: #4a4a4a;
      margin-bottom: 14px;
      min-height: 72px;
    }
    .rating-card .customer-names {
      margin-top: 8px;
      font-style: italic;
    }
    .rating-card .customer-comment {
      font-style: italic;
      color: #495057;
      line-height: 1.4;
      margin-bottom: 8px;
    }
    .rating-empty {
      text-align: center;
      padding: 40px;
      background: rgba(0, 0, 0, 0.03);
      border-radius: 12px;
      color: #6c757d;
    }

    /* Detailed Services Section Styles */
    #detailed-services .desktop-cards {
      display: flex;
      flex-wrap: wrap;
      justify-content: center;
      gap: 30px;
      margin-bottom: 30px;
    }

    #detailed-services .card {
      border: none;
      border-radius: 8px;
      background-color: #f8f9fa;
      box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
      padding: 25px;
      transition: all 0.3s ease;
      position: relative;
      overflow: visible;
      z-index: 1;
      width: 100%;
      max-width: 400px;
      min-width: 250px;
      min-height: 250px;
      display: flex;
      flex-direction: column;
    }

    #detailed-services .card:hover {
      transform: translateY(-10px);
      box-shadow: 0 15px 30px rgba(0, 0, 0, 0.1);
    }

    #detailed-services .card h4 {
      font-size: 1.2rem;
      margin-bottom: 15px;
      text-align: center;
      position: relative;
      z-index: 2;
      color: #000000;
      text-shadow: 1px 1px 2px rgba(255, 255, 255, 0.9);
    }

    #detailed-services .card i {
      font-size: 2.5rem;
      display: block;
      text-align: center;
      margin-bottom: 10px;
      position: relative;
      z-index: 2;
    }

    #detailed-services .card ul {
      position: relative;
      z-index: 2;
      background-color: rgba(255, 255, 255, 0.85);
      padding: 15px;
      border-radius: 8px;
      margin: 0;
      flex-grow: 1;
      overflow-y: auto;
    }

    #detailed-services .card ul li {
      position: relative;
      z-index: 2;
      margin-bottom: 8px;
      text-shadow: 0 0 1px rgba(255, 255, 255, 0.9);
      margin-bottom: 8px;
    }

    #detailed-services .accordion-item {
      border-radius: 8px;
      margin-bottom: 10px;
    }

    #detailed-services .accordion-button i {
      margin-right: 8px;
    }

    #detailed-services .mobile-accordion {
      display: none;
    }

    @media (max-width: 768px) {
      #detailed-services .desktop-cards {
        display: none;
      }
      #detailed-services .mobile-accordion {
        display: block;
      }
    }
  </style>

  <script src="https://www.google.com/recaptcha/api.js" async defer></script>
</head>
<body>
  <!-- Navbar -->
  <nav class="navbar navbar-expand-lg navbar-light custom-navbar shadow-sm sticky-top">
    <div class="container">
      <a class="navbar-brand" href="{{ route('home') }}">
@php
    use App\Models\Setting;
    $companyLogo = Setting::getValue('company_logo');
@endphp
        <img src="{{ $companyLogo ? asset('storage/' . $companyLogo) : asset('images/Smarthands.png') }}" alt="Logo" onerror="this.src='https://via.placeholder.com/100'">
      </a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav ms-auto">
          <li class="nav-item"><a class="nav-link" href="#hero">Home</a></li>
          <li class="nav-item"><a class="nav-link" href="#detailed-services">Services</a></li>
          <li class="nav-item"><a class="nav-link" href="#ratings">Ratings</a></li>
          <li class="nav-item"><a class="nav-link" href="#about">About Us</a></li>
          <li class="nav-item"><a class="nav-link" href="#contact">Contact</a></li>
          @if(Auth::check())
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

  <main>
    <!-- Success Message -->
    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
      {{ session('success') }}
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    <!-- Error Message -->
    @if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
      {{ session('error') }}
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    <!-- Hero Section -->
    <section id="hero" class="hero-section">
      <div class="hero-content">
        <h1 class="main-headline">Smarthands Cleaning Services</h1>
        <p class="tagline">Where Simplicity Meets Spotless Results</p>
      </div>
      @if(isset($heroMedia) && $heroMedia)
        @if($heroMedia->media_type === 'video')
          <video class="hero-video" autoplay loop playsinline muted @if($heroMedia->poster_url) poster="{{ $heroMedia->poster_url }}" @endif>
            <source src="{{ $heroMedia->media_url }}" type="video/mp4">
            Your browser does not support the video tag.
          </video>
        @elseif($heroMedia->media_type === 'image')
          <img class="hero-image" src="{{ $heroMedia->media_url }}" alt="Hero Image">
        @else
          <video class="hero-video" autoplay loop playsinline muted>
            <source src="{{ asset('clean.mp4') }}" type="video/mp4">
            Your browser does not support the video tag.
          </video>
        @endif
      @else
        <video class="hero-video" autoplay loop playsinline muted>
          <source src="{{ asset('clean.mp4') }}" type="video/mp4">
          Your browser does not support the video tag.
        </video>
      @endif
    </section>

    <!-- Services Section -->
    <section id="services" class="services-section">
      <h2 class="services-tagline">Book Our Service <i class="bi bi-arrow-down d-block mt-2"></i></h2>

      <div class="services-grid">
        @if($servicesMedia->isNotEmpty())
          @foreach($servicesMedia as $service)
            <div class="service-box">
              <img src="{{ $service->media_url }}" alt="{{ $service->title }}">
              <div class="service-content">
                <h3 class="service-title">{{ $service->title }}</h3>
                <p class="service-description">{{ $service->description }}</p>
                <p class="service-price">Price: {{ $service->price }}</p>
                @if(Auth::check())
                <a href="#" class="book-btn" data-bs-toggle="modal" data-bs-target="#bookingModal"
       data-service-name="{{ $service->title }}"
       data-service-price="{{ $service->price }}"
       data-service-id="{{ $service->service_id }}"
       data-service-type="{{ $service->service_type }}">Book</a>
                @else
                <a href="#" class="book-btn" data-bs-toggle="modal" data-bs-target="#guestBookingModal">Book</a>
                @endif
              </div>
            </div>
          @endforeach
        @else
          <!-- Fallback to static services if no dynamic services found -->
          <div class="service-box">
            <img src="{{ asset('images/service1.jpg') }}" alt="Customized Deep Cleaning">
            <div class="service-content">
              <h3 class="service-title">Customized Deep Cleaning</h3>
              <p class="service-description">Tailored cleaning solutions to meet your specific needs and preferences.</p>
              <p class="service-price">Price: ₱299/hr minimum of 6 hours</p>
              @if(Auth::check())
              <a href="#" class="book-btn" data-bs-toggle="modal" data-bs-target="#bookingModal"
     data-service-name="Customized Deep Cleaning"
     data-service-price="₱299/hr minimum of 6 hours"
     data-service-id="1"
     data-service-type="hourly">Book</a>
              @else
              <a href="#" class="book-btn" data-bs-toggle="modal" data-bs-target="#guestBookingModal">Book</a>
              @endif
            </div>
          </div>

          <div class="service-box">
            <img src="{{ asset('images/service2.jpg') }}" alt="Apartment Deep Cleaning">
            <div class="service-content">
              <h3 class="service-title">Apartment Deep Cleaning</h3>
              <p class="service-description">Thorough cleaning for apartments, covering every corner and surface.</p>
              <p class="service-price">Price: ₱299/hr minimum of 6 hours</p>
              @if(Auth::check())
              <a href="#" class="book-btn" data-bs-toggle="modal" data-bs-target="#bookingModal"
     data-service-name="Apartment Deep Cleaning"
     data-service-price="₱299/hr minimum of 6 hours"
     data-service-id="2"
     data-service-type="hourly">Book</a>
              @else
              <a href="#" class="book-btn" data-bs-toggle="modal" data-bs-target="#guestBookingModal">Book</a>
              @endif
            </div>
          </div>

          <div class="service-box">
            <img src="{{ asset('images/service3.jpg') }}" alt="2-Story/Bungalow House Cleaning">
            <div class="service-content">
              <h3 class="service-title">2-Story/Bungalow House Cleaning</h3>
              <p class="service-description">Comprehensive cleaning for larger homes with attention to all levels.</p>
              <p class="service-price">Price: ₱75/sqm</p>
              @if(Auth::check())
              <a href="#" class="book-btn" data-bs-toggle="modal" data-bs-target="#bookingModal"
     data-service-name="2-Story/Bungalow House Cleaning"
     data-service-price="₱75/sqm"
     data-service-id="3"
     data-service-type="sqm">Book</a>
              @else
              <a href="#" class="book-btn" data-bs-toggle="modal" data-bs-target="#guestBookingModal">Book</a>
              @endif
            </div>
          </div>

          <div class="service-box">
            <img src="{{ asset('images/service4.jpg') }}" alt="Move-in/Move-out Cleaning">
            <div class="service-content">
              <h3 class="service-title">Move-in/Move-out Cleaning</h3>
              <p class="service-description">Make your transition smooth with our professional move cleaning services.</p>
              <p class="service-price">Price: ₱75/sqm minimum of 6 hours</p>
              @if(Auth::check())
              <a href="#" class="book-btn" data-bs-toggle="modal" data-bs-target="#bookingModal"
     data-service-name="Move-in/Move-out Cleaning"
     data-service-price="₱75/sqm minimum of 6 hours"
     data-service-id="4"
     data-service-type="sqm">Book</a>
              @else
              <a href="#" class="book-btn" data-bs-toggle="modal" data-bs-target="#guestBookingModal">Book</a>
              @endif
            </div>
          </div>

          <div class="service-box">
            <img src="{{ asset('images/service5.jpg') }}" alt="Post-Construction/Renovation Cleaning">
            <div class="service-content">
              <h3 class="service-title">Post-Construction/Renovation Cleaning</h3>
              <p class="service-description">Specialized cleaning to remove construction dust and debris.</p>
              <p class="service-price">Price: ₱75/sqm</p>
              @if(Auth::check())
              <a href="#" class="book-btn" data-bs-toggle="modal" data-bs-target="#bookingModal"
     data-service-name="Post-Construction/Renovation Cleaning"
     data-service-price="₱75/sqm"
     data-service-id="5"
     data-service-type="sqm">Book</a>
              @else
              <a href="#" class="book-btn" data-bs-toggle="modal" data-bs-target="#guestBookingModal">Book</a>
              @endif
            </div>
          </div>
        @endif
      </div>
    </section>

    <!-- Detailed Services Section -->
    <section id="detailed-services" class="services-section" style="background-color: #e9ecef; padding: 40px 5%;">

      <!-- Desktop cards -->
      <div class="desktop-cards">
        @forelse($services as $service)
            <div class="card" style="background-color: #cbf3f0;">
            <h4>{{ $service->name }}</h4>
            <ul class="list-unstyled">
              <li>
                <strong>Hour/s:</strong>
                @if($service->pricing_type === 'duration' && $service->duration_minutes)
                  Minimum of {{ number_format($service->duration_minutes / 60, 0) }} hours
                @else
                  -
                @endif
              </li>
              <li>
                <strong>Price:</strong>
                @if($service->pricing_type === 'sqm')
                  ₱{{ number_format($service->price, 2) }}/sqm
                @else
                  ₱{{ number_format($service->price, 2) }}/hr
                @endif
              </li>
              <li><strong>Discounts:</strong> -</li>
              <li>
                <strong>Inclusion:</strong>
                {{ $service->description ?? '—' }}
              </li>
            </ul>
          </div>
        @empty
          <p class="text-center w-100">No services available yet. Please check back later.</p>
        @endforelse
      </div>

      <!-- Mobile accordion -->
      <div class="accordion mobile-accordion mt-4" id="servicesAccordion">
        @foreach($services as $index => $service)
          @php
            $collapseId = 'collapseService' . $index;
            $headingId = 'headingService' . $index;
            $isFirst = $index === 0;
          @endphp
          <div class="accordion-item">
            <h2 class="accordion-header" id="{{ $headingId }}">
              <button class="accordion-button {{ $isFirst ? '' : 'collapsed' }}" type="button" data-bs-toggle="collapse" data-bs-target="#{{ $collapseId }}">
                {{ $service->name }}
              </button>
            </h2>
            <div id="{{ $collapseId }}" class="accordion-collapse collapse {{ $isFirst ? 'show' : '' }}" data-bs-parent="#servicesAccordion">
              <div class="accordion-body">
                <strong>Hour/s:</strong>
                @if($service->pricing_type === 'duration' && $service->duration_minutes)
                  Minimum of {{ number_format($service->duration_minutes / 60, 0) }} hours
                @else
                  -
                @endif
                <br>
                <strong>Price:</strong>
                @if($service->pricing_type === 'sqm')
                  ₱{{ number_format($service->price, 2) }}/sqm
                @else
                  ₱{{ number_format($service->price, 2) }}/hr
                @endif
                <br>
                <strong>Discounts:</strong> -<br>
                <strong>Inclusion:</strong> {{ $service->description ?? '—' }}
              </div>
            </div>
          </div>
        @endforeach
        @if($services->isEmpty())
          <div class="accordion-item">
            <div class="accordion-body">No services available yet. Please check back later.</div>
          </div>
        @endif
      </div>

      <div class="mt-5 text-center">
        <p class="fw-bold mb-2">All services include free cleaning materials.</p>
        <p class="fw-bold text-danger">Note: For areas outside Bacolod, a ₱300 fuel charge applies.</p>
      </div>
    </section>

    <!-- Ratings Section -->
    <section id="ratings" class="ratings-section">
      <div class="container">
        <h2 class="section-title">What Our Customers Say</h2>

        <div class="ratings-intro">
          @if($ratingSummary['count'] > 0 && $ratingSummary['average'])
            <div class="ratings-summary">
              <span class="score">{{ number_format($ratingSummary['average'], 1) }}</span>
              <div class="details">
                <div>Average rating from {{ number_format($ratingSummary['count']) }} reviews</div>
                <div class="text-muted" style="font-size: 0.95rem;">Collected via our Admin Dashboard</div>
              </div>
            </div>
            <p class="mb-0">See how our customers rate each of our cleaning services. These averages are calculated from real customer feedback to help you choose the best service for your needs.</p>
            @if($contactInfo && $contactInfo->google_business_url)
              <div class="mt-3 text-center">
                <a href="{{ $contactInfo->google_business_url }}" target="_blank" class="btn btn-outline-primary">
                  <i class="bi bi-google me-2"></i>Leave a Review on Google
                </a>
              </div>
            @endif
          @else
            <p class="mb-0">We're gathering customer feedback from completed jobs. Check back soon to see what customers are saying about Smarthands!</p>
            @if($contactInfo && $contactInfo->google_business_url)
              <div class="mt-3 text-center">
                <a href="{{ $contactInfo->google_business_url }}" target="_blank" class="btn btn-outline-primary">
                  <i class="bi bi-google me-2"></i>Be the first to leave a review on Google
                </a>
              </div>
            @endif
          @endif
        </div>

        @if($serviceRatings->isNotEmpty())
          <div class="ratings-grid">
            @foreach($serviceRatings as $serviceRating)
              <div class="rating-card">
                <div class="rating-stars" aria-label="{{ $serviceRating['average_rating'] }} out of 5 stars">
                  @for($i = 1; $i <= 5; $i++)
                    <span>{{ $i <= $serviceRating['average_rating'] ? '★' : '☆' }}</span>
                  @endfor
                </div>
                <div class="rating-value">{{ number_format($serviceRating['average_rating'], 1) }} / 5</div>
                <div class="service-name">{{ $serviceRating['service_name'] }}</div>
                <div class="text-muted mb-2" style="font-size: 0.9rem; display: none;">
                  <small>{{ $serviceRating['customer_name'] }}’s average across {{ $serviceRating['rating_count'] }} rating{{ $serviceRating['rating_count'] > 1 ? 's' : '' }}</small>
                </div>
                <div class="rating-comment">
                  @if($serviceRating['customer_comment'])
                    <div class="customer-comment">
                      <small class="text-muted">"</small>{{ $serviceRating['customer_comment'] }}<small class="text-muted">"</small>
                    </div>
                  @elseif($serviceRating['service_description'])
                    {{ Str::limit(strip_tags($serviceRating['service_description']), 100) }}
                  @else
                    Professional cleaning service with excellent customer satisfaction.
                  @endif
                </div>
                <div class="rating-footer" style="display: none;">
                  <div class="customer-names">
                    <small>Rated by: {{ $serviceRating['customer_name'] }}</small>
                  </div>
                  <div class="rating-date text-muted" style="font-size: 0.85rem; margin-top: 5px;">
                    <small>{{ optional($serviceRating['latest_rating_date'])->diffForHumans() }}</small>
                  </div>
                </div>
              </div>
            @endforeach
          </div>
        @else
          <div class="rating-empty">
            <strong>No service ratings yet.</strong> Once customers start rating our services, you'll see customer feedback per service here.
          </div>
        @endif
      </div>
    </section>

    <!-- About Us Section -->
    <section id="about" class="about-section">
      <div class="container">
        <h2 class="section-title">About Smarthands</h2>
        <div class="row justify-content-center">
          <div class="col-lg-8">
            <div class="info-card mb-4" style="background-color: #cbf3f0;">
              <div class="about-content">
                {{ $contactInfo->about_content ?? 'Welcome to Smarthands Cleaning Services, your trusted partner in professional cleaning solutions.' }}
              </div>
            </div>

            <div class="info-card mb-4" style="background-color: #cbf3f0;">
              <h3 class="mb-4">Our Mission</h3>
              <div class="mission-content">
                {{ $contactInfo->mission ?? 'At SMARTHANDS, our mission is to bring world-class cleaning standards and on-call service systems inspired by our experience in Middle East  not just in Bacolod City but also in nearby areas within our service coverage.
We aim to make life easier for busy individuals and families by offering affordable, reliable, and professional cleaning services.
We are committed to providing a modern, hassle-free booking system that gives convenience and comfort — making cleaning services more accessible to everyone in Bacolod and nearby cities.
We dream of helping build a happy, clean, and growing community.
' }}
              </div>
            </div>

            <div class="info-card mb-4" style="background-color: #cbf3f0;">
              <h3 class="mb-4">Our Vision</h3>
              <div class="vision-content">
                {{ $contactInfo->vision ?? 'Our vision is to be a leading cleaning service in Bacolod and nearby cities  known not only for excellence and trust but also for our heart to uplift others.
We dream of building a community where hardworking people  especially mothers can find purpose, confidence, and financial independence through honest work.
Beyond cleaning, we are committed to empowering local workers by creating job opportunities, teaching valuable skills and trainings, and showing that they can earn and grow without leaving the country to work overseas.
With SmartHands, we don’t just clean spaces and homes — we aim to change lives, one opportunity at a time.
' }}
              </div>
            </div>

            <div class="info-card" style="background-color: #cbf3f0;">
              <h3 class="mb-4">Services We Offer</h3>
              <div class="services-content">
                {{ $contactInfo->services_offered ?? 'We offer a comprehensive range of cleaning services including residential cleaning, commercial cleaning, deep cleaning, and specialized cleaning solutions.' }}
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- Contact Section -->
    <section id="contact" class="contact-section">
      <div class="container">
        <h2 class="section-title">Contact Us</h2>
        <div class="row justify-content-center">
  <!-- Right: Message Us Form -->
  <div class="col-lg-8 d-flex flex-column justify-content-center">
    <div class="contact-card h-100 d-flex flex-column justify-content-center">
      
      <form method="POST" action="{{ route('contact.message.store') }}" id="messageUsForm">
        @csrf
        <div class="mb-3">
          <label for="contact_name" class="form-label">Name *</label>
          <input type="text" class="form-control" id="contact_name" name="name" required>
        </div>
        <div class="mb-3">
          <label for="contact_email" class="form-label">Email *</label>
          <input type="email" class="form-control" id="contact_email" name="email" required>
        </div>
        <div class="mb-3">
          <label for="contact_phone" class="form-label">Phone</label>
          <input type="text" class="form-control" id="contact_phone" name="phone">
        </div>
        <div class="mb-3">
          <label for="contact_message" class="form-label">Message *</label>
          <textarea class="form-control" id="contact_message" name="message" rows="4" required></textarea>
        </div>
        <div class="mb-3">
          <div class="g-recaptcha" data-sitekey="{{ config('services.recaptcha.key') }}"></div>
          @if($errors->has('g-recaptcha-response'))
            <div class="text-danger small mt-1">{{ $errors->first('g-recaptcha-response') }}</div>
          @endif
        </div>
        <button type="submit" class="btn btn-success w-100" id="sendMessageBtn">Send Message</button>
      </form>
      @if($errors->any())
        <div class="alert alert-danger mt-3">
          <ul class="mb-0">
            @foreach($errors->all() as $error)
              <li>{{ $error }}</li>
            @endforeach
          </ul>
        </div>
      @endif
    </div>
  </div>
</div>
      </div>
    </section>

    <!-- Booking Modal - Only visible to authenticated users -->
    @auth
    <div class="modal fade" id="bookingModal" tabindex="-1" aria-labelledby="bookingModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
          <div class="modal-header" style="background-color: #ff9f1c;">
            <h5 class="modal-title fw-bold" id="bookingModalLabel">Book your Cleaning Service</h5>
            <div class="selected-service-name fw-bold text-success d-none"></div>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>

          @if($errors->any())
            <div class="alert alert-danger m-3">
              <ul class="mb-0">
                @foreach($errors->all() as $error)
                  <li>{{ $error }}</li>
                @endforeach
              </ul>
            </div>
          @endif

          <form action="{{ route('public.bookings.store') }}" method="POST" id="bookingForm">
            @csrf
            <div class="modal-body px-4">
              <div class="mb-3">
                <label class="form-label fw-bold">Selected Service</label>
                <div class="p-3 bg-light rounded border">
                  <div class="service-title h6 mb-2"></div>
                  <div class="service-price text-success"></div>
                </div>
                <input type="hidden" name="service_id" id="service_id">
              </div>
              <div class="mb-3">
                <label for="name" class="form-label">Name: *</label>
                <input type="text" id="name" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" required>
                @error('name')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>
              <div class="mb-3">
                <label for="contact" class="form-label">Contact: *</label>
                <input type="tel" id="contact" name="contact" class="form-control @error('contact') is-invalid @enderror" value="{{ old('contact') }}" required>
                @error('contact')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>
              <div class="mb-3">
                <label for="email" class="form-label">Email: *</label>
                <input type="email" id="email" name="email" class="form-control @error('email') is-invalid @enderror" value="@auth{{ Auth::user()->email }}@else{{ old('email') }}@endauth" @auth readonly @endauth required>
                @error('email')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>
              <div class="mb-3">
                <label class="form-label">Address Details:</label>
                <div class="row g-2">
                  <div class="col-md-6">
                    <input type="text" id="block" name="block" placeholder="Block Number" class="form-control @error('block') is-invalid @enderror" value="{{ old('block') }}">
                    @error('block')
                      <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                  </div>
                  <div class="col-md-6">
                    <input type="text" id="lot" name="lot" placeholder="Lot Number" class="form-control @error('lot') is-invalid @enderror" value="{{ old('lot') }}">
                    @error('lot')
                      <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                  </div>
                </div>
                <div class="mt-2">
                  <input type="text" id="street" name="street" placeholder="Street Name *" class="form-control @error('street') is-invalid @enderror" value="{{ old('street') }}" required>
                  @error('street')
                    <div class="invalid-feedback">{{ $message }}</div>
                  @enderror
                </div>
                <div class="mt-2">
                  <input type="text" id="subdivision" name="subdivision" placeholder="Subdivision/Village (if applicable)" class="form-control @error('subdivision') is-invalid @enderror" value="{{ old('subdivision') }}">
                  @error('subdivision')
                    <div class="invalid-feedback">{{ $message }}</div>
                  @enderror
                </div>
                <div class="mt-2">
                  <select id="city" name="city" class="form-select @error('city') is-invalid @enderror" required>
                    <option value="">Select City/Municipality *</option>
                    <option value="Bacolod">Bacolod</option>
                    <option value="Bago">Bago</option>
                    <option value="Binalbagan">Binalbagan</option>
                    <option value="Cadiz">Cadiz</option>
                    <option value="E.B. Magalona">E.B. Magalona</option>
                    <option value="Escalante">Escalante</option>
                    <option value="Hinigaran">Hinigaran</option>
                    <option value="Manapla">Manapla</option>
                    <option value="Pontevedra">Pontevedra</option>
                    <option value="Pulupandan">Pulupandan</option>
                    <option value="Sagay City">Sagay City</option>
                    <option value="San Enrique">San Enrique</option>
                    <option value="Silay">Silay</option>
                    <option value="Talisay">Talisay</option>
                    <option value="Valladolid">Valladolid</option>
                    <option value="Victorias">Victorias</option>
                  </select>
                  @error('city')
                    <div class="invalid-feedback">{{ $message }}</div>
                  @enderror
                </div>
                <div class="mt-2">
                  <select id="barangay" name="barangay" class="form-select @error('barangay') is-invalid @enderror" required disabled>
                    <option value="">Select Barangay *</option>
                  </select>
                  @error('barangay')
                    <div class="invalid-feedback">{{ $message }}</div>
                  @enderror
                </div>
                <div class="row g-2 mt-2">
                  <div class="col-md-12">
                    <input type="text" id="zip_code" name="zip_code" placeholder="Zip Code *" class="form-control @error('zip_code') is-invalid @enderror" value="{{ old('zip_code') }}" required>
                    @error('zip_code')
                      <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                  </div>
                </div>
                <!-- Hidden input for combined address -->
                <input type="hidden" name="address" id="address">
                @error('address')
                  <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror
              </div>
              <div class="mb-3">
                <label class="form-label">Cleaning Date and Time: *</label>
                <div class="row g-2">
                  <div class="col-md-6">
                    <input type="date" 
                           id="cleaning_date" 
                           name="cleaning_date" 
                           class="form-control @error('cleaning_date') is-invalid @enderror" 
                           value="{{ old('cleaning_date') }}"
                           min="{{ now()->addDays(2)->toDateString() }}"
                           placeholder="Select Date" required>
                    <div id="fullyBookedAlert" class="alert alert-warning mt-2" role="alert" style="display:none;">
                      <i class="bi bi-exclamation-triangle-fill"></i>
                      
                    </div>
                    <div id="loadingDates" class="mt-2 text-info" style="display:none;">
                      <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                      Loading available dates...
                    </div>
                  </div>

                  <div class="col-md-6">
                    <select id="cleaning_time" 
                            name="cleaning_time" 
                            class="form-select @error('cleaning_time') is-invalid @enderror" 
                            required>
                      <option value="">Select Time</option>
                      <option value="09:00">9:00 AM</option>
                      <option value="10:00">10:00 AM</option>
                      <option value="11:00">11:00 AM</option>
                      <option value="12:00">12:00 PM</option>
                      <option value="13:00">1:00 PM</option>
                      <option value="14:00">2:00 PM</option>
                      <option value="15:00">3:00 PM</option>
                    </select>
                  </div>
                </div>
                @error('cleaning_date')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
                @error('cleaning_time')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>
              
              <!-- Hidden field for token -->
              <input type="hidden" name="booking_token" id="booking_token">
            <!-- Hidden field for client timezone offset -->
            <input type="hidden" name="client_timezone_offset" id="client_timezone_offset" value="0">
            </div>
            <div class="modal-footer d-block text-center">
              <!-- Total Initial Price -->
              <div class="alert alert-warning w-100 mb-2 d-none" role="alert" id="totalInitialPriceAlert"></div>

              <!-- Payment Note -->
              <div class="alert alert-info w-100 mb-2" role="alert">
                <strong>Cancellation & Rescheduling Policy</strong>
                <ul class="mb-0 mt-2" style="text-align: left;">
                  <li>A 50% down payment is required to secure your booking</li>
                  <li>Cancellations or rescheduling must be made at least 48 hours before the scheduled service</li>
                  <li>If cancellation or rescheduling is made less than 48 hours before the service, the 50% down payment will be forfeited and considered non-refundable</li>
                  <li>This policy is in place to cover the loss of reserved schedules and preparation costs</li>
                  <li>The balance must be paid immediately upon completion of service</li>
                  <li>Payments may be made via cash, GCash, or bank transfer</li>
                  <li>The manager will contact you regarding the payment</li>
                </ul>
              </div>
              <div class="d-flex justify-content-center gap-3">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" style="width: 150px;"><i class="bi bi-x-circle me-2"></i>Cancel</button>
                <button type="submit" class="btn btn-success" style="width: 200px;"><strong>Confirm Booking</strong></button>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
    @endauth
    @auth
    @php
        $user = Auth::user();
    @endphp
    
    @endauth
    <!-- Add Validation Error Modal -->
    <div class="modal fade" id="validationErrorModal" tabindex="-1" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
          <div class="modal-header bg-danger text-white">
            <h5 class="modal-title">Form Validation Errors</h5>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <ul class="list-unstyled" id="validationErrorList">
            </ul>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          </div>
        </div>
      </div>
    </div>
  <!-- Guest Booking Modal -->
<div class="modal fade" id="guestBookingModal" tabindex="-1" aria-labelledby="guestBookingModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header" style="background-color: #ff9f1c;">
        <h5 class="modal-title fw-bold" id="guestBookingModalLabel">Login or Register Required</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body text-center">
        <p class="mb-4">You must login or register before you can make a booking.</p>
        <a href="{{ route('login') }}" class="btn me-2" style="background-color: #cbf3f0; transition: background-color 0.3s ease;" onmouseover="this.style.backgroundColor='#6ceae0'" onmouseout="this.style.backgroundColor='#cbf3f0'">Login</a>
        <a href="{{ route('register') }}" class="btn btn-success">Register</a>
      </div>
    </div>
  </div>
</div>
<!-- Profile Modal -->
@if(Auth::check())
<div class="modal fade" id="profileModal" tabindex="-1" aria-labelledby="profileModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="profileModalLabel">Your Profile</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form id="profileForm" method="POST" action="{{ url('/customer/profile') }}">
        @csrf
        <div id="profileSuccessAlert" class="alert alert-success d-none" role="alert"></div>
        <div class="modal-body">
            <div class="mb-3">
                <label for="name" class="form-label">Name</label>
                <input type="text" class="form-control" id="profile_name" name="name" value="{{ Auth::user()->name ?? '' }}">
                <div class="invalid-feedback" id="error-name"></div>
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" id="profile_email" name="email" value="{{ Auth::user()->email ?? '' }}">
                <div class="invalid-feedback" id="error-email"></div>
            </div>
            <hr>
            <h6 class="mb-3">Change Password:</h6>
            <div class="mb-3">
                <label for="current_password" class="form-label">Current Password</label>
                <div class="input-group">
                    <input type="password" class="form-control" id="current_password" name="current_password" autocomplete="current-password">
                    <button class="btn btn-outline-secondary toggle-password" type="button" data-target="#current_password" tabindex="-1">
                        <i class="bi bi-eye" id="icon-current_password"></i>
                    </button>
                </div>
                <div class="invalid-feedback" id="error-current_password"></div>
                <small class="text-muted">Enter your current login password to verify changes.</small>
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
        <div class="modal-footer d-flex justify-content-between align-items-center flex-wrap gap-2">
          <button type="button" class="btn btn-outline-danger" id="openLogoutConfirm">
            <i class="bi bi-box-arrow-right me-1"></i> Logout
          </button>
          <div class="d-flex gap-2">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-success">Save Changes</button>
          </div>
        </div>
      </form>
    </div>
  </div>
</div>
@endif

<!-- Removed duplicate early include of home_custom.js to ensure Flatpickr is loaded before initialization -->
<script>
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
</script>
<!-- All scripts moved to public/js/home_custom.js -->

</main>
  
  <!-- Logout Form -->
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
      const openLogoutBtn = document.getElementById('openLogoutConfirm');
      if (openLogoutBtn) {
        openLogoutBtn.addEventListener('click', function () {
          const profileModalEl = document.getElementById('profileModal');
          if (profileModalEl && typeof bootstrap !== 'undefined') {
            const profileModalInstance = bootstrap.Modal.getInstance(profileModalEl) || new bootstrap.Modal(profileModalEl);
            profileModalInstance.hide();
          }

          const logoutModalEl = document.getElementById('logoutConfirmModal');
          if (logoutModalEl && typeof bootstrap !== 'undefined') {
            const logoutModalInstance = bootstrap.Modal.getOrCreateInstance(logoutModalEl);
            logoutModalInstance.show();
          }
        });
      }
      if (confirmBtn) {
        confirmBtn.addEventListener('click', function () {
          const form = document.getElementById('logout-form');
          if (form) form.submit();
        });
      }
    });
  </script>
  
  <style>
    /* Custom Flatpickr styles for a more colorful and user-friendly calendar */
    .flatpickr-calendar {
      border-radius: 8px;
      box-shadow: 0 6px 20px rgba(0, 0, 0, 0.15);
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

    .flatpickr-months .flatpickr-month {
      background-color: #007bff; /* Primary blue for month header */
      color: white;
      fill: white;
    }

    .flatpickr-current-month .flatpickr-monthDropdown-months .flatpickr-monthItem.active,
    .flatpickr-current-month .flatpickr-monthDropdown-months .flatpickr-monthItem:hover {
      background-color: #0056b3; /* Darker blue on hover/active */
    }

    .flatpickr-current-month .flatpickr-monthDropdown-months .flatpickr-monthItem {
      color: #333; /* Default text color for month dropdown */
    }

    .flatpickr-day.selected,
    .flatpickr-day.startRange,
    .flatpickr-day.endRange,
    .flatpickr-day.selected.inRange,
    .flatpickr-day.startRange.inRange,
    .flatpickr-day.endRange.inRange,
    .flatpickr-day.selected:focus,
    .flatpickr-day.startRange:focus,
    .flatpickr-day.endRange:focus,
    .flatpickr-day.selected:hover,
    .flatpickr-day.startRange:hover,
    .flatpickr-day.endRange:hover,
    .flatpickr-day.selected.prevMonthDay,
    .flatpickr-day.startRange.prevMonthDay,
    .flatpickr-day.endRange.prevMonthDay,
    .flatpickr-day.selected.nextMonthDay,
    .flatpickr-day.startRange.nextMonthDay,
    .flatpickr-day.endRange.nextMonthDay {
      background-color: #28a745; /* Green for selected dates */
      border-color: #28a745;
      color: white;
    }

    .flatpickr-day.today {
      border-color: #ffc107; /* Yellow for today's date */
      color: #ffc107;
    }

    .flatpickr-day.today:hover,
    .flatpickr-day.today.selected {
      background-color: #ffc107;
      color: white;
    }

    .flatpickr-day.disabled,
    .flatpickr-day.disabled:hover {
      color: #ccc;
      background-color: #f5f5f5;
      cursor: not-allowed;
    }

    .flatpickr-day.fully-booked-date {
      background-color: #f8d7da; /* Light red for fully booked dates */
      color: #721c24;
      cursor: not-allowed;
      text-decoration: line-through;
    }

    .flatpickr-day.fully-booked-date:hover {
      background-color: #f5c6cb;
    }

    .flatpickr-day.inRange {
      background-color: #e9ecef; /* Light gray for dates in range */
      box-shadow: none;
      border-color: #e9ecef;
    }

    .flatpickr-day.flatpickr-disabled {
      color: #d3d3d3;
    }

    .flatpickr-day.flatpickr-disabled:hover {
      background-color: transparent;
    }

    .flatpickr-weekdays {
      background-color: #f8f9fa; /* Light background for weekdays */
    }

    .flatpickr-weekday {
      color: #6c757d; /* Gray text for weekdays */
    }

    .flatpickr-time {
      border-top: 1px solid #eee;
    }

    .flatpickr-time input.flatpickr-hour,
    .flatpickr-time input.flatpickr-minute,
    .flatpickr-time input.flatpickr-second {
      font-weight: bold;
    }

    .flatpickr-time .flatpickr-am-pm {
      font-weight: bold;
      color: #007bff;
    }
  </style>

  <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
  <script src="{{ asset('js/app.js') }}"></script>
  <script src="{{ asset('js/home_custom.js') }}"></script>

@auth
    @include('partials.my_bookings_modal', ['user' => Auth::user()])
@endauth
  <!-- Footer -->
  <x-footer :contactInfo="$contactInfo" />


  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script src="{{ asset('js/fade-effect.js') }}"></script>
  
  <!-- Smooth Scrolling -->
  <script>
    document.querySelectorAll('a[href^="#"]').forEach(link => {
      link.addEventListener('click', function(e) {
        const href = this.getAttribute('href');
        if (href === '#' || this.hasAttribute('data-bs-toggle')) return;
        
        e.preventDefault();
        const target = document.getElementById(href.substring(1));
        if (target) {
          const navHeight = document.querySelector('.navbar').offsetHeight;
          window.scrollTo({
            top: target.offsetTop - navHeight - 20,
            behavior: 'smooth'
          });
        }
      });
    });
    
    // Handle contact form submission to prevent multiple submissions
    document.addEventListener('DOMContentLoaded', function() {
      const messageForm = document.getElementById('messageUsForm');
      const submitButton = document.getElementById('sendMessageBtn');
      const successMessageContainer = document.createElement('div');
      
      if (messageForm && submitButton) {
        // Insert success message container after the form
        messageForm.parentNode.insertBefore(successMessageContainer, messageForm.nextSibling);
        
        let isSubmitting = false;
        
        messageForm.addEventListener('submit', function(e) {
          e.preventDefault(); // Prevent default form submission

          if (isSubmitting) {
            return false;
          }

          isSubmitting = true;
          submitButton.disabled = true;
          const originalButtonText = submitButton.textContent;
          submitButton.textContent = 'Sending...';

          // Clear previous messages
          successMessageContainer.innerHTML = '';

          // Get form data
          const formData = new FormData(messageForm);

          // Submit form via AJAX
          fetch(messageForm.action, {
            method: 'POST',
            body: formData,
            headers: {
              'X-Requested-With': 'XMLHttpRequest',
              'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
              'Accept': 'application/json'
            }
          })
          .then(response => {
            if (!response.ok) {
              throw new Error('Network response was not ok');
            }
            return response.json();
          })
          .then(data => {
            if (data.success) {
              // Show success message and reset form without page reload
              successMessageContainer.innerHTML = `<div class="alert alert-success mt-3">${data.message}</div>`;
              messageForm.reset();
              
              // Reset reCAPTCHA
              if (typeof grecaptcha !== 'undefined') {
                grecaptcha.reset();
              }
              
              // Keep user at current scroll position
              window.scrollTo({
                top: window.pageYOffset,
                behavior: 'instant'
              });
            } else {
              // Show error message
              successMessageContainer.innerHTML = `<div class="alert alert-danger mt-3">${data.message || 'Please complete the reCAPTCHA verification and try again.'}</div>`;
            }
          })
          .catch(error => {
            console.error('Error:', error);
            // Show error message without page reload
            successMessageContainer.innerHTML = `<div class="alert alert-danger mt-3">Please complete the reCAPTCHA verification and try again.</div>`;
          })
          .finally(() => {
            isSubmitting = false;
            submitButton.disabled = false;
            submitButton.textContent = originalButtonText;
          });
        });
      }
    });
  </script>

  <script>
    document.addEventListener('DOMContentLoaded', async function() { // Made DOMContentLoaded listener async
      const cleaningDateInput = document.getElementById('cleaning_date');
      const fullyBookedAlert = document.getElementById('fullyBookedAlert');
      const loadingDates = document.getElementById('loadingDates');
      const bookingModal = document.getElementById('bookingModal'); // Get the modal element

      let fullyBookedDates = [];
      let fpInstance; // Declare flatpickr instance globally within this scope
      let fullyBookedTimes = {};

      // Function to fetch fully booked dates
      async function fetchFullyBookedDates() {
        if (loadingDates) {
          loadingDates.style.display = 'block';
        }
        try {
          const response = await fetch('{{ route('fully.booked.dates') }}?context=home');
          if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
          }
          const data = await response.json();
          fullyBookedDates = data.fullyBookedDates || [];
          fullyBookedTimes = data.fullyBookedTimes || {};
          console.log('Fully booked dates:', fullyBookedDates);
          console.log('Fully booked times:', fullyBookedTimes);
        } catch (error) {
          console.error('Error fetching fully booked dates:', error);
          // Optionally, show an error message to the user
        } finally {
          if (loadingDates) {
            loadingDates.style.display = 'none';
          }
        }
      }

      // Initial fetch and Flatpickr setup
      await fetchFullyBookedDates(); // Wait for dates to be fetched before initializing Flatpickr

      // Initialize Flatpickr after dates are fetched
      fpInstance = flatpickr(cleaningDateInput, {
        dateFormat: "Y-m-d",
        minDate: "today",
        disable: fullyBookedDates,
        onDayCreate: function(dObj, dStr, fp, dayElem) {
          // Add a class to fully booked dates for styling
          const dateString = flatpickr.formatDate(dayElem.dateObj, "Y-m-d");
          if (fullyBookedDates.includes(dateString)) {
            dayElem.classList.add('fully-booked-date');
          }
        },
        onChange: function(selectedDates, dateStr, instance) {
          // Hide alert if a new date is selected
          if (fullyBookedAlert) {
            fullyBookedAlert.style.display = 'none';
          }
          // If the selected date is fully booked, show alert
          if (fullyBookedDates.includes(dateStr)) {
            if (fullyBookedAlert) {
              fullyBookedAlert.style.display = 'block';
            }
          } else {
            if (fullyBookedAlert) {
              fullyBookedAlert.style.display = 'none';
            }
          }

          // Logic to handle time slot availability based on fullyBookedTimes
          const cleaningTimeSelect = document.getElementById('cleaning_time');
          const timesForSelectedDay = fullyBookedTimes[dateStr] || [];

          // Enable all time options first
          Array.from(cleaningTimeSelect.options).forEach(option => {
            if (option.value !== "") {
              option.disabled = false;
            }
          });

          // Disable times that are fully booked for the selected day
          timesForSelectedDay.forEach(time => {
            const option = cleaningTimeSelect.querySelector(`option[value="${time}"]`);
            if (option) {
              option.disabled = true;
            }
          });

          // If the currently selected time is disabled, reset to default
          if (cleaningTimeSelect.value && cleaningTimeSelect.options[cleaningTimeSelect.selectedIndex].disabled) {
            cleaningTimeSelect.value = "";
          }
        }
       });

       // Set initial date if available (from old('cleaning_date'))
       const initialDateValue = cleaningDateInput.value;
       if (initialDateValue) {
           fpInstance.setDate(initialDateValue);
           // Manually trigger onChange for initial date to update times
           fpInstance.config.onChange[0]([fpInstance.parseDate(initialDateValue)], initialDateValue, fpInstance);
       }

       // Re-fetch and update Flatpickr when the calendar input gains focus
      cleaningDateInput.addEventListener('focus', async () => {
        await fetchFullyBookedDates();
        // Update the disable option and redraw
        if (fpInstance) { // Ensure fpInstance exists before setting options
          fpInstance.set('disable', [
            function(date) {
              return fullyBookedDates.includes(flatpickr.formatDate(date, "Y-m-d"));
            }
          ]);
          fpInstance.redraw(); // Redraw the calendar to apply new disabled dates
          // Also refresh time slots for the currently selected date
          const currentDateStr = cleaningDateInput.value || (fpInstance.selectedDates[0] ? flatpickr.formatDate(fpInstance.selectedDates[0], "Y-m-d") : "");
          if (currentDateStr) {
            // Invoke the existing onChange handler to update times
            fpInstance.config.onChange[0](fpInstance.selectedDates, currentDateStr, fpInstance);
          }
        }
      });

      // Re-fetch and update Flatpickr when the booking modal is shown
      if (bookingModal) {
        bookingModal.addEventListener('shown.bs.modal', async function () {
          await fetchFullyBookedDates();
          if (fpInstance) { // Ensure fpInstance exists
            fpInstance.set('disable', [
              function(date) {
                return fullyBookedDates.includes(flatpickr.formatDate(date, "Y-m-d"));
              }
            ]);
            fpInstance.redraw();
            // Also refresh time slots for the currently selected date when modal opens
            const currentDateStr = cleaningDateInput.value || (fpInstance.selectedDates[0] ? flatpickr.formatDate(fpInstance.selectedDates[0], "Y-m-d") : "");
            if (currentDateStr) {
              fpInstance.config.onChange[0](fpInstance.selectedDates, currentDateStr, fpInstance);
            }
          }
        });
      }
    });
  </script>

  @if(session('open_profile_modal'))
  <script>
    document.addEventListener('DOMContentLoaded', function() {
      const profileModal = document.getElementById('profileModal');
      if (profileModal) {
        const modal = new bootstrap.Modal(profileModal);
        modal.show();
      }
    });
  </script>
  @endif
</body>
</html>
