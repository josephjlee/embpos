<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Kontak extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();

        $this->load->model('pelanggan_model');
        $this->load->model('kreditur_model');
        $this->load->model('vendor_model');
        $this->load->helper('text');
    }

    public function pelanggan()
    {

        $data['title'] = 'Pelanggan';

        $data['customers'] = $this->pelanggan_model->list_all_customers();

        $data['content'] = $this->load->view('dashboard/customer/customer-index', $data, TRUE);

        $data['view_script'] = 'index--customer.js';

        $this->load->view('layout/dashboard', $data);
    }

    public function kreditur()
    {

        $data['title'] = 'Kreditur';

        $data['creditors'] = $this->kreditur_model->list_all_creditors();

        $data['content'] = $this->load->view('dashboard/finance/creditor-index', $data, TRUE);

        $data['view_script'] = 'index--creditor.js';

        $this->load->view('layout/dashboard', $data);
    }

    public function vendor()
    {
        $data['title'] = 'Vendor';

        $data['vendors'] = $this->vendor_model->list_all_vendors();

        $data['content'] = $this->load->view('dashboard/finance/vendor-index', $data, TRUE);

        $data['view_script'] = 'index--vendor.js';

        $this->load->view('layout/dashboard', $data);
    }
}
