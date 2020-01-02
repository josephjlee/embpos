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

  public function list_unpaid_debts()
  {
    $debts = $this->list_all_debts();

    function filter_unpaid($debt)
    {
      return $debt['due'] != 0;
    }

    $unpaid_debt = array_filter($debts, "filter_unpaid");

    return $unpaid_debt;
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

  public function get_top_five_creditor()
  {
    $creditors_payable = $this->get_debt_value_per_creditor();

    $top_four = array_slice($creditors_payable, 0, 4);

    $the_rest = ['debt_value' => 0, 'total_paid' => 0];

    foreach (array_slice($creditors_payable, 4) as $payable) {
      $the_rest['debt_value'] += $payable['debt_value'];
      $the_rest['total_paid'] += $payable['total_paid'];
    }

    $output = [
      'top_four' => $top_four,
      'the_rest' => $the_rest
    ];

    return $output;
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
    if (empty($this->get_debt_value_per_creditor())) {
      return "-";
    }

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

    if (empty($query->row_array())) {
      return '-';
    }

    return $query->row_array();
  }

  public function simpan_pengeluaran($expense)
  {

    if (!empty($expense['expense_id'])) {
      return $this->perbarui_pengeluaran($expense);
    }

    return $this->tambah_pengeluaran($expense);
  }

  public function tambah_pengeluaran($expense)
  {

    $expense_data = $this->siapkan_data($expense);

    return $this->db->insert('expense', $expense_data);
  }

  public function perbarui_pengeluaran($expense)
  {
    $expense_data = $this->siapkan_data($expense);

    $this->db->where('expense_id', $expense['expense_id']);

    return $this->db->update('expense', $expense_data);
  }

  public function hapus_pengeluaran($expense)
  {
    $this->db->where('expense_id', $expense['expense_id']);
    return $this->db->delete('expense');
  }

  public function list_all_expenses()
  {

    $query = $this->db->query("SELECT 
                  vendor.name AS vendor,
                  vendor.vendor_id,
                  expense_category.name AS category,
                  expense_category.expense_category_id,
                  expense.expense_id,
                  expense.description,
                  expense.amount,
                  expense.transaction_date,
                  expense.note
              FROM expense
              JOIN vendor ON expense.vendor_id = vendor.vendor_id
              JOIN expense_category ON expense.expense_category_id = expense_category.expense_category_id");

    return $query->result_array();
  }

  public function list_all_expense_categories()
  {
    $query = $this->db->query("SELECT expense_category_id, name FROM expense_category");
    return $query->result_array();
  }

  public function list_expense_by_category()
  {
    $query = $this->db->query("SELECT 
            expense_category.name AS category,
            SUM(expense.amount) AS amount
        FROM expense
        JOIN expense_category ON expense.expense_category_id = expense_category.expense_category_id
        GROUP BY expense.expense_category_id
    ");

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
