<?php

class Keuangan_model extends CI_Model
{

  public function simpan($debt)
  {

    if (!empty($debt['debt_id'])) {
      return $this->perbarui($debt);
    }

    return $this->tambah($debt);
  }

  public function tambah($debt)
  {

    $debt_data = $this->siapkan_data($debt);

    return $this->db->insert('debt', $debt_data);
  }

  public function perbarui($debt)
  {
    $debt_data = $this->siapkan_data($debt);

    $this->db->where('debt_id', $debt['debt_id']);

    return $this->db->update('debt', $debt_data);
  }

  public function hapus($debt)
  {
    $this->db->where('debt_id', $debt['debt_id']);
    return $this->db->delete('debt');
  }

  public function list_all_debts()
  {

    $this->db->select('
      debt.debt_id,
      debt.amount,
      debt.term,
      debt.transaction_date,
      debt.payment_date,
      creditor.name
    ');
    $this->db->from('debt');
    $this->db->join('creditor', 'debt.creditor_id = creditor.creditor_id');

    return $this->db->get()->result_array();
  }

  public function get_debt_by_debt_id($debt_id)
  {

    $this->db->select('
      debt.debt_id,
      debt.image,
      debt.sku,
      debt.title,
      debt.description,
      debt.item_id,
      item.name AS item_name,
      debt.stock,
      debt.base_price,
      debt.sell_price
    ');

    $this->db->join('item', 'debt.item_id = item.item_id');
    $this->db->where('debt_id', $debt_id);

    return $this->db->get('debt')->row_array();
  }

  public function get_debt_category()
  {
    $this->db->select('item_id, name');
    $this->db->from('item');
    $this->db->where('for_debt', 1);
    return $this->db->get()->result_array();
  }

  public function siapkan_data($debt)
  {

    $debt_db_data = [];

    foreach ($debt as $col => $val) {
      $debt_db_data[$col] = $val;

      if ($col == 'stock' || $col == 'base_price' || $col == 'sell_price') {
        $debt_db_data[$col] = str_replace(',', '', $val);
      }
    }

    return $debt_db_data;
  }

  public function get_stock_by_debt_id($debt_id)
  {
    $this->db->select('stock');
    $this->db->from('debt');
    $this->db->where('debt_id', $debt_id);

    return $this->db->get()->row_array()['stock'];
  }

  public function get_debt_sale_qty_by_id($debt_sale_id)
  {
    $this->db->select('quantity');
    $this->db->from('debt_sale');
    $this->db->where('debt_sale_id', $debt_sale_id);

    return $this->db->get()->row_array()['quantity'];
  }

  public function update_stock_on_purchase($debt_solds)
  {
    $data = [];

    $i = 0;

    foreach ($debt_solds as $debts) {
      $data[$i]['debt_id'] = $debts['debt_id'];
      $data[$i]['stock'] = $this->get_stock_by_debt_id($debts['debt_id']) - $debts['quantity'];
      $i++;
    }

    $this->db->update_batch('debt', $data, 'debt_id');
  }

  public function update_stock_on_update($debt_solds)
  {
    $data = [];

    $i = 0;

    foreach ($debt_solds as $debts) {
      $data[$i]['debt_id'] = $debts['debt_id'];
      $data[$i]['stock'] = $this->get_stock_by_debt_id($debts['debt_id']) + $this->get_debt_sale_qty_by_id($debts['debt_sale_id']) - $debts['quantity'];
      $i++;
    }

    $this->db->update_batch('debt', $data, 'debt_id');
  }

  public function get_stock_data()
  {
    $this->db->select('
      SUM(stock*sell_price) AS value,
      SUM(stock) AS quantity
    ');

    $this->db->from('debt');

    return $this->db->get()->row_array();
  }
}
