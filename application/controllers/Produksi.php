<?php
defined('BASEPATH') or exit('No direct script allowed!');

class Produksi extends CI_Controller {

    public function __construct()
    {
        parent::__construct();

        $this->load->model('produksi_model');
    }

    public function daftar_desain()
    {
        $data['title'] = 'Daftar Desain';

        $data['design_list'] = $this->produksi_model->get_design_list();

        $data['view_script'] = 'index--design.js';

        $data['content'] = $this->load->view('dashboard/production/design-index', $data, TRUE);

        $this->load->view('layout/dashboard', $data);
    }

    public function detail_desain($production_design_id)
    {
        $data['title'] = "Pesanan Desain: DSN-{$production_design_id}";

        $data['design_detail'] = $this->produksi_model->get_design_detail_by_id($production_design_id);

        $data['content'] = $this->load->view('dashboard/production/design-detail', $data, TRUE);

        $this->load->view('layout/dashboard', $data);
    }

    public function proses()
    {
        $data['title'] = 'Proses Berjalan';

        $data['production_data'] = $this->produksi_model->get_production_card_data();
        $data['content'] = $this->load->view('dashboard/production/production--checklist', $data, TRUE);
        
        $this->load->view('layout/dashboard', $data);
    }

}