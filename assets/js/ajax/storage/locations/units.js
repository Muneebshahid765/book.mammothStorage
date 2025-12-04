
// Define locations for each type
const locationTypes = {
    storage: ["L001", "L002", "L004", "L006", "L007", "L008"],
    vehicle: ["L003", "L005"]
};

/*

// Define locations for each type
const locationTypes = {
    storage: ["L001"],
    vehicle: ["L003", "L005"]
};
*/


const locationCoordinates = {
    L001: { lat: -26.6830022, lng: 153.0044652, name: "Forest Glen", address: "Shop 14 /380 Mons Road, Forest Glen QLD 4556" },
    L002: { lat: -26.6675501, lng: 153.0932467, name: "Maroochydore", address: "31/82 Sugar Rd, Maroochydore QLD 4558, Australia" },
    L003: { lat: -26.6131889, lng: 152.9608031, name: "Nambour", address: "33-49 Zealey Rd, Nambour QLD 4560" },
    L004: { lat: -26.7850723, lng: 153.0613775, name: "Caloundra", address: "40-42 Geo Hawkins Cres, Bells Creek QLD 4551" },
    L005: { lat: -25.320437, lng: 152.8895147, name: "Hervey Bay", address: "34 Southern Cross Circuit Urangan" },
    L006: { lat: -24.8963294, lng: 152.3256104, name: "Bundaberg", address: "38 Commerical Street, Kensington QLD" },
    L007: { lat: -26.6621377, lng: 153.0450456, name: "Kunda Park", address: "486 Maroochydore Rd, Kunda Park, QLD 4558, Australia" },
    L008: { lat: -26.1696936, lng: 152.6488382, name: "Gympie", address: "18 Bonnick Rd, Gympie QLD 4570" },

};

function getQueryParameter(name) {
    const urlParams = new URLSearchParams(window.location.search);
    return urlParams.get(name);
}

function getUserCoordinatesFromURL() {
    const urlParams = new URLSearchParams(window.location.search);
    const lat = parseFloat(urlParams.get("lat"));
    const lng = parseFloat(urlParams.get("lng"));
    return (!isNaN(lat) && !isNaN(lng)) ? { lat, lng } : null;
}

// Get the type from the URL
const type = getQueryParameter("type");
let locations = [];

// Determine locations based on type
if (type === "storage") {
    locations = locationTypes.storage;
} else if (type === "vehicle") {
    locations = locationTypes.vehicle;
} else {
    /*Swal.fire({
        icon: "error",
        title: "Invalid Type",
        text: "No location type specified in the URL. Please specify a valid type: 'storage' or 'vehicle'.",
    });*/

    locations = locationTypes.storage;
}


const unitDataStore = {};
const unitImagesStore = {};
const specialsStore = {};

function storeSpecials(locationCode, specials) {
    specialsStore[locationCode] = Array.isArray(specials) ? specials : [];
}

function findMatchingSpecial(unit, locationCode) {
    const specials = specialsStore[locationCode] || [];
    if (!specials.length) return null;

    const sizeKey = `${unit.dcWidth}x${unit.dcLength}`;

    return specials.find(special => {
        // Prefer table-based size mappings when available
        if (Array.isArray(special.size_keys) && special.size_keys.length > 0) {
            return special.size_keys.indexOf(sizeKey) !== -1;
        }

        // Fallback to legacy dcWidth/dcLength matching
        if (special.dcWidth == null || special.dcLength == null) return false;
        const wMatch = Math.abs(parseFloat(special.dcWidth) - parseFloat(unit.dcWidth)) < 0.01;
        const lMatch = Math.abs(parseFloat(special.dcLength) - parseFloat(unit.dcLength)) < 0.01;
        return wMatch && lMatch;
    }) || null;
}

function getPriceWithSpecial(unit, locationCode) {
    const base = parseFloat(unit.dcStdRate);
    if (isNaN(base)) {
        return { price: '0.00', label: null };
    }

    const special = findMatchingSpecial(unit, locationCode);
    if (!special) {
        return { price: base.toFixed(2), label: null };
    }

    let finalPrice = base;
    let label = '';
    const type = (special.sDiscountType || '').toLowerCase();
    const value = parseFloat(special.dDiscountPrice);

    if (type === 'percentage' && !isNaN(value) && value > 0) {
        finalPrice = base * (1 - value / 100);
        label = `${value}% off - ${special.title}`;
    } else if (type === 'fixed' && !isNaN(value) && value > 0) {
        finalPrice = base - value;
        if (finalPrice < 0) finalPrice = 0;
        label = `$${value.toFixed(2)} off - ${special.title}`;
    } else if (type === 'free_months') {
        const mPay = parseInt(special.months_to_pay, 10);
        const mFree = parseInt(special.free_months, 10);
        const total = (!isNaN(mPay) && !isNaN(mFree)) ? (mPay + mFree) : 0;

        if (mPay > 0 && total > 0) {
            finalPrice = base * (mPay / total);
            label = special.title || `Buy ${mPay}, Get ${mFree} Free`;
        } else {
            return { price: base.toFixed(2), label: special.title || null };
        }
    } else {
        return { price: base.toFixed(2), label: special.title || null };
    }

    return { price: finalPrice.toFixed(2), label };
}


function fetchLocationData(locationCode) {
    return $.ajax({
        url: `${base_url}/units/fetch`,
        type: "POST",
        data: JSON.stringify({ locationCode: locationCode }),
        dataType: "json",
        contentType: 'application/json',
    });
}

function getImageUrl(locationCode, unit, type = "by_size") {
    // Default fallback image
    const fallbackUrl = "https://mammothselfstore.com.au/wp-content/uploads/2021/08/logo.svg";
    let imageUrl = fallbackUrl;

    // If no images for this location, return fallback immediately
    if (!unitImagesStore[locationCode] || unitImagesStore[locationCode].length === 0) {
        return imageUrl;
    }

    if (type === "by_location") {
        // Just return the first image for this location, or fallback
        imageUrl = unitImagesStore[locationCode][0]?.url || fallbackUrl;
    } else if (type === "by_size") {
        // Match by width × length
        const match = unitImagesStore[locationCode].find(
            img =>
                parseFloat(img.dcWidth) === parseFloat(unit.dcWidth) &&
                parseFloat(img.dcLength) === parseFloat(unit.dcLength)
        );
        imageUrl = match ? match.url : fallbackUrl;
    }

    return imageUrl;
}



function renderSmallestSize(groupName, locationCode) {
    if (!unitDataStore[locationCode] || !unitDataStore[locationCode][groupName]) return "";

    const firstSizeKey = Object.keys(unitDataStore[locationCode][groupName]).sort((a, b) => parseFloat(a) - parseFloat(b))[0];
    const smallestUnits = unitDataStore[locationCode][groupName][firstSizeKey];
    if (!smallestUnits || smallestUnits.length === 0) return "";

    const smallestUnit = smallestUnits[0];
    const pricing = getPriceWithSpecial(smallestUnit, locationCode);
    const specialHtml = pricing.label
        ? `<div class="mt-1"><span class="badge bg-warning text-dark">${pricing.label}</span></div>`
        : '';

    // ✅ Now just call getImageUrl
    const imageUrl = getImageUrl(locationCode, smallestUnit, "by_size");


    return `
        <div class="size-group mb-3">
            <div class="d-flex flex-row bg-light p-3 shadow-sm rounded sizes-section align-items-center">

            <!-- Image -->
            <div class="me-3">
                <a href="${imageUrl}" target="_blank">
                    <img src="${imageUrl}" alt="${smallestUnit.dcWidth} × ${smallestUnit.dcLength}m"
                        class="img-fluid rounded shadow-sm"
                        style="max-width:100px; cursor:pointer;">
                </a>
            </div>

        <!-- Text Content -->
        <div class="flex-grow-1">
            <h6 class="fw-bold text-secondary mb-1">${groupName}</h6>
            <span>
                <i class="bi bi-box me-1"></i> ${smallestUnit.dcWidth} × ${smallestUnit.dcLength}m
                <i class="bi bi-grid me-1"></i>
                (${(smallestUnit.dcWidth * smallestUnit.dcLength).toFixed(2)} m²)
            </span>

            <!-- Price & Button on same row -->
            <div class="d-flex justify-content-between align-items-center mt-3 w-100">
                <span class="badge p-2 text-secondary fs-6 fw-bold">
                    $${pricing.price}/mo
                </span>
                ${specialHtml}
                <button class="btn btn-custom-primary btn-sm show-all-units d-flex align-items-center"
                        data-location="${locationCode}"
                        data-key="${groupName}_${smallestUnit.dcWidth.toFixed(1)}x${smallestUnit.dcLength.toFixed(1)}">
                    <i class="bi bi-arrow-right-circle me-1"></i> Continue
                </button>
            </div>
        </div>

    </div>
</div>
`;

}

