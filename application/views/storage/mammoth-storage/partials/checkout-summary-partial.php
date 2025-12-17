<style>
    .ms-checkout-summary-card {
        background-color: #f8f9fa;
        border-radius: 16px;
        padding: 24px 24px 20px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

    .ms-checkout-summary-card .box-heading {
        font-size: 1.4rem;
        font-weight: 700;
        color: #dc3545;
        margin: 0;
    }

    .ms-checkout-summary-card .textmuted {
        color: #6c757d;
        font-size: 0.85rem;
        margin: 0;
    }

    .ms-checkout-summary-card .summary-table-header {
        background-color: #f1f3f5;
        font-weight: 600;
        font-size: 0.9rem;
    }

    .ms-checkout-summary-card .h8 {
        font-size: 0.8rem;
    }

    .ms-checkout-summary-card .summary-row {
        border-bottom: 1px solid #dee2e6;
    }

    .ms-checkout-summary-card .summary-total-row {
        font-weight: 600;
    }

    .ms-checkout-summary-card .recurring-card {
        border-radius: 8px;
        border: 1px solid #dee2e6;
        background-color: #ffffff;
    }

    .ms-checkout-summary-card .icon-info {
        font-size: 0.85rem;
    }

    /* Keep money values on a single line so $ and amount don't wrap */
    #msSummaryUnitPrice,
    #msSummaryInsurancePrice,
    #msSummaryUnitTotal,
    #msSummaryInsuranceTotal,
    #msSummaryTodayCharge,
    #msSummaryRecurringCharge {
        white-space: nowrap;
        font-variant-numeric: tabular-nums;
    }

    /* Slightly smaller font in numeric columns to handle larger amounts */
    #msSummaryUnitPrice,
    #msSummaryInsurancePrice,
    #msSummaryUnitTotal,
    #msSummaryInsuranceTotal {
        font-size: 0.8rem;
    }

    /* Storage unit text: keep to a single line with ellipsis so row height stays consistent */
    .ms-checkout-summary-card .col-6.h8.pe-0.ps-2 span {
        display: block;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    @media (max-width: 992px) {
        .ms-checkout-summary-wrapper {
            margin-top: 20px;
        }
    }
</style>

<div class="ms-checkout-summary-wrapper">
    <div class="ms-checkout-summary-card">
        <p class="textmuted">Order</p>
        <h4 class="box-heading">Checkout Summary</h4>
        <p class="textmuted h8 mb-2 facility_name" id="msSummaryFacilityName"></p>

        <div class="h8">
            <div class="row m-0 border mb-3">
                <div class="col-6 h8 pe-0 ps-2">
                    <p class="textmuted py-2">Storage Unit</p>
                    <span class="d-block py-2 border-bottom" id="msSummaryUnitSize">-</span>
                    <span class="d-block py-2 border-bottom" id="msSummaryInsuranceLabel">Goods Protection</span>
                </div>
                <div class="col-2 text-center p-0">
                    <p class="textmuted p-2">Qty</p>
                    <span class="d-block p-2 border-bottom" id="msSummaryUnitQty">1</span>
                    <span class="d-block p-2 border-bottom" id="msSummaryInsuranceQty">1</span>
                </div>
                <div class="col-2 p-0 text-center h8 border-end">
                    <p class="textmuted p-2">Price</p>
                    <span class="d-block border-bottom py-2" id="msSummaryUnitPrice">
                        <span class="fas fa-dollar-sign"></span>0.00
                    </span>
                    <span class="d-block border-bottom py-2" id="msSummaryInsurancePrice">
                        <span class="fas fa-dollar-sign"></span>0.00
                    </span>
                </div>
                <div class="col-2 p-0 text-center">
                    <p class="textmuted p-2">Total</p>
                    <span class="d-block py-2 border-bottom" id="msSummaryUnitTotal">
                        <span class="fas fa-dollar-sign"></span>0.00
                    </span>
                    <span class="d-block py-2 border-bottom" id="msSummaryInsuranceTotal">
                        <span class="fas fa-dollar-sign"></span>0.00
                    </span>
                </div>
            </div>

            <div class="d-flex h7 mb-2">
                <p class="mb-0">Today's Charge:</p>
                <p class="ms-auto mb-0" id="msSummaryTodayCharge">
                    <span class="fas fa-dollar-sign"></span>0.00
                </p>
            </div>

            <div class="card mb-3 recurring-card">
                <div class="card-body">
                    <div class="d-flex h7 mb-2 align-items-center">
                        <i class="fas fa-calendar-alt me-2"></i>
                        <p class="mb-0">Recurring Charges</p>
                        <p class="ms-auto mb-0" id="msSummaryRecurringCharge">
                            <span class="fas fa-dollar-sign"></span>0.00
                        </p>
                    </div>
                    <div class="d-flex align-items-center">
                        <i class="fas fa-info-circle me-2 icon-info"></i>
                        <p class="text-muted mb-0">
                            Your monthly total charges, including unit and insurance costs, for a snapshot of your future expenses.
                        </p>
                    </div>
                </div>
            </div>

            <div class="h8 mb-1">
                <p class="textmuted mb-0">Thank you for your purchase. If you need assistance, we’re here to help!</p>
            </div>
        </div>
    </div>
</div>

<script>
(function() {
    function parseAmount(value) {
        const n = parseFloat(value);
        return isNaN(n) ? 0 : n;
    }

    window.updateCheckoutSummaryFromSession = function() {
        try {
            const raw = sessionStorage.getItem('bookingData');
            if (!raw) return;
            const data = JSON.parse(raw);

            // Read any saved insurance terms (from Insurance & Terms step)
            const rawInsurance = sessionStorage.getItem('insuranceTerms') || '{}';
            let insuranceTerms = {};
            try {
                insuranceTerms = JSON.parse(rawInsurance);
            } catch (e) {
                insuranceTerms = {};
            }

            const insuranceOption = insuranceTerms.insurance || '';
            const insurancePremium = parseAmount(insuranceTerms.premium || '0');
            const coverageValue = parseAmount(insuranceTerms.coverage || '0');
            const hasStoreProtect = insuranceOption === 'storeProtect' && insurancePremium > 0;

            // Build a simple label like "Store Protect 30k" based on coverage
            let coverageLabel = '';
            if (hasStoreProtect && coverageValue >= 1000) {
                const k = coverageValue / 1000;
                const kStr = Number.isInteger(k) ? k.toString() : k.toFixed(1);
                // coverageLabel = `Store Protect ${kStr}k`;
            }

            const insuranceLabelEl = document.getElementById('msSummaryInsuranceLabel');
            if (insuranceLabelEl) {
                insuranceLabelEl.textContent = coverageLabel
                    ? `Goods Protection `
                    : 'Goods Protection';
            }

            const facilityEl = document.getElementById('msSummaryFacilityName');
            if (facilityEl) {
                facilityEl.textContent = data.locationName || '';
            }

            const unitSizeEl = document.getElementById('msSummaryUnitSize');
            if (unitSizeEl) {
                let unitSize = data.unit?.size || '';

                // Prefer human-readable floor/type label from booking data (e.g. 'Drive up Access', 'Ground Floor')
                let floorLabel = (data.unit && (data.unit.floorLabel || data.unit.building)) || '';

                // Fallback to numeric floor only if no label string is available
                if (!floorLabel && data.unit && data.unit.floor !== undefined && data.unit.floor !== null && data.unit.floor !== '') {
                    const floorNumber = data.unit.floor;
                    if (floorNumber === 0 || floorNumber === '0') {
                        floorLabel = 'Ground Floor';
                    } else {
                        floorLabel = `Floor ${floorNumber}`;
                    }
                }

                if (floorLabel) {
                    if (unitSize) {
                        unitSize = `${unitSize}, ${floorLabel}`;
                    } else {
                        unitSize = floorLabel;
                    }
                }

                unitSizeEl.textContent = unitSize || '-';
            }

            const price = parseAmount(data.unit?.price || '0');
            const originalPrice = parseAmount(data.unit?.originalPrice || price) || price;

            // Derive special behaviour when either:
            // 1) BOTH a discount (percentage/fixed) and a free-months special
            //    apply (Buy X Get Y + discount), or
            // 2) A pure discount special applies for a fixed number of months
            //    (e.g. "50% discount for 3 months"). We rely on flags stored
            //    when the unit was selected.
            const monthsToPay = parseInt((data.unit && data.unit.monthsToPay) || '0', 10) || 0;
            const freeMonths = parseInt((data.unit && data.unit.freeMonths) || '0', 10) || 0;
            const discountMonths = parseInt((data.unit && data.unit.discountMonths) || '0', 10) || 0;
            const hasFreeMonthsSpecial = !!(data.unit && data.unit.hasFreeMonthsSpecial);
            const hasDiscountSpecial = !!(data.unit && data.unit.hasDiscountSpecial);

            let qtyDisplay = 1;
            let linePrice = price; // what we show in Price/Total on the summary row

            if (hasFreeMonthsSpecial && hasDiscountSpecial && monthsToPay > 0 && freeMonths > 0) {
                // Case 1: Percentage/fixed discount + Buy X Get Y (e.g. 50% off + 3 month pay 3 free)
                // Customer pays for monthsToPay at discounted rate, freeMonths are FREE
                qtyDisplay = monthsToPay + freeMonths;
                linePrice = price * monthsToPay; // Only pay for monthsToPay, free months are free
            } else if (hasDiscountSpecial && discountMonths > 0) {
                // Case 2: Discount for N months (no free-months special).
                qtyDisplay = discountMonths;
                linePrice = price * discountMonths;
            } else if (!hasDiscountSpecial && hasFreeMonthsSpecial && monthsToPay > 0 && freeMonths > 0) {
                // Case 3: Only Buy X Get Y (free_months) applies.
                qtyDisplay = monthsToPay + freeMonths;
                // price is the effective monthly rate over the X+Y months, so the
                // total amount paid is that rate multiplied by all months.
                linePrice = price * qtyDisplay;
            }

            const unitQtyEl = document.getElementById('msSummaryUnitQty');
            const insuranceQtyEl = document.getElementById('msSummaryInsuranceQty');
            const unitPriceEl = document.getElementById('msSummaryUnitPrice');
            const unitTotalEl = document.getElementById('msSummaryUnitTotal');

            if (unitQtyEl) {
                unitQtyEl.textContent = qtyDisplay.toString();
            }

            if (insuranceQtyEl) {
                insuranceQtyEl.textContent = qtyDisplay.toString();
            }

            if (unitPriceEl && unitTotalEl) {
                unitPriceEl.innerHTML = `<span class="fas fa-dollar-sign"></span>${linePrice.toFixed(2)}`;
                unitTotalEl.innerHTML = `<span class="fas fa-dollar-sign"></span>${linePrice.toFixed(2)}`;
            }

            const insurancePriceEl = document.getElementById('msSummaryInsurancePrice');
            const insuranceTotalEl = document.getElementById('msSummaryInsuranceTotal');
            if (insurancePriceEl && insuranceTotalEl) {
                if (hasStoreProtect) {
                    insurancePriceEl.innerHTML = `<span class="fas fa-dollar-sign"></span>${insurancePremium.toFixed(2)}`;
                    insuranceTotalEl.innerHTML = `<span class="fas fa-dollar-sign"></span>${insurancePremium.toFixed(2)}`;
                } else {
                    insurancePriceEl.innerHTML = '<span class="fas fa-dollar-sign"></span>0.00';
                    insuranceTotalEl.innerHTML = '<span class="fas fa-dollar-sign"></span>0.00';
                }
            }

            const todayChargeEl = document.getElementById('msSummaryTodayCharge');
            if (todayChargeEl) {
                // Today's Charge mirrors the line total for all multi-month
                // special scenarios so the customer sees the full upfront
                // amount when Percentage + Buy X Get Y or Discount-for-N-Months
                // or Buy X Get Y-only offers are active.
                let todaysAmount = price;
                if (
                    (hasFreeMonthsSpecial && hasDiscountSpecial && monthsToPay > 0 && freeMonths > 0) ||
                    (hasDiscountSpecial && discountMonths > 0) ||
                    (!hasDiscountSpecial && hasFreeMonthsSpecial && monthsToPay > 0 && freeMonths > 0)
                ) {
                    todaysAmount = linePrice;
                }

                if (hasStoreProtect) {
                    todaysAmount += insurancePremium;
                }

                todayChargeEl.innerHTML = `<span class="fas fa-dollar-sign"></span>${todaysAmount.toFixed(2)}`;
            }

            const recurringEl = document.getElementById('msSummaryRecurringCharge');
            if (recurringEl) {
                const recurringBase = originalPrice;
                const recurringTotal = hasStoreProtect
                    ? (recurringBase + insurancePremium)
                    : recurringBase;

                recurringEl.innerHTML = `<span class="fas fa-dollar-sign"></span>${recurringTotal.toFixed(2)}`;
            }
        } catch (e) {
            console.error('Error initialising checkout summary card:', e);
        }
    };

    // Initial render when the summary card loads
    window.updateCheckoutSummaryFromSession();
})();
</script>
