<?php $currentStep = 2; ?>
<!-- Header already loaded in main index.php -->

<style>
    .form-card {
        background: white;
        border-radius: 12px;
        padding: 40px;
        max-width: 800px;
        margin: 0 auto 40px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.08);
    }

    .back-button {
        color: #dc3545;
        text-decoration: none;
        font-size: 1rem;
        font-weight: 500;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        margin-bottom: 20px;
        cursor: pointer;
        transition: color 0.3s;
    }

    .back-button:hover {
        color: #c82333;
    }

    .form-card h2 {
        font-size: 1.8rem;
        font-weight: 600;
        color: #2c3e50;
        margin-bottom: 30px;
    }

    .subtitle {
        color: #6c757d;
        margin-bottom: 30px;
    }

    .form-label {
        font-weight: 600;
        color: #2c3e50;
        margin-bottom: 8px;
        font-size: 0.95rem;
    }

    .form-label .required {
        color: #dc3545;
        margin-left: 3px;
    }

    .form-control, .form-select {
        border: 2px solid #e0e0e0;
        border-radius: 8px;
        padding: 12px 15px;
        font-size: 0.95rem;
        transition: border-color 0.3s;
    }

    .form-control:focus, .form-select:focus {
        border-color: #dc3545;
        box-shadow: 0 0 0 0.2rem rgba(220, 53, 69, 0.15);
        outline: none;
    }

    .form-control.is-invalid, .form-select.is-invalid {
        border-color: #dc3545;
        background-image: none;
    }

    .form-control.is-valid, .form-select.is-valid {
        border-color: #28a745;
        background-image: none;
    }

    .invalid-feedback {
        display: none;
        color: #dc3545;
        font-size: 0.875rem;
        margin-top: 0.25rem;
    }

    .valid-feedback {
        display: none;
        color: #28a745;
        font-size: 0.875rem;
        margin-top: 0.25rem;
    }

    .form-control.is-invalid ~ .invalid-feedback,
    .form-select.is-invalid ~ .invalid-feedback {
        display: block;
    }

    .form-control.is-valid ~ .valid-feedback,
    .form-select.is-valid ~ .valid-feedback {
        display: block;
    }

    .btn-continue {
        width: 100%;
        background-color: #dc3545;
        color: white;
        border: none;
        padding: 15px;
        font-size: 1rem;
        font-weight: 600;
        border-radius: 8px;
        cursor: pointer;
        transition: all 0.3s;
        margin-top: 30px;
    }

    .btn-continue:hover:not(:disabled) {
        background-color: #c82333;
        transform: translateY(-1px);
        box-shadow: 0 4px 8px rgba(220, 53, 69, 0.3);
    }

    .btn-continue:disabled {
        background-color: #cccccc;
        cursor: not-allowed;
        opacity: 0.6;
    }

    @media (max-width: 768px) {
        .form-card {
            padding: 30px 20px;
        }
    }
</style>

<div class="container">
    <div class="form-card">
        <a href="javascript:void(0);" class="back-button" onclick="goBackToMain()">
            <i class="fas fa-chevron-left"></i> Back
        </a>
        
        <h2>Personal Information</h2>
        <p class="subtitle">Enter your personal information to begin your storage move-in process.</p>

        <form id="personalInfoForm" novalidate>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="firstName" class="form-label">First Name<span class="required">*</span></label>
                    <input type="text" class="form-control" id="firstName" name="firstName" placeholder="First Name" required>
                    <div class="invalid-feedback">First name cannot be empty.</div>
                    <div class="valid-feedback">Looks good!</div>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="lastName" class="form-label">Last Name<span class="required">*</span></label>
                    <input type="text" class="form-control" id="lastName" name="lastName" placeholder="Last Name" required>
                    <div class="invalid-feedback">Last name cannot be empty.</div>
                    <div class="valid-feedback">Looks good!</div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="country" class="form-label">Country<span class="required">*</span></label>
                    <select id="country" name="country" class="form-select" required>
                        <option value="">Select Country</option>
                        <option value="Australia">Australia</option>
                    </select>
                    <div class="invalid-feedback">Country must be selected.</div>
                    <div class="valid-feedback">Looks good!</div>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="countryCode" class="form-label">Country Code<span class="required">*</span></label>
                    <select id="countryCode" name="countryCode" class="form-select" required>
                        <option value="">Select Country Code</option>
                        <option value="+61">AUD (+61)</option>
                    </select>
                    <div class="invalid-feedback">Country code must be selected.</div>
                    <div class="valid-feedback">Looks good!</div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="city" class="form-label">City<span class="required">*</span></label>
                    <input type="text" class="form-control" id="city" name="city" placeholder="City" required>
                    <div class="invalid-feedback">City cannot be empty.</div>
                    <div class="valid-feedback">Looks good!</div>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="state" class="form-label">State<span class="required">*</span></label>
                    <input type="text" class="form-control" id="state" name="state" placeholder="State" required>
                    <div class="invalid-feedback">State cannot be empty.</div>
                    <div class="valid-feedback">Looks good!</div>
                </div>
            </div>

            <div class="mb-3">
                <label for="email" class="form-label">Email<span class="required">*</span></label>
                <input type="email" class="form-control" id="email" name="email" placeholder="Email Address" required>
                <div class="invalid-feedback">Please enter a valid email address.</div>
                <div class="valid-feedback">Looks good!</div>
            </div>

            <div class="mb-3">
                <label for="phone" class="form-label">Phone<span class="required">*</span></label>
                <input type="tel" class="form-control" id="phone" name="phone" placeholder="Phone Number" required>
                <div class="invalid-feedback">Phone number cannot be empty.</div>
                <div class="valid-feedback">Looks good!</div>
            </div>

            <button type="submit" class="btn-continue" id="submitBtn" disabled>Continue to Additional Details</button>
        </form>
    </div>
