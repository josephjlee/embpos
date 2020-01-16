<?php

class Produksi_model extends CI_Model
{
    public function simpan($production)
    {
        if (!empty($production['production_id'])) {
            return $this->perbarui($production);
        }

        return $this->buat($production);
    }

    public function buat($production)
    {
        $design_data = $this->siapkan_data($production);

        return $this->db->insert('production', $design_data);
    }

    public function perbarui($production)
    {
        $production_data = $this->siapkan_data($production);

        $this->db->where('production_id', $production['production_id']);

        return $this->db->update('production', $production_data);
    }

    public function rekam_output($output, $table)
    {
        $output_data = $this->siapkan_data($output);

        return $this->db->insert($table, $output_data);
    }

    public function siapkan_data($production)
    {
        $design_db_data = [];

        foreach ($production as $col => $val) {
            $design_db_data[$col] = $val;

            if ($col == 'labor_price') {
                $design_db_data[$col] = str_replace(',', '', $val);
            }
        }

        return $design_db_data;
    }

    public function get_design_list()
    {
        $this->db->select('
            order.order_id,
            order.image as artwork,
            order.number as order_number,
            order.description as title,
            order.dimension,
            order.material,
            order.color,
            order.required_date as required,
            production.production_id,
            production.repeat,
            production.production_status_id,
            production_status.name as status
        ');
        $this->db->from('production');
        $this->db->join('order', 'production.order_id = order.order_id');
        $this->db->join('production_status', 'production.production_status_id = production_status.production_status_id');

        $this->db->where('order.image<>', '');
        $this->db->where('production.production_status_id<=', 3);

        return $this->db->get()->result_array();
    }

    public function get_embro_list()
    {
        $this->db->select('
            order.order_id,
            order.image as artwork,
            order.number as order_number,
            order.description as title,
            order.required_date as required,
            production.production_id,
            production.color_order,
            production.machine,
            production.flashdisk,
            production.file,
            production.labor_price,
            production.production_status_id,
            production_status.name as status
        ');
        $this->db->from('production');
        $this->db->join('order', 'production.order_id = order.order_id');
        $this->db->join('production_status', 'production.production_status_id = production_status.production_status_id');
        $this->db->where('file!=', null);
        $this->db->where('color_order<>', '');
        $this->db->where('machine<>', '');
        $this->db->where('production.production_status_id>', 2);
        $this->db->where('production.production_status_id<', 6);

        return $this->db->get()->result_array();
    }

    public function get_finishing_list()
    {
        $this->db->select('
            item.item_id,
            item.name AS item_name,
            item.icon AS item_icon,
            position.position_id,
            position.name AS position_name,
            order.image,
            order.required_date AS deadline,
            order.order_id,
            order.number AS order_number,
            order.description AS title,
            order.quantity,
            production.production_id,
            production_status.production_status_id,
            production_status.name AS status
        ');
        $this->db->from('order');
        $this->db->join('item', 'order.item_id = item.item_id');
        $this->db->join('position', 'order.position_id = position.position_id');
        $this->db->join('production', 'order.order_id = production.order_id');
        $this->db->join('production_status', 'production.production_status_id = production_status.production_status_id');
        $this->db->where('production.production_status_id>', 5);
        $this->db->where('production.production_status_id<', 9);

        return $this->db->get()->result_array();
    }

    public function check_design_output_by_order_id($order_id)
    {
        $design_output = 'Antri';
        $production_status_id = $this->get_production_detail_by_order_id($order_id)['production_status_id'];

        switch (true) {
            case $production_status_id == 1:
                $design_output = 'Antri';
                break;
            case $production_status_id == 2:
                $design_output = 'Dikerjakan';
                break;
            case $production_status_id >= 3:
                $design_output = 'Selesai';
                break;
        }

        return $design_output;
    }

    public function get_embro_output_by_production_id($production_id)
    {
        $this->db->select('
            output_embro.output_embro_id AS output_id,
            output_embro.quantity,
            output_embro.date,
            output_embro.shift,
            employee.nick_name AS operator,
            employee.employee_id
        ');
        $this->db->from('output_embro');
        $this->db->join('employee', 'output_embro.employee_id = employee.employee_id');
        $this->db->where('output_embro.production_id', $production_id);

        return $this->db->get()->result_array();
    }

    public function get_finishing_output_by_production_id($production_id)
    {
        $this->db->select('
            output_finishing.output_finishing_id AS output_id,
            output_finishing.quantity,
            output_finishing.date,
            employee.nick_name AS operator,
            employee.employee_id
        ');
        $this->db->from('output_finishing');
        $this->db->join('employee', 'output_finishing.employee_id = employee.employee_id');

        return $this->db->get()->result_array();
    }

    public function sum_output_embro_by_order_id($order_id)
    {
        $this->db->select('
            order.number AS order_number,
            ANY_VALUE(order.quantity) AS order_qty,
            SUM(output_embro.quantity) AS quantity
        ');
        $this->db->from('output_embro');
        $this->db->join('production', 'output_embro.production_id = production.production_id');
        $this->db->join('order', 'production.order_id = order.order_id');
        $this->db->where('order.order_id', $order_id);
        $this->db->group_by('order.order_id');

        return $this->db->get()->row_array();
    }

    public function sum_output_finishing_by_order_id($order_id)
    {
        $this->db->select('
            order.number AS order_number,
            ANY_VALUE(order.quantity) AS order_qty,
            SUM(output_finishing.quantity) AS quantity
        ');
        $this->db->from('output_finishing');
        $this->db->join('production', 'output_finishing.production_id = production.production_id');
        $this->db->join('order', 'production.order_id = order.order_id');
        $this->db->where('order.order_id', $order_id);
        $this->db->group_by('order.order_id');

        return $this->db->get()->row_array();
    }

    public function get_production_output_by_order_id($order_id)
    {
        $production_output = [
            'design'    => $this->check_design_output_by_order_id($order_id),
            'embro'     => $this->sum_output_embro_by_order_id($order_id)['quantity'],
            'finishing' => $this->sum_output_finishing_by_order_id($order_id)['quantity']
        ];

        return $production_output;
    }

    public function check_design_progress_by_order_id($order_id)
    {
        $design_progress = 0;
        $production_status_id = $this->get_production_detail_by_order_id($order_id)['production_status_id'];

        switch (true) {
            case $production_status_id == 1:
                $design_progress = 0;
                break;
            case $production_status_id == 2:
                $design_progress = 50;
                break;
            case $production_status_id >= 3:
                $design_progress = 100;
                break;
        }

        return $design_progress;
    }

    public function check_embro_progress_by_order_id($order_id)
    {
        $emb_output = $this->sum_output_embro_by_order_id($order_id);

        if ($emb_output['quantity'] == 0) {
            return 0;
        }

        return round(($emb_output['quantity'] / $emb_output['order_qty']) * 100);
    }

    public function check_finishing_progress_by_order_id($order_id)
    {
        $finishing_output = $this->sum_output_finishing_by_order_id($order_id);

        if ($finishing_output['quantity'] == 0) {
            return 0;
        }

        return round(($finishing_output['quantity'] / $finishing_output['order_qty']) * 100);
    }


    public function check_production_status_by_order_id($order_id)
    {
        $production_status = [
            'design'    => $this->check_design_progress_by_order_id($order_id),
            'embro'     => $this->check_embro_progress_by_order_id($order_id),
            'finishing' => $this->check_finishing_progress_by_order_id($order_id)
        ];

        return $production_status;
    }

    public function list_operator_output($period)
    {

        /**
         * Query operator production output for current period.
         * Dynamic start and end date is based on current day relative to 17th day
         */

        $start_date = $period['start'];
        $end_date = $period['end'];

        $sql = "
            SELECT 
                employee.employee_id, 
                employee.name,
                IFNULL(SUM(output_embro.quantity),0) AS quantity,
                (IFNULL(SUM(output_embro.quantity),0) * IFNULL(ANY_VALUE(production.labor_price),0)) AS value
            FROM employee
            LEFT JOIN output_embro ON employee.employee_id = output_embro.employee_id
            LEFT JOIN production ON output_embro.production_id = production.production_id
            WHERE output_embro.date BETWEEN '{$start_date}' AND '{$end_date}'
            GROUP BY employee.employee_id
        ";

        return $this->db->query($sql)->result_array();
    }

    public function select_production_detail()
    {
        $this->db->select('
            order.number as order_number,
            order.order_id,
            order.image as artwork,
            order.description as title,
            order.dimension,
            order.material,
            order.color,
            order.quantity,
            order.required_date as required,
            order.note,
            production.production_id,
            production.repeat,
            production.color_order,
            production.production_status_id,
            production_status.name as status,
            production.machine,
            production.flashdisk,
            production.file,
            production.labor_price,
            item.name as item
        ');
        $this->db->from('production');
        $this->db->join('order', 'production.order_id = order.order_id');
        $this->db->join('item', 'order.item_id = item.item_id');
        $this->db->join('production_status', 'production.production_status_id = production_status.production_status_id');

        return $this->db->get_compiled_select();
    }

    public function get_production_by_id($order_id)
    {
        return $this->db->get_where('production', ['order_id' => $order_id])->row_array();
    }

    public function get_production_detail_by_id($production_id)
    {
        $select_production_detail = $this->select_production_detail($production_id);
        $production_detail_query = "{$select_production_detail} WHERE `production`.production_id = {$production_id}";

        return $this->db->query($production_detail_query)->row_array();
    }

    public function get_production_detail_by_order_id($order_id)
    {
        $select_production_detail = $this->select_production_detail($order_id);
        $production_detail_query = "{$select_production_detail} WHERE `order`.order_id = {$order_id}";

        return $this->db->query($production_detail_query)->row_array();
    }

    public function get_employee_name_by_job_id($job_id)
    {
        $this->db->select('employee_id, nick_name');
        $this->db->from('employee');
        $this->db->where('job_role_id', $job_id);

        return $this->db->get()->result_array();
    }

    /***
     * Unused method in current implementation 
     * */

    public function get_production_status()
    {
        return $this->db->get('production_status')->result_array();
    }

    public function get_design_status()
    {
        function grab_design_process_only($production_status)
        {
            return strstr(strtolower($production_status['name']), 'desain');
        }

        return array_filter($this->get_production_status(), "grab_design_process_only");
    }

    public function get_embro_status()
    {
        function grab_embro_process_only($production_status)
        {
            return strstr(strtolower($production_status['name']), 'bordir');
        }

        return array_filter($this->get_production_status(), "grab_embro_process_only");
    }

    public function get_finishing_status()
    {
        function grab_finishing_process_only($production_status)
        {
            return strstr(strtolower($production_status['name']), 'finishing');
        }
    }

    public function check_process_icon($date_started, $date_finished)
    {
        $process_icon = '<i class="far fa-clock fa-lg"></i>';

        if ($date_started != null && $date_finished == null) {
            $process_icon = '<i class="fas fa-circle-notch fa-lg fa-spin"></i>';
        }

        if ($date_started != null && $date_finished != null) {
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
                'data' => $this->db->query($this->select_basic_card_data('process_design'))->result_array(),
            ],
            [
                'title' => 'Persiapan Bahan Baku',
                'icon' => 'fas fa-tape mr-2',
                'url' => 'produksi/persiapan',
                'data' => $this->db->query($this->select_basic_card_data('process_preparation'))->result_array(),
            ],
            [
                'title' => 'Bordir',
                'icon' => 'fab fa-shirtsinbulk mr-2',
                'url' => 'produksi/bordir',
                'data' => $this->db->query($this->select_basic_card_data('process_embroidery'))->result_array(),
            ],
            [
                'title' => 'Finishing',
                'icon' => 'fas fa-cut mr-2',
                'url' => 'produksi/bordir',
                'data' => $this->db->query($this->select_basic_card_data('process_finishing'))->result_array(),
            ],
            [
                'title' => 'Packaging',
                'icon' => 'fas fa-gift mr-2',
                'url' => 'produksi/bordir',
                'data' => $this->db->query($this->select_basic_card_data('process_finishing'))->result_array(),
            ],

        ];

        return $cards;
    }
}
