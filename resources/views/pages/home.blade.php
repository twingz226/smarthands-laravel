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
</head>
<body>
  <!-- Navbar -->
  <nav class="navbar navbar-expand-lg navbar-light custom-navbar shadow-sm sticky-top">
    <div class="container">
      <a class="navbar-brand" href="{{ route('home') }}">
        <img src="{{ asset('images/Smarthand.png') }}" alt="Logo" onerror="this.src='https://via.placeholder.com/100'">
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
      <h2 class="services-tagline">Services</h2>

      <div class="services-grid">
        <!-- Service 1 -->
        <div class="service-box">
          <img src="{{ asset('images/service1.jpg') }}" alt="Customized Deep Cleaning">
          <div class="service-content">
            <h3 class="service-title">Customized Deep Cleaning</h3>
            <p class="service-description">Tailored cleaning solutions to meet your specific needs and preferences.</p>
            <p class="service-price">Price: ₱299/hr minimum of 6 hours</p>
            <button class="book-btn service-book-btn" data-bs-toggle="modal" data-bs-target="#bookingModal">Book</button>
          </div>
        </div>

        <!-- Service 2 -->
        <div class="service-box">
          <img src="{{ asset('images/service2.jpg') }}" alt="Apartment Deep Cleaning">
          <div class="service-content">
            <h3 class="service-title">Apartment Deep Cleaning</h3>
            <p class="service-description">Thorough cleaning for apartments, covering every corner and surface.</p>
            <p class="service-price">Price: ₱299/hr minimum of 6 hours</p>
            <button class="book-btn service-book-btn" data-bs-toggle="modal" data-bs-target="#bookingModal">Book</button>
          </div>
        </div>

        <!-- Service 3 -->
        <div class="service-box">
          <img src="{{ asset('images/service3.jpg') }}" alt="House Deep Cleaning">
          <div class="service-content">
            <h3 class="service-title">2-Story/Bungalow House Cleaning</h3>
            <p class="service-description">Comprehensive cleaning for larger homes with attention to all levels.</p>
            <p class="service-price">Price: ₱75/sqm</p>
            <button class="book-btn service-book-btn" data-bs-toggle="modal" data-bs-target="#bookingModal">Book</button>
          </div>
        </div>

        <!-- Service 4 -->
        <div class="service-box">
          <img src="{{ asset('images/service4.jpg') }}" alt="Move-in/Move-out Cleaning">
          <div class="service-content">
            <h3 class="service-title">Move-in/Move-out Cleaning</h3>
            <p class="service-description">Make your transition smooth with our professional move cleaning services.</p>
            <p class="service-price">Price: ₱75/sqm minimum of 6 hours</p>
            <button class="book-btn service-book-btn" data-bs-toggle="modal" data-bs-target="#bookingModal">Book</button>
          </div>
        </div>

        <!-- Service 5 -->
        <div class="service-box">
          <img src="{{ asset('images/service5.jpg') }}" alt="Post-Construction Cleaning">
          <div class="service-content">
            <h3 class="service-title">Post-Construction/Renovation Cleaning</h3>
            <p class="service-description">Specialized cleaning to remove construction dust and debris.</p>
            <p class="service-price">Price: ₱75/sqm</p>
            <button class="book-btn service-book-btn" data-bs-toggle="modal" data-bs-target="#bookingModal">Book</button>
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
            <div class="selected-service-name fw-bold text-success"></div>
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
                <input type="email" id="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}" required>
                @error('email')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>
              <div class="mb-3">
                <label class="form-label">Address Details: *</label>
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
                    <option value="Bacolod City">Bacolod City</option>
                    <option value="Bago City">Bago City</option>
                    <option value="Cadiz City">Cadiz City</option>
                    <option value="Escalante City">Escalante City</option>
                    <option value="Himamaylan City">Himamaylan City</option>
                    <option value="Kabankalan City">Kabankalan City</option>
                    <option value="La Carlota City">La Carlota City</option>
                    <option value="Sagay City">Sagay City</option>
                    <option value="San Carlos City">San Carlos City</option>
                    <option value="Silay City">Silay City</option>
                    <option value="Sipalay City">Sipalay City</option>
                    <option value="Talisay City">Talisay City</option>
                    <option value="Victorias City">Victorias City</option>
                    <option value="Binalbagan">Binalbagan</option>
                    <option value="Calatrava">Calatrava</option>
                    <option value="Candoni">Candoni</option>
                    <option value="Cauayan">Cauayan</option>
                    <option value="Enrique B. Magalona">Enrique B. Magalona</option>
                    <option value="Hinigaran">Hinigaran</option>
                    <option value="Hinoba-an">Hinoba-an</option>
                    <option value="Ilog">Ilog</option>
                    <option value="Isabela">Isabela</option>
                    <option value="La Castellana">La Castellana</option>
                    <option value="Manapla">Manapla</option>
                    <option value="Moises Padilla">Moises Padilla</option>
                    <option value="Murcia">Murcia</option>
                    <option value="Pontevedra">Pontevedra</option>
                    <option value="Pulupandan">Pulupandan</option>
                    <option value="Salvador Benedicto">Salvador Benedicto</option>
                    <option value="San Enrique">San Enrique</option>
                    <option value="Toboso">Toboso</option>
                    <option value="Valladolid">Valladolid</option>
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
                           required>
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
              <input type="hidden" name="booking_token" id="bookingToken">
            </div>
            <div class="modal-footer">
              <button type="submit" class="btn btn-success w-100">Confirm Booking</button>
            </div>
          </form>
        </div>
      </div>
    </div>
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
  </main>
  

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script>
  document.addEventListener('DOMContentLoaded', function () {
    console.log('DOM fully loaded and parsed');

    // DateTime constraints
    const dateInput = document.getElementById('cleaning_date');
    const timeInput = document.getElementById('cleaning_time');
    
    function setDateConstraints() {
      const now = new Date();
      const tomorrow = new Date(now);
      tomorrow.setDate(tomorrow.getDate() + 1);
      
      // Format tomorrow's date for min attribute
      const minYear = tomorrow.getFullYear();
      const minMonth = String(tomorrow.getMonth() + 1).padStart(2, '0');
      const minDay = String(tomorrow.getDate()).padStart(2, '0');
      dateInput.min = `${minYear}-${minMonth}-${minDay}`;
      
      // Set max date to 30 days from now
      const maxDate = new Date(now);
      maxDate.setDate(maxDate.getDate() + 30);
      const maxYear = maxDate.getFullYear();
      const maxMonth = String(maxDate.getMonth() + 1).padStart(2, '0');
      const maxDay = String(maxDate.getDate()).padStart(2, '0');
      dateInput.max = `${maxYear}-${maxMonth}-${maxDay}`;
    }
    
    // Set initial date constraints
    setDateConstraints();
    
    // Update constraints daily at midnight
    setInterval(setDateConstraints, 24 * 60 * 60 * 1000);

    // Add validation to the fields
    dateInput.addEventListener('change', function() {
      const selectedDate = new Date(this.value);
      const now = new Date();
      now.setHours(0, 0, 0, 0); // Set to midnight for date comparison
      
      // Get tomorrow's date
      const tomorrow = new Date(now);
      tomorrow.setDate(tomorrow.getDate() + 1);
      
      if (selectedDate < tomorrow) {
        this.value = '';
        alert('Please select a date starting from tomorrow');
      }
    });

    // Form submission handling
    document.getElementById('bookingForm').addEventListener('submit', function(e) {
      e.preventDefault(); // Prevent default form submission
      
      const dateInput = document.getElementById('cleaning_date');
      const timeInput = document.getElementById('cleaning_time');
      const formData = new FormData(this);
      
      // Remove any existing cleaning_datetime input
      formData.delete('cleaning_datetime');
      
      // Combine address fields
      const addressParts = [
        document.getElementById('block').value.trim(),
        document.getElementById('lot').value.trim(),
        document.getElementById('street').value.trim(),
        document.getElementById('subdivision').value.trim(),
        document.getElementById('barangay').value.trim(),
        document.getElementById('city').value.trim(),
        document.getElementById('zip_code').value.trim()
      ].filter(Boolean); // Remove empty values
      
      // Set the combined address
      formData.set('address', addressParts.join(', '));
      
      // Generate and set booking token
      const bookingToken = 'BK' + Date.now() + Math.random().toString(36).substr(2, 5).toUpperCase();
      formData.set('booking_token', bookingToken);
      document.getElementById('bookingToken').value = bookingToken;
      
      if (dateInput.value && timeInput.value) {
        // Add +08:00 to explicitly set PHT timezone
        const dateTimeValue = `${dateInput.value}T${timeInput.value}+08:00`;
        formData.set('cleaning_date', dateTimeValue);
      }
      
      // Send the form data via fetch
      fetch(this.action, {
        method: this.method,
        body: formData,
        headers: {
          'X-Requested-With': 'XMLHttpRequest',
          'Accept': 'application/json',
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
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
          window.location.href = '{{ route("bookings.success") }}';
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
    });

    // Initialize validation error modal
    const validationErrorModal = new bootstrap.Modal(document.getElementById('validationErrorModal'));

    // Update service selection handling
    document.querySelectorAll('.service-book-btn').forEach(button => {
      button.addEventListener('click', function () {
        console.log('Book Now button clicked');
        
        const serviceContent = this.closest('.service-content');
        const serviceTitle = serviceContent.querySelector('.service-title').textContent;
        const servicePrice = serviceContent.querySelector('.service-price').textContent;
        
        console.log('Selected service:', serviceTitle, servicePrice);

        // Update the service details in the booking form
        const modal = document.getElementById('bookingModal');
        const serviceTitleElement = modal.querySelector('.service-title');
        const servicePriceElement = modal.querySelector('.service-price');

        if (serviceTitleElement && servicePriceElement) {
          serviceTitleElement.textContent = serviceTitle;
          servicePriceElement.textContent = servicePrice;
          console.log('Updated service details in form');
        } else {
          console.error('Service title or price elements not found in modal');
        }
        
        // Set the service ID in hidden input
        let found = false;
        @foreach($services as $service)
        if (!found && '{{ $service->name }}' === serviceTitle.trim()) {
          document.getElementById('service_id').value = '{{ $service->id }}';
          console.log('Set service ID:', '{{ $service->id }}');
          found = true;
        }
        @endforeach
      });
    });

    // Real-time validation functions
    function validateName(value) {
      const nameRegex = /^[A-Za-z\s]{2,50}$/;
      return {
        isValid: nameRegex.test(value.trim()),
        message: 'Name should be 2-50 characters long and contain only letters and spaces'
      };
    }

    function validateEmail(value) {
      const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
      return {
        isValid: emailRegex.test(value.trim()),
        message: 'Please enter a valid email address (e.g., example@email.com)'
      };
    }

    function validatePhone(value) {
      const phoneRegex = /^(\+63|0)[0-9]{10}$/;
      return {
        isValid: phoneRegex.test(value.trim()),
        message: 'Please enter a valid phone number (e.g., +639123456789 or 09123456789)'
      };
    }

    function validateAddress(value) {
      return {
        isValid: value.trim().length >= 10,
        message: 'Address should be at least 10 characters long'
      };
    }

    function validateZipCode(value) {
      const zipRegex = /^\d{4}$/;
      return {
        isValid: zipRegex.test(value.trim()),
        message: 'Zip Code must be 4 digits'
      };
    }

    // Function to combine address fields
    function updateCompleteAddress() {
      const addressParts = [];
      
      if (document.getElementById('block').value.trim()) {
        addressParts.push('Block ' + document.getElementById('block').value.trim());
      }
      
      if (document.getElementById('lot').value.trim()) {
        addressParts.push('Lot ' + document.getElementById('lot').value.trim());
      }
      
      if (document.getElementById('street').value.trim()) {
        let streetName = document.getElementById('street').value.trim();
        // Check if the street name already contains the word "Street" (case insensitive)
        if (!streetName.toLowerCase().includes('street')) {
          streetName += ' Street';
        }
        addressParts.push(streetName);
      }
      
      if (document.getElementById('subdivision').value.trim()) {
        addressParts.push(document.getElementById('subdivision').value.trim());
      }
      
      if (document.getElementById('barangay').value.trim()) {
        addressParts.push('Barangay ' + document.getElementById('barangay').value.trim());
      }
      
      if (document.getElementById('city').value.trim()) {
        addressParts.push(document.getElementById('city').value.trim() + ' City');
      }
      
      if (document.getElementById('zip_code').value.trim()) {
        addressParts.push(document.getElementById('zip_code').value.trim());
      }
      
      document.getElementById('address').value = addressParts.join(', ');
    }

    // Replace the barangaysByCity object with Negros Occidental data
    const barangaysByCity = {
      'Bacolod City': [
        'Alangilan', 'Alijis', 'Banago', 'Barangay 1', 'Barangay 2', 'Barangay 3', 'Barangay 4',
        'Barangay 5', 'Barangay 6', 'Barangay 7', 'Barangay 8', 'Barangay 9', 'Barangay 10',
        'Barangay 11', 'Barangay 12', 'Barangay 13', 'Barangay 14', 'Barangay 15', 'Barangay 16',
        'Barangay 17', 'Barangay 18', 'Barangay 19', 'Barangay 20', 'Barangay 21', 'Barangay 22',
        'Barangay 23', 'Barangay 24', 'Barangay 25', 'Barangay 26', 'Barangay 27', 'Barangay 28',
        'Barangay 29', 'Barangay 30', 'Barangay 31', 'Barangay 32', 'Barangay 33', 'Barangay 34',
        'Barangay 35', 'Barangay 36', 'Barangay 37', 'Barangay 38', 'Barangay 39', 'Barangay 40',
        'Barangay 41', 'Bata', 'Cabug', 'Estefania', 'Felisa', 'Granada', 'Handumanan',
        'Mandalagan', 'Mansilingan', 'Montevista', 'Pahanocoy', 'Punta Taytay', 'Singcang-Airport',
        'Sum-ag', 'Taculing', 'Tangub', 'Villamonte', 'Vista Alegre'
      ],
      'Talisay City': [
        'Bubog', 'Cabatangan', 'Camp Phillips', 'Concepcion', 'Dos Hermanas', 'Efigenio Lizares',
        'Katilingban', 'Matab-ang', 'San Fernando', 'Zone 1', 'Zone 2', 'Zone 3', 'Zone 4',
        'Zone 5', 'Zone 6', 'Zone 7', 'Zone 8', 'Zone 9', 'Zone 10', 'Zone 11', 'Zone 12',
        'Zone 14-A', 'Zone 14-B', 'Zone 15'
      ],
      'Silay City': [
        'Barangay I (Poblacion)', 'Barangay II (Poblacion)', 'Barangay III (Poblacion)',
        'Barangay IV (Poblacion)', 'Barangay V (Poblacion)', 'Balaring', 'Eustaquio Lopez',
        'Guimbala-on', 'Guinhalaran', 'Hawaiian-Philippine Company', 'Kapitan Ramon',
        'Lantad', 'Mambulac', 'Rizal', 'Salvacion', 'Suay'
      ],
      'Bago City': [
        'Abuanan', 'Atipuluan', 'Bacong', 'Balingasag', 'Binubuhan', 'Busay', 'Calumangan',
        'Caridad', 'Don Jorge L. Araneta', 'Dulao', 'Ilijan', 'Lag-asan', 'Ma-ao', 'Mailum',
        'Malingin', 'Napoles', 'Pacol', 'Poblacion', 'Sampinit', 'San Miguel', 'Tabunan',
        'Taloc', 'Tangub', 'Templo'
      ],
      'Sagay City': [
        'Barangay I-A (Poblacion)', 'Barangay I-B (Poblacion)', 'Barangay II', 'Barangay III',
        'Baviera', 'Binunga', 'Bato', 'Bulanon', 'Colonia Divina', 'Fabrica', 'General Luna',
        'Himogaan Baybay', 'Lopez Jaena', 'Malubon', 'Molocaboc', 'Old Sagay', 'Paraiso',
        'Puey', 'Rafaela Barrera', 'Rizal', 'Taba-ao', 'Tadlong', 'Tan-ao', 'Vito'
      ],
      'Cadiz City': [
        'Andres Bonifacio', 'Barangay 1 (Poblacion)', 'Barangay 2 (Poblacion)', 'Barangay 3 (Poblacion)',
        'Barangay 4 (Poblacion)', 'Barangay 5 (Poblacion)', 'Barangay 6 (Poblacion)', 'Barangay Zone 1',
        'Barangay Zone 2', 'Barangay Zone 3', 'Barangay Zone 4', 'Banquerohan', 'Burgos', 'Cabahug',
        'Cadiz Viejo', 'Caduha-an', 'Central', 'Daga', 'Jerusalem', 'Luna', 'Mabini', 'Magsaysay',
        'Marina', 'Sicaba', 'Tinampaan', 'V.F. Gustilo'
      ],
      'Victorias City': [
        'Barangay I (Poblacion)', 'Barangay II (Poblacion)', 'Barangay III (Poblacion)',
        'Barangay IV (Poblacion)', 'Barangay V (Poblacion)', 'Barangay VI-A (Poblacion)',
        'Barangay VI-B (Poblacion)', 'Barangay VII (Poblacion)', 'Barangay VIII (Poblacion)',
        'Barangay IX (Poblacion)', 'Barangay X (Poblacion)', 'Barangay XI (Poblacion)',
        'Barangay XII (Poblacion)', 'Barangay XIII (Poblacion)', 'Barangay XIV (Poblacion)',
        'Barangay XV (Poblacion)', 'Barangay XVI (Poblacion)', 'Barangay XVII (Poblacion)',
        'Barangay XVIII (Poblacion)', 'Barangay XIX (Poblacion)', 'Daan Banua', 'State College'
      ],
      'San Carlos City': [
        'Barangay I (Poblacion)', 'Barangay II (Poblacion)', 'Barangay III (Poblacion)',
        'Barangay IV (Poblacion)', 'Barangay V (Poblacion)', 'Barangay VI (Poblacion)',
        'Antilla', 'Buluangan', 'Codcod', 'Ermita', 'Guadalupe', 'Nataban',
        'Palampas', 'Prosperidad', 'Punao', 'Quezon', 'Rizal', 'San Juan',
        'Barangay 1', 'Barangay 2', 'Barangay 3', 'Barangay 4', 'Barangay 5', 'Barangay 6'
      ],
      'Kabankalan City': [
        'Barangay 1 (Poblacion)', 'Barangay 2 (Poblacion)', 'Barangay 3 (Poblacion)',
        'Barangay 4 (Poblacion)', 'Barangay 5 (Poblacion)', 'Barangay 6 (Poblacion)',
        'Barangay 7 (Poblacion)', 'Barangay 8 (Poblacion)', 'Barangay 9 (Poblacion)',
        'Binicuil', 'Camansi', 'Camugao', 'Carol-an', 'Daan Banua', 'Hilamonan',
        'Inapoy', 'Linao', 'Locotan', 'Magballo', 'Oringao', 'Pinaguinpinan',
        'Salong', 'Tabugon', 'Tagukon', 'Talubangi', 'Tampalon', 'Tan-awan',
        'Tapi', 'Tomina'
      ],
      'Himamaylan City': [
        'Aguisan', 'Buenavista', 'Cabadiangan', 'Cabanbanan', 'Carabalan', 'Libacao',
        'Mambagaton', 'Nabali-an', 'Poblacion', 'San Antonio', 'Sara-et', 'Su-ay',
        'Talaban', 'To-oy', 'Tooy'
      ],
      'La Carlota City': [
        'Barangay I (Poblacion)', 'Barangay II (Poblacion)', 'Barangay III (Poblacion)',
        'Ayungon', 'Balabag', 'Barangay I-A', 'Barangay II-A', 'Barangay III-A',
        'Batuan', 'Cubay', 'Haguimit', 'La Granja', 'Nagasi', 'Yubo'
      ],
      'Escalante City': [
        'Barangay I (Poblacion)', 'Barangay II (Poblacion)', 'Barangay II-A',
        'Barangay II-B', 'Barangay II-C', 'Barangay II-D', 'Balintawak', 'Binaguiohan',
        'Dian-ay', 'Japitan', 'Jonob-jonob', 'Langub', 'Libertad', 'Mabini',
        'Malubon', 'Paitan', 'Pinapugasan', 'Rizal', 'Tamlang', 'Udtongan',
        'Washington'
      ],
      'Sipalay City': [
        'Barangay 1 (Poblacion)', 'Barangay 2 (Poblacion)', 'Barangay 3 (Poblacion)',
        'Barangay 4 (Poblacion)', 'Barangay 5 (Poblacion)', 'Cabadiangan', 'Camindangan',
        'Canturay', 'Cartagena', 'Cayhagan', 'Gil Montilla', 'Mambaroto', 'Manlucahoc',
        'Maricalum', 'Nauhang', 'Nabulao', 'San Jose'
      ],
      'Binalbagan': [
        'Amontay', 'Bagroy', 'Bi-ao', 'Binalbagan Proper', 'Enclaro', 'Marina',
        'Payao', 'Progreso', 'San Jose', 'San Pedro', 'San Vicente', 'Santo Rosario',
        'Santol', 'Ubay'
      ],
      'Hinigaran': [
        'Anahaw', 'Aranda', 'Barangay I (Poblacion)', 'Barangay II (Poblacion)',
        'Barangay III (Poblacion)', 'Barangay IV (Poblacion)', 'Bato', 'Calapi',
        'Camalobalo', 'Gargato', 'Himaya', 'Miranda', 'Narauis', 'Nanunga',
        'Palayog', 'Paticui', 'Pilar', 'Quiwi', 'River Bank', 'San Jose'
      ],
      'Murcia': [
        'Alegria', 'Amayco', 'Aning', 'Blumentritt', 'Caliban', 'Canlandog',
        'Cansilayan', 'Lopez Jaena', 'Minoyan', 'Pandanon', 'Salvacion',
        'San Miguel', 'Santa Cruz', 'Santa Rosa', 'Talotog'
      ],
      'Calatrava': [
        'Acero', 'Bagacay', 'Bantayanon', 'Cabungahan', 'Calampisawan', 'Cruz',
        'Dolis', 'Ferlou', 'Hilub-ang', 'Hinab-ongan', 'Lalong', 'Lemery',
        'Lipat-on', 'Maaslob', 'Macasilao', 'Mahilum', 'Malatas', 'Marcelo',
        'Minapasuk', 'Mina-utok', 'Patun-an', 'Puso', 'Suba', 'Tigbon', 'Winaswasan'
      ],
      'Candoni': [
        'Agboy', 'Banga', 'Cabia-an', 'Caningay', 'Gatuslao', 'Haba',
        'Payauan', 'Poblacion East', 'Poblacion West'
      ],
      'Cauayan': [
        'Abaca', 'Basak', 'Bulata', 'Caliling', 'Camalanda-an', 'Guiljungan',
        'Inayawan', 'Isio', 'Linaon', 'Lumbia', 'Mambugsay', 'Man-Uling',
        'Masaling', 'Molobolo', 'Poblacion', 'Sura', 'Talacdan', 'Tambad',
        'Tiling', 'Tomina', 'Yao-yao'
      ],
      'Enrique B. Magalona': [
        'Alacaygan', 'Alicante', 'Batea', 'Consing', 'Cudangdang', 'Damgo',
        'Gahit', 'Latasan', 'Madalag', 'Nanca', 'Poblacion I', 'Poblacion II',
        'Poblacion III', 'San Isidro', 'San Jose', 'Santo Niño', 'Tabigue',
        'Tanza', 'Tuburan', 'Tomongtong'
      ],
      'Hinoba-an': [
        'Asia', 'Bacuyangan', 'Barangay 1 (Poblacion)', 'Barangay 2 (Poblacion)',
        'Bulwangan', 'Culipapa', 'Damutan', 'San Rafael', 'Sangke',
        'Talacagay', 'Po-ok', 'Alim'
      ],
      'Ilog': [
        'Andulauan', 'Balicotoc', 'Bocana', 'Calubang', 'Canlamay', 'Consuelo',
        'Dancalan', 'Delicioso', 'Galicia', 'Katilingban', 'Manalad', 'Pinggot',
        'Poblacion', 'Tabu', 'Vista Alegre'
      ],
      'Isabela': [
        'Barangay I (Poblacion)', 'Barangay II (Poblacion)', 'Barangay III (Poblacion)',
        'Barangay IV (Poblacion)', 'Banogbanog', 'Bungahin', 'Cabcab', 'Cansalongon',
        'Guintubhan', 'Libas', 'Makilignit', 'Mansablay', 'Panaquiao', 'Riverside',
        'San Agustin', 'Santa Cruz', 'Tinongan'
      ],
      'La Castellana': [
        'Biaknabato', 'Cabacungan', 'Cabagnaan', 'Camandag', 'Lalagsan', 'Maao',
        'Manghanoy', 'Mansalanao', 'Nato', 'Puso', 'Robles', 'Sag-ang', 'Talaptap'
      ],
      'Manapla': [
        'Barangay I-A (Poblacion)', 'Barangay I-B (Poblacion)', 'Barangay I-C (Poblacion)',
        'Barangay II (Poblacion)', 'Chamber', 'Punta Mesa', 'Punta Salong',
        'San Pablo', 'Santa Teresa', 'Tortosa'
      ],
      'Moises Padilla': [
        'Barangay I (Poblacion)', 'Barangay II (Poblacion)', 'Barangay III (Poblacion)',
        'Crossing Magallon', 'Guinpana-an', 'Inolingan', 'Macagahay', 'Magallon Cadre',
        'Montilla', 'Odiong', 'Quintin Remo'
      ],
      'Pontevedra': [
        'Barangay I (Poblacion)', 'Barangay II (Poblacion)', 'Barangay III (Poblacion)',
        'Antipolo', 'Barangay IV (Poblacion)', 'Buenavista', 'Canroma', 'Don Salvador Benedicto',
        'General Malvar', 'Miranda', 'San Juan', 'Santiago', 'Tacushong'
      ],
      'Pulupandan': [
        'Barangay I (Poblacion)', 'Barangay II (Poblacion)', 'Barangay III (Poblacion)',
        'Barangay IV (Poblacion)', 'Canjusa', 'Palaka Norte', 'Palaka Sur',
        'Patic', 'Tapong', 'Ubay', 'Zone 1', 'Zone 2', 'Zone 3', 'Zone 4'
      ],
      'Salvador Benedicto': [
        'Bagong Silang (Poblacion)', 'Bago Pinay', 'Bunga', 'Igmaya-an',
        'Kumaliskis', 'Lalung', 'Pandanon', 'Pinowayan'
      ],
      'San Enrique': [
        'Barangay I (Poblacion)', 'Barangay II (Poblacion)', 'Barangay III (Poblacion)',
        'Barangay IV (Poblacion)', 'Bato', 'Batuan', 'Guintorilan', 'Nayon',
        'Sibucao', 'Tabao Baybay', 'Tabao Proper', 'Tibsoc'
      ],
      'Toboso': [
        'Bandila', 'Bug-ang', 'General Luna', 'Magticol', 'Poblacion',
        'San Isidro', 'San Jose', 'Tabun-ac'
      ],
      'Valladolid': [
        'Alijis', 'Ayungon', 'Barangay I (Poblacion)', 'Barangay II (Poblacion)',
        'Barangay III (Poblacion)', 'Barangay IV (Poblacion)', 'Bayabas',
        'Central', 'Doldol', 'Guintorilan', 'Lacaron', 'Mabini', 'Pacol',
        'Palaka', 'Paloma', 'Sagua Banua'
      ]
    };

    // Function to populate barangay dropdown based on selected city
    function populateBarangays(cityValue) {
      const barangaySelect = document.getElementById('barangay');
      barangaySelect.innerHTML = '<option value="">Select Barangay *</option>';
      
      if (cityValue && barangaysByCity[cityValue]) {
        barangaySelect.disabled = false;
        barangaysByCity[cityValue].forEach(barangay => {
          const option = document.createElement('option');
          option.value = barangay;
          option.textContent = barangay;
          barangaySelect.appendChild(option);
        });
      } else {
        barangaySelect.disabled = true;
      }
    }

    // Add event listener for city selection
    document.getElementById('city').addEventListener('change', function() {
      populateBarangays(this.value);
      // Trigger validation
      if (fields['city']) {
        validateField(this, fields['city']);
      }
    });

    // Add event listener for barangay selection
    document.getElementById('barangay').addEventListener('change', function() {
      if (fields['barangay']) {
        validateField(this, fields['barangay']);
      }
    });

    // Update the fields validation object
    const fields = {
      'name': validateName,
      'email': validateEmail,
      'contact': validatePhone,
      'street': { validate: value => ({ isValid: value.trim().length > 0, message: 'Street Name is required' }), required: true },
      'city': { 
        validate: value => ({ 
          isValid: value && value.trim().length > 0, 
          message: 'Please select a City/Municipality' 
        }), 
        required: true 
      },
      'barangay': { 
        validate: value => ({ 
          isValid: value && value.trim().length > 0, 
          message: 'Please select a Barangay' 
        }), 
        required: true 
      },
      'zip_code': { validate: validateZipCode, required: true },
      'cleaning_date': validateDate
    };

    // Function to validate a single field
    function validateField(field, validator) {
      const feedbackDiv = field.nextElementSibling;
      const result = typeof validator === 'function' ? validator(field.value) : validator.validate(field.value);
      
      if (!field.value.trim() && (typeof validator === 'function' || validator.required)) {
        field.classList.add('is-invalid');
        if (feedbackDiv && feedbackDiv.classList.contains('invalid-feedback')) {
          feedbackDiv.textContent = `${field.placeholder || field.id} is required`;
        }
        return false;
      } else if (!result.isValid) {
        field.classList.add('is-invalid');
        if (feedbackDiv && feedbackDiv.classList.contains('invalid-feedback')) {
          feedbackDiv.textContent = result.message;
        }
        return false;
      } else {
        field.classList.remove('is-invalid');
        if (feedbackDiv && feedbackDiv.classList.contains('invalid-feedback')) {
          feedbackDiv.textContent = '';
        }
        return true;
      }
    }

    // Add real-time validation for all fields
    for (const [fieldId, validator] of Object.entries(fields)) {
      const field = document.getElementById(fieldId);
      if (field) {
        // Validate on input (as user types)
        field.addEventListener('input', function() {
          validateField(this, validator);
        });
        
        // Validate on blur (when field loses focus)
        field.addEventListener('blur', function() {
          validateField(this, validator);
        });
      }
    }

    // Add event listeners for address fields
    const addressFields = ['block', 'lot', 'street', 'subdivision', 'barangay', 'city', 'zip_code'];
    addressFields.forEach(fieldId => {
      const field = document.getElementById(fieldId);
      if (field) {
        // Update complete address on any change
        field.addEventListener('input', function() {
          updateCompleteAddress();
          // If this field has a validator, run it
          if (fields[fieldId]) {
            validateField(this, fields[fieldId]);
          }
        });

        // Validate required fields on blur
        field.addEventListener('blur', function() {
          if (fields[fieldId]) {
            validateField(this, fields[fieldId]);
          }
        });
      }
    });
  });
</script>

</body>
</html>