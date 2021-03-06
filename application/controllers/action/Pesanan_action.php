<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Pesanan_action extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();

        $this->load->model('pesanan_model');
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

    public function simpan()
    {
        $order = $this->input->post('order');
        $order['image'] = $this->unggah($_FILES['image']);
        $order['quantity'] = str_replace(',', '', $order['quantity']);
        $order['price'] = str_replace(',', '', $order['price']);

        $this->db->insert('order', $order);

        redirect(base_url('pesanan/buat'));
    }

    public function simpan_dari_invoice()
    {
        $order = $this->input->post('order');
        $order['quantity'] = str_replace(',', '', $order['quantity']);
        $order['price'] = str_replace(',', '', $order['price']);

        $this->db->update('order', $order, ['order_id' => $order['order_id']]);

        redirect(base_url($this->input->post('source-url')));
    }

    public function perbarui()
    {
        // Grab input data and image data
        $order = $this->input->post('order');

        // Use existing image or grab new uploaded image
        $order['image'] = !empty($this->image_exist($order['order_id'])) ? $this->image_exist($order['order_id']) : $this->unggah($_FILES['image']);

        // Remove thousand separator from number-type input data
        $order['quantity'] = str_replace(',', '', $order['quantity']);
        $order['price'] = str_replace(',', '', $order['price']);

        // Concatenate array of machine number into comma separated machine_number
        if (isset($order['machine_number'])) {
            $order['machine_number'] = implode(',', $order['machine_number']);
        }

        // Save to the db
        $this->db->update('order', $order, ['order_id' => $order['order_id']]);

        // Grab redirection destination
        $redirect_target = base_url($this->input->post('redirect-here'));

        // Redirect to the source url
        redirect($redirect_target);
    }

    public function tandai_sebagai()
    {
        $order = $this->input->post('order');

        $date  = new DateTime("now", new DateTimeZone('Asia/Jakarta'));
        $order['status_date'] = $date->format('Y-m-d H:i:s');

        $this->db->update('order', $order, ['order_id' => $order['order_id']]);

        $redirect_here = $this->input->post('redirect-here') ?? 'pesanan/sunting/' . $order['order_id'];
        redirect(base_url($redirect_here));
    }

    public function lepas_artwork()
    {
        $order = $this->input->post('order');

        $this->pesanan_model->detach_artwork($order);

        redirect(base_url('pesanan/sunting/' . $order['order_id']));
    }

    public function hapus_pesanan()
    {
        $order = $this->input->post('order');

        $this->db->delete('order', ['order_id' => $order['order_id']]);

        redirect(base_url('pesanan/daftar'));
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
            $this->session->set_flashdata('message', $this->upload->display_errors('<div class="alert alert-warning alert-dismissible fade show shadow" role="alert">', '<button type="button" class="close" data-dismiss="alert"><span>&times;</span></button></div>'));

            redirect(base_url('pesanan/buat/'));
        }

        return $this->upload->data()['file_name'];
    }
}
