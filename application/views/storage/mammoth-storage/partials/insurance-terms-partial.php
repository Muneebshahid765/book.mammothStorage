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
    <div class="row">
        <div class="col-lg-8">
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
                        <p class="section-subtitle">Confirm that you understand our service policies by reviewing the full agreement below.</p>

                        <div class="info-box">
                            <p><strong>Storage Period:</strong> From <span id="moveInDateText">-</span> To <span id="moveInDateOneMonthLaterText">-</span> initially, and then extended automatically until the expiry of 14 days from the date that either the FO or the Storer gives written notice to the other party of its intention to terminate this Agreement (Termination Notice Period).</p>
                        </div>

                        <p class="section-title" style="margin-top: 10px;">Full Storage Agreement Terms</p>
                        <div id="agreementScrollNotice" class="text-center mb-2" style="background-color: #ff9933; color: #ffffff; padding: 6px 10px; border-radius: 4px; font-size: 0.9rem;">
                            Scroll to the bottom of the box to confirm review.
                        </div>

                        <div id="agreementScrollBox" class="border rounded" style="max-height: 350px; overflow-y: auto; background-color: #ffffff; margin-bottom: 15px;">
                            <div id="standardAgreementContent" style="padding: 15px; font-size: 0.9rem; line-height: 1.5; white-space: pre-wrap;">
                                <?php include FCPATH . '../shared/storage-agreement-terms-content.php'; ?>
                            </div>
                            <div id="vehicleAgreementContent" style="display:none; padding: 15px; font-size: 0.9rem; line-height: 1.5; white-space: pre-wrap;">
                                <?php
                                    $vehicle_terms_path = FCPATH . '../shared/storage-agreement-vehicle-terms-content.php';
                                    if (file_exists($vehicle_terms_path)) {
                                        include $vehicle_terms_path;
                                    } else {
                                        // Fallback to standard terms if vehicle-specific terms file is missing
                                        include FCPATH . '../shared/storage-agreement-terms-content.php';
                                    }
                                ?>
                            </div>
                        </div>

                        <div class="form-check">
                            <input type="checkbox" class="form-check-input storage-terms" id="termsAgree">
                            <label class="form-check-label" for="termsAgree">
                                I confirm that I have reviewed the Full Storage Agreement Terms above and have read and understood the <b><a target="_blank" href="https://mammothselfstore.com.au/wp-content/uploads/2024/09/Lease-Agreement.pdf">Terms and Conditions</a></b> that will form the agreement between myself and Mammoth Storage.
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

                    <button type="submit" class="btn-continue" id="submitBtn" disabled>Continue to Payment</button>
                </form>
            </div>
        </div>
        <div class="col-lg-4 mt-4 mt-lg-0">
            <?php include(APPPATH . 'views/storage/mammoth-storage/partials/checkout-summary-partial.php'); ?>
        </div>
    </div>
