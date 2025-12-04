<?php $currentStep = 3; ?>
<!-- Header already loaded in main index.php, no need to include again -->

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

    .password-requirements {
        font-size: 0.85rem;
        color: #6c757d;
        margin-top: 0.25rem;
        line-height: 1.5;
    }

    .password-requirements ul {
        margin: 5px 0;
        padding-left: 20px;
    }

    .password-requirements li {
        margin: 3px 0;
    }

    .password-requirements .met {
        color: #28a745;
    }

    .password-requirements .unmet {
        color: #dc3545;
    }

    .section-title {
        font-size: 1.3rem;
        font-weight: 600;
        color: #2c3e50;
        margin: 30px 0 20px;
        padding-bottom: 10px;
        border-bottom: 2px solid #e0e0e0;
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

    .position-relative {
        position: relative;
    }

    .toggle-password {
        position: absolute;
        right: 10px;
        top: 50%;
        transform: translateY(-50%);
        background: none;
        border: none;
        color: #6c757d;
        cursor: pointer;
        padding: 5px 10px;
    }

    .toggle-password:hover {
        color: #dc3545;
    }

    @media (max-width: 768px) {
        .form-card {
            padding: 30px 20px;
        }
    }
</style>

<div class="container">
    <div class="form-card">
        <a href="javascript:void(0);" class="back-button" onclick="loadStep('personal-info')">
            <i class="fas fa-chevron-left"></i> Back
        </a>
        
        <h2>Additional Details</h2>
        <p class="subtitle">Provide identification and access credentials to verify your identity.</p>

        <form id="additionalDetailsForm" novalidate>
            <div class="mb-3">
                <label for="dob" class="form-label">Date of Birth<span class="required">*</span></label>
                <input type="date" class="form-control" id="dob" name="dob" required>
                <div class="invalid-feedback">Date of Birth is required.</div>
                <div class="valid-feedback">Looks good!</div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="licenseState" class="form-label">License State<span class="required">*</span></label>
                    <select class="form-select" id="licenseState" name="licenseState" required>
                        <option value="">Select License State</option>
                        <option value="NSW">New South Wales (NSW)</option>
                        <option value="VIC">Victoria (VIC)</option>
                        <option value="QLD">Queensland (QLD)</option>
                        <option value="WA">Western Australia (WA)</option>
                        <option value="SA">South Australia (SA)</option>
                        <option value="TAS">Tasmania (TAS)</option>
                        <option value="ACT">Australian Capital Territory (ACT)</option>
                        <option value="NT">Northern Territory (NT)</option>
                    </select>
                    <div class="invalid-feedback">License State is required.</div>
                    <div class="valid-feedback">Looks good!</div>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="licenseNumber" class="form-label">License Number<span class="required">*</span></label>
                    <input type="text" class="form-control" id="licenseNumber" name="licenseNumber" placeholder="Enter License Number" required>
                    <div class="invalid-feedback">License Number is required.</div>
                    <div class="valid-feedback">Looks good!</div>
                </div>
            </div>

            <div class="section-title">Alternative Contact (Optional)</div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="altFirstName" class="form-label">Alternative First Name</label>
                    <input type="text" class="form-control" id="altFirstName" name="altFirstName" placeholder="Alternative First Name">
                </div>
                <div class="col-md-6 mb-3">
                    <label for="altLastName" class="form-label">Alternative Last Name</label>
                    <input type="text" class="form-control" id="altLastName" name="altLastName" placeholder="Alternative Last Name">
                </div>
            </div>

            <div class="mb-3">
                <label for="altPhone" class="form-label">Alternative Phone</label>
                <input type="tel" class="form-control" id="altPhone" name="altPhone" placeholder="Alternative Phone">
                <div class="invalid-feedback">Alternative contact number must be different from the primary phone number.</div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="startDate" class="form-label">Start Date<span class="required">*</span></label>
                    <input type="date" class="form-control" id="startDate" name="startDate" required>
                    <div class="invalid-feedback">Start date is required.</div>
                    <div class="valid-feedback">Looks good!</div>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="webPassword" class="form-label">Web Password<span class="required">*</span></label>
                    <div class="position-relative">
                        <input type="password" class="form-control" id="webPassword" name="webPassword" placeholder="Create a password" required>
                        <button type="button" class="toggle-password" data-target="#webPassword" tabindex="-1">
                            <i class="fas fa-eye"></i>
                        </button>
                    </div>
                    <div class="password-requirements" id="passwordRequirements">
                        Password must contain:
                        <ul>
                            <li id="req-length" class="unmet">At least 8 characters</li>
                            <li id="req-uppercase" class="unmet">At least one uppercase letter (A-Z)</li>
                            <li id="req-number" class="unmet">At least one number (0-9)</li>
                            <li id="req-special" class="unmet">At least one special character (!@#$%^&*)</li>
                        </ul>
                    </div>
                    <div class="invalid-feedback">Please meet all password requirements.</div>
                    <div class="valid-feedback">Strong password!</div>
                </div>
            </div>

            <input type="hidden" id="endDate" name="endDate">

            <button type="submit" class="btn-continue" id="submitBtn" disabled>Continue to Insurance & Terms</button>
        </form>
    </div>
</div>

<!-- Libraries already loaded in main header -->
<script>
// Validate individual field with visual feedback
function validateField(id, showFeedback = true) {
    const $el = $('#' + id);
    const val = $el.val().trim();
    let isValid = false;

    if ($el.prop('disabled')) return true;

    // Check if field has value
    if (id === 'webPassword') {
        isValid = validatePassword(val, showFeedback);
    } else {
        isValid = val !== '';
    }

    if (showFeedback) {
        $el.removeClass('is-invalid is-valid');
        if (val === '') {
            // Empty - no class
        } else if (isValid) {
            $el.addClass('is-valid');
        } else {
            $el.addClass('is-invalid');
        }
    }

    return isValid;
}

// Validate password with live requirements feedback
function validatePassword(password, showFeedback = true) {
    const hasLength = password.length >= 8;
    const hasUppercase = /[A-Z]/.test(password);
    const hasNumber = /\d/.test(password);
    const hasSpecial = /[!@#$%^&*()_\-+=\[\]{};':"\\|,.<>/?]/.test(password);
    
    if (showFeedback) {
        $('#req-length').removeClass('met unmet').addClass(hasLength ? 'met' : 'unmet');
        $('#req-uppercase').removeClass('met unmet').addClass(hasUppercase ? 'met' : 'unmet');
        $('#req-number').removeClass('met unmet').addClass(hasNumber ? 'met' : 'unmet');
        $('#req-special').removeClass('met unmet').addClass(hasSpecial ? 'met' : 'unmet');
    }
    
    return hasLength && hasUppercase && hasNumber && hasSpecial;
}

function calculateEndDateFromStart(startDateValue) {
    if (!startDateValue) {
        return '';
    }

    const startDate = new Date(startDateValue);

    if (isNaN(startDate.getTime())) {
        return '';
    }

    const endDate = new Date(startDate);
    endDate.setMonth(endDate.getMonth() + 1);
    endDate.setDate(endDate.getDate() - 1);

    const year = endDate.getFullYear();
    const month = String(endDate.getMonth() + 1).padStart(2, '0');
    const day = String(endDate.getDate()).padStart(2, '0');

    return `${day}-${month}-${year}`;
}

// Check if entire form is valid (silent check for button state)
function isFormValid() {
    const dob = $('#dob').val().trim();
    const licenseState = $('#licenseState').val().trim();
    const licenseNumber = $('#licenseNumber').val().trim();
    const startDate = $('#startDate').val().trim();
    const password = $('#webPassword').val().trim();
    
    // Check required fields
    if (!dob || !licenseState || !licenseNumber || !startDate) {
        return false;
    }
    
    // Validate password requirements
    if (!validatePassword(password, false)) {
        return false;
    }
    
    // Check alternative phone if provided
    const altPhone = $('#altPhone').val().trim();
    if (altPhone) {
        const personalInfo = JSON.parse(sessionStorage.getItem('personalInfo') || '{}');
        const mainPhone = personalInfo.phone || '';
        if (altPhone === mainPhone) {
            return false;
        }
    }
    
    return true;
}

// Update submit button state
function updateSubmitButton() {
    $('#submitBtn').prop('disabled', !isFormValid());
}

// Validate entire form with visual feedback
function validateEntireForm() {
    let valid = true;
    
    // Validate required fields
    valid &= validateField('dob');
    valid &= validateField('licenseState');
    valid &= validateField('licenseNumber');
    valid &= validateField('startDate');
    valid &= validateField('webPassword');
    
    // Check alternative phone
    const personalInfo = JSON.parse(sessionStorage.getItem('personalInfo') || '{}');
    const mainPhone = personalInfo.phone || '';
    const altPhone = $('#altPhone').val().trim();
    
    if (altPhone !== '' && mainPhone === altPhone) {
        $('#altPhone').addClass('is-invalid');
        valid = false;
    } else {
        $('#altPhone').removeClass('is-invalid');
    }
    
    return Boolean(valid);
}

$(document).ready(function() {
    // console.log('Additional Details page loaded');
    
    // Reinitialize header search autocomplete if available
    setTimeout(function() {
        if (typeof window.initHeaderAutocomplete === 'function') {
            // console.log('Reinitializing header search...');
            window.initHeaderAutocomplete();
        }
    }, 1000);
    
    // Real-time validation on input/change
    $('#dob, #licenseNumber, #licenseState, #startDate').on('input change blur', function() {
        validateField($(this).attr('id'));
        updateSubmitButton();
    });
    
    // Password validation with live feedback
    $('#webPassword').on('input', function() {
        validateField('webPassword');
        updateSubmitButton();
    });
    
    // Alternative phone validation
    $('#altPhone').on('input blur', function() {
        const altPhone = $(this).val().trim();
        if (altPhone) {
            const personalInfo = JSON.parse(sessionStorage.getItem('personalInfo') || '{}');
            const mainPhone = personalInfo.phone || '';
            
            if (altPhone === mainPhone) {
                $(this).addClass('is-invalid');
            } else {
                $(this).removeClass('is-invalid');
            }
        } else {
            $(this).removeClass('is-invalid');
        }
        updateSubmitButton();
    });
    
    // Calculate end date when start date changes and enforce minimum (tomorrow)
    $('#startDate').on('change', function() {
        let startDateValue = $(this).val();
        const min = $(this).attr('min');

        // If user somehow selects or types a date before the minimum, clamp to min
        if (min && startDateValue && startDateValue < min) {
            startDateValue = min;
            $(this).val(min);
        }

        const calculatedEndDate = calculateEndDateFromStart(startDateValue);

        if (calculatedEndDate) {
            $('#endDate').val(calculatedEndDate);
        }
    });

    // Toggle password visibility
    $(document).on('click', '.toggle-password', function() {
        const $btn = $(this);
        const $input = $($btn.data('target'));
        const $icon = $btn.find('i');

        const isPassword = $input.attr('type') === 'password';
        $input.attr('type', isPassword ? 'text' : 'password');
        $icon.toggleClass('fa-eye fa-eye-slash');
    });

    // Only allow numbers in license number
    $('#licenseNumber').on('keypress', function(e) {
        if (!/\d/.test(String.fromCharCode(e.which))) return false;
    });

    // Form submission
    $('#additionalDetailsForm').on('submit', function(e) {
        e.preventDefault();

        if (!validateEntireForm()) {
            Swal.fire({
                icon: 'error',
                title: 'Incomplete Form',
                text: 'Please fill in all required fields correctly and meet all password requirements.',
                confirmButtonText: 'OK',
                buttonsStyling: false,
                customClass: {
                    confirmButton: 'btn btn-custom-primary me-2'
                }
            });
            return false;
        }

        const formData = {
            dob: $('#dob').val(),
            licenseState: $('#licenseState').val(),
            licenseNumber: $('#licenseNumber').val(),
            altFirstName: $('#altFirstName').val(),
            altLastName: $('#altLastName').val(),
            altPhone: $('#altPhone').val(),
            startDate: $('#startDate').val(),
            endDate: $('#endDate').val(),
            webPassword: $('#webPassword').val()
        };

        sessionStorage.setItem('additionalDetails', JSON.stringify(formData));

        Swal.fire({
            icon: 'success',
            title: 'Details Saved!',
            text: 'Proceeding to insurance and terms...',
            timer: 1500,
            showConfirmButton: false
        }).then(() => {
            if (typeof loadStep === 'function') {
                loadStep('insurance-terms');
            } else {
                window.location.href = '<?php echo base_url('mammoth-storage/insurance-terms'); ?>';
            }
        });
    });

    // Load saved data if exists
    const savedData = sessionStorage.getItem('additionalDetails');
    if (savedData) {
        try {
            const data = JSON.parse(savedData);
            $('#dob').val(data.dob || '');
            $('#licenseState').val(data.licenseState || '');
            $('#licenseNumber').val(data.licenseNumber || '');
            $('#altFirstName').val(data.altFirstName || '');
            $('#altLastName').val(data.altLastName || '');
            $('#altPhone').val(data.altPhone || '');
            $('#startDate').val(data.startDate || '');
            $('#endDate').val(data.endDate || calculateEndDateFromStart(data.startDate || ''));
            $('#webPassword').val(data.webPassword || '');
            
            // Validate pre-filled fields
            setTimeout(function() {
                $('#dob, #licenseState, #licenseNumber, #startDate').each(function() {
                    validateField($(this).attr('id'));
                });
                validateField('webPassword');
                updateSubmitButton();
            }, 100);
        } catch (e) {
            console.error('Error loading saved data:', e);
        }
    }

    // Enforce minimum start date based on location lead time
    (function enforceStartDateMinimum() {
        const today = new Date();
        today.setHours(0, 0, 0, 0);
        
        // Use currentLeadTimeDays from parent window (set when location is selected)
        let leadTimeDays = 1;
        try {
            if (window.parent && typeof window.parent.currentLeadTimeDays !== 'undefined') {
                const rawLead = window.parent.currentLeadTimeDays;
                const parsedLead = parseInt(rawLead, 10);
                if (!isNaN(parsedLead) && parsedLead >= 0) {
                    leadTimeDays = parsedLead; // allow 0 for same-day
                }
            }
        } catch (e) {
            leadTimeDays = 1;
        }
        
        const minDate = new Date(today);
        minDate.setDate(minDate.getDate() + leadTimeDays);

        const year = minDate.getFullYear();
        const month = String(minDate.getMonth() + 1).padStart(2, '0');
        const day = String(minDate.getDate()).padStart(2, '0');
        const minStart = `${year}-${month}-${day}`;

        const $start = $('#startDate');
        $start.attr('min', minStart);

        // Always default the visible start date to the earliest
        // allowed date for the current location lead time.
        $start.val(minStart);

        const calculatedEndDate = calculateEndDateFromStart(minStart);
        if (calculatedEndDate) {
            $('#endDate').val(calculatedEndDate);
        }
    })();
    
    // Initial button state check
    updateSubmitButton();
});
</script>

<?php include(APPPATH . 'views/storage/mammoth-storage/partials/footer-steps.php'); ?>