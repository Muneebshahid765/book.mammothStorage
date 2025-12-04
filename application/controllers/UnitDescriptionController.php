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

        $description = $this->AdminUnitsModel->getDescriptionBySize($size_key);

        if ($description) {
            $data = [
                'size_key'    => $size_key,
                'description' => $description['description'],
                'filters'     => array_map(function ($f) {
                    return $f['name'];
                }, $description['filters'] ?? []),
            ];
        } else {
            $data = [
                'size_key'    => $size_key,
                'description' => '',
                'filters'     => [],
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
