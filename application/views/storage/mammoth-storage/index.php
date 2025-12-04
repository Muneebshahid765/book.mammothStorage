<?php
// index.php - Main Storage Selection Page (Step 1) with Backend Integration
$currentStep = 1;

// Load navigation header first
include(APPPATH . 'views/storage/templates/header.php');

// Then load search header
require_once 'header.php';
?>

<link rel="stylesheet" href="<?php echo base_url('assets/css/mammoth-storage.css'); ?>">

<!-- Main Content -->
<div class="container">
    <!-- Storage Type Section -->
    <div class="section-card">
        <div class="section-header-main" onclick="window.toggleMainSection('storageType')">
            <h2>What type of storage do you need?</h2>
            <div class="select-dropdown collapsed" id="storageTypeDropdown">
                Select storage type  <i class="fas fa-chevron-down"></i>
            </div>
        </div>
        
        <div class="content-area collapsed" id="storageTypeArea">
            <div class="storage-type-buttons">
                <button 
                    type="button" 
                    class="storage-type-btn-main" 
                    onclick="window.selectStorageTypeMain(this, 'storage')"
                >
                    Self Storage
                </button>
                <button 
                    type="button" 
                    class="storage-type-btn-main" 
                    onclick="window.selectStorageTypeMain(this, 'vehicle')"
                >
                    Vehicle Storage
                </button>
                <button 
                    type="button" 
                    class="storage-type-btn-main" 
                    onclick="window.selectStorageTypeMain(this, 'both')"
                >
                    Both Types
                </button>
            </div>
        </div>
    </div>

    <!-- Location Section -->
    <div class="section-card">
        <div class="section-header-main" onclick="window.toggleMainSection('location')">
            <h2>Choose Your Location</h2>
            <div class="select-dropdown collapsed" id="locationDropdown">
                Select location  <i class="fas fa-chevron-down"></i>
            </div>
        </div>

        <div class="content-area collapsed" id="locationArea">
            <div class="location-grid" id="locationGrid">
                <!-- Locations will be populated dynamically -->
            </div>
            
            <button class="load-more-btn hidden" id="loadMoreLocations" onclick="window.loadMoreLocations()">
                Load More Locations
            </button>
        </div>
    </div>

    <!-- Unit Size Section -->
    <div class="collapsible-section" id="unitSizeSection" style="display: none;">
        <div class="section-header" onclick="window.toggleSection('unitSize')">
            <h2>Select Your Unit Size</h2>
            <div class="section-toggle collapsed" id="unitSizeToggle">
                Select unit size <i class="fas fa-chevron-down"></i>
            </div>
        </div>
        <div class="section-content collapsed" id="unitSizeContent">
            <h5 class="text-center mb-3">Filter by Size</h5>
            <div class="filter-buttons justify-content-center d-flex" id="sizeFilters">
                <button class="filter-btn active" onclick="window.filterBySize(this, 'all')">All</button>
                <button class="filter-btn" onclick="window.filterBySize(this, 'Small')">Small</button>
                <button class="filter-btn" onclick="window.filterBySize(this, 'Medium')">Medium</button>
                <button class="filter-btn" onclick="window.filterBySize(this, 'Large')">Large</button>
                <button class="filter-btn" onclick="window.filterBySize(this, 'XLarge')">Extra Large</button>
            </div>

            <h5 class="text-center mb-3 mt-4" id="bedroomFilterTitle" style="display: none;">Filter by Bedrooms</h5>
            <div class="filter-buttons justify-content-center d-flex flex-wrap" id="bedroomFilters" style="display: none;">
                <button class="filter-btn active" onclick="window.filterByBedrooms(this, 'all')">All</button>
                <button class="filter-btn" onclick="window.filterByBedrooms(this, 'studio')">Studio</button>
                <button class="filter-btn" onclick="window.filterByBedrooms(this, '1_bedroom')">1 Bed</button>
                <button class="filter-btn" onclick="window.filterByBedrooms(this, '2_bedroom')">2 Bed</button>
                <button class="filter-btn" onclick="window.filterByBedrooms(this, '3_bedroom')">3 Bed</button>
                <button class="filter-btn" onclick="window.filterByBedrooms(this, '4_bedroom')">4 Bed</button>
                <button class="filter-btn" onclick="window.filterByBedrooms(this, 'large_4_bed')">Large 4 Bed</button>
            </div>

            <div class="unit-grid" id="unitGrid">
                <!-- Units will be populated dynamically -->
            </div>
            
            <button class="load-more-btn hidden" id="loadMoreUnits" onclick="window.loadMoreUnits()">
                Load More Units
            </button>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal-overlay" id="unitModal" onclick="window.closeModalOnOverlay(event)">
    <div class="modal-content-custom">
        <div class="modal-header-custom">
            <h3 id="modalTitle"></h3>
            <button class="modal-close" onclick="window.closeModal()">&times;</button>
        </div>
        <div class="modal-body-custom">
            <div class="modal-price" id="modalPrice"></div>
            <p class="modal-subtitle" id="modalSubtitle"></p>
            <div class="modal-description">
                <p id="modalDescription"></p>
            </div>
        </div>
        <div class="modal-footer-custom">
            <button class="btn-secondary-custom" onclick="window.closeModal()">View Other Units</button>
            <button class="btn-primary-custom" onclick="window.selectUnit()">Select This Unit</button>
        </div>
    </div>
</div>

<!-- Hidden container for form steps -->
<div id="step-container" style="display: none;"></div>

<style>
    /* Add spacing from the main navbar for all subsequent step forms */
    #step-container .container {
        margin-top: 40px;
    }

    @media (max-width: 768px) {
        #step-container .container {
            margin-top: 24px;
        }
    }
</style>

<script>
// ===== GLOBAL CONFIGURATION =====
const base_url = window.base_url || '<?php echo base_url(); ?>';

// console.log('Index.php script starting, base_url:', base_url);

// Location coordinates
const locationCoordinates = {
    L001: { lat: -26.6830022, lng: 153.0044652, name: "Forest Glen", address: "Shop 14 /380 Mons Road, Forest Glen QLD 4556", types: ["storage", "vehicle"] },
    L002: { lat: -26.6675501, lng: 153.0932467, name: "Maroochydore", address: "31/82 Sugar Rd, Maroochydore QLD 4558, Australia", types: ["storage"] },
    L003: { lat: -26.6131889, lng: 152.9608031, name: "Nambour", address: "33-49 Zealey Rd, Nambour QLD 4560", types: ["vehicle"] },
    L004: { lat: -26.7850723, lng: 153.0613775, name: "Caloundra", address: "40-42 Geo Hawkins Cres, Bells Creek QLD 4551", types: ["storage"] },
    L005: { lat: -25.320437, lng: 152.8895147, name: "Hervey Bay", address: "34 Southern Cross Circuit Urangan", types: ["vehicle"] },
    L006: { lat: -24.8963294, lng: 152.3256104, name: "Bundaberg", address: "38 Commerical Street, Kensington QLD", types: ["storage"] },
    L007: { lat: -26.6621377, lng: 153.0450456, name: "Kunda Park", address: "486 Maroochydore Rd, Kunda Park, QLD 4558, Australia", types: ["storage"] },
    L008: { lat: -26.1696936, lng: 152.6488382, name: "Gympie", address: "18 Bonnick Rd, Gympie QLD 4570", types: ["storage"] }
};

