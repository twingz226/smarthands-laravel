<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>Smarthands Cleaning Services</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
  <link rel="stylesheet" href="{{ asset('css/style.css') }}">
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

    /* Hero section */
    .hero-section {
      height: auto;
      min-height: 100vh;
      display: flex;
      flex-direction: column;
      align-items: center;
      padding: 80px 5% 40px;
      position: relative;
      z-index: 1;
    }
    .hero-content {
      width: 100%;
      text-align: center;
      margin-bottom: 40px;
      margin-top: 0;
    }
    .hero-image {
      width: 100%;
      height: 50vh;
      min-height: 300px;
      background-image: url('https://plus.unsplash.com/premium_photo-1678980766527-b33a383238ae?q=80&w=1479&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D');
      background-size: cover;
      background-position: center;
      border-radius: 8px;
      box-shadow: 0 15px 30px rgba(0, 0, 0, 0.1);
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
    .book-btn {
      padding: 12px 30px;
      font-size: 1.1rem;
      background-color: #f8f9fa;
      color: #333;
      border: 2px solid #333;
      border-radius: 30px;
      cursor: pointer;
      transition: all 0.3s ease;
      margin-bottom: 30px;
    }
    .book-btn:hover {
      background-color: #111c5d;
      color: white;
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
    .services-grid {
      display: flex;
      flex-wrap: wrap;
      justify-content: center;
      gap: 30px;
      margin-bottom: 30px;
    }
    .service-box {
      width: 100%;
      max-width: 400px;
      min-width: 250px;
      height: 250px;
      background-color: #f8f9fa;
      border-radius: 8px;
      box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
      overflow: hidden;
      position: relative;
      transition: all 0.3s ease;
    }
    .service-box:hover {
      transform: translateY(-10px);
      box-shadow: 0 15px 30px rgba(0, 0, 0, 0.1);
    }
    .service-box img {
      width: 100%;
      height: 100%;
      object-fit: cover;
      transition: all 0.5s ease;
    }
    .service-box:hover img {
      transform: scale(1.05);
    }
    .service-content {
      position: absolute;
      bottom: 0;
      left: 0;
      right: 0;
      background: rgba(0, 0, 0, 0.7);
      color: white;
      padding: 15px;
      transform: translateY(100%);
      transition: all 0.3s ease;
    }
    .service-box:hover .service-content {
      transform: translateY(0);
    }
    .service-title {
      font-size: 1.3rem;
      margin-bottom: 8px;
    }
    .service-description {
      font-size: 0.85rem;
      opacity: 0.9;
    }
    .service-price {
      font-size: 0.9rem;
      margin-bottom: 10px;
      font-weight: bold;
    }

    /* Success Alert */
    .alert-success {
      position: fixed;
      top: 20px;
      right: 20px;
      z-index: 9999;
      animation: fadeInOut 5s ease-in-out;
    }

    @keyframes fadeInOut {
      0% { opacity: 0; }
      10% { opacity: 1; }
      90% { opacity: 1; }
      100% { opacity: 0; }
    }

    /* Responsive Styles */
    @media (min-width: 768px) {
      .hero-section {
        flex-direction: row;
        align-items: center;
        padding: 0 5%;
      }
      .hero-content {
        width: 40%;
        text-align: left;
        margin-bottom: 0;
        margin-top: -100px;
      }
      .hero-image {
        width: 55%;
        height: 60vh;
        margin-left: 5%;
      }
      .main-headline {
        font-size: 3rem;
      }
      .tagline {
        font-size: 1.3rem;
      }
      .services-tagline {
        font-size: 2.5rem;
        margin-bottom: 60px;
      }
      .service-box {
        width: 45%;
        height: 280px;
      }
    }

    @media (min-width: 992px) {
      .hero-content {
        width: 30%;
      }
      .hero-image {
        width: 60%;
        height: 70vh;
      }
      .service-box {
        width: 30%;
        height: 300px;
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
    <section class="hero-section">
      <div class="hero-content">
        <h1 class="main-headline">Smarthands Cleaning Services</h1>
        <p class="tagline">Where Simplicity Meets Spotless Results</p>
      </div>
      <div class="hero-image"></div>
    </section>

    <!-- Services Section -->
    <section class="services-section">
      <h2 class="services-tagline">Our Services</h2>

      <div class="services-grid">
        <!-- Service 1 -->
        <div class="service-box">
          <img src="{{ asset('images/service1.jpg') }}" alt="Customized Deep Cleaning">
          <div class="service-content">
            <h3 class="service-title">Customized Deep Cleaning</h3>
            <p class="service-description">Tailored cleaning solutions to meet your specific needs and preferences.</p>
            <p class="service-price">Price: ₱299/hr minimum of 6 hours</p>
            <button class="book-btn service-book-btn" data-bs-toggle="modal" data-bs-target="#bookingModal">Book Now</button>
          </div>
        </div>

        <!-- Service 2 -->
        <div class="service-box">
          <img src="{{ asset('images/service2.jpg') }}" alt="Apartment Deep Cleaning">
          <div class="service-content">
            <h3 class="service-title">Apartment Deep Cleaning</h3>
            <p class="service-description">Thorough cleaning for apartments, covering every corner and surface.</p>
            <p class="service-price">Price: ₱299/hr minimum of 6 hours</p>
            <button class="book-btn service-book-btn" data-bs-toggle="modal" data-bs-target="#bookingModal">Book Now</button>
          </div>
        </div>

        <!-- Service 3 -->
        <div class="service-box">
          <img src="{{ asset('images/service3.jpg') }}" alt="House Deep Cleaning">
          <div class="service-content">
            <h3 class="service-title">2-Story/Bungalow House Cleaning</h3>
            <p class="service-description">Comprehensive cleaning for larger homes with attention to all levels.</p>
            <p class="service-price">Price: ₱75/sqm </p>
            <button class="book-btn service-book-btn" data-bs-toggle="modal" data-bs-target="#bookingModal">Book Now</button>
          </div>
        </div>

        <!-- Service 4 -->
        <div class="service-box">
          <img src="{{ asset('images/service4.jpg') }}" alt="Move-in/Move-out Cleaning">
          <div class="service-content">
            <h3 class="service-title">Move-in/Move-out Cleaning</h3>
            <p class="service-description">Make your transition smooth with our professional move cleaning services.</p>
            <p class="service-price">Price: ₱75/sqm minimum of 6 hours</p>
            <button class="book-btn service-book-btn" data-bs-toggle="modal" data-bs-target="#bookingModal">Book Now</button>
          </div>
        </div>

        <!-- Service 5 -->
        <div class="service-box">
          <img src="{{ asset('images/service5.jpg') }}" alt="Post-Construction Cleaning">
          <div class="service-content">
            <h3 class="service-title">Post-Construction/Renovation Cleaning</h3>
            <p class="service-description">Specialized cleaning to remove construction dust and debris.</p>
            <p class="service-price">Price: ₱75/sqm</p>
            <button class="book-btn service-book-btn" data-bs-toggle="modal" data-bs-target="#bookingModal">Book Now</button>
          </div>
        </div>
      </div>
    </section>

    <!-- Booking Modal -->
    <div class="modal fade" id="bookingModal" tabindex="-1" aria-labelledby="bookingModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
          <div class="modal-header bg-warning">
            <h5 class="modal-title fw-bold" id="bookingModalLabel">Book your Cleaning Service</h5>
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

          <form action="/" method="POST" id="bookingForm">
            @csrf
            <div class="modal-body px-4">
              <div class="mb-3">
                <label for="name" class="form-label">Name:</label>
                <input type="text" id="name" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" required>
                @error('name')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>
              <div class="mb-3">
                <label for="contact" class="form-label">Contact:</label>
                <input type="tel" id="contact" name="contact" class="form-control @error('contact') is-invalid @enderror" value="{{ old('contact') }}" required>
                @error('contact')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>
              <div class="mb-3">
                <label for="email" class="form-label">Email:</label>
                <input type="email" id="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}" required>
                @error('email')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>
              <div class="mb-3">
                <label for="address" class="form-label">Address:</label>
                <textarea id="address" name="address" class="form-control @error('address') is-invalid @enderror" required>{{ old('address') }}</textarea>
                @error('address')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>
              <div class="mb-3">
                <label for="service_id" class="form-label">Service Type:</label>
                <select name="service_id" id="service_id" class="form-select @error('service_id') is-invalid @enderror" required>
                  <option value="">Select a service</option>
                  @foreach($services as $service)
                    <option value="{{ $service->id }}">
                      {{ $service->name }} - {{ $service->pricing_type === 'sqm' ? '₱' . number_format($service->price, 2) . '/sqm' : '₱' . number_format($service->price, 2) . ' for ' . $service->duration_minutes . ' mins' }}
                    </option>
                  @endforeach
                </select>
                @error('service_id')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>
              <div class="mb-3">
                <label for="cleaning_date" class="form-label">Cleaning Date and Time:</label>
                <input type="datetime-local" id="cleaning_date" name="cleaning_date" class="form-control @error('cleaning_date') is-invalid @enderror" value="{{ old('cleaning_date') }}" required>
                @error('cleaning_date')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>
              
              <!-- Hidden fields for calculated values -->
              <input type="hidden" name="booking_token" id="bookingToken">
              <input type="hidden" name="duration" id="durationField">
              <input type="hidden" name="price" id="priceField">
            </div>
            <div class="modal-footer">
              <button type="submit" class="btn btn-success w-100">Confirm Booking</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </main>
  

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script>
  document.addEventListener('DOMContentLoaded', function () {
    console.log('DOM fully loaded and parsed');

    // Get services data from PHP
    const services = @json($services);
    console.log('Services:', services);

    document.getElementById('bookingForm').addEventListener('submit', function (event) {
      event.preventDefault();

      console.log('Booking form submitted');

      // Generate token and set fields
      const token = Math.random().toString(36).substring(2, 15) +
                  Math.random().toString(36).substring(2, 15);
      document.getElementById('bookingToken').value = token;

      const serviceId = document.getElementById('service_id').value;
      const service = services.find(s => s.id == serviceId);
      
      if (!service) {
        alert('Please select a service');
        return;
      }

      let duration, price;

      if (service.pricing_type === 'duration') {
        duration = service.duration_minutes / 60; // Convert to hours
        price = service.price;
      } else {
        // For sqm pricing, use default area
        duration = 8; // Default duration for sqm pricing
        const defaultArea = 100; // Default area in square meters
        price = service.price * defaultArea;
      }

      document.getElementById('durationField').value = duration;
      document.getElementById('priceField').value = price;

      // Get the CSRF token from the meta tag
      const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

      // Gather form data
      const formData = new FormData(this);

      // Send data via fetch to your backend endpoint
      fetch(this.action, {
        method: this.method,
        body: formData,
        headers: {
          'X-Requested-With': 'XMLHttpRequest',
          'Accept': 'application/json',
          'X-CSRF-TOKEN': csrfToken
        },
        credentials: 'same-origin'
      })
      .then(response => {
        console.log('Response status:', response.status);
        if (!response.ok) {
          return response.json().then(json => {
            console.error('Response error:', json);
            return Promise.reject(json);
          });
        }
        return response.json();
      })
      .then(data => {
        console.log('Server response:', data);
        if(data.success) {
          window.location.href = '/booking/success';
        } else {
          alert('Failed to save booking: ' + (data.message || 'Unknown error'));
        }
      })
      .catch(error => {
        console.error('Error submitting booking:', error);
        if (error.errors) {
          // Handle validation errors
          const errorMessages = Object.values(error.errors).flat().join('\n');
          alert('Validation errors:\n' + errorMessages);
        } else {
          alert('Error submitting booking. Please try again.');
        }
      });

      // Log form data for debugging
      console.log('Form data being sent:');
      for (let pair of formData.entries()) {
        console.log(pair[0] + ': ' + pair[1]);
      }
    });

    document.getElementById('cleaning_date').addEventListener('change', function () {
      console.log('Cleaning date changed:', this.value);

      const selectedDate = new Date(this.value);
      const now = new Date();

      if (selectedDate < now) {
        alert('Please select a future date and time for cleaning');
        this.value = '';
        console.log('Invalid date selected, resetting input');
      } else {
        console.log('Valid date selected');
      }
    });

    document.querySelectorAll('.service-book-btn').forEach(button => {
      button.addEventListener('click', function () {
        const serviceTitle = this.closest('.service-content').querySelector('.service-title').textContent;
        console.log('Service book button clicked:', serviceTitle);

        const select = document.getElementById('service_id');
        let found = false;
        for (let i = 0; i < select.options.length; i++) {
          if (select.options[i].text.includes(serviceTitle)) {
            select.selectedIndex = i;
            found = true;
            console.log('Selected service option index:', i, 'Text:', select.options[i].text);
            break;
          }
        }
        if (!found) console.log('No matching service option found for:', serviceTitle);
      });
    });
  });
</script>

</body>
</html>