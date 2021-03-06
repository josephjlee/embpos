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

		function opt_for_select2($position)
		{

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

	public function new_order_number()
	{
		$this->db->select('number');
		$this->db->from('order');
		$this->db->order_by('number', 'ASC');

		$last_order_number = $this->db->get()->last_row('array')['number'];
		$new_order_number = $last_order_number + 1;

		echo json_encode($new_order_number);
	}

	public function add_order()
	{
		$order = $this->input->post('order');

		// echo json_encode($order);
		// exit;

		$this->db->insert('order', $order);

		$new_order_id = $this->db->insert_id();

		$new_order = $this->pesanan_model->get_order($new_order_id);
		$new_order['required_date'] = date('d-m-Y', strtotime($new_order['required_date']));

		echo json_encode($new_order);
	}

	public function list_all_orders()
	{
		$orders = [
			'data' => []
		];

		foreach ($this->pesanan_model->get_all_orders() as $order) {

			$order['quantity'] = [
				'display' => moneyStrDot($order['quantity']),
				'raw'    => $order['quantity']
			];

			$order['thumbnail'] = !empty($order['image']) ? base_url('assets/img/artwork/') . $order['image'] : base_url('assets/icon/') . $order['item_icon'];

			$order['order_deadline'] = [
				'display' => date('d/m/Y', strtotime($order['order_deadline'])),
				'raw'     => strtotime($order['order_deadline']),
				'input'   => date('Y-m-d', strtotime($order['order_deadline']))
			];

			array_push($orders['data'], $order);
		};

		header('Content-Type: application/json');
		echo json_encode($orders);
	}

	public function list_order_price()
	{
		$orders = [
			'data' => []
		];

		foreach ($this->pesanan_model->get_order_price_reference() as $order) {

			$order['quantity'] = [
				'display' => moneyStrDot($order['quantity']),
				'raw'    => $order['quantity']
			];

			$order['price'] = [
				'display' => moneyStrDot($order['price']),
				'raw'    => $order['price']
			];

			$order['thumbnail'] = isset($order['image']) ? base_url('assets/img/artwork/') . $order['image'] : base_url('assets/icon/') . $order['item_icon'];

			array_push($orders['data'], $order);
		};

		header('Content-Type: application/json');
		echo json_encode($orders);
	}

	public function list_order_for_invoice_pdf()
	{
		$invoice_id = $this->input->post('invoice_id');

		$orders = $this->db->query("SELECT 
								description,
								quantity,
								price,
								(quantity*price) AS value
							FROM
								`order`
							WHERE 
								invoice_id = {$invoice_id}
		")->result_array();

		$row_set = [];
		$row_set[] = [
			[
				'text' => 'Pesanan',
				'style' => ['itemsHeader', 'center']
			],
			[
				'text' => 'Jml',
				'style' => ['itemsHeader', 'right']
			],
			[
				'text' => 'Harga',
				'style' => ['itemsHeader', 'right']
			],
			[
				'text' => 'Nilai',
				'style' => ['itemsHeader', 'right']
			]
		];

		foreach ($orders as $key => $order) {
			$row = [];
			foreach ($order as $key => $value) {
				if ($key == 'description') {
					$row[] = [
						'text' => moneyStrDot($value),
						'style'	=> 'itemTitle'
					];
				} else {
					$row[] = [
						'text' => moneyStrDot($value),
						'style'	=> 'itemNumber'
					];
				}
			}
			array_push($row_set, $row);
		}

		// pretty_print($row_set);

		header('Content-Type: application/json');
		echo json_encode($row_set);
	}
}
