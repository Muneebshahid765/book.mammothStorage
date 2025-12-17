<?php $currentStep = 5; ?>
<!-- Header already loaded in main index.php -->

<style>
    .form-card {
        background: white;
        border-radius: 12px;
        padding: 40px;
        max-width: 800px;
        margin: 0 auto 40px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
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

    .form-control,
    .form-select {
        border: 2px solid #e0e0e0;
        border-radius: 8px;
        padding: 12px 15px;
        font-size: 0.95rem;
        transition: border-color 0.3s;
    }

    .form-select {
        padding-right: 2.5rem;
        background-position: right 0.85rem center;
        background-repeat: no-repeat;
        background-size: 0.85rem;
    }

    .form-control:focus,
    .form-select:focus {
        border-color: #dc3545;
        box-shadow: 0 0 0 0.2rem rgba(220, 53, 69, 0.15);
        outline: none;
    }

    .form-control.is-invalid,
    .form-select.is-invalid {
        border-color: #dc3545;
    }

    .invalid-feedback {
        display: none;
        color: #dc3545;
        font-size: 0.875rem;
        margin-top: 0.25rem;
    }

    .form-control.is-invalid~.invalid-feedback,
    .form-select.is-invalid~.invalid-feedback {
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
        transition: background-color 0.3s;
        margin-top: 30px;
    }

    .btn-continue:hover {
        background-color: #c82333;
    }

    .btn-continue:disabled {
        background-color: #ccc;
        cursor: not-allowed;
    }

    .booking-summary {
        background-color: #f8f9fa;
        border-radius: 8px;
        padding: 25px;
        margin-top: 30px;
        margin-bottom: 20px;
    }

    .booking-summary h3 {
        font-size: 1.3rem;
        font-weight: 600;
        color: #2c3e50;
        margin-bottom: 20px;
    }

    .summary-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 12px 0;
        border-bottom: 1px solid #dee2e6;
    }

    .summary-row:last-child {
        border-bottom: none;
        padding-top: 15px;
        font-weight: 600;
    }

    .summary-label {
        color: #495057;
        font-size: 0.95rem;
    }

    .summary-value {
        color: #2c3e50;
        font-weight: 500;
        text-align: right;
    }

    .summary-row:last-child .summary-value {
        color: #dc3545;
        font-size: 1.1rem;
    }

    @media (max-width: 768px) {
        .form-card {
            padding: 30px 20px;
        }
    }

    /* Loading Spinner Overlay */
    #spinner-overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.5);
        display: none;
        justify-content: center;
        align-items: center;
        z-index: 9999;
    }

    .spinner {
        border: 4px solid #f3f3f3;
        border-top: 4px solid #dc3545;
        border-radius: 50%;
        width: 50px;
        height: 50px;
        animation: spin 1s linear infinite;
    }

    @keyframes spin {
        0% {
            transform: rotate(0deg);
        }

        100% {
            transform: rotate(360deg);
        }
    }
</style>

<div id="spinner-overlay" style="display: none;">
    <div class="spinner"></div>
</div>

