<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Keuangan_ajax extends CI_Controller
{

  public function __construct()
  {

    parent::__construct();

    $this->load->model('keuangan_model');
    $this->load->model('kreditur_model');
  }

  public function simpan_hutang()
  {
    $debt = $this->input->post('debt');

    $this->keuangan_model->simpan($debt);

    $alert = '<div class="row mb-2">
                <div class="col">
                  <div class="alert alert-warning alert-dismissible fade show shadow" role="alert">
                    <strong class="alert-content">Hutang baru berhasil dicatat</strong>
                    <button type="button" class="close" data-dismiss="alert">
                      <span>&times;</span>
                    </button>
                  </div>
                </div>
              </div>';

    header('Content-Type: application/json');
    echo json_encode($alert);
  }

  public function hapus_hutang()
  {

    $debt = $this->input->post('debt');

    $this->db->where('debt_id', $debt['debt_id']);
    $this->db->delete('debt');

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

    $response['alert'] = '<div class="row mb-2">
                            <div class="col">
                              <div class="alert alert-warning alert-dismissible fade show shadow" role="alert">
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

  public function tambah_kreditur()
  {

    // Insert creditor data into table
    $creditor = $this->input->post('creditor');
    $this->kreditur_model->tambah($creditor);
    $new_creditor_id = $this->db->insert_id();

    // Store the inserted creditor data into variable
    $new_creditor_data = $this->kreditur_model->get_creditor_by_id($new_creditor_id);
    $new_creditor = [
      'id' => $new_creditor_data['creditor_id'],
      'text' => $new_creditor_data['name']
    ];

    // Compose notification alert
    $alert = '<div class="row mb-2">
                <div class="col">
                  <div class="alert alert-warning alert-dismissible fade show shadow" role="alert">
                    <strong class="alert-content">Kreditur baru berhasil ditambahkan</strong>
                    <button type="button" class="close" data-dismiss="alert">
                      <span>&times;</span>
                    </button>
                  </div>
                </div>
              </div>';

    $response = [
      'newCreditor' => $new_creditor,
      'alert'       => $alert
    ];

    header('Content-Type: application/json');
    echo json_encode($response);
  }
}