const locationTypes = {
    storage: ["L001", "L002", "L004", "L006", "L007", "L008"],
    vehicle: ["L003", "L005"],
    both: ["L001", "L002", "L003", "L004", "L005", "L006", "L007", "L008"]
};

// State variables
const unitDataStore = {};
const specialsStore = {};
const descriptionsCache = {};

let selectedStorageType = null;
let selectedLocationCode = null;
let selectedUnit = null;
let allUnitCards = [];
let displayedUnitsCount = 0;
const UNITS_PER_PAGE = 12;
let currentSizeFilter = 'all';
let currentBedroomFilter = 'all';
let searchUserCoords = null; // set from header search for inline distance sorting
var currentLeadTimeDays = 1; // Default lead time in days (global on window for step pages)

// ===== UTILITY FUNCTIONS =====

function getUserCoordinatesFromURL() {
    const urlParams = new URLSearchParams(window.location.search);
    const lat = parseFloat(urlParams.get("lat"));
    const lng = parseFloat(urlParams.get("lng"));
    return (!isNaN(lat) && !isNaN(lng)) ? { lat, lng } : null;
}

function calculateDistance(lat1, lng1, lat2, lng2) {
    const toRad = deg => deg * Math.PI / 180;
    const R = 6371;
    const dLat = toRad(lat2 - lat1);
    const dLng = toRad(lng2 - lng1);
    const a = Math.sin(dLat / 2) ** 2 + Math.cos(toRad(lat1)) * Math.cos(toRad(lat2)) * Math.sin(dLng / 2) ** 2;
    return R * (2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a)));
}

function fetchLocationData(locationCode) {
    const url = `${base_url}/units/fetch`;
    // console.log('Fetching from URL:', url, 'Location:', locationCode);
    
    return $.ajax({
        url: url,
        type: "POST",
        data: JSON.stringify({ locationCode: locationCode }),
        dataType: "json",
        contentType: 'application/json'
    });
}

