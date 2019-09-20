
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

    public function atur_produksi()
    {
        $production = $this->input->post('production');

        $this->produksi_model->simpan($production);

        $redirect_dest = $this->input->post('redirect-here');

        redirect( $redirect_dest );
    }
    
    public function perbarui_detail()
    {

        $production = $this->input->post('production');

        $this->produksi_model->perbarui($production);

        redirect( $this->input->post('input-src') );
    }


}
