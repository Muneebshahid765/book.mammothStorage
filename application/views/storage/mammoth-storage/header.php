<?php
// header.php - Reusable header and progress component with Google Places Search
// Usage: include('header.php');
// Set $currentStep variable before including this file

if (!isset($currentStep)) {
    $currentStep = 1;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f8f9fa;
            margin: 0;
            padding: 0;
        }

        .header-section {
            padding: 40px 0 30px;
            text-align: center;
            margin-bottom: 30px;
        }

        .header-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 15px;
        }

        .logo {
            font-size: 2rem;
            font-weight: bold;
            color: #dc3545;
            margin-bottom: 20px;
        }

        .header-section h1 {
            font-size: 2rem;
            font-weight: 600;
            color: #2c3e50;
            margin-bottom: 10px;
        }

        .header-section p {
            font-size: 1rem;
            color: #6c757d;
            margin-bottom: 20px;
        }

        .search-box-wrapper {
            max-width: 700px;
            margin: 0 auto;
            position: relative;
        }

        .search-box {
            display: flex;
            gap: 0;
            position: relative;
        }

        .search-box input {
            border-radius: 8px 0 0 8px;
            border: 2px solid #e0e0e0;
            padding: 12px 20px;
            font-size: 0.95rem;
            flex: 1;
        }

        .search-box input:focus {
            outline: none;
            border-color: #dc3545;
        }

        .btn-find {
            background-color: #dc3545;
            color: white;
            border: none;
            padding: 12px 30px;
            font-size: 0.95rem;
            font-weight: 600;
            border-radius: 0 8px 8px 0;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .btn-find:hover {
            background-color: #c82333;
        }

        .use-location-link {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            color: #dc3545;
            text-decoration: none;
            font-size: 0.9rem;
            margin-top: 10px;
            cursor: pointer;
        }

        .use-location-link:hover {
            color: #c82333;
            text-decoration: underline;
        }

        #spinner-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            z-index: 9999999;
            justify-content: center;
            align-items: center;
        }

        #spinner-overlay.show {
            display: flex;
        }

        .spinner {
            border: 5px solid #f3f3f3;
            border-top: 5px solid #dc3545;
            border-radius: 50%;
            width: 50px;
            height: 50px;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        @media (max-width: 768px) {
            .search-box {
                flex-direction: column;
            }

            .search-box input,
            .btn-find {
                border-radius: 8px;
            }
        }
    </style>
</head>
<body>
    <!-- Spinner Overlay -->
    <div id="spinner-overlay">
        <div class="spinner"></div>
    </div>

    <!-- Header Section -->
    <div class="header-section">
        <div class="header-container">
            <div class="logo">Mammoth Storage</div>
            <h1>Find Your Perfect Storage Solution</h1>
            <p>Choose from 8 convenient locations across Queensland</p>
            
            <div class="search-box-wrapper">
                <div class="search-box">
                    <input 
                        type="text" 
                        id="autocomplete"
                        class="form-control address-search" 
                        placeholder="Enter your suburb or postcode..." 
                    >
                    <button type="button" class="btn-find search-locations-btn">Find Closest</button>
                </div>
                <a href="#" id="use-current-location" class="use-location-link">
                    <i class="fas fa-map-marker-alt"></i> Use Current Location
                </a>
            </div>
        </div>
    </div>

    <!-- Load jQuery and other libraries FIRST -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- Google Maps JS API is loaded once globally in storage/templates/header.php -->

    <script>
        // Define base_url globally
        window.base_url = '<?php echo base_url(); ?>';
        // console.log('Header.php loaded, base_url:', window.base_url);
    </script>
    
    <!-- Global Search Functionality -->
    <script src="<?php echo base_url('assets/js/global-search.js'); ?>"></script>

<!-- Body continues in main content files -->