function renderAllSizes(groupedData, locationCode) {
    let content = "";
    let index = 0;


    Object.keys(groupedData).forEach(group => {
        const isActive = index === 0 ? "show" : "";
        const isExpanded = index === 0 ? "true" : "false";
        const iconClass = index === 0 ? "bi bi-chevron-up" : "bi bi-chevron-down";

        content += `<div class="card mb-4">
                        <div class="card-header bg-custom-primary text-white d-flex justify-content-between align-items-center">
                            <h5 class="fw-bold mb-0 text-white d-flex align-items-center text-start">
                                <i class="bi bi-box-seam fs-3 me-3"></i>
                                ${group} Storage Size
                            </h5>
                            <button class="btn btn-link text-white text-decoration-none fw-bold d-flex align-items-center"
                                    data-bs-toggle="collapse"
                                    data-bs-target="#collapse_${locationCode}_${index}"
                                    aria-expanded="${isExpanded}"
                                    aria-controls="collapse_${locationCode}_${index}">
                                <i class="${iconClass} fs-5 toggle-icon"></i>
                            </button>
                        </div>
                        <div id="collapse_${locationCode}_${index}" class="collapse ${isActive}" data-bs-parent="#accordion_${locationCode}">
                            <div class="card-body">
                                <p class="text-muted">Find the perfect storage solutions under ${group} Storage Size:</p>`;

        Object.keys(groupedData[group]).forEach(size => {
            const unitsForSize = groupedData[group][size];
            const uniqueGroups = {};

            unitsForSize.forEach(unit => {
                const key = `${unit.dcWidth.toFixed(1)}x${unit.dcLength.toFixed(1)}`;
                if (!uniqueGroups[key]) {
                    uniqueGroups[key] = [];
                }
                uniqueGroups[key].push(unit);
            });

            Object.keys(uniqueGroups).sort((a, b) => {
                const [widthA, lengthA] = a.split("x").map(parseFloat);
                const [widthB, lengthB] = b.split("x").map(parseFloat);

                if (widthA !== widthB) return widthA - widthB;
                if (lengthA !== lengthB) return lengthA - lengthB;
                return 0;
            }).forEach(key => {
                const unitsForGroup = uniqueGroups[key];
                const exampleUnit = unitsForGroup[0];
                const pricing = getPriceWithSpecial(exampleUnit, locationCode);
                const specialHtml = pricing.label
                    ? `<div class="mt-1"><span class="badge bg-warning text-dark">${pricing.label}</span></div>`
                    : '';

                // ✅ Get image using shared helper
                const imageUrl = getImageUrl(locationCode, exampleUnit, "by_size");

                content += `
                <div class="size-group mb-3">
                    <div class="d-flex flex-row bg-light p-3 shadow-sm rounded sizes-section align-items-center">

                    <!-- Image -->
                        <div class="me-3">
                            <a href="${imageUrl}" target="_blank">
                                <img src="${imageUrl}" alt="${exampleUnit.dcWidth} × ${exampleUnit.dcLength}m"
                                     class="img-fluid rounded shadow-sm"
                                     style="max-width:100px; cursor:pointer;">
                            </a>
                        </div>


                        <div class="flex-grow-1">
                            <h6 class="fw-bold text-secondary mb-1">${group} - (${unitsForGroup.length} ${unitsForGroup.length === 1 ? 'unit' : 'units'})</h6>
                            <span>
                                <i class="bi bi-box me-1"></i>
                                ${exampleUnit.dcWidth}m × ${exampleUnit.dcLength}m
                                <i class="bi bi-grid me-1"></i>
                                (${(exampleUnit.dcWidth * exampleUnit.dcLength).toFixed(2)}m²)
                            </span>

                            <div class="d-flex justify-content-between align-items-center mt-3 w-100">
                                <span class="badge p-2 fs-6 text-secondary fw-bold">
                                    $${pricing.price}/mo
                                </span>
                                ${specialHtml}
                                <button class="btn btn-custom-primary btn-sm d-flex align-items-center show-all-units"
                                        data-location="${locationCode}"
                                        data-key="${group}_${key}">
                                    <i class="bi bi-arrow-right-circle me-1"></i> Continue
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                `;
            });
        });

        content += `</div></div></div>`;
        index++;
    });

    return `<div id="accordion_${locationCode}">${content}</div>`;
}

function renderLocation(location, locationCode) {
    const groupedData = unitDataStore[locationCode];
    const container = $("#locations-container");
    const locationHTML = `

        <div class="location mb-4 rounded bg-white">
           <div class="bg-custom-primary text-white p-4 rounded shadow-sm mb-4">
                <div class="row align-items-center">
                    <div class="col-12 col-sm d-flex align-items-center mb-3 mb-md-0">
                        <i class="bi bi-building fs-4 me-3"></i>
                        <h4 class="mb-0 fw-bold text-white">${location.name}</h4>
                    </div>
                    <div class="col-12 col-sm-auto text-md-end">
                    <div class="distance-km-text"></div>
                        <a href="${location.view_center}" target="_blank" class="btn btn-outline-light btn-sm d-flex align-items-center">
                            View Centre
                            <i class="bi bi-arrow-right ms-2"></i>
                        </a>

                    </div>
                </div>
                <hr class="border-light my-3">
                <div class="d-flex flex-column flex-md-row justify-content-between">
                    <div class="mb-2 mb-md-0 d-flex align-items-center">
                        <i class="bi bi-geo-alt-fill text-light fs-5 me-2"></i>
                        <p class="mb-0">
                            <span class="fw-bold">Address:&nbsp;</span>${location.address}
                        </p>
                    </div>
                    <div class="d-flex align-items-center">
                        <i class="bi bi-telephone-fill text-light fs-5 me-2"></i>
                        <p class="mb-0">
                            <span class="fw-bold">Phone:&nbsp;</span>${location.number}
                        </p>
                    </div>
                </div>
            </div>
            <div class="card mb-4">
                <div class="card-body">
                        ${Object.keys(groupedData || {})
            .map(group => renderSmallestSize(group, locationCode))
            .join("")}
                </div>
            </div>
            <div class="row mt-3 p-3 align-items-center">
                <div class="col-12 col-sm d-flex align-items-center mb-3 mb-md-0">
                    <a href="https://mammothselfstore.com.au/storage-calculator/"  target="_blank" class="text-custom-primary size-guide d-flex align-items-center">
                        <i class="bi bi-rulers fs-5 me-2"></i>
                        Size guide
                    </a>
                </div>
                <div class="col-12 col-sm-auto text-md-end">
                    <a href="#" class="text-custom-primary view-all-sizes d-flex align-items-center" data-location="${location.name}" data-location-code="${locationCode}">
                        <i class="bi bi-box-arrow-down fs-5 me-2"></i>
                        Show more units at this centre <i class="bi bi-arrow-down fs-6 ms-1"></i>
                    </a>
                </div>
            </div>
        </div>
    `;
    container.append(locationHTML);
}

