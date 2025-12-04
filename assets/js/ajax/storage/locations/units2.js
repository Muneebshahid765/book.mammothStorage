const locations = ["L001", "L002"];
const unitDataStore = {};

function fetchLocationData(locationCode) {
    return $.ajax({
        url: `${base_url}/units/fetch`,
        type: "POST",
        data: JSON.stringify({ locationCode: locationCode }),
        dataType: "json",
        contentType: 'application/json',
    });
}

function renderSmallestSize(groupName) {
    if (!unitDataStore[groupName]) return "";

    const firstSizeKey = Object.keys(unitDataStore[groupName]).sort((a, b) => parseFloat(a) - parseFloat(b))[0];
    const smallestUnits = unitDataStore[groupName][firstSizeKey];
    if (!smallestUnits || smallestUnits.length === 0) return "";

    const smallestUnit = smallestUnits[0];

    return `
    <div class="size-group mb-3">
        <div class="d-flex flex-column bg-light p-3 shadow-sm rounded">
            <!-- First Row for Group Name and Dimensions -->
            <div class="flex-grow-1">
                <h6 class="fw-bold text-secondary mb-1">${groupName}</h6>
                <span><i class="bi bi-box me-1"></i> ${smallestUnit.dcWidth} × ${smallestUnit.dcLength}m
                <i class="bi bi-grid me-1"></i> (${(smallestUnit.dcWidth * smallestUnit.dcLength).toFixed(2)} m²)</span>
            </div>
            <!-- Second Row for Pricing and Button -->
            <div class="d-flex justify-content-between align-items-center mt-3 w-100">
                <span class="badge p-2 text-secondary fs-6 fw-bold">
                    $${parseFloat(smallestUnit.dcStdRate).toFixed(2)}/mo
                </span>
                <button class="btn btn-custom-primary btn-sm show-all-units d-flex align-items-center"
                        data-key="${groupName}_${smallestUnit.dcWidth.toFixed(1)}x${smallestUnit.dcLength.toFixed(1)}">
                    <i class="bi bi-arrow-right-circle me-1"></i> Continue
                </button>
            </div>
        </div>
    </div>
    `;

}

