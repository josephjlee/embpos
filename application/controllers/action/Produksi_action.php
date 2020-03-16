
<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Produksi_action extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();

        $this->load->model('produksi_model');
        $this->load->model('pesanan_model');
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

        $production['file'] = $this->file_exist($production['production_id']) ?? $this->unggah($_FILES['file'], $this->input->post('input-src'));

        if (!empty($production['production_id'])) {
            $this->db->update('production', $production, ['production_id' => $production['production_id']]);
            redirect($this->input->post('input-src'));
        }

        $this->db->insert('production', $production);
        redirect($this->input->post('input-src'));
    }

    public function rekam_output_desainer()
    {
        // Grab the color order data submited by the designer
        $production = $this->input->post('production');

        // Check file submission, use existing or the uploaded one
        // $production['file'] = $this->file_exist($production['production_id']) ?? $this->unggah($_FILES['file'], $this->input->post('input-src'));

        // Update production_status_name to 'Desain Selesai'
        $production['production_status_id'] = 3;

        // Execute update process
        $this->db->update('production', $production, ['production_id' => $production['production_id']]);

        // Update the production_status_id for order table as well
        $order = $this->input->post('order');
        $order['production_status_id'] = $production['production_status_id'];
        $this->db->update('order', $order, ['order_id' => $order['order_id']]);

        // Redirect to its original page
        redirect($this->input->post('input-src'));
    }

    public function rekam_output_operator()
    {
        // Grab output data from form submission
        $output = $this->input->post('output');

        // Record operator's output
        $this->db->insert('output_embro', $output);

        // Grab current output and order quantity data
        $current_output = $this->input->post('current-output');
        $order_quantity = $this->input->post('order-qty');

        // Check total output after update. If equal to order quantity then update production_status_id to 6
        $production['production_id'] = $output['production_id'];
        $production['production_status_id'] = $current_output + $output['quantity'] == $order_quantity ? 6 : 5;

        $this->db->update('production', $production, ['production_id' => $production['production_id']]);

        // Update the production_status_id for order table as well
        $order = $this->input->post('order');
        $order['production_status_id'] = $production['production_status_id'];
        $this->db->update('order', $order, ['order_id' => $order['order_id']]);

        // Redirect to its original page
        redirect($this->input->post('input-src'));
    }

    public function rekam_output_finishing()
    {
        // Grab output data from form submission
        $output = $this->input->post('output');

        // Record operator's output
        $this->db->insert('output_finishing', $output);

        // Grab current output and order quantity data
        $current_output = $this->input->post('current-output');
        $order_quantity = $this->input->post('order-qty');

        // Check total output after update. If equal to order quantity then update production_status_id to 6
        $production['production_id'] = $output['production_id'];
        $production['production_status_id'] = $current_output + $output['quantity'] == $order_quantity ? 9 : 8;

        $this->db->update('production', $production, ['production_id' => $production['production_id']]);

        // Update the production_status_id for order table as well
        $order = $this->input->post('order');
        $order['production_status_id'] = $production['production_status_id'];
        $this->db->update('order', $order, ['order_id' => $order['order_id']]);

        // Redirect to its original page
        redirect($this->input->post('input-src'));
    }

    public function perbarui_detail()
    {
        $production = $this->input->post('production');

        $order = $this->input->post('order');
        $order['production_status_id'] = $production['production_status_id'];

        $this->db->update('production', $production, ['production_id' => $production['production_id']]);

        $this->db->update('order', $order, ['order_id' => $order['order_id']]);

        redirect($this->input->post('input-src'));
    }

    /*
    | -------------------------------------------------------------------
    | UTILITY-TYPE METHOD
    | -------------------------------------------------------------------
    | These method have no front-end, their job is to help render-type
    | method on accomplishing their task.
    |
     */

    public function file_exist($production_id)
    {

        // Check existing file of a production order
        $this->db->select('file');
        $this->db->from('production');
        $this->db->where('production_id', $production_id);

        return $this->db->get()->row_array()['file'];
    }

    public function unggah($file, $redirect_dest)
    {

        // If input file is empty then bailed out
        if ($file['size'] == 0) {
            return null;
        }

        // Set up the upload library configuration
        $config['upload_path'] = realpath(FCPATH . 'assets/img/dst');
        $config['allowed_types'] = 'dst';
        $config['max_size'] = 40960;

        $this->load->library('upload', $config);

        // If error, redirect to pesanan/buat with error message
        if (!$this->upload->do_upload('file')) {
            $this->session->set_flashdata('message', $this->upload->display_errors('<div class="alert alert-warning alert-dismissible fade show shadow" role="alert">', '<button type="button" class="close" data-dismiss="alert"><span>&times;</span></button></div>'));

            redirect($redirect_dest);
        }

        return $this->upload->data()['file_name'];
    }
}
