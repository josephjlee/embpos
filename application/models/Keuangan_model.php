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

  public function get_total_debt()
  {
    $query = $this->db->query("SELECT SUM(amount) AS total_amount FROM debt");
    return $query->row_array()['total_amount'];
  }

  public function get_total_debt_payment()
  {
    $query = $this->db->query("SELECT SUM(amount) AS total_amount FROM debt_payment WHERE debt_id IS NOT NULL");
    return $query->row_array()['total_amount'];
  }

  public function get_total_debt_due()
  {
    $total_debt = $this->get_total_debt();
    $total_debt_payment = $this->get_total_debt_payment();
    $total_debt_due = $total_debt - $total_debt_payment;

    return $total_debt_due;
  }

  public function get_debt_value_per_creditor()
  {
    $query = $this->db->query("SELECT 
                  debt.creditor_id,
                  creditor.name AS creditor_name, 
                  SUM(debt.amount) AS debt_value,
                  ANY_VALUE(COALESCE(paid,0)) AS total_paid
              FROM
                  debt
              LEFT JOIN 
                  creditor ON debt.creditor_id = creditor.creditor_id
              LEFT JOIN
                  (
                  SELECT 
                    debt_payment.creditor_id, 
                    SUM(debt_payment.amount) AS paid
                  FROM
                    debt_payment
                  GROUP BY debt_payment.creditor_id
                ) AS payment_table ON debt.creditor_id = payment_table.creditor_id
              GROUP BY debt.creditor_id
              ORDER BY debt_value DESC");
    return $query->result_array();
  }

  public function count_active_creditor()
  {
    $active_creditors = 0;

    foreach ($this->get_debt_value_per_creditor() as $credtior) {
      if ($credtior['debt_value'] != $credtior['total_paid']) {
        $active_creditors += 1;
      }
    }

    return $active_creditors;
  }

  public function get_the_biggest_creditor()
  {
    return $this->get_debt_value_per_creditor()[0]['creditor_name'];
  }

  public function get_the_nearest_debt_due()
  {
    $query = $this->db->query("SELECT 
              creditor.name AS creditor_name,
              description,
                payment_date,
                amount
            FROM
                debt
            JOIN creditor ON debt.creditor_id = creditor.creditor_id
            ORDER BY payment_date
            LIMIT 1");

    return $query->row_array();
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
