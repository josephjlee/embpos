<?php

class Penjualan_model extends CI_Model
{

  public function tambah($products, $invoice_id, $customer_id)
  {

    $product_sale_data = $this->siapkan_data($products, $invoice_id, $customer_id);

    return $this->db->insert_batch('product_sale', $product_sale_data);
  }

  public function perbarui($products, $invoice_id, $customer_id)
  {

    $product_sale_data = $this->siapkan_data($products, $invoice_id, $customer_id);

    return $this->db->update_batch('product_sale', $product_sale_data, 'product_sale_id');
  }

  public function get_product_sale_by_invoice_id($invoice_id)
  {
    $this->db->select('
            product_sale.product_sale_id,
            product_sale.product_id,
            product.title,
            product_sale.quantity,
            product_sale.price,
            (product_sale.price*product_sale.quantity) AS amount
        ');
    $this->db->from('product_sale');
    $this->db->join('product', 'product_sale.product_id = product.product_id');
    $this->db->where('product_sale.invoice_id', $invoice_id);

    $product_query = $this->db->get();
    return $product_query->result_array();
  }

  public function get_total_sale_by_month($month)
  {
    $query = $this->db->query("SELECT 
          SUM(product_sale.quantity * product_sale.price) AS total_sale
      FROM
          product_sale
              JOIN
          invoice ON product_sale.invoice_id = invoice.invoice_id
      WHERE MONTH(invoice.invoice_date) = {$month} AND YEAR(invoice.invoice_date) = YEAR(CURDATE())
    ");

    $result = $query->row_array();

    return $result['total_sale'];
  }

  public function get_total_sold_by_month($month)
  {
    $query = $this->db->query("SELECT 
          SUM(product_sale.quantity) AS total_sold
      FROM
          product_sale
              JOIN
          invoice ON product_sale.invoice_id = invoice.invoice_id
      WHERE MONTH(invoice.invoice_date) = {$month} AND YEAR(invoice.invoice_date) = YEAR(CURDATE())
    ");

    $result = $query->row_array();

    return $result['total_sold'];
  }

  public function hapus($product_sale)
  {
    $this->db->where('product_sale_id', $product_sale['product_sale_id']);
    return $this->db->delete('product_sale');
  }

  public function siapkan_data($products, $invoice_id, $customer_id)
  {

    $product_db_data = [];

    foreach ($products as $product) {

      $product_entry = [];

      foreach ($product as $col => $val) {

        $product_entry['invoice_id'] = $invoice_id;
        $product_entry['customer_id'] = $customer_id;

        $product_entry[$col] = $val;

        if ($col == 'price' || $col == 'quantity' || $col == 'amount') {
          $product_entry[$col] = str_replace(',', '', $val);
        }
      }

      array_push($product_db_data, $product_entry);
    }

    return $product_db_data;
  }
}
