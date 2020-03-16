
<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Pelanggan_action extends CI_Controller
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

    public function tambah()
    {

        $customer = $this->input->post('customer');

        $this->db->insert('customer', $customer);

        redirect(base_url('kontak/pelanggan'));
    }

    public function perbarui()
    {
        $customer = $this->input->post('customer');

        $this->db->update('customer', $customer, ['customer_id' => $customer['customer_id']]);

        redirect(base_url('kontak/pelanggan'));
    }


    public function hapus_data()
    {

        $customer = $this->input->post('customer');

        $this->db->delete('customer', ['customer_id' => $customer['customer_id']]);

        redirect(base_url('kontak/pelanggan'));
    }
}
