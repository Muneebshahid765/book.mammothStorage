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
        
        $this->process_abandoned_leads();
        
        $hasEnabled = $this->db->field_exists('is_enabled', 'tbl_locations');

        // Fetch all locations from database
        $select = 'id, name, code';
        if ($hasEnabled) {
            $select .= ', is_enabled';
        }

        $this->db->select($select);
        $this->db->from('tbl_locations');
        $this->db->order_by('id', 'ASC');
        $query = $this->db->get();

        $locations = $query ? $query->result_array() : [];
        
        // Map location codes to their details (address and storage types)
        $rawLocationDetails = array(
            "L001" => array("address" => "380 Mons Road", "storage_types" => "Self Storage & Vehicle Storage"),
            "L002" => array("address" => "82 Sugar Road", "storage_types" => "Self Storage Only"),
            "L003" => array("address" => "38 Zealey Road", "storage_types" => "Vehicle Storage Only"),
            "L004" => array("address" => "40 Geo-Hawkins Crescent", "storage_types" => "Self Storage Only"),
            "L005" => array("address" => "34 Southern Cross Circuit", "storage_types" => "Vehicle Storage Only"),
            "L006" => array("address" => "38 Commercial Street", "storage_types" => "Self Storage Only"),
            "L007" => array("address" => "486 Maroochydore Road", "storage_types" => "Self Storage Only"),
            "L008" => array("address" => "18 Bonnick Road", "storage_types" => "Self Storage Only")
        );
        $locationDetails = [];
        foreach ($rawLocationDetails as $detailCode => $detail) {
            $locationDetails[strtoupper($detailCode)] = $detail;
        }
        
        // Merge location details with database data
        $enabledCodes = [];
        foreach ($locations as &$location) {
            $code = isset($location['code']) ? strtoupper(trim((string) $location['code'])) : '';
            $location['code'] = $code;
            $isEnabled = 1;
            if ($hasEnabled) {
                $rawEnabled = isset($location['is_enabled']) ? $location['is_enabled'] : 1;
                $isEnabled = (int) trim((string) $rawEnabled) === 1 ? 1 : 0;
            }
            $location['is_enabled'] = $isEnabled;
            if ($isEnabled === 1 && $code !== '') {
                $enabledCodes[] = $code;
            }
            if (isset($locationDetails[$code])) {
                $location['address'] = $locationDetails[$code]['address'];
                $location['storage_types'] = $locationDetails[$code]['storage_types'];
            } else {
                $location['address'] = 'Address not available';
                $location['storage_types'] = 'Self Storage Only';
            }
        }
        
        $data['locations'] = $locations;

        $enabledCodes = array_values(array_unique($enabledCodes));
        log_message('debug', 'MammothStorageController index enabled codes: ' . json_encode($enabledCodes));
        $data['enabledLocationCodes'] = $hasEnabled ? $enabledCodes : null;
        
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

   public function capture_personal_info()
{
    $this->load->database();

    // Get POST data with proper sanitization
    $firstName = trim($this->input->post('firstName', true));
    $lastName = trim($this->input->post('lastName', true));
    $country = trim($this->input->post('country', true));
    $countryCode = trim($this->input->post('countryCode', true));
    $city = trim($this->input->post('city', true));
    $state = trim($this->input->post('state', true));
    $address1 = trim($this->input->post('address1', true));
    $email = trim($this->input->post('email', true));
    $phone = trim($this->input->post('phone', true));

    $locationCode = trim($this->input->post('locationCode', true));
    $locationName = trim($this->input->post('locationName', true));
    $storageType = trim($this->input->post('storageType', true));
    $unitId = trim($this->input->post('unitId', true));
    $unitName = trim($this->input->post('unitName', true));
    $unitSize = trim($this->input->post('unitSize', true));
    $unitGroup = trim($this->input->post('unitGroup', true));

    // Debug: Log received data
    log_message('debug', 'capture_personal_info received: ' . json_encode(array(
        'firstName' => $firstName,
        'lastName' => $lastName,
        'email' => $email,
        'phone' => $phone,
        'country' => $country,
        'countryCode' => $countryCode,
        'city' => $city,
        'state' => $state,
        'address1' => $address1,
        'locationCode' => $locationCode,
        'locationName' => $locationName,
        'storageType' => $storageType,
        'unitId' => $unitId,
        'unitName' => $unitName,
        'unitSize' => $unitSize,
        'unitGroup' => $unitGroup
    )));

    // Validation - Required fields
    if (empty($email)) {
        log_message('error', 'capture_personal_info: Missing email');
        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode(array('success' => false, 'message' => 'Email is required')));
        return;
    }

    if (empty($firstName)) {
        log_message('error', 'capture_personal_info: Missing first name');
        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode(array('success' => false, 'message' => 'First name is required')));
        return;
    }

    if (empty($lastName)) {
        log_message('error', 'capture_personal_info: Missing last name');
        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode(array('success' => false, 'message' => 'Last name is required')));
        return;
    }

    if (empty($city)) {
        log_message('error', 'capture_personal_info: Missing city');
        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode(array('success' => false, 'message' => 'City is required')));
        return;
    }

    if (empty($state)) {
        log_message('error', 'capture_personal_info: Missing state');
        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode(array('success' => false, 'message' => 'State is required')));
        return;
    }

    if (empty($address1)) {
        log_message('error', 'capture_personal_info: Missing address line');
        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode(array('success' => false, 'message' => 'Address line is required')));
        return;
    }

    // Apply safe defaults for country and country code
    if (empty($country)) {
        $country = 'Australia';
    }
    if (empty($countryCode)) {
        $countryCode = '+61';
    }

    // Check if table exists
    if (!$this->db->table_exists('tbl_abandoned_carts')) {
        log_message('error', 'tbl_abandoned_carts table does not exist');
        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode(array('success' => false, 'message' => 'Database table does not exist')));
        return;
    }

    $hasCity = $this->db->field_exists('city', 'tbl_abandoned_carts');
    if (!$hasCity) {
        $this->db->query("ALTER TABLE `tbl_abandoned_carts` ADD `city` VARCHAR(255) NULL AFTER `country_code`");
        $err = $this->db->error();
        if (isset($err['code']) && (int) $err['code'] !== 0) {
            log_message('error', 'capture_personal_info: failed to add city column: ' . (isset($err['message']) ? $err['message'] : ''));
        }
        $hasCity = $this->db->field_exists('city', 'tbl_abandoned_carts');
    }

    $hasState = $this->db->field_exists('state', 'tbl_abandoned_carts');
    if (!$hasState) {
        $this->db->query("ALTER TABLE `tbl_abandoned_carts` ADD `state` VARCHAR(255) NULL AFTER `city`");
        $err = $this->db->error();
        if (isset($err['code']) && (int) $err['code'] !== 0) {
            log_message('error', 'capture_personal_info: failed to add state column: ' . (isset($err['message']) ? $err['message'] : ''));
        }
        $hasState = $this->db->field_exists('state', 'tbl_abandoned_carts');
    }

    $hasAddress1 = $this->db->field_exists('address1', 'tbl_abandoned_carts');
    if (!$hasAddress1) {
        $this->db->query("ALTER TABLE `tbl_abandoned_carts` ADD `address1` VARCHAR(255) NULL AFTER `state`");
        $err = $this->db->error();
        if (isset($err['code']) && (int) $err['code'] !== 0) {
            log_message('error', 'capture_personal_info: failed to add address1 column: ' . (isset($err['message']) ? $err['message'] : ''));
        }
        $hasAddress1 = $this->db->field_exists('address1', 'tbl_abandoned_carts');
    }

    $hasLocationCode = $this->db->field_exists('location_code', 'tbl_abandoned_carts');
    if (!$hasLocationCode) {
        $this->db->query("ALTER TABLE `tbl_abandoned_carts` ADD `location_code` VARCHAR(50) NULL");
        $err = $this->db->error();
        if (isset($err['code']) && (int) $err['code'] !== 0) {
            log_message('error', 'capture_personal_info: failed to add location_code column: ' . (isset($err['message']) ? $err['message'] : ''));
        }
        $hasLocationCode = $this->db->field_exists('location_code', 'tbl_abandoned_carts');
    }

    $hasLocationName = $this->db->field_exists('location_name', 'tbl_abandoned_carts');
    if (!$hasLocationName) {
        $this->db->query("ALTER TABLE `tbl_abandoned_carts` ADD `location_name` VARCHAR(255) NULL");
        $err = $this->db->error();
        if (isset($err['code']) && (int) $err['code'] !== 0) {
            log_message('error', 'capture_personal_info: failed to add location_name column: ' . (isset($err['message']) ? $err['message'] : ''));
        }
        $hasLocationName = $this->db->field_exists('location_name', 'tbl_abandoned_carts');
    }

    $hasStorageType = $this->db->field_exists('storage_type', 'tbl_abandoned_carts');
    if (!$hasStorageType) {
        $this->db->query("ALTER TABLE `tbl_abandoned_carts` ADD `storage_type` VARCHAR(50) NULL");
        $err = $this->db->error();
        if (isset($err['code']) && (int) $err['code'] !== 0) {
            log_message('error', 'capture_personal_info: failed to add storage_type column: ' . (isset($err['message']) ? $err['message'] : ''));
        }
        $hasStorageType = $this->db->field_exists('storage_type', 'tbl_abandoned_carts');
    }

    $hasUnitId = $this->db->field_exists('unit_id', 'tbl_abandoned_carts');
    if (!$hasUnitId) {
        $this->db->query("ALTER TABLE `tbl_abandoned_carts` ADD `unit_id` VARCHAR(100) NULL");
        $err = $this->db->error();
        if (isset($err['code']) && (int) $err['code'] !== 0) {
            log_message('error', 'capture_personal_info: failed to add unit_id column: ' . (isset($err['message']) ? $err['message'] : ''));
        }
        $hasUnitId = $this->db->field_exists('unit_id', 'tbl_abandoned_carts');
    }

    $hasUnitName = $this->db->field_exists('unit_name', 'tbl_abandoned_carts');
    if (!$hasUnitName) {
        $this->db->query("ALTER TABLE `tbl_abandoned_carts` ADD `unit_name` VARCHAR(255) NULL");
        $err = $this->db->error();
        if (isset($err['code']) && (int) $err['code'] !== 0) {
            log_message('error', 'capture_personal_info: failed to add unit_name column: ' . (isset($err['message']) ? $err['message'] : ''));
        }
        $hasUnitName = $this->db->field_exists('unit_name', 'tbl_abandoned_carts');
    }

    $hasUnitSize = $this->db->field_exists('unit_size', 'tbl_abandoned_carts');
    if (!$hasUnitSize) {
        $this->db->query("ALTER TABLE `tbl_abandoned_carts` ADD `unit_size` VARCHAR(255) NULL");
        $err = $this->db->error();
        if (isset($err['code']) && (int) $err['code'] !== 0) {
            log_message('error', 'capture_personal_info: failed to add unit_size column: ' . (isset($err['message']) ? $err['message'] : ''));
        }
        $hasUnitSize = $this->db->field_exists('unit_size', 'tbl_abandoned_carts');
    }

    $hasUnitGroup = $this->db->field_exists('unit_group', 'tbl_abandoned_carts');
    if (!$hasUnitGroup) {
        $this->db->query("ALTER TABLE `tbl_abandoned_carts` ADD `unit_group` VARCHAR(100) NULL");
        $err = $this->db->error();
        if (isset($err['code']) && (int) $err['code'] !== 0) {
            log_message('error', 'capture_personal_info: failed to add unit_group column: ' . (isset($err['message']) ? $err['message'] : ''));
        }
        $hasUnitGroup = $this->db->field_exists('unit_group', 'tbl_abandoned_carts');
    }

    $now = date('Y-m-d H:i:s');

    // Check if lead already exists
    $existing = $this->db->select('id, highest_step')
        ->from('tbl_abandoned_carts')
        ->where('email', $email)
        ->where('completed', 0)
        ->order_by('id', 'DESC')
        ->limit(1)
        ->get()
        ->row_array();

    // Prepare data array
    $data = array(
        'first_name' => $firstName,
        'last_name' => $lastName,
        'country' => $country,
        'country_code' => $countryCode,
        'email' => $email,
        'phone' => $phone,
        'highest_step' => 2,
        'completed' => 0,
        'emailed' => 0,
        'updated_at' => $now
    );

    if ($hasCity) {
        $data['city'] = $city;
    }
    if ($hasState) {
        $data['state'] = $state;
    }
    if ($hasAddress1) {
        $data['address1'] = $address1;
    }

    if ($hasLocationCode) {
        $data['location_code'] = $locationCode;
    }
    if ($hasLocationName) {
        $data['location_name'] = $locationName;
    }
    if ($hasStorageType) {
        $data['storage_type'] = $storageType;
    }
    if ($hasUnitId) {
        $data['unit_id'] = $unitId;
    }
    if ($hasUnitName) {
        $data['unit_name'] = $unitName;
    }
    if ($hasUnitSize) {
        $data['unit_size'] = $unitSize;
    }
    if ($hasUnitGroup) {
        $data['unit_group'] = $unitGroup;
    }

    if ($existing) {
        // Update existing record
        $leadId = (int) $existing['id'];
        $data['highest_step'] = max((int) $existing['highest_step'], 2);
        
        $this->db->where('id', $leadId);
        $this->db->update('tbl_abandoned_carts', $data);
        
        if ($this->db->error()['code'] !== 0) {
            $error = $this->db->error();
            log_message('error', 'capture_personal_info update failed: ' . $error['message']);
            $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode(array(
                    'success' => false, 
                    'message' => 'Database update failed', 
                    'db_error' => $error['message']
                )));
            return;
        }
        
        log_message('debug', 'Updated lead ID: ' . $leadId . ' with city: ' . $city . ', state: ' . $state);
    } else {
        // Insert new record
        $data['created_at'] = $now;
        
        $this->db->insert('tbl_abandoned_carts', $data);
        
        if ($this->db->error()['code'] !== 0) {
            $error = $this->db->error();
            log_message('error', 'capture_personal_info insert failed: ' . $error['message']);
            $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode(array(
                    'success' => false, 
                    'message' => 'Database insert failed', 
                    'db_error' => $error['message']
                )));
            return;
        }

        $leadId = (int) $this->db->insert_id();
        log_message('debug', 'Created new lead ID: ' . $leadId . ' with city: ' . $city . ', state: ' . $state);
    }

    // Verify the data was saved correctly

    $verifySelect = array();
    if ($hasCity) {
        $verifySelect[] = 'city';
    }
    if ($hasState) {
        $verifySelect[] = 'state';
    }
    if ($hasAddress1) {
        $verifySelect[] = 'address1';
    }

    if (!empty($verifySelect)) {
        $verify = $this->db->select(implode(', ', $verifySelect))
            ->from('tbl_abandoned_carts')
            ->where('id', $leadId)
            ->get()
            ->row_array();

        $citySaved = $hasCity && isset($verify['city']) ? $verify['city'] : '';
        $stateSaved = $hasState && isset($verify['state']) ? $verify['state'] : '';
        $addressSaved = $hasAddress1 && isset($verify['address1']) ? $verify['address1'] : '';
        log_message('debug', 'Verification - Saved city: ' . $citySaved . ', state: ' . $stateSaved . ', address1: ' . $addressSaved);
    }

    $this->output
        ->set_content_type('application/json')
        ->set_output(json_encode(array('success' => true, 'lead_id' => $leadId)));
}

    public function update_lead_step()
    {
        $this->load->database();

        $leadId = (int) $this->input->post('lead_id');
        $step = (int) $this->input->post('step');

        if (!$leadId || !$step) {
            $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode(array('success' => false, 'message' => 'Missing parameters')));
            return;
        }

        if (!$this->db->table_exists('tbl_abandoned_carts')) {
            $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode(array('success' => false, 'message' => 'Table missing')));
            return;
        }

        $row = $this->db->select('highest_step')
            ->from('tbl_abandoned_carts')
            ->where('id', $leadId)
            ->get()
            ->row_array();

        if (!$row) {
            $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode(array('success' => false, 'message' => 'Lead not found')));
            return;
        }

        $current = isset($row['highest_step']) ? (int) $row['highest_step'] : 0;
        $newStep = max($current, $step);

        $this->db->where('id', $leadId)->update('tbl_abandoned_carts', array(
            'highest_step' => $newStep,
            'updated_at' => date('Y-m-d H:i:s')
        ));

        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode(array('success' => true)));
    }

    public function mark_booking_completed()
    {
        $this->load->database();

        $leadId = (int) $this->input->post('lead_id');

        if (!$leadId) {
            $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode(array('success' => false, 'message' => 'Missing lead_id')));
            return;
        }

        if (!$this->db->table_exists('tbl_abandoned_carts')) {
            $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode(array('success' => true)));
            return;
        }

        $this->db->where('id', $leadId)->update('tbl_abandoned_carts', array(
            'completed' => 1,
            'highest_step' => 6,
            'updated_at' => date('Y-m-d H:i:s')
        ));

        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode(array('success' => true)));
    }

    protected function process_abandoned_leads($graceMinutes = 30, &$details = null)
{
    $this->load->database();

    if (!$this->db->table_exists('tbl_abandoned_carts')) {
        return 0;
    }

    $now = date('Y-m-d H:i:s');
    $cutoff = date('Y-m-d H:i:s', time() - ($graceMinutes * 60));

    // Count pending leads regardless of cutoff (helps debug when attempted=0)
    $pendingTotal = (int) $this->db->from('tbl_abandoned_carts')
        ->where('completed', 0)
        ->where('emailed', 0)
        ->count_all_results();

    $pendingOldest = $this->db->select('id, created_at')
        ->from('tbl_abandoned_carts')
        ->where('completed', 0)
        ->where('emailed', 0)
        ->order_by('created_at', 'ASC')
        ->limit(1)
        ->get()
        ->row_array();

    $pendingNewest = $this->db->select('id, created_at')
        ->from('tbl_abandoned_carts')
        ->where('completed', 0)
        ->where('emailed', 0)
        ->order_by('created_at', 'DESC')
        ->limit(1)
        ->get()
        ->row_array();

    $leads = $this->db->from('tbl_abandoned_carts')
        ->where('completed', 0)
        ->where('emailed', 0)
        ->where("COALESCE(updated_at, created_at) <= '" . $this->db->escape_str($cutoff) . "'", null, false)
        ->order_by('id', 'ASC')
        ->get()
        ->result_array();

    if (empty($leads)) {
        log_message('debug', 'No abandoned leads found to email');
        $details = array(
            'attempted' => 0,
            'sent' => 0,
            'failed' => 0,
            'errors' => array(),
            'meta' => array(
                'now' => $now,
                'cutoff' => $cutoff,
                'graceMinutes' => (int) $graceMinutes,
                'pending_total' => $pendingTotal,
                'pending_oldest' => $pendingOldest,
                'pending_newest' => $pendingNewest
            )
        );
        return 0;
    }

    log_message('debug', 'Found ' . count($leads) . ' abandoned leads to email');

    $processed = 0;
    $details = array(
        'attempted' => 0,
        'sent' => 0,
        'failed' => 0,
        'errors' => array(),
        'meta' => array(
            'now' => $now,
            'cutoff' => $cutoff,
            'graceMinutes' => (int) $graceMinutes,
            'pending_total' => $pendingTotal,
            'pending_oldest' => $pendingOldest,
            'pending_newest' => $pendingNewest
        )
    );

    // Delegate abandoned lead email sending to checkout app (uses checkout SMTP/PHPMailer).
    $this->load->config('email', true);
    $toEmail = $this->config->item('abandoned_lead_to', 'email');
    $bccEmail = $this->config->item('abandoned_lead_bcc', 'email');
    if ($toEmail === null || $toEmail === '') {
        $toEmail = 'comender786@gmail.com';
    }
    if ($bccEmail === null) {
        $bccEmail = '';
    }

    $checkoutUrl = 'https://checkout.mammothstorage.com.au/api/v1/leads/send-abandoned-email';
    $checkoutApiKey = getenv('ABANDONED_LEAD_API_KEY');

    foreach ($leads as $lead) {
        $details['attempted']++;
        $sent = false;
        $sendError = '';
        $transportUsed = 'checkout_api';

        $payload = array(
            'to' => $toEmail,
            'bcc' => $bccEmail,
            'first_name' => isset($lead['first_name']) ? (string) $lead['first_name'] : '',
            'last_name' => isset($lead['last_name']) ? (string) $lead['last_name'] : '',
            'email' => isset($lead['email']) ? (string) $lead['email'] : '',
            'phone' => isset($lead['phone']) ? (string) $lead['phone'] : '',
            'country' => isset($lead['country']) ? (string) $lead['country'] : '',
            'country_code' => isset($lead['country_code']) ? (string) $lead['country_code'] : '',
            'city' => isset($lead['city']) ? (string) $lead['city'] : '',
            'state' => isset($lead['state']) ? (string) $lead['state'] : '',
            'location_code' => isset($lead['location_code']) ? (string) $lead['location_code'] : '',
            'location_name' => isset($lead['location_name']) ? (string) $lead['location_name'] : '',
            'storage_type' => isset($lead['storage_type']) ? (string) $lead['storage_type'] : '',
            'unit_name' => isset($lead['unit_name']) ? (string) $lead['unit_name'] : '',
            'unit_size' => isset($lead['unit_size']) ? (string) $lead['unit_size'] : '',
            'unit_group' => isset($lead['unit_group']) ? (string) $lead['unit_group'] : '',
            'highest_step' => isset($lead['highest_step']) ? (string) $lead['highest_step'] : '',
            'created_at' => isset($lead['created_at']) ? (string) $lead['created_at'] : '',
            'updated_at' => isset($lead['updated_at']) ? (string) $lead['updated_at'] : ''
        );

        $ch = curl_init($checkoutUrl);
        $headers = array('Content-Type: application/json');
        if ($checkoutApiKey !== false && $checkoutApiKey !== '') {
            $headers[] = 'X-API-Key: ' . $checkoutApiKey;
        }
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
        curl_setopt($ch, CURLOPT_TIMEOUT, 20);

        $respBody = curl_exec($ch);
        $curlErr = curl_error($ch);
        $httpCode = (int) curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($respBody === false) {
            $sent = false;
            $sendError = $curlErr !== '' ? $curlErr : 'Checkout API request failed';
        } else {
            $decoded = json_decode((string) $respBody, true);
            if ($httpCode === 200 && is_array($decoded) && !empty($decoded['success'])) {
                $sent = true;
            } else {
                $sent = false;
                $sendError = 'Checkout API error (HTTP ' . $httpCode . '): ' . (string) $respBody;
            }
        }

        if (!$sent) {
            // Keep lead eligible for retry by leaving `emailed` as 0
            log_message('error', 'Failed to send abandoned cart email for lead ID: ' . $lead['id']);
            $debug = $sendError !== '' ? $sendError : 'Unknown error';
            log_message('error', 'Email error: ' . $debug);

            $details['failed']++;
            $details['errors'][] = array(
                'lead_id' => (int) $lead['id'],
                'error' => $debug,
                'transport' => $transportUsed
            );

            $this->db->where('id', $lead['id'])->update('tbl_abandoned_carts', array(
                'emailed' => 0,
                'updated_at' => date('Y-m-d H:i:s')
            ));
            continue;
        }

        log_message('debug', 'Successfully sent abandoned cart email for lead ID: ' . $lead['id']);

        $details['sent']++;

        $details['meta']['last_transport'] = $transportUsed;

        $this->db->where('id', $lead['id'])->update('tbl_abandoned_carts', array(
            'emailed' => 1,
            'emailed_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ));

        $processed++;
    }

    log_message('debug', 'Processed ' . $processed . ' abandoned leads');

    return $processed;
}

    public function send_abandoned_emails()
    {
        $this->load->config('email', true);
        $requiredKey = (string) $this->config->item('abandoned_cron_key', 'email');
        if ($requiredKey !== '') {
            $providedKey = (string) $this->input->get_post('key', true);
            if (!hash_equals($requiredKey, $providedKey)) {
                $this->output
                    ->set_status_header(403)
                    ->set_content_type('application/json')
                    ->set_output(json_encode(array('success' => false, 'message' => 'Forbidden')));
                return;
            }
        }

        $rawGrace = $this->input->get_post('graceMinutes', true);
        if ($rawGrace === null || $rawGrace === '') {
            $graceMinutes = 30;
        } else {
            $graceMinutes = (int) $rawGrace;
            if ($graceMinutes < 0) {
                $graceMinutes = 0;
            }
        }

        $logsPath = APPPATH . 'logs' . DIRECTORY_SEPARATOR;
        $logWritable = @is_writable($logsPath);
        log_message('debug', 'send_abandoned_emails invoked: graceMinutes=' . (int) $graceMinutes . ', ENVIRONMENT=' . (defined('ENVIRONMENT') ? ENVIRONMENT : '') . ', logs_path=' . $logsPath . ', logs_writable=' . ($logWritable ? '1' : '0'));

        $details = null;
        $processed = $this->process_abandoned_leads($graceMinutes, $details);

        $emailCfg = array(
            'protocol' => $this->config->item('protocol', 'email'),
            'smtp_host' => $this->config->item('smtp_host', 'email'),
            'smtp_port' => $this->config->item('smtp_port', 'email'),
            'smtp_crypto' => $this->config->item('smtp_crypto', 'email'),
            'to' => $this->config->item('abandoned_lead_to', 'email'),
            'from' => $this->config->item('abandoned_lead_from', 'email'),
            'bcc' => $this->config->item('abandoned_lead_bcc', 'email')
        );

        $success = true;
        if (is_array($details)) {
            if (!empty($details['failed'])) {
                $success = false;
            }
            if (isset($details['attempted']) && (int) $details['attempted'] > 0 && (!isset($details['sent']) || (int) $details['sent'] === 0)) {
                $success = false;
            }
        }

        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode(array(
                'success' => $success,
                'graceMinutes' => $graceMinutes,
                'processed' => $processed,
                'details' => $details,
                'email_config' => $emailCfg,
                'diagnostics' => array(
                    'environment' => (defined('ENVIRONMENT') ? ENVIRONMENT : null),
                    'logs_path' => $logsPath,
                    'logs_writable' => $logWritable
                )
            )));
    }
}
