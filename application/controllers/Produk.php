<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Produk extends MY_Controller
{

  public function __construct()
  {

    parent::__construct();

    $this->load->model('produk_model');
  }

  /*
  | -------------------------------------------------------------------
  | RENDER-TYPE METHOD
  | -------------------------------------------------------------------
  | Each of these method correspond to a single page on the front-end.
  | Example: $pesanan->buat() = Halaman 'Buat Pesanan Baru'
  |
  */

  public function tambah()
  {

    $data['title'] = 'Tambah Produk';

    $data['content'] = $this->load->view('dashboard/product/product-editor', $data, TRUE);

    $data['view_script'] = 'editor--product.js';

    $this->load->view('layout/dashboard', $data);
  }

  public function sunting($product_id)
  {

    $data['product'] = $this->produk_model->get_product_by_product_id($product_id);

    $data['title'] = 'Sunting ' . $data['product']['sku'];

    $data['content'] = $this->load->view('dashboard/product/product-editor', $data, TRUE);

    $data['view_script'] = 'editor--product.js';

    $this->load->view('layout/dashboard', $data);
  }

  public function semua()
  {

    $data['title'] = 'Semua produk';

    $data['products'] = $this->produk_model->get_all_products();

    $data['content'] = $this->load->view('dashboard/product/product-index', $data, TRUE);

    $data['view_script'] = 'index--product.js';

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
