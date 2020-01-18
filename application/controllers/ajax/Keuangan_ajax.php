<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Keuangan_ajax extends CI_Controller
{

  public function __construct()
  {

    parent::__construct();

    $this->load->model('invoice_model');
    $this->load->model('pesanan_model');
    $this->load->model('keuangan_model');
    $this->load->model('vendor_model');
    $this->load->model('kreditur_model');
  }

  public function list_all_invoices()
  {
    $invoices = [
      'data' => []
    ];

    foreach ($this->invoice_model->list_all_invoices() as $invoice) {

      $invoice['payment_due'] = [
        'display' => moneyStrDot($invoice['payment_due']),
        'raw'    => $invoice['payment_due']
      ];

      $invoice['payment_date'] = [
        'display' => date('d/m/Y', strtotime($invoice['payment_date'])),
        'raw'     => strtotime($invoice['payment_date']),
        'input'   => date('Y-m-d', strtotime($invoice['payment_date']))
      ];

      $invoice['invoice_date'] = [
        'display' => date('d/m/Y', strtotime($invoice['invoice_date'])),
        'raw'     => strtotime($invoice['invoice_date']),
        'input'   => date('Y-m-d', strtotime($invoice['invoice_date']))
      ];

      $invoice['payment_status'] = $this->invoice_model->check_payment_status($invoice['invoice_id'])['payment_status'];

      $invoice['order_progress'] = $this->pesanan_model->check_order_progress($invoice['invoice_id']);

      array_push($invoices['data'], $invoice);
    };

    header('Content-Type: application/json');
    echo json_encode($invoices);
  }

  public function get_total_active_invoice()
  {
    $invoices = $this->invoice_model->list_all_invoices();

    $wip = $this->pesanan_model->get_invoice_total_wip($invoices);

    header('Content-Type: application/json');
    echo json_encode($wip);
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

  public function simpan_pembayaran_hutang()
  {

    $debt_payment = $this->input->post('debt_payment');

    $message = '';

    if (!empty($debt_payment['debt_payment_id'])) {
      $this->keuangan_model->perbarui_pembayaran_hutang($debt_payment);
      $message = 'Detail pembayaran hutang telah diperbarui';
    } else {
      $this->keuangan_model->bayar_hutang($debt_payment);
      $message = 'Pembayaran hutang berhasil disimpan';
    }

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

  public function list_all_debt_payments()
  {
    $debt_payments = [
      'data' => []
    ];

    foreach ($this->keuangan_model->list_all_debt_payments() as $debt_payment) {

      $debt_payment['amount'] = [
        'display' => moneyStrDot($debt_payment['amount']) . ',00',
        'raw'    => $debt_payment['amount']
      ];

      $debt_payment['payment_date'] = [
        'display' => date('d/m/Y', strtotime($debt_payment['payment_date'])),
        'raw'     => strtotime($debt_payment['payment_date']),
        'input'   => date('Y-m-d', strtotime($debt_payment['payment_date']))
      ];

      array_push($debt_payments['data'], $debt_payment);
    };

    header('Content-Type: application/json');
    echo json_encode($debt_payments);
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
                                <strong class="alert-content">Riwayat pembayaran hutang berhasil dihapus</strong>
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

    // Create message placeholder variable
    $message = '';

    // Data processing for creditor data with creditor_id
    if (!empty($creditor['creditor_id'])) {
      // Run update kreditur query
      $this->kreditur_model->perbarui($creditor);

      // FIll $message with update notification
      $message = 'Detail kreditur berhasil diperbarui';

      $response['action'] = 'update';
    } else {

      // By default run tambah kreditur query
      $this->kreditur_model->tambah($creditor);

      // Grab creditor_id of the newly created creditor
      $new_creditor_id = $this->db->insert_id();

      // Store the inserted creditor data into variable
      $new_creditor_data = $this->kreditur_model->get_creditor_by_id($new_creditor_id);
      $response['newCreditor'] = [
        'id' => $new_creditor_data['creditor_id'],
        'text' => $new_creditor_data['name']
      ];

      // FIll $message with create notification
      $message = 'Kreditur baru berhasil ditambahkan';

      $response['action'] = 'create';
    }

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

    // Create message placeholder variable
    $message = '';

    // Data processing for vendor data with vendor_id
    if (!empty($vendor['vendor_id'])) {
      // Run update vendor query
      $this->vendor_model->perbarui($vendor);

      // FIll $message with update notification
      $message = 'Detail vendor berhasil diperbarui';

      $response['action'] = 'update';
    } else {

      // By default run tambah vendor query
      $this->vendor_model->tambah($vendor);

      // Grab vendor_id of the newly created vendor
      $new_vendor_id = $this->db->insert_id();

      // Store the inserted vendor data into variable
      $new_vendor_data = $this->vendor_model->get_vendor_by_id($new_vendor_id);
      $response['newVendor'] = [
        'id' => $new_vendor_data['vendor_id'],
        'text' => $new_vendor_data['name']
      ];

      // FIll $message with create notification
      $message = 'Vendor baru berhasil ditambahkan';

      $response['action'] = 'create';
    }

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

    // Delete expense table entry by vendor_id
    $this->db->where('vendor_id', $vendor['vendor_id']);
    $this->db->delete('expense');

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