<div class="container">
    <div class="row">
        <div class="col-lg-8">
            <div class="form-card">
                <a href="javascript:void(0);" class="back-button" onclick="loadStep('insurance-terms')">
                    <i class="fas fa-chevron-left"></i> Back
                </a>

                <h2>Payment Information</h2>
                <p class="subtitle">Enter your billing and payment details to complete the reservation.</p>

                <form id="paymentForm" novalidate>
                    <h3
                        style="font-size: 1.3rem; font-weight: 600; color: #2c3e50; margin-bottom: 20px; padding-bottom: 10px; border-bottom: 2px solid #e0e0e0;">
                        Billing Address</h3>

                    <div class="mb-3">
                        <label for="billingName" class="form-label">Full Name</label>
                        <input type="text" class="form-control" id="billingName" name="billingName"
                            placeholder="Enter full name" required>
                        <div class="invalid-feedback">Full name is required.</div>
                    </div>

                    <div class="mb-3">
                        <label for="billingAddress" class="form-label">Street Address</label>
                        <input type="text" class="form-control" id="billingAddress" name="billingAddress"
                            placeholder="Enter street address" required>
                        <div class="invalid-feedback">Street address is required.</div>
                    </div>

                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label for="billingCity" class="form-label">City</label>
                            <input type="text" class="form-control" id="billingCity" name="billingCity"
                                placeholder="Enter city" required>
                            <div class="invalid-feedback">City is required.</div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="billingState" class="form-label">State</label>
                            <select class="form-select" id="billingState" name="billingState" required>
                                <option value="">Select State</option>
                                <option value="NSW">New South Wales (NSW)</option>
                                <option value="VIC">Victoria (VIC)</option>
                                <option value="QLD">Queensland (QLD)</option>
                                <option value="WA">Western Australia (WA)</option>
                                <option value="SA">South Australia (SA)</option>
                                <option value="TAS">Tasmania (TAS)</option>
                                <option value="ACT">Australian Capital Territory (ACT)</option>
                                <option value="NT">Northern Territory (NT)</option>
                            </select>
                            <div class="invalid-feedback">State is required.</div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="billingZip" class="form-label">Postcode</label>
                            <input type="text" class="form-control" id="billingZip" name="billingZip"
                                placeholder="Enter postcode" maxlength="4" required>
                            <div class="invalid-feedback">Postcode is required.</div>
                        </div>
                    </div>

                    <h3
                        style="font-size: 1.3rem; font-weight: 600; color: #2c3e50; margin: 30px 0 20px; padding-bottom: 10px; border-bottom: 2px solid #e0e0e0;">
                        Payment Details</h3>

                    <div class="row">
                        <div class="col-md-3 mb-3">
                            <label for="cardType" class="form-label">Card Type</label>
                            <select class="form-select" id="cardType" name="cardType" required>
                                <option value="">Select Card Type</option>
                                <option value="5">Master Card</option>
                                <option value="6">Visa</option>
                            </select>
                            <div class="invalid-feedback">Card type is required.</div>
                        </div>
                        <div class="col-md-9 mb-3">
                            <label for="cardNumber" class="form-label">Card Number</label>
                            <input type="text" class="form-control" id="cardNumber" name="cardNumber"
                                placeholder="Card Number" maxlength="16" required>
                            <div class="invalid-feedback">Card number must be 16 digits.</div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label for="expYear" class="form-label">Expiration Year</label>
                            <select class="form-select" id="expYear" name="expYear" required>
                                <option value="">Expiration Year</option>
                            </select>
                            <div class="invalid-feedback">Expiration year is required.</div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="expMonth" class="form-label">Expiration Month</label>
                            <select class="form-select" id="expMonth" name="expMonth" required>
                                <option value="">Expiration Month</option>
                            </select>
                            <div class="invalid-feedback">Expiration month is required.</div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="cvv" class="form-label">CVV</label>
                            <input type="text" class="form-control" id="cvv" name="cvv" placeholder="CVV" maxlength="3"
                                required>
                            <div class="invalid-feedback">CVV must be 3 digits.</div>
                        </div>
                    </div>

                    <button type="submit" class="btn-continue" id="submitBtn">Complete Booking</button>
                </form>
            </div>
        </div>
        <div class="col-lg-4 mt-4 mt-lg-0">
            <?php include(APPPATH . 'views/storage/mammoth-storage/partials/checkout-summary-partial.php'); ?>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    // Note: base_url is already defined in index.php

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

        $el.toggleClass('is-invalid', !isValid);
        return isValid;
    }

    function validateCardNumber() {
        const $field = $('#cardNumber');
        const val = $field.val().trim();
        const isValid = /^\d{16}$/.test(val);
        $field.toggleClass('is-invalid', !isValid);
        return isValid;
    }

    function validateCVV() {
        const $field = $('#cvv');
        const val = $field.val().trim();
        const isValid = /^\d{3}$/.test(val);
        $field.toggleClass('is-invalid', !isValid);
        return isValid;
    }

    function step_8_validate() {
        let valid = true;
        valid &= validateField('billingName');
        valid &= validateField('billingAddress');
        valid &= validateField('billingCity');
        valid &= validateField('billingState');
        valid &= validateField('billingZip');
        valid &= validateField('cardType');
        valid &= validateCardNumber();
        valid &= validateCVV();
        valid &= validateField('expYear');
        valid &= validateField('expMonth');
        return Boolean(valid);
    }

    function populateExpYears() {
        const $year = $('#expYear');
        if ($year.length === 0) return;
        const currentYear = new Date().getFullYear();
        for (let i = 0; i < 10; i++) {
            $year.append(`<option value="${currentYear + i}">${currentYear + i}</option>`);
        }
    }

    function populateExpMonths(selectedYear) {
        const $month = $('#expMonth');
        const currentYear = new Date().getFullYear();
        const currentMonth = new Date().getMonth();

        let startMonth = 0;
        if (parseInt(selectedYear) === currentYear) {
            startMonth = currentMonth;
        }

        $month.empty().append('<option value="">Expiration Month</option>');
        for (let i = startMonth; i < 12; i++) {
            const monthVal = String(i + 1).padStart(2, '0');
            $month.append(`<option value="${monthVal}">${monthVal}</option>`);
        }
    }

    function capitalizeFirstLetter(str) {
        if (!str) return "";
        return str.charAt(0).toUpperCase() + str.slice(1).toLowerCase();
    }

    async function searchTenant(formData) {
        try {
            const response = await $.ajax({
                url: 'https://checkout.mammothstorage.com.au/api/v1/tenant/search',
                type: "POST",
                contentType: "application/json",
                data: JSON.stringify(formData)
            });
            return (response.success && response.data && response.data.tenant) ? response.data.tenant : null;
        } catch (error) {
            console.error("Unable to search for tenant:", error);
            return null;
        }
    }

    async function createTenant(formData) {
        try {
            const response = await $.ajax({
                url: 'https://checkout.mammothstorage.com.au/api/v1/tenant/create',
                type: "POST",
                contentType: "application/json",
                data: JSON.stringify(formData)
            });

            if (response.success && response.data && response.data.tenant) {
                // Include tenant_unit_informations_id in the returned tenant object
                const tenant = response.data.tenant;
                tenant.tenant_unit_informations_id = response.data.tenant_unit_informations_id || '';
                return tenant;
            } else {
                throw new Error(response.message || "Tenant creation failed.");
            }
        } catch (error) {
            console.error("Failed to create tenant:", error);
            throw error;
        }
    }

    async function processTenantMoveIn(formData) {
        try {
            const response = await $.ajax({
                url: 'https://checkout.mammothstorage.com.au/api/v1/tenant/move-in',
                type: "POST",
                contentType: "application/json",
                data: JSON.stringify(formData)
            });

            if (response.success && response.data?.move_in?.TenantID && response.data?.move_in?.LedgerID) {
                return response.data;
            } else {
                throw new Error(response.message || "Move-in process failed.");
            }
        } catch (error) {
            console.error("Unable to process move-in:", error);
            throw error;
        }
    }

    async function sendWelcomeEmail(formData) {
        try {
            // Log signature data for debugging (first 100 chars only)
            if (formData.tenant_signature) {
                console.log('Signature data being sent (first 100 chars):', formData.tenant_signature.substring(0, 100));
                console.log('Signature data total length:', formData.tenant_signature.length);
            } else {
                console.warn('WARNING: No signature data in formData.tenant_signature');
            }

            // Request response as plain text so we can safely handle any HTML
            // warnings or error traces that may be prepended before the JSON.
            const rawResponse = await $.ajax({
                url: "https://checkout.mammothstorage.com.au/api/v1/customers/welcome-email",
                type: "POST",
                contentType: "application/json",
                data: JSON.stringify(formData),
                dataType: "text"
            });

            let responseText = typeof rawResponse === 'string' ? rawResponse : String(rawResponse || '');

            // Try to locate the beginning of the JSON payload. This makes the
            // code resilient to any HTML noise printed before the JSON.
            const jsonStart = responseText.indexOf('{');
            if (jsonStart > 0) {
                responseText = responseText.substring(jsonStart);
            }

            let response;
            try {
                response = JSON.parse(responseText);
            } catch (parseError) {
                console.error('Failed to parse welcome-email JSON response:', responseText);
                throw parseError;
            }

            if (response.success) {
                return response;
            } else {
                throw new Error(response.message || "Email sending failed.");
            }
        } catch (error) {
            // Log detailed information to help debug backend issues
            if (error && error.responseText !== undefined) {
                console.error("Error sending welcome email (response):", error.status, error.responseText);
            } else {
                console.error("Error sending welcome email:", error);
            }
            throw error;
        }
    }

    function collectTenantCreateFormData() {
        const personalInfo = JSON.parse(sessionStorage.getItem('personalInfo') || '{}');
        const additionalDetails = JSON.parse(sessionStorage.getItem('additionalDetails') || '{}');
        const bookingData = JSON.parse(sessionStorage.getItem('bookingData') || '{}');

        // Get billing info directly from form (non-sensitive billing address only)
        const billingName = capitalizeFirstLetter($('#billingName').val() || '');
        const billingAddress = capitalizeFirstLetter($('#billingAddress').val() || '');
        const billingCity = capitalizeFirstLetter($('#billingCity').val() || '');
        const billingState = $('#billingState').val() || '';
        const billingZip = $('#billingZip').val() || '';

        return {
            billing_name: billingName,
            billing_address: billingAddress,
            billing_city: billingCity,
            billing_state: billingState,
            billing_zipcode: billingZip,
            customer_firstname: personalInfo.firstName || '',
            customer_lastname: personalInfo.lastName || '',
            customer_phone: personalInfo.phone || personalInfo.mobileNumber || '',
            customer_country: personalInfo.country || 'Australia',
            customer_country_code: personalInfo.countryCode || 'AU',
            customer_city: billingCity || personalInfo.city || '',
            customer_state: billingState || personalInfo.state || additionalDetails.licenseState || '',
            customer_dob: additionalDetails.dob || additionalDetails.dateOfBirth || '',
            customer_email: personalInfo.email || '',
            customer_license: additionalDetails.licenseNumber || '',
            customer_license_state: additionalDetails.licenseState || '',
            customer_web_password: additionalDetails.webPassword || '',
            access_code: '',
            alt_customer_firstname: additionalDetails.altFirstName || '',
            alt_customer_lastname: additionalDetails.altLastName || '',
            alt_customer_phone: additionalDetails.altPhone || '',
            alt_customer_email: '',
            location_code: bookingData.locationCode || ''
        };
    }

    function collectMoveInFormData(tenantData) {
        const personalInfo = JSON.parse(sessionStorage.getItem('personalInfo') || '{}');
        const additionalDetails = JSON.parse(sessionStorage.getItem('additionalDetails') || '{}');
        const insuranceTerms = JSON.parse(sessionStorage.getItem('insuranceTerms') || '{}');
        const bookingData = JSON.parse(sessionStorage.getItem('bookingData') || '{}');

        // SECURITY: Get payment data directly from form - NEVER store in sessionStorage
        const billingName = capitalizeFirstLetter($('#billingName').val() || '');
        const billingAddress = capitalizeFirstLetter($('#billingAddress').val() || '');
        const billingCity = capitalizeFirstLetter($('#billingCity').val() || '');
        const billingState = $('#billingState').val() || '';
        const billingZip = $('#billingZip').val() || '';
        const cardType = $('#cardType').val() || '';
        const cardNumber = $('#cardNumber').val() || '';
        const cvv = $('#cvv').val() || '';
        const expYear = $('#expYear').val() || '';
        const expMonth = $('#expMonth').val() || '';

        let insurance = {
            option: insuranceTerms.insurance || 'noInsurance',
            coverage: insuranceTerms.coverage || '0',
            coverage_id: insuranceTerms.coverage_id || '0',
            premium: insuranceTerms.premium || '0'
        };

        const unit = bookingData.unit || {};
        const baseMonthly = parseFloat(unit.originalPrice || unit.price || '0') || 0;
        const discountedMonthly = parseFloat(unit.price || '0') || 0;

        const monthsToPay = parseInt(unit.monthsToPay, 10) || 0;
        const freeMonths = parseInt(unit.freeMonths, 10) || 0;
        const discountMonths = parseInt(unit.discountMonths, 10) || 0;
        const hasFreeMonthsSpecial = !!unit.hasFreeMonthsSpecial;
        const hasDiscountSpecial = !!unit.hasDiscountSpecial;

        // SiteLink requires FULL STANDARD RATE payment - concession plan discount is applied separately
        // Always send baseMonthly (original price) to SiteLink, the concession plan handles the discount
        let rentTotal = baseMonthly;

        // Payment calculation for different special types
        // IMPORTANT: SiteLink expects full standard rate, concession plan applies discount
        if (hasFreeMonthsSpecial && hasDiscountSpecial && monthsToPay > 0 && freeMonths > 0) {
            // Customer pays for monthsToPay at BASE rate, concession plan applies discount
            rentTotal = baseMonthly * monthsToPay;
        } else if (hasDiscountSpecial && discountMonths > 0) {
            // Discount special - charge BASE rate, concession plan applies discount
            rentTotal = baseMonthly * discountMonths;
        } else if (!hasDiscountSpecial && hasFreeMonthsSpecial && monthsToPay > 0 && freeMonths > 0) {
            // Free months special only - charge base rate for paying months
            rentTotal = baseMonthly * monthsToPay;
        } else {
            // No special - just charge 1 month at the base price
            rentTotal = baseMonthly;
        }

        // CRITICAL FIX: Ensure payment amount is never 0
        // If rentTotal is 0 (e.g., all months are free), charge at least 1 month's rent
        if (rentTotal === 0 || isNaN(rentTotal)) {
            console.warn('Payment amount was 0 or invalid, defaulting to 1 month rent:', baseMonthly);
            rentTotal = baseMonthly;
        }

        const premiumAmount = parseFloat(insurance.premium || '0') || 0;
        const paymentAmount = rentTotal.toFixed(2);
        const fullPaymentAmount = (rentTotal + premiumAmount).toFixed(2);

        // Debug logging
        console.log('Payment Calculation:', {
            baseMonthly,
            discountedMonthly,
            monthsToPay,
            freeMonths,
            discountMonths,
            hasFreeMonthsSpecial,
            hasDiscountSpecial,
            rentTotal,
            premiumAmount,
            paymentAmount,
            fullPaymentAmount
        });

        // Get concession plan ID for applying discount in SiteLink
        const concessionPlanId = unit.concessionPlanId || '';

        return {
            tenant: tenantData,
            unit: {
                unit_id: bookingData.unit?.unitId || '',
                concession_plan_id: concessionPlanId
            },
            billing: {

                billing_name: billingName,
                billing_address: billingAddress,
                billing_city: billingCity,
                billing_state: billingState,
                billing_zipcode: billingZip,
                // Base payment amount (full upfront rent for the special period)
                payment_amount: paymentAmount,
                // Full payment amount required by checkout API (rent + insurance premium)
                full_payment_amount: fullPaymentAmount
            },

            move: {
                move_in_start_date: additionalDetails.startDate || additionalDetails.dateOfBirth || '',
                move_in_end_date: additionalDetails.endDate || additionalDetails.startDate || ''
            },
            card: {
                type: cardType,
                number: cardNumber,
                cvv: cvv,
                year: expYear,
                month: expMonth
            },
            insurance: insurance
        };
    }

    function collectWelcomeEmailData() {
        const personalInfo = JSON.parse(sessionStorage.getItem('personalInfo') || '{}');
        const additionalDetails = JSON.parse(sessionStorage.getItem('additionalDetails') || '{}');
        const insuranceTerms = JSON.parse(sessionStorage.getItem('insuranceTerms') || '{}');
        const bookingData = JSON.parse(sessionStorage.getItem('bookingData') || '{}');

        const billingAddress = capitalizeFirstLetter($('#billingAddress').val() || '');
        const billingCity = capitalizeFirstLetter($('#billingCity').val() || '');
        const billingState = $('#billingState').val() || '';
        const billingZip = $('#billingZip').val() || '';

        let insurance = {
            option: insuranceTerms.insurance || 'noInsurance',
            coverage: insuranceTerms.coverage || '0',
            coverage_id: insuranceTerms.coverage_id || '0',
            premium: insuranceTerms.premium || '0'
        };

        const signatureDataUrl = insuranceTerms.signature || '';

        const altFullName = `${(additionalDetails.altFirstName || '').trim()} ${(additionalDetails.altLastName || '').trim()} `.trim();

        // Determine base monthly storage fee for the agreement (non-discounted when available)
        const baseMonthlyPrice = (function () {
            const original = bookingData.unit?.originalPrice;
            const current = bookingData.unit?.price;

            // Prefer original/standard rate when present, otherwise fall back to current price
            const raw = (original !== undefined && original !== null && original !== '') ? original : current;
            const parsed = parseFloat(raw || '0');
            return isNaN(parsed) ? 0 : parsed;
        })();

        // Build unit size including floor/type information where available
        // Format: size first, then label (e.g., '3m × 1.5m, Drive up Access' or '3m × 1.5m, Ground Floor')
        let unitSize = bookingData.unit?.size || '';

        // Prefer human-readable label from booking data (Drive up Access, Ground Floor, Level 1, etc.)
        let floorLabel = (bookingData.unit && (bookingData.unit.floorLabel || bookingData.unit.building)) || '';

        // Fallback to numeric floor only if no label string is available
        if (!floorLabel && bookingData.unit && bookingData.unit.floor !== undefined && bookingData.unit.floor !== null && bookingData.unit.floor !== '') {
            const floorNumber = bookingData.unit.floor;
            if (floorNumber === 0 || floorNumber === '0') {
                floorLabel = 'Ground Floor';
            } else {
                floorLabel = `Floor ${floorNumber}`;
            }
        }

        if (floorLabel) {
            if (unitSize) {
                unitSize = `${unitSize}, ${floorLabel}`;
            } else {
                unitSize = floorLabel;
            }
        }

        // Extract only block name from unitDesc (e.g., 'Block 2' or 'E Block')
        // unitDesc might be like 'Block 2' or 'E Block', we want just that, not floor/size
        let buildingName = bookingData.unit?.unitDesc || bookingData.unit?.building || '';
        // If buildingName contains comma or extra info, take only the first part
        if (buildingName.includes(',')) {
            buildingName = buildingName.split(',')[0].trim();
        }

        return {
            customer_fname: personalInfo.firstName,
            customer_lname: personalInfo.lastName,
            customer_name: `${personalInfo.firstName} ${personalInfo.lastName}`,
            customer_email: personalInfo.email,
            location_name: bookingData.locationName || '',
            billing_anniversary: additionalDetails.startDate,
            unit_number: bookingData.unit?.unitName || '',
            building: buildingName,
            access_code: '',
            padlock_code: '1243',
            start_date: additionalDetails.startDate,
            unit_size: unitSize,
            // Use base (non-discounted) monthly fee in the agreement payload
            price: baseMonthlyPrice.toFixed(2),
            location_code: bookingData.locationCode || '',
            insurance: insurance,
            tenant_address1: billingAddress || personalInfo.address1 || '',
            tenant_city: billingCity || personalInfo.city || '',
            tenant_region: billingState || personalInfo.state || additionalDetails.licenseState || '',
            tenant_postcode: billingZip || personalInfo.postcode || '',
            tenant_mobile: personalInfo.phone || personalInfo.mobileNumber || '',
            tenant_driving_license: additionalDetails.licenseNumber || '',
            tenant_alt_name: altFullName,
            tenant_alt_mobile: additionalDetails.altPhone || '',
            tenant_signature: signatureDataUrl
        };
    }

    function collectLegacyOrderData(tenant, moveInResult, legacyMeta = {}) {
        const personalInfo = JSON.parse(sessionStorage.getItem('personalInfo') || '{}');
        const additionalDetails = JSON.parse(sessionStorage.getItem('additionalDetails') || '{}');
        const insuranceTerms = JSON.parse(sessionStorage.getItem('insuranceTerms') || '{}');
        const bookingData = JSON.parse(sessionStorage.getItem('bookingData') || '{}');

        const billingName = capitalizeFirstLetter($('#billingName').val() || '');
        const billingAddress = capitalizeFirstLetter($('#billingAddress').val() || '');
        const billingCity = capitalizeFirstLetter($('#billingCity').val() || '');
        const billingState = $('#billingState').val() || '';
        const billingZip = $('#billingZip').val() || '';
        const cardType = $('#cardType').val() || '';
        const cardNumber = $('#cardNumber').val() || '';
        const cvv = $('#cvv').val() || '';
        const expYear = $('#expYear').val() || '';
        const expMonth = $('#expMonth').val() || '';

        const sanitizedCard = cardNumber.replace(/\s+/g, '');
        const cardLastFour = sanitizedCard ? sanitizedCard.slice(-4) : '';
        const cardExpiry = expMonth && expYear ? `${expMonth}/${expYear}` : '';

        let termsDescription = '';
        if (insuranceTerms.insurance === 'storeProtect') {
            termsDescription = 'Accepts StoreProtect Policy';
        } else if (insuranceTerms.insurance === 'ownInsurance') {
            termsDescription = 'Has own insurance.';
        } else if (insuranceTerms.insurance === 'noInsurance') {
            termsDescription = 'Accepts no insurance and knows it is liable for loss.';
        }

        // Extract LedgerID from moveInResult - try multiple paths
        let ledgerId = '';
        if (moveInResult && moveInResult.move_in && moveInResult.move_in.LedgerID) {
            ledgerId = moveInResult.move_in.LedgerID;
        } else if (moveInResult && moveInResult.data && moveInResult.data.move_in && moveInResult.data.move_in.LedgerID) {
            ledgerId = moveInResult.data.move_in.LedgerID;
        }

        const altFullName = `${(additionalDetails.altFirstName || '').trim()} ${(additionalDetails.altLastName || '').trim()}`.trim();

        return {
            tenant_firstname: personalInfo.firstName || '',
            tenant_lastname: personalInfo.lastName || '',
            tenant_businessname: personalInfo.businessName || '',
            tenant_email: personalInfo.email || '',
            tenant_mobile: personalInfo.phone || personalInfo.mobileNumber || '',
            tenant_driving_license: additionalDetails.licenseNumber || '',
            tenant_alt_name: altFullName,
            tenant_alt_mobile: additionalDetails.altPhone || '',
            tenant_address1: billingAddress || personalInfo.address1 || '',
            tenant_address2: '',
            tenant_suburb: billingCity || personalInfo.city || '',
            tenant_state: billingState || personalInfo.state || additionalDetails.licenseState || '',
            tenant_postcode: billingZip || personalInfo.postcode || '',
            tenant_unit_name: bookingData.unit?.unitName || '',
            tenant_rental_with_tax: bookingData.unit?.price || '0',
            tenant_accessCode: tenant && tenant.sAccessCode ? tenant.sAccessCode : '',
            move_in_date: additionalDetails.startDate || '',
            anticipated_length_of_stay: insuranceTerms.anticipated_length_of_stay || '',
            reason_for_storage: insuranceTerms.reason_for_storage || '',
            terms_and_conditions: termsDescription,
            maximum_replacement_value: insuranceTerms.coverage || '0',
            tenant_card_type: cardType,
            tenant_cardholder_name: billingName,
            tenant_card_last_four: cardLastFour,
            tenant_card_expiry_date: cardExpiry,
            tenant_card_cvv: cvv,
            location_code: bookingData.locationCode || '',
            unit_name: bookingData.unit?.unitName || '',
            tenant_id: tenant.TenantID || '',
            ledger_id: ledgerId,
            token: legacyMeta.token || '',
            tenant_unit_informations_id: legacyMeta.tenant_unit_informations_id || ''
        };
    }

    async function logLegacyOrder(orderData) {
        try {
            console.log('Logging legacy order data:', orderData);
            return { success: true };
        } catch (error) {
            console.error('logLegacyOrder error:', error);
            return { success: false, error: error.message };
        }
    }

    async function processFinalSubmission() {
        const $submitBtn = $('#submitBtn');
        $submitBtn.prop('disabled', true).text('Processing...');
        $('#spinner-overlay').css('display', 'flex');

        try {
            const personalInfo = JSON.parse(sessionStorage.getItem('personalInfo') || '{}');
            const additionalDetails = JSON.parse(sessionStorage.getItem('additionalDetails') || '{}');
            const bookingData = JSON.parse(sessionStorage.getItem('bookingData') || '{}');

            const tenantSearchData = {
                customer_email: personalInfo.email,
                location_code: bookingData.locationCode
            };

            // console.log('=== STEP 1: SEARCHING TENANT ===');
            let tenant = await searchTenant(tenantSearchData);
            // console.log('Tenant search result:', tenant);

            if (!tenant) {
                // console.log('=== STEP 2: CREATING NEW TENANT ===');
                const newTenantData = collectTenantCreateFormData();
                // console.log('New tenant data:', newTenantData);
                tenant = await createTenant(newTenantData);
                // console.log('Tenant created:', tenant);
            } else {
                console.log('=== TENANT ALREADY EXISTS ===');
            }

            // console.log('=== STEP 3: PROCESSING MOVE-IN ===');
            const moveInData = collectMoveInFormData(tenant);
            // console.log('Move-in data:', moveInData);
            const moveInResult = await processTenantMoveIn(moveInData);
            // console.log('Move-in result:', moveInResult);

            // Extract tenant_unit_informations_id from tenant creation response
            const tenant_unit_informations_id = tenant.tenant_unit_informations_id || '';

            // console.log('=== STEP 3b: LOGGING LEGACY ORDER ===');
            const legacyOrderData = collectLegacyOrderData(tenant, moveInResult, {
                tenant_unit_informations_id: tenant_unit_informations_id,
                token: ''
            });
            // console.log('Legacy order data:', legacyOrderData);
            await logLegacyOrder(legacyOrderData);

            // console.log('=== STEP 4: SENDING WELCOME EMAIL ===');
            const welcomeEmailData = collectWelcomeEmailData();
            // Add tenant ID for agreement number (fallback if tenant_unit_informations_id not available)
            welcomeEmailData.tenant_id = tenant.TenantID;
            if (tenant && tenant.sAccessCode) {
                welcomeEmailData.access_code = tenant.sAccessCode;
            }
            // Add tenant_unit_informations_id for agreement number (preferred)
            if (tenant_unit_informations_id) {
                welcomeEmailData.tenant_unit_informations_id = tenant_unit_informations_id;
            }
            const emailResult = await sendWelcomeEmail(welcomeEmailData);

            // Derive the full amount paid today (rent + insurance) from the
            // move-in payload so the confirmation receipt can show the total.
            const rawTotalAmount = (moveInData && moveInData.billing && (moveInData.billing.full_payment_amount || moveInData.billing.payment_amount)) || '0';
            let parsedTotalAmount = parseFloat(rawTotalAmount);
            if (isNaN(parsedTotalAmount)) {
                parsedTotalAmount = 0;
            }

            const confirmationData = {
                tenantId: tenant.TenantID,
                ledgerId: moveInResult.data?.move_in?.LedgerID,
                customerName: `${personalInfo.firstName} ${personalInfo.lastName}`,
                customerEmail: personalInfo.email,
                locationName: bookingData.locationName,
                unitNumber: bookingData.unit?.unitName,
                unitSize: bookingData.unit?.size,
                unitDesc: bookingData.unit?.unitDesc,
                building: bookingData.unit?.building,
                floor: bookingData.unit?.floor,
                isInside: bookingData.unit?.isInside,
                totalAmount: parsedTotalAmount.toFixed(2),
                monthlyRate: bookingData.unit?.price,
                startDate: additionalDetails.startDate,
                timestamp: new Date().toISOString()
            };

            sessionStorage.setItem('bookingConfirmation', JSON.stringify(confirmationData));

            try {
                var leadId = sessionStorage.getItem('bookingLeadId');
                if (leadId) {
                    $.post(base_url + 'MammothStorageController/mark_booking_completed', {
                        lead_id: leadId
                    });
                }
            } catch (e) {
            }

            if (typeof loadStep === 'function') {
                loadStep('booking-confirmed');
            } else {
                window.location.href = base_url + 'mammoth-storage/booking-confirmed';
            }

        } catch (error) {
            console.error('Booking error:', error);
            Swal.fire({
                icon: 'error',
                title: 'Booking Failed',
                text: error.message || 'An error occurred during booking. Please try again.',
                confirmButtonText: 'OK',
                buttonsStyling: false,
                customClass: {
                    confirmButton: 'btn btn-custom-primary me-2'
                }
            });
        } finally {
            $('#spinner-overlay').hide();
            $submitBtn.prop('disabled', false).text('Complete Booking');
        }
    }

    $(document).ready(function () {
        populateExpYears();

        $('#expYear').on('change', function () {
            populateExpMonths($(this).val());
        });

        $('#cardNumber, #cvv').on('keypress', function (e) {
            if (!/\d/.test(String.fromCharCode(e.which))) return false;
        });

        $('#cardNumber').on('input', function () {
            const val = $(this).val();
            if (val.length > 16) $(this).val(val.slice(0, 16));
        });

        $('#cvv').on('input', function () {
            const val = $(this).val();
            if (val.length > 3) $(this).val(val.slice(0, 3));
        });

        const bookingData = sessionStorage.getItem('bookingData');
        // console.log('Booking Data from sessionStorage:', bookingData);

        if (bookingData) {
            try {
                const data = JSON.parse(bookingData);
                // console.log('Parsed booking data:', data);

                // Populate summary fields
                $('#summaryLocation').text(data.locationName || '-');

                // Format storage type
                let storageTypeText = '-';
                if (data.storageType === 'storage') {
                    storageTypeText = 'Self Storage';
                } else if (data.storageType === 'vehicle') {
                    storageTypeText = 'Vehicle Storage';
                } else if (data.storageType === 'both') {
                    storageTypeText = 'Both Types';
                } else if (data.storageType) {
                    storageTypeText = data.storageType;
                }
                $('#summaryStorageType').text(storageTypeText);

                // Show unit size with floor information
                let unitSizeDisplay = data.unit?.size || '';
                if (data.unit && data.unit.floor !== undefined && data.unit.floor !== null && data.unit.floor !== '') {
                    const floorNumber = data.unit.floor;
                    let floorLabel = '';
                    if (floorNumber === 0 || floorNumber === '0') {
                        floorLabel = 'Ground Floor';
                    } else {
                        floorLabel = `Floor ${floorNumber}`;
                    }
                    if (unitSizeDisplay) {
                        unitSizeDisplay = `${unitSizeDisplay}, ${floorLabel}`;
                    } else {
                        unitSizeDisplay = floorLabel;
                    }
                }
                $('#summaryUnitSize').text(unitSizeDisplay || '-');

                // Show block name from unitDesc instead of floor type
                let buildingBlock = data.unit?.unitDesc || data.unit?.building || '';
                if (buildingBlock && buildingBlock.indexOf(',') !== -1) {
                    buildingBlock = buildingBlock.split(',')[0].trim();
                }
                $('#summaryFloor').text(buildingBlock || '-');

                const currentPrice = data.unit?.price;
                const originalPrice = data.unit?.originalPrice;

                if (currentPrice && originalPrice && parseFloat(originalPrice) > parseFloat(currentPrice)) {
                    $('#summaryRate').html(
                        `<span style="text-decoration: line-through; color: #6c757d; margin-right: 8px;">$${parseFloat(originalPrice).toFixed(2)}/mo</span>` +
                        `<span>$${parseFloat(currentPrice).toFixed(2)}/mo</span>`
                    );
                } else if (currentPrice) {
                    $('#summaryRate').text(`$${currentPrice}/mo`);
                } else {
                    $('#summaryRate').text('-');
                }

                // console.log('Summary populated successfully');
            } catch (e) {
                console.error('Error parsing booking data:', e);
            }
        } else {
            console.warn('No booking data found in sessionStorage');
        }

        $('#paymentForm').on('submit', function (e) {
            e.preventDefault();

            if (!step_8_validate()) {
                Swal.fire({
                    icon: 'error',
                    title: 'Validation Error',
                    text: 'Please fill in all payment fields correctly.',
                    confirmButtonText: 'OK',
                    buttonsStyling: false,
                    customClass: {
                        confirmButton: 'btn btn-custom-primary me-2'
                    }
                });
                return;
            }

            // SECURITY: Do NOT store payment data in sessionStorage
            // Payment data is read directly from form during submission
            processFinalSubmission();
        });

        // SECURITY: Removed code that saved/restored payment data from sessionStorage
        // Payment forms should never persist sensitive card information in browser storage
    });
</script>

<?php include(APPPATH . 'views/storage/mammoth-storage/partials/footer-steps.php'); ?>