<?php

class Produksi_model extends CI_Model
{

    public function check_process_icon($date_started, $date_finished)
    {
        $process_icon = '<i class="far fa-clock fa-lg"></i>';

        if ($date_started != NULL && $date_finished == NULL) {
            $process_icon = '<i class="fas fa-circle-notch fa-lg fa-spin"></i>';
        }

        if ($date_started != NULL && $date_finished != NULL) {
            $process_icon = '<i class="fas fa-check fa-lg"></i>';
        }

        return $process_icon;
    }

    public function is_col_exist($table, $col)
    {
        return $this->db->query("SHOW COLUMNS FROM `{$table}` LIKE '{$col}'")->num_rows();
    }

    public function select_basic_card_data($table)
    {

        $sql = "SELECT
                    `{$table}`.`order_detail_id`,
                    `{$table}`.`date_started`,
                    `{$table}`.`person_in_charge`,
                    `order`.`required_date`,
                    `order_detail`.`description` AS `item_desc`,
                    `item`.`icon`,";

        $sql .= $this->is_col_exist($table, 'output') != 0 ? "`{$table}`.`output`," : '';

        $sql .= "(CASE 
                    WHEN date_started IS NULL THEN 'menunggu' 
                    WHEN date_started IS NOT NULL AND date_finished IS NULL THEN 'dikerjakan' 
                    WHEN date_finished IS NOT NULL THEN 'selesai' END
                ) AS status ";

        $sql .= "FROM `{$table}`";
        $sql .= "LEFT JOIN `order_detail` ON `{$table}`.`order_detail_id` = `order_detail`.`order_detail_id`";
        $sql .= "LEFT JOIN `item` ON `order_detail`.`item_id` = `item`.`item_id`";
        $sql .= "LEFT JOIN `order` ON `order_detail`.`order_id` = `order`.`order_id` ";
        $sql .= "WHERE `{$table}`.`date_finished` IS NULL";

        return $sql;
    }


    public function get_undesigned_list()
    {

        return $this->db->query($this->select_basic_card_data('design_process'))->result_array();
    }

    public function get_unprepared_list()
    {

        return $this->db->query($this->select_basic_card_data('preparation_process'))->result_array();
    }


    public function get_unembroideried_list()
    {

        // function is_unembroideried(array $db_result_array)
        // {
        //     return $db_result_array['is_designed'] == 1 && $db_result_array['is_prepared'] == 1 && $db_result_array['is_embroideried'] == 0;
        // }

        // return array_filter($this->get_production_card_data(), "is_unembroideried");
    }

    public function get_unfinished_list()
    {

        function is_unfinished(array $db_result_array)
        {
            return $db_result_array['is_designed'] == 1 && $db_result_array['is_prepared'] == 1 && $db_result_array['is_embroideried'] == 1 && $db_result_array['is_finished'] == 0;
        }

        return array_filter($this->get_production_card_data(), "is_unfinished");
    }

    public function get_unpacked_list()
    {

        function is_unpacked(array $db_result_array)
        {
            return $db_result_array['is_designed'] == 1 && $db_result_array['is_prepared'] == 1 && $db_result_array['is_embroideried'] == 1 && $db_result_array['is_finished'] == 1 && $db_result_array['is_packed'] == 0;
        }

        return array_filter($this->get_production_card_data(), "is_unpacked");
    }

    public function get_production_card_data()
    {

        $cards = [

            [
                'title' => 'Desain',
                'icon' => 'fas fa-palette mr-2',
                'url' => 'produksi/desain',
                'data' => $this->db->query($this->select_basic_card_data('process_design'))->result_array()
            ],
            [
                'title' => 'Persiapan Bahan Baku',
                'icon' => 'fas fa-tape mr-2',
                'url' => 'produksi/persiapan',
                'data' => $this->db->query($this->select_basic_card_data('process_preparation'))->result_array()
            ],
            [
                'title' => 'Bordir',
                'icon' => 'fab fa-shirtsinbulk mr-2',
                'url' => 'produksi/bordir',
                'data' => $this->db->query($this->select_basic_card_data('process_embroidery'))->result_array()
            ],
            [
                'title' => 'Finishing',
                'icon' => 'fas fa-cut mr-2',
                'url' => 'produksi/bordir',
                'data' => $this->db->query($this->select_basic_card_data('process_finishing'))->result_array()
            ],
            [
                'title' => 'Packaging',
                'icon' => 'fas fa-gift mr-2',
                'url' => 'produksi/bordir',
                'data' => $this->db->query($this->select_basic_card_data('process_finishing'))->result_array()
            ]

        ];

        return $cards;
    }
}