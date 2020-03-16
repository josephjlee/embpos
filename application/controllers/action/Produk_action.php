<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Produk_action extends CI_Controller
{

  public function __construct()
  {

    parent::__construct();

    $this->load->model('produk_model');
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

    // Grab input data and image data
    $product = $this->input->post('product');
    $product['image'] = $this->unggah($_FILES['image']);
    $product['base_price'] = str_replace(',', '', $product['base_price']);
    $product['sell_price'] = str_replace(',', '', $product['sell_price']);
    $product['stock'] = str_replace(',', '', $product['stock']);

    $this->db->insert('product', $product);

    redirect(base_url('produk/semua'));
  }

  public function perbarui()
  {

    // Grab input data and image data
    $product = $this->input->post('product');

    // Use existing image or grab new uploaded image
    $product['image'] = $this->image_exist($product['product_id']) ?? $this->unggah($_FILES['image']);

    $product['base_price'] = str_replace(',', '', $product['base_price']);
    $product['sell_price'] = str_replace(',', '', $product['sell_price']);
    $product['stock'] = str_replace(',', '', $product['stock']);

    // Save to the db
    $this->db->update('product', $product, ['product_id' => $product['product_id']]);

    // Redirect to current editing page
    redirect(base_url('produk/sunting/') . $product['product_id']);
  }

  public function hapus_produk()
  {

    $product = $this->input->post('product');

    $this->db->delete('product', ['product_id' => $product['product_id']]);

    redirect(base_url('produk/semua'));
  }

  public function lepas_gambar()
  {

    $product = $this->input->post('product');

    $this->produk_model->detach_image($product);

    redirect(base_url('produk/sunting/') . $product['product_id']);
  }

  /*
  | -------------------------------------------------------------------
  | UTILITY-TYPE METHOD
  | -------------------------------------------------------------------
  | These method have no front-end, their job is to help render-type 
  | method on accomplishing their task.
  |     
  */

  public function unggah($file)
  {

    // If input file is empty then bailed out
    if ($file['size'] == 0) {
      return NULL;
    }

    // Set up the upload library configuration
    $config['upload_path']      = realpath(FCPATH . 'assets/img/product');
    $config['allowed_types']    = 'gif|jpg|png';
    $config['max_size']         = 100;

    $this->load->library('upload', $config);

    // If error, redirect to produk/tambah with error message
    if (!$this->upload->do_upload('image')) {

      $this->session->set_flashdata('message', $this->upload->display_errors('<div class="alert alert-warning alert-dismissible fade show" role="alert">', '<button type="button" class="close" data-dismiss="alert"><span>&times;</span></button></div>'));

      redirect(base_url('produk/tambah/'));
    }

    return $this->upload->data('file_name');
  }

  public function image_exist($product_id)
  {

    // Check existing image of a product
    $this->db->select('image');
    $this->db->from('product');
    $this->db->where('product_id', $product_id);

    return $this->db->get()->row_array()['image'];
  }
}