function renderAllSizes(groupedData) {
    let content = "";
    let index = 0; // To track the first card for default active

    Object.keys(groupedData).forEach(group => {
        const isActive = index === 0 ? "show" : ""; // First card should be active
        const isExpanded = index === 0 ? "true" : "false"; // First card expanded
        const iconClass = index === 0 ? "bi bi-chevron-up" : "bi bi-chevron-down"; // Icon for toggle

        content += `<div class="card mb-4">
                        <div class="card-header bg-custom-primary text-white d-flex justify-content-between align-items-center">
                            <h5 class="fw-bold mb-0 text-white d-flex align-items-center text-start">
                                <i class="bi bi-box-seam fs-3 me-3"></i> <!-- Larger icon before the group -->
                                ${group} Storage Size
                            </h5>
                            <button class="btn btn-link text-white text-decoration-none fw-bold d-flex align-items-center"
                                    data-bs-toggle="collapse"
                                    data-bs-target="#collapse_${index}"
                                    aria-expanded="${isExpanded}"
                                    aria-controls="collapse_${index}">
                                <i class="${iconClass} fs-5 toggle-icon"></i> <!-- Collapse/Expand icon -->
                            </button>
                        </div>
                        <div id="collapse_${index}" class="collapse ${isActive}" data-bs-parent="#accordion">
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
                content += `
                <div class="size-group mb-3">
                    <div class="d-flex flex-column bg-light p-3 shadow-sm rounded">
                        <!-- First Row: Group Name and Dimensions -->
                        <div class="flex-grow-1">
                            <h6 class="fw-bold text-secondary mb-1">${group} - (${unitsForGroup.length} ${unitsForGroup.length === 1 ? 'unit' : 'units'})</h6>
                            <span>
                                <i class="bi bi-box me-1"></i>
                                ${exampleUnit.dcWidth}m × ${exampleUnit.dcLength}m
                                <i class="bi bi-grid me-1"></i>
                                (${(exampleUnit.dcWidth * exampleUnit.dcLength).toFixed(2)}m²)
                            </span>
                        </div>
                        <!-- Second Row: Price and Button -->
                        <div class="d-flex justify-content-between align-items-center mt-3 w-100">
                            <span class="badge p-2 fs-6 text-secondary fw-bold">
                                $${parseFloat(exampleUnit.dcStdRate).toFixed(2)}/mo
                            </span>
                            <button class="btn btn-custom-primary btn-sm d-flex align-items-center show-all-units" data-key="${group}_${key}">
                                <i class="bi bi-arrow-right-circle me-1"></i> Continue
                            </button>
                        </div>
                    </div>
                </div>
            `;

            });
        });

        content += `</div>
                    </div>
                </div>`;
        index++; // Increment index for the next group
    });

    return `<div id="accordion">${content}</div>`; // Wrap in an accordion
}


$(document).ready(function () {
    // Listen for collapse show and hide events
    $(document).on('show.bs.collapse', '.collapse', function () {
        // Update the icon for the currently expanding section
        $(this).prev('.card-header').find('.toggle-icon').removeClass('bi-chevron-down').addClass('bi-chevron-up');
    });

    $(document).on('hide.bs.collapse', '.collapse', function () {
        // Update the icon for the currently collapsing section
        $(this).prev('.card-header').find('.toggle-icon').removeClass('bi-chevron-up').addClass('bi-chevron-down');
    });
});


function renderLocation(location) {
    const groupedData = unitDataStore;
    const container = $("#locations-container");
    const locationHTML = `
        <div class="location mb-4 rounded bg-white">

           <div class="bg-custom-primary text-white p-4 rounded shadow-sm mb-4">
    <div class="row align-items-center">
    <!-- Location Name and Icon -->
    <div class="col-12 col-sm d-flex align-items-center mb-3 mb-md-0">
        <i class="bi bi-building fs-4 me-3"></i> <!-- Icon for the location name -->
        <h4 class="mb-0 fw-bold text-white">${location.name}</h4>
    </div>
    <!-- View Centre Link -->
    <div class="col-12 col-sm-auto text-md-end">
        <a href="${location.view_center}" target="_blank" class="btn btn-outline-light btn-sm d-flex align-items-center">
            View Centre
            <i class="bi bi-arrow-right ms-2"></i>
        </a>
    </div>
</div>

    <hr class="border-light my-3">
    <div class="d-flex flex-column flex-md-row justify-content-between">
        <!-- Address Section -->
        <div class="mb-2 mb-md-0 d-flex align-items-center">
            <i class="bi bi-geo-alt-fill text-light fs-5 me-2"></i>
            <p class="mb-0">
                <span class="fw-bold">Address:&nbsp;</span>${location.address}
            </p>
        </div>
        <!-- Phone Section -->
        <div class="d-flex align-items-center">
            <i class="bi bi-telephone-fill text-light fs-5 me-2"></i>
            <p class="mb-0">
                <span class="fw-bold">Phone:&nbsp;</span>${location.number}
            </p>
        </div>
    </div>
</div>


            <div>
                ${Object.keys(groupedData)
            .map(group => renderSmallestSize(group))
            .join("")}
            </div>

            <div class="row mt-3 p-3 align-items-center">
                <!-- Size Guide -->
                <div class="col-12 col-sm d-flex align-items-center mb-3 mb-md-0">
                    <a href="#" class="text-custom-primary size-guide d-flex align-items-center">
                        <i class="bi bi-rulers fs-5 me-2"></i>
                        Size guide
                    </a>
                </div>
                <!-- Show More Units -->
                <div class="col-12 col-sm-auto text-md-end">
                    <a href="#" class="text-custom-primary view-all-sizes d-flex align-items-center" data-location="${location.name}">
                        <i class="bi bi-box-arrow-down fs-5 me-2"></i>
                        Show more units at this centre <i class="bi bi-arrow-down fs-6 ms-1"></i>
                    </a>
                </div>
            </div>



        </div>
    `;
    container.append(locationHTML);
}

$(document).on("click", ".show-all-units", function (event) {
    event.preventDefault(); // Prevent default anchor behavior
    const key = $(this).data("key");
    const [group, sizeKey] = key.split("_");
    const [width, length] = sizeKey.split("x");
    const units = unitDataStore[group] && unitDataStore[group][width] ? unitDataStore[group][width].filter(unit => unit.dcLength.toFixed(1) === length) : [];

    if (units.length) {
        // console.log(`All Units for Group: ${group}, Size: ${width}x${length}m`, units);

        const randomUnit = units[Math.floor(Math.random() * units.length)];

        // console.log(`Selected Unit Details:`, randomUnit);

        Swal.fire({
            icon: "success",
            title: "Storage Unit Selected",
            text: `Unit ID: ${randomUnit.UnitID}, Price: $${randomUnit.dcStdRate}`,
            willOpen: () => {
                $(document.body).addClass('swal-open');
            },
            willClose: () => {
                $(document.body).removeClass('swal-open');
            }
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

function storeUnitData(groupName, units) {
    if (!unitDataStore[groupName]) {
        unitDataStore[groupName] = {};
    }
    units.forEach(unit => {
        const width = unit.dcWidth.toFixed(1);
        if (!unitDataStore[groupName][width]) {
            unitDataStore[groupName][width] = [];
        }
        unitDataStore[groupName][width].push(unit);
    });
}


$(document).ready(() => {
    locations.forEach(locationCode => {
        $("#spinner-overlay").show();
        fetchLocationData(locationCode).done(response => {
            if (!response.error) {
                const groupedData = groupUnitsBySize(response.data);
                Object.keys(groupedData).forEach(group => {
                    storeUnitData(group, groupedData[group]);
                });
                renderLocation(response.location);
            }
        }).always(() => {
            $("#spinner-overlay").hide();
        });
    });

    $(document).on("click", ".view-all-sizes", function (event) {
        event.preventDefault(); // Prevent default anchor behavior
        const groupedData = unitDataStore;
        const allSizesHtml = renderAllSizes(groupedData);
        // Get the location name from the data-location attribute
        const locationName = $(this).data('location');
        $("#all-sizes-content").html(allSizesHtml);

        // Update the modal title
        $("#allSizesModalLabel").html(`${locationName} Storage Sizes`);

        $("#allSizesModal").modal("show");
    });

    $(document).on("click", ".random-select", function () {
        const unitIds = $(this).data("unit-ids").split(",");
        const randomUnit = unitIds[Math.floor(Math.random() * unitIds.length)];
        Swal.fire({
            icon: "success",
            title: "Random Unit Selected",
            text: `Unit ID: ${randomUnit}`,
        });
    });
});

function groupUnitsBySize(units) {
    const grouped = { Small: [], Medium: [], Large: [], XLarge: [] };

    units.forEach(unit => {
        const width = unit.dcWidth;
        if (width > 1 && width < 2) grouped.Small.push(unit);
        else if (width > 2 && width < 3) grouped.Medium.push(unit);
        else if (width > 3 && width < 4) grouped.Large.push(unit);
        else if (width >= 4) grouped.XLarge.push(unit);
    });

    return grouped;
}