<?php

class Invoice_model extends CI_Model
{

    public function simpan($invoice)
    {

        if (!empty($invoice['invoice_id'])) {
            return $this->perbarui($invoice);
        }

        return $this->tambah($invoice);
    }

    public function tambah($invoice)
    {

        $invoice_data = $this->siapkan_data($invoice);

        return $this->db->insert('invoice', $invoice_data);
    }

    public function perbarui($invoice)
    {

        $invoice_data = $this->siapkan_data($invoice);

        $this->db->where('invoice_id', $invoice['invoice_id']);

        return $this->db->update('invoice', $invoice_data);
    }

    public function hapus($invoice)
    {
        $this->db->where('invoice_id', $invoice['invoice_id']);
		return $this->db->delete('invoice');
    }

    public function simpan_pembayaran($payment)
    {

        $payment_data = $this->siapkan_data($payment);

        return $this->db->insert('payment', $payment_data);
    }

    public function get_order_sub_total($invoice_id)
    {

        $this->db->select('SUM(quantity * price) AS sub_total');
        $this->db->from('order');
        $this->db->where('invoice_id', $invoice_id);

        $sub_total_query = $this->db->get();
        $sub_total = $sub_total_query->row_array();

        return $sub_total['sub_total'];
    }

    public function get_product_sub_total($invoice_id)
    {

        $this->db->select('SUM(product_sale.price*product_sale.quantity) AS sub_total');
        $this->db->from('invoice');
        $this->db->join('product_sale', 'invoice.invoice_id = product_sale.invoice_id');
        $this->db->where('invoice.invoice_id', $invoice_id);

        $sub_total_query = $this->db->get();
        $sub_total = $sub_total_query->row_array();

        return $sub_total['sub_total'];
    }

    public function get_sub_total($invoice_id)
    {
        return $this->get_product_sub_total($invoice_id) + $this->get_order_sub_total($invoice_id);
    }

    public function get_discount($invoice_id)
    {
        $this->db->select('discount');
        $this->db->from('invoice');
        $this->db->where('invoice_id', $invoice_id);

        $discount_query = $this->db->get();
        $discount = $discount_query->row_array();

        return $discount['discount'];
    }

    public function get_total_due($invoice_id)
    {
        // Query sub_total of particular invoice_id and assign to a variabel
        $sub_total = $this->get_sub_total($invoice_id);

        // Query discount of particular invoice_id and assign to a variabel
        $discount = $this->get_discount($invoice_id);

        // Calculate total_due by substracting $sub_total with $discount
        $total_due = $sub_total - $discount;
        return $total_due;
    }

    public function get_paid($invoice_id)
    {
        $this->db->select('IFNULL(SUM(amount), 0) AS paid');
        $this->db->from('payment');
        $this->db->where('invoice_id', $invoice_id);

        $payment_query = $this->db->get();
        $payment = $payment_query->row_array();

        return $payment['paid'];
    }

    public function get_payment_due($invoice_id)
    {
        $total_due = $this->get_total_due($invoice_id);
        $paid = $this->get_paid($invoice_id);

        $payment_due = $total_due - $paid;
        return $payment_due;
    }

    public function generate_invoice_calc($invoice_id)
    {
        $invoice_calc_var = [
            'discount'  => $this->get_discount($invoice_id),
            'paid'      => $this->get_paid($invoice_id),
            'sub_total' => $this->get_sub_total($invoice_id),
            'total_due' => $this->get_total_due($invoice_id),
            'invoice_due' => $this->get_payment_due($invoice_id)
        ];

        return $invoice_calc_var;
    }

    public function check_payment_status($invoice_id)
    {
        $invoice_calc_var = $this->generate_invoice_calc($invoice_id);
        $total_due = $invoice_calc_var['total_due'];
        $paid      = $invoice_calc_var['paid'];

        $payment_status = [
            'Kosong',
            'Sebagian',
            'Lunas'
        ];

        $status['payment_status'] = $payment_status[0];
        switch (true) {
            case $paid > 0 && $paid < $total_due:
                $status['payment_status'] = $payment_status[1];
                break;
            case $paid == $total_due:
                $status['payment_status'] = $payment_status[2];
                break;
        }

        return $status;
    }