// Function to get card type dropdown options based on location code
function getCardTypeOptions(locationCode) {
    const cardTypeMapping = {
        "L001": { "1211": { value: "5", name: "MasterCard" }, "1212": { value: "6", name: "Visa Card" } },
        "L002": { "2471": { value: "5", name: "MasterCard" }, "2472": { value: "6", name: "Visa Card" } },
        "L003": { "4271": { value: "5", name: "MasterCard" }, "4272": { value: "6", name: "Visa Card" } },
        "L004": { "4331": { value: "5", name: "MasterCard" }, "4332": { value: "6", name: "Visa Card" } },
        "L005": { "5311": { value: "5", name: "MasterCard" }, "5312": { value: "6", name: "Visa Card" } },
        "L006": { "5371": { value: "5", name: "MasterCard" }, "5372": { value: "6", name: "Visa Card" } },
        "L007": { "6131": { value: "5", name: "MasterCard" }, "6132": { value: "6", name: "Visa Card" } },
        "L008": { "6211": { value: "5", name: "MasterCard" }, "6212": { value: "6", name: "Visa Card" } }
    };

    let options = `<option style="display: none;" value="">Select Card Type</option>`;

    if (cardTypeMapping[locationCode]) {
        Object.keys(cardTypeMapping[locationCode]).forEach(cardPrefix => {
            const card = cardTypeMapping[locationCode][cardPrefix];
            options += `<option value="${card.value}" data-card-prefix="${cardPrefix}">${card.name}</option>`;
        });
    } else {
        options = `<option value="">No Card Type Available</option>`;
    }

    return options;
}


