<?php
defined('BASEPATH') or exit('No direct script allowed!');

class Produksi extends CI_Controller
{

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

    public function daftar_bordir()
    {
        $data['title'] = 'Daftar Bordir';

        $data['embro_list'] = $this->produksi_model->get_embro_list();

        $data['view_script'] = 'index--embro.js';

        $data['content'] = $this->load->view('dashboard/production/embro-index', $data, TRUE);

        $this->load->view('layout/dashboard', $data);
    }

    public function daftar_finishing()
    {
        $data['title'] = 'Daftar Finishing';

        $data['finishing_list'] = $this->produksi_model->get_finishing_list();

        $data['view_script'] = 'index--finishing.js';

        $data['content'] = $this->load->view('dashboard/production/finishing-index', $data, TRUE);

        $this->load->view('layout/dashboard', $data);
    }

    public function detail_desain($production_id)
    {
        $data['title'] = "Pesanan Desain: DSN-{$production_id}";

        $data['design_detail'] = $this->produksi_model->get_production_detail_by_id($production_id);

        $data['content'] = $this->load->view('dashboard/production/design-detail', $data, TRUE);

        $this->load->view('layout/dashboard', $data);
    }

    public function detail_bordir($production_id)
    {
        $data['title'] = "Pesanan Bordir: BRD-{$production_id}";

        $data['embro_detail'] = $this->produksi_model->get_production_detail_by_id($production_id);

        $data['output_records'] = $this->produksi_model->get_embro_output_by_production_id($production_id);

        $data['view_script'] = 'editor--embro_detail.js';

        $data['content'] = $this->load->view('dashboard/production/embro-detail', $data, TRUE);

        $this->load->view('layout/dashboard', $data);
    }

    public function detail_finishing($production_id)
    {
        $data['title'] = "Tugas Finishing: FIN-{$production_id}";

        $data['finishing_detail'] = $this->produksi_model->get_production_detail_by_id($production_id);

        $data['output_records'] = $this->produksi_model->get_finishing_output_by_production_id($production_id);

        $data['view_script'] = 'editor--finishing_detail.js';

        $data['content'] = $this->load->view('dashboard/production/finishing-detail', $data, TRUE);

        $this->load->view('layout/dashboard', $data);
    }

    public function output()
    {

        $data['title'] = 'Output Produksi';

        $data['period'] = $this->input->post('period') ?? $this->current_period();

        $data['output_list'] = $this->produksi_model->list_operator_output($data['period']);

        $data['view_script'] = 'index--production_output.js';

        $data['content'] = $this->load->view('dashboard/production/output-index', $data, TRUE);

        $this->load->view('layout/dashboard', $data);
    }

    public function proses()
    {
        $data['title'] = 'Proses Berjalan';

        $data['production_data'] = $this->produksi_model->get_production_card_data();
        $data['content'] = $this->load->view('dashboard/production/production--checklist', $data, TRUE);

        $this->load->view('layout/dashboard', $data);
    }

    public function current_period()
    {
        /**
         * Check whether user-defined period range exist.
         * If it is, use that. If not, use current periode
         */

        $start_date = date('d') < 17 ? date('Y-m-d', mktime(0, 0, 0, date('m') - 1, 17, date('Y'))) : date('Y-m-d', mktime(0, 0, 0, date('m'), 17, date('Y')));

        $end_date =  date('d') < 17 ? date('Y-m-d', mktime(0, 0, 0, date('m'), 16, date('Y'))) : date('Y-m-d', mktime(0, 0, 0, date('m') + 1, 16, date('Y')));

        $period = [
            'start' => $start_date,
            'end' => $end_date
        ];

        return $period;
    }
}
