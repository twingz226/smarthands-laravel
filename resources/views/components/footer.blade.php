<!-- Footer -->
<footer class="bg-dark text-light py-5 mt-auto">
    <div class="container">
        <div class="row g-4">
            <!-- About Section -->
            <div class="col-md-4">
                <h5 class="text-uppercase mb-4">About Smarthands</h5>
                <p>{{ $contactInfo->about_content ?? 'Welcome to Smarthands Cleaning Services, your trusted partner in professional cleaning solutions.' }}</p>
            </div>
            
            <!-- Quick Links -->
            <div class="col-md-2">
                <h5 class="text-uppercase mb-4">Quick Links</h5>
                <ul class="list-unstyled">
                    <li class="mb-2"><a href="{{ route('home') }}" class="text-white text-decoration-none">Home</a></li>
                    <li class="mb-2"><a href="{{ route('services') }}" class="text-white text-decoration-none">Services</a></li>
                    <li class="mb-2"><a href="{{ route('about') }}" class="text-white text-decoration-none">About Us</a></li>
                    <li class="mb-2"><a href="{{ route('contact') }}" class="text-white text-decoration-none">Contact</a></li>
                    <li class="mb-2"><a href="{{ route('privacy') }}" class="text-white text-decoration-none">Privacy Policy</a></li>
                    <li class="mb-2"><a href="{{ route('terms') }}" class="text-white text-decoration-none">Terms & Conditions</a></li>
                    <li class="mb-2"><a href="{{ route('cookies') }}" class="text-white text-decoration-none">Cookie Policy</a></li>
                </ul>
            </div>
            
            <!-- Contact Info -->
            <div class="col-md-3">
                <h5 class="text-uppercase mb-4">Contact Us</h5>
                <ul class="list-unstyled">
                    <li class="mb-2"><i class="bi bi-geo-alt-fill me-2"></i> {{ $contactInfo->address ?? 'Bacolod City, Philippines' }}</li>
                    <li class="mb-2"><i class="bi bi-telephone-fill me-2"></i> 0953 957 4130 <small class="text-light">(Viber/WhatsApp)</small></li>
                    <li class="mb-2"><i class="bi bi-envelope-fill me-2"></i> {{ $contactInfo->email ?? 'smarthandsbcd@gmail.com' }}</li>
                </ul>
            </div>
            
            <!-- Social Media -->
            <div class="col-md-3">
                <h5 class="text-uppercase mb-4">Follow Us</h5>
                <div class="social-links">
                    @if($contactInfo && $contactInfo->facebook_url)
                        <a href="{{ $contactInfo->facebook_url }}" class="text-white me-3" target="_blank" rel="noopener noreferrer"><i class="bi bi-facebook"></i></a>
                    @else
                        <a href="https://www.facebook.com/profile.php?id=100088701112041" class="text-white me-3" target="_blank" rel="noopener noreferrer"><i class="bi bi-facebook"></i></a>
                    @endif
                    @if($contactInfo && $contactInfo->instagram_url)
                        <a href="{{ $contactInfo->instagram_url }}" class="text-white me-3" target="_blank" rel="noopener noreferrer"><i class="bi bi-instagram"></i></a>
                    @else
                        <a href="https://www.instagram.com/smarthandscleaning" class="text-white me-3" target="_blank" rel="noopener noreferrer"><i class="bi bi-instagram"></i></a>
                    @endif
                </div>
            </div>
        </div>
        
        <hr class="my-4 bg-light">
        
        <div class="row">
            <div class="col-12 text-center">
                <p class="mb-0">&copy; {{ date('Y') }} Smarthands Cleaning Services. All rights reserved.</p>
            </div>
        </div>
    </div>
</footer>

<style>
    footer {
        background-color: #2c3e50;
    }
    
    footer h5 {
        color: #ff9f1c;
        font-weight: 600;
        position: relative;
        padding-bottom: 10px;
    }
    
    footer h5::after {
        content: '';
        position: absolute;
        left: 0;
        bottom: 0;
        width: 50px;
        height: 2px;
        background-color: #ff9f1c;
    }
    
    footer a:hover {
        color: #ff9f1c !important;
        padding-left: 5px;
        transition: all 0.3s ease;
    }
    
    .social-links a {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 36px;
        height: 36px;
        border-radius: 50%;
        background-color: rgba(255, 255, 255, 0.1);
        margin-right: 10px;
        transition: all 0.3s ease;
    }
    
    .social-links a:hover {
        background-color: #ff9f1c;
        color: #fff !important;
        transform: translateY(-3px);
        padding-left: 0;
    }
</style>
