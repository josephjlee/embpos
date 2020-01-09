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
      array_push($monthly_payment['monthLabel'], $payment_data['period']);
      array_push($monthly_payment['amount'], $payment_data['amount']);
    }

    header('Content-Type: application/json');
    echo json_encode($monthly_payment);
  }

  public function get_payment_amount_per_method()
  {
    $payment_by_method = [
      'methodLabel' => [],
      'percentage' => []
    ];

    $payment_amount = [];

    foreach ($this->pembayaran_model->get_payment_amount_per_method() as $payment_data) {
      array_push($payment_by_method['methodLabel'], $payment_data['method']);
      array_push($payment_amount, $payment_data['amount']);
    }

    $payment_amount_sum = array_sum($payment_amount);

    foreach ($payment_amount as $pd) {
      array_push($payment_by_method['percentage'], round(($pd / $payment_amount_sum) * 100));
    }

    header('Content-Type: application/json');
    echo json_encode($payment_by_method);
  }

  public function get_payment_amount_per_customer_category()
  {
    $payment_by_category = [
      'categoryLabel' => [],
      'percentage' => []
    ];

    $payment_amount = [];

    foreach ($this->pembayaran_model->get_payment_amount_per_customer_category() as $payment_data) {
      array_push($payment_by_category['categoryLabel'], $payment_data['category']);
      array_push($payment_amount, $payment_data['amount']);
    }

    $payment_amount_sum = array_sum($payment_amount);

    foreach ($payment_amount as $pd) {
      array_push($payment_by_category['percentage'], round(($pd / $payment_amount_sum) * 100));
    }

    header('Content-Type: application/json');
    echo json_encode($payment_by_category);
  }
}