function fetchLocationLeadTime(locationCode) {
    return $.ajax({
        url: `${base_url}/location-config/lead-time`,
        type: "GET",
        data: { location_code: locationCode },
        dataType: "json"
    }).then(function(response) {
        if (response && response.success && response.lead_time_days !== undefined && response.lead_time_days !== null) {
            var parsed = parseInt(response.lead_time_days, 10);
            if (!isNaN(parsed) && parsed >= 0) {
                currentLeadTimeDays = parsed; // allow 0 for same-day
            } else {
                currentLeadTimeDays = 1; // Fallback for invalid values
            }
        } else {
            currentLeadTimeDays = 1; // Fallback when API does not return a value
        }
        console.log('Lead time for', locationCode, ':', currentLeadTimeDays, 'days');
        return currentLeadTimeDays;
    }).catch(function() {
        currentLeadTimeDays = 1; // Fallback on error
        return currentLeadTimeDays;
    });
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

function findMatchingSpecials(unit, locationCode) {
    const specials = specialsStore[locationCode] || [];
    if (!Array.isArray(specials) || specials.length === 0) return [];

    const sizeKey = `${unit.dcWidth}x${unit.dcLength}`;

    return specials.filter(special => {
        // Prefer table-based size mappings when available
        if (Array.isArray(special.size_keys) && special.size_keys.length > 0) {
            return special.size_keys.indexOf(sizeKey) !== -1;
        }

        // Fallback to legacy dcWidth/dcLength matching
        if (special.dcWidth == null || special.dcLength == null) return false;
        const wMatch = Math.abs(parseFloat(special.dcWidth) - parseFloat(unit.dcWidth)) < 0.01;
        const lMatch = Math.abs(parseFloat(special.dcLength) - parseFloat(unit.dcLength)) < 0.01;
        return wMatch && lMatch;
    });
}

// Backwards-compatible helper; returns only the first matching special
function findMatchingSpecial(unit, locationCode) {
    const matches = findMatchingSpecials(unit, locationCode);
    return matches.length ? matches[0] : null;
}

function getPriceWithSpecial(unit, locationCode) {
    const base = parseFloat(unit.dcStdRate);
    if (isNaN(base)) {
        return { price: '0.00', label: null };
    }

    const matches = findMatchingSpecials(unit, locationCode);
    if (!matches || matches.length === 0) {
        return { price: base.toFixed(2), label: null };
    }

    let finalPrice = base;

    // Choose one discount-type special to affect price, if any
    const pricingSpecial = matches.find(sp => {
        const t = (sp.sDiscountType || '').toLowerCase();
        const v = parseFloat(sp.dDiscountPrice);

        if ((t === 'percentage' || t === 'fixed') && !isNaN(v) && v > 0) {
            return true;
        }

        if (t === 'free_months') {
            const mPay = parseInt(sp.months_to_pay, 10);
            const mFree = parseInt(sp.free_months, 10);
            return !isNaN(mPay) && mPay > 0 && !isNaN(mFree) && mFree > 0;
        }

        return false;
    }) || null;

    if (pricingSpecial) {
        const type  = (pricingSpecial.sDiscountType || '').toLowerCase();
        const value = parseFloat(pricingSpecial.dDiscountPrice);

        if (type === 'percentage' && !isNaN(value) && value > 0) {
            finalPrice = base * (1 - value / 100);
        } else if (type === 'fixed' && !isNaN(value) && value > 0) {
            finalPrice = base - value;
            if (finalPrice < 0) finalPrice = 0;
        } else if (type === 'free_months') {
            const mPay = parseInt(pricingSpecial.months_to_pay, 10);
            const mFree = parseInt(pricingSpecial.free_months, 10);
            const total = (!isNaN(mPay) && !isNaN(mFree)) ? (mPay + mFree) : 0;
            if (mPay > 0 && total > 0) {
                // Effective monthly rate over the full Buy X + Get Y period
                finalPrice = base * (mPay / total);
            }
        }
    }

    // Combine titles of all matching specials into a single label
    const labels = matches.map(sp => {
        if (sp.title && sp.title.trim()) return sp.title.trim();

        const t = (sp.sDiscountType || '').toLowerCase();
        const v = parseFloat(sp.dDiscountPrice);
        if (t === 'percentage' && !isNaN(v) && v > 0) {
            return `${v}% off`;
        }
        if (t === 'fixed' && !isNaN(v) && v > 0) {
            return `$${v.toFixed(2)} off`;
        }
        return '';
    }).filter(Boolean);

    const label = labels.length ? labels.join(' · ') : null;
    return { price: finalPrice.toFixed(2), label, labels };
}

function fetchDescription(sizeKey) {
    if (descriptionsCache[sizeKey] !== undefined) {
        return Promise.resolve(descriptionsCache[sizeKey]);
    }

    return $.ajax({
        url: `${base_url}/unit-description/get`,
        type: 'POST',
        dataType: 'json',
        contentType: 'application/json',
        data: JSON.stringify({ size_key: sizeKey })
    }).then(function(resp) {
        if (resp && resp.success) {
            descriptionsCache[sizeKey] = resp.data;
            return resp.data;
        }
        descriptionsCache[sizeKey] = null;
        return null;
    }).catch(function() {
        descriptionsCache[sizeKey] = null;
        return null;
    });
}

// ===== TOGGLE FUNCTIONS =====
window.toggleMainSection = function(sectionName) {
    // console.log('toggleMainSection:', sectionName);
    const area = document.getElementById(sectionName + 'Area');
    const dropdown = document.getElementById(sectionName + 'Dropdown');
    
    if (!area || !dropdown) {
        // console.error('Elements not found:', sectionName);
        return;
    }
    
    area.classList.toggle('collapsed');
    dropdown.classList.toggle('collapsed');
    
    const icon = dropdown.querySelector('i');
    if (icon) {
        icon.className = area.classList.contains('collapsed') ? 'fas fa-chevron-down' : 'fas fa-chevron-up';
    }
};

window.toggleSection = function(sectionName) {
    // console.log('toggleSection:', sectionName);
    const content = document.getElementById(sectionName + 'Content');
    const toggle = document.getElementById(sectionName + 'Toggle');
    
    if (!content || !toggle) {
        // console.error('Elements not found:', sectionName);
        return;
    }
    
    content.classList.toggle('collapsed');
    toggle.classList.toggle('collapsed');
    
    const icon = toggle.querySelector('i');
    if (icon) {
        icon.className = content.classList.contains('collapsed') ? 'fas fa-chevron-down' : 'fas fa-chevron-up';
    }
};

// ===== STORAGE TYPE SELECTION =====
window.selectStorageTypeMain = function(element, type) {
    // console.log('selectStorageTypeMain:', type);
    event.stopPropagation();
    
    document.querySelectorAll('.storage-type-btn-main').forEach(btn => btn.classList.remove('active'));
    element.classList.add('active');
    selectedStorageType = type;
    updateBedroomFiltersVisibility();
    
    document.getElementById('storageTypeDropdown').innerHTML = 
        element.textContent.trim() + ' <i class="fas fa-chevron-up"></i>';
    
    // Hide other sections
    const storageTypeSection = document.getElementById('storageTypeSelectionSection');
    const unitSection = document.getElementById('unitSizeSection');
    if (storageTypeSection) storageTypeSection.style.display = 'none';
    if (unitSection) unitSection.style.display = 'none';
    
    // Render locations
    renderLocations(type);
    
    // Show location section
    const locationArea = document.getElementById('locationArea');
    const locationDropdown = document.getElementById('locationDropdown');
    if (locationArea && locationDropdown) {
        locationArea.classList.remove('collapsed');
        locationDropdown.classList.remove('collapsed');
        const icon = locationDropdown.querySelector('i');
        if (icon) icon.className = 'fas fa-chevron-up';
    }
    
    // Scroll to location section
    setTimeout(() => {
        const section = document.querySelector('.section-card:nth-child(2)');
        if (section) section.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
    }, 100);
};

window.selectStorageType = function(element, type) {
    // console.log('selectStorageType (dropdown):', type);
    event.stopPropagation();
    
    document.querySelectorAll('#storageTypeSelectionContent .storage-type-btn-main').forEach(btn => btn.classList.remove('active'));
    element.classList.add('active');
    selectedStorageType = type;
    updateBedroomFiltersVisibility();
    
    document.getElementById('storageTypeSelectionToggle').innerHTML = 
        element.textContent.trim() + ' <i class="fas fa-chevron-up"></i>';
    
    // Show unit section
    const unitSection = document.getElementById('unitSizeSection');
    // console.log('Unit section found:', !!unitSection);
    
    if (unitSection) {
        unitSection.style.display = 'flex';
        // console.log('Unit section display set to flex');
        
        const unitSizeContent = document.getElementById('unitSizeContent');
        const unitSizeToggle = document.getElementById('unitSizeToggle');
        // console.log('Unit content found:', !!unitSizeContent, 'Toggle found:', !!unitSizeToggle);
        
        if (unitSizeContent && unitSizeToggle) {
            unitSizeContent.classList.remove('collapsed');
            unitSizeToggle.classList.remove('collapsed');
            // console.log('Removed collapsed classes');
            
            const icon = unitSizeToggle.querySelector('i');
            if (icon) {
                icon.className = 'fas fa-chevron-up';
                // console.log('Icon changed to chevron-up');
            }
        }
        
        setTimeout(() => {
            unitSection.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
            // console.log('Scrolled to unit section');
        }, 300);
    } else {
        // console.error('Unit section not found!');
    }
};

// ===== LOCATION FUNCTIONS =====
function renderLocations(type) {
    // console.log('renderLocations:', type);
    const locationGrid = document.getElementById('locationGrid');
    if (!locationGrid) return;
    
    locationGrid.innerHTML = '';
    const locations = locationTypes[type] || [];
    // Prefer coordinates provided by header search; fall back to URL params
    const userCoords = searchUserCoords || getUserCoordinatesFromURL();
    
    const locationCards = locations.map(code => {
        const loc = locationCoordinates[code];
        if (!loc) return null;
        
        let distance = null;
        if (userCoords) {
            distance = calculateDistance(userCoords.lat, userCoords.lng, loc.lat, loc.lng);
        }
        
        return { code, loc, distance };
    }).filter(Boolean);
    
    if (userCoords) {
        locationCards.sort((a, b) => (a.distance || 999) - (b.distance || 999));
    }
    
    locationCards.forEach(({ code, loc, distance }) => {
        const card = document.createElement('div');
        card.className = 'location-card';
        card.setAttribute('data-location-code', code);
        card.onclick = () => window.selectLocationCard(code);
        
        let distanceBadge = '';
        if (distance !== null) {
            const formattedDistance = distance.toFixed(1);
            distanceBadge = `<span class="distance-badge"><i class="fas fa-map-marker-alt"></i> ${formattedDistance} km away</span>`;
        }
        
        let typeLabel = '';
        if (loc.types) {
            if (loc.types.includes('storage') && loc.types.includes('vehicle')) {
                typeLabel = '<p class="storage-type-label">Self Storage & Vehicle Storage</p>';
            } else if (loc.types.includes('storage')) {
                typeLabel = '<p class="storage-type-label">Self Storage Only</p>';
            } else if (loc.types.includes('vehicle')) {
                typeLabel = '<p class="storage-type-label">Vehicle Storage Only</p>';
            }
        }
        
        card.innerHTML = `
            <h3>${loc.name}</h3>
            <p class="address">${loc.address}</p>
            ${typeLabel}
            ${distanceBadge}
        `;
        
        locationGrid.appendChild(card);
    });
    
    console.log('Rendered', locationCards.length, 'locations');
}

window.selectLocationCard = async function(locationCode) {
    console.log('selectLocationCard:', locationCode);
    
    document.querySelectorAll('.location-card').forEach(card => card.classList.remove('selected'));
    
    const selectedCard = document.querySelector(`[data-location-code="${locationCode}"]`);
    if (selectedCard) selectedCard.classList.add('selected');
    
    selectedLocationCode = locationCode;
    const locationName = locationCoordinates[locationCode].name;
    
    document.getElementById('locationDropdown').innerHTML = locationName + ' <i class="fas fa-chevron-up"></i>';
    
    const spinner = document.getElementById('spinner-overlay');
    if (spinner) spinner.classList.add('show');
    
    try {
        // Fetch lead time for this location
        await fetchLocationLeadTime(locationCode);
        
        const response = await fetchLocationData(locationCode);
        console.log('API Response:', response);
        console.log('Response data length:', response?.data?.length);
        
        if (response && !response.error && response.data) {
            const groupedData = groupUnitsBySize(response.data);
            console.log('Grouped data:', groupedData);
            unitDataStore[locationCode] = groupedData;
            if (response.specials) {
                specialsStore[locationCode] = response.specials;
            }
            renderUnits(locationCode, groupedData);

            // Hide the intermediate storage type selection section
            const storageTypeSection = document.getElementById('storageTypeSelectionSection');
            if (storageTypeSection) {
                storageTypeSection.style.display = 'none';
            }

            // Immediately show and expand the unit section after location selection
            const unitSection = document.getElementById('unitSizeSection');
            if (unitSection) {
                unitSection.style.display = 'flex';

                const unitSizeContent = document.getElementById('unitSizeContent');
                const unitSizeToggle = document.getElementById('unitSizeToggle');
                if (unitSizeContent && unitSizeToggle) {
                    unitSizeContent.classList.remove('collapsed');
                    unitSizeToggle.classList.remove('collapsed');
                    const icon = unitSizeToggle.querySelector('i');
                    if (icon) icon.className = 'fas fa-chevron-up';
                }

                setTimeout(() => {
                    unitSection.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
                }, 300);
            }
        } else {
            alert('No units available for this location.');
        }
    } catch (error) {
        // console.error('Error:', error);
        alert('Error loading units. Please try again.');
    } finally {
        if (spinner) spinner.classList.remove('show');
        updateBedroomFiltersVisibility();
    }
}

function renderUnits(locationCode, groupedData) {
    // console.log('renderUnits:', locationCode);
    const unitGrid = document.getElementById('unitGrid');
    if (!unitGrid) return;
    
    unitGrid.innerHTML = '';
    allUnitCards = [];
    displayedUnitsCount = 0;
    currentSizeFilter = 'all';
    currentBedroomFilter = 'all';
    updateBedroomFiltersVisibility();
    
    Object.keys(groupedData).forEach(group => {
        const units = groupedData[group];
        if (units.length === 0) return;
        
        const dimensionGroups = {};
        units.forEach(unit => {
            const key = `${unit.dcWidth.toFixed(1)}x${unit.dcLength.toFixed(1)}`;
            if (!dimensionGroups[key]) dimensionGroups[key] = [];
            dimensionGroups[key].push(unit);
        });
        
        Object.keys(dimensionGroups).forEach(key => {
            const unitsForSize = dimensionGroups[key];
            const exampleUnit = unitsForSize[0];
            const pricing = getPriceWithSpecial(exampleUnit, locationCode);
            
            let specialLabelHtml = '';
            if (pricing.labels && Array.isArray(pricing.labels) && pricing.labels.length) {
                // Render one pill per label so long texts don't break the layout
                specialLabelHtml = pricing.labels.map(function(text) {
                    return `<p class="special-label"><i class="bi bi-tag-fill"></i> ${text}</p>`;
                }).join('');
            } else if (pricing.label) {
                specialLabelHtml = `<p class="special-label"><i class="bi bi-tag-fill"></i> ${pricing.label}</p>`;
            } else {
                // Render an invisible placeholder so layout stays consistent when there is no offer
                specialLabelHtml = `<p class="special-label special-label-empty">&nbsp;</p>`;
            }
            
            const sizeKeyForDesc = `${exampleUnit.dcWidth}x${exampleUnit.dcLength}`;

            // Determine original vs discounted price for display
            const basePrice = parseFloat(exampleUnit.dcStdRate);
            const currentPrice = parseFloat(pricing.price);
            let priceHtml = '';

            if (!isNaN(basePrice) && !isNaN(currentPrice) && currentPrice < basePrice) {
                priceHtml = `
                    <span class="old-price">$${basePrice.toFixed(2)}/mo</span>
                    <span class="new-price">$${currentPrice.toFixed(2)}/mo</span>
                `;
            } else if (!isNaN(currentPrice)) {
                priceHtml = `$${currentPrice.toFixed(2)}/mo`;
            } else {
                priceHtml = '$0.00/mo';
            }

            const isLowStock = unitsForSize.length <= 2;
            const availabilityHtml = isLowStock
                ? `<p class="unit-availability unit-availability-low"><i class="bi bi-exclamation-circle-fill"></i> Last ones remaining</p>`
                : '';

            const card = document.createElement('div');
            card.className = 'unit-card';
            // Initial size group based on width; may be overridden later by
            // description filters (Small/Medium/Large/Extra Large).
            card.setAttribute('data-size', group);

            const clickHandler = function (event) {
                const targetCard = (event && event.currentTarget) ? event.currentTarget : this || card;
                const sizeFromAttr = (targetCard && targetCard.getAttribute('data-size')) || group;
                window.openUnitModal(exampleUnit, unitsForSize, sizeFromAttr);
            };

            card.innerHTML = `
                <div class="unit-card-header">
                    <h4 class="unit-title">${group}</h4>
                    <p class="unit-size">${exampleUnit.dcWidth}m × ${exampleUnit.dcLength}m <span class="unit-area">(${(exampleUnit.dcWidth * exampleUnit.dcLength).toFixed(2)}m²)</span></p>
                    <p class="unit-floor">Floor: ${exampleUnit.sTypeName || '-'}</p>
                </div>
                <div class="unit-card-body">
                    <div class="unit-body-icon">
                        <i class="bi bi-box-seam"></i>
                    </div>
                    <p class="unit-desc" data-size-key="${sizeKeyForDesc}"></p>
                </div>
                <div class="unit-card-footer">
                    <div class="unit-price-block">
                        <p class="price">${priceHtml}</p>
                        ${specialLabelHtml}
                        ${availabilityHtml}
                    </div>
                    <div class="unit-arrow-btn">
                        <i class="bi bi-arrow-right"></i>
                    </div>
                </div>
            `;

            // Load description asynchronously and attach bedroom + size filters
            fetchDescription(sizeKeyForDesc).then(function(descData) {
                const descEls = document.querySelectorAll(`.unit-desc[data-size-key="${sizeKeyForDesc}"]`);

                descEls.forEach(function(descEl) {
                    if (!descEl) return;
                    const cardEl = descEl.closest('.unit-card');
                    const bodyEl = descEl.closest('.unit-card-body');

                    if (descData && descData.description) {
                        descEl.textContent = descData.description;
                        if (bodyEl) bodyEl.classList.remove('unit-card-body-hidden');
                    } else {
                        // No description: visually hide the whole bubble but keep its space
                        // so the card format and height remain consistent.
                        descEl.textContent = '';
                        if (bodyEl) bodyEl.classList.add('unit-card-body-hidden');
                    }

                    if (cardEl && descData && Array.isArray(descData.filters)) {
                        const rawFilters = descData.filters;
                        const nameToSlugMap = {
                            'Studio': 'studio',
                            '1 Bedroom': '1_bedroom',
                            '2 Bedroom': '2_bedroom',
                            '3 Bedroom': '3_bedroom',
                            '4 Bedroom': '4_bedroom',
                            'Large 4 Bedroom': 'large_4_bed',
                            'Vehicle': 'vehicle',
                            'Small': 'small',
                            'Medium': 'medium',
                            'Large': 'large',
                            'Extra Large': 'extra_large'
                        };

                        const slugs = rawFilters
                            .map(function(f) {
                                if (typeof f === 'string') return f;
                                if (!f) return '';
                                return f.name || f.slug || '';
                            })
                            .map(function(name) {
                                name = (name || '').trim();
                                if (!name) return '';
                                if (nameToSlugMap[name]) return nameToSlugMap[name];
                                return name.toLowerCase().replace(/\s+/g, '_');
                            })
                            .filter(function(s) { return s; });

                        if (slugs.length) {
                            const slugString = slugs.join(',');
                            cardEl.setAttribute('data-bedroom-filters', slugString);
                            card.setAttribute('data-bedroom-filters', slugString);
                        }

                        // If description specifies an explicit size category
                        // (Small/Medium/Large/Extra Large), override the
                        // card's size label and data-size so the frontend
                        // matches the admin Unit Inventory classification.
                        const sizeCategorySlugs = ['small', 'medium', 'large', 'extra_large'];
                        const sizeSlug = slugs.find(function (s) {
                            return sizeCategorySlugs.indexOf(s) !== -1;
                        });

                        if (sizeSlug) {
                            var displayGroup;
                            if (sizeSlug === 'extra_large') {
                                displayGroup = 'XLarge';
                            } else {
                                displayGroup = sizeSlug.charAt(0).toUpperCase() + sizeSlug.slice(1);
                            }

                            const titleEl = cardEl.querySelector('.unit-title');
                            if (titleEl) {
                                titleEl.textContent = displayGroup;
                            }

                            cardEl.setAttribute('data-size', displayGroup);
                            card.setAttribute('data-size', displayGroup);
                        }
                    }
                });
            });

            allUnitCards.push({ element: card, onclick: clickHandler });
        });
    });
    
    displayInitialUnits();
}

function displayInitialUnits() {
    const unitGrid = document.getElementById('unitGrid');
    if (!unitGrid) return;
    
    unitGrid.innerHTML = '';
    displayedUnitsCount = 0;
    
    const cardsToShow = allUnitCards.slice(0, UNITS_PER_PAGE);
    cardsToShow.forEach(cardData => {
        const newCard = cardData.element.cloneNode(true);
        newCard.onclick = cardData.onclick;
        unitGrid.appendChild(newCard);
    });
    
    displayedUnitsCount = cardsToShow.length;
    
    const loadMoreBtn = document.getElementById('loadMoreUnits');
    if (loadMoreBtn) {
        if (allUnitCards.length > UNITS_PER_PAGE) {
            loadMoreBtn.classList.remove('hidden');
        } else {
            loadMoreBtn.classList.add('hidden');
        }
    }
}

// ===== MODAL FUNCTIONS =====
window.openUnitModal = function(unit, allUnits, group) {
    // console.log('openUnitModal:', group);
    const pricing = getPriceWithSpecial(unit, selectedLocationCode);
    const modalTitleEl = document.getElementById('modalTitle');
    const modalPriceEl = document.getElementById('modalPrice');
    const modalSubtitleEl = document.getElementById('modalSubtitle');
    const modalDescriptionEl = document.getElementById('modalDescription');

    if (!modalTitleEl || !modalPriceEl || !modalSubtitleEl || !modalDescriptionEl) {
        return;
    }

    modalTitleEl.textContent = `${group} - ${unit.dcWidth}m × ${unit.dcLength}m`;
    modalPriceEl.textContent = `$${pricing.price}/month`;

    // Modal subtitle: only show special label, no unit count
    if (pricing.label) {
        modalSubtitleEl.textContent = pricing.label;
    } else {
        modalSubtitleEl.textContent = '';
    }

    // Default/fallback description
    const fallbackDesc = `Storage area: ${(unit.dcWidth * unit.dcLength).toFixed(2)}m². Perfect for your storage needs.`;
    modalDescriptionEl.textContent = fallbackDesc;

    const sizeKeyForDesc = `${unit.dcWidth}x${unit.dcLength}`;
    const blockLabel = '';

    // Prefer admin size-based description; if missing, fall back to unit.sUnitDesc; else fallback text
    fetchDescription(sizeKeyForDesc)
        .then(function(descData) {
            if (descData && descData.description) {
                // When admin description exists, render block on its own line (if available),
                // then show the description text on the next line.
                modalDescriptionEl.innerHTML = '';

                if (blockLabel) {
                    const labelNode = document.createTextNode(blockLabel);
                    modalDescriptionEl.appendChild(labelNode);
                    modalDescriptionEl.appendChild(document.createElement('br'));
                }

                const descSpan = document.createElement('span');
                descSpan.textContent = descData.description;
                modalDescriptionEl.appendChild(descSpan);
            } else if (blockLabel) {
                // No admin description; just show the block label as before
                modalDescriptionEl.textContent = blockLabel;
            } else {
                modalDescriptionEl.textContent = fallbackDesc;
            }
        })
        .catch(function() {
            if (blockLabel) {
                modalDescriptionEl.textContent = blockLabel;
            } else {
                modalDescriptionEl.textContent = fallbackDesc;
            }
        });

    selectedUnit = { ...unit, group: group, allUnits: allUnits };

    document.getElementById('unitModal').classList.add('show');
    document.body.style.overflow = 'hidden';
};

window.closeModal = function() {
    document.getElementById('unitModal').classList.remove('show');
    document.body.style.overflow = 'auto';
};

window.closeModalOnOverlay = function(event) {
    if (event.target.id === 'unitModal') window.closeModal();
};

window.selectUnit = function() {
    if (!selectedUnit) return;
    
    const randomUnit = selectedUnit.allUnits[Math.floor(Math.random() * selectedUnit.allUnits.length)];
    const pricing = getPriceWithSpecial(randomUnit, selectedLocationCode);
    
    const bookingData = {
        storageType: selectedStorageType,
        locationCode: selectedLocationCode,
        locationName: locationCoordinates[selectedLocationCode].name,
        unit: {
            unitId: randomUnit.UnitID,
            unitName: randomUnit.sUnitName,
            building: randomUnit.sTypeName,
            unitDesc: randomUnit.sUnitDesc || '',
            group: selectedUnit.group,
            width: randomUnit.dcWidth,
            length: randomUnit.dcLength,
            size: `${randomUnit.dcWidth}m × ${randomUnit.dcLength}m`,
            area: (randomUnit.dcWidth * randomUnit.dcLength).toFixed(2),
            price: pricing.price,
            originalPrice: parseFloat(randomUnit.dcStdRate).toFixed(2),
            floor: randomUnit.iFloor,
            isInside: randomUnit.bInside === "true",
            isClimate: randomUnit.bClimate === "true",
            hasAlarm: randomUnit.bAlarm === "true"
        },
        timestamp: new Date().toISOString()
    };
    
    sessionStorage.setItem('bookingData', JSON.stringify(bookingData));
    
    document.getElementById('unitSizeToggle').innerHTML = 
        `${selectedUnit.group} - ${randomUnit.dcWidth}m × ${randomUnit.dcLength}m - ${pricing.price}/mo <i class="fas fa-chevron-up"></i>`;

    window.closeModal();
    loadStep('personal-info');
};

// ===== FILTER FUNCTIONS =====
function updateBedroomFiltersVisibility() {
    const bedroomTitle = document.getElementById('bedroomFilterTitle');
    const bedroomFilters = document.getElementById('bedroomFilters');
    if (!bedroomTitle || !bedroomFilters) return;

    let showBedrooms = false;

    if (selectedStorageType === 'storage') {
        showBedrooms = true;
    } else if (selectedStorageType === 'both') {
        // For 'Both Types', only show bedroom filters when the chosen location
        // actually offers self storage (i.e. its types include 'storage').
        if (selectedLocationCode && locationCoordinates[selectedLocationCode]) {
            const loc = locationCoordinates[selectedLocationCode];
            if (Array.isArray(loc.types) && loc.types.includes('storage')) {
                showBedrooms = true;
            }
        }
    }

    if (showBedrooms) {
        bedroomTitle.classList.remove('bedroom-hidden');
        bedroomFilters.classList.remove('bedroom-hidden');
        bedroomTitle.style.display = '';
        bedroomFilters.style.display = 'flex';
    } else {
        bedroomTitle.classList.add('bedroom-hidden');
        bedroomFilters.classList.add('bedroom-hidden');
        bedroomTitle.style.display = 'none';
        bedroomFilters.style.display = 'none';

        // Reset bedroom filter to 'all' when hiding
        if (currentBedroomFilter !== 'all') {
            currentBedroomFilter = 'all';
            const buttons = bedroomFilters.querySelectorAll('.filter-btn');
            buttons.forEach((btn, index) => {
                if (index === 0) {
                    btn.classList.add('active');
                } else {
                    btn.classList.remove('active');
                }
            });
            applyUnitFilters();
        }
    }
}

function applyUnitFilters() {
    const cards = document.querySelectorAll('.unit-card');
    cards.forEach(function(card) {
        const sizeAttr = card.getAttribute('data-size');
        const bedroomAttr = card.getAttribute('data-bedroom-filters') || '';
        const bedroomSlugs = bedroomAttr ? bedroomAttr.split(',') : [];

        const matchesSize = (currentSizeFilter === 'all' || sizeAttr === currentSizeFilter);
        let matchesBedroom = true;
        if (currentBedroomFilter !== 'all') {
            matchesBedroom = bedroomSlugs.includes(currentBedroomFilter);
        }

        // Keep unit cards as flex containers so the design (footer alignment, arrow position)
        // stays consistent even after filters or Load More are applied.
        card.style.display = (matchesSize && matchesBedroom) ? 'flex' : 'none';
    });
}

window.filterBySize = function(element, size) {
    document.querySelectorAll('#sizeFilters .filter-btn').forEach(btn => btn.classList.remove('active'));
    element.classList.add('active');
    
    currentSizeFilter = size;
    applyUnitFilters();
};

window.filterByBedrooms = function(element, bedrooms) {
    document.querySelectorAll('#bedroomFilters .filter-btn').forEach(btn => btn.classList.remove('active'));
    element.classList.add('active');
    
    currentBedroomFilter = bedrooms;
    applyUnitFilters();
};

// ===== LOAD MORE FUNCTIONS =====
window.loadMoreLocations = function() {
    // console.log('Load more locations');
};

window.loadMoreUnits = function() {
    const unitGrid = document.getElementById('unitGrid');
    if (!unitGrid) return;
    
    const nextBatch = allUnitCards.slice(displayedUnitsCount, displayedUnitsCount + UNITS_PER_PAGE);
    
    nextBatch.forEach(cardData => {
        const newCard = cardData.element.cloneNode(true);
        newCard.onclick = cardData.onclick;
        unitGrid.appendChild(newCard);

        // Ensure description visibility is correct for newly added cards
        const descEl = newCard.querySelector('.unit-desc');
        if (descEl) {
            const sizeKeyForDesc = descEl.getAttribute('data-size-key');
            if (sizeKeyForDesc) {
                fetchDescription(sizeKeyForDesc).then(function(descData) {
                    const bodyEl = descEl.closest('.unit-card-body');
                    if (descData && descData.description) {
                        descEl.textContent = descData.description;
                        if (bodyEl) bodyEl.classList.remove('unit-card-body-hidden');
                    } else {
                        descEl.textContent = '';
                        if (bodyEl) bodyEl.classList.add('unit-card-body-hidden');
                    }
                });
            }
        }
    });
    
    displayedUnitsCount += nextBatch.length;
    
    const loadMoreBtn = document.getElementById('loadMoreUnits');
    if (loadMoreBtn && displayedUnitsCount >= allUnitCards.length) {
        loadMoreBtn.classList.add('hidden');
    }
    
    // Ensure newly loaded cards respect the currently selected size/bedroom filters
    applyUnitFilters();
};

window.applySearchToLocations = function(lat, lng) {
    searchUserCoords = { lat: lat, lng: lng };

    try {
        const url = new URL(window.location.href);
        url.searchParams.set('lat', lat);
        url.searchParams.set('lng', lng);
        window.history.replaceState({}, '', url.toString());
    } catch (e) {}

    // If user has NOT selected a storage type yet, prompt them to choose storage type first
    if (!selectedStorageType) {
        // Expand the storage type section
        const storageArea = document.getElementById('storageTypeArea');
        const storageDropdown = document.getElementById('storageTypeDropdown');
        if (storageArea && storageDropdown) {
            storageArea.classList.remove('collapsed');
            storageDropdown.classList.remove('collapsed');
            const icon = storageDropdown.querySelector('i');
            if (icon) icon.className = 'fas fa-chevron-up';
        }

        // Collapse the location section until a storage type is chosen
        const locationArea = document.getElementById('locationArea');
        const locationDropdown = document.getElementById('locationDropdown');
        if (locationArea && locationDropdown) {
            locationArea.classList.add('collapsed');
            locationDropdown.classList.add('collapsed');
            const icon = locationDropdown.querySelector('i');
            if (icon) icon.className = 'fas fa-chevron-down';
        }

        // Scroll the user to the storage type section
        setTimeout(function() {
            const section = document.querySelector('.section-card:nth-child(1)');
            if (section) {
                section.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
            }
        }, 100);

        // Do not render locations yet; selectStorageTypeMain() will handle this
        return;
    }

    // If a storage type is already selected, re-render locations for that type
    renderLocations(selectedStorageType);

    // Ensure the location section is visible and expanded
    const locationArea = document.getElementById('locationArea');
    const locationDropdown = document.getElementById('locationDropdown');
    if (locationArea && locationDropdown) {
        locationArea.classList.remove('collapsed');
        locationDropdown.classList.remove('collapsed');
        const icon = locationDropdown.querySelector('i');
        if (icon) icon.className = 'fas fa-chevron-up';
    }

    // Smooth scroll user to the location section
    setTimeout(function() {
        const section = document.querySelector('.section-card:nth-child(2)');
        if (section) {
            section.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
        }
    }, 100);
};

// ===== KEYBOARD EVENTS =====
document.addEventListener('keydown', function(event) {
    if (event.key === 'Escape') window.closeModal();
});

// ===== STEP LOADING =====
function loadStep(stepName) {
    sessionStorage.setItem('currentStep', stepName);
    
    document.querySelectorAll('.container').forEach(container => container.style.display = 'none');
    
    // Hide the header/search section on subsequent steps so it only shows
    // on the main Unit Selection page (step 1).
    const headerSection = document.querySelector('.header-section');
    if (headerSection) {
        headerSection.style.display = 'none';
    }
    
    const stepContainer = document.getElementById('step-container');
    stepContainer.style.display = 'block';
    // Clear previous step content while global spinner overlay is shown
    stepContainer.innerHTML = '';

    // Show the global full-page spinner overlay so it also covers the header
    const globalSpinner = document.getElementById('spinner-overlay');
    if (globalSpinner) {
        globalSpinner.classList.add('show');
    }
    
    fetch(base_url + '/mammoth-storage/load-step', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: 'step=' + encodeURIComponent(stepName)
    })
    .then(response => response.text())
    .then(html => {
        stepContainer.innerHTML = html;
        executeInlineScripts(stepContainer);

        const stepNumbers = {
            'personal-info': 2,
            'additional-details': 3,
            'insurance-terms': 4,
            'payment': 5,
            'booking-confirmed': 6
        };

        if (typeof updateFooterSteps === 'function') {
            updateFooterSteps(stepNumbers[stepName] || 1);
        }

        initializeStepHandlers(stepName);

        // MutationObserver in global-search.js will automatically reinitialize autocomplete
        // No manual reinitialization needed - it detects DOM changes automatically

        window.scrollTo(0, 0);

        // Hide the global spinner once content has loaded
        if (globalSpinner) {
            globalSpinner.classList.remove('show');
        }
    })
    .catch(error => {
        // console.error('Error loading step:', error);
        stepContainer.innerHTML = '<div style="text-align: center; padding: 50px;"><p>Error loading content. Please try again.</p></div>';

        // Also hide the global spinner on error so the user can interact again
        if (globalSpinner) {
            globalSpinner.classList.remove('show');
        }
    });
}

