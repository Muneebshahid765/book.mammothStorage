<?php
defined('BASEPATH') or exit('No direct script access allowed');

class AdminUnitsModel extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    /**
     * Get all unit sizes - merges sizes from tbl_unit_size_images AND live Sitelink API
     */
    public function getAllSizes()
    {
        $sizeMap = [];

        $this->db->distinct();
        $this->db->select('dcWidth, dcLength');
        $this->db->from('tbl_unit_size_images');
        $query = $this->db->get();

        if ($query) {
            foreach ($query->result_array() as $size) {
                $width  = (float) $size['dcWidth'];
                $length = (float) $size['dcLength'];

                if ($width <= 0 || $length <= 0) {
                    continue;
                }

                $key = $this->normalizeSizeKeyFromDims($width, $length);
                if ($key === '') {
                    continue;
                }

                if (!isset($sizeMap[$key])) {
                    $sizeMap[$key] = [
                        'width' => $width,
                        'length' => $length,
                        'is_live' => false
                    ];
                }
            }
        }

        // Fallback sizes from descriptions (so metadata rows are editable when images missing)
        if ($this->db->table_exists('tbl_unit_size_descriptions')) {
            $this->db->select('size_key');
            $this->db->from('tbl_unit_size_descriptions');
            $descQuery = $this->db->get();

            if ($descQuery !== false) {
                foreach ($descQuery->result_array() as $row) {
                    $canonicalKey = $this->normalizeSizeKey($row['size_key']);
                    if ($canonicalKey === '' || $canonicalKey === 'parking' || isset($sizeMap[$canonicalKey])) {
                        continue;
                    }

                    if (strpos($canonicalKey, 'x') === false) {
                        continue;
                    }

                    $parts = explode('x', $canonicalKey);
                    if (count($parts) !== 2) {
                        continue;
                    }

                    $width = (float) $parts[0];
                    $length = (float) $parts[1];
                    if ($width <= 0 || $length <= 0) {
                        continue;
                    }

                    $sizeMap[$canonicalKey] = [
                        'width' => $width,
                        'length' => $length,
                        'is_live' => false
                    ];
                }
            }
        }

        // Live sizes from SiteLink (ensures we only keep sizes that actually exist at locations)
        $locationCodes = $this->getAllSitelinkLocationCodes();
        foreach ($locationCodes as $locationCode) {
            $apiSizes = $this->fetchSizesFromSitelink($locationCode);
            foreach ($apiSizes as $apiSize) {
                $width  = (float) $apiSize['dcWidth'];
                $length = (float) $apiSize['dcLength'];
                $key    = $this->normalizeSizeKeyFromDims($width, $length);

                if ($key === '') {
                    continue;
                }

                if (!isset($sizeMap[$key])) {
                    $sizeMap[$key] = [
                        'width' => $width,
                        'length' => $length,
                        'is_live' => true
                    ];
                } else {
                    $sizeMap[$key]['is_live'] = true;
                }
            }
        }

        $liveSizeMap = array_filter($sizeMap, function ($entry) {
            return !empty($entry['is_live']);
        });

        if (!empty($liveSizeMap)) {
            $sizeMap = $liveSizeMap;
        }

        $formatted_sizes = [];
        foreach ($sizeMap as $key => $dims) {
            $width  = $dims['width'];
            $length = $dims['length'];

            $widthLabel  = rtrim(rtrim(number_format($width, 2, '.', ''), '0'), '.');
            $lengthLabel = rtrim(rtrim(number_format($length, 2, '.', ''), '0'), '.');
            $areaLabel   = number_format($width * $length, 2);
            $label       = $widthLabel . 'm × ' . $lengthLabel . 'm (' . $areaLabel . 'm²)';

            $formatted_sizes[] = [
                'key' => $key,
                'label' => $label,
                'width' => $width,
                'length' => $length
            ];
        }

        // Add parking
        $formatted_sizes[] = [
            'key' => 'parking',
            'label' => 'Parking',
            'width' => 0,
            'length' => 0
        ];

        // Sort for consistent presentation
        usort($formatted_sizes, function ($a, $b) {
            if ($a['width'] != $b['width']) {
                return $a['width'] <=> $b['width'];
            }
            return $a['length'] <=> $b['length'];
        });

        return $formatted_sizes;
    }

    /**
     * Fetch unit sizes from Sitelink API for a given location
     */
    private function fetchSizesFromSitelink($locationCode)
    {
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

        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_URL => 'https://api.smdservers.net/CCWs_3.5/CallCenterWs.asmx',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => $soapRequest,
            CURLOPT_HTTPHEADER => [
                'Content-Type: text/xml; charset=utf-8',
                'SOAPAction: http://tempuri.org/CallCenterWs/CallCenterWs/UnitsInformation'
            ],
            CURLOPT_TIMEOUT => 10,
        ]);

        $response = curl_exec($curl);
        curl_close($curl);

        if ($response === false) {
            return [];
        }

        // Parse SOAP response
        libxml_use_internal_errors(true);
        $xml = simplexml_load_string($response);
        if ($xml === false) {
            return [];
        }

        $xml->registerXPathNamespace('soap', 'http://schemas.xmlsoap.org/soap/envelope/');
        $xml->registerXPathNamespace('diffgr', 'urn:schemas-microsoft-com:xml-diffgram-v1');

        $result = $xml->xpath('//diffgr:diffgram/NewDataSet/Table[bRentable="true"]');
        $sizes = [];

        if (!empty($result)) {
            foreach ($result as $unit) {
                $sizes[] = [
                    'dcWidth' => (float) $unit->dcWidth,
                    'dcLength' => (float) $unit->dcLength
                ];
            }
        }

        return $sizes;
    }

    /**
     * Get all fit filters
     */
    public function getAllFilters()
    {
        $this->db->select('id, name, slug');
        $this->db->from('tbl_unit_fit_filters');
        $this->db->order_by('sort_order', 'ASC');
        $query = $this->db->get();
        return $query->result_array();
    }

    /**
     * Get description for a size
     */
    public function getDescriptionBySize($size_key)
    {
        $candidates = $this->buildSizeKeyCandidates($size_key);

        $description = null;
        foreach ($candidates as $candidate) {
            $this->db->select('*');
            $this->db->from('tbl_unit_size_descriptions');
            $this->db->where('size_key', $candidate);
            $query = $this->db->get();
            $row = $query ? $query->row_array() : null;
            if ($row) {
                $description = $row;
                break;
            }
        }
        
        if ($description) {
            $description['filters'] = $this->getFiltersForDescription($description['id']);
        }
        
        return $description;
    }

    private function buildSizeKeyCandidates($size_key)
    {
        $raw = trim((string) $size_key);
        if ($raw === '') {
            return [];
        }

        $candidates = [$raw];

        $normalized = strtolower($raw);
        $normalized = str_replace(['×', ' '], ['x', ''], $normalized);
        $normalized = preg_replace('/[^0-9x\.\-]/', '', $normalized);
        if ($normalized !== '' && $normalized !== $raw) {
            $candidates[] = $normalized;
        }

        if (strpos($normalized, 'x') !== false) {
            $parts = explode('x', $normalized);
            if (count($parts) === 2) {
                $w = (float) $parts[0];
                $l = (float) $parts[1];

                $w1 = rtrim(rtrim(number_format($w, 1, '.', ''), '0'), '.');
                $l1 = rtrim(rtrim(number_format($l, 1, '.', ''), '0'), '.');
                $w2 = number_format($w, 1, '.', '');
                $l2 = number_format($l, 1, '.', '');

                $w3 = rtrim(rtrim(number_format($w, 2, '.', ''), '0'), '.');
                $l3 = rtrim(rtrim(number_format($l, 2, '.', ''), '0'), '.');
                $w4 = number_format($w, 2, '.', '');
                $l4 = number_format($l, 2, '.', '');

                $candidates[] = $w1 . 'x' . $l1;
                $candidates[] = $w2 . 'x' . $l2;

                $candidates[] = $w3 . 'x' . $l3;
                $candidates[] = $w4 . 'x' . $l4;

                $w0 = rtrim(rtrim(number_format($w, 0, '.', ''), '0'), '.');
                $l0 = rtrim(rtrim(number_format($l, 0, '.', ''), '0'), '.');
                if ($w0 !== '' && $l0 !== '') {
                    $candidates[] = $w0 . 'x' . $l0;
                }

                // Also try swapped dimensions (some SiteLink locations can return
                // width/length reversed, e.g. 2x3 vs 3x2).
                if ($w > 0 && $l > 0 && $w !== $l) {
                    $candidates[] = $l1 . 'x' . $w1;
                    $candidates[] = $l2 . 'x' . $w2;
                    $candidates[] = $l3 . 'x' . $w3;
                    $candidates[] = $l4 . 'x' . $w4;

                    if ($w0 !== '' && $l0 !== '') {
                        $candidates[] = $l0 . 'x' . $w0;
                    }
                }
            }
        }

        $candidates = array_values(array_unique(array_filter($candidates, function ($v) {
            return $v !== '';
        })));

        return $candidates;
    }

    /**
     * Get filters for a description
     */
    private function getFiltersForDescription($description_id)
    {
        $this->db->select('f.id, f.name, f.slug');
        $this->db->from('tbl_unit_size_fit_filters tusff');
        $this->db->join('tbl_unit_fit_filters f', 'tusff.filter_id = f.id');
        $this->db->where('tusff.description_id', $description_id);
        $query = $this->db->get();
        return $query->result_array();
    }

    /**
     * Bulk update descriptions and filters for selected sizes
     */
    public function bulkUpdateDescriptions($size_keys, $filter_ids, $description_text)
    {
        foreach ($size_keys as $size_key) {
            // Check if description exists
            $this->db->where('size_key', $size_key);
            $query = $this->db->get('tbl_unit_size_descriptions');
            $existing = $query->row_array();
            
            if ($existing) {
                // Update existing
                $description_id = $existing['id'];
                $this->db->where('id', $description_id);
                $this->db->update('tbl_unit_size_descriptions', [
                    'description' => $description_text,
                    'updated_at' => date('Y-m-d H:i:s')
                ]);
                
                // Delete old filter mappings
                $this->db->delete('tbl_unit_size_fit_filters', ['description_id' => $description_id]);
            } else {
                // Insert new
                $this->db->insert('tbl_unit_size_descriptions', [
                    'size_key' => $size_key,
                    'description' => $description_text
                ]);
                $description_id = $this->db->insert_id();
            }
            
            // Insert new filter mappings
            if (is_array($filter_ids) && count($filter_ids) > 0) {
                foreach ($filter_ids as $filter_id) {
                    $this->db->insert('tbl_unit_size_fit_filters', [
                        'description_id' => $description_id,
                        'filter_id' => $filter_id
                    ]);
                }
            }
        }
        
        return true;
    }

    /**
     * Get all descriptions with their filters
     */
    public function getAllDescriptions()
    {
        $this->db->select('*');
        $this->db->from('tbl_unit_size_descriptions');
        $this->db->order_by('size_key', 'ASC');
        $query = $this->db->get();
        $descriptions = $query->result_array();
        
        foreach ($descriptions as &$desc) {
            $desc['filters'] = $this->getFiltersForDescription($desc['id']);
        }
        
        return $descriptions;
    }
}