$(document).on("click", ".show-all-units", function (event) {
    event.preventDefault(); // Prevent default anchor behavior


    const parentContainer = $(this).closest(".size-group");
    const existingForm = parentContainer.find(".unit-form-container");

    // Blur all other .sizes-section except the clicked one
    /*$(".sizes-section").not($(this).closest(".sizes-section")).css({
        "filter": "blur(4px)",
        "pointer-events": "none"
    });*/

    // If the form is already present and visible, slide up and remove it
    if (existingForm.length && existingForm.is(":visible")) {
        existingForm.slideUp(300, function () {
            $(this).remove();

            // Reset blur on all sizes-section
            /* $(".sizes-section").css({
                 "filter": "none",
                 "pointer-events": "auto"
             });*/
        });
        return; // Exit function, no need to recreate the form
    }

    // Helper function to remove all unit-form-containers first
    function removeOldForms2() {
        return new Promise((resolve) => {
            const forms = $(".unit-form-container");
            let remaining = forms.length;

            if (remaining === 0) {
                resolve();
            }

            forms.slideUp(300, function () {
                $(this).remove();
                remaining--;
                if (remaining === 0) {
                    resolve();
                }
            });
        });
    }

    function removeOldForms() {
        return new Promise((resolve) => {
            $(".unit-form-container").each(function () {
                $(this).hide().remove();
            });
            resolve();
        });
    }




    const locationCode = $(this).data("location");
    const locationName = locationCoordinates[locationCode].name;
    const key = $(this).data("key");
    const [group, sizeKey] = key.split("_");
    const [width, length] = sizeKey.split("x");
    const units = unitDataStore[locationCode][group] && unitDataStore[locationCode][group][width] ? unitDataStore[locationCode][group][width].filter(unit => unit.dcLength.toFixed(1) === length) : [];

    if (units.length) {
        // console.log(`Location: ${locationCode}, All Units for Group: ${group}, Size: ${width}x${length}m`, units);

        const randomUnit = units[Math.floor(Math.random() * units.length)];

        // console.log(`Selected Unit Details:`, randomUnit);

        /* Swal.fire({
             icon: "success",
             title: "Storage Unit Selected",
             text: `Location: ${locationCode}, Unit ID: ${randomUnit.UnitID}, Price: $${randomUnit.dcStdRate}`,
             willOpen: () => {
                 $(document.body).addClass('swal-open');
             },
             willClose: () => {
                 $(document.body).removeClass('swal-open');
             }
         });*/

        // Get Australia/Brisbane date
        const now = new Date().toLocaleString("en-US", { timeZone: "Australia/Brisbane" });
        const currentDate = new Date(now);
        const startDate = currentDate.toISOString().split("T")[0]; // Today's date
        currentDate.setMonth(currentDate.getMonth() + 1);
        currentDate.setDate(currentDate.getDate() - 1);
        const endDate = currentDate.toISOString().split("T")[0]; // +1 month

        const cardTypeOptions = getCardTypeOptions(locationCode);


        const formHtml = `

<div class="unit-form-container" style="display: none;">


  <div class="form-wrapper row mx-auto">

<div class="row">
        <div id="form-error-alert" class="alert alert-danger alert-white rounded d-none">
            <div class="icon">
                <i class="fa fa-times-circle"></i>
            </div>
            <strong>Error!</strong> <span class="form-error-message">Something went wrong.</span>
        </div>
</div>

    <div class="col-md-3 step-sidebar">
      <h2>Storage Application Form</h2>
      <p class="mt-3">Fill in your details to continue with your storage setup.</p>
      <ul class="list-unstyled mt-4" id="stepList">
        <li class="active"><i class="fas fa-user me-2"></i>Personal Information</li>
        <li><i class="fas fa-id-card me-2"></i>Customer Information</li>
        <li><i class="fas fa-user-friends me-2"></i>Alternate Contact Information</li>
        <li><i class="fas fa-calendar-alt me-2"></i>Move-in Info</li>
         <li><i class="fas fa-shield-alt me-2"></i>Security Credentials</li>
          <li><i class="fas fa-file-contract me-2"></i>Terms & Conditions</li>
<li><i class="fas fa-file-signature me-2"></i>Storage Terms & Conditions</li>

        <li><i class="fas fa-credit-card me-2"></i>Payment Information</li>
        <li><i class="fas fa-warehouse me-2"></i>Unit Details</li>
      </ul>

    </div>
    <div class="col-md-9 form-section">






      <form id="multiStepForm" novalidate>
        <!-- Step 1 -->
        <div class="step active" id="step-1">

          <h4 class="mb-3"><strong>Personal Information</strong></h4>
          <p class="text-muted">Enter your personal information to begin your storage move-in process.</p>
          <div class="row g-3">
            <div class="col-md-6">
              <label for="firstName" class="form-label">First Name</label>
              <input type="text" class="form-control" placeholder="First Name" id="firstName" required>
              <div class="invalid-feedback">First name cannot be empty.</div>
            </div>
            <div class="col-md-6">
              <label for="lastName" class="form-label">Last Name</label>
              <input type="text" class="form-control" placeholder="Last Name" id="lastName" required>
              <div class="invalid-feedback">Last name cannot be empty.</div>
            </div>
            <div class="col-md-6">
              <label for="country" class="form-label">Country</label>
              <select id="country" class="form-select" required>
                <option value="">Select Country</option>
                <option value="Australia">Australia</option>
              </select>
              <div class="invalid-feedback">Country must be selected.</div>
            </div>
            <div class="col-md-6">
              <label for="countryCode" class="form-label">Country Code</label>
              <select id="countryCode" class="form-select" required>
                <option value="">Select Country Code</option>
                <option value="+61">AUD (+61)</option>
              </select>
              <div class="invalid-feedback">Country code must be selected.</div>
            </div>

            <div class="col-md-6">
                <label for="city" class="form-label">City</label>
                <input type="text" class="form-control" placeholder="City" id="city" required>
                <div class="invalid-feedback">City cannot be empty.</div>
            </div>
            <div class="col-md-6">
                <label for="state" class="form-label">State</label>
                <input type="text" class="form-control" placeholder="State" id="state" required>
                <div class="invalid-feedback">State cannot be empty.</div>
            </div>


            <div class="col-md-6">
              <label for="email" class="form-label">Email</label>
              <input type="email" class="form-control" placeholder="Email Address" id="email" required>
              <div class="invalid-feedback">Email cannot be empty.</div>
            </div>
            <div class="col-md-6">
              <label for="phone" class="form-label">Phone</label>
              <input type="tel" class="form-control" id="phone" placeholder="Phone Number" required>
              <div class="invalid-feedback">Phone number cannot be empty.</div>
            </div>
          </div>
        </div>

        <!-- Step 2 -->
        <div class="step" id="step-2">
          <h4 class="mb-3"><strong>Customer Information</strong></h4>
          <p class="text-muted">Provide identification and access credentials to verify your identity and enable facility access.</p>
          <div class="row g-3">
            <div class="col-md-12">
              <label for="dob" class="form-label">Date of Birth</label>
              <input type="date" class="form-control" id="dob">
              <div class="invalid-feedback">Date of Birth is required.</div>
            </div>
             <div class="col-md-6">
              <label for="licenseState" class="form-label">License State</label>
              <select class="form-select" id="licenseState">
                <option value="">License State</option>
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
            </div>
            <div class="col-md-6">
              <label for="licenseNumber" class="form-label">License Number</label>
              <input type="text" class="form-control" id="licenseNumber" placeholder="Enter License Number">
              <div class="invalid-feedback">License Number is required.</div>
            </div>
          </div>
        </div>



        <!-- Step 3 -->
        <div class="step" id="step-3">
          <h4 class="mb-3"><strong>Alternate Contact Information</strong></h4>
          <p class="text-muted">Enter contact details for an alternative person we can reach if needed.</p>
          <div class="row g-3">
            <div class="col-md-6">
              <label for="altFirstName" class="form-label">Alternative First Name</label>
              <input type="text" class="form-control" id="altFirstName" placeholder="Alternative First Name">
              <div class="invalid-feedback">Alternative first name is required.</div>
            </div>
            <div class="col-md-6">
              <label for="altLastName" class="form-label">Alternative Last Name</label>
              <input type="text" class="form-control" id="altLastName" placeholder="Alternative Last Name">
              <div class="invalid-feedback">Alternative last name is required.</div>
            </div>
            <div class="col-md-6">
              <label for="altPhone" class="form-label">Alternative Phone</label>
              <input type="text" class="form-control" id="altPhone" placeholder="Alternative Phone">
              <div class="invalid-feedback">Alternative phone is required.</div>
            </div>
          </div>
        </div>


        <!-- Step 4 -->
        <div class="step" id="step-4">
          <h4 class="mb-3"><strong>Move-in Date Range</strong></h4>
          <p class="text-muted">Choose your storage start date. Your agreement will be month to month until you give us 14 days notice.</p>
          <div class="row g-3">
            <div class="col-md-12">
              <label for="startDate" class="form-label">Start Date</label>
              <input type="date" class="form-control" id="startDate">
              <div class="invalid-feedback">Start date is required.</div>
            </div>
            <div class="col-md-6 d-none">
              <label for="endDate" class="form-label">End Date</label>
              <input type="hidden" class="form-control" id="endDate">
              <div class="invalid-feedback">End date is required.</div>
            </div>

            <div class="col-md-6 d-none">
              <label for="accessCode" class="form-label">Access Code (Must be 6 digits)</label>
              <input type="hidden" class="form-control" id="accessCode" placeholder="Access Code">
              <div class="invalid-feedback">Access Code must be exactly 6 digits.</div>
            </div>

          </div>
        </div>


        <!-- Step 5 -->
        <div class="step" id="step-5">
          <h4 class="mb-3"><strong>Security Credentials</strong></h4>
          <p class="text-muted">Create a secure password to access your storage account online.</p>
          <div class="row g-3">
            <div class="col-12">
            <label for="webPassword" class="form-label">Web Password</label>
            <div class="position-relative">
                <input type="password" class="form-control" id="webPassword" placeholder="Web Password">
                <button type="button" class="btn btn-sm btn-custom-primary-2 position-absolute top-50 end-0 translate-middle-y me-2 toggle-password" data-target="#webPassword" tabindex="-1" aria-label="Toggle password visibility">
                <i class="fas fa-eye"></i>
                </button>
            </div>
            <div class="invalid-feedback">Web password is required.</div>
            </div>

          </div>
        </div>


         <!-- Step 6 -->
        <div class="step" id="step-6">
          <h4 class="mb-3"><strong>Terms & Conditions</strong></h4>
          <p class="text-muted">Confirm how you are protecting your goods.</p>
          <div class="row g-3">
            <div class="col-12">


                                            You must select one of the following:</p>

                                            <div class="mb-3 mt-3 form-check">
                                                <input type="checkbox" class="form-check-input terms-and-conditions" id="storeProtect">
                                                <div class="d-inline-block protect-text margin-top-left-default" for="storeProtect">
                                                    <span class="show-more-for-store-protect" style="cursor:pointer;">
                                                        <strong>I/We wish to accept extended liability protection</strong>
                                                        under StoreProtect </span>
                                                    <span class="full-description-store-protect" style="display:none;"> and confirm I/We: have read and understood the enclosed StoreProtect Addendum; understand that the Proportional Reduction shall apply if the Maximum Replacement Value provided is inadequate at any time during the Storage Period; and understand a $100 Claims Administration Fee will be deducted from claim payments.
                                                        <br /><br />I/We also confirm I do not hold any insurance with another party for this storage shed.
                                                        <br /><br />The agreed Maximum Replacement Value of your goods is and this is an accurate value of the contents you will be storing. You have read and understand the Proportional Reduction clause.
                                                        <br /><br />Maximum Replacement Value ($100,000 maximum):
                                                        <div class="col-12 mb-3 mt-3" id="maximum_replacement_value_section" style="display:none;">


                                                            <input class="form-control" type="hidden" placeholder="Maximum Replacement Value" value="0" name="maximum_replacement_value2" id="maximum_replacement_value2" readonly required>

                                                            <select class="form-select" id="maximum_replacement_value" name="maximum_replacement_value" required>
                                                                <option value="">Select Insurance Coverage</option>
                                                            </select>


                                                            <div class="mb-3 mt-3 form-check">
                                                                <input type="checkbox" class="form-check-input terms-and-conditions_for_license" id="licenseInsurance">
                                                                <label class="form-check-label" for="licenseInsurance">
                                                                    By ticking this box you have read and understand the terms <b><a target="_blank" style="display: inline;" href="https://mammothselfstore.com.au/wp-content/uploads/2024/09/Lease-Agreement.pdf">Licence terms</a></b> and <b><a target="_blank" style="display: inline;" href="https://mammothselfstore.com.au/wp-content/uploads/2024/09/StoreProtect-Addendum.pdf">StoreProtect Addendum</a></b> and you agree to them.
                                                                </label>
                                                            </div>
                                                        </div>
                                                    </span>

                                                </div>
                                            </div>




                                            <div class="mb-3 form-check">
                                                <input type="checkbox" class="form-check-input terms-and-conditions" id="ownInsurance">
                                                <div class="d-inline-block protect-text2 margin-top-left-default2" for="ownInsurance">
                                                    <span class="show-more-for-own-insurance" style="cursor:pointer;">
                                                        <strong>I/We have adequate insurance</strong>
                                                        for the Goods that we are storing
                                                    </span>
                                                    <span class="full-description-own-insurance" style="display:none;">and will provide details
                                                        of this coverage if required.
                                                    </span>
                                                </div>
                                            </div>

                                            <div class="mb-3 form-check">
                                                <input type="checkbox" class="form-check-input terms-and-conditions" id="noInsurance">
                                                <div class="d-inline-block protect-text3 margin-top-left-default3" for="noInsurance">
                                                    <span class="show-more-for-no-insurance" style="cursor:pointer;">
                                                        <strong>I/We don't have insurance</strong>
                                                        and understand that if the Goods are lost or damaged I/We: may have to bear that loss.
                                                    </span>
                                                    <span class="full-description-no-insurance" style="display:none;"> Further, that in the event that my/our Goods cause any damage or loss then I/We may be liable
                                                        for such damage or loss. <br /> <b><a target="_blank" style="display: inline;" href="https://mammothselfstore.com.au/wp-content/uploads/2024/09/Insurance-Waiver.pdf">Insurance Opt Out Notice</a></b>
                                                    </span>
                                                </div>
                                            </div>


                <div class="invalid-feedback">Terms & Conditions are required.</div>
            </div>

          </div>
        </div>


                 <!-- Step 7 -->
        <div class="step" id="step-7">
          <h4 class="mb-3"><strong>Storage Terms & Conditions</strong></h4>
          <p class="text-muted">Confirm that you understand our service policies.</p>
          <div class="row g-3">
            <div class="col-12">


<p>Storage Period: From: <b><span id="moveInDateText"></span></b> To: <b><span id="moveInDateOneMonthLaterText"></span></b> initially, and then extended automatically until the expiry of 14 days from the date that either the FO or the Storer gives written notice to the other party of its intention to terminate this Agreement (Termination Notice Period).</p>

                                            <ul>
                                                <li>I/We acknowledge that I/We have been provided with reasonable access to the full terms of this Agreement.</li>
                                                <li>I/We accept that our nominated credit card will be charged on our due date for the following period.</li>
                                                <li>I/We represent that I/We have read or had the opportunity to read the full terms of this Agreement.</li>
                                                <li>I/We agree to be bound by all of the terms of this Agreement.</li>
                                                <li>I/We understand that we must provide 14 days' notice prior to vacating the facility.</li>
                                                <li>I/We understand that my/our goods may be sold if abandoned.</li>
                                            </ul>


                                            <div class="mb-3 form-check">
                                                <input type="checkbox" class="form-check-input storage-terms-and-conditions" id="storageTermsAndConditions">
                                                <label class="form-check-label" for="exampleCheck1" style="font-size: 1.25rem;">I have read and understood the <b><a target="_blank" style="display: inline;" href="https://mammothselfstore.com.au/wp-content/uploads/2024/09/Lease-Agreement.pdf">Terms and Conditions</a></b> that will form the agreement between myself and Mammoth Storage.</label>
                                            </div>

                <div class="invalid-feedback">Storage Terms & Conditions are required.</div>
            </div>

          </div>
        </div>


        <!-- Step 8 -->
        <div class="step" id="step-8">
          <h4 class="mb-3"><strong>Payment Information</strong></h4>
          <p class="text-muted">Enter your billing and payment details to complete the reservation.</p>
          <div class="row g-3">

            <div class="col-md-6">
              <label for="billingName" class="form-label">Billing Name</label>
             <input type="text" class="form-control" id="billingName" placeholder="Billing Name">
              <div class="invalid-feedback">Billing name is required.</div>
            </div>

            <div class="col-md-6">
            <label for="billingZip" class="form-label">Billing Zip Code</label>
              <input type="text" class="form-control" id="billingZip" placeholder="Billing Zip Code">
              <div class="invalid-feedback">Billing zip code is required.</div>
            </div>

            <div class="col-md-12">
             <label for="billingAddress" class="form-label">Billing Address</label>
              <input type="text" class="form-control" id="billingAddress" placeholder="Billing Address">
              <div class="invalid-feedback">Billing address is required.</div>
            </div>

            <div class="col-md-3">
            <label for="cardType" class="form-label">Card Type</label>
              <select class="form-select" id="cardType">
                <option value="">Select Card Type</option>
                <option value="5">Master Card</option>
                <option value="6">Visa</option>
              </select>
              <div class="invalid-feedback">Card type is required.</div>
            </div>

            <div class="col-md-9">
              <label for="cardNumber" class="form-label">Card Number</label>
              <input type="text" class="form-control" id="cardNumber" placeholder="Card Number">
              <div class="invalid-feedback">Card number is required.</div>
            </div>

            <div class="col-md-4">
              <label for="expYear" class="form-label">Expiration Year</label>
              <select class="form-select" id="expYear">
                <option value="">Expiration Year</option>
                <!-- JavaScript will populate this -->
              </select>
              <div class="invalid-feedback">Expiration year is required.</div>
            </div>

            <div class="col-md-4">
             <label for="expMonth" class="form-label">Expiration Month</label>
              <select class="form-select" id="expMonth">
                <option value="">Expiration Month</option>
              </select>
              <div class="invalid-feedback">Expiration month is required.</div>
            </div>

            <div class="col-md-4">
            <label for="cvv" class="form-label">CVV</label>
              <input type="text" class="form-control" id="cvv" placeholder="CVV" maxlength="3">
              <div class="invalid-feedback">CVV must be 3 digits.</div>
            </div>

            <p class="mt-3">By signing up online, you agree to a recurring monthly payment that will be taken in advance as part of your storage agreement.</p>
                                            <p class="mt-1">To change your payment method to direct debit, contact <b>Mammoth Storage</b>.</p>

                                            <p class="h5 terms-extra__title mt-1"><span><b>Agreements for your Recurring Payment</b></span></p>

                                            <p class="mt-1"><i class="icon-ok"></i> <span>I acknowledge and agree to the <b><a target="_blank" style="display: inline;" href="https://mammothselfstore.com.au/cardpay-termsandconditions/">CardPay Terms and Conditions</a></b> and for Mammoth Storage to arrange for a recurring payment to be established using the card nominated above to pay for fees under my Storage Agreement. </span></p>

                                            <p class="mt-1"><i class="icon-ok"></i> <span>We will debit the amount owing under your storage agreement from the credit or debit card you’ve nominated above.</span></p>

                                            <p class="mt-1"><i class="icon-ok"></i> <span>Your payment above will be taken today, and your recurring payments due under your storage agreement will be setup to take Storage Fee payments monthly in advance.</span></p>

                                            <div class="mb-1 mt-1 form-check">
                                                <input type="checkbox" class="form-check-input autoplay-terms-and-conditions" id="autoTermsAndConditions">
                                                <label class="form-check-label" for="exampleCheck1" style="font-size: 1.25rem;">I agree to the CardPay Terms and Conditions and consent to receiving all notices from Mammoth Storage by way of email, post and SMS.</label>
                                            </div>


          </div>
        </div>


       <!-- Step 9 -->
<div class="step" id="step-9">
  <h4 class="mb-3"><strong>Final Checkout Summary</strong></h4>
  <p class="text-muted">Review your selected storage unit before submitting your application.</p>

  <div class="card shadow-sm border-0 rounded-4 p-4 bg-light">
    <div class="mb-4">
      <h5 class="text-custom-primary mb-3"><i class="bi bi-box-seam me-2"></i>Unit Information</h5>
      <div class="row g-3">
        <div class="col-md-6">
          <div class="border rounded p-3 bg-white">
            <p class="mb-1 text-muted">Unit ID</p>
            <h6 class="mb-0 fw-bold">${randomUnit.UnitID}</h6>
          </div>
        </div>
        <div class="col-md-6">
          <div class="border rounded p-3 bg-white">
            <p class="mb-1 text-muted">Unit Name</p>
            <h6 class="mb-0 fw-bold">${randomUnit.sUnitName}</h6>
          </div>
        </div>
        <div class="col-md-6">
          <div class="border rounded p-3 bg-white">
            <p class="mb-1 text-muted">Storage Type</p>
            <h6 class="mb-0 fw-bold">${randomUnit.sTypeName}</h6>
          </div>
        </div>
        <div class="col-md-6">
          <div class="border rounded p-3 bg-white">
            <p class="mb-1 text-muted">Dimensions</p>
            <h6 class="mb-0 fw-bold">${randomUnit.dcWidth}m x ${randomUnit.dcLength}m</h6>
          </div>
        </div>
        <div class="col-md-6">
          <div class="border rounded p-3 bg-white">
            <p class="mb-1 text-muted">Floor</p>
            <h6 class="mb-0 fw-bold">${randomUnit.sTypeName || '-'}</h6>
          </div>
        </div>
        <div class="col-md-6">
          <div class="border rounded p-3 bg-white">
            <p class="mb-1 text-muted">Features</p>
            <div class="d-flex flex-wrap gap-2">
              ${randomUnit.bInside === "true" ? '<span class="badge bg-secondary">Inside</span>' : '<span class="badge bg-warning text-dark">Outside</span>'}
              ${randomUnit.bClimate === "true" ? '<span class="badge bg-info text-dark">Climate Controlled</span>' : ''}
              ${randomUnit.bAlarm === "true" ? '<span class="badge bg-danger">Alarm</span>' : ''}
            </div>
          </div>
        </div>
      </div>
    </div>

    <div>
      <h5 class="text-custom-primary mb-3"><i class="bi bi-currency-dollar me-2"></i>Payment Summary</h5>
      <div class="d-flex justify-content-between align-items-center border-top pt-3">
        <span class="text-muted fw-semibold">Monthly Rate</span>
        <span class="fs-4 fw-bold text-custom-primary">$${randomUnit.dcStdRate} AUD</span>
      </div>
    </div>
  </div>

  <!-- Hidden Inputs -->
  <input type="hidden" id="locationCode" value="${locationCode}">
   <input type="hidden" id="locationName" value="${locationName}">
  <input type="hidden" id="unitId" value="${randomUnit.UnitID}">
  <input type="hidden" id="unitName" value="${randomUnit.sUnitName}">
  <input type="hidden" id="building" value="${randomUnit.sTypeName}">
  <input type="hidden" id="unitSize" value="${randomUnit.dcWidth}m x ${randomUnit.dcLength}m">
  <input type="hidden" id="paymentAmount" value="${randomUnit.dcStdRate}">

</div>



        <!-- Navigation Buttons -->
        <div class="d-flex justify-content-end mt-4 nav-btns">

        </div>
      </form>
      <div id="thankYouMessage" class="text-center mt-5 d-none">
        <div class="card shadow-sm border-0 rounded-4 p-5 mx-auto" style="max-width: 500px;">
            <div class="mb-4">
            <img src="https://mammothselfstore.com.au/wp-content/uploads/2021/08/logo.svg" class="img-fluid" style="max-width: 80px;" alt="Confirmation Envelope">
            </div>
            <h2 class="fw-bold text-custom-primary mb-3">Thank You!</h2>
            <p class="text-muted mb-4">
            Your application has been successfully submitted. We’ll reach out to you shortly with next steps.
            </p>
            <a href="${base_url}" class="btn btn-custom-outline-primary">
            <i class="bi bi-arrow-left-circle me-2"></i>Back to Site
            </a>
        </div>
        </div>

    </div>
  </div>

</div>

        `;

        // Remove old forms first, then append the new one
        removeOldForms().then(() => {

            // Append form inside the parent .size-group
            parentContainer.append(formHtml);

            initMultiStepForm();

            // Populate Expiration Year & Month Dropdowns After Appending
            function initializeExpirationDropdowns() {
                const currentYear = new Date().getFullYear();
                const currentMonth = new Date().getMonth() + 1; // Months are zero-based in JavaScript

                // Populate Year Dropdown
                $("#card-year").empty();
                for (let i = 0; i <= 10; i++) {
                    const year = currentYear + i;
                    $("#card-year").append(new Option(year, year));
                }

                // Function to populate months based on selected year
                function populateMonths(selectedYear) {
                    $("#card-month").empty(); // Clear previous months
                    const startMonth = selectedYear == currentYear ? currentMonth : 1; // If current year, start from current month

                    for (let i = startMonth; i <= 12; i++) {
                        const monthValue = i.toString().padStart(2, "0"); // Converts 1 to '01', 2 to '02', etc.
                        const monthName = new Date(2023, i - 1, 1).toLocaleString('default', { month: 'long' });
                        $("#card-month").append(new Option(`${monthValue}`, monthValue));
                    }
                }

                // Initialize Months Based on Current Year
                populateMonths(currentYear);

                // Update Month Dropdown When Year Changes
                $("#card-year").on("change", function () {
                    const selectedYear = $(this).val();
                    populateMonths(selectedYear);
                });
            }

            // Initialize dropdowns after form is appended
            initializeExpirationDropdowns();


            // Show form with animation
            parentContainer.find(".unit-form-container").slideDown(300);
        });

    } else {
        console.error("No units found for the selected size and group.");
        Swal.fire({
            icon: "error",
            title: "Error",
            text: "No units found for the selected size and group.",
        });
    }
});


