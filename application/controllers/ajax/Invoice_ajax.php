<?php
defined('BASEPATH') or exit('No direct script access allowed.');

class Invoice_ajax extends CI_Controller
{

  public function get_detil_pembayaran()
  {

    $payment_id = $this->input->post('paymentID');
    $payment = $this->pembayaran_model->get_payment_detail($payment_id);

    $payment_data = [
      'payment_amount'     => $payment['payment_amount'],
      'payment_method_id'  => $payment['payment_method_id'],
      'customer_id'        => $payment['customer_id'],
      'payment_date'       => date('Y-m-d', strtotime($payment['payment_date'])),
      'customer_name'      => $payment['customer_name'],
      'invoice_id'         => $payment['invoice_id'],
      'invoice_number'     => $payment['invoice_number']
    ];

    echo json_encode($payment_data);
  }

}
