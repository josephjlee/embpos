<?php

class Pesanan_model extends CI_Model
{

    public function simpan($order)
    {

        if (!empty($order['order_id'])) {
            return $this->perbarui($order);
        }

        return $this->tambah($order);
    }

    public function tambah($order)
    {

        $order_data = $this->siapkan_data($order);

        return $this->db->insert('order', $order_data);
    }

    public function perbarui($order)
    {

        $order_data = $this->siapkan_data($order);

        $this->db->where('order_id', $order['order_id']);

        return $this->db->update('order', $order_data);
    }

    public function perbarui_banyak($orders)
    {
        $orders_data = [];

        foreach ($orders as $col => $val) {
            $orders_data[$col] = $val;
            if ($col == 'price' || $col = 'quantity') {
                $orders_data[$col] = str_replace(',', '', $val);
            }
        }

        return $this->db->update_batch('order', $orders_data, 'order_id');
    }

    public function hapus($order)
    {
        $this->db->where('order_id', $order['order_id']);
        return $this->db->delete('order');
    }

    public function assign_invoice_id($orders, $invoice_id)
    {

        $orders_db_data = [];

        foreach ($orders as $order) {

            $order_entry = [];

            foreach ($order as $col => $val) {

                $order_entry['invoice_id'] = $invoice_id;
                $order_entry[$col] = $val;
            }

            array_push($orders_db_data, $order_entry);
        }

        return $this->db->update_batch('order', $orders_db_data, 'order_id');
    }

    public function detach_order($order)
    {
        $this->db->where('order_id', $order['order_id']);
        return $this->db->update('order', ['invoice_id' => NULL]);
    }

    public function detach_artwork($order)
    {
        $this->db->where('order_id', $order['order_id']);
        return $this->db->update('order', ['image' => NULL]);
    }