function executeInlineScripts(container) {
    const scripts = container.querySelectorAll('script');
    scripts.forEach(script => {
        try {
            const scriptContent = script.textContent;
            const hasBlockDeclarations = /\b(const|let)\s+\w+/.test(scriptContent);
            
            const newScript = document.createElement('script');
            Array.from(script.attributes).forEach(attr => 
                newScript.setAttribute(attr.name, attr.value)
            );
            
            if (hasBlockDeclarations) {
                newScript.textContent = `(function() { ${scriptContent} })();`;
            } else {
                newScript.textContent = scriptContent;
            }
            
            script.parentNode.replaceChild(newScript, script);
        } catch (error) {
            console.error('Error executing inline script:', error);
        }
    });
}

function initializeStepHandlers(stepName) {
    if (stepName === 'personal-info') {
        const form = document.getElementById('personalInfoForm');
        if (form) {
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                const formData = {
                    firstName: document.getElementById('firstName')?.value || '',
                    lastName: document.getElementById('lastName')?.value || '',
                    mobileNumber: document.getElementById('mobileNumber')?.value || '',
                    email: document.getElementById('email')?.value || '',
                    phone: document.getElementById('phone')?.value || ''
                };
                sessionStorage.setItem('personalInfo', JSON.stringify(formData));
                loadStep('additional-details');
            });
        }
    }
}