</div>

<!-- Libraries already loaded in main header -->
<script>
// Real-time validation function
function validateField(id, type = 'text') {
    const $el = $('#' + id);
    const val = $el.val().trim();
    let isValid = false;

    if ($el.prop('disabled')) return true;

    if (type === 'email') {
        isValid = /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(val);
    } else {
        isValid = val !== '';
    }

    $el.removeClass('is-invalid is-valid');
    if (val === '') {
        // Empty field - no validation class
        $el.removeClass('is-invalid is-valid');
    } else if (isValid) {
        $el.addClass('is-valid');
    } else {
        $el.addClass('is-invalid');
    }

    return isValid;
}

// Check if entire form is valid
function isFormValid() {
    let valid = true;
    
    // Check all required text fields
    ['firstName', 'lastName', 'city', 'state', 'phone'].forEach(function(id) {
        const val = $('#' + id).val().trim();
        if (val === '') valid = false;
    });
    
    // Check email
    const email = $('#email').val().trim();
    if (!email || !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
        valid = false;
    }
    
    // Check select fields
    if ($('#country').val() === '') valid = false;
    if ($('#countryCode').val() === '') valid = false;
    
    return valid;
}

// Update submit button state
function updateSubmitButton() {
    const $submitBtn = $('#submitBtn');
    if (isFormValid()) {
        $submitBtn.prop('disabled', false);
    } else {
        $submitBtn.prop('disabled', true);
    }
}

// Validate entire form and show errors
function validateEntireForm() {
    let valid = true;
    
    valid &= validateField('firstName');
    valid &= validateField('lastName');
    valid &= validateField('countryCode');
    valid &= validateField('country');
    valid &= validateField('city');
    valid &= validateField('state');
    valid &= validateField('email', 'email');
    valid &= validateField('phone');
    
    return Boolean(valid);
}

function capitalizeFirstLetter(str) {
    if (!str) return "";
    return str.charAt(0).toUpperCase() + str.slice(1).toLowerCase();
}

$(document).ready(function() {
    // Real-time validation on input
    $('#firstName, #lastName, #city, #state, #phone').on('input blur', function() {
        validateField($(this).attr('id'));
        updateSubmitButton();
    });

    $('#email').on('input blur', function() {
        validateField('email', 'email');
        updateSubmitButton();
    });

    $('#country, #countryCode').on('change', function() {
        validateField($(this).attr('id'));
        updateSubmitButton();
    });

    // Form submission
    $('#personalInfoForm').on('submit', function(e) {
        e.preventDefault();

        // Final validation before submit
        if (!validateEntireForm()) {
            Swal.fire({
                icon: 'error',
                title: 'Incomplete Form',
                text: 'Please fill in all required fields correctly before continuing.',
                confirmButtonText: 'OK',
                buttonsStyling: false,
                customClass: {
                    confirmButton: 'btn btn-custom-primary me-2'
                }
            });
            return false;
        }

        // If form is valid, save data and proceed
        const formData = {
            firstName: capitalizeFirstLetter($('#firstName').val()),
            lastName: capitalizeFirstLetter($('#lastName').val()),
            country: $('#country').val(),
            countryCode: $('#countryCode').val(),
            city: capitalizeFirstLetter($('#city').val()),
            state: capitalizeFirstLetter($('#state').val()),
            email: $('#email').val().trim(),
            phone: $('#phone').val().trim()
        };

        sessionStorage.setItem('personalInfo', JSON.stringify(formData));

        // Show success message
        Swal.fire({
            icon: 'success',
            title: 'Information Saved!',
            text: 'Proceeding to additional details...',
            timer: 1500,
            showConfirmButton: false
        }).then(() => {
            if (typeof loadStep === 'function') {
                loadStep('additional-details');
            } else {
                window.location.href = '<?php echo base_url('mammoth-storage/additional-details'); ?>';
            }
        });
    });

    // Load saved data if exists
    const savedData = sessionStorage.getItem('personalInfo');
    if (savedData) {
        try {
            const data = JSON.parse(savedData);
            $('#firstName').val(data.firstName || '');
            $('#lastName').val(data.lastName || '');
            $('#country').val(data.country || '');
            $('#countryCode').val(data.countryCode || '');
            $('#city').val(data.city || '');
            $('#state').val(data.state || '');
            $('#email').val(data.email || '');
            $('#phone').val(data.phone || '');
            
            // Validate pre-filled fields
            setTimeout(function() {
                $('#firstName, #lastName, #city, #state, #phone').each(function() {
                    validateField($(this).attr('id'));
                });
                validateField('email', 'email');
                $('#country, #countryCode').each(function() {
                    validateField($(this).attr('id'));
                });
                updateSubmitButton();
            }, 100);
        } catch (e) {
            console.error('Error loading saved data:', e);
        }
    }

    // Initial button state check
    updateSubmitButton();
});
</script>

<?php include(APPPATH . 'views/storage/mammoth-storage/partials/footer-steps.php'); ?>