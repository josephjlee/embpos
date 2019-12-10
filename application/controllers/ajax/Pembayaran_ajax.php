<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Pembayaran_ajax extends CI_Controller
{

  public function __construct()
  {

    parent::__construct();

    $this->load->model('pembayaran_model');
  }

  public function get_monthly_payment()
  {
    $monthly_payment = [
      'monthLabel' => [],
      'amount' => []
    ];

    foreach ($this->pembayaran_model->get_monthly_payment() as $payment_data) {
      array_push($monthly_payment['monthLabel'], $payment_data['monthName']);
      array_push($monthly_payment['amount'], $payment_data['amount']);
    }

    header('Content-Type: application/json');
    echo json_encode($monthly_payment);
  }
}
