
<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Produksi_pcsr extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();

        $this->load->model('produksi_model');
    }

    /*
    | -------------------------------------------------------------------
    | PROCESSOR-TYPE METHOD
    | -------------------------------------------------------------------
    | These method have no front-end, their job is to process 
    | form submission. Ex: delete request, input request, etc...
    |     
    */

    public function pesan_desain()
    {

        $production_design = $this->input->post('production_design');

        $this->produksi_model->pesan_desain($production_design);

        redirect( base_url('pesanan/semua') );
    }


}
