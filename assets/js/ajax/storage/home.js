$(document).ready(function () {
    let selectedType = "storage"; // Default type

    let selectedLat = null;
    let selectedLng = null;

    const input = document.getElementById("autocomplete");

    // Initialize Google Places Autocomplete
    function initAutocomplete() {

        const qldBounds = new google.maps.LatLngBounds(
            { lat: -29.178, lng: 137.996 }, // Southwest corner of QLD
            { lat: -9.142, lng: 153.553 }   // Northeast corner of QLD
        );

        const autocomplete = new google.maps.places.Autocomplete(input, {
            componentRestrictions: { country: "au" },
            bounds: qldBounds,
            strictBounds: true
        });

        autocomplete.addListener("place_changed", function () {
            const place = autocomplete.getPlace();
            if (place.geometry) {

                const address = place.formatted_address;
                if (address.includes("QLD")) {
                    selectedLat = place.geometry.location.lat();
                    selectedLng = place.geometry.location.lng();
                    // console.log("Selected Place (QLD only):", address);
                    // console.log("Location:", `(${selectedLat}, ${selectedLng})`);
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Invalid Location',
                        text: 'Please select an address in Queensland (QLD).',
                        confirmButtonText: 'OK'
                    });
                    input.value = ""; // reset input
                }
            }
        });
    }

    function isLocationInQLD(lat, lng, callback) {
        const geocoder = new google.maps.Geocoder();
        const latlng = { lat, lng };

        geocoder.geocode({ location: latlng }, function (results, status) {
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


    // Trigger Search Button
    $(".search-locations-btn2").click(function () {
        const address = $("#autocomplete").val();
        console.log("Search address:", address);
        // Optionally trigger geocoding here or call backend
    });

    // Use current location
    $("#use-current-location").click(function (e) {
        e.preventDefault();

        $("#spinner-overlay").addClass('show');

        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(function (position) {
                const lat = position.coords.latitude;
                const lng = position.coords.longitude;
                console.log("Current location:", lat, lng);

                const geocoder = new google.maps.Geocoder();
                const latlng = { lat, lng };
                geocoder.geocode({ location: latlng }, function (results, status) {
                    $("#spinner-overlay").removeClass('show');

                    if (status === "OK" && results[0]) {
                        const address = results[0].formatted_address;

                        // ✅ Check if address belongs to QLD
                        if (address.includes("QLD")) {
                            $("#autocomplete").val(address);
                            selectedLat = lat;
                            selectedLng = lng;
                            console.log("Detected QLD address:", address);
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Outside Queensland',
                                text: 'Current location is not in Queensland (QLD).',
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
                        }
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Location Error',
                            text: 'No address found for your current location.',
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
                    }
                });

            }, function () {

                $("#spinner-overlay").hide();

                Swal.fire({
                    icon: 'error',
                    title: 'Permission Denied',
                    text: 'Geolocation permission was denied. Please allow access to use current location.',
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

            });
        } else {

            $("#spinner-overlay").hide();

            Swal.fire({
                icon: 'error',
                title: 'Not Supported',
                text: 'Geolocation is not supported by your browser.',
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
    });

    // Initialize on load
    if (input && typeof google !== 'undefined' && google.maps && google.maps.places) {
        initAutocomplete();
    } else {
        console.error('Google Maps API not loaded or autocomplete input not found');
    }

    // Handle tab switching to update selected type
    $('#storageTabs button').on('click', function () {

        // Remove the custom class from all tabs
        $('#storageTabs button').removeClass('storage-tab-custom-selected');

        // Add the custom class to the clicked tab
        $(this).addClass('storage-tab-custom-selected');

        selectedType = $(this).data('bs-target') === "#storage-unit" ? "storage" : "vehicle";

        // alert(selectedType);
    });

    // Main search button
    $('.search-locations-btn').click(function () {
        const searchValue = $('#autocomplete').val().trim();

        if (!searchValue) {
            Swal.fire({
                icon: 'warning',
                title: 'Empty Input',
                text: 'Please enter a location.',
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
            return;
        }

        $("#spinner-overlay").addClass('show');


        // ✅ QLD validation for autocomplete case
        if (selectedLat !== null && selectedLng !== null) {
            isLocationInQLD(selectedLat, selectedLng, function (isQLD) {
                if (!isQLD) {
                    $("#spinner-overlay").removeClass('show');
                    Swal.fire({
                        icon: 'error',
                        title: 'Outside Queensland',
                        text: 'The selected location is not in Queensland (QLD).',
                        confirmButtonText: 'OK'
                    });
                    return; // ❌ stop
                }

                // ✅ Only if in QLD → redirect
                window.location.href = base_url + `/locations/?query=${encodeURIComponent(searchValue)}&type=${selectedType}&lat=${selectedLat}&lng=${selectedLng}`;
            });
            return; // stop here so your original code won’t trigger right away
        }

        if (navigator.geolocation) {
            // Fallback: use current location
            navigator.geolocation.getCurrentPosition(function (position) {
                const lat = position.coords.latitude;
                const lng = position.coords.longitude;

                isLocationInQLD(lat, lng, function (isQLD) {
                    if (!isQLD) {
                        $("#spinner-overlay").removeClass('show');
                        Swal.fire({
                            icon: 'error',
                            title: 'Outside Queensland',
                            text: 'Your current location is not in Queensland (QLD).',
                            confirmButtonText: 'OK'
                        });
                        return; // ❌ stop
                    }

                    // ✅ Only if in QLD → redirect
                    window.location.href = base_url + `/locations/?query=${encodeURIComponent(searchValue)}&type=${selectedType}&lat=${lat}&lng=${lng}`;
                });


            }, function () {
                // window.location.href = base_url + `/locations/?query=${encodeURIComponent(searchValue)}&type=${selectedType}`;

                $("#spinner-overlay").hide();
                Swal.fire({
                    icon: 'error',
                    title: 'Permission Denied',
                    text: 'Geolocation permission was denied.',
                    confirmButtonText: 'OK'
                });

            });
        } else {
            // No location available
            // window.location.href = base_url + `/locations/?query=${encodeURIComponent(searchValue)}&type=${selectedType}`;

            // No location available
            $("#spinner-overlay").hide();
            Swal.fire({
                icon: 'error',
                title: 'Location Required',
                text: 'No location available and cannot verify Queensland.',
                confirmButtonText: 'OK'
            });
        }
    });


    // List of addresses
    const addresses = [
        { address: "Shop 14 /380 Mons Road, Forest Glen QLD 4556", type: "storage" }, // L001
        { address: "31/82 Sugar Rd, Maroochydore QLD 4558, Australia", type: "storage" }, // L002
        { address: "33-49 Zealey Rd, Nambour QLD 4560", type: "vehicle" }, // L003
        { address: "40-42 Geo Hawkins Cres, Bells Creek QLD 4551", type: "storage" }, // L004
        { address: "34 Southern Cross Circuit Urangan", type: "vehicle" }, // L005
        { address: "38 Commerical Street, Kensington QLD", type: "storage" } // L006
    ];

    // Handle input event to show suggestions
    /* $(".address-search").on("input", function () {
         const query = $(this).val().toLowerCase();
         const $dropdown = $(this).siblings(".suggestion-dropdown");
         $dropdown.empty(); // Clear previous suggestions

         if (query) {
             // Filter addresses based on the query and selected type
             const suggestions = addresses.filter(
                 item => item.type === selectedType && item.address.toLowerCase().includes(query)
             );

             if (suggestions.length) {
                 suggestions.forEach(item => {
                     $dropdown.append(`<div class="dropdown-item">${item.address}</div>`);
                 });
                 $dropdown.show(); // Show the dropdown
             } else {
                 $dropdown.hide(); // Hide if no matches
             }
         } else {
             $dropdown.hide(); // Hide if query is empty
         }
     });*/

    // Handle click on a suggestion
    $(document).on("click", ".suggestion-dropdown .dropdown-item", function () {
        const selectedAddress = $(this).text();
        $(".address-search").val(selectedAddress); // Set the input field value
        $(".suggestion-dropdown").hide(); // Hide the dropdown
    });

    // Hide dropdown when clicking outside
    $(document).on("click", function (e) {
        if (!$(e.target).closest(".address-search").length) {
            $(".suggestion-dropdown").hide();
        }
    });
});
