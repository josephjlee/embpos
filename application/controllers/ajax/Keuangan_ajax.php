<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Keuangan_ajax extends CI_Controller
{

  public function __construct()
  {

    parent::__construct();

    $this->load->model('keuangan_model');
    $this->load->model('vendor_model');
    $this->load->model('kreditur_model');
  }

  public function simpan_hutang()
  {
    $debt = $this->input->post('debt');

    $this->keuangan_model->simpan($debt);

    $message = $debt['debt_id'] ? 'Detail hutang berhasil diperbarui' : 'Hutang baru berhasil dicatat';

    $response['action'] = $debt['debt_id'] ? 'update' : 'create';

    $response['alert'] = "<div class='row'>
                <div class='col'>
                  <div class='alert alert-warning alert-dismissible fade show' role='alert'>
                    <strong class='alert-content'>{$message}</strong>
                    <button type='button' class='close' data-dismiss='alert'>
                      <span>&times;</span>
                    </button>
                  </div>
                </div>
              </div>";

    header('Content-Type: application/json');
    echo json_encode($response);
  }

  public function hapus_hutang()
  {

    $debt = $this->input->post('debt');

    // Delete debt table entry by the received debt_id
    $this->db->where('debt_id', $debt['debt_id']);
    $this->db->delete('debt');

    // Delete debt_payment table entry by the received debt_id
    $this->db->where('debt_id', $debt['debt_id']);
    $this->db->delete('debt_payment');

    $response['alert'] = '<div class="row mb-2">
                            <div class="col">
                              <div class="alert alert-warning alert-dismissible fade show shadow" role="alert">
                                <strong class="alert-content">Hutang telah berhasil dihapus</strong>
                                <button type="button" class="close" data-dismiss="alert">
                                  <span>&times;</span>
                                </button>
                              </div>
                            </div>
                          </div>';

    header('Content-Type: application/json');
    echo json_encode($response);
  }

  public function bayar_hutang()
  {

    $debt_payment = $this->input->post('debt_payment');

    $this->keuangan_model->bayar_hutang($debt_payment);

    $response['alert'] = '<div class="row">
                            <div class="col">
                              <div class="alert alert-warning alert-dismissible fade show" role="alert">
                                <strong class="alert-content">Pembayaran Hutang berhasil dicatat</strong>
                                <button type="button" class="close" data-dismiss="alert">
                                  <span>&times;</span>
                                </button>
                              </div>
                            </div>
                          </div>';

    header('Content-Type: application/json');
    echo json_encode($response);
  }

  public function list_all_debts()
  {
    $debts = [
      'data' => []
    ];

    foreach ($this->keuangan_model->list_all_debts() as $debt) {

      $debt['amount'] = [
        'display' => moneyStrDot($debt['amount']) . ',00',
        'raw'    => $debt['amount']
      ];

      $debt['paid'] = [
        'display' => moneyStrDot($debt['paid']) . ',00',
        'raw'    => $debt['paid']
      ];

      $debt['due'] = [
        'display' => moneyStrDot($debt['due']) . ',00',
        'raw'    => $debt['due']
      ];

      $debt['transaction_date'] = [
        'display' => date('d/m/Y', strtotime($debt['transaction_date'])),
        'raw'     => strtotime($debt['transaction_date']),
        'input'   => date('Y-m-d', strtotime($debt['transaction_date']))
      ];

      $debt['payment_date'] = [
        'display' => date('d/m/Y', strtotime($debt['payment_date'])),
        'raw'    => strtotime($debt['payment_date']),
        'input'   => date('Y-m-d', strtotime($debt['payment_date']))
      ];

      array_push($debts['data'], $debt);
    };

    header('Content-Type: application/json');
    echo json_encode($debts);
  }

  public function list_debt_payment_by_debt_id()
  {

    // Grab debt-id from the submitted form
    $debt_id = $this->input->post('debt-id');

    // Query payment history for the debt_id
    $response['payment_history'] = $this->keuangan_model->get_debt_payment_history_by_debt_id($debt_id);

    header('Content-Type: application/json');
    echo json_encode($response);
  }

  public function bulk_edit_debt_payment()
  {

    // Grab debt_payment data sent by ajax-powered form
    $debt_payment_data = $this->input->post('debt_payment');

    // Batch update the debt_payment table
    $this->db->update_batch('debt_payment', $debt_payment_data, 'debt_payment_id');

    // Compose notification alert
    $response['alert'] = '
      <div class="row mb-2">
        <div class="col">
          <div class="alert alert-warning alert-dismissible fade show shadow" role="alert">
            <strong class="alert-content">Riwayat pembayaran hutang berhasil diperbarui</strong>
            <button type="button" class="close" data-dismiss="alert">
              <span>&times;</span>
            </button>
          </div>
        </div>
      </div>
    ';

    // Sent the response as json
    header('Content-Type: application/json');
    echo json_encode($response);
  }

  public function delete_debt_payment_by_id()
  {
    $debt_payment_id = $this->input->post('debt-payment-id');

    $this->db->where('debt_payment_id', $debt_payment_id);
    $this->db->delete('debt_payment');

    $response['alert'] = '<div class="row mb-2">
                            <div class="col">
                              <div class="alert alert-warning alert-dismissible fade show" role="alert">
                                <strong class="alert-content">Riwayat pembayaran berhasil dihapus</strong>
                                <button type="button" class="close" data-dismiss="alert">
                                  <span>&times;</span>
                                </button>
                              </div>
                            </div>
                          </div>';

    header('Content-Type: application/json');
    echo json_encode($response);
  }

  public function simpan_kreditur()
  {
    // Insert creditor data into table
    $creditor = $this->input->post('creditor');

    // Run simpan kreditur query
    $this->kreditur_model->simpan($creditor);

    // Check whether it's a create or an update operation
    $message = $creditor['creditor_id'] ? 'Detail kreditur berhasil diperbarui' : 'Kreditur baru berhasil ditambahkan';

    $response['action'] = $creditor['creditor_id'] ? 'update' : 'create';

    // Compose notification alert
    $response['alert'] = "<div class='row'>
                <div class='col'>
                  <div class='alert alert-warning alert-dismissible fade show' role='alert'>
                    <strong class='alert-content'>{$message}</strong>
                    <button type='button' class='close' data-dismiss='alert'>
                      <span>&times;</span>
                    </button>
                  </div>
                </div>
              </div>";

    header('Content-Type: application/json');
    echo json_encode($response);
  }

  public function list_all_creditors()
  {
    $creditors = [
      'data' => []
    ];

    foreach ($this->kreditur_model->list_all_creditors() as $creditor) {

      $creditor['receivable'] = [
        'display' => moneyStrDot($creditor['receivable']) . ',00',
        'raw'    => $creditor['receivable']
      ];

      $creditor['paid'] = [
        'display' => moneyStrDot($creditor['paid']) . ',00',
        'raw'    => $creditor['paid']
      ];

      $creditor['due'] = [
        'display' => moneyStrDot($creditor['due']) . ',00',
        'raw'    => $creditor['due']
      ];

      array_push($creditors['data'], $creditor);
    }

    header('Content-Type: application/json');
    echo json_encode($creditors);
  }

  public function hapus_kreditur()
  {
    $creditor = $this->input->post('creditor');

    // Delete creditor table entry by the received creditor_id
    $this->db->where('creditor_id', $creditor['creditor_id']);
    $this->db->delete('creditor');

    // Delete debt by the creditor_id
    $this->db->where('creditor_id', $creditor['creditor_id']);
    $this->db->delete('debt');

    $response['alert'] = '<div class="row mb-2">
                            <div class="col">
                              <div class="alert alert-warning alert-dismissible fade show shadow" role="alert">
                                <strong class="alert-content">Vendor berhasil dihapus</strong>
                                <button type="button" class="close" data-dismiss="alert">
                                  <span>&times;</span>
                                </button>
                              </div>
                            </div>
                          </div>';

    header('Content-Type: application/json');
    echo json_encode($response);
  }

  public function simpan_vendor()
  {

    // Insert vendor data into table
    $vendor = $this->input->post('vendor');

    // Run add vendor query
    $this->vendor_model->simpan($vendor);

    // Check whether it's a create or an update operation
    $message = $vendor['vendor_id'] ? 'Detail vendor berhasil diperbarui' : 'Vendor baru berhasil ditambahkan';

    $response['action'] = $vendor['vendor_id'] ? 'update' : 'create';

    // Compose notification alert and pack into $response
    $response['alert'] = "<div class='row'>
                <div class='col'>
                  <div class='alert alert-warning alert-dismissible fade show' role='alert'>
                    <strong class='alert-content'>{$message}</strong>
                    <button type='button' class='close' data-dismiss='alert'>
                      <span>&times;</span>
                    </button>
                  </div>
                </div>
              </div>";

    header('Content-Type: application/json');
    echo json_encode($response);
  }

  public function list_all_vendors()
  {
    $vendors = [
      'data' => []
    ];

    foreach ($this->vendor_model->list_all_vendors() as $vendor) {

      $vendor['value'] = [
        'display' => moneyStrDot($vendor['value']) . ',00',
        'raw'    => $vendor['value']
      ];

      array_push($vendors['data'], $vendor);
    };

    header('Content-Type: application/json');
    echo json_encode($vendors);
  }

  public function hapus_vendor()
  {
    $vendor = $this->input->post('vendor');

    // Delete vendor table entry by the received vendor_id
    $this->db->where('vendor_id', $vendor['vendor_id']);
    $this->db->delete('vendor');

    $response['alert'] = '<div class="row mb-2">
                            <div class="col">
                              <div class="alert alert-warning alert-dismissible fade show shadow" role="alert">
                                <strong class="alert-content">Vendor berhasil dihapus</strong>
                                <button type="button" class="close" data-dismiss="alert">
                                  <span>&times;</span>
                                </button>
                              </div>
                            </div>
                          </div>';

    header('Content-Type: application/json');
    echo json_encode($response);
  }

  public function simpan_pengeluaran()
  {
    $expense = $this->input->post('expense');

    $this->keuangan_model->simpan_pengeluaran($expense);

    $message = $expense['expense_id'] ? 'Detail pengeluaran berhasil diperbarui' : 'Pengeluaran baru berhasil dicatat';

    $response['action'] = $expense['expense_id'] ? 'update' : 'create';

    $response['alert'] = "<div class='row'>
                            <div class='col'>
                              <div class='alert alert-warning alert-dismissible fade show' role='alert'>
                                <strong class='alert-content'>{$message}</strong>
                                <button type='button' class='close' data-dismiss='alert'>
                                  <span>&times;</span>
                                </button>
                              </div>
                            </div>
                          </div>";

    header('Content-Type: application/json');
    echo json_encode($response);
  }

  public function list_all_expenses()
  {
    $expenses = [
      'data' => []
    ];

    foreach ($this->keuangan_model->list_all_expenses() as $expense) {

      $expense['amount'] = [
        'display' => moneyStrDot($expense['amount']) . ',00',
        'raw'    => $expense['amount']
      ];

      $expense['transaction_date'] = [
        'display' => date('d/m/Y', strtotime($expense['transaction_date'])),
        'raw'     => strtotime($expense['transaction_date']),
        'input'   => date('Y-m-d', strtotime($expense['transaction_date']))
      ];

      array_push($expenses['data'], $expense);
    };

    header('Content-Type: application/json');
    echo json_encode($expenses);
  }

  public function hapus_pengeluaran()
  {

    $expense = $this->input->post('expense');

    // Delete expense table entry by the received expense_id
    $this->db->where('expense_id', $expense['expense_id']);
    $this->db->delete('expense');

    $response['alert'] = '<div class="row mb-2">
                            <div class="col">
                              <div class="alert alert-warning alert-dismissible fade show shadow" role="alert">
                                <strong class="alert-content">Pengeluaran berhasil dihapus</strong>
                                <button type="button" class="close" data-dismiss="alert">
                                  <span>&times;</span>
                                </button>
                              </div>
                            </div>
                          </div>';

    header('Content-Type: application/json');
    echo json_encode($response);
  }

  public function get_expense_per_category()
  {
    $data = $this->keuangan_model->list_expense_by_category();

    header('Content-Type: application/json');
    echo json_encode($data);
  }
}
