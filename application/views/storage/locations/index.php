<div class="container py-5">
    <div id="locations-container"></div>

    <div class="modal fade" id="allSizesModal" tabindex="-1" aria-labelledby="allSizesModalLabel" aria-hidden="true" style="z-index:99999;">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <!-- Modal Header -->
                <div class="modal-header text-dark">
                    <div class="d-flex align-items-center">
                        <i class="bi bi-list-check fs-3 me-3"></i> <!-- Icon for the title -->
                        <div>
                            <h4 class="modal-title mb-0 text-dark" id="allSizesModalLabel">Mammoth Storage Sizes</h4>
                            <small class="text-dark">Explore all storage sizes offered at this location</small>
                        </div>
                    </div>
                    <button type="button" class="btn-close text-white " data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <!-- Modal Body -->
                <div class="modal-body" id="all-sizes-content">
                    <div class="text-center mb-4">
                        <i class="bi bi-grid-fill fs-1 text-muted"></i> <!-- Large icon for decorative purposes -->
                        <p class="text-muted">Sizes will be dynamically populated here. Please wait or contact us for more information.</p>
                    </div>
                    <!-- Dynamically populated sizes will appear below -->
                </div>
            </div>
        </div>
    </div>




</div>

<script src="<?php echo base_url('assets/js/ajax/storage/locations/units.js'); ?>?time=<?php echo time(); ?>"></script>