    public function get_all_product_sales_amount()
    {
        $this->db->select('
          IFNULL( SUM(product_sale.quantity*product_sale.price),0 ) AS total_due
        ');

        $this->db->from('invoice');
        $this->db->join('product_sale', 'invoice.invoice_id = product_sale.invoice_id', 'left');
        $this->db->group_by('invoice.invoice_id');
        $this->db->order_by('invoice.number', 'DESC');

        return $this->db->get()->result_array();
    }

    public function get_all_orders_amount()
    {
        $this->db->select(' 
          IFNULL( SUM(order.quantity*order.price),0 ) AS total_due
        ');

        $this->db->from('invoice');
        $this->db->join('order', 'order.invoice_id = invoice.invoice_id', 'left');
        $this->db->group_by('invoice.invoice_id');
        $this->db->order_by('invoice.number', 'DESC');

        return $this->db->get()->result_array();
    }

    public function get_all_invoice_discount()
    {
      $this->db->select('discount');
      $this->db->from('invoice');
      $this->db->order_by('invoice.number', 'DESC');

      return $this->db->get()->result_array();
    }

    public function get_all_invoices_payment()
    {
      $this->db->select('IFNULL( SUM(payment.amount),0 ) AS payment');
      $this->db->from('invoice');
      $this->db->join('payment', 'invoice.invoice_id = payment.invoice_id', 'left');
      $this->db->group_by('invoice.invoice_id');
      $this->db->order_by('invoice.number', 'DESC');

      return $this->db->get()->result_array();
    }

    public function get_all_invoices_meta()
    {
        $this->db->select('
            invoice.invoice_id,
            invoice.number, 
            invoice.invoice_date, 
            invoice.payment_date, 
            customer.name AS customer, 
        ');

        $this->db->from('invoice');
        $this->db->join('customer', 'invoice.customer_id = customer.customer_id');
        $this->db->order_by('invoice.number', 'DESC');

        return $this->db->get()->result_array();
        
    }

    public function get_all_invoices_payment_due()
    {

        function calculate_payment_due($order_amount, $product_amount, $discount, $payment)
        {

            $invoice_entry['payment_due'] = $order_amount['total_due'] + $product_amount['total_due'] - $discount['discount'] - $payment['payment'];

            return $invoice_entry;
        }

        $order_amount = $this->get_all_orders_amount();
        $product_sale = $this->get_all_product_sales_amount();
        $discount     = $this->get_all_invoice_discount();
        $payment      = $this->get_all_invoices_payment();

        return array_map("calculate_payment_due", $order_amount, $product_sale, $discount, $payment);
    }

    public function list_all_invoices()
    {
      function merge_payment_due($payment_due_arr, $invoice_meta_arr) 
      {
        $invoice_meta_arr['payment_due'] = $payment_due_arr['payment_due'];
        return $invoice_meta_arr;
      }

      return array_map("merge_payment_due", $this->get_all_invoices_payment_due(), $this->get_all_invoices_meta());
    }



    public function get_invoice_meta($invoice_number)
    {
        $this->db->select('
            invoice.customer_id,
            invoice.invoice_id,
            invoice.number AS invoice_number,
            invoice.invoice_date,
            invoice.payment_date,
            invoice.note,
        ');

        $this->db->from('invoice');
        $this->db->where('invoice.number', $invoice_number);

        $order_meta_query = $this->db->get();
        $order_meta = $order_meta_query->row_array();

        return $order_meta;
    }

    public function generate_invoice_data($invoice_number)
    {

        $invoice_meta = $this->get_invoice_meta($invoice_number);
        $invoice_calc = $this->generate_invoice_calc($invoice_meta['invoice_id']);
        $payment_status = $this->check_payment_status($invoice_meta['invoice_id']);

        $invoice_detail = array_merge($invoice_meta, $invoice_calc, $payment_status);
        return $invoice_detail;
    }

    public function get_invoice_number($invoice_id)
    {
        $this->db->select('number');
        $this->db->where('invoice_id', $invoice_id);
        $order_number = $this->db->get('invoice');

        return $order_number->row_array()['number'];
    }

    public function get_artwork_by_invoice_id($invoice_id)
    {

        $this->db->select('artwork.file_name, order.description');
        $this->db->from('order');
        $this->db->join('artwork', 'order.artwork_id = artwork.artwork_id');
        $this->db->join('invoice', 'order.invoice_id = invoice.invoice_id');
        $this->db->where('invoice.invoice_id', $invoice_id);

        return $this->db->get()->result_array();
    }

    public function siapkan_data($product)
    {

        $product_db_data = [];

        foreach ($product as $col => $val) {
            $product_db_data[$col] = $val;

            if ($col == 'discount' || $col == 'amount') {
                $product_db_data[$col] = str_replace(',', '', $val);
            }
        }

        return $product_db_data;
    }
}
