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

  public function bayar_hutang($debt_payment)
  {
    $debt_payment_data = $this->siapkan_data($debt_payment);

    return $this->db->insert('debt_payment', $debt_payment_data);
  }

  public function list_all_debts()
  {

    $query = $this->db->query("SELECT 
                  creditor.name AS creditor,
                  creditor.creditor_id,
                  debt.debt_id,
                  debt.description,
                  debt.amount,
                  debt.transaction_date,
                  debt.payment_date,
                  debt.term,
                  debt.note,
                  (
                    SELECT IFNULL(SUM(debt_payment.amount),0)
                    FROM debt_payment
                    WHERE debt_payment.debt_id = debt.debt_id
                  ) AS paid,
                  debt.amount - (
                    SELECT IFNULL(SUM(debt_payment.amount),0)
                    FROM debt_payment
                    WHERE debt_payment.debt_id = debt.debt_id
                  ) AS due
              FROM debt
              JOIN creditor ON debt.creditor_id = creditor.creditor_id");

    return $query->result_array();
  }

  public function get_debt_by_debt_id($debt_id)
  {

    $query = $this->db->query("SELECT 
                  creditor.name AS creditor,
                  creditor.creditor_id,
                  debt.debt_id,
                  debt.description,
                  debt.amount,
                  debt.transaction_date,
                  debt.payment_date,
                  debt.term,
                  debt.note,
                  (
                    SELECT IFNULL(SUM(debt_payment.amount),0)
                    FROM debt_payment
                    WHERE debt_payment.debt_id = debt.debt_id
                  ) AS paid
              FROM debt
              JOIN creditor ON debt.creditor_id = creditor.creditor_id
              WHERE debt.debt_id = {$debt_id}");

    return $query->row_array();
  }

  public function get_debt_category()
  {
    $this->db->select('item_id, name');
    $this->db->from('item');
    $this->db->where('for_debt', 1);
    return $this->db->get()->result_array();
  }

  public function get_debt_payment_history_by_debt_id($debt_id)
  {
    $query = $this->db->query("SELECT debt_payment_id, amount, DATE_FORMAT(payment_date, '%Y-%m-%d') AS payment_date 
      FROM debt_payment 
      WHERE debt_id = {$debt_id}");

    return $query->result_array();
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
}
