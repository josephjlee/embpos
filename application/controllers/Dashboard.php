<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Dashboard extends CI_Controller
{
  public function __construct()
  {
    parent::__construct();
    $this->load->model('invoice_model');
    $this->load->model('pesanan_model');
    $this->load->model('penjualan_model');
    $this->load->model('keuangan_model');
  }
  public function index()
  {

    $data['title'] = 'Dashboard';

    $data['data_card'] = [
      'monthly_invoice_rev_avg' => $this->invoice_model->get_monthly_invoice_revenue_average(),
      'monthly_order_qty_avg' => $this->pesanan_model->get_monthly_order_quantity_average(),
      'monthly_product_sale_avg' => $this->penjualan_model->get_monthly_product_sale_avg(),
      'total_receivable' => $this->invoice_model->get_total_receivable()
    ];

    $data['unpaid_invoices'] = $this->invoice_model->get_unpaid_invoice();

    $data['unpaid_debts'] = $this->keuangan_model->list_unpaid_debts();

    $data['near_deadline_orders'] = $this->pesanan_model->get_near_deadline_order();

    $data['content'] = $this->load->view('dashboard/dashboard-index.php', $data, TRUE);

    $data['view_script'] = 'dashboard.js';

    $this->load->view('layout/dashboard', $data);
  }
}
