<?php
defined('BASEPATH') or exit('No direct script access allowed');

class UnitDescriptionController extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('admin/AdminUnitsModel', 'AdminUnitsModel');
    }

    /**
     * Get description for a size key like "3x1.5".
     * Expects JSON body: { "size_key": "3x1.5" }
     */
    public function get()
    {
        $input = json_decode($this->input->raw_input_stream, true);
        $size_key = isset($input['size_key']) ? trim($input['size_key']) : '';

        if ($size_key === '') {
            return $this->output
                ->set_content_type('application/json')
                ->set_status_header(400)
                ->set_output(json_encode([
                    'success' => false,
                    'message' => 'size_key is required'
                ]));
        }

        // Debug log
        log_message('debug', '[UnitDescriptionController] Received size_key: ' . $size_key);
        
        $description = $this->AdminUnitsModel->getDescriptionBySize($size_key);
        
        // Debug log
        log_message('debug', '[UnitDescriptionController] Description found: ' . ($description ? 'YES' : 'NO'));
        if ($description) {
            log_message('debug', '[UnitDescriptionController] Filters: ' . json_encode($description['filters'] ?? []));
        }

        $buildingLabel = $description['building_label'] ?? '';
        $floorLabel = $description['floor_label'] ?? '';

        if ($description) {
            $data = [
                'size_key'       => $size_key,
                'description'    => $description['description'],
                'building_label' => $buildingLabel,
                'floor_label'    => $floorLabel,
                'filters'        => array_map(function ($f) {
                    // Return the full filter object with both name and slug
                    return [
                        'name' => $f['name'],
                        'slug' => $f['slug']
                    ];
                }, $description['filters'] ?? []),
            ];
        } else {
            $data = [
                'size_key'       => $size_key,
                'description'    => '',
                'building_label' => '',
                'floor_label'    => '',
                'filters'        => [],
            ];
        }

        return $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode([
                'success' => true,
                'data'    => $data,
            ]));
    }
}
