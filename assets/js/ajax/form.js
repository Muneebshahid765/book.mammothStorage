
function initMultiStepForm() {

    let currentStep = 1;
    const steps = $('.step');
    const totalSteps = steps.length;
    const $form = $('#multiStepForm');
    const $btnContainer = $('.nav-btns');

    const SUBMIT_COOKIE = 'step2_attempts';
    const SUBMIT_LIMIT = 3;
    const BLOCK_DAYS = 3;
    let step2Submitted = false;

    let selectedOptionForTermsAndConditions = '';
    let selectedOptionForStorageTermsAndConditions = '';
    let selectedOptionForAutopayTermsAndConditions = '';
    let selectedOptionForLicenseTermsAndConditions = '';

    const $nextBtn = $('<button>', {
        id: 'nextBtn',
        class: 'btn btn-custom-primary ms-2 btn-sm',
        type: 'button',
        html: '<i class="bi bi-arrow-right-circle me-1"></i>Next'
    });

    const $prevBtn = $('<button>', {
        id: 'prevBtn',
        class: 'btn btn-custom-primary me-2 btn-sm',
        type: 'button',
        html: '<i class="bi bi-arrow-left-circle me-1"></i>Back'
    });

    const $submitBtn = $('<button>', {
        id: 'submitBtn',
        class: 'btn btn-custom-primary ms-2 btn-sm',
        type: 'submit',
        html: '<i class="bi bi-check-circle me-1"></i>Submit'
    });


    const validators = {
        1: validateStep1,
        2: step_2_validate,
        3: step_3_validate,
        4: step_4_validate,
        5: step_5_validate,
        6: step_6_validate,
        7: step_7_validate,
        8: step_8_validate,
        9: step_9_validate
    };

    function calculateEndDateFromStart(startDateVal) {
        if (!startDateVal) return '';

        const startDate = new Date(startDateVal);
        if (isNaN(startDate.getTime())) return '';

        const endDate = new Date(startDate);
        endDate.setMonth(endDate.getMonth() + 1);
        endDate.setDate(endDate.getDate() - 1);

        const year = endDate.getFullYear();
        const month = String(endDate.getMonth() + 1).padStart(2, '0');
        const day = String(endDate.getDate()).padStart(2, '0');

        return `${year}-${month}-${day}`;
    }

    function formatDateForDisplay(dateStr) {
        if (!dateStr) return '';

        const parts = dateStr.split('-');
        if (parts.length !== 3) return dateStr;

        // If looks like YYYY-MM-DD, convert to DD-MM-YYYY
        if (parts[0].length === 4) {
            return `${parts[2]}-${parts[1]}-${parts[0]}`;
        }

        return dateStr;
    }

    init();

    function init() {
        updateStepUI();
        $btnContainer.append($nextBtn);
        populateExpYears();

        $('#startDate').on('change', function () {
            const startDateVal = $(this).val();
            const endDateVal = calculateEndDateFromStart(startDateVal);

            if (endDateVal) {
                $('#endDate').val(endDateVal);
                $('#endDate').removeClass('is-invalid');
            }
        });

        $(document).on('click', '.toggle-password', function () {
            const $btn = $(this);
            const $input = $($btn.data('target'));
            const $icon = $btn.find('i');

            const isPassword = $input.attr('type') === 'password';
            $input.attr('type', isPassword ? 'text' : 'password');
            $icon.toggleClass('fa-eye fa-eye-slash');
        });



        function sendIncompleteFormEmailWebhook(payload) {
            $.ajax({
                url: 'https://checkout.mammothstorage.com.au/api/v1/customers/send-incomplete-email',
                method: 'POST',
                contentType: 'application/json',
                data: JSON.stringify(payload),
                success: function () {
                    $("#spinner-overlay").hide();

                    Swal.fire({
                        title: 'Application Submitted',
                        text: 'Your progress has been saved.',
                        icon: 'success',
                        confirmButtonText: 'OK',
                        buttonsStyling: false,
                        customClass: {
                            confirmButton: 'btn btn-custom-primary me-2',
                            cancelButton: 'btn btn-custom-gray'
                        },
                        allowOutsideClick: false,
                        allowEscapeKey: false,
                        allowEnterKey: false,
                    }).then(() => {
                        step2Submitted = true;
                        incrementAttemptCookie();
                        currentStep++;
                        updateStepUI();
                    });
                },
                error: function () {
                    $("#spinner-overlay").hide();
                    step2Submitted = true;
                    currentStep++;
                    updateStepUI();
                }
            });
        }


        $btnContainer.on('click', '#nextBtn', function () {
            if (!validateStep(currentStep)) return;

            // === Step 1 to Step 2 transition trigger ===
            if (currentStep === 1 && !step2Submitted && isAllowedToSubmit()) {
                Swal.fire({
                    title: 'Need a Hand Finishing?',
                    allowOutsideClick: false,
                    allowEscapeKey: false,
                    allowEnterKey: false,
                    text: 'If you don’t complete the form now, we’ll keep your info and a team member will contact you to help you complete it.',
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonText: 'Call Me & Complete',
                    cancelButtonText: 'Book Online Now',
                    buttonsStyling: false, // Required for custom classes to work
                    customClass: {
                        confirmButton: 'btn btn-custom-primary me-2',  // Green
                        cancelButton: 'btn btn-custom-gray'          // Red
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        const userCoords = getUserCoordinatesFromURL();
                        const payload = collectTenantIncompleteFormData();

                        $("#spinner-overlay").show(); // Start spinner

                        // Set default first
                        payload.customer_lat = "";
                        payload.customer_lng = "";
                        payload.customer_location_name = "Not Provided";

                        if (userCoords) {
                            const geocoder = new google.maps.Geocoder();
                            geocoder.geocode({ location: userCoords }, function (results, status) {
                                if (status === "OK" && results[0]) {
                                    const placeId = results[0].place_id;
                                    payload.customer_lat = userCoords.lat;
                                    payload.customer_lng = userCoords.lng;

                                    // Now use PlacesService to get the name/title
                                    const service = new google.maps.places.PlacesService(document.createElement('div'));
                                    service.getDetails({ placeId }, function (place, status) {
                                        if (status === google.maps.places.PlacesServiceStatus.OK) {
                                            const placeName = place.name || "";
                                            const address = results[0].formatted_address;

                                            payload.customer_location_name = placeName && !address.startsWith(placeName)
                                                ? `${placeName}, ${address}`
                                                : address;
                                        }

                                        sendIncompleteFormEmailWebhook(payload);
                                    });
                                } else {
                                    sendIncompleteFormEmailWebhook(payload);
                                }
                            });
                        } else {
                            sendIncompleteFormEmailWebhook(payload);
                        }
                    }
                    else {
                        step2Submitted = true;
                        currentStep++;
                        updateStepUI();
                    }
                });

                return;
            }

            currentStep++;
            updateStepUI();
        });

        $btnContainer.on('click', '#prevBtn', function () {
            currentStep--;
            updateStepUI();
        });

        $form.on("submit", async function (e) {
            e.preventDefault();

            if (!validateStep(currentStep)) return;

            const $submitBtn = $(this).find("button[type=submit]");
            $submitBtn.prop("disabled", true).text("Processing...");

            try {
                await sendFinalStepData();
                // $form.hide();
                //$("#thankYouMessage").removeClass("d-none");
            } catch (error) {
                showError(error || "An error occurred. Please try again.");
            } finally {
                $submitBtn.prop("disabled", false).text("Submit");
            }
        });


    }

    function updateStepUI() {
        $('.step').removeClass('active').hide();
        $('#step-' + currentStep).addClass('active').show();
        $('#stepList li').removeClass('active').eq(currentStep - 1).addClass('active');

        $btnContainer.empty();
        if (currentStep > 1) $btnContainer.append($prevBtn);
        if (currentStep < totalSteps) $btnContainer.append($nextBtn);
        if (currentStep === totalSteps) $btnContainer.append($submitBtn);
    }

    function validateStep(step) {
        return validators[step] ? validators[step]() : true;
    }

    // General Field Validator
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


    function validateAccessCode() {
        const $field = $('#accessCode');
        const val = $field.val().trim();
        const isValid = /^\d{6}$/.test(val);
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

    function populateExpMonths(selectedYear) {
        const $month = $('#expMonth');
        const currentYear = new Date().getFullYear();
        const currentMonth = new Date().getMonth(); // 0-based

        let startMonth = 0;
        if (parseInt(selectedYear) === currentYear) {
            startMonth = currentMonth;
        }

        $month.empty().append('<option value="">Expiration Month</option>');
        for (let i = startMonth; i < 12; i++) {
            const monthVal = String(i + 1).padStart(2, '0'); // Ensure leading zero
            $month.append(`<option value="${monthVal}">${monthVal}</option>`);
        }
    }



    // Enforce only numbers and prevent copy/paste for cardNumber & cvv
    $('#cardNumber, #cvv, #licenseNumber, #accessCode').on('keypress', function (e) {
        if (!/\d/.test(String.fromCharCode(e.which))) return false;
    });

    /* $('#cardNumber, #cvv, #licenseNumber, #accessCode').on('paste copy cut', function (e) {
         e.preventDefault();
     });*/

    $('#cardNumber, #cvv, #licenseNumber, #accessCode').on('paste', function (e) {
        const pastedData = (e.originalEvent || e).clipboardData.getData('text');

        if (!/^\d+$/.test(pastedData)) {
            e.preventDefault();
            // Optionally show feedback
            $(this).addClass('is-invalid');
        } else {
            $(this).removeClass('is-invalid');
        }
    });

    // Still prevent copy and cut
    $('#cardNumber, #cvv, #licenseNumber, #accessCode').on('copy cut', function (e) {
        e.preventDefault();
    });

    // Validate length while typing
    $('#cardNumber').on('input', function () {
        const val = $(this).val();
        if (val.length > 16) $(this).val(val.slice(0, 16));
    });

    $('#cvv').on('input', function () {
        const val = $(this).val();
        if (val.length > 3) $(this).val(val.slice(0, 3));
    });

    // Validate length while typing
    $('#accessCode').on('input', function () {
        const val = $(this).val();
        if (val.length > 6) $(this).val(val.slice(0, 6));
    });


    // Populate expMonth based on year
    $('#expYear').on('change', function () {
        populateExpMonths($(this).val());
    });


    $(".show-more-for-store-protect").on("click", function (e) {

        $(".protect-text")
            .toggleClass("margin-top-left-default")
            .toggleClass("margin-top-left-for-descriptions");

        $(".full-description-store-protect").toggle();

    });


    $(".show-more-for-own-insurance").on("click", function (e) {

        $(".protect-text2")
            .toggleClass("margin-top-left-default2")
            .toggleClass("margin-top-left-for-descriptions2");

        $(".full-description-own-insurance").toggle();
    });

    $(".show-more-for-no-insurance").on("click", function (e) {

        $(".protect-text3")
            .toggleClass("margin-top-left-default3")
            .toggleClass("margin-top-left-for-descriptions3");

        $(".full-description-no-insurance").toggle();
    });

    // Expiration year population
    function populateExpYears() {
        const $year = $('#expYear');
        if ($year.length === 0) return;
        const currentYear = new Date().getFullYear();
        for (let i = 0; i < 10; i++) {
            $year.append(`<option value="${currentYear + i}">${currentYear + i}</option>`);
        }
    }

    function isAllowedToSubmit() {
        const attempts = parseInt(Cookies.get(SUBMIT_COOKIE) || '0');
        return attempts < SUBMIT_LIMIT;
    }

    function incrementAttemptCookie() {
        const attempts = parseInt(Cookies.get(SUBMIT_COOKIE) || '0') + 1;
        Cookies.set(SUBMIT_COOKIE, attempts, { expires: BLOCK_DAYS });
    }

    // Step 1: Personal Info
    function validateStep1() {
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

    // Step 2: Customer Info
    function step_2_validate() {
        let valid = true;
        valid &= validateField('dob');
        valid &= validateField('licenseNumber');
        valid &= validateField('licenseState');
        return Boolean(valid);
    }

    // Step 3: Alternate Contact
    function step_3_validate() {
        let valid = true;
        //valid &= validateField('altFirstName');
        //valid &= validateField('altLastName');
        //valid &= validateField('altPhone');

        const mainPhone = $('#phone').val().trim();
        const altPhone = $('#altPhone').val().trim(); // assuming altPhone is the ID for the alternative phone field

        // Clear previous error state
        $('#altPhone').removeClass('is-invalid');

        if (altPhone !== '') {
            if (mainPhone === altPhone) {
                $('#altPhone').addClass('is-invalid');
                Swal.fire({
                    title: 'Duplicate Phone Number',
                    text: 'Alternative contact number must be different from the primary phone number.',
                    icon: 'warning',
                    confirmButtonText: 'OK',
                    buttonsStyling: false,
                    customClass: {
                        confirmButton: 'btn btn-custom-primary me-2'
                    },
                    allowOutsideClick: false,
                    allowEscapeKey: false,
                    allowEnterKey: false,
                });

                valid = false;
            }
        }


        return Boolean(valid);
    }

    // Step 4: Move-in Date Range
    function step_4_validate() {
        let valid = true;
        const start = $('#startDate').val();
        const end = $('#endDate').val();

        valid &= validateField('startDate');
        valid &= validateField('endDate');
        // valid &= validateAccessCode('accessCode');

        if (start && end && new Date(start) > new Date(end)) {
            $('#endDate').addClass('is-invalid');
            $('#endDate').next('.invalid-feedback').text('End Date must be after Start Date.');
            valid = false;
        }

        return Boolean(valid);
    }

    function step_5_validate() {
        const $field = $('#webPassword');
        const value = $field.val().trim();

        // Regex for strong password: at least 8 chars, 1 uppercase, 1 number, 1 special char
        const strongPasswordPattern = /^(?=.*[A-Z])(?=.*\d)(?=.*[!@#$%^&*()_\-+=\[\]{};':"\\|,.<>/?]).{8,}$/;

        // Select the .invalid-feedback that's outside the position-relative wrapper
        const $feedback = $field.closest('.col-12').find('.invalid-feedback');

        if (!strongPasswordPattern.test(value)) {
            $field.addClass('is-invalid');
            $feedback.text(
                'Password must be at least 8 characters long, include at least one uppercase letter, one special character, and one number.'
            ).show();
            return false;
        } else {
            $field.removeClass('is-invalid');
            $feedback.hide();

            const locationCode = $('#locationCode').val().trim();

            loadInsuranceOptions(locationCode);


            return true;
        }

    }


    async function loadInsuranceOptions(locationCode) {


        selectedOptionForLicenseTermsAndConditions = '';
        selectedOptionForTermsAndConditions = '';


        $(".full-description-store-protect").hide();
        $(".full-description-own-insurance").hide();
        $(".full-description-no-insurance").hide();


        $('#storeProtect').prop('checked', false);
        $('#licenseInsurance').prop('checked', false);
        $('#ownInsurance').prop('checked', false);
        $('#noInsurance').prop('checked', false);

        const select = $("#maximum_replacement_value");

        // empty before adding new
        select.empty().append('<option value="">Loading...</option>');

        $("#spinner-overlay").show();

        try {
            const response = await $.ajax({
                url: "https://checkout.mammothstorage.com.au/api/v1/insurance_coverage/getAll", // <-- your PHP file
                method: "POST",
                data: JSON.stringify({ location_code: locationCode }),
                contentType: "application/json",
                dataType: "json"
            });

            select.empty(); // clear old options

            if (response.success && response.data && response.data.length > 0) {

                $("#spinner-overlay").hide();

                select.append('<option value="">Select Insurance Coverage</option>');
                response.data.forEach(item => {
                    select.append(
                        `<option value="${item.dcCoverage}"
                             data-premium="${item.dcPremium}"
                             data-coverage-id="${item.InsurCoverageID}">
                        ${item.sCoverageDesc} - $${parseFloat(item.dcPremium).toFixed(2)}/mo
                    </option>`
                    );
                });
            } else {
                select.append('<option value="">No Insurance Options Available</option>');
            }
        } catch (err) {

            $("#spinner-overlay").hide();

            console.error("Insurance fetch error:", err);
            select.empty().append('<option value="">Error loading insurance</option>');
        }
    }


    function step_6_validate() {

        let valid = true;

        maximum_replacement_value();

        valid &= termsAndConditions();

        if (!valid) {
            Swal.fire({
                title: 'Confirmation Required',
                text: 'Confirm acceptance of StoreProtect, verify Maximum Replacement Value, and disclose any other insurance coverage.',
                icon: 'info',
                confirmButtonText: 'OK',
                buttonsStyling: false, // Required for custom classes to work
                customClass: {
                    confirmButton: 'btn btn-custom-primary me-2',  // Green
                    cancelButton: 'btn btn-custom-gray'          // Red
                },
                allowOutsideClick: false,
                allowEscapeKey: false,
                allowEnterKey: false,
            });
        }

        let startDate = $('#startDate').val();
        let endDate = $('#endDate').val();

        const locationCode = $('#locationCode').val().trim();
        $("#moveInDateText").text(formatDateForDisplay(startDate));
        $("#moveInDateOneMonthLaterText").text(formatDateForDisplay(endDate));

        return Boolean(valid);
    }

    function step_7_validate() {

        let valid = true;

        valid &= storageTermsAndConditions();

        if (!valid) {
            Swal.fire({
                title: 'Terms and Conditions',
                text: 'If you do not accept these terms you cannot proceed with this storage agreement.',
                icon: 'info',
                confirmButtonText: 'OK',
                buttonsStyling: false, // Required for custom classes to work
                customClass: {
                    confirmButton: 'btn btn-custom-primary me-2',  // Green
                    cancelButton: 'btn btn-custom-gray'          // Red
                },
                allowOutsideClick: false,
                allowEscapeKey: false,
                allowEnterKey: false,
            });
        }

        return Boolean(valid);
    }

    // Step 6: Payment Info
    function step_8_validate() {
        let valid = true;

        valid &= validateField('billingName');
        valid &= validateField('billingZip');
        valid &= validateField('billingAddress');
        valid &= validateField('cardType');

        valid &= validateCardNumber();
        valid &= validateCVV();

        valid &= validateField('expYear');
        valid &= validateField('expMonth');


        // If all fields are valid, then check terms and conditions
        if (valid) {
            const termsAccepted = cardPayTermsAndConditions();

            if (!termsAccepted) {
                Swal.fire({
                    title: 'Payment Details Required',
                    text: 'Enter payment details to set up secure monthly AutoPay. By signing up, you agree to recurring charges and receive notices via email, post, and SMS.',
                    icon: 'info',
                    confirmButtonText: 'OK',
                    buttonsStyling: false,
                    customClass: {
                        confirmButton: 'btn btn-custom-primary me-2',
                        cancelButton: 'btn btn-custom-gray'
                    },
                    allowOutsideClick: false,
                    allowEscapeKey: false,
                    allowEnterKey: false,
                });

                return false;
            }

            return true;
        }

        return false;
    }

    // Step 7: Unit Details
    function step_9_validate() {
        let valid = true;


        const locationCode = $('#locationCode').val().trim();
        const unitId = $('#unitId').val().trim();
        const paymentAmount = $('#paymentAmount').val().trim();

        if (!locationCode) {
            $('#locationCode').addClass('is-invalid');
            valid = false;
        } else {
            $('#locationCode').removeClass('is-invalid');
        }

        if (!unitId) {
            $('#unitId').addClass('is-invalid');
            valid = false;
        } else {
            $('#unitId').removeClass('is-invalid');
        }

        if (!paymentAmount) {
            $('#paymentAmount').addClass('is-invalid');
            valid = false;
        } else {
            $('#paymentAmount').removeClass('is-invalid');
        }

        return Boolean(valid);
    }



    function cardPayTermsAndConditions() {

        let isValid = true;
        if (selectedOptionForAutopayTermsAndConditions != '') {

            isValid = true;

        } else {

            isValid = false;

        }

        return isValid;


    }

    function storageTermsAndConditions() {

        let isValid = true;
        if (selectedOptionForStorageTermsAndConditions != '') {

            isValid = true;

        } else {

            isValid = false;

        }

        return isValid;


    }
    function termsAndConditions() {

        let isValid = true;

        if (selectedOptionForTermsAndConditions != '') {

            if (selectedOptionForTermsAndConditions == "storeProtect") {

                if (selectedOptionForLicenseTermsAndConditions != '') {
                    isValid = true;
                } else {

                    isValid = false;
                }
            } else {
                isValid = true;
            }


        } else {

            isValid = false;

        }

        return isValid;
    }

    // Function to handle checkbox selection
    function handleCheckboxSelection(event) {


        maximum_replacement_value();

        var selectedCheckbox = event.target;

        if (selectedCheckbox.type === 'checkbox') {
            // Check if the clicked checkbox is already selected
            if (selectedCheckbox.checked) {
                // Uncheck all checkboxes
                document.querySelectorAll('.terms-and-conditions').forEach(function (box) {
                    if (box !== selectedCheckbox) {
                        box.checked = false;
                    }
                });





                // Show or hide the maxReplacementValue input field
                var maxReplacementValueField = document.getElementById('maximum_replacement_value_section');
                if (selectedCheckbox.id === 'storeProtect') {
                    maxReplacementValueField.style.display = 'block';


                    $(".protect-text2")
                        .removeClass("margin-top-left-for-descriptions2")
                        .addClass("margin-top-left-default2");

                    $(".full-description-own-insurance").hide();

                    $(".protect-text3")
                        .removeClass("margin-top-left-for-descriptions3")
                        .addClass("margin-top-left-default3");

                    $(".full-description-no-insurance").hide();

                    $(".protect-text")
                        .removeClass("margin-top-left-default")
                        .addClass("margin-top-left-for-descriptions");

                    $(".full-description-store-protect").show();

                } else {
                    maxReplacementValueField.style.display = 'none';

                }

                // Set the hidden input field value based on the selected checkbox
                selectedOptionForTermsAndConditions = selectedCheckbox.id;

                selectedOptionForLicenseTermsAndConditions = '';
                $('.terms-and-conditions_for_license').prop('checked', false);


                if (parseInt($('#maximum_replacement_value').val()) > 0) {

                    if (selectedCheckbox.id === 'ownInsurance' || selectedCheckbox.id === 'noInsurance') {

                        $(".protect-text2")
                            .removeClass("margin-top-left-for-descriptions2")
                            .addClass("margin-top-left-default2");

                        $(".full-description-own-insurance").hide();


                        $(".protect-text3")
                            .removeClass("margin-top-left-for-descriptions3")
                            .addClass("margin-top-left-default3");

                        $(".full-description-no-insurance").hide();

                        Swal.fire({
                            title: 'Insurance Confirmed',
                            text: 'This lease includes StoreProtect coverage for your belongings. If you wish to make any changes or update the coverage amount, please contact the Mammoth Team for assistance.',
                            icon: 'success',
                            confirmButtonText: 'OK',
                            customClass: {
                                confirmButton: 'btn btn-custom-primary me-2',  // Green
                                cancelButton: 'btn btn-custom-gray'          // Red
                            },
                            allowOutsideClick: false,
                            allowEscapeKey: false,
                            allowEnterKey: false,
                        });

                        $("#" + selectedCheckbox.id).prop('checked', false);
                        selectedOptionForTermsAndConditions = '';

                    }
                } else {

                    if (selectedCheckbox.id === 'ownInsurance') {


                        $(".protect-text")
                            .removeClass("margin-top-left-for-descriptions")
                            .addClass("margin-top-left-default");

                        $(".full-description-store-protect").hide();

                        $(".protect-text3")
                            .removeClass("margin-top-left-for-descriptions3")
                            .addClass("margin-top-left-default3");

                        $(".full-description-no-insurance").hide();


                        $(".protect-text2")
                            .removeClass("margin-top-left-default2")
                            .addClass("margin-top-left-for-descriptions2");

                        $(".full-description-own-insurance").show();

                    } else if (selectedCheckbox.id === 'noInsurance') {

                        $(".protect-text")
                            .removeClass("margin-top-left-for-descriptions")
                            .addClass("margin-top-left-default");

                        $(".full-description-store-protect").hide();


                        $(".protect-text2")
                            .removeClass("margin-top-left-for-descriptions2")
                            .addClass("margin-top-left-default2");

                        $(".full-description-own-insurance").hide();

                        $(".protect-text3")
                            .removeClass("margin-top-left-default3")
                            .addClass("margin-top-left-for-descriptions3");

                        $(".full-description-no-insurance").show();

                    }
                }

            } else {
                // Uncheck the selected checkbox if it was already unchecked
                selectedCheckbox.checked = false;

                document.getElementById('maximum_replacement_value_section').style.display = 'none';

                selectedOptionForTermsAndConditions = '';

                $(".protect-text")
                    .removeClass("margin-top-left-for-descriptions")
                    .addClass("margin-top-left-default");

                $(".full-description-store-protect").hide();

                $(".protect-text2")
                    .removeClass("margin-top-left-for-descriptions2")
                    .addClass("margin-top-left-default2");

                $(".full-description-own-insurance").hide();

                $(".protect-text3")
                    .removeClass("margin-top-left-for-descriptions3")
                    .addClass("margin-top-left-default3");

                $(".full-description-no-insurance").hide();



            }
        }
    }

    // Attach event listeners to checkboxes and the submit button
    document.querySelectorAll('.terms-and-conditions').forEach(function (checkbox) {
        checkbox.addEventListener('change', handleCheckboxSelection);
    });

    // Event handler for the checkbox click
    $('.storage-terms-and-conditions').click(function () {

        if ($(this).is(':checked')) {
            // Set the variable to the id of the checked checkbox
            selectedOptionForStorageTermsAndConditions = $(this).attr('id');
        } else {
            // Clear the variable if the checkbox is unchecked
            selectedOptionForStorageTermsAndConditions = '';
        }
    });


    // Event handler for the checkbox click
    $('.autoplay-terms-and-conditions').click(function () {

        if ($(this).is(':checked')) {
            // Set the variable to the id of the checked checkbox
            selectedOptionForAutopayTermsAndConditions = $(this).attr('id');
        } else {
            // Clear the variable if the checkbox is unchecked
            selectedOptionForAutopayTermsAndConditions = '';
        }
    });

    // Event handler for the checkbox click
    $('.terms-and-conditions_for_license').click(function () {


        if (parseInt($('#maximum_replacement_value').val()) > 0) {
            if (selectedOptionForTermsAndConditions == "storeProtect") {

                if ($(this).is(':checked')) {
                    // Set the variable to the id of the checked checkbox
                    selectedOptionForLicenseTermsAndConditions = $(this).attr('id');
                } else {
                    // Clear the variable if the checkbox is unchecked
                    selectedOptionForLicenseTermsAndConditions = '';
                }
            } else {
                // Clear the variable if the checkbox is unchecked
                selectedOptionForLicenseTermsAndConditions = '';

                Swal.fire({
                    title: 'Access Agreement Required',
                    text: 'Firstly, please accept the agreement of StoreProtect.',
                    icon: 'info',
                    confirmButtonText: 'OK',
                    customClass: {
                        confirmButton: 'btn btn-custom-primary me-2',  // Green
                        cancelButton: 'btn btn-custom-gray'          // Red
                    },
                    allowOutsideClick: false,
                    allowEscapeKey: false,
                    allowEnterKey: false,
                });

                $(this).prop('checked', false);
            }
        } else {


            Swal.fire({
                title: 'Confirmation Required',
                text: 'No Store Protect coverage has been selected, please let the Mammoth Team know what value your goods are that you would like to cover. Or select another option.',
                icon: 'info',
                confirmButtonText: 'OK',
                customClass: {
                    confirmButton: 'btn btn-custom-primary me-2',  // Green
                    cancelButton: 'btn btn-custom-gray'          // Red
                },
                allowOutsideClick: false,
                allowEscapeKey: false,
                allowEnterKey: false,
            });

            $(this).prop('checked', false);
        }

        // selectedOptionForLicenseTermsAndConditions = '';
        maximum_replacement_value();

    });

    function maximum_replacement_value() {
        // parseInt($('#maximum_replacement_value').val(0));
        $('#maximum_replacement_value').prop('readonly', true);
    }

    // Watch for any changes or input attempts
    $('#maximum_replacement_value').on('change input', function () {

        maximum_replacement_value();
    });
}