function storeUnitData(locationCode, groupName, units) {
    if (!unitDataStore[locationCode]) {
        unitDataStore[locationCode] = {};
    }
    if (!unitDataStore[locationCode][groupName]) {
        unitDataStore[locationCode][groupName] = {};
    }
    units.forEach(unit => {
        const width = unit.dcWidth.toFixed(1);
        if (!unitDataStore[locationCode][groupName][width]) {
            unitDataStore[locationCode][groupName][width] = [];
        }
        unitDataStore[locationCode][groupName][width].push(unit);
    });
}

function storeSizeImages(locationCode, images) {
    unitImagesStore[locationCode] = images;
}


function groupUnitsBySize(units) {
    const grouped = { Small: [], Medium: [], Large: [], XLarge: [] };

    units.forEach(unit => {
        const width = unit.dcWidth;
        if (width >= 1 && width < 2) grouped.Small.push(unit);
        else if (width >= 2 && width < 3) grouped.Medium.push(unit);
        else if (width >= 3 && width < 4) grouped.Large.push(unit);
        else if (width >= 4) grouped.XLarge.push(unit);
    });

    return grouped;
}

function calculateDistance(lat1, lng1, lat2, lng2) {
    const toRad = deg => deg * Math.PI / 180;
    const R = 6371; // km
    const dLat = toRad(lat2 - lat1);
    const dLng = toRad(lng2 - lng1);
    const a = Math.sin(dLat / 2) ** 2 +
        Math.cos(toRad(lat1)) * Math.cos(toRad(lat2)) *
        Math.sin(dLng / 2) ** 2;
    return R * (2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a)));
}

