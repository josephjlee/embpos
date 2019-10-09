<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Pelanggan_ajax extends CI_Controller
{

  public function __construct()
	{

		parent::__construct();

    $this->load->model('pelanggan_model');
    
  }
  
  public function get_customer_contact()
  {

    $customer_id = $this->input->post('customer_id');

    $customer_data = $this->pelanggan_model->get_customer_by_id($customer_id);

    echo json_encode($customer_data);
  }
  
  public function tambah_pelanggan()
  {

    $customer = $this->input->post('customer');

    $this->pelanggan_model->tambah($customer);

    $new_customer_id = $this->db->insert_id();

    $new_customer = $this->pelanggan_model->get_customer_by_id($new_customer_id);

    echo json_encode($new_customer);
  }
}
