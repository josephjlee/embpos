
<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Keuangan_action extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();

        $this->load->model('keuangan_model');
        $this->load->model('kreditur_model');
    }

    /*
    | -------------------------------------------------------------------
    | PROCESSOR-TYPE METHOD
    | -------------------------------------------------------------------
    | These method have no front-end, their job is to process 
    | form submission. Ex: delete request, input request, etc...
    |     
    */

    public function tambah_kreditur()
    {

        $creditor = $this->input->post('creditor');

        $this->kreditur_model->tambah($creditor);

        redirect(base_url($this->input->post('request-source')));
    }

    public function hapus_kreditur()
    {

        $creditor = $this->input->post('creditor');

        $this->kreditur_model->hapus($creditor);

        redirect(base_url('kontak/kreditur'));
    }

    public function sunting_hutang()
    {
        $debt = $this->input->post('debt');

        $this->keuangan_model->simpan($debt);

        redirect(base_url($this->input->post('request-source')));
    }
}
