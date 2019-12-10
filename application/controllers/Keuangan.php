<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Keuangan extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();

        $this->load->model('keuangan_model');

        $this->load->model('pelanggan_model');
    }

    public function hutang()
    {

        $data['title'] = 'Hutang';

        $data['debts'] = $this->keuangan_model->list_all_debts();

        $data['content'] = $this->load->view('dashboard/finance/debt-index', $data, TRUE);

        $data['view_script'] = 'index--debt.js';

        $this->load->view('layout/dashboard', $data);
    }
}
