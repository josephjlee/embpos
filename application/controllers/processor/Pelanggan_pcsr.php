
<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Pelanggan_pcsr extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();

        $this->load->model('pelanggan_model');
    }

    /*
    | -------------------------------------------------------------------
    | PROCESSOR-TYPE METHOD
    | -------------------------------------------------------------------
    | These method have no front-end, their job is to process 
    | form submission. Ex: delete request, input request, etc...
    |     
    */

    public function simpan_data()
    {

        $customer = $this->input->post('customer');

        $this->pelanggan_model->simpan($customer);

        redirect(base_url('kontak/pelanggan'));
    }


    public function hapus_data()
    {

        $customer = $this->input->post('customer');

        $this->pelanggan_model->hapus($customer);

        redirect(base_url('kontak/pelanggan'));
    }
}
