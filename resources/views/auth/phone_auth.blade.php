
    
    <div class="mb-3">
        <label for="phone" class="form-label">Phone Number</label>
        <input type="tel" class="form-control" id="phone" name="phone" placeholder="e.g. +63 912 345 6789" pattern="^\+?\d{10,15}$" required autocomplete="tel">
        <div class="invalid-feedback">Please enter a valid phone number.</div>
    </div>
    <div class="mb-3 position-relative">
        <label for="phone-password" class="form-label">Password</label>
        <input type="password" class="form-control" id="phone-password" name="password" placeholder="Enter your password" required autocomplete="current-password">
        <span class="password-toggle" style="position:absolute;top:38px;right:16px;cursor:pointer;">
            <i class="bi bi-eye-fill" id="togglePhonePassword"></i>
        </span>
        <div class="invalid-feedback">Password is required.</div>
    </div>
    <!-- Optional: OTP Field for 2FA
    <div class="mb-3">
        <label for="otp" class="form-label">OTP Code</label>
        <input type="text" class="form-control" id="otp" name="otp" placeholder="Enter OTP" maxlength="6" autocomplete="one-time-code">
        <div class="invalid-feedback">Invalid OTP code.</div>
    </div>
    -->
    <button type="submit" class="btn btn-primary w-100">Continue</button>

</form>
<script>
// Password show/hide toggle for phone modal
    document.addEventListener('DOMContentLoaded', function () {
        var toggle = document.getElementById('togglePhonePassword');
        var input = document.getElementById('phone-password');
        if(toggle && input) {
            toggle.addEventListener('click', function() {
                if(input.type === 'password') {
                    input.type = 'text';
                    toggle.classList.replace('bi-eye-fill', 'bi-eye-slash-fill');
                } else {
                    input.type = 'password';
                    toggle.classList.replace('bi-eye-slash-fill', 'bi-eye-fill');
                }
            });
        }
    });
    // Optional: Add client-side validation
    document.addEventListener('DOMContentLoaded', function () {
        var form = document.getElementById('phone-auth-form');
        if(form) {
            form.addEventListener('submit', function(e) {
                var phone = document.getElementById('phone');
                var password = document.getElementById('phone-password');
                var valid = true;
                if(!/^\+?\d{10,15}$/.test(phone.value)) {
                    phone.classList.add('is-invalid');
                    valid = false;
                } else {
                    phone.classList.remove('is-invalid');
                }
                if(!password.value) {
                    password.classList.add('is-invalid');
                    valid = false;
                } else {
                    password.classList.remove('is-invalid');
                }
                if(!valid) e.preventDefault();
            });
        }
    });
</script>
