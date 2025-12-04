<?php $currentStep = 4; ?>
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
        margin-bottom: 10px;
    }

    .form-card .subtitle {
        font-size: 1rem;
        color: #6c757d;
        margin-bottom: 30px;
    }

    .section-title {
        font-size: 1.2rem;
        font-weight: 600;
        color: #2c3e50;
        margin: 25px 0 15px;
    }

    .section-subtitle {
        font-size: 0.95rem;
        color: #6c757d;
        margin-bottom: 15px;
    }

    .info-box {
        background-color: #f8f9fa;
        border-left: 4px solid #dc3545;
        padding: 15px;
        margin-bottom: 20px;
        border-radius: 4px;
    }

    .info-box p {
        margin: 0;
        font-size: 0.9rem;
        line-height: 1.6;
        color: #495057;
    }

    .form-check {
        margin-bottom: 15px;
        padding-left: 1.8rem;
    }

    .form-check-input {
        width: 1.2rem;
        height: 1.2rem;
        margin-top: 0.2rem;
        cursor: pointer;
    }

    .form-check-input:checked {
        background-color: #dc3545;
        border-color: #dc3545;
    }

    .form-check-input:focus {
        border-color: #dc3545;
        box-shadow: 0 0 0 0.2rem rgba(220, 53, 69, 0.25);
    }

    .form-check-label {
        font-size: 0.95rem;
        color: #2c3e50;
        cursor: pointer;
        line-height: 1.5;
    }

    .form-select {
        border: 2px solid #e0e0e0;
        border-radius: 8px;
        padding: 12px 15px;
        font-size: 0.95rem;
        transition: border-color 0.3s;
    }

    .form-select:focus {
        border-color: #dc3545;
        box-shadow: 0 0 0 0.2rem rgba(220, 53, 69, 0.15);
        outline: none;
    }

    .show-more-link {
        color: #dc3545;
        cursor: pointer;
        text-decoration: underline;
    }

    .show-more-link:hover {
        color: #c82333;
    }

    .full-description {
        display: none;
        margin-top: 10px;
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

    @media (max-width: 768px) {
        .form-card {
            padding: 30px 20px;
        }
    }
</style>

<div class="container">
    <div class="form-card">
        <a href="javascript:void(0);" class="back-button" onclick="loadStep('additional-details')">
            <i class="fas fa-chevron-left"></i> Back
        </a>
        
        <h2>Insurance Terms & Conditions</h2>
        <p class="subtitle">Confirm how you are protecting your goods.</p>

        <form id="insuranceTermsForm" novalidate>
            <p class="section-title">You must select one of the following:</p>

            <div class="form-check">
                <input class="form-check-input terms-and-conditions" type="checkbox" id="storeProtect" value="storeProtect">
                <label class="form-check-label" for="storeProtect">
                    <strong>I/We wish to accept extended liability protection</strong> under StoreProtect
                    <span class="show-more-link show-more-for-store-protect">...more</span>
                </label>
                <div class="full-description full-description-store-protect">
                    and confirm I/We: have read and understood the enclosed StoreProtect Addendum; understand that the Proportional Reduction shall apply if the Maximum Replacement Value provided is inadequate at any time during the Storage Period; and understand a $100 Claims Administration Fee will be deducted from claim payments.
                    <br><br>I/We also confirm I do not hold any insurance with another party for this storage shed.
                    <br><br>The agreed Maximum Replacement Value of your goods is and this is an accurate value of the contents you will be storing. You have read and understand the Proportional Reduction clause.
                </div>
            </div>

            <div class="col-12 mb-3 mt-3" id="maximum_replacement_value_section" style="display:none;">
                <label for="maximum_replacement_value" class="form-label">Maximum Replacement Value ($100,000 maximum):</label>
                <select class="form-select" id="maximum_replacement_value" name="maximum_replacement_value">
                    <option value="">Select Insurance Coverage</option>
                </select>

                <div class="form-check mt-3">
                    <input type="checkbox" class="form-check-input terms-and-conditions_for_license" id="licenseInsurance">
                    <label class="form-check-label" for="licenseInsurance">
                        By ticking this box you have read and understand the terms <b><a target="_blank" href="https://mammothselfstore.com.au/wp-content/uploads/2024/09/Lease-Agreement.pdf">Licence terms</a></b> and <b><a target="_blank" href="https://mammothselfstore.com.au/wp-content/uploads/2024/09/StoreProtect-Addendum.pdf">StoreProtect Addendum</a></b> and you agree to them.
                    </label>
                </div>
            </div>

            <div class="form-check">
                <input class="form-check-input terms-and-conditions" type="checkbox" id="ownInsurance" value="ownInsurance">
                <label class="form-check-label" for="ownInsurance">
                    <strong>I/We have adequate insurance</strong> for the Goods that we are storing
                    <span class="show-more-link show-more-for-own-insurance">...more</span>
                </label>
                <div class="full-description full-description-own-insurance">
                    and will provide details of this coverage if required.
                </div>
            </div>

            <div class="form-check">
                <input class="form-check-input terms-and-conditions" type="checkbox" id="noInsurance" value="noInsurance">
                <label class="form-check-label" for="noInsurance">
                    <strong>I/We don't have insurance</strong> and understand that if the Goods are lost or damaged I/We may have to bear that loss
                    <span class="show-more-link show-more-for-no-insurance">...more</span>
                </label>
                <div class="full-description full-description-no-insurance">
                    Further, that in the event that my/our Goods cause any damage or loss then I/We may be liable for such damage or loss. <br><b><a target="_blank" href="https://mammothselfstore.com.au/wp-content/uploads/2024/09/Insurance-Waiver.pdf">Insurance Opt Out Notice</a></b>
                </div>
            </div>

            <div id="storageTermsSection" style="display: none;">
                <p class="section-title">Storage Terms & Conditions</p>
                <p class="section-subtitle">Confirm that you understand our service policies.</p>

                <div class="info-box">
                    <p><strong>Storage Period:</strong> From <span id="moveInDateText">-</span> To <span id="moveInDateOneMonthLaterText">-</span> initially, and then extended automatically until the expiry of 14 days from the date that either the FO or the Storer gives written notice to the other party of its intention to terminate this Agreement (Termination Notice Period).</p>
                </div>

                <div class="form-check">
                    <input type="checkbox" class="form-check-input storage-terms" id="terms1">
                    <label class="form-check-label" for="terms1">
                        I/We acknowledge that I/We have been provided with reasonable access to the full terms of this Agreement.
                    </label>
                </div>

                <div class="form-check">
                    <input type="checkbox" class="form-check-input storage-terms" id="terms2">
                    <label class="form-check-label" for="terms2">
                        I/We accept that our nominated credit card will be charged on our due date for the following period.
                    </label>
                </div>

                <div class="form-check">
                    <input type="checkbox" class="form-check-input storage-terms" id="terms3">
                    <label class="form-check-label" for="terms3">
                        I/We represent that I/We have read or had the opportunity to read the full terms of this Agreement.
                    </label>
                </div>

                <div class="form-check">
                    <input type="checkbox" class="form-check-input storage-terms" id="terms4">
                    <label class="form-check-label" for="terms4">
                        I/We agree to be bound by all of the terms of this Agreement.
                    </label>
                </div>

                <div class="form-check">
                    <input type="checkbox" class="form-check-input storage-terms" id="terms5">
                    <label class="form-check-label" for="terms5">
                        I/We understand that we must provide 14 days' notice prior to vacating the facility.
                    </label>
                </div>

                <div class="form-check">
                    <input type="checkbox" class="form-check-input storage-terms" id="terms6">
                    <label class="form-check-label" for="terms6">
                        I/We understand that my/our goods may be sold if abandoned.
                    </label>
                </div>

                <div class="form-check">
                    <input type="checkbox" class="form-check-input storage-terms" id="terms7">
                    <label class="form-check-label" for="terms7">
                        I have read and understood the <b><a target="_blank" href="https://mammothselfstore.com.au/wp-content/uploads/2024/09/Lease-Agreement.pdf">Terms and Conditions</a></b> that will form the agreement between myself and Mammoth Storage.
                    </label>
                </div>
            </div>

            <p class="section-title mt-4">Digital Signature</p>
            <p class="section-subtitle">Please sign below to confirm your agreement to the terms and conditions.</p>

            <div class="signature-container" style="border: 2px solid #e0e0e0; border-radius: 8px; background-color: #fff; overflow: hidden;">
                <canvas id="signaturePad" class="signature-pad" style="display: block; width: 100%; height: 250px; cursor: crosshair; touch-action: none; background-color: #fafafa;"></canvas>
                <div style="display: flex; justify-content: space-between; align-items: center; padding: 15px 20px; background-color: #fff; border-top: 1px solid #e0e0e0;">
                    <a href="#" class="clear-signature" id="clearSignature" style="color: #dc3545; text-decoration: none; font-size: 0.95rem; cursor: pointer; font-weight: 500;">Clear Signature</a>
                    <span style="font-size: 0.9rem; color: #6c757d;">Please sign above</span>
                </div>
            </div>

            <button type="submit" class="btn-continue" id="submitBtn" disabled title="Please complete all required fields">Continue to Payment Information</button>
        </form>
    </div>
</div>

<script>
let selectedOptionForTermsAndConditions = '';
let selectedOptionForLicenseTermsAndConditions = '';
let selectedOptionForStorageTermsAndConditions = '';
let hasSignature = false;
let signaturePad = null;
let ctx = null;
let isDrawing = false;
let lastX = 0;
let lastY = 0;

// Make function globally accessible
window.initializeSignaturePad = function initializeSignaturePad() {
    const canvas = document.getElementById('signaturePad');
    if (!canvas) {
        // console.warn('Signature pad canvas not found');
        return;
    }
    
    ctx = canvas.getContext('2d', { willReadFrequently: false });
    
    function resizeCanvas() {
        const rect = canvas.getBoundingClientRect();
        canvas.width = rect.width;
        canvas.height = rect.height;
        
        ctx.strokeStyle = '#000';
        ctx.lineWidth = 2.5;
        ctx.lineCap = 'round';
        ctx.lineJoin = 'round';
    }
    
    resizeCanvas();
    window.addEventListener('resize', resizeCanvas);
    
    function getCoordinates(e) {
        const rect = canvas.getBoundingClientRect();
        let clientX, clientY;
        
        if (e.touches && e.touches.length > 0) {
            clientX = e.touches[0].clientX;
            clientY = e.touches[0].clientY;
        } else if (e.clientX !== undefined && e.clientY !== undefined) {
            clientX = e.clientX;
            clientY = e.clientY;
        } else {
            return { x: 0, y: 0 };
        }
        
        return {
            x: clientX - rect.left,
            y: clientY - rect.top
        };
    }
    
    function startDrawing(e) {
        if (e.cancelable) e.preventDefault();
        e.stopPropagation();
        
        isDrawing = true;
        const coords = getCoordinates(e);
        lastX = coords.x;
        lastY = coords.y;
        
        ctx.beginPath();
        ctx.moveTo(lastX, lastY);
        ctx.lineTo(lastX + 0.1, lastY + 0.1);
        ctx.stroke();
    }
    
    function draw(e) {
        if (!isDrawing) return;
        if (e.cancelable) e.preventDefault();
        e.stopPropagation();
        
        const coords = getCoordinates(e);
        ctx.lineTo(coords.x, coords.y);
        ctx.stroke();
        
        lastX = coords.x;
        lastY = coords.y;
        hasSignature = true;
        checkFormValidity();
    }
    
    function stopDrawing(e) {
        if (isDrawing) {
            if (e && e.cancelable) e.preventDefault();
            if (e) e.stopPropagation();
            isDrawing = false;
            ctx.closePath();
        }
    }
    
    canvas.addEventListener('mousedown', startDrawing, false);
    canvas.addEventListener('mousemove', draw, false);
    canvas.addEventListener('mouseup', stopDrawing, false);
    canvas.addEventListener('mouseleave', stopDrawing, false);
    
    canvas.addEventListener('touchstart', startDrawing, { passive: false });
    canvas.addEventListener('touchmove', draw, { passive: false });
    canvas.addEventListener('touchend', stopDrawing, { passive: false });
    canvas.addEventListener('touchcancel', stopDrawing, { passive: false });
    
    canvas.addEventListener('contextmenu', function(e) {
        e.preventDefault();
        return false;
    }, false);
    
    $('#clearSignature').on('click', function(e) {
        e.preventDefault();
        ctx.clearRect(0, 0, canvas.width, canvas.height);
        hasSignature = false;
        checkFormValidity();
    });
}

async function loadInsuranceOptions(locationCode) {
    selectedOptionForLicenseTermsAndConditions = '';
    selectedOptionForTermsAndConditions = '';

    $('.full-description-store-protect').hide();
    $('.full-description-own-insurance').hide();
    $('.full-description-no-insurance').hide();

    $('#storeProtect').prop('checked', false);
    $('#licenseInsurance').prop('checked', false);
    $('#ownInsurance').prop('checked', false);
    $('#noInsurance').prop('checked', false);

    const select = $('#maximum_replacement_value');
    select.empty().append('<option value="">Loading...</option>');

    $('#spinner-overlay').hide();

    try {
        // console.log('Fetching insurance for locationCode:', locationCode);
        const response = await $.ajax({
            url: 'https://checkout.mammothstorage.com.au/api/v1/insurance_coverage/getAll',
            method: 'POST',
            data: JSON.stringify({ location_code: locationCode }),
            contentType: 'application/json',
            dataType: 'json'
        });

        // console.log('Insurance API response:', response);
        select.empty();

        if (response.success && response.data && response.data.length > 0) {
            // console.log('Insurance options found:', response.data.length);
            select.append('<option value="">Select Insurance Coverage</option>');
            response.data.forEach(item => {
                select.append(
                    `<option value="${item.dcCoverage}"
                         data-premium="${item.dcPremium}"
                         data-coverage-id="${item.InsurCoverageID}">
                    ${item.sCoverageDesc} - ${parseFloat(item.dcPremium).toFixed(2)}/mo
                </option>`
                );
            });
        } else {
            // console.warn('No insurance data returned. Response:', response);
            select.append('<option value="">No Insurance Options Available</option>');
        }
        
        // Always hide spinner after loading completes
        $('#spinner-overlay').hide();
    } catch (err) {
        $('#spinner-overlay').hide();
        console.error('Insurance fetch error:', err);
        console.error('Error details:', err.responseJSON || err.responseText);
        select.empty().append('<option value="">Error loading insurance</option>');
    }
}

function handleCheckboxSelection(event) {
    const selectedCheckbox = event.target;

    if (selectedCheckbox.type === 'checkbox') {
        if (selectedCheckbox.checked) {
            $('.terms-and-conditions').not(selectedCheckbox).prop('checked', false);

            const maxReplacementValueField = $('#maximum_replacement_value_section');
            if (selectedCheckbox.id === 'storeProtect') {
                maxReplacementValueField.show();
                $('.full-description-own-insurance').hide();
                $('.full-description-no-insurance').hide();
                $('.full-description-store-protect').show();
            } else {
                maxReplacementValueField.hide();
                $('.full-description-store-protect').hide();
                
                if (selectedCheckbox.id === 'ownInsurance') {
                    $('.full-description-own-insurance').show();
                    $('.full-description-no-insurance').hide();
                } else if (selectedCheckbox.id === 'noInsurance') {
                    $('.full-description-own-insurance').hide();
                    $('.full-description-no-insurance').show();
                }
            }

            selectedOptionForTermsAndConditions = selectedCheckbox.id;
            selectedOptionForLicenseTermsAndConditions = '';
            $('.terms-and-conditions_for_license').prop('checked', false);
        } else {
            selectedCheckbox.checked = false;
            $('#maximum_replacement_value_section').hide();
            selectedOptionForTermsAndConditions = '';
            $('.full-description').hide();
        }
    }
    
    // Show/hide Storage Terms & Conditions section based on whether
    // any insurance option has been selected above.
    const anyInsuranceSelected = $('.terms-and-conditions:checked').length > 0;
    if (anyInsuranceSelected) {
        $('#storageTermsSection').slideDown(200);
    } else {
        $('#storageTermsSection').slideUp(200);
        $('.storage-terms').prop('checked', false);
    }

    checkFormValidity();
}

$('.show-more-for-store-protect').on('click', function(e) {
    e.preventDefault();
    $('.full-description-store-protect').toggle();
    $(this).text($('.full-description-store-protect').is(':visible') ? '...less' : '...more');
});

$('.show-more-for-own-insurance').on('click', function(e) {
    e.preventDefault();
    $('.full-description-own-insurance').toggle();
    $(this).text($('.full-description-own-insurance').is(':visible') ? '...less' : '...more');
});

$('.show-more-for-no-insurance').on('click', function(e) {
    e.preventDefault();
    $('.full-description-no-insurance').toggle();
    $(this).text($('.full-description-no-insurance').is(':visible') ? '...less' : '...more');
});

$('.terms-and-conditions').on('change', handleCheckboxSelection);

$('.terms-and-conditions_for_license').on('click', function() {
    const insuranceValue = parseInt($('#maximum_replacement_value').val()) || 0;
    
    if (insuranceValue > 0) {
        if (selectedOptionForTermsAndConditions === 'storeProtect') {
            if ($(this).is(':checked')) {
                selectedOptionForLicenseTermsAndConditions = $(this).attr('id');
            } else {
                selectedOptionForLicenseTermsAndConditions = '';
            }
        } else {
            selectedOptionForLicenseTermsAndConditions = '';
            Swal.fire({
                title: 'Access Agreement Required',
                text: 'Firstly, please accept the agreement of StoreProtect.',
                icon: 'info',
                confirmButtonText: 'OK',
                buttonsStyling: false,
                customClass: {
                    confirmButton: 'btn btn-custom-primary me-2'
                }
            });
            $(this).prop('checked', false);
        }
    } else {
        Swal.fire({
            title: 'Confirmation Required',
            text: 'No Store Protect coverage has been selected, please let the Mammoth Team know what value your goods are that you would like to cover. Or select another option.',
            icon: 'info',
            confirmButtonText: 'OK',
            buttonsStyling: false,
            customClass: {
                confirmButton: 'btn btn-custom-primary me-2'
            }
        });
        $(this).prop('checked', false);
    }
    
    checkFormValidity();
});

function checkFormValidity() {
    const insuranceSelected = selectedOptionForTermsAndConditions !== '';
    let insuranceValid = false;

    if (selectedOptionForTermsAndConditions === 'storeProtect') {
        const coverageValue = parseInt($('#maximum_replacement_value').val()) || 0;
        insuranceValid = coverageValue > 0 && selectedOptionForLicenseTermsAndConditions !== '';
    } else if (selectedOptionForTermsAndConditions === 'ownInsurance' || selectedOptionForTermsAndConditions === 'noInsurance') {
        insuranceValid = true;
    }

    const allStorageTermsChecked = $('.storage-terms').length === $('.storage-terms:checked').length;

    const isValid = insuranceSelected && insuranceValid && allStorageTermsChecked && hasSignature;
    
    // console.log('Form Validity Check:', {
    //     insuranceSelected,
    //     insuranceValid,
    //     allStorageTermsChecked,
    //     hasSignature,
    //     isValid
    // });
    
    $('#submitBtn').prop('disabled', !isValid);
}

$('.storage-terms').on('change', checkFormValidity);
$('#maximum_replacement_value').on('change', checkFormValidity);

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

    return `${year}-${month}-${day}`;
}

