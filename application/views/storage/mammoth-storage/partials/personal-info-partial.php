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
    <div class="row">
        <div class="col-lg-7">
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
                                <option value="Australia" selected>Australia</option>

                            </select>
                            <div class="invalid-feedback">Country must be selected.</div>
                            <div class="valid-feedback">Looks good!</div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="countryCode" class="form-label">Country Code<span class="required">*</span></label>
                            <select id="countryCode" name="countryCode" class="form-select" required>
                                <option value="">Select Country Code</option>
                                <option value="+61" selected>AUD (+61)</option>

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
                        <label for="address1" class="form-label">Address Line<span class="required">*</span></label>
                        <input type="text" class="form-control" id="address1" name="address1" placeholder="Street Address" required>
                        <div class="invalid-feedback">Address cannot be empty.</div>
                        <div class="valid-feedback">Looks good!</div>
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
        <div class="col-lg-5 mt-4 mt-lg-0">
            <?php include(APPPATH . 'views/storage/mammoth-storage/partials/checkout-summary-partial.php'); ?>
        </div>
    </div>
</div>

<!-- Libraries already loaded in main header -->
<script>
// Real-time validation function
function validateField(id, type = 'text') {
    const $el = $('#' + id);
    if (!$el.length) {
        return true; // Skip validation if field not found
    }

    const rawVal = $el.val();
    let val = (rawVal === undefined || rawVal === null) ? '' : String(rawVal).trim();
    if (/^n\/?a$/i.test(val)) {
        // Treat N/A as empty and clear the field
        $el.val('');
        val = '';
    }
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
    ['firstName', 'lastName', 'city', 'state', 'address1', 'phone'].forEach(function(id) {
        let val = $('#' + id).val().trim();
        if (/^n\/?a$/i.test(val)) val = '';
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
    valid &= validateField('address1');
    valid &= validateField('email', 'email');
    valid &= validateField('phone');
    
    return Boolean(valid);
}

function capitalizeFirstLetter(str) {
    if (!str) return "";
    return str.charAt(0).toUpperCase() + str.slice(1).toLowerCase();
}

// Safely get an input value as string (prevents undefined.trim errors)
const safeVal = (id) => {
    const $el = $('#' + id);
    return $el.length ? ($el.val() || '') : '';
};

const normalizeNA = (val) => {
    const v = (val === undefined || val === null) ? '' : String(val).trim();
    return /^n\/?a$/i.test(v) ? '' : v;
};

let personalInfoXhr = null;

$(document).ready(function() {
    // Real-time validation on input
    $('#firstName, #lastName, #city, #state, #address1, #phone')
        .off('input.personalInfo blur.personalInfo')
        .on('input.personalInfo blur.personalInfo', function() {
        validateField($(this).attr('id'));
        updateSubmitButton();
    });

    ['city', 'state'].forEach(function(id) {
        const $el = $('#' + id);
        if ($el.length) {
            const v = String($el.val() || '').trim();
            if (/^n\/?a$/i.test(v)) {
                $el.val('');
            }
        }
    });

    $('#email')
        .off('input.personalInfo blur.personalInfo')
        .on('input.personalInfo blur.personalInfo', function() {
        validateField('email', 'email');
        updateSubmitButton();
    });

    $('#country, #countryCode')
        .off('change.personalInfo')
        .on('change.personalInfo', function() {
        validateField($(this).attr('id'));
        updateSubmitButton();
    });

    // Default selects if still empty
    if (!$('#country').val()) {
        $('#country').val('Australia');
    }
    if (!$('#countryCode').val()) {
        $('#countryCode').val('+61');
    }

    // Form submission
    $('#personalInfoForm')
        .off('submit.personalInfo')
        .on('submit.personalInfo', function(e) {
        e.preventDefault();

        if (personalInfoXhr && personalInfoXhr.readyState !== 4) {
            try { personalInfoXhr.abort(); } catch (e) {}
        }

        const $submitBtn = $('#submitBtn');
        $submitBtn.prop('disabled', true);

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
            $submitBtn.prop('disabled', false);
            return false;
        }

        // If form is valid, save data and proceed
        const cached = (() => {
            try { return JSON.parse(sessionStorage.getItem('personalInfo') || '{}'); } catch (e) { return {}; }
        })();

        const bookingData = (() => {
            try { return JSON.parse(sessionStorage.getItem('bookingData') || '{}'); } catch (e) { return {}; }
        })();

        const formData = {
            firstName: capitalizeFirstLetter(safeVal('firstName') || cached.firstName || ''),
            lastName: capitalizeFirstLetter(safeVal('lastName') || cached.lastName || ''),
            country: safeVal('country') || cached.country || 'Australia',
            countryCode: safeVal('countryCode') || cached.countryCode || '+61',
            city: capitalizeFirstLetter(normalizeNA(safeVal('city') || cached.city || '')),
            state: capitalizeFirstLetter(normalizeNA(safeVal('state') || cached.state || '')),
            address1: (safeVal('address1') || cached.address1 || '').trim(),
            email: (safeVal('email') || cached.email || '').trim(),
            phone: (safeVal('phone') || cached.phone || '').trim(),
            locationCode: bookingData.locationCode || '',
            locationName: bookingData.locationName || '',
            storageType: bookingData.storageType || '',
            unitId: (bookingData.unit && bookingData.unit.unitId) ? bookingData.unit.unitId : '',
            unitName: (bookingData.unit && bookingData.unit.unitName) ? bookingData.unit.unitName : '',
            unitSize: (bookingData.unit && bookingData.unit.size) ? bookingData.unit.size : '',
            unitGroup: (bookingData.unit && bookingData.unit.group) ? bookingData.unit.group : ''
        };

        if (!formData.email) {
            Swal.fire({
                icon: 'error',
                title: 'Email required',
                text: 'Please enter your email before continuing.',
                confirmButtonText: 'OK'
            });
            $submitBtn.prop('disabled', false);
            return false;
        }

        if (!formData.city || !formData.state || !formData.address1) {
            validateField('city');
            validateField('state');
            validateField('address1');
            updateSubmitButton();
            $submitBtn.prop('disabled', false);
            const $city = $('#city');
            if ($city.length) {
                try {
                    $('html, body').animate({ scrollTop: $city.offset().top - 120 }, 200);
                } catch (e) {}
                $city.focus();
            }
            return false;
        }

        sessionStorage.setItem('personalInfo', JSON.stringify(formData));

        function proceedToNextStep() {
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
        }

        personalInfoXhr = $.ajax({
            url: '<?php echo base_url('MammothStorageController/capture_personal_info'); ?>',
            type: 'POST',
            data: formData,
            dataType: 'json'
        }).done(function(response) {
            if (response && response.success && typeof response.lead_id !== 'undefined' && response.lead_id) {
                try {
                    sessionStorage.setItem('bookingLeadId', response.lead_id);
                } catch (e) {}
                proceedToNextStep();
            } else {
                if (!$('#personalInfoForm').length) {
                    return;
                }
                const message = response && response.message ? response.message : 'Could not save your details. Please try again.';
                Swal.fire({
                    icon: 'error',
                    title: 'Save failed',
                    text: message,
                    confirmButtonText: 'OK'
                });
                $submitBtn.prop('disabled', false);
            }
        }).fail(function(jqXHR, textStatus, errorThrown) {
            if (textStatus === 'abort') {
                return;
            }
            if (!$('#personalInfoForm').length) {
                return;
            }
            Swal.fire({
                icon: 'error',
                title: 'Network error',
                text: 'Unable to save your details (' + textStatus + '). Please check your connection and try again.',
                confirmButtonText: 'OK'
            });
            $submitBtn.prop('disabled', false);
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
            $('#city').val(normalizeNA(data.city));
            $('#state').val(normalizeNA(data.state));
            $('#address1').val(data.address1 || '');
            $('#email').val(data.email || '');
            $('#phone').val(data.phone || '');

            // Persist normalized values back to sessionStorage so N/A never repopulates
            data.city = normalizeNA(data.city);
            data.state = normalizeNA(data.state);
            try {
                sessionStorage.setItem('personalInfo', JSON.stringify(data));
            } catch (e) {}

            // Validate pre-filled fields
            setTimeout(function() {
                try {
                    $('#firstName, #lastName, #city, #state, #address1, #phone').each(function() {
                        validateField($(this).attr('id'));
                    });
                    validateField('email', 'email');
                    $('#country, #countryCode').each(function() {
                        validateField($(this).attr('id'));
                    });

                    // Re-apply defaults if still empty after loading cache
                    if (!$('#country').val()) {
                        $('#country').val('Australia');
                    }
                    if (!$('#countryCode').val()) {
                        $('#countryCode').val('+61');
                    }
                    updateSubmitButton();
                } catch (e) {
                    console.error('Error loading saved data:', e);
                }
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