function reorderRenderedLocations(locations) {
    const userCoords = getUserCoordinatesFromURL();
    if (!userCoords) return;

    const container = $("#locations-container");

    const distanceMap = locations.map(code => {
        const loc = locationCoordinates[code];
        if (!loc) return null;

        const distance = calculateDistance(userCoords.lat, userCoords.lng, loc.lat, loc.lng);

        // Find matching rendered element by code
        const matchEl = container.children(".location").filter(function () {
            return $(this).html().includes(code);
        })[0];

        return matchEl ? { element: $(matchEl), distance, code } : null;
    }).filter(Boolean);

    // Sort locations by proximity
    distanceMap.sort((a, b) => a.distance - b.distance);

    container.empty();

    distanceMap.forEach(({ element, distance }) => {
        const formattedDistance = distance.toLocaleString(undefined, {
            minimumFractionDigits: 1,
            maximumFractionDigits: 1
        });

        // Remove any previous distance buttons to avoid duplicates
        element.find(".distance-button").remove();

        // Create distance button styled like "View Centre" with location icon
        const distanceBtn = $(`
    <a href="#" class="btn btn-light text-custom-primary btn-sm d-flex align-items-center distance-button" tabindex="-1">
       <strong> <i class="bi bi-geo-alt-fill me-2"></i> ${formattedDistance} km away </strong>
    </a>
`);


        // Ensure the right-side column is flex-row
        const rightCol = element.find(".row.align-items-center .col-12.col-sm-auto");
        rightCol.addClass("d-flex flex-wrap gap-2 justify-content-end align-items-center");

        // Insert the distance button
        rightCol.find(".distance-km-text").empty().append(distanceBtn);

        // Optional: clean up heading if it has leftover (km) text
        const heading = element.find("h4");
        heading.html(heading.text().replace(/\s*\(.*?km.*?\)/, '').trim());

        // Append reordered element
        container.append(element);
    });
}

