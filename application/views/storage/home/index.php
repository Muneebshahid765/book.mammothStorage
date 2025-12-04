<div class="container py-5">
    <!-- Header -->
    <div class="text-center mb-5">
        <h2 class="fw-bold" style="font-size: 3rem;">Tailored Storage Solutions</h2>
        <p class="text-secondary fs-4">Easily book online or get a quote</p>
    </div>

    <!-- Tab Section -->
    <div class="bg-custom-primary p-4 rounded shadow">
        <ul class="nav nav-tabs justify-content-center mb-4 border-0" id="storageTabs" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active fw-bold d-flex flex-column align-items-center storage-tab-custom-selected" id="storage-unit-tab" data-bs-toggle="tab" data-bs-target="#storage-unit" type="button" role="tab" aria-controls="storage-unit" aria-selected="true">
                    <i class="bi bi-box-seam fs-2 mb-1"></i>
                    Storage unit
                </button>
            </li>

            <li class="nav-item" role="presentation">
                <button class="nav-link active fw-bold d-flex flex-column align-items-center" id="vehicle-unit-tab" data-bs-toggle="tab" data-bs-target="#vehicle-unit" type="button" role="tab" aria-controls="vehicle-unit" aria-selected="true">
                    <i class="bi bi-car-front fs-2 mb-1"></i>
                    Vehicle unit
                </button>
            </li>
        </ul>

        <!-- Tab Content -->
        <div class="tab-content" id="storageTabsContent">
            <div class="tab-pane fade show active" id="storage-unit" role="tabpanel" aria-labelledby="storage-unit-tab">
                <h2 class="fw-bold text-white mb-3">Find storage near you</h2>
                <div>
                    <div class="row g-3 mb-3">
                        <!-- Input Field -->
                        <div class="col-12 col-sm position-relative">
                            <input type="text" id="autocomplete"  class="form-control form-control-lg address-search" placeholder="Enter suburb, city or postcode">
                            <div class="dropdown-menu w-100 suggestion-dropdown"></div>
                        </div>
                        <!-- Search Button -->
                        <div class="col-12 col-sm-auto">
                            <button class="btn btn-dark btn-lg w-100 px-4 search-locations-btn">
                                Search
                            </button>
                        </div>
                    </div>
                    <!-- Current Location Link -->
                    <a href="#" id="use-current-location" class="text-decoration-none text-white fw-bold">
                        <i class="bi bi-geo-alt-fill"></i> Or use current location
                    </a>
                </div>

            </div>
        </div>
    </div>
</div>

<script src="<?php echo base_url('assets/js/ajax/storage/home.js'); ?>?time=<?php echo time(); ?>"></script>