    public function select_all_orders()
    {
        $this->db->select('
            invoice.number AS invoice_number,
            customer.customer_id,
            customer.name AS customer_name,
            item.item_id,
            item.name AS item_name,
            item.icon AS item_icon,
            position.position_id,
            position.name AS position_name,
            order.image,
            order.required_date AS order_deadline,
            order.order_id,
            order.number AS order_number,
            order.description,
            order.quantity,
            order.price,
            order.process_status_id,
            production.color_order,
            production.machine,
            production.flashdisk,
            production.file,
            production.operator,
            production.labor_price,
            process_status.name AS process_status
        ');

        $this->db->from('order');
        $this->db->join('customer', 'order.customer_id = customer.customer_id', 'left');
        $this->db->join('item', 'order.item_id = item.item_id', 'left');
        $this->db->join('position', 'order.position_id = position.position_id', 'left');
        $this->db->join('invoice', 'order.invoice_id = invoice.invoice_id', 'left');
        $this->db->join('production', 'order.order_id = production.order_id', 'left');
        $this->db->join('process_status', 'order.process_status_id = process_status.process_status_id', 'left');
        $this->db->where('order.position_id !=', NULL);

        return $this->db->get_compiled_select();
    }

    public function get_all_orders()
    {

        $all_orders_query = $this->db->query($this->select_all_orders());
        return $all_orders_query->result_array();
    }

    public function get_all_finished_orders()
    {

        $select_all_orders = $this->select_all_orders();
        $select_finished_orders = "{$select_all_orders} AND `order`.`process_status_id` = 5";

        $finished_order_query = $this->db->query($select_finished_orders);
        return $finished_order_query->result_array();
    }

    public function get_all_active_orders()
    {

        $select_all_orders = $this->select_all_orders();
        $select_active_orders = "{$select_all_orders} AND `order`.`process_status_id` != 1 AND `order`.`process_status_id` != 5";

        $active_order_query = $this->db->query($select_active_orders);
        return $active_order_query->result_array();
    }

    public function get_queued_orders()
    {

        $select_all_orders = $this->select_all_orders();
        $select_active_orders = "{$select_all_orders} AND `order`.`process_status_id` = 1";

        $active_order_query = $this->db->query($select_active_orders);
        return $active_order_query->result_array();
    }

    public function get_single_order($order_id)
    {

        $this->db->select('
            invoice.invoice_id,
            invoice.number AS invoice_number,            
            customer.customer_id,
            customer.name AS customer_name,
            customer.company AS customer_company,
            customer.address AS customer_address,
            customer.phone AS customer_phone,
            item.item_id,
            item.name AS item_name,
            item.price_constant,
            position.position_id,
            position.name AS position_name,
            order.image,
            order.machine_file as emb,
            order.order_id,
            order.number AS order_number,
            order.description,
            order.dimension,
            order.color,
            order.material,
            order.quantity,
            order.price,
            (order.price*order.quantity) AS amount,
            order.received_date,
            order.required_date,
            order.process_status_id,
            order.note,
            process_status.name AS process_status
        ');

        $this->db->from('order');
        $this->db->join('item', 'order.item_id = item.item_id');
        $this->db->join('position', 'order.position_id = position.position_id', 'left');
        $this->db->join('invoice', 'order.invoice_id = invoice.invoice_id', 'left');
        $this->db->join('customer', 'order.customer_id = customer.customer_id');
        $this->db->join('process_status', 'order.process_status_id = process_status.process_status_id', 'left');
        $this->db->where('order.position_id !=', NULL);
        $this->db->where('order_id', $order_id);

        return $this->db->get()->row_array();
    }

    public function get_all_items()
    {

        $this->db->select('item_id, name AS item_name, price_constant AS item_pc');
        $this->db->order_by('item_name');

        return $this->db->get('item')->result_array();
    }

    public function get_item_by_id($item_id)
    {
        $this->db->select('item_id, name AS item_name, price_constant');
        $this->db->from('item');
        $this->db->where('item_id', $item_id);

        $item_query = $this->db->get();
        return $item_query->row_array();
    }

    public function get_all_positions()
    {
        $this->db->select('position_id, name AS position_name');
        $this->db->from('position');

        $position_query = $this->db->get();
        return $position_query->result_array();
    }

    public function get_item_position_pairs()
    {
        $this->db->select('position.position_id, position.name');
        $this->db->join('item_position', 'item_position.position_id = position.position_id', 'left');
        $this->db->order_by('name');

        return $this->db->get('position')->result_array();
    }

    public function get_position_by_id($position_id)
    {

        $this->db->select('position_id, name AS position_name');
        $this->db->from('position');
        $this->db->where('position_id', $position_id);

        $position_query = $this->db->get();
        return $position_query->row_array();
    }

    public function get_position_by_item_id($item_id)
    {
        $this->db->select('position.position_id, position.name');
        $this->db->join('item_position', 'item_position.position_id = position.position_id', 'left');
        $this->db->order_by('name');
        $this->db->where('item_position.item_id', $item_id);

        return $this->db->get('position')->result_array();
    }

    public function get_stitch_price_by_quantity_id($quantity_id)
    {
        $this->db->select('stitch.stitch_id, stitch.name AS stitch_count, price.price AS stitch_price');
        $this->db->join('price', 'price.stitch_id = stitch.stitch_id');
        $this->db->order_by('stitch_id', 'asc');
        $this->db->where('price.quantity_id', $quantity_id);

        return $this->db->get('stitch')->result_array();
    }

    public function get_order_metas($invoice_id)
    {

        $this->db->select('
            order.invoice_id,
            order.item_id,
            order.position_id,
            order.order_id,
            order.description,
            order.quantity,
            order.price,
            (order.quantity*order.price) AS amount
        ');

        $this->db->from('order');
        $this->db->where('order.invoice_id', $invoice_id);

        $query_order = $this->db->get();
        return $query_order->result_array();
    }

    public function get_order($order_id)
    {
        $this->db->select('
            item.item_id,
            item.name AS item_name,
            item.price_constant,
            position.position_id,
            position.name AS position_name,
            order.order_id,
            order.description,
            order.quantity,
            order.price,
            (order.price*order.quantity) AS amount
        ');
        $this->db->from('order');
        $this->db->join('item', 'order.item_id = item.item_id');
        $this->db->join('position', 'order.position_id = position.position_id', 'left');
        $this->db->where('order.order_id', $order_id);

        $order_query = $this->db->get();
        return $order_query->row_array();
    }

    public function get_order_by_customer_id($customer_id)
    {
        $this->db->select('
              order.invoice_id, 
              order.order_id AS id, 
              order.required_date, 
              order.description AS text,
              order.dimension AS dimension,
              order.quantity AS qty,
              order.price AS price,
              order.image,
              (order.quantity*order.price) AS amount,
              item.icon
          ');

        $this->db->from('order');
        $this->db->join('item', 'order.item_id = item.item_id');
        $this->db->where('customer_id', $customer_id);
        $this->db->where('invoice_id', NULL);

        $customer_order = $this->db->get()->result_array();

        function create_option_collection($option_data)
        {

            $item_index = 0;

            $base_url    = base_url();
            $file_name   = $option_data['image'] ?? $option_data['icon'];
            $file_folder = $option_data['image'] ? 'img/artwork' : 'icon';

            $required_date = date('d-M-Y', strtotime($option_data['required_date']));

            return
                "<div class='order-list-item d-flex justify-content-between align-items-center mb-3' data-order-id='{$option_data['id']}' data-desc='{$option_data['text']}' data-dimension='{$option_data['dimension']}' data-qty='{$option_data['qty']}' data-price='{$option_data['price']}' data-amount='{$option_data['amount']}' id='order-list-item-{$option_data['id']}' data-order-id='{$option_data['id']}'>
            <div class='d-flex align-items-center'>
                <img class='mr-3' style='width:33px;height:33px' src='{$base_url}/assets/{$file_folder}/{$file_name}'>
                <div>
                    <div style='color:#9aa0ac;font-size:13px' class='order-item__required-date'>Diambil: {$required_date}</div>
                    <div style='color:#495057;font-size:15px' class='order-item__description'>{$option_data['text']}</div>
                </div>
            </div>
            <a href='#' style='color:#AAB0C6' class='add-order-btn' id='add-order-{$option_data['id']}' data-order-id='{$option_data['id']}'>
                <i class='fas fa-plus'></i>
            </a>
          </div>";

            $item_index++;
        }

        $order_collection = array_map("create_option_collection", $customer_order);

        return $order_collection;
    }

    public function get_order_by_invoice_id($invoice_id)
    {

        $this->db->select('
            item.item_id,
            item.name AS item_name,
            item.price_constant,
            position.position_id,
            position.name AS position_name,
            order.invoice_id,
            order.order_id,
            order.description,
            order.dimension,
            order.material,
            order.color,
            order.quantity,
            order.price,
            (order.price*order.quantity) AS amount,
            order.received_date,
            order.required_date,
            order.process_status_id,
            order.status_date,            
            process_status.name AS process_status
        ');
        $this->db->from('order');
        $this->db->join('item', 'order.item_id = item.item_id');
        $this->db->join('position', 'order.position_id = position.position_id', 'left');
        $this->db->join('invoice', 'order.invoice_id = invoice.invoice_id');
        $this->db->join('process_status', 'order.process_status_id = process_status.process_status_id', 'left');
        $this->db->where('order.invoice_id', $invoice_id);

        $order_query = $this->db->get();
        return $order_query->result_array();
    }

    public function get_total_order_by_invoice_id($invoice_id)
    {

        $this->db->select('COUNT(order_id) AS total_order');
        $this->db->from('order');
        $this->db->where('invoice_id', $invoice_id);
        $total_order = $this->db->get()->row_array()['total_order'];

        $this->db->select('COUNT(product_sale_id) AS total_order');
        $this->db->from('product_sale');
        $this->db->where('invoice_id', $invoice_id);
        $total_product_sale = $this->db->get()->row_array()['total_order'];

        return $total_order + $total_product_sale;
    }

    public function get_finished_order_by_invoice_id($invoice_id)
    {

        $this->db->select('COUNT(order_id) AS total_completed');
        $this->db->from('order');
        $this->db->where('invoice_id', $invoice_id);
        $this->db->where('process_status_id', 5);
        $finished_order = $this->db->get()->row_array()['total_completed'];

        $this->db->select('COUNT(product_sale_id) AS total_completed');
        $this->db->from('product_sale');
        $this->db->where('invoice_id', $invoice_id);
        $this->db->where('process_status_id', 5);
        $finished_product_sale = $this->db->get()->row_array()['total_completed'];

        return $finished_order + $finished_product_sale;
    }

    public function get_active_order_by_invoice_id($invoice_id)
    {

        $this->db->select('COUNT(order_id) AS total_active');
        $this->db->from('order');
        $this->db->where('invoice_id', $invoice_id);
        $this->db->where('process_status_id!=', 1);
        $this->db->where('process_status_id!=', 5);

        $active_order_query = $this->db->get();
        return $active_order_query->row_array()['total_active'];
    }

    public function check_order_progress($invoice_id)
    {
        $total    = $this->get_total_order_by_invoice_id($invoice_id);
        $active   = $this->get_active_order_by_invoice_id($invoice_id);
        $finished = $this->get_finished_order_by_invoice_id($invoice_id);

        $order_status = [
            'Menunggu',
            'Diproses',
            'Tuntas'
        ];

        $status = $order_status[0];

        switch (true) {
            case $finished == 0 && $active == 0:
                $status = $order_status[0];
                break;
            case $finished == 0 && $active != 0:
                $status = $order_status[1];
                break;
            case $finished < $total:
                $status = "{$finished}/{$total} Pesanan";
                break;
            case $finished == $total:
                $status = $order_status[2];
                break;
        }

        return $status;
    }

    public function check_invoice($order_id)
    {
        $this->db->select('invoice.number');
        $this->db->from('order');
        $this->db->join('invoice', 'order.invoice_id = invoice.invoice_id');
        $this->db->where('order_id', $order_id);

        $invoice_number = $this->db->get()->row_array()['number'];

        $invoice_status = [
            'number'  => $invoice_number,
            'message' => $invoice_number ? "INV-{$invoice_number}" : 'Belum ditagihkan'
        ];

        return $invoice_status;
    }

    public function new_order_number()
    {
        $this->db->select('number');
        $this->db->from('order');
        $this->db->order_by('number', 'ASC');

        $last_order_number = $this->db->get()->last_row('array')['number'];

        return $last_order_number + 1;
    }

    public function siapkan_data($order)
    {

        $order_db_data = [];

        foreach ($order as $col => $val) {
            $order_db_data[$col] = $val;

            if ($col == 'quantity' || $col == 'price') {
                $order_db_data[$col] = str_replace(',', '', $val);
            }
        }

        return $order_db_data;
    }
}
