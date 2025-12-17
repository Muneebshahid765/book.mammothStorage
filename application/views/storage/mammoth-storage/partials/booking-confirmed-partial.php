<style>
    .confirmation-container {
        padding: 60px 15px 80px;
        background-color: #f5f7fb;
    }

    .confirmation-card {
        background: #ffffff;
        border-radius: 16px;
        padding: 32px 32px 36px;
        max-width: 720px;
        margin: 0 auto;
        box-shadow: 0 12px 30px rgba(15, 23, 42, 0.08);
        text-align: left;
        border-top: 4px solid #28a745;
    }

    .success-icon {
        font-size: 3.5rem;
        color: #28a745;
        margin-bottom: 12px;
        text-align: center;
    }

    .confirmation-card h1 {
        font-size: 2rem;
        font-weight: 600;
        color: #2c3e50;
        margin-bottom: 6px;
        text-align: center;
    }

    .confirmation-card > p {
        font-size: 1rem;
        color: #6c757d;
        margin-bottom: 20px;
        text-align: center;
    }

    .booking-id {
        margin: 20px 0 10px;
        padding: 12px 16px;
        background-color: #fff5f5;
        border-radius: 8px;
        display: flex;
        justify-content: center;
        align-items: center;
    }

    .booking-id h3 {
        font-size: 1rem;
        color: #495057;
        margin: 0;
        font-weight: 500;
    }

    .booking-id .id-number {
        font-size: 1.1rem;
        font-weight: 700;
        color: #dc3545;
        font-family: 'Courier New', monospace;
        margin-left: 6px;
    }

    .info-section {
        background-color: #f8f9fa;
        border-radius: 10px;
        padding: 20px 22px;
        margin: 18px 0;
        text-align: left;
        border-left: 4px solid #e9ecef;
    }

    .info-section h3 {
        font-size: 1.1rem;
        font-weight: 600;
        color: #2c3e50;
        margin-bottom: 18px;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .info-section h3 i {
        color: #dc3545;
        font-size: 1.1rem;
    }

    .info-section p {
        font-size: 0.95rem;
        color: #495057;
        margin-bottom: 8px;
        line-height: 1.7;
    }

    .info-section p strong {
        display: inline-block;
        min-width: 140px;
        font-weight: 600;
        color: #2c3e50;
    }

    .info-section p span {
        font-weight: 400;
        color: #343a40;
    }

    .info-section p:last-child {
        margin-bottom: 0;
    }

    .info-section--booking {
        border-left-color: #dc3545;
    }

    .info-section--email {
        border-left-color: #007bff;
    }

    .info-section--help {
        border-left-color: #28a745;
    }

    .btn-new-booking {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        background-color: #dc3545;
        color: white;
        text-decoration: none;
        padding: 14px 40px;
        font-size: 1rem;
        font-weight: 600;
        border-radius: 999px;
        transition: background-color 0.2s ease, box-shadow 0.2s ease, transform 0.2s ease;
        margin-top: 28px;
        border: none;
        box-shadow: 0 8px 20px rgba(220, 53, 69, 0.25);
    }

    .btn-new-booking:hover {
        background-color: #c82333;
        color: white;
        transform: translateY(-1px);
        box-shadow: 0 10px 24px rgba(220, 53, 69, 0.3);
    }

    @media (max-width: 768px) {
        .confirmation-container {
            padding: 40px 12px 50px;
        }

        .confirmation-card {
            padding: 28px 20px 32px;
        }

        .confirmation-card h1 {
            font-size: 1.6rem;
        }

        .booking-id h3 {
            font-size: 0.95rem;
        }

        .booking-id .id-number {
            font-size: 1rem;
        }

        .info-section {
            padding: 18px 18px;
        }

        .btn-new-booking {
            width: 100%;
        }
    }
</style>

<div class="container confirmation-container">
    <div class="confirmation-card">
        <div class="success-icon">
            <i class="fas fa-check-circle"></i>
        </div>

        <h1>Booking Confirmed!</h1>
        <p>Thank you for choosing Mammoth Storage. Your reservation has been successfully confirmed.</p>

        <div class="booking-id">
            <h3>Your Tenant ID: <span class="id-number" id="tenantId">-</span></h3>
        </div>

        <div class="info-section info-section--booking">
            <h3><i class="fas fa-info-circle"></i> Booking Details</h3>
            <p><strong>Customer Name:</strong> <span id="customerName">-</span></p>
            <p><strong>Email:</strong> <span id="customerEmail">-</span></p>
            <p><strong>Location:</strong> <span id="locationName">-</span></p>
            <p><strong>Unit Number:</strong> <span id="unitNumber">-</span></p>
            <p><strong>Unit Size:</strong> <span id="unitSize">-</span></p>
             <p><strong>Floor:</strong> <span id="floorDisplay">-</span></p>
            <p><strong>Total Amount Paid:</strong> $<span id="totalAmount">-</span></p>
            <p><strong>Start Date:</strong> <span id="startDate">-</span></p>
            <p><strong>Building:</strong> <span id="building">-</span></p>
           
        </div>

        <div class="info-section">
            <h3><i class="fas fa-envelope"></i> Confirmation Email</h3>
            <p>A confirmation email with your booking details and access instructions has been sent to your email address.</p>
        </div>

        <div class="info-section">
            <h3><i class="fas fa-phone"></i> Need Help?</h3>
            <p>If you have any questions, please contact us at:</p>
            <p><strong>Phone:</strong> 1300 136 851</p>
            <p><strong>Email:</strong> hello@mammothstorage.com.au</p>
        </div>

        <a href="javascript:void(0);" class="btn-new-booking" onclick="makeAnotherBooking()">Make Another Booking</a>
    </div>
</div>

<script>
(function() {
    function formatDateToDDMMYYYY(isoDate) {
        if (!isoDate) return '-';
        const parts = isoDate.split('-');
        if (parts.length !== 3) return isoDate;
        const [year, month, day] = parts;
        return `${day}-${month}-${year}`;
    }

    function populateBookingConfirmation() {
        const confirmationData = sessionStorage.getItem('bookingConfirmation');
        
        if (confirmationData) {
            try {
                const data = JSON.parse(confirmationData);
                
                // Populate all fields
                document.getElementById('tenantId').textContent = data.tenantId || '-';
                document.getElementById('customerName').textContent = data.customerName || '-';
                document.getElementById('customerEmail').textContent = data.customerEmail || '-';
                document.getElementById('locationName').textContent = data.locationName || '-';
                document.getElementById('unitNumber').textContent = data.unitNumber || '-';
                document.getElementById('unitSize').textContent = data.unitSize || '-';

                // Prefer explicit totalAmount field if present; fall back to monthly
                // rate for older bookings that may not have totalAmount stored.
                var totalEl = document.getElementById('totalAmount');
                if (totalEl) {
                    var rawTotal = (data.totalAmount !== undefined && data.totalAmount !== null && data.totalAmount !== '')
                        ? data.totalAmount
                        : data.monthlyRate;

                    var parsed = parseFloat(rawTotal);
                    if (!isNaN(parsed)) {
                        totalEl.textContent = parsed.toFixed(2);
                    } else {
                        totalEl.textContent = '-';
                    }
                }
                document.getElementById('startDate').textContent = formatDateToDDMMYYYY(data.startDate || '');

                // Show block/building name from unitDesc if available
                const buildingEl = document.getElementById('building');
                if (buildingEl) {
                    let buildingBlock = data.unitDesc || data.building || '';
                    if (buildingBlock && buildingBlock.indexOf(',') !== -1) {
                        buildingBlock = buildingBlock.split(',')[0].trim();
                    }
                    buildingEl.textContent = buildingBlock || '-';
                }

                // Show floor using numeric floor value (e.g., Ground Floor or Floor 2)
                const floorEl = document.getElementById('floorDisplay');
                if (floorEl) {
                    let floorLabel = '-';
                    const floorNumber = data.floor;
                    if (floorNumber !== undefined && floorNumber !== null && floorNumber !== '') {
                        if (floorNumber === 0 || floorNumber === '0') {
                            floorLabel = 'Ground Floor';
                        } else {
                            floorLabel = `Floor ${floorNumber}`;
                        }
                    }
                    floorEl.textContent = floorLabel;
                }
                
                console.log('Booking confirmation data loaded:', data);
            } catch (error) {
                console.error('Error loading booking confirmation:', error);
            }
        } else {
            console.warn('No booking confirmation data found');
        }
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', populateBookingConfirmation);
    } else {
        populateBookingConfirmation();
    }
})();

function makeAnotherBooking() {
    // Clear all session storage
    sessionStorage.clear();
    
    // Go back to main page
    if (typeof goBackToMain === 'function') {
        goBackToMain();
    } else {
        location.reload();
    }
}
</script>
