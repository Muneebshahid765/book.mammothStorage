<?php
defined('BASEPATH') or exit('No direct script access allowed');

class MammothStorageController extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        date_default_timezone_set('Australia/Brisbane');
        
        // Enable CORS
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
        header('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With');
        
        // Handle preflight requests
        if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
            exit(0);
        }
    }

    // Main booking page - Single page with all steps
    public function index()
    {
        // Load database
        $this->load->database();
        
        // Fetch all locations from database
        $query = $this->db->select('id, name, code')
                          ->from('tbl_locations')
                          ->order_by('name', 'ASC')
                          ->get();
        
        $locations = $query->result_array();
        
        // Map location codes to their details (address and storage types)
        $locationDetails = array(
            "L001" => array("address" => "380 Mons Road", "storage_types" => "Self Storage & Vehicle Storage"),
            "L002" => array("address" => "82 Sugar Road", "storage_types" => "Self Storage Only"),
            "L003" => array("address" => "38 Zealey Road", "storage_types" => "Vehicle Storage Only"),
            "L004" => array("address" => "40 Geo-Hawkins Crescent", "storage_types" => "Self Storage Only"),
            "L005" => array("address" => "34 Southern Cross Circuit", "storage_types" => "Vehicle Storage Only"),
            "L006" => array("address" => "38 Commercial Street", "storage_types" => "Self Storage Only"),
            "L007" => array("address" => "486 Maroochydore Road", "storage_types" => "Self Storage Only"),
            "L008" => array("address" => "18 Bonnick Road", "storage_types" => "Self Storage Only")
        );
        
        // Merge location details with database data
        foreach ($locations as &$location) {
            $code = $location['code'];
            if (isset($locationDetails[$code])) {
                $location['address'] = $locationDetails[$code]['address'];
                $location['storage_types'] = $locationDetails[$code]['storage_types'];
            } else {
                $location['address'] = 'Address not available';
                $location['storage_types'] = 'Self Storage Only';
            }
        }
        
        $data['locations'] = $locations;
        
        // Generate random booking ID for confirmation step
        $data['bookingId'] = 'MB' . date('Y') . rand(100000, 999999) . 'L';
        
        $this->load->view('storage/mammoth-storage/index', $data);
    }

    // AJAX endpoint to load step content (partials without headers/footers)
    public function load_step()
    {
        $step = $this->input->post('step');
        $data['bookingId'] = 'MB' . date('Y') . rand(100000, 999999) . 'L';
        
        switch ($step) {
            case 'personal-info':
                $this->load->view('storage/mammoth-storage/partials/personal-info-partial', $data);
                break;
            case 'additional-details':
                $this->load->view('storage/mammoth-storage/partials/additional-details-partial', $data);
                break;
            case 'insurance-terms':
                $this->load->view('storage/mammoth-storage/partials/insurance-terms-partial', $data);
                break;
            case 'payment':
                $this->load->view('storage/mammoth-storage/partials/payment-partial', $data);
                break;
            case 'booking-confirmed':
                $this->load->view('storage/mammoth-storage/partials/booking-confirmed-partial', $data);
                break;
            default:
                echo 'Invalid step';
        }
    }
}
