<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Keuangan extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();

        $this->load->model('keuangan_model');
        $this->load->model('kreditur_model');
        $this->load->model('vendor_model');
    }

    public function pengeluaran()
    {
        $data['title'] = 'Pengeluaran';

        $data['debts'] = $this->keuangan_model->list_all_debts();

        $data['content'] = $this->load->view('dashboard/finance/expense-index', $data, TRUE);

        $data['view_script'] = 'index--expense.js';

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

    public function daftar_hutang()
    {

        $data['title'] = 'Daftar Hutang';

        $data['debts'] = $this->keuangan_model->list_all_debts();

        $data['content'] = $this->load->view('dashboard/finance/debt-index', $data, TRUE);

        $data['view_script'] = 'index--debt.js';

        $this->load->view('layout/dashboard', $data);
    }

    public function detail_hutang($debt_id)
    {
        $data['title'] = "Detail Hutang HTG-{$debt_id}";

        $data['debt_detail'] = $this->keuangan_model->get_debt_by_debt_id($debt_id);

        $data['content'] = $this->load->view('dashboard/finance/debt-editor', $data, TRUE);

        // $data['view_script'] = 'editor--debt.js';

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
}
