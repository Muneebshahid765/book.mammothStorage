/**
 * Global Search Functionality for Mammoth Storage
 * Robust implementation with proper DOM change detection
 * Handles autocomplete across all booking steps
 * @version 3.0 - Fixed for all steps
 */

(function() {
    'use strict';

    // ===== CONFIGURATION =====
    const CONFIG = {
        QLD_BOUNDS: {
            north: -9.142,
            south: -29.178,
            east: 153.553,
            west: 137.996
        },
        AUTOCOMPLETE_INPUT_ID: 'autocomplete',
        USE_LOCATION_BTN_ID: 'use-current-location',
        SEARCH_BTN_CLASS: 'search-locations-btn',
        SPINNER_ID: 'spinner-overlay',
        REINIT_DELAY: 500,
        CHECK_INTERVAL: 1000 // Check for input every second
    };

    // ===== STATE MANAGEMENT =====
    let autocompleteInstance = null;
    let mutationObserver = null;
    let intervalChecker = null;
    let lastInputElement = null;
    let reinitTimeout = null;

    // Global variables for selected location
    window.selectedLat = null;
    window.selectedLng = null;
    window.selectedType = 'address';

    /**
     * Check if location is in Queensland
     */
    function isLocationInQLD(lat, lng, callback) {
        if (typeof google === 'undefined' || !google.maps) {
            console.error('[Search] Google Maps API not loaded');
            callback(false);
            return;
        }

        const geocoder = new google.maps.Geocoder();
        geocoder.geocode({ location: { lat, lng } }, function (results, status) {
            if (status === "OK" && results[0]) {
                const isQLD = results[0].address_components.some(comp =>
                    comp.long_name === "Queensland" || comp.short_name === "QLD"
                );
                callback(isQLD);
            } else {
                callback(false);
            }
        });
    }

    /**
     * Destroy existing autocomplete instance
     */
    function destroyAutocomplete() {
        if (autocompleteInstance) {
            console.log('[Search] Destroying previous autocomplete instance');
            try {
                // Clear the autocomplete instance
                google.maps.event.clearInstanceListeners(autocompleteInstance);
            } catch (e) {
                console.log('[Search] Error clearing listeners:', e);
            }
            autocompleteInstance = null;
        }
        lastInputElement = null;
    }

    /**
     * Check if input element has changed
     */
    function hasInputChanged() {
        const currentInput = document.getElementById(CONFIG.AUTOCOMPLETE_INPUT_ID);
        if (!currentInput) return false;
        if (currentInput !== lastInputElement) {
            console.log('[Search] Input element has changed, needs reinitialization');
            return true;
        }
        return false;
    }

    /**
     * Initialize Google Places autocomplete
     */
    function initAutocomplete() {
        const input = document.getElementById(CONFIG.AUTOCOMPLETE_INPUT_ID);
        if (!input) {
            console.log('[Search] Autocomplete input not found');
            return false;
        }

        // Check if this is the same input element
        if (input === lastInputElement && autocompleteInstance) {
            console.log('[Search] Autocomplete already attached to this input');
            return true;
        }

        // Check if Google Maps API is loaded
        if (typeof google === 'undefined' || !google.maps || !google.maps.places) {
            console.log('[Search] Google Maps API not ready, retrying...');
            setTimeout(initAutocomplete, 500);
            return false;
        }

        // Destroy previous instance
        destroyAutocomplete();

        console.log('[Search] Initializing autocomplete on input element...');

        try {
            // Create autocomplete with QLD restrictions
            const autocomplete = new google.maps.places.Autocomplete(input, {
                componentRestrictions: { country: "au" },
                bounds: new google.maps.LatLngBounds(
                    { lat: CONFIG.QLD_BOUNDS.south, lng: CONFIG.QLD_BOUNDS.west },
                    { lat: CONFIG.QLD_BOUNDS.north, lng: CONFIG.QLD_BOUNDS.east }
                ),
                strictBounds: true,
                fields: ['formatted_address', 'geometry', 'address_components']
            });

            // Store instance and input reference
            autocompleteInstance = autocomplete;
            lastInputElement = input;

            // Add place changed listener
            autocomplete.addListener("place_changed", function () {
                const place = autocomplete.getPlace();
                if (place.geometry) {
                    const address = place.formatted_address;
                    if (address && address.includes("QLD")) {
                        window.selectedLat = place.geometry.location.lat();
                        window.selectedLng = place.geometry.location.lng();
                        console.log("[Search] Selected Place (QLD):", address);
                    } else {
                        showAlert('warning', 'Outside Queensland', 'Please select a location in Queensland (QLD).');
                        input.value = '';
                        window.selectedLat = null;
                        window.selectedLng = null;
                    }
                }
            });

            console.log('[Search] ✓ Autocomplete initialized successfully');
            return true;

        } catch (error) {
            console.error('[Search] Error initializing autocomplete:', error);
            return false;
        }
    }

    /**
     * Show alert message (SweetAlert2 or fallback to native alert)
     */
    function showAlert(icon, title, text) {
        if (typeof Swal !== 'undefined') {
            Swal.fire({ icon, title, text, confirmButtonText: 'OK' });
        } else {
            alert(text);
        }
    }

    /**
     * Show/hide spinner
     */
    function toggleSpinner(show) {
        const spinner = document.getElementById(CONFIG.SPINNER_ID);
        if (spinner) {
            if (show) {
                spinner.classList.add('show');
            } else {
                spinner.classList.remove('show');
            }
        }
    }

    /**
     * Handle "Use Current Location" button click
     */
    function handleUseCurrentLocation(e) {
        e.preventDefault();
        toggleSpinner(true);

        if (!navigator.geolocation) {
            toggleSpinner(false);
            showAlert('error', 'Not Supported', 'Geolocation is not supported by your browser.');
            return;
        }

        navigator.geolocation.getCurrentPosition(
            function (position) {
                const lat = position.coords.latitude;
                const lng = position.coords.longitude;

                if (typeof google === 'undefined' || !google.maps) {
                    toggleSpinner(false);
                    showAlert('error', 'Error', 'Google Maps is not loaded. Please try again.');
                    return;
                }

                const geocoder = new google.maps.Geocoder();
                geocoder.geocode({ location: { lat, lng } }, function (results, status) {
                    toggleSpinner(false);

                    if (status === "OK" && results[0]) {
                        const address = results[0].formatted_address;
                        if (address.includes("QLD")) {
                            const input = document.getElementById(CONFIG.AUTOCOMPLETE_INPUT_ID);
                            if (input) input.value = address;
                            window.selectedLat = lat;
                            window.selectedLng = lng;
                            console.log("[Search] Current location set:", address);
                        } else {
                            showAlert('error', 'Outside Queensland', 'Current location is not in Queensland (QLD).');
                        }
                    } else {
                        showAlert('error', 'Location Error', 'Unable to retrieve your location address.');
                    }
                });
            },
            function () {
                toggleSpinner(false);
                showAlert('error', 'Permission Denied', 'Geolocation permission was denied.');
            }
        );
    }

    /**
     * Handle search button click
     */
    function handleSearchClick() {
        const searchInput = document.getElementById(CONFIG.AUTOCOMPLETE_INPUT_ID);
        const searchValue = searchInput ? searchInput.value.trim() : '';

        if (!searchValue) {
            showAlert('warning', 'Empty Input', 'Please enter a location.');
            return;
        }

        toggleSpinner(true);
        const baseUrl = window.base_url || '';
        // Detect if we are on the new booking page (has inline location cards)
        const inlineMode = !!document.getElementById('locationGrid');

        // If location was selected from autocomplete
        if (window.selectedLat !== null && window.selectedLng !== null) {
            isLocationInQLD(window.selectedLat, window.selectedLng, function (isQLD) {
                if (!isQLD) {
                    toggleSpinner(false);
                    showAlert('error', 'Outside Queensland', 'The selected location is not in Queensland (QLD).');
                    return;
                }

                // On the new booking page, apply search inline instead of redirecting
                if (inlineMode && typeof window.applySearchToLocations === 'function') {
                    // If user is on a later step, go back to the main step first
                    try {
                        const currentStep = window.sessionStorage && window.sessionStorage.getItem('currentStep');
                        if (currentStep && typeof window.goBackToMain === 'function') {
                            window.goBackToMain();
                        }
                    } catch (e) {
                        console.log('[Search] Unable to read currentStep from sessionStorage', e);
                    }

                    toggleSpinner(false);

                    // Small delay so DOM can switch back to Step 1 before filtering locations
                    setTimeout(function() {
                        window.applySearchToLocations(window.selectedLat, window.selectedLng);
                    }, 100);
                } else {
                    window.location.href = baseUrl + `/locations/?query=${encodeURIComponent(searchValue)}&type=${window.selectedType}&lat=${window.selectedLat}&lng=${window.selectedLng}`;
                }
            });
            return;
        }

        // Try to use current location if available
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(
                function (position) {
                    const lat = position.coords.latitude;
                    const lng = position.coords.longitude;

                    isLocationInQLD(lat, lng, function (isQLD) {
                        if (!isQLD) {
                            toggleSpinner(false);
                            showAlert('error', 'Outside Queensland', 'Your current location is not in Queensland (QLD).');
                            return;
                        }

                        // On the new booking page, apply search inline instead of redirecting
                        if (inlineMode && typeof window.applySearchToLocations === 'function') {
                            // If user is on a later step, go back to the main step first
                            try {
                                const currentStep = window.sessionStorage && window.sessionStorage.getItem('currentStep');
                                if (currentStep && typeof window.goBackToMain === 'function') {
                                    window.goBackToMain();
                                }
                            } catch (e) {
                                console.log('[Search] Unable to read currentStep from sessionStorage', e);
                            }

                            toggleSpinner(false);

                            // Small delay so DOM can switch back to Step 1 before filtering locations
                            setTimeout(function() {
                                window.applySearchToLocations(lat, lng);
                            }, 100);
                        } else {
                            window.location.href = baseUrl + `/locations/?query=${encodeURIComponent(searchValue)}&type=${window.selectedType}&lat=${lat}&lng=${lng}`;
                        }
                    });
                },
                function () {
                    toggleSpinner(false);
                    showAlert('error', 'Permission Denied', 'Geolocation permission was denied. Please select a location from the dropdown.');
                }
            );
        } else {
            toggleSpinner(false);
            showAlert('error', 'Location Required', 'Please select a location from the dropdown suggestions.');
        }
    }

    /**
     * Attach event listeners to buttons
     */
    function attachEventListeners() {
        // Use Current Location button
        const useLocationBtn = document.getElementById(CONFIG.USE_LOCATION_BTN_ID);
        if (useLocationBtn && !useLocationBtn.hasAttribute('data-listener-attached')) {
            useLocationBtn.addEventListener('click', handleUseCurrentLocation);
            useLocationBtn.setAttribute('data-listener-attached', 'true');
            console.log('[Search] Attached "Use Current Location" listener');
        }

        // Search button
        const searchBtn = document.querySelector('.' + CONFIG.SEARCH_BTN_CLASS);
        if (searchBtn && !searchBtn.hasAttribute('data-listener-attached')) {
            searchBtn.addEventListener('click', handleSearchClick);
            searchBtn.setAttribute('data-listener-attached', 'true');
            console.log('[Search] Attached search button listener');
        }
    }

    /**
     * Debounced reinitialization
     */
    function scheduleReinit() {
        if (reinitTimeout) {
            clearTimeout(reinitTimeout);
        }
        reinitTimeout = setTimeout(function() {
            console.log('[Search] Scheduled reinitialization triggered');
            initAutocomplete();
            attachEventListeners();
            reinitTimeout = null;
        }, CONFIG.REINIT_DELAY);
    }

    /**
     * Setup MutationObserver to detect DOM changes
     */
    function setupMutationObserver() {
        if (mutationObserver) {
            console.log('[Search] MutationObserver already exists');
            return;
        }

        const targetNode = document.body;
        const config = {
            childList: true,
            subtree: true,
            attributes: false
        };

        mutationObserver = new MutationObserver(function(mutationsList) {
            for (let mutation of mutationsList) {
                if (mutation.type === 'childList') {
                    // Check if our input exists
                    const input = document.getElementById(CONFIG.AUTOCOMPLETE_INPUT_ID);
                    if (input && hasInputChanged()) {
                        console.log('[Search] DOM mutation detected, input changed');
                        scheduleReinit();
                        break;
                    }
                }
            }
        });

        mutationObserver.observe(targetNode, config);
        console.log('[Search] MutationObserver setup complete');
    }

    /**
     * Setup interval checker as backup
     */
    function setupIntervalChecker() {
        if (intervalChecker) {
            console.log('[Search] Interval checker already exists');
            return;
        }

        intervalChecker = setInterval(function() {
            const input = document.getElementById(CONFIG.AUTOCOMPLETE_INPUT_ID);
            if (input && hasInputChanged()) {
                console.log('[Search] Interval check: Input changed, reinitializing...');
                initAutocomplete();
                attachEventListeners();
            }
        }, CONFIG.CHECK_INTERVAL);

        console.log('[Search] Interval checker setup complete (checking every ' + CONFIG.CHECK_INTERVAL + 'ms)');
    }

    /**
     * Initialize all search functionality
     */
    function initializeSearch() {
        console.log('[Search] Initializing global search system...');

        // Wait for Google Maps to be available
        if (typeof google === 'undefined' || !google.maps || !google.maps.places) {
            console.log('[Search] Google Maps not ready, retrying in 500ms...');
            setTimeout(initializeSearch, 500);
            return;
        }

        // Initialize autocomplete
        initAutocomplete();
        
        // Attach event listeners
        attachEventListeners();

        // Setup MutationObserver for automatic reinitialization
        setupMutationObserver();

        // Setup interval checker as backup
        setupIntervalChecker();

        console.log('[Search] ✓ Global search system initialized');
    }

    /**
     * Expose manual reinitialization function globally
     */
    window.initHeaderAutocomplete = function() {
        console.log('[Search] ⚠ Manual reinitialization requested');
        scheduleReinit();
    };

    /**
     * Expose cleanup function
     */
    window.cleanupHeaderAutocomplete = function() {
        console.log('[Search] Cleanup requested');
        
        // Clear timers
        if (reinitTimeout) {
            clearTimeout(reinitTimeout);
            reinitTimeout = null;
        }
        if (intervalChecker) {
            clearInterval(intervalChecker);
            intervalChecker = null;
        }
        
        // Disconnect observer
        if (mutationObserver) {
            mutationObserver.disconnect();
            mutationObserver = null;
        }
        
        // Destroy autocomplete
        destroyAutocomplete();
    };

    /**
     * Force immediate reinitialization (for debugging)
     */
    window.forceReinitAutocomplete = function() {
        console.log('[Search] ⚡ Force reinitialization');
        destroyAutocomplete();
        initAutocomplete();
        attachEventListeners();
    };

    // Initialize when DOM is ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initializeSearch);
    } else {
        // DOM already loaded
        initializeSearch();
    }

    console.log('[Search] Global search module loaded');

})();
