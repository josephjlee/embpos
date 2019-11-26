<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Pesanan extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();

        $this->load->model('pesanan_model');
        $this->load->model('pelanggan_model');
        $this->load->model('produksi_model');
    }

    /*
    | -------------------------------------------------------------------
    | RENDER-TYPE METHOD
    | -------------------------------------------------------------------
    | Each of these method correspond to a single page on the front-end.
    | Example: $pesanan->buat() = Halaman 'Buat Pesanan Baru'
    |
    */

    public function buat()
    {
        $data['title'] = 'Buat Pesanan';

        $data['order']['order_number'] = $this->pesanan_model->new_order_number();

        $data['content'] = $this->load->view('dashboard/order/order-editor', $data, true);

        $data['view_script'] = 'editor--order.js';

        $this->load->view('layout/dashboard', $data);
    }

    public function sunting($order_id)
    {
        $data['order'] = $this->pesanan_model->get_single_order($order_id);

        $data['title'] = 'Sunting PSN-' . $data['order']['order_number'];

        $data['production'] = $this->produksi_model->get_production_detail_by_order_id($order_id);

        $data['is_invoiced'] = $this->pesanan_model->check_invoice($order_id);

        $data['output'] = $this->produksi_model->get_production_output_by_order_id($order_id);

        $data['production_status'] = $this->produksi_model->check_production_status_by_order_id($order_id);

        $data['content'] = $this->load->view('dashboard/order/order-editor', $data, true);

        $data['view_script'] = 'editor--order.js';

        $this->load->view('layout/dashboard', $data);
    }

    public function pratinjau($order_id)
    {
        $data['order'] = $this->pesanan_model->get_single_order($order_id);

        $data['title'] = 'Pratinjau PSN-' . $data['order']['order_number'];

        $data['content'] = $this->load->view('dashboard/order/order-preview', $data, true);

        $this->load->view('layout/dashboard', $data);
    }

    public function daftar()
    {
        $data['title'] = 'Daftar Pesanan';

        $data['orders'] = $this->pesanan_model->get_all_orders();

        $data['content'] = $this->load->view('dashboard/order/order-index', $data, true);

        $data['view_script'] = 'index--order.js';

        $this->load->view('layout/dashboard', $data);
    }

    public function selesai()
    {
        $data['title'] = 'Pesanan Selesai';
        $data['orders'] = $this->pesanan_model->get_all_finished_orders();
        $data['content'] = $this->load->view('dashboard/order/order-index', $data, true);

        $data['view_script'] = 'index--order.js';

        $this->load->view('layout/dashboard', $data);
    }

    public function aktif()
    {
        $data['title'] = 'Pesanan Aktif';
        $data['orders'] = $this->pesanan_model->get_all_active_orders();
        $data['content'] = $this->load->view('dashboard/order/order-index', $data, true);

        $data['view_script'] = 'index--order.js';

        $this->load->view('layout/dashboard', $data);
    }

    public function antri()
    {
        $data['title'] = 'Pesanan Antri';
        $data['orders'] = $this->pesanan_model->get_queued_orders();
        $data['content'] = $this->load->view('dashboard/order/order-index', $data, true);

        $data['view_script'] = 'index--order.js';

        $this->load->view('layout/dashboard', $data);
    }

    /*
    | -------------------------------------------------------------------
    | UTILITY-TYPE METHOD
    | -------------------------------------------------------------------
    | These method have no front-end, their job is to help render-type
    | method on accomplishing their task.
    |
    */

    public function image_exist($order_id)
    {

        // Check existing image of a order
        $this->db->select('image');
        $this->db->from('order');
        $this->db->where('order_id', $order_id);

        return $this->db->get()->row_array()['image'];
    }

    public function unggah($file)
    {

        // If input file is empty then bailed out
        if ($file['size'] == 0) {
            return null;
        }

        // Set up the upload library configuration
        $config['upload_path']      = realpath(FCPATH . 'assets/img/artwork');
        $config['allowed_types']    = 'gif|jpg|png';
        $config['max_size']         = 100;

        $this->load->library('upload', $config);

        // If error, redirect to pesanan/buat with error message
        if (!$this->upload->do_upload('image')) {
            $this->session->set_flashdata('message', $this->upload->display_errors('<div class="alert alert-warning alert-dismissible fade show" role="alert">', '<button type="button" class="close" data-dismiss="alert"><span>&times;</span></button></div>'));

            redirect(base_url('pesanan/buat/'));
        }

        return $this->upload->data()['file_name'];
    }
}