function renderInsuranceOptions(locationCode, insuranceData) {


    if (!insuranceData || !insuranceData.data) {
        select.html('<option value="">No Insurance Options Available</option>');
        return;
    }

    let options = '<option value="">Select Insurance Coverage 1</option>';
    insuranceData.data.forEach(item => {
        options += `
            <option value="${item.dcCoverage}"
                    data-premium="${item.dcPremium}"
                    data-coverage-id="${item.InsurCoverageID}">
                ${item.sCoverageDesc} - $${parseFloat(item.dcPremium).toFixed(2)}/mo
            </option>
        `;
    });

    select.html(options);
}




async function processLocationsSequentially(locations) {
    for (const locationCode of locations) {
        $("#spinner-overlay").show();
        try {
            const response = await fetchLocationData(locationCode);
            if (!response.error) {
                const groupedData = groupUnitsBySize(response.data);
                Object.keys(groupedData).forEach(group => {
                    storeUnitData(locationCode, group, groupedData[group]);
                });

                // ✅ Store size images globally
                if (response.size_images) {
                    storeSizeImages(locationCode, response.size_images);
                }

                if (response.specials) {
                    storeSpecials(locationCode, response.specials);
                }

                renderLocation(response.location, locationCode);
            }
        } catch (error) {
            console.error(`Error processing location ${locationCode}:`, error);
        } finally {
            $("#spinner-overlay").hide();
        }
    }

    // ✅ After loop completes, reorder the rendered blocks
    reorderRenderedLocations(locations);
}


$(document).ready(() => {

    if (locations.length > 0) {
        processLocationsSequentially(locations);

        $(document).on("click", ".view-all-sizes", function (event) {
            event.preventDefault(); // Prevent default anchor behavior
            const locationCode = $(this).data("location-code");
            const groupedData = unitDataStore[locationCode];
            const allSizesHtml = renderAllSizes(groupedData, locationCode);
            // Get the location name from the data-location attribute
            const locationName = $(this).data('location');
            $("#all-sizes-content").html(allSizesHtml);

            // Update the modal title
            $("#allSizesModalLabel").html(`Storage Sizes for Location ${locationName}`);

            $("#allSizesModal").modal("show");
        });

        // Listen for collapse show and hide events
        $(document).on('show.bs.collapse', '.collapse', function () {
            // Update the icon for the currently expanding section
            $(this).prev('.card-header').find('.toggle-icon').removeClass('bi-chevron-down').addClass('bi-chevron-up');
        });

        $(document).on('hide.bs.collapse', '.collapse', function () {
            // Update the icon for the currently collapsing section
            $(this).prev('.card-header').find('.toggle-icon').removeClass('bi-chevron-up').addClass('bi-chevron-down');
        });

    }

});

/*$('#allSizesModal').on('hidden.bs.modal', function () {
    // Reset blur when modal closes
    $(".sizes-section").css({
        "filter": "none",
        "pointer-events": "auto"
    });
});*/


// Handle cancel button (Close the form)
$(document).on("click", ".close-unit-form", function () {
    $(this).closest(".unit-form-container").slideUp(300, function () {
        $(this).remove();
    });
});

function showFormError(message) {
    $('#form-error-alert .form-error-message').text(message);
    $('#form-error-alert').removeClass('d-none');
}


async function sendFinalStepData() {
    try {

        // Show loader at the beginning
        $("#spinner-overlay").show();

        const tenantSearchFormData = {
            customer_email: $("#email").val(),
            location_code: $("#locationCode").val()
        };

        const tenant = await searchTenant(tenantSearchFormData);

        if (tenant) {

            const gateCode = getTenantAccessCode(tenant);
            $("#accessCode").val(gateCode);

            const moveInData = collectMoveInFormData(tenant);
            await processTenantMoveIn(moveInData);
        } else {
            const newTenantData = collectTenantCreateFormData();
            const createdTenant = await createTenant(newTenantData);

            if (createdTenant) {
                const gateCode = getTenantAccessCode(createdTenant);
                $("#accessCode").val(gateCode);
            }

            const moveInData = collectMoveInFormData(createdTenant);
            await processTenantMoveIn(moveInData);
        }
    } catch (err) {
        showFormError(err || "Something went wrong during the reservation process.");
    } finally {
        // Always hide the loader
        $("#spinner-overlay").hide();
    }
}

async function searchTenant(formData) {
    try {
        const response = await $.ajax({
            url: `https://checkout.mammothstorage.com.au/api/v1/tenant/search`,
            type: "POST",
            contentType: "application/json",
            data: JSON.stringify(formData)
        });

        return (response.success && response.data && response.data.tenant) ? response.data.tenant : null;
    } catch (error) {
        showFormError("Unable to search for tenant.");
        return null;
    }
}


async function createTenant(formData) {
    try {
        const response = await $.ajax({
            url: `https://checkout.mammothstorage.com.au/api/v1/tenant/create`,
            type: "POST",
            contentType: "application/json",
            data: JSON.stringify(formData)
        });

        if (response.success && response.data && response.data.tenant) {
            return response.data.tenant;
        } else {
            showFormError(response.message || "Tenant creation failed.");
            return null;
        }
    } catch (error) {
        showFormError("Failed to create tenant.");
        return null;
    }
}

function getTenantGateCode(tenant) {
    return tenant && tenant.sGateCode ? tenant.sGateCode : null;
}

function getTenantAccessCode(tenant) {
    return tenant && tenant.sAccessCode ? tenant.sAccessCode : null;
}


async function processTenantMoveIn(formData) {
    try {
        const response = await $.ajax({
            url: `https://checkout.mammothstorage.com.au/api/v1/tenant/move-in`,
            type: "POST",
            contentType: "application/json",
            data: JSON.stringify(formData)
        });

        if (
            response.success &&
            response.data?.move_in?.TenantID &&
            response.data?.move_in?.LedgerID &&
            response.data?.location_code
        ) {
            const { TenantID, UnitID, LedgerID } = response.data.move_in;
            const { location_code } = response.data;
            const sFName = formData.tenant.sFName;

            await sendWelcomeEmailWebhook();

            return response.data;
        } else {
            showFormError(response.message || "Move-in process failed.");
            return null;
        }
    } catch (error) {
        showFormError("Unable to process move-in.");
        return null;
    }
}

/**
 * Function to send move-in data to webhook
 */
