<style>
    .footer-steps {
        position: fixed;
        bottom: 0;
        left: 0;
        right: 0;
        background: white;
        padding: 20px 0;
        box-shadow: 0 -2px 10px rgba(0,0,0,0.1);
        z-index: 1000;
    }

    .footer-steps-container {
        display: flex;
        justify-content: center;
        align-items: center;
        gap: 15px;
        flex-wrap: wrap;
        padding: 0 20px;
    }

    .footer-step {
        display: flex;
        align-items: center;
        gap: 10px;
        background: #e9ecef;
        padding: 6px 20px;
        border-radius: 50px;
        transition: all 0.3s;
    }

    .footer-step.active {
        background: #dc3545;
        color: white;
    }

    .footer-step.completed {
         background-color: #28a745;
         color: white;
    }

    .footer-step-number {
           width: 20px;
    height: 20px;
    border-radius: 50%;
    background: white;
    color: #6c757d;
    display: flex;
    align-items: center;
    justify-content: center;
    /* font-weight: 600; */
    font-size: 0.95rem;
}

    .footer-step.active .footer-step-number {
        background: white;
        color: #dc3545;
    }

    .footer-step-label {
        font-size: 0.95rem;
        font-weight: 500;
        white-space: nowrap;
    }

    .footer-step.active .footer-step-label {
        color: white;
    }

    /* Add padding to body to prevent content from being hidden behind footer */
    body {
        padding-bottom: 100px;
    }

    @media (max-width: 768px) {
        .footer-step-label {
            display: none;
        }
        
        .footer-step {
            padding: 10px 15px;
        }
         .footer-steps-container {
        
        gap: 0px;
        flex-wrap: normalizer_normalize;
        padding: 0 0px;
    }
        
        body {
            padding-bottom: 80px;
        }
    }
</style>

<div class="footer-steps" id="footerSteps">
    <div class="footer-steps-container">
        <div class="footer-step" data-step="1">
            <div class="footer-step-number">1</div>
            <span class="footer-step-label">Location & Unit</span>
        </div>

        <div class="footer-step" data-step="2">
            <div class="footer-step-number">2</div>
            <span class="footer-step-label">Personal Info</span>
        </div>

        <div class="footer-step" data-step="3">
            <div class="footer-step-number">3</div>
            <span class="footer-step-label">Additional Details</span>
        </div>

        <div class="footer-step" data-step="4">
            <div class="footer-step-number">4</div>
            <span class="footer-step-label">Insurance & Terms</span>
        </div>

        <div class="footer-step" data-step="5">
            <div class="footer-step-number">5</div>
            <span class="footer-step-label">Payment</span>
        </div>
    </div>
</div>

<script>
// Function to update footer steps based on current step
function updateFooterSteps(currentStep) {
    const steps = document.querySelectorAll('.footer-step');
    
    steps.forEach(step => {
        const stepNumber = parseInt(step.getAttribute('data-step'));
        const stepNumberElement = step.querySelector('.footer-step-number');
        
        // Remove all classes
        step.classList.remove('active', 'completed');
        
        if (stepNumber < currentStep) {
            // Completed step
            step.classList.add('completed');
            stepNumberElement.innerHTML = `<i class="fas fa">${stepNumber}</i>`;
        } else if (stepNumber === currentStep) {
            // Active step
            step.classList.add('active');
            stepNumberElement.textContent = stepNumber;
        } else {
            // Future step
            stepNumberElement.textContent = stepNumber;
        }
    });
}

// Initialize footer on page load
(function() {
    // Check if we're on a step page or main page
    const currentStepValue = <?php echo isset($currentStep) ? $currentStep : 1; ?>;
    updateFooterSteps(currentStepValue);
})();
</script>
