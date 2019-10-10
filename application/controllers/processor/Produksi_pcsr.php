
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

        redirect($redirect_dest);
    }

    public function perbarui_detail()
    {

        $production = $this->input->post('production');

        // Use existing file or grab new uploaded file
        $production['file'] = $this->file_exist($production['production_id']) ?? $this->unggah($_FILES['file'], $this->input->post('input-src'));

        $this->produksi_model->perbarui($production);

        redirect($this->input->post('input-src'));
    }

    public function record_machine_output()
    {
        // Grab output data from form submission
        $output = $this->input->post('output');

        // Record operator's output
        $this->produksi_model->rekam_output_mesin($output);

        // Redirect to its original page
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
