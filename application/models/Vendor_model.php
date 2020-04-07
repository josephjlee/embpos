<?php

class Vendor_model extends CI_Model
{

	public function list_all_vendors()
	{
		$query = $this->db->query("SELECT 
							vendor.vendor_id,
							vendor.name,
							vendor.phone,
							vendor.email,
							vendor.address,
							vendor.selling,
							(
								SELECT IFNULL(SUM(expense.amount),0)
								FROM expense
								WHERE	expense.vendor_id = vendor.vendor_id
							) AS value
							FROM vendor");
		return $query->result_array();
	}

	public function get_vendor_by_id($vendor_id)
	{
		return $this->db->get_where('vendor', ['vendor_id' => $vendor_id])->row_array();
	}

	public function get_total_vendor()
	{
		$query = $this->db->query("SELECT COUNT(vendor_id) AS total_vendor FROM vendor");
		$result = $query->row_array();

		return $result['total_vendor'];
	}

	public function get_most_order_by_month($month)
	{
		$query = $this->db->query("SELECT 
									vendor.name,
									SUM(`order`.quantity) AS total_order
							FROM
									embryo.`order`
							JOIN
								vendor ON `order`.vendor_id = vendor.vendor_id
							WHERE MONTH(`order`.received_date) = {$month}
							GROUP BY vendor.name
							ORDER BY total_order DESC
							LIMIT 1
		");

		$result = $query->row_array();

		return $result['name'];
	}

	public function get_most_buy_by_month($month)
	{
		$query = $this->db->query("SELECT 
									vendor.name, SUM(product_sale.quantity) AS total_buy
							FROM
									invoice
											JOIN
									product_sale ON invoice.invoice_id = product_sale.invoice_id
											JOIN
									vendor ON product_sale.vendor_id = vendor.vendor_id
							WHERE
									MONTH(invoice.invoice_date) = {$month}
							GROUP BY vendor.name
							ORDER BY total_buy DESC
							LIMIT 1
		");

		$result = $query->row_array();

		return $result['name'];
	}

	public function get_order_value_per_vendor_by_month($month)
	{
		$query = $this->db->query("SELECT 
									`vendor`.`name` AS `vendor_name`,
									IFNULL(SUM(`order`.quantity * `order`.price),0) AS order_sale
							FROM
									`invoice`
											LEFT JOIN
									`vendor` ON `invoice`.`vendor_id` = `vendor`.`vendor_id`
											LEFT JOIN
									`order` ON invoice.invoice_id = `order`.invoice_id
							WHERE
									MONTH(invoice.invoice_date) = {$month}
							GROUP BY vendor.name");

		return $query->result_array();
	}

	public function get_product_sale_value_per_vendor_by_month($month)
	{
		$query = $this->db->query("SELECT 
									`vendor`.`name` AS `vendor_name`,
									IFNULL(SUM(product_sale.quantity * product_sale.price),0) AS product_sale
							FROM
									`invoice`
											LEFT JOIN
									`vendor` ON `invoice`.`vendor_id` = `vendor`.`vendor_id`
											LEFT JOIN
									product_sale ON invoice.invoice_id = product_sale.invoice_id
							WHERE
									MONTH(invoice.invoice_date) = {$month}
							GROUP BY vendor.name");

		return $query->result_array();
	}

	public function get_most_valueable_by_month($month)
	{
		$order_sale = $this->get_order_value_per_vendor_by_month($month);
		$product_sale = $this->get_product_sale_value_per_vendor_by_month($month);

		function calculate_total_sale($order_sale, $product_sale)
		{
			$total[$order_sale['vendor_name']] = $order_sale['order_sale'] + $product_sale['product_sale'];

			return $total;
		}

		$raw = array_map("calculate_total_sale", $order_sale, $product_sale);

		if (empty($raw)) {
			return '-';
		}

		$ready = [];
		foreach ($raw as $r) {
			foreach ($r as $key => $value) {
				$ready[$key] = $value;
			}
		}

		arsort($ready);

		$key = array_keys($ready);

		return $key[0];
	}
}
