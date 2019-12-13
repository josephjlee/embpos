<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Keuangan_ajax extends CI_Controller
{

  public function __construct()
  {

    parent::__construct();

    $this->load->model('keuangan_model');
  }

  public function simpan_hutang()
  {
    $debt = $this->input->post('debt');

    $this->keuangan_model->simpan($debt);
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
}
