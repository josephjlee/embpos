<?php

class Pembayaran_model extends CI_Model
{
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

  public function get_total_payment_by_month($month)
  {
    $query = $this->db->query("SELECT SUM(amount) AS total_payment 
      FROM payment 
      WHERE MONTH(payment_date) = {$month} AND YEAR(payment_date) = YEAR(CURDATE())
    ");
    $result = $query->row_array();
    return $result['total_payment'];
  }

  public function get_total_payment_by_month_by_method($month, $method_id)
  {
    $query = $this->db->query("SELECT IFNULL(SUM(amount),0) AS total_payment 
      FROM payment 
      WHERE MONTH(payment_date) = {$month} AND YEAR(payment_date) = YEAR(CURDATE()) AND payment_method_id = $method_id
    ");
    $result = $query->row_array();
    return $result['total_payment'];
  }

  public function get_monthly_payment()
  {

    $query = $this->db->query("SELECT
                  YEAR(payment_date) AS tahun,
                  MONTHNAME(payment_date) AS monthName,
                  ANY_VALUE(CONCAT(DATE_FORMAT(payment_date,'%b'), ' ', DATE_FORMAT(payment_date,'%y'))) AS period,
                  SUM(amount) AS amount
              FROM
                  payment
              GROUP BY tahun, monthName, MONTH(payment_date)
              ORDER BY YEAR(payment_date), MONTH(payment_date)
              LIMIT 12");

    return $query->result_array();
  }

  public function get_payment_amount_per_method()
  {
    $query = $this->db->query("SELECT 
              payment_method.name AS method,
                SUM(amount) AS amount
            FROM payment
            JOIN payment_method ON payment.payment_method_id = payment_method.payment_method_id
            GROUP BY method");

    return $query->result_array();
  }

  public function get_payment_amount_per_customer_category()
  {
    $query = $this->db->query("SELECT 
                ANY_VALUE(customer_category.name) AS category,
                  SUM(payment.amount) AS amount
              FROM embryo.payment
              JOIN customer ON payment.customer_id = customer.customer_id
              JOIN customer_category ON customer.customer_category_id = customer_category.customer_category_id
              GROUP BY customer_category.name");

    return $query->result_array();
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
