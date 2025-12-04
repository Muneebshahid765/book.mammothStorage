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
     * Get all unit sizes
     */
    public function getAllSizes()
    {
        // Use CI's distinct() helper so DISTINCT is not treated as a column name
        $this->db->distinct();
        $this->db->select('dcWidth, dcLength');
        $this->db->from('tbl_unit_size_images');
        $this->db->order_by('dcWidth', 'ASC');
        $this->db->order_by('dcLength', 'ASC');
        $query = $this->db->get();
        $sizes = $query->result_array();
        
        $formatted_sizes = [];
        foreach ($sizes as $size) {
            $key = $size['dcWidth'] . 'x' . $size['dcLength'];
            $formatted_sizes[] = [
                'key' => $key,
                'label' => $key,
                'width' => $size['dcWidth'],
                'length' => $size['dcLength']
            ];
        }
        
        // Add parking
        $formatted_sizes[] = [
            'key' => 'parking',
            'label' => 'Parking',
            'width' => 0,
            'length' => 0
        ];
        
        return $formatted_sizes;
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
        $this->db->select('*');
        $this->db->from('tbl_unit_size_descriptions');
        $this->db->where('size_key', $size_key);
        $query = $this->db->get();
        $description = $query->row_array();
        
        if ($description) {
            $description['filters'] = $this->getFiltersForDescription($description['id']);
        }
        
        return $description;
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