function formatDateForDisplay(dateStr) {
    if (!dateStr) {
        return '';
    }

    const parts = dateStr.split('-');
    if (parts.length !== 3) {
        return dateStr;
    }

    // If looks like YYYY-MM-DD, convert to DD-MM-YYYY
    if (parts[0].length === 4) {
        return `${parts[2]}-${parts[1]}-${parts[0]}`;
    }

    return dateStr;
}

$(document).ready(function() {
    // console.log('Insurance Terms page loaded');
    
    // Reinitialize header search autocomplete if available
    if (typeof window.initHeaderAutocomplete === 'function') {
        // console.log('Reinitializing header search...');
        window.initHeaderAutocomplete();
    }
    
    initializeSignaturePad();
    
    const bookingData = JSON.parse(sessionStorage.getItem('bookingData') || '{}');
    const additionalDetails = JSON.parse(sessionStorage.getItem('additionalDetails') || '{}');

    // console.log('Insurance page - bookingData:', bookingData);
    // console.log('Insurance page - additionalDetails:', additionalDetails);

    if (additionalDetails.startDate) {
        $('#moveInDateText').text(formatDateForDisplay(additionalDetails.startDate));
    }

    let endDateForDisplay = additionalDetails.endDate || '';

    if (!endDateForDisplay && additionalDetails.startDate) {
        endDateForDisplay = calculateEndDateFromStart(additionalDetails.startDate);

        if (endDateForDisplay) {
            additionalDetails.endDate = endDateForDisplay;
            sessionStorage.setItem('additionalDetails', JSON.stringify(additionalDetails));
        }
    }

    if (endDateForDisplay) {
        $('#moveInDateOneMonthLaterText').text(formatDateForDisplay(endDateForDisplay));
    }

    if (bookingData.locationCode) {
        // console.log('Loading insurance options for location:', bookingData.locationCode);
        loadInsuranceOptions(bookingData.locationCode);
    } else {
        // console.error('No locationCode found in bookingData!');
        $('#maximum_replacement_value').empty().append('<option value="">Location code not found</option>');
    }

    $('#insuranceTermsForm').on('submit', function(e) {
        e.preventDefault();

        if (!selectedOptionForTermsAndConditions) {
            Swal.fire({
                title: 'Confirmation Required',
                text: 'Please select an insurance option.',
                icon: 'info',
                confirmButtonText: 'OK',
                buttonsStyling: false,
                customClass: {
                    confirmButton: 'btn btn-custom-primary me-2'
                }
            });
            return;
        }

        if (!hasSignature) {
            Swal.fire({
                title: 'Signature Required',
                text: 'Please provide your signature before continuing.',
                icon: 'warning',
                confirmButtonText: 'OK',
                buttonsStyling: false,
                customClass: {
                    confirmButton: 'btn btn-custom-primary me-2'
                }
            });
            return;
        }

        const canvas = document.getElementById('signaturePad');
        const $selectedInsurance = $('#maximum_replacement_value').find(':selected');
        
        // Check if canvas exists and has signature
        if (!canvas) {
            Swal.fire({
                title: 'Error',
                text: 'Signature pad not found. Please refresh the page.',
                icon: 'error',
                confirmButtonText: 'OK',
                buttonsStyling: false,
                customClass: {
                    confirmButton: 'btn btn-custom-primary me-2'
                }
            });
            return;
        }
        
        const formData = {
            insurance: selectedOptionForTermsAndConditions,
            coverage: selectedOptionForTermsAndConditions === 'storeProtect' ? ($selectedInsurance.val() || '0') : '0',
            coverage_id: selectedOptionForTermsAndConditions === 'storeProtect' ? ($selectedInsurance.data('coverage-id') || '0') : '0',
            premium: selectedOptionForTermsAndConditions === 'storeProtect' ? ($selectedInsurance.data('premium') || '0') : '0',
            storageTermsAccepted: true,
            signature: canvas.toDataURL('image/png'),
            timestamp: new Date().toISOString()
        };

        sessionStorage.setItem('insuranceTerms', JSON.stringify(formData));

        if (typeof loadStep === 'function') {
            loadStep('payment');
        } else {
            window.location.href = '<?php echo base_url('mammoth-storage/payment'); ?>';
        }
    });

    const savedData = sessionStorage.getItem('insuranceTerms');
    if (savedData) {
        try {
            const data = JSON.parse(savedData);
            if (data.insurance) {
                $('#' + data.insurance).prop('checked', true).trigger('change');
                
                if (data.insurance === 'storeProtect' && data.coverage) {
                    $('#maximum_replacement_value').val(data.coverage);
                    if (data.coverage !== '0') {
                        $('#licenseInsurance').prop('checked', true);
                        selectedOptionForLicenseTermsAndConditions = 'licenseInsurance';
                    }
                }
            }
            
            $('.storage-terms').prop('checked', true);
            
            if (data.signature) {
                hasSignature = true;
            }
            
            checkFormValidity();
        } catch (e) {
            console.error('Error loading saved data:', e);
        }
    }
});
</script>

<?php include(APPPATH . 'views/storage/mammoth-storage/partials/footer-steps.php'); ?>