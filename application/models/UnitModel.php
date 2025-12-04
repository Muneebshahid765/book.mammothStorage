<?php
defined('BASEPATH') or exit('No direct script access allowed');

class UnitModel extends CI_Model
{
    /*
            Available Discount Types:
                - percentage
                - fixed
                - seasonal
        */

    public function getUnitSpecials($locationCode)
    {
        // Select the required columns
        $this->db->select('
            tus.id AS id,
            tus.title AS title,
            tus.dcWidth AS dcWidth,
            tus.dcLength AS dcLength,
            tus.discount_type AS sDiscountType,
            tus.discount_price AS dDiscountPrice,
            tus.months AS months,
            tus.months_to_pay AS months_to_pay,
            tus.free_months AS free_months,
            tus.concession_plan_id  AS concession_plan_id,
            tus.location_id  AS LocationID
        ');

        // Specify the tables and joins
        $this->db->from('tbl_unit_specials AS tus');
        if ($this->db->table_exists('tbl_unit_specials_locations')) {
            $this->db->join('tbl_unit_specials_locations AS tusl', 'tus.id = tusl.special_id');
            $this->db->join('tbl_locations AS l', 'tusl.location_id = l.id');
        } else {
            $this->db->join('tbl_locations AS l', 'tus.location_id = l.id');
        }
        // Add the condition to filter by token
        $this->db->where('l.code', $locationCode);

        // Only active specials (if the column exists).
        // This keeps things compatible with older databases that don't yet
        // have the is_active column, instead of throwing a DB error.
        if ($this->db->field_exists('is_active', 'tbl_unit_specials')) {
            $this->db->where('tus.is_active', 1);
        }

        // Order by created_at
        $this->db->order_by('tus.created_at', 'ASC');

        // Execute the query
        $query = $this->db->get();

        // Gracefully handle database errors (e.g. missing columns/tables in production)
        if ($query === false) {
            log_message('error', 'UnitModel::getUnitSpecials query failed: ' . json_encode($this->db->error()));
            return [];
        }

        if ($query->num_rows() === 0) {
            return [];
        }

        $specials = $query->result_array();

        // Attach size mappings from tbl_unit_specials_sizes so frontend can
        // match by size tables instead of relying solely on dcWidth/dcLength.
        $specialIds = array_column($specials, 'id');

        if (!empty($specialIds)) {
            $this->db->select('special_id, size_key');
            $this->db->from('tbl_unit_specials_sizes');
            $this->db->where_in('special_id', $specialIds);
            $sizesQuery = $this->db->get();

            $sizeMap = [];
            if ($sizesQuery === false) {
                log_message('error', 'UnitModel::getUnitSpecials sizes query failed: ' . json_encode($this->db->error()));
            } elseif ($sizesQuery->num_rows() > 0) {
                foreach ($sizesQuery->result_array() as $row) {
                    $sizeMap[$row['special_id']][] = $row['size_key'];
                }
            }

            foreach ($specials as &$special) {
                $id = isset($special['id']) ? $special['id'] : null;
                $special['size_keys'] = $id !== null && isset($sizeMap[$id]) ? $sizeMap[$id] : [];
            }
            unset($special);
        }

        return $specials;
    }


    public function getUnitSizeImages($locationCode)
    {
        // Select the required columns
        $this->db->select('
            usi.dcWidth AS dcWidth,
            usi.dcLength AS dcLength,
            usi.url AS url,
            usi.location_id  AS LocationID
        ');

        // Specify the tables and joins
        $this->db->from('tbl_unit_size_images AS usi');

        // Order by created_at and limit to 1 result
        $this->db->order_by('usi.created_at', 'ASC');

        // Execute the query
        $query = $this->db->get();

        // Gracefully handle database errors (e.g. table missing)
        if ($query === false) {
            log_message('error', 'UnitModel::getUnitSizeImages query failed: ' . json_encode($this->db->error()));
            return [];
        }

        // Check if any row exists and fetch the result
        if ($query->num_rows() > 0) {
            return $query->result_array(); // Fetch all rows as an associative array
        } else {
            return []; // Return an empty array if no data is found
        }
    }
}