function goBackToMain() {
    // console.log('goBackToMain called');
    sessionStorage.removeItem('currentStep');
    
    const stepContainer = document.getElementById('step-container');
    if (stepContainer) {
        stepContainer.style.display = 'none';
        stepContainer.innerHTML = '';
    }
    
    document.querySelectorAll('.container').forEach(container => container.style.display = 'block');
    
    const headerSection = document.querySelector('.header-section');
    if (headerSection) headerSection.style.display = 'block';
    
    if (typeof updateFooterSteps === 'function') {
        updateFooterSteps(1);
    }
    
    // Restore the state from sessionStorage
    const bookingData = sessionStorage.getItem('bookingData');
    if (bookingData) {
        // console.log('Restoring state after going back');
        try {
            const data = JSON.parse(bookingData);
            
            // Restore storage type selection
            if (data.storageType) {
                selectedStorageType = data.storageType;
                const typeText = data.storageType === 'storage' ? 'Self Storage' : 
                                data.storageType === 'vehicle' ? 'Vehicle Storage' : 'Both Types';
                const dropdown = document.getElementById('storageTypeDropdown');
                if (dropdown) {
                    dropdown.innerHTML = typeText + ' <i class="fas fa-chevron-up"></i>';
                    dropdown.classList.remove('collapsed');
                }
                
                // Show and expand storage type area
                const storageTypeArea = document.getElementById('storageTypeArea');
                if (storageTypeArea) {
                    storageTypeArea.classList.remove('collapsed');
                }
                
                // Render locations
                renderLocations(data.storageType);
            }
            
            // Restore location selection
            if (data.locationCode) {
                selectedLocationCode = data.locationCode;
                const dropdown = document.getElementById('locationDropdown');
                if (dropdown) {
                    dropdown.innerHTML = data.locationName + ' <i class="fas fa-chevron-up"></i>';
                    dropdown.classList.remove('collapsed');
                }
                
                // Show and expand location area
                const locationArea = document.getElementById('locationArea');
                if (locationArea) {
                    locationArea.classList.remove('collapsed');
                }
                
                // Highlight selected location card
                setTimeout(() => {
                    const locationCard = document.querySelector(`[data-location-code="${data.locationCode}"]`);
                    if (locationCard) {
                        locationCard.classList.add('selected');
                    }
                }, 100);
                
                // Fetch and render units
                fetchLocationData(data.locationCode).then(response => {
                    if (response && !response.error && response.data) {
                        const groupedData = groupUnitsBySize(response.data);
                        unitDataStore[data.locationCode] = groupedData;
                        renderUnits(data.locationCode, groupedData);
                        
                        // Show storage type selection section (3rd dropdown)
                        const storageTypeSection = document.getElementById('storageTypeSelectionSection');
                        if (storageTypeSection) {
                            storageTypeSection.style.display = 'flex';
                            
                            const content = document.getElementById('storageTypeSelectionContent');
                            const toggle = document.getElementById('storageTypeSelectionToggle');
                            if (content && toggle) {
                                content.classList.remove('collapsed');
                                toggle.classList.remove('collapsed');
                                const icon = toggle.querySelector('i');
                                if (icon) icon.className = 'fas fa-chevron-up';
                            }
                            
                            // Update the toggle text if storage type was selected
                            if (data.storageType && toggle) {
                                const typeText = data.storageType === 'storage' ? 'Self Storage' : 'Vehicle Storage';
                                toggle.innerHTML = typeText + ' <i class="fas fa-chevron-up"></i>';
                            }
                        }
                        
                        // Show unit section (4th dropdown)
                        const unitSection = document.getElementById('unitSizeSection');
                        if (unitSection && data.unit) {
                            unitSection.style.display = 'flex';
                            
                            const unitSizeContent = document.getElementById('unitSizeContent');
                            const unitSizeToggle = document.getElementById('unitSizeToggle');
                            if (unitSizeContent && unitSizeToggle) {
                                unitSizeContent.classList.remove('collapsed');
                                unitSizeToggle.classList.remove('collapsed');
                                const icon = unitSizeToggle.querySelector('i');
                                if (icon) icon.className = 'fas fa-chevron-up';
                                
                                // Update toggle text
                                unitSizeToggle.innerHTML = 
                                    `${data.unit.group} - ${data.unit.size} - ${data.unit.price}/mo <i class="fas fa-chevron-up"></i>`;
                            }
                        }
                    }
                }).catch(error => {
                    console.error('Error restoring location data:', error);
                });
            }
        } catch (error) {
            console.error('Error restoring state:', error);
        }
    }
    
    window.scrollTo(0, 0);
}

