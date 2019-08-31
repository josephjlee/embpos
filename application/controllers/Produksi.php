<?php
defined('BASEPATH') or exit('No direct script allowed!');

class Produksi extends CI_Controller {

    public function __construct()
    {
        parent::__construct();

        $this->load->model('Produksi_model', 'produksi');
    }

    public function proses()
    {
        $data['title'] = 'Proses Berjalan';

        $data['production_data'] = $this->produksi->get_production_card_data();
        $data['content'] = $this->load->view('dashboard/production/production--checklist', $data, TRUE);
        
        $this->load->view('layout/dashboard', $data);
    }

}