async function sendWelcomeEmailWebhook() {
    try {


        const tenantWelcomeEmailFormData = collectTenantForWelcomeEmailFormData();

        const response = await $.ajax({
            url: "https://checkout.mammothstorage.com.au/api/v1/customers/welcome-email",
            type: "POST",
            contentType: "application/json",
            /*headers: {
                "API-Key": "GYaEPXnhOT0BZbzOZFPR3Dj5sXAJkm10Ak8eNT4BZskOFNGnNA6D6lzIhlFgxRlr2KrGSnau9WchyZFV6RRfoufMkrbK8hXKRg6oDubTQwNH2x2RCWw2Oigfzvbkdatm" // Replace with the actual key
            },*/
            data: JSON.stringify(tenantWelcomeEmailFormData)
        });

        if (response.success) {

            $('#multiStepForm').hide();
            $("#thankYouMessage").removeClass("d-none");

            showSuccess(`Thank you, ${sFName}! Your reservation for Unit ID ${unitId} has been successfully submitted.`);
            $(".unit-form-container").slideUp(300, function () {
                $(this).remove();
            });

            // Delay redirect by 2 seconds (2000 milliseconds)
            setTimeout(function () {
                window.location.href = "https://mammothselfstore.com.au/thank-you-booking/";
            }, 2000);

        } else {
            showFormError(response.message || "Create order process failed.");
            return null;
        }

    } catch (error) {
        showFormError("Error during final webhook step.");
        return null;
    }
}



/**
 * Function to send move-in data to webhook
 */
async function sendMoveInWebhook(sFName, tenantId, unitId, ledgerId, locationCode) {
    try {
        const payload = {
            tenantId,
            ledgerId,
            locationCode,
            scheduleType: "api"
        };

        const response = await $.ajax({
            url: "https://checkout.mammothstorage.com.au/tenant",
            type: "POST",
            contentType: "application/json",
            data: JSON.stringify(payload)
        });

        if (response.success) {

            $('#multiStepForm').hide();
            $("#thankYouMessage").removeClass("d-none");

            showSuccess(`Thank you, ${sFName}! Your reservation for Unit ID ${unitId} has been successfully submitted.`);
            $(".unit-form-container").slideUp(300, function () {
                $(this).remove();
            });
        } else {
            showFormError(response.message || "Create order process failed.");
            return null;
        }

    } catch (error) {
        showFormError("Error during final webhook step.");
        return null;
    }
}





/**
 * Function to collect Move-In form data
 */
function collectMoveInFormData(tenantData) {
    const $selectedInsurance = $('#maximum_replacement_value').find(':selected');
    const $selectedOption = $('.terms-and-conditions:checked');
    const selectedId = $selectedOption.attr("id") || "";


    // Default insurance details (all 0 if not storeProtect)
    let insurance = {
        option: selectedId,
        coverage: "0",
        coverage_id: "0",
        premium: "0"
    };

    // If storeProtect is selected, use dropdown values
    if (selectedId === "storeProtect") {
        insurance.coverage = $selectedInsurance.val() || "0";
        insurance.coverage_id = $selectedInsurance.data("coverage-id") || "0";
        insurance.premium = $selectedInsurance.data("premium") || "0";
    }

    // Normalize payment amount for the checkout API
    const rawPayment = $("#paymentAmount").val() || "0";
    const paymentNumeric = parseFloat(rawPayment) || 0;
    const fullPaymentAmount = paymentNumeric.toFixed(2);

    return {
        tenant: tenantData, // Pass full tenant data

        unit: {
            unit_id: $("#unitId").val(),
        },

        billing: {
            billing_name: capitalizeFirstLetter($("#billingName").val()),
            billing_address: capitalizeFirstLetter($("#billingAddress").val()),
            billing_zipcode: $("#billingZip").val(),
            // Base payment amount (monthly rent)
            payment_amount: fullPaymentAmount,
            // Full payment amount required by checkout API
            full_payment_amount: fullPaymentAmount
        },

        move: {
            move_in_start_date: $("#startDate").val(),
            move_in_end_date: $("#endDate").val()
        },

        card: {
            type: $("#cardType").val(),
            number: $("#cardNumber").val(),
            cvv: $("#cvv").val(),
            year: $("#expYear").val(),
            month: $("#expMonth").val()
        },

        insurance: insurance
    };
}


/**
 * Function to collect Tenant Creation form data
 */
function collectTenantCreateFormData() {
    return {
        billing_name: capitalizeFirstLetter($("#billingName").val()),
        billing_address: capitalizeFirstLetter($("#billingAddress").val()),
        billing_zipcode: $("#billingZip").val(),
        customer_firstname: capitalizeFirstLetter($("#firstName").val()),
        customer_lastname: capitalizeFirstLetter($("#lastName").val()),
        customer_phone: $("#phone").val(),
        customer_country: $("#country").val(),
        customer_country_code: $("#countryCode").val(),
        customer_city: $("#city").val(),
        customer_state: $("#state").val(),
        customer_dob: $("#dob").val(),
        customer_email: $("#email").val(),
        customer_license: $("#licenseNumber").val(),
        customer_license_state: $("#licenseState").val(),
        customer_web_password: $("#webPassword").val(),
        access_code: $("#accessCode").val(),
        alt_customer_firstname: $("#altFirstName").val(),
        alt_customer_lastname: $("#altLastName").val(),
        alt_customer_phone: $("#altPhone").val(),
        alt_customer_email: "",
        location_code: $("#locationCode").val()
    };
}


function collectTenantIncompleteFormData() {
    return {
        customer_firstname: capitalizeFirstLetter($("#firstName").val()),
        customer_lastname: capitalizeFirstLetter($("#lastName").val()),
        customer_country: capitalizeFirstLetter($("#country").val()),
        customer_country_code: $("#countryCode").val(),
        customer_email: $("#email").val(),
        customer_phone: $("#phone").val(),
        location_code: $("#locationCode").val(),
        unit_id: $("#unitId").val(),
        unit_name: $("#unitName").val(),
        payment_amount: $("#paymentAmount").val(),
    };
}



function collectTenantForWelcomeEmailFormData() {

    const $selectedInsurance = $('#maximum_replacement_value').find(':selected');
    const $selectedOption = $('.terms-and-conditions:checked');
    const selectedId = $selectedOption.attr("id") || "";


    // Default insurance details (all 0 if not storeProtect)
    let insurance = {
        option: selectedId,
        coverage: "0",
        coverage_id: "0",
        premium: "0"
    };

    // If storeProtect is selected, use dropdown values
    if (selectedId === "storeProtect") {
        insurance.coverage = $selectedInsurance.val() || "0";
        insurance.coverage_id = $selectedInsurance.data("coverage-id") || "0";
        insurance.premium = $selectedInsurance.data("premium") || "0";
    }


    return {
        customer_fname: capitalizeFirstLetter($("#firstName").val()),
        customer_lname: capitalizeFirstLetter($("#lastName").val()),
        customer_name: capitalizeFirstLetter($("#firstName").val()) + " " + capitalizeFirstLetter($("#lastName").val()),
        customer_email: $("#email").val(),
        location_name: $("#locationName").val(),
        billing_anniversary: $("#startDate").val(),
        unit_number: $("#unitName").val(),
        building: $("#building").val(),
        access_code: $("#accessCode").val(),
        padlock_code: 1243,
        start_date: $("#startDate").val(),
        unit_size: $("#unitSize").val(),
        price: $("#paymentAmount").val(),
        location_code: $("#locationCode").val(),
        insurance: insurance
    };
}

function capitalizeFirstLetter(str) {
    if (!str) return "";
    return str.charAt(0).toUpperCase() + str.slice(1).toLowerCase();
}


/**
 * Function to show success message
 */
function showSuccess(message) {
    Swal.fire({
        icon: "success",
        title: "Success",
        text: message
    });
}

/**
 * Function to show error message
 */
function showError(message) {
    Swal.fire({
        icon: "error",
        title: "Error",
        text: message
    });
}
