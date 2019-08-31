<?php
defined('BASEPATH') or exit('No direct script access allowed.');

class Pesanan_ajax extends CI_Controller
{

	public function __construct()
	{
		parent::__construct();

		$this->load->model('pesanan_model');
	}

	public function get_customer_order()
  {

    $customer_id = $this->input->post('customer_id');

    $customer_order = $this->pesanan_model->get_order_by_customer_id($customer_id);

    echo json_encode($customer_order);
	}
	
	public function item_list()
	{

		$items = $this->pesanan_model->get_all_items();

		echo json_encode($items);
	}

	public function get_item_position()
	{
		$item_id = $this->input->post('item_id');
		
		$position = $this->pesanan_model->get_position_by_item_id($item_id);

		function opt_for_select2($position) {

			$options = [
				'id' 		=> $position['position_id'],
				'text' 	=> $position['name']
			];

			return $options;
		}

		$position_select2 = array_map("opt_for_select2", $position);

		echo json_encode($position_select2);

	}

	public function get_stitch_price()
	{
		$quantity_id = $this->input->post('quantity_id');
		
		$pricelist = $this->pesanan_model->get_stitch_price_by_quantity_id($quantity_id);

		echo json_encode($pricelist);
	}
	
}
