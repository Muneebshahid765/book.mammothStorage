<?php
defined('BASEPATH') or exit('No direct script access allowed');

class LocationConfigController extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->helper('url');
    }

    /**
     * Get lead time configuration for a specific location
     * Accepts: location_code (e.g., L001)
     * Returns: JSON with lead_time_days
     */
    public function lead_time()
    {
        // Accept both GET and POST
        $location_code = $this->input->get('location_code') ?: $this->input->post('location_code');
        
        if (empty($location_code)) {
            $this->output
                ->set_content_type('application/json')
                ->set_status_header(400)
                ->set_output(json_encode([
                    'success' => false,
                    'message' => 'location_code parameter is required',
                    'lead_time_days' => 1 // Default fallback
                ]));
            return;
        }

        // Find location by code
        $this->db->select('id');
        $this->db->from('tbl_locations');
        $this->db->where('code', $location_code);
        $query = $this->db->get();
        $location = $query->row_array();

        if (!$location) {
            $this->output
                ->set_content_type('application/json')
                ->set_status_header(404)
                ->set_output(json_encode([
                    'success' => false,
                    'message' => 'Location not found',
                    'lead_time_days' => 1 // Default fallback
                ]));
            return;
        }

        $location_id = $location['id'];

        // 1) Try to get the ACTIVE lead time option (matches SPECIAL admin behaviour)
        $this->db->select('lead_time_days');
        $this->db->from('tbl_location_lead_times');
        $this->db->where('location_id', $location_id);
        $this->db->where('is_active', 1);
        $query = $this->db->get();
        $lead_time = $query->row_array();

        $lead_time_days = null;
        if ($lead_time && isset($lead_time['lead_time_days']) && $lead_time['lead_time_days'] !== null) {
            $lead_time_days = (int)$lead_time['lead_time_days'];
        }

        // 2) If no active option, fall back to the smallest configured value
        if ($lead_time_days === null) {
            $this->db->select_min('lead_time_days', 'min_days');
            $this->db->from('tbl_location_lead_times');
            $this->db->where('location_id', $location_id);
            $query = $this->db->get();
            $min_row = $query->row_array();

            if ($min_row && isset($min_row['min_days']) && $min_row['min_days'] !== null) {
                $lead_time_days = (int)$min_row['min_days'];
            } else {
                // No configuration at all, default to 1 day
                $lead_time_days = 1;
            }
        }

        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode([
                'success' => true,
                'lead_time_days' => $lead_time_days,
                'location_code' => $location_code
            ]));
    }
}
