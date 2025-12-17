<?php
defined('BASEPATH') or exit('No direct script access allowed');

class UnitController extends CI_Controller
{

    // Function to display the form view
    public function index()
    {
        $this->load->view('units_view');
    }

    public function fetchInsuranceCoverage($locationCode)
    {

        // ðŸ”¥ Call insurance coverage API
        $insuranceCurl = curl_init();
        curl_setopt_array($insuranceCurl, array(
            CURLOPT_URL => 'https://checkout.mammothstorage.com.au/api/v1/insurance_coverage/getAll',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => json_encode(["location_code" => $locationCode]),
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json'
            ),
        ));
        $insuranceResponse = curl_exec($insuranceCurl);
        curl_close($insuranceCurl);

        $insuranceData = json_decode($insuranceResponse, true);

        return  $insuranceData;
    }


    // Function to handle the API request
    public function fetchUnits()
    {
        $this->load->database();

        // Parse the JSON input from the request body
        $inputData = json_decode(file_get_contents('php://input'), true);

        // Check if locationCode is present in the request
        if (!isset($inputData['locationCode']) || empty($inputData['locationCode'])) {
            echo json_encode(['error' => true, 'message' => 'Missing or invalid locationCode']);
            return;
        }

        $locationCode = trim(htmlspecialchars($inputData['locationCode'])); // Sanitize input

        // Block disabled locations (backward compatible if column doesn't exist)
        $hasEnabled = $this->db->field_exists('is_enabled', 'tbl_locations');
        if ($hasEnabled) {
            $query = $this->db
                ->select('is_enabled')
                ->from('tbl_locations')
                ->where('code', $locationCode)
                ->limit(1)
                ->get();

            $row = $query ? $query->row_array() : null;
            if (!$row || (int) $row['is_enabled'] !== 1) {
                echo json_encode(['error' => true, 'message' => 'This location is currently unavailable.']);
                return;
            }
        }

        // Prepare the SOAP request XML with the provided locationCode
        $soapRequest = <<<XML
    <soap:Envelope xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:soap="http://schemas.xmlsoap.org/soap/envelope/">
        <soap:Body>
            <UnitsInformation xmlns="http://tempuri.org/CallCenterWs/CallCenterWs">
                <sCorpCode>CCBZ</sCorpCode>
                <sLocationCode>{$locationCode}</sLocationCode>
                <sCorpUserName>Ali:::MAMMOTHSW28BGD9OUBBX</sCorpUserName>
                <sCorpPassword>Currie131!</sCorpPassword>
            </UnitsInformation>
        </soap:Body>
    </soap:Envelope>
    XML;

        // Initialize cURL
        $curl = curl_init();

        // Set cURL options
        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://api.smdservers.net/CCWs_3.5/CallCenterWs.asmx',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => $soapRequest,
            CURLOPT_HTTPHEADER => array(
                'Content-Type: text/xml; charset=utf-8',
                'SOAPAction: http://tempuri.org/CallCenterWs/CallCenterWs/UnitsInformation'
            ),
        ));

        // Execute the cURL request and get the response
        $response = curl_exec($curl);

        // Check for cURL errors
        if ($response === false) {
            echo json_encode(['error' => true, 'message' => curl_error($curl)]);
            curl_close($curl);
            return;
        }

        // Close cURL session
        curl_close($curl);

        // Parse the SOAP XML response
        $units = $this->parseSOAPResponse($response);

        // Check if there was an error in parsing the SOAP response
        if (isset($units['error']) && $units['error'] === true) {
            echo json_encode(['error' => true, 'message' => $units['message']]);
            return;
        }

        // Filter out blocked units based on admin configuration
        $units['data'] = $this->filterBlockedUnits($units['data'], $locationCode);

        $locations_data = array();
        if ($locationCode == "L001") {
            $locations_data = array("name" => "Forest Glen", "view_center" => "https://mammothselfstore.com.au/locations/storage-forest-glen/", "address" => "Shop 14 /380 Mons Road, Forest Glen QLD 4556", "number" => "1300 136 851");
        } else if ($locationCode == "L002") {
            $locations_data = array("name" => "Maroochydore", "view_center" => "https://mammothselfstore.com.au/locations/self-storage-maroochydore/", "address" => "31/82 Sugar Rd, Maroochydore QLD 4558, Australia", "number" => "1300 136 851");
        } else if ($locationCode == "L003") {
            $locations_data = array("name" => "Nambour", "view_center" => "https://mammothselfstore.com.au/locations/nambour-vehicle-storage", "address" => "33-49 Zealey Rd, Nambour QLD 4560", "number" => "1300 136 851");
        } else if ($locationCode == "L004") {
            $locations_data = array("name" => "Caloundra", "view_center" => "https://mammothselfstore.com.au/locations/storage-units-caloundra/", "address" => "40-42 Geo Hawkins Cres, Bells Creek QLD 4551", "number" => "1300 136 851");
        } else if ($locationCode == "L005") {
            $locations_data = array("name" => "Hervey Bay", "view_center" => "https://mammothselfstore.com.au/locations/vehicle-storage-hervey-bay/", "address" => "34 Southern Cross Circuit Urangan", "number" => "1300 136 851");
        } else if ($locationCode == "L006") {
            $locations_data = array("name" => "Bundaberg", "view_center" => "https://mammothselfstore.com.au/locations/self-storage-bundaberg/", "address" => "38 Commerical Street, Kensington QLD", "number" => "1300 136 851");
        } else if ($locationCode == "L007") {
            $locations_data = array("name" => "Kunda Park", "view_center" => "https://mammothselfstore.com.au/locations/kunda-park-self-storage/", "address" => "486 Maroochydore Rd, Kunda Park, QLD 4558, Australia", "number" => "1300 136 851");
        } else if ($locationCode == "L008") {
            $locations_data = array("name" => "Gympie", "view_center" => "https://mammothselfstore.com.au/locations/gympie-self-storage/", "address" => "18 Bonnick Rd, Gympie QLD 4570", "number" => "1300 136 851");
        }

        $this->load->model('UnitModel');


        $specials = $this->UnitModel->getUnitSpecials($locationCode);
        $sizeImages = $this->UnitModel->getUnitSizeImages($locationCode);

        // Append base URL and cast numeric fields to float
        foreach ($sizeImages as &$img) {
            $img['url']      = base_url($img['url']);
            $img['dcWidth']  = (float) $img['dcWidth'];
            $img['dcLength'] = (float) $img['dcLength'];
        }

        // $insuranceData  = $this->fetchInsuranceCoverage($locationCode);

        // Return the parsed units as JSON
        // echo json_encode(['error' => false, 'data' => $units['data'], 'location' => $locations_data, 'specials' => $specials, 'insurance_coverage' => $insuranceData]);
        echo json_encode(['error' => false, 'data' => $units['data'], 'location' => $locations_data, 'specials' => $specials, 'size_images' => $sizeImages]);
    }


    // Function to parse the SOAP response and extract units information
    private function parseSOAPResponse($response)
    {
        // Set the timezone
        date_default_timezone_set('Australia/Brisbane');

        // Suppress libxml errors and allow to fetch them manually
        libxml_use_internal_errors(true);

        // Load the SOAP response as SimpleXML
        $xml = simplexml_load_string($response);

        // Handle XML parsing errors
        if ($xml === false) {
            return ["error" => true, "message" => "Failed to parse SOAP response"];
        }

        // Register namespaces for correct XPath queries
        $xml->registerXPathNamespace('soap', 'http://schemas.xmlsoap.org/soap/envelope/');
        $xml->registerXPathNamespace('diffgr', 'urn:schemas-microsoft-com:xml-diffgram-v1');

        // Check for any returned error codes and messages
        $errorCode = $xml->xpath('//RT/Ret_Code');
        $errorMessage = $xml->xpath('//RT/Ret_Msg');

        // If error codes are present, handle them
        if (!empty($errorCode) && !empty($errorMessage)) {
            $code = (string)$errorCode[0];
            $msg = (string)$errorMessage[0];

            // Map error codes to meaningful messages
            $errors = [
                '-95' => "Invalid Unit ID",
                '-98' => "Login failed",
                '-89' => "Invalid API License Key",
                '-99' => "General Exception: $msg"
            ];

            // If an error code is found, return the respective message
            $error = $errors[$code] ?? "Unknown error: $msg";
            return ["error" => true, "message" => $error];
        }

        // Extract units information from the SOAP response
        // Filter: bRentable="true" AND bRented="false" to only show available units
        $result = $xml->xpath('//diffgr:diffgram/NewDataSet/Table[bRentable="true" and bRented="false"]');
        $units = [];

        // Loop through the units and extract relevant information
        if (!empty($result)) {
            foreach ($result as $unit) {
                $units[] = [
                    'UnitID' => (string) $unit->UnitID,
                    'sTypeName' => (string) $unit->sTypeName,
                    'dcWidth' => (float) $unit->dcWidth,
                    'dcLength' => (float) $unit->dcLength,
                    'iFloor' => (int) $unit->iFloor,
                    'sUnitName' => (string) $unit->sUnitName,
                    'sUnitDesc' => isset($unit->sUnitDesc) ? (string) $unit->sUnitDesc : '',
                    'dcStdRateWithoutTax' => number_format((float) ($unit->dcStdRate), 2), // Format to 2 decimals
                    'dcStdRate' => number_format((float) ($unit->dcStdRate * 1.10), 2), // Format to 2 decimals
                    'bRented' => (string) $unit->bRented,
                    'bRentable' => (string) $unit->bRentable,
                    'bClimate' => (string) $unit->bClimate,
                    'bInside' => (string) $unit->bInside,
                    'bAlarm' => (string) $unit->bAlarm,

                ];
            }

            // Return extracted units data
            return ["error" => false, "data" => $units];
        } else {
            // If no unit information is found, return an error
            return ["error" => true, "message" => "No unit information found"];
        }
    }



    public function getAllUnits()
    {
        // Get user session data
        $user_data = $this->session->userdata('user_data');

        // If the user is not logged in, return an error
        if (!$user_data || !$user_data['logged_in']) {
            $this->output->set_content_type('application/json')->set_output(json_encode([
                'data' => [],
                'error' => 'User not logged in.'
            ]));
            return;
        }

        // Extract tenant_id and location from session data
        $tenant_id = $user_data['user_id'];
        $location = $user_data['location'];

        // Fetch account balance via SOAP request
        $response = $this->fetchAllAccountUnits($location, $tenant_id);

        if (!$response) {
            $this->output->set_content_type('application/json')->set_output(json_encode([
                'data' => [],
                'error' => 'Failed to fetch account balance.'
            ]));
            return;
        }

        // Handle XML parsing errors
        libxml_use_internal_errors(true);
        $xml = simplexml_load_string($response);

        if ($xml === false) {
            $this->output->set_content_type('application/json')->set_output(json_encode([
                'data' => [],
                'error' => 'Failed to parse the account balance data.'
            ]));
            return;
        }

        $xml->registerXPathNamespace('soap', 'http://schemas.xmlsoap.org/soap/envelope/');
        $xml->registerXPathNamespace('diffgr', 'urn:schemas-microsoft-com:xml-diffgram-v1');

        // Handle errors
        $error_code = $xml->xpath('//diffgr:diffgram/NewDataSet/RT/Ret_Code');
        $error_msg = $xml->xpath('//diffgr:diffgram/NewDataSet/RT/Ret_Msg');

        if (!empty($error_code) && (int)$error_code[0] < 0) {
            $this->output->set_content_type('application/json')->set_output(json_encode([
                'success' => false,
                'error' => (string)$error_msg[0]
            ]));
            return;
        }

        // Extract balance details and handle errors
        $units = $xml->xpath('//diffgr:diffgram/NewDataSet/Table1');
        $unit_data = [];

        foreach ($units as $balance) {
            $unit_id = (int)$balance->UnitID;
            $ledger_id = (int)$balance->LedgerID;
            $unit_name = (string)$balance->sUnitName; // Extract unit name

            // If UnitID already exists, sum up the balance
            if (isset($unit_data[$unit_id])) {
            } else {
                // Create a new entry for each unique UnitID
                $unit_data[$unit_id] = [
                    'unit_id' => $unit_id,
                    'ledger_id' => $ledger_id,
                    'unit_name' => $unit_name,
                    'paid_thru' => $this->getPaidThroughDateByLedgerID($location, $ledger_id),
                ];
            }
        }

        // Prepare arrays for JSON response
        $unit_ids = [];
        $ledger_ids = [];
        $unit_names = [];
        $paid_thrus = [];

        foreach ($unit_data as $data) {
            $unit_ids[] = $data['unit_id'];
            $ledger_ids[] = $data['ledger_id'];
            $unit_names[] = $data['unit_name']; // Add unit names to a separate array
            $paid_thrus[] = $data['paid_thru'];
        }

        // Return the account balance data in the required format
        $this->output->set_content_type('application/json')->set_output(json_encode([
            'unit_ids' => $unit_ids,
            'ledger_ids' => $ledger_ids,
            'unit_names' => $unit_names,
            'paid_thrus' => $paid_thrus
        ]));
    }

    // Function to fetch account balance
    private function fetchAllAccountUnits($location, $tenant_id)
    {
        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_URL => 'https://api.smdservers.net/CCWs_3.5/CallCenterWs.asmx',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => '
            <soap:Envelope xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:soap="http://schemas.xmlsoap.org/soap/envelope/">
              <soap:Body>
                <CustomerAccountsBalanceDetails xmlns="http://tempuri.org/CallCenterWs/CallCenterWs">
                  <sCorpCode>CCBZ</sCorpCode>
                  <sLocationCode>' . $location . '</sLocationCode>
                  <sCorpUserName>Ali:::MAMMOTHSW28BGD9OUBBX</sCorpUserName>
                  <sCorpPassword>Currie131!</sCorpPassword>
                  <iTenantID>' . $tenant_id . '</iTenantID>
                </CustomerAccountsBalanceDetails>
              </soap:Body>
            </soap:Envelope>',
            CURLOPT_HTTPHEADER => [
                'SOAPAction: http://tempuri.org/CallCenterWs/CallCenterWs/CustomerAccountsBalanceDetails',
                'Content-Type: text/xml; charset=utf-8',
            ],
        ]);

        $response = curl_exec($curl);
        curl_close($curl);
        return $response;
    }



    private function parsePaidThroughDate($response)
    {

        if (!$response) {
            $this->output->set_content_type('application/json')->set_output(json_encode([
                'data' => [],
                'error' => 'Failed to fetch paid through date.'
            ]));
            return;
        }

        // Handle XML parsing errors
        libxml_use_internal_errors(true);
        $xml = simplexml_load_string($response);

        if ($xml === false) {
            $this->output->set_content_type('application/json')->set_output(json_encode([
                'data' => [],
                'error' => 'Failed to parse the unit paid thru data.'
            ]));
            return;
        }

        $xml->registerXPathNamespace('soap', 'http://schemas.xmlsoap.org/soap/envelope/');
        $xml->registerXPathNamespace('diffgr', 'urn:schemas-microsoft-com:xml-diffgram-v1');

        // Handle errors
        $error_code = $xml->xpath('//diffgr:diffgram/NewDataSet/RT/Ret_Code');
        $error_msg = $xml->xpath('//diffgr:diffgram/NewDataSet/RT/Ret_Msg');

        if (!empty($error_code) && (int)$error_code[0] < 0) {
            $this->output->set_content_type('application/json')->set_output(json_encode([
                'success' => false,
                'error' => (string)$error_msg[0]
            ]));
            return;
        }

        // Extract balance details and handle errors
        $ledgers = $xml->xpath('//diffgr:diffgram/NewDataSet/Table');
        $paidThru = "";

        foreach ($ledgers as $ledger) {
            $timestamp = (string)$ledger->dPaidThru;
            $date = (new DateTime($timestamp))->format('d/m/Y'); // Extracts and formats the date as DD/MM/YYYY
            $paidThru = $date;
        }

        return $paidThru;
    }
    private function getPaidThroughDateByLedgerID($location, $ledgerID)
    {
        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_URL => 'https://api.smdservers.net/CCWs_3.5/CallCenterWs.asmx',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => '
            <soap:Envelope xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:soap="http://schemas.xmlsoap.org/soap/envelope/">
              <soap:Body>
                <PaidThroughDateByLedgerID xmlns="http://tempuri.org/CallCenterWs/CallCenterWs">
                  <sCorpCode>CCBZ</sCorpCode>
                  <sLocationCode>' . $location . '</sLocationCode>
                  <sCorpUserName>Ali:::MAMMOTHSW28BGD9OUBBX</sCorpUserName>
                  <sCorpPassword>Currie131!</sCorpPassword>
                  <iLedgerID>' . $ledgerID . '</iLedgerID>
                </PaidThroughDateByLedgerID>
              </soap:Body>
            </soap:Envelope>',
            CURLOPT_HTTPHEADER => [
                'SOAPAction: http://tempuri.org/CallCenterWs/CallCenterWs/PaidThroughDateByLedgerID',
                'Content-Type: text/xml; charset=utf-8',
            ],
        ]);

        $response = curl_exec($curl);
        curl_close($curl);

        return $this->parsePaidThroughDate($response);
    }

    /**
     * Filter units based on admin block rules
     * @param array $units Array of units from SOAP response
     * @param string $locationCode Location code (e.g., L001)
     * @return array Filtered units array
     */
    private function filterBlockedUnits($units, $locationCode)
    {
        if (empty($units) || !is_array($units)) {
            return $units;
        }

        // Load database
        $this->load->database();

        // Get location_id from location code
        $this->db->select('id');
        $this->db->from('tbl_locations');
        $this->db->where('code', $locationCode);
        $query = $this->db->get();
        $location = $query->row_array();

        if (!$location) {
            // Location not found, return units unfiltered
            return $units;
        }

        $location_id = $location['id'];

        // Get all block rules for this location (including "all locations" rules)
        $this->db->select('id, location_id, size_key, type_id');
        $this->db->from('tbl_unit_display_blocks');
        $this->db->group_start();
        $this->db->where('location_id', $location_id);
        $this->db->or_where('location_id', null);
        $this->db->group_end();
        $query = $this->db->get();
        $blockRules = $query->result_array();

        if (empty($blockRules)) {
            // No block rules, return all units
            return $units;
        }

        // Get unit types mapping for comparison
        $this->db->select('id, name, slug');
        $this->db->from('tbl_unit_types');
        $query = $this->db->get();
        $unitTypes = $query->result_array();

        $slugify = function ($value) {
            $value = strtolower(trim((string) $value));
            $value = preg_replace('/[^a-z0-9]+/', '-', $value);
            return trim($value, '-');
        };

        // Create a map of type keys to ALL matching IDs for quick lookup (handles duplicates)
        $typeKeyToIds = [];
        foreach ($unitTypes as $type) {
            $id = isset($type['id']) ? (int) $type['id'] : 0;
            if ($id <= 0) {
                continue;
            }

            $name = isset($type['name']) ? trim((string) $type['name']) : '';
            $slug = isset($type['slug']) ? trim((string) $type['slug']) : '';

            $keys = [];
            if ($name !== '') {
                $keys[] = strtolower($name);
                $keys[] = $slugify($name);
            }
            if ($slug !== '') {
                $keys[] = strtolower($slug);
                $keys[] = $slugify($slug);
            }

            $keys = array_values(array_unique(array_filter($keys, function ($k) {
                return $k !== '';
            })));

            foreach ($keys as $k) {
                if (!isset($typeKeyToIds[$k])) {
                    $typeKeyToIds[$k] = [];
                }
                if (!in_array($id, $typeKeyToIds[$k], true)) {
                    $typeKeyToIds[$k][] = $id;
                }
            }
        }

        // Filter units
        $filteredUnits = [];
        foreach ($units as $unit) {
            // Build size_key for this unit
            $size_key = $unit['dcWidth'] . 'x' . $unit['dcLength'];

            // Determine unit type_id from sTypeName
            $type_ids = [];
            if (!empty($unit['sTypeName'])) {
                $typeName = strtolower(trim($unit['sTypeName']));
                $typeSlug = $slugify($typeName);

                if (isset($typeKeyToIds[$typeName]) && is_array($typeKeyToIds[$typeName])) {
                    $type_ids = array_merge($type_ids, $typeKeyToIds[$typeName]);
                }

                if ($typeSlug !== '' && isset($typeKeyToIds[$typeSlug]) && is_array($typeKeyToIds[$typeSlug])) {
                    $type_ids = array_merge($type_ids, $typeKeyToIds[$typeSlug]);
                }

                $type_ids = array_values(array_unique(array_map('intval', $type_ids)));
            }

            // Check if this unit matches any block rule
            $isBlocked = false;
            foreach ($blockRules as $rule) {
                $locationMatch = ($rule['location_id'] === null || $rule['location_id'] == $location_id);
                $sizeMatch = ($rule['size_key'] === null || $rule['size_key'] === $size_key);
                $typeMatch = ($rule['type_id'] === null || (!empty($type_ids) && in_array((int) $rule['type_id'], $type_ids, true)));

                if ($locationMatch && $sizeMatch && $typeMatch) {
                    $isBlocked = true;
                    break;
                }
            }

            // Only include unit if it's not blocked
            if (!$isBlocked) {
                $filteredUnits[] = $unit;
            }
        }

        return $filteredUnits;
    }
}