function makeAnotherBooking() {
    sessionStorage.clear();
    window.location.href = base_url + '/mammoth-storage';
}

// ===== PAGE LOAD RESTORATION =====
window.addEventListener('DOMContentLoaded', function() {
   
    
    const bookingData = sessionStorage.getItem('bookingData');
    
    if (bookingData) {
        try {
            const data = JSON.parse(bookingData);
            // console.log('Restoring booking data:', data);
            
            // Restore storage type
            if (data.storageType) {
                selectedStorageType = data.storageType;
                const typeText = data.storageType === 'storage' ? 'Self Storage' : 
                                data.storageType === 'vehicle' ? 'Vehicle Storage' : 'Both Types';
                const dropdown = document.getElementById('storageTypeDropdown');
                if (dropdown) {
                    dropdown.innerHTML = typeText + ' <i class="fas fa-chevron-down"></i>';
                }
                
                document.querySelectorAll('.storage-type-btn-main').forEach(btn => {
                    btn.classList.remove('active');
                });
                
                renderLocations(data.storageType);
            }
            
            // Restore location
            if (data.locationCode) {
                selectedLocationCode = data.locationCode;
                const dropdown = document.getElementById('locationDropdown');
                if (dropdown) {
                    dropdown.innerHTML = data.locationName + ' <i class="fas fa-chevron-down"></i>';
                }
                
                const locationCard = document.querySelector(`[data-location-code="${data.locationCode}"]`);
                if (locationCard) {
                    locationCard.classList.add('selected');
                }
                
                // Fetch and render units
                fetchLocationData(data.locationCode).then(response => {
                    if (response && !response.error && response.data) {
                        const groupedData = groupUnitsBySize(response.data);
                        unitDataStore[data.locationCode] = groupedData;
                        renderUnits(data.locationCode, groupedData);
                        
                        // Show storage type selection section (3rd dropdown)
                        const storageTypeSection = document.getElementById('storageTypeSelectionSection');
                        if (storageTypeSection) {
                            storageTypeSection.style.display = 'flex';
                            
                            const content = document.getElementById('storageTypeSelectionContent');
                            const toggle = document.getElementById('storageTypeSelectionToggle');
                            if (content && toggle) {
                                content.classList.remove('collapsed');
                                toggle.classList.remove('collapsed');
                                const icon = toggle.querySelector('i');
                                if (icon) icon.className = 'fas fa-chevron-up';
                                
                                // Update the toggle text if storage type was selected
                                if (data.storageType) {
                                    const typeText = data.storageType === 'storage' ? 'Self Storage' : 'Vehicle Storage';
                                    toggle.innerHTML = typeText + ' <i class="fas fa-chevron-up"></i>';
                                }
                            }
                        }
                        
                        // Show unit section (4th dropdown)
                        const unitSection = document.getElementById('unitSizeSection');
                        if (unitSection) {
                            unitSection.style.display = 'flex';
                            
                            // If unit was selected, expand the section
                            if (data.unit) {
                                const unitSizeContent = document.getElementById('unitSizeContent');
                                const unitSizeToggle = document.getElementById('unitSizeToggle');
                                if (unitSizeContent && unitSizeToggle) {
                                    unitSizeContent.classList.remove('collapsed');
                                    unitSizeToggle.classList.remove('collapsed');
                                    const icon = unitSizeToggle.querySelector('i');
                                    if (icon) icon.className = 'fas fa-chevron-up';
                                }
                            }
                        }
                    }
                }).catch(error => {
                    console.error('Error restoring location data:', error);
                });
            }
            
            // Restore unit selection
            if (data.unit) {
                const toggle = document.getElementById('unitSizeToggle');
                if (toggle) {
                    toggle.innerHTML = 
                        `${data.unit.group} - ${data.unit.size} - ${data.unit.price}/mo <i class="fas fa-chevron-down"></i>`;
                }
            }
        } catch (error) {
            console.error('Error restoring booking data:', error);
        }
    }
    
    // Restore current step if exists
    const currentStep = sessionStorage.getItem('currentStep');
    if (currentStep) {
      
        loadStep(currentStep);
    }
});


// console.log('All functions registered:', {
//     toggleMainSection: typeof window.toggleMainSection,
//     toggleSection: typeof window.toggleSection,
//     selectStorageTypeMain: typeof window.selectStorageTypeMain,
//     selectStorageType: typeof window.selectStorageType,
//     selectLocationCard: typeof window.selectLocationCard,
//     filterBySize: typeof window.filterBySize,
//     filterByBedrooms: typeof window.filterByBedrooms,
//     openUnitModal: typeof window.openUnitModal,
//     closeModal: typeof window.closeModal,
//     loadMoreLocations: typeof window.loadMoreLocations,
//     loadMoreUnits: typeof window.loadMoreUnits
// });
</script>

<?php include(APPPATH . 'views/storage/mammoth-storage/partials/footer-steps.php'); ?>

</body>
</html>