<script>
    let selectedOptionForTermsAndConditions = '';
    let selectedOptionForLicenseTermsAndConditions = '';
    let selectedOptionForStorageTermsAndConditions = '';
    let hasSignature = false;
    let hasReadAgreement = false;
    let signaturePad = null;
    let ctx = null;

    let isDrawing = false;
    let lastX = 0;
    let lastY = 0;

    // --- Helpers to sync insurance selection with summary card ---
    function saveInsuranceTermsPartial(partial) {
        let existing = {};
        try {
            existing = JSON.parse(sessionStorage.getItem('insuranceTerms') || '{}');
        } catch (e) {
            existing = {};
        }

        const updated = Object.assign({}, existing, partial || {});
        sessionStorage.setItem('insuranceTerms', JSON.stringify(updated));

        if (typeof window.updateCheckoutSummaryFromSession === 'function') {
            window.updateCheckoutSummaryFromSession();
        }
    }

    function getCurrentInsuranceSelection() {
        const insurance = selectedOptionForTermsAndConditions || '';
        if (!insurance) {
            return null;
        }

        if (insurance === 'storeProtect') {
            const $selected = $('#maximum_replacement_value').find(':selected');
            const coverage = $selected.val() || '0';
            const premium = $selected.data('premium') || '0';
            const coverageId = $selected.data('coverage-id') || '0';

            return {
                insurance: 'storeProtect',
                coverage: coverage,
                coverage_id: coverageId || '0',
                premium: premium || '0'
            };
        }

        if (insurance === 'ownInsurance' || insurance === 'noInsurance') {
            return {
                insurance: insurance,
                coverage: '0',
                coverage_id: '0',
                premium: '0'
            };
        }

        return null;
    }

    function updateInsuranceSummaryFromCurrentSelection() {
        const current = getCurrentInsuranceSelection();
        if (current) {
            saveInsuranceTermsPartial(current);
        } else {
            // Clear premium if nothing selected
            saveInsuranceTermsPartial({
                premium: '0',
                coverage: '0',
                coverage_id: '0'
            });
        }
    }

    // --- Insurance coverage API ---
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
            const response = await $.ajax({
                url: 'https://checkout.mammothstorage.com.au/api/v1/insurance_coverage/getAll',
                method: 'POST',
                data: JSON.stringify({ location_code: locationCode }),

                contentType: 'application/json',
                dataType: 'json'
            });

            select.empty();

            if (response.success && response.data && response.data.length > 0) {
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

                // If user had already selected a coverage before (coming back from Payment),
                // re-apply that saved value after the async options load completes.
                const savedRaw = sessionStorage.getItem('insuranceTerms');
                if (savedRaw) {
                    try {
                        const saved = JSON.parse(savedRaw);
                        if (saved && saved.coverage) {
                            select.val(String(saved.coverage));
                        }
                    } catch (e) {
                        console.error('Error applying saved insurance coverage:', e);
                    }
                }
            } else {
                select.append('<option value="">No Insurance Options Available</option>');
            }

            $('#spinner-overlay').hide();
        } catch (err) {
            $('#spinner-overlay').hide();
            console.error('Insurance fetch error:', err);
            console.error('Error details:', err.responseJSON || err.responseText);
            select.empty().append('<option value="">Error loading insurance</option>');
        }
    }

    // --- UI + validation helpers ---
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
        updateInsuranceSummaryFromCurrentSelection();
    }

    function checkFormValidity() {
        const insuranceSelected = selectedOptionForTermsAndConditions !== '';
        let insuranceValid = false;

        if (selectedOptionForTermsAndConditions === 'storeProtect') {
            const coverageValue = parseInt($('#maximum_replacement_value').val(), 10) || 0;
            insuranceValid = coverageValue > 0 && selectedOptionForLicenseTermsAndConditions !== '';
        } else if (
            selectedOptionForTermsAndConditions === 'ownInsurance' ||
            selectedOptionForTermsAndConditions === 'noInsurance'
        ) {
            insuranceValid = true;
        }

        const allStorageTermsChecked = $('.storage-terms').length === $('.storage-terms:checked').length;

        const isValid = insuranceSelected && insuranceValid && allStorageTermsChecked && hasSignature && hasReadAgreement;

        $('#submitBtn').prop('disabled', !isValid);
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

    function initializeSignaturePad() {
        const canvas = document.getElementById('signaturePad');
        const clearLink = document.getElementById('clearSignature');

        if (!canvas) {
            return;
        }

        signaturePad = canvas;
        ctx = canvas.getContext('2d');

        if (!ctx) {
            return;
        }

        function resizeCanvas() {
            const ratio = window.devicePixelRatio || 1;
            const width = canvas.offsetWidth || 0;
            const height = canvas.offsetHeight || 0;

            if (!width || !height) {
                return;
            }

            canvas.width = width * ratio;
            canvas.height = height * ratio;
            ctx.setTransform(ratio, 0, 0, ratio, 0, 0);
            ctx.lineWidth = 2;
            ctx.lineCap = 'round';
            ctx.strokeStyle = '#000000';

            if (!hasSignature) {
                ctx.clearRect(0, 0, canvas.width, canvas.height);
            }
        }

        resizeCanvas();
        window.addEventListener('resize', resizeCanvas);

        function getPosition(evt) {
            const rect = canvas.getBoundingClientRect();
            let clientX = evt.clientX;
            let clientY = evt.clientY;

            if (evt.touches && evt.touches.length > 0) {
                clientX = evt.touches[0].clientX;
                clientY = evt.touches[0].clientY;
            }

            return {
                x: clientX - rect.left,
                y: clientY - rect.top
            };
        }

        function startDraw(evt) {
            evt.preventDefault();
            const pos = getPosition(evt);
            isDrawing = true;
            lastX = pos.x;
            lastY = pos.y;
        }

        function draw(evt) {
            if (!isDrawing) {
                return;
            }

            evt.preventDefault();
            const pos = getPosition(evt);
            ctx.beginPath();
            ctx.moveTo(lastX, lastY);
            ctx.lineTo(pos.x, pos.y);
            ctx.stroke();
            lastX = pos.x;
            lastY = pos.y;

            if (!hasSignature) {
                hasSignature = true;
                checkFormValidity();
            }
        }

        function endDraw(evt) {
            if (!isDrawing) {
                return;
            }

            evt.preventDefault();
            isDrawing = false;
        }

        canvas.addEventListener('mousedown', startDraw);
        canvas.addEventListener('mousemove', draw);
        canvas.addEventListener('mouseup', endDraw);
        canvas.addEventListener('mouseleave', endDraw);

        canvas.addEventListener('touchstart', startDraw, { passive: false });
        canvas.addEventListener('touchmove', draw, { passive: false });
        canvas.addEventListener('touchend', endDraw);
        canvas.addEventListener('touchcancel', endDraw);

        if (clearLink) {
            clearLink.addEventListener('click', function (evt) {
                evt.preventDefault();

                if (!ctx) {
                    return;
                }

                ctx.clearRect(0, 0, canvas.width, canvas.height);
                hasSignature = false;
                checkFormValidity();
            });
        }
    }

    function initAgreementScrollWatcher() {
        const $box = $('#agreementScrollBox');
        const $notice = $('#agreementScrollNotice');

        if (!$box.length || !$notice.length) {
            return;
        }

        let hasMarkedRead = false;

        $box.on('scroll', function () {
            const scrollTop = this.scrollTop || 0;
            const scrollHeight = this.scrollHeight || 0;
            const clientHeight = this.clientHeight || 0;

            if (!hasMarkedRead && scrollTop + clientHeight >= scrollHeight - 10) {
                hasMarkedRead = true;
                hasReadAgreement = true;

                $notice
                    .css({
                        'background-color': '#28a745',
                        'color': '#ffffff'
                    })
                    .text('Agreement Fully Read ');

                checkFormValidity();
            }
        });
    }

    function restoreInsuranceFormFromSession() {
        const savedDataRaw = sessionStorage.getItem('insuranceTerms');
        if (!savedDataRaw) {
            return;
        }

        try {
            const data = JSON.parse(savedDataRaw);
            if (data.insurance) {
                selectedOptionForTermsAndConditions = data.insurance;
                $('#' + data.insurance).prop('checked', true).trigger('change');

                if (data.insurance === 'storeProtect' && data.coverage) {
                    $('#maximum_replacement_value').val(String(data.coverage));
                    if (data.coverage !== '0') {
                        $('#licenseInsurance').prop('checked', true);
                        selectedOptionForLicenseTermsAndConditions = 'licenseInsurance';
                    }
                }
            }

            if (data.storageTermsAccepted) {
                $('.storage-terms').prop('checked', true);
                hasReadAgreement = true;

                const $notice = $('#agreementScrollNotice');
                if ($notice.length) {
                    $notice
                        .css({
                            'background-color': '#28a745',
                            'color': '#ffffff'
                        })
                        .text('Agreement Fully Read ');
                }
            }

            if (data.signature) {
                hasSignature = true;
                const canvas = document.getElementById('signaturePad');
                if (canvas && canvas.getContext && ctx) {
                    const img = new Image();
                    img.onload = function () {
                        try {
                            ctx.clearRect(0, 0, canvas.width, canvas.height);
                            ctx.drawImage(img, 0, 0, canvas.width, canvas.height);
                        } catch (e) {
                            console.error('Error restoring signature image:', e);
                        }
                    };
                    img.src = data.signature;
                }
            }

            updateInsuranceSummaryFromCurrentSelection();
            checkFormValidity();
        } catch (err) {
            console.error('Error loading saved insuranceTerms:', err);
        }
    }

    $(document).ready(async function () {
        // Reinitialize header search autocomplete if available
        if (typeof window.initHeaderAutocomplete === 'function') {
            window.initHeaderAutocomplete();
        }

        initializeSignaturePad();
        initAgreementScrollWatcher();

        const bookingData = JSON.parse(sessionStorage.getItem('bookingData') || '{}');
        const additionalDetails = JSON.parse(sessionStorage.getItem('additionalDetails') || '{}');

        // Decide which agreement template to show (standard vs vehicle)
        // Use bookingData hints (storage type, unit group/desc, explicit flag) + legacy L003 rule
        (function() {
            const locationCode = (bookingData.locationCode || '').toString().toUpperCase();
            const $standard = $('#standardAgreementContent');
            const $vehicle = $('#vehicleAgreementContent');

            const storageType = (bookingData.storageType || '').toString().toLowerCase();
            const unit = bookingData.unit || {};
            const group = (unit.group || '').toString().toLowerCase();
            const unitDesc = (unit.unitDesc || unit.building || '').toString().toLowerCase();
            const explicitFlag = bookingData.isVehicleBooking === true;

            const heuristicsVehicle = storageType === 'vehicle'
                || group.includes('vehicle') || group.includes('parking')
                || unitDesc.includes('vehicle') || unitDesc.includes('parking');

            const useVehicle = explicitFlag || heuristicsVehicle || locationCode === 'L003';

            if (useVehicle && $vehicle.length) {
                $standard.hide();
                $vehicle.show();
            } else {
                $vehicle.hide();
                $standard.show();
            }
        })();

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
            await loadInsuranceOptions(bookingData.locationCode);
        } else {
            $('#maximum_replacement_value').empty().append('<option value="">Location code not found</option>');
        }

        $('.show-more-for-store-protect').on('click', function (e) {
            e.preventDefault();
            $('.full-description-store-protect').toggle();
            $(this).text($('.full-description-store-protect').is(':visible') ? '...less' : '...more');
        });

        $('.show-more-for-own-insurance').on('click', function (e) {
            e.preventDefault();
            $('.full-description-own-insurance').toggle();
            $(this).text($('.full-description-own-insurance').is(':visible') ? '...less' : '...more');
        });

        $('.show-more-for-no-insurance').on('click', function (e) {
            e.preventDefault();
            $('.full-description-no-insurance').toggle();
            $(this).text($('.full-description-no-insurance').is(':visible') ? '...less' : '...more');
        });

        $('.terms-and-conditions').on('change', handleCheckboxSelection);

        $('#maximum_replacement_value').on('change', function () {
            checkFormValidity();
            updateInsuranceSummaryFromCurrentSelection();
        });

        $('.terms-and-conditions_for_license').on('click', function () {
            const insuranceValue = parseInt($('#maximum_replacement_value').val(), 10) || 0;

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
            updateInsuranceSummaryFromCurrentSelection();
        });

        // Initial sync to summary from any existing insuranceTerms
        if (typeof window.updateCheckoutSummaryFromSession === 'function') {
            window.updateCheckoutSummaryFromSession();
        }

        restoreInsuranceFormFromSession();

        $('#insuranceTermsForm').on('submit', function (e) {
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

            const snapshot = getCurrentInsuranceSelection() || {
                insurance: selectedOptionForTermsAndConditions || '',
                coverage: '0',
                coverage_id: '0',
                premium: '0'
            };

            const formData = {
                insurance: snapshot.insurance,
                coverage: snapshot.coverage,
                coverage_id: snapshot.coverage_id,
                premium: snapshot.premium,
                storageTermsAccepted: true,
                signature: canvas.toDataURL('image/png'),
                timestamp: new Date().toISOString()
            };

            saveInsuranceTermsPartial(formData);

            try {
                var leadId = sessionStorage.getItem('bookingLeadId');
                if (leadId) {
                    $.post('<?php echo base_url('MammothStorageController/update_lead_step'); ?>', {
                        lead_id: leadId,
                        step: 4
                    });
                }
            } catch (e) {
            }

            Swal.fire({
                icon: 'success',
                title: 'Insurance Saved!',
                text: 'Preparing secure payment...',
                timer: 1200,
                showConfirmButton: false
            });

            if (typeof loadStep === 'function') {
                // Small delay so the toast feels responsive before spinner covers screen
                setTimeout(function () {
                    loadStep('payment');
                }, 400);
            } else {
                setTimeout(function () {
                    window.location.href = '<?php echo base_url('mammoth-storage/payment'); ?>';
                }, 400);
            }
        });
    });
</script>

    <?php include(APPPATH . 'views/storage/mammoth-storage/partials/footer-steps.php'); ?>