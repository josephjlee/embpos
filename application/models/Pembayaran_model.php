<?php

class Pembayaran_model extends CI_Model
{

  public function simpan($payment)
  {

    if (!empty($payment['payment_id'])) {
      return $this->perbarui($payment);
    }

    return $this->tambah($payment);
  }

  public function tambah($payment)
  {

    $payment_data = $this->siapkan_data($payment);

    return $this->db->insert('payment', $payment_data);
  }

  public function perbarui($payment)
  {

    $payment_data = $this->siapkan_data($payment);

    $this->db->where('payment_id', $payment['payment_id']);

    return $this->db->update('payment', $payment_data);
  }

  public function hapus($payment)
  {
    $this->db->where('payment_id', $payment['payment_id']);
		return $this->db->delete('payment');
  }

  public function get_payment_by_invoice_id($invoice_id)
  {
    $this->db->select('
      payment.payment_id, 
      payment.amount as payment_amount, 
      payment.payment_date, 
      payment_method.payment_method_id,
      payment_method.name as payment_name
    ');

    $this->db->from('payment');
    $this->db->join('payment_method', 'payment_method.payment_method_id = payment.payment_method_id');
    $this->db->where('invoice_id', $invoice_id);
    $this->db->order_by('payment_date', 'DESC');

    return $this->db->get()->result_array();
  }

  public function get_payment_history()
  {

    $this->db->select('
          payment.payment_id,
          payment.amount as payment_amount, 
          payment.payment_date, 
          payment_method.name as payment_name,
          invoice.invoice_id,
          invoice.number as order_number,
          customer.name as customer_name
      ');

    $this->db->from('payment');

    $this->db->join('payment_method', 'payment_method.payment_method_id = payment.payment_method_id');
    $this->db->join('invoice', 'invoice.invoice_id = payment.invoice_id');
    $this->db->join('customer', 'customer.customer_id = payment.customer_id');
    $this->db->order_by('payment_date', 'DESC');

    $payment_query = $this->db->get();
    return $payment_query->result_array();
  }

  public function get_payment_method()
  {
    $this->db->select('payment_method_id, name');
    $this->db->from('payment_method');
    return $this->db->get()->result_array();
  }

  public function get_payment_detail($payment_id)
  {

    $this->db->select('
            payment.payment_id,
            payment.amount as payment_amount, 
            payment.payment_date, 
            payment_method.name as payment_name,
            payment.customer_id,
            payment.invoice_id,
            payment.payment_method_id,
            invoice.invoice_id,
            invoice.number as order_number,
            customer.name as customer_name
        ');

    $this->db->from('payment');

    $this->db->join('payment_method', 'payment_method.payment_method_id = payment.payment_method_id');
    $this->db->join('invoice', 'invoice.invoice_id = payment.invoice_id');
    $this->db->join('customer', 'customer.customer_id = payment.customer_id');
    $this->db->where('payment_id', $payment_id);

    return $this->db->get()->row_array();
  }

  public function siapkan_data($payment)
  {

    $payment_db_data = [];

    foreach ($payment as $col => $val) {
      $payment_db_data[$col] = $val;

      if ($col == 'amount') {
        $payment_db_data[$col] = str_replace(',', '', $val);
      }
    }

    return $payment_db_data;
  }
}
