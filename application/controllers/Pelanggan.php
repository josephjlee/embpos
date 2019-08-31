<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Pelanggan extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();

        $this->load->model('pelanggan_model');
    }

    public function semua()
    {

        $data['title'] = 'Semua Pelanggan';
        
        $data['customers'] = $this->pelanggan_model->list_all_customers();

        $data['content'] = $this->load->view('dashboard/customer/customer-index', $data, TRUE);

        $data['view_script'] = 'index--customer.js';

        $this->load->view('layout/dashboard', $data);
    }

}
