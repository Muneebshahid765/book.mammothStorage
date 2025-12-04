<!DOCTYPE html>
<html lang="en">

<head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name='robots' content='index, follow, max-image-preview:large, max-snippet:-1, max-video-preview:-1' />
    <meta name="description" content="Welcome to one of the Sunshine Coast&#039;s leading SELF STORAGE organisations. We provide the most affordable storage units starting at..." />

    <!-- This site is optimized with the Yoast SEO plugin v20.7 - https://yoast.com/wordpress/plugins/seo/ -->
    <title>Self Storage Units &amp; Sheds Sunshine Coast | Mammoth Storage Unit Information</title>
    <link rel="icon" href="<?php echo base_url('assets/images/logo.jpeg'); ?>" type="image/jpeg">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">


    <link rel="stylesheet" href="<?php echo base_url('assets/css/custom.css'); ?>">
    <link rel="stylesheet" href="<?php echo base_url('assets/css/form.css'); ?>">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.2/css/all.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <!-- SweetAlert2 CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
    <!-- Bootstrap Datepicker CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css">
    <!-- Font Awesome CSS for Tempus Dominus icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" />
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
    <link rel="stylesheet" href="<?php echo base_url('assets/css/theme.css'); ?>">




    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js"></script>
    <!-- Bootstrap JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/2.10.2/umd/popper.min.js"></script>
    <!-- Bootstrap Datepicker JavaScript -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>
    <!-- SweetAlert2 JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- Load all tab-specific JavaScript files -->
    <!-- Google Maps API with Places library -->
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAVfGb-g1V3gP1rHrvDB7N7HBv9Cmz9sQU&libraries=places"></script>
    <script>
        // Expose base_url only via the window object to avoid const/var redeclaration issues
        window.base_url = <?= json_encode(rtrim(base_url(), '/'), JSON_UNESCAPED_SLASHES); ?>;
    </script>

    <style>

        @import url('https://fonts.googleapis.com/css2?family=Poppins&display=swap');

        /* Make sure the datepicker fills the full width of its container */
        #inline-datepicker table {
            width: 100% !important;
            /* Full width of the container */
        }

        .datepicker-inline {

            width: 100% !important;
        }


        /* Background color */
        body {
            background-color: #f8f9fa;
            /* Silver-like background */
        }

        /* Spinner overlay */
        /* .spinner-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(255, 255, 255, 0.8);
            /* Light overlay with transparency */
            /* display: none;
            z-index: 9999999999999; */
        /* } */ */

        /* Centering content inside overlay */
        /* .spinner-content {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            text-align: center;
            background: #6c757d;
            padding: 20px;
            border-radius: 20px;
            border: 2px solid #6c757d !important;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.25)
        } */

        /* .spinner-text {
            margin-top: 10px;
            font-size: 1.2rem;
            color: white !important;
        } */

        /* .swal2-container {
            z-index: 9999999 !important;
            /* Ensure SweetAlert appears above Bootstrap modal */
        /* } */ 

        .swal-open {
            overflow: hidden;
            /* Prevent scrolling while the Swal modal is open */
        }

        .alert {
            border-radius: 0;
            -webkit-border-radius: 0;
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.11);
            display: table;
            width: 100%;
        }

        .alert-white {
            background-image: linear-gradient(to bottom, #fff, #f9f9f9);
            border-top-color: #d8d8d8;
            border-bottom-color: #bdbdbd;
            border-left-color: #cacaca;
            border-right-color: #cacaca;
            color: #404040;
            padding-left: 61px;
            position: relative;
        }

        .alert-white.rounded {
            border-radius: 3px;
            -webkit-border-radius: 3px;
        }

        .alert-white.rounded .icon {
            border-radius: 3px 0 0 3px;
            -webkit-border-radius: 3px 0 0 3px;
        }

        .alert-white .icon {
            text-align: center;
            width: 45px;
            height: 100%;
            position: absolute;
            top: 0;
            left: 0;
            border: 1px solid #bdbdbd;
            padding-top: 15px;
        }


        .alert-white .icon:after {
            -webkit-transform: rotate(45deg);
            -moz-transform: rotate(45deg);
            -ms-transform: rotate(45deg);
            -o-transform: rotate(45deg);
            transform: rotate(45deg);
            display: block;
            content: '';
            width: 10px;
            height: 10px;
            border: 1px solid #bdbdbd;
            position: absolute;
            border-left: 0;
            border-bottom: 0;
            top: 50%;
            right: -6px;
            margin-top: -3px;
            background: #fff;
        }

        .alert-white .icon i {
            font-size: 20px;
            color: #fff;
            left: 12px;
            margin-top: -10px;
            position: absolute;
            top: 50%;
        }

        /*============ colors ========*/
        .alert-success {
            color: #3c763d;
            background-color: #dff0d8;
            border-color: #d6e9c6;
        }

        .alert-white.alert-success .icon,
        .alert-white.alert-success .icon:after {
            border-color: #54a754;
            background: #60c060;
        }

        .alert-info {
            background-color: #d9edf7;
            border-color: #98cce6;
            color: #3a87ad;
        }

        .alert-white.alert-info .icon,
        .alert-white.alert-info .icon:after {
            border-color: #3a8ace;
            background: #4d90fd;
        }


        .alert-white.alert-warning .icon,
        .alert-white.alert-warning .icon:after {
            border-color: #d68000;
            background: #fc9700;
        }

        .alert-warning {
            background-color: #fcf8e3;
            border-color: #f1daab;
            color: #c09853;
        }

        .alert-danger {
            background-color: #f2dede;
            border-color: #e0b1b8;
            color: #b94a48;
        }

        .alert-white.alert-danger .icon,
        .alert-white.alert-danger .icon:after {
            border-color: #ca452e;
            background: #da4932;
        }
    </style>
</head>

<body>


    <div class="fusion-tb-header">
        <div class="fusion-fullwidth fullwidth-box fusion-builder-row-1 fusion-flex-container has-pattern-background has-mask-background custom-new-header-top nonhundred-percent-fullwidth non-hundred-percent-height-scrolling" style="--awb-border-radius-top-left:0px;--awb-border-radius-top-right:0px;--awb-border-radius-bottom-right:0px;--awb-border-radius-bottom-left:0px;--awb-padding-right-medium:16px;--awb-padding-left-medium:16px;--awb-padding-top-small:16px;--awb-padding-bottom-small:16px;--awb-background-color:#000000;--awb-flex-wrap:wrap;">
            <div class="fusion-builder-row fusion-row fusion-flex-align-items-center fusion-flex-content-wrap" style="max-width:calc( 1320px + 0px );margin-left: calc(-0px / 2 );margin-right: calc(-0px / 2 );">
                <div class="fusion-layout-column fusion_builder_column fusion-builder-column-0 fusion_builder_column_1_2 1_2 fusion-flex-column tel-col" style="--awb-bg-size:cover;--awb-width-large:50%;--awb-margin-top-large:0px;--awb-spacing-right-large:0px;--awb-margin-bottom-large:0px;--awb-spacing-left-large:0px;--awb-width-medium:50%;--awb-order-medium:0;--awb-spacing-right-medium:0px;--awb-spacing-left-medium:0px;--awb-width-small:100%;--awb-order-small:0;--awb-spacing-right-small:0px;--awb-spacing-left-small:0px;">
                    <div class="fusion-column-wrapper fusion-column-has-shadow fusion-flex-justify-content-flex-start fusion-content-layout-row"><i class="fb-icon-element-1 fb-icon-element fontawesome-icon fa-phone-alt fas circle-no fusion-text-flow new-heading-custom-icon" style="--awb-font-size:21px;--awb-margin-right:10.5px;"></i>
                        <div class="fusion-text fusion-text-1 fusion-text-no-margin" style="--awb-font-size:16px;--awb-text-color:var(--awb-color1);--awb-text-font-family:&quot;Proxima Nova - Semi Bold&quot;;--awb-text-font-style:normal;--awb-text-font-weight:400;">
                            <p>Call Us On : <a href="tel:1300136851">1300 136 851</a></p>
                        </div>
                    </div>
                </div>
                <div class="fusion-layout-column fusion_builder_column fusion-builder-column-1 fusion_builder_column_1_2 1_2 fusion-flex-column account-col fusion-no-small-visibility" style="--awb-padding-right:30px;--awb-padding-right-medium:0px;--awb-bg-size:cover;--awb-width-large:50%;--awb-margin-top-large:0px;--awb-spacing-right-large:0px;--awb-margin-bottom-large:0px;--awb-spacing-left-large:0px;--awb-width-medium:50%;--awb-order-medium:0;--awb-spacing-right-medium:0px;--awb-spacing-left-medium:0px;--awb-width-small:50%;--awb-order-small:0;--awb-spacing-right-small:0px;--awb-spacing-left-small:0px;">
                    <div class="fusion-column-wrapper fusion-column-has-shadow fusion-flex-justify-content-flex-start fusion-content-layout-column">
                        <nav class="awb-menu awb-menu_row awb-menu_em-hover mobile-mode-collapse-to-button awb-menu_icons-left awb-menu_dc-yes mobile-trigger-fullwidth-off awb-menu_mobile-toggle awb-menu_indent-left mobile-size-full-absolute mega-menu-loading awb-menu_desktop awb-menu_dropdown awb-menu_expand-right awb-menu_transition-fade" style="--awb-min-height:50px;--awb-justify-content:flex-end;--awb-color:var(--awb-color1);--awb-active-color:var(--awb-color1);--awb-icons-color:var(--awb-color1);--awb-main-justify-content:flex-start;--awb-mobile-justify:flex-start;--awb-mobile-caret-left:auto;--awb-mobile-caret-right:0;--awb-fusion-font-family-typography:&quot;Proxima Nova - Semi Bold&quot;;--awb-fusion-font-style-typography:normal;--awb-fusion-font-weight-typography:400;--awb-fusion-font-family-submenu-typography:inherit;--awb-fusion-font-style-submenu-typography:normal;--awb-fusion-font-weight-submenu-typography:400;--awb-fusion-font-family-mobile-typography:inherit;--awb-fusion-font-style-mobile-typography:normal;--awb-fusion-font-weight-mobile-typography:400;" aria-label="Top Menu" data-breakpoint="0" data-count="0" data-transition-type="fade" data-transition-time="300" data-expand="right">
                            <ul id="menu-top-menu" class="fusion-menu awb-menu__main-ul awb-menu__main-ul_row">
                                <li id="menu-item-1013" class="my_account menu-item menu-item-type-custom menu-item-object-custom menu-item-1013 awb-menu__li awb-menu__main-li awb-menu__main-li_regular" data-classes="my_account" data-item-id="1013"><span class="awb-menu__main-background-default awb-menu__main-background-default_fade"></span><span class="awb-menu__main-background-active awb-menu__main-background-active_fade"></span><a href="https://account.mammothstorage.com.au/login" class="awb-menu__main-a awb-menu__main-a_regular fusion-flex-link"><span class="awb-menu__i awb-menu__i_main fusion-megamenu-icon"><i class="glyphicon fa-user fas" aria-hidden="true"></i></span><span class="menu-text">My Account</span></a></li>
                            </ul>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
        <div class="fusion-fullwidth fullwidth-box fusion-builder-row-2 fusion-flex-container has-pattern-background has-mask-background custom-new-header nonhundred-percent-fullwidth non-hundred-percent-height-scrolling fusion-sticky-container" style="--awb-border-radius-top-left:0px;--awb-border-radius-top-right:0px;--awb-border-radius-bottom-right:0px;--awb-border-radius-bottom-left:0px;--awb-padding-top-medium:0px;--awb-padding-right-medium:16px;--awb-padding-bottom-medium:0px;--awb-padding-left-medium:16px;--awb-padding-top-small:0px;--awb-padding-right-small:0px;--awb-padding-bottom-small:0px;--awb-padding-left-small:0px;--awb-background-color:var(--awb-color1);--awb-sticky-background-color:var(--awb-color1) !important;--awb-flex-wrap:wrap;" data-transition-offset="0" data-scroll-offset="0" data-sticky-medium-visibility="1" data-sticky-large-visibility="1" data-sticky_kit="true">
            <div class="fusion-builder-row fusion-row fusion-flex-align-items-center fusion-flex-justify-content-space-between fusion-flex-content-wrap" style="max-width:calc( 1320px + 0px );margin-left: calc(-0px / 2 );margin-right: calc(-0px / 2 );">
                <div class="fusion-layout-column fusion_builder_column fusion-builder-column-2 fusion_builder_column_1_2 1_2 fusion-flex-column logo-col" style="--awb-bg-size:cover;--awb-width-large:50%;--awb-margin-top-large:0px;--awb-spacing-right-large:0px;--awb-margin-bottom-large:0px;--awb-spacing-left-large:0px;--awb-width-medium:33.333333333333%;--awb-order-medium:0;--awb-spacing-right-medium:0px;--awb-spacing-left-medium:0px;--awb-width-small:auto;--awb-order-small:0;--awb-spacing-right-small:0px;--awb-spacing-left-small:0px;">
                    <div class="fusion-column-wrapper fusion-column-has-shadow fusion-flex-justify-content-flex-start fusion-content-layout-column">
                        <div class="fusion-image-element " style="--awb-margin-bottom:-20px;--awb-margin-bottom-medium:0px;--awb-caption-title-font-family:var(--h2_typography-font-family);--awb-caption-title-font-weight:var(--h2_typography-font-weight);--awb-caption-title-font-style:var(--h2_typography-font-style);--awb-caption-title-size:var(--h2_typography-font-size);--awb-caption-title-transform:var(--h2_typography-text-transform);--awb-caption-title-line-height:var(--h2_typography-line-height);--awb-caption-title-letter-spacing:var(--h2_typography-letter-spacing);"><span class=" fusion-imageframe imageframe-none imageframe-1 hover-type-none"><a class="fusion-no-lightbox" href="https://mammothselfstore.com.au/" target="_self"><img decoding="async" width="115" height="131" alt="Logo" src="https://mammothselfstore.com.au/wp-content/uploads/2021/08/logo.svg" class="img-responsive wp-image-13"></a></span></div>
                    </div>
                </div>
                <div class="fusion-layout-column fusion_builder_column fusion-builder-column-3 fusion_builder_column_1_2 1_2 fusion-flex-column fusion-flex-align-self-center" style="--awb-bg-size:cover;--awb-width-large:50%;--awb-margin-top-large:0px;--awb-spacing-right-large:0px;--awb-margin-bottom-large:0px;--awb-spacing-left-large:0px;--awb-width-medium:66.666666666667%;--awb-order-medium:0;--awb-spacing-right-medium:0px;--awb-spacing-left-medium:0px;--awb-width-small:auto;--awb-order-small:0;--awb-spacing-right-small:0px;--awb-spacing-left-small:0px;">
                    <div class="fusion-column-wrapper fusion-column-has-shadow fusion-flex-justify-content-flex-start fusion-content-layout-column">
                        <nav class="awb-menu awb-menu_row awb-menu_em-hover mobile-mode-collapse-to-button awb-menu_icons-left awb-menu_dc-no mobile-trigger-fullwidth-off awb-menu_mobile-accordion awb-menu_indent-left mobile-size-full-absolute mega-menu-loading awb-menu_desktop awb-menu_dropdown awb-menu_expand-right awb-menu_transition-fade" style="--awb-min-height:110px;--awb-gap:28px;--awb-justify-content:flex-end;--awb-color:#000000;--awb-active-color:#000000;--awb-submenu-sep-color:var(--awb-color1);--awb-submenu-font-size:16px;--awb-main-justify-content:flex-start;--awb-mobile-nav-button-align-hor:flex-end;--awb-mobile-nav-items-height:60;--awb-mobile-trigger-font-size:39px;--awb-trigger-padding-top:44px;--awb-trigger-padding-right:0px;--awb-trigger-padding-bottom:44px;--awb-mobile-justify:flex-start;--awb-mobile-caret-left:auto;--awb-mobile-caret-right:0;--awb-box-shadow:1px 1px 30px 0px rgba(0,0,0,0.06);;--awb-fusion-font-family-typography:&quot;Proxima Nova Bold&quot;;--awb-fusion-font-style-typography:normal;--awb-fusion-font-weight-typography:400;--awb-fusion-font-family-submenu-typography:&quot;Proxima Nova - Regular&quot;;--awb-fusion-font-style-submenu-typography:normal;--awb-fusion-font-weight-submenu-typography:400;--awb-fusion-font-family-mobile-typography:inherit;--awb-fusion-font-style-mobile-typography:normal;--awb-fusion-font-weight-mobile-typography:400;" aria-label="New Main Menu" data-breakpoint="1024" data-count="1" data-transition-type="fade" data-transition-time="300" data-expand="right"><button type="button" class="awb-menu__m-toggle awb-menu__m-toggle_no-text" aria-expanded="false" aria-controls="menu-new-main-menu"><span class="awb-menu__m-toggle-inner"><span class="collapsed-nav-text"><span class="screen-reader-text">Toggle Navigation</span></span><span class="awb-menu__m-collapse-icon awb-menu__m-collapse-icon_no-text"><span class="awb-menu__m-collapse-icon-open awb-menu__m-collapse-icon-open_no-text fa-bars fas"></span><span class="awb-menu__m-collapse-icon-close awb-menu__m-collapse-icon-close_no-text fa-times fas"></span></span></span></button>
                            <ul id="menu-new-main-menu" class="fusion-menu awb-menu__main-ul awb-menu__main-ul_row">
                                <li id="menu-item-4167" class="menu-item menu-item-type-post_type menu-item-object-page menu-item-has-children menu-item-4167 awb-menu__li awb-menu__main-li awb-menu__main-li_regular" data-item-id="4167"><span class="awb-menu__main-background-default awb-menu__main-background-default_fade"></span><span class="awb-menu__main-background-active awb-menu__main-background-active_fade"></span><a href="https://mammothselfstore.com.au/storage-services/" class="awb-menu__main-a awb-menu__main-a_regular"><span class="menu-text">Storage Types</span><span class="awb-menu__open-nav-submenu-hover"></span></a><button type="button" aria-label="Open submenu of Storage Types" aria-expanded="false" class="awb-menu__open-nav-submenu_mobile awb-menu__open-nav-submenu_main"></button>
                                    <ul class="awb-menu__sub-ul awb-menu__sub-ul_main" style="">
                                        <li id="menu-item-4169" class="menu-item menu-item-type-post_type menu-item-object-page menu-item-4169 awb-menu__li awb-menu__sub-li"><a href="https://mammothselfstore.com.au/storage-services/personal-storage/" class="awb-menu__sub-a"><span>Personal Storage</span></a></li>
                                        <li id="menu-item-4168" class="menu-item menu-item-type-post_type menu-item-object-page menu-item-4168 awb-menu__li awb-menu__sub-li"><a href="https://mammothselfstore.com.au/storage-services/business-storage/" class="awb-menu__sub-a"><span>Business Storage</span></a></li>
                                        <li id="menu-item-4171" class="menu-item menu-item-type-post_type menu-item-object-page menu-item-has-children menu-item-4171 awb-menu__li awb-menu__sub-li"><a href="https://mammothselfstore.com.au/storage-services/vehicle-storage/" class="awb-menu__sub-a"><span>Vehicle Storage</span><span class="awb-menu__open-nav-submenu-hover"></span></a><button type="button" aria-label="Open submenu of Vehicle Storage" aria-expanded="false" class="awb-menu__open-nav-submenu_mobile awb-menu__open-nav-submenu_sub"></button>
                                            <ul class="awb-menu__sub-ul awb-menu__sub-ul_grand" style="">
                                                <li id="menu-item-4941" class="menu-item menu-item-type-post_type menu-item-object-page menu-item-4941 awb-menu__li awb-menu__sub-li"><a href="https://mammothselfstore.com.au/storage-services/boat-storage/" class="awb-menu__sub-a"><span>Boat Storage</span></a></li>
                                                <li id="menu-item-4940" class="menu-item menu-item-type-post_type menu-item-object-page menu-item-4940 awb-menu__li awb-menu__sub-li"><a href="https://mammothselfstore.com.au/storage-services/car-storage/" class="awb-menu__sub-a"><span>Car Storage</span></a></li>
                                                <li id="menu-item-4939" class="menu-item menu-item-type-post_type menu-item-object-page menu-item-4939 awb-menu__li awb-menu__sub-li"><a href="https://mammothselfstore.com.au/storage-services/caravan-storage/" class="awb-menu__sub-a"><span>Caravan Storage</span></a></li>
                                            </ul>
                                        </li>
                                    </ul>
                                </li>
                                <li id="menu-item-4172" class="menu-item menu-item-type-post_type menu-item-object-page menu-item-has-children menu-item-4172 awb-menu__li awb-menu__main-li awb-menu__main-li_regular" data-item-id="4172"><span class="awb-menu__main-background-default awb-menu__main-background-default_fade"></span><span class="awb-menu__main-background-active awb-menu__main-background-active_fade"></span><a href="https://mammothselfstore.com.au/locations/" class="awb-menu__main-a awb-menu__main-a_regular"><span class="menu-text">Locations</span><span class="awb-menu__open-nav-submenu-hover"></span></a><button type="button" aria-label="Open submenu of Locations" aria-expanded="false" class="awb-menu__open-nav-submenu_mobile awb-menu__open-nav-submenu_main"></button>
                                    <ul class="awb-menu__sub-ul awb-menu__sub-ul_main" style="">
                                        <li id="menu-item-4173" class="menu-item menu-item-type-custom menu-item-object-custom menu-item-4173 awb-menu__li awb-menu__sub-li"><a href="https://mammothselfstore.com.au/locations/nambour-vehicle-storage" class="awb-menu__sub-a"><span>Vehicle Storage Nambour</span></a></li>
                                        <li id="menu-item-4174" class="menu-item menu-item-type-custom menu-item-object-custom menu-item-4174 awb-menu__li awb-menu__sub-li"><a href="https://mammothselfstore.com.au/locations/storage-forest-glen/" class="awb-menu__sub-a"><span>Self Storage Forest Glen</span></a></li>
                                        <li id="menu-item-4175" class="menu-item menu-item-type-custom menu-item-object-custom menu-item-4175 awb-menu__li awb-menu__sub-li"><a href="https://mammothselfstore.com.au/locations/storage-units-caloundra/" class="awb-menu__sub-a"><span>Self Storage Caloundra</span></a></li>
                                        <li id="menu-item-4176" class="menu-item menu-item-type-custom menu-item-object-custom menu-item-4176 awb-menu__li awb-menu__sub-li"><a href="https://mammothselfstore.com.au/locations/self-storage-maroochydore/" class="awb-menu__sub-a"><span>Self Storage Maroochydore</span></a></li>
                                        <li id="menu-item-4177" class="menu-item menu-item-type-custom menu-item-object-custom menu-item-4177 awb-menu__li awb-menu__sub-li"><a href="https://mammothselfstore.com.au/locations/vehicle-storage-hervey-bay/" class="awb-menu__sub-a"><span>Vehicle Storage Hervey Bay</span></a></li>
                                        <li id="menu-item-4178" class="menu-item menu-item-type-custom menu-item-object-custom menu-item-4178 awb-menu__li awb-menu__sub-li"><a href="https://mammothselfstore.com.au/locations/self-storage-bundaberg/" class="awb-menu__sub-a"><span>Self Storage Bundaberg</span></a></li>
                                        <li id="menu-item-5412" class="menu-item menu-item-type-custom menu-item-object-custom menu-item-5412 awb-menu__li awb-menu__sub-li"><a href="https://mammothselfstore.com.au/locations/kunda-park-self-storage/" class="awb-menu__sub-a"><span>Self Storage Kunda Park</span></a></li>
                                    </ul>
                                </li>
                                <li id="menu-item-4179" class="menu-item menu-item-type-post_type menu-item-object-page menu-item-has-children menu-item-4179 awb-menu__li awb-menu__main-li awb-menu__main-li_regular" data-item-id="4179"><span class="awb-menu__main-background-default awb-menu__main-background-default_fade"></span><span class="awb-menu__main-background-active awb-menu__main-background-active_fade"></span><a href="https://mammothselfstore.com.au/about/" class="awb-menu__main-a awb-menu__main-a_regular"><span class="menu-text">About</span><span class="awb-menu__open-nav-submenu-hover"></span></a><button type="button" aria-label="Open submenu of About" aria-expanded="false" class="awb-menu__open-nav-submenu_mobile awb-menu__open-nav-submenu_main"></button>
                                    <ul class="awb-menu__sub-ul awb-menu__sub-ul_main" style="">
                                        <li id="menu-item-4182" class="menu-item menu-item-type-post_type menu-item-object-page menu-item-4182 awb-menu__li awb-menu__sub-li"><a href="https://mammothselfstore.com.au/our-stories/" class="awb-menu__sub-a"><span>Our Stories</span></a></li>
                                        <li id="menu-item-5300" class="menu-item menu-item-type-custom menu-item-object-custom menu-item-5300 awb-menu__li awb-menu__sub-li"><a href="https://account.mammothstorage.com.au/login" class="awb-menu__sub-a"><span>Pay Online</span></a></li>
                                        <li id="menu-item-4180" class="menu-item menu-item-type-post_type menu-item-object-page menu-item-4180 awb-menu__li awb-menu__sub-li"><a href="https://mammothselfstore.com.au/faqs/" class="awb-menu__sub-a"><span>FAQ’s</span></a></li>
                                        <li id="menu-item-4181" class="menu-item menu-item-type-post_type menu-item-object-page menu-item-4181 awb-menu__li awb-menu__sub-li"><a href="https://mammothselfstore.com.au/store-protect/" class="awb-menu__sub-a"><span>Goods Protection</span></a></li>
                                    </ul>
                                </li>
                                <li id="menu-item-5083" class="menu-item menu-item-type-post_type menu-item-object-page menu-item-5083 awb-menu__li awb-menu__main-li awb-menu__main-li_regular" data-item-id="5083"><span class="awb-menu__main-background-default awb-menu__main-background-default_fade"></span><span class="awb-menu__main-background-active awb-menu__main-background-active_fade"></span><a href="https://mammothselfstore.com.au/secure-self-storage/" class="awb-menu__main-a awb-menu__main-a_regular"><span class="menu-text">Book Now</span></a></li>
                                <li id="menu-item-5398" class="menu-item menu-item-type-custom menu-item-object-custom menu-item-5398 awb-menu__li awb-menu__main-li awb-menu__main-li_regular" data-item-id="5398"><span class="awb-menu__main-background-default awb-menu__main-background-default_fade"></span><span class="awb-menu__main-background-active awb-menu__main-background-active_fade"></span><a href="https://account.mammothstorage.com.au/" class="awb-menu__main-a awb-menu__main-a_regular"><span class="menu-text">My Account</span></a></li>
                                <li id="menu-item-5373" class="menu-item menu-item-type-post_type menu-item-object-page menu-item-5373 awb-menu__li awb-menu__main-li awb-menu__main-li_regular" data-item-id="5373"><span class="awb-menu__main-background-default awb-menu__main-background-default_fade"></span><span class="awb-menu__main-background-active awb-menu__main-background-active_fade"></span><a href="https://mammothselfstore.com.au/storage-calculator/" class="awb-menu__main-a awb-menu__main-a_regular"><span class="menu-text">Storage Calculator</span></a></li>
                            </ul>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </div>