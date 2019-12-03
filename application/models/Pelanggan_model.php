<?php

class Pelanggan_model extends CI_Model
{

	public function simpan($customer)
	{

		if (!empty($customer['customer_id'])) {
			return $this->perbarui($customer);
		}

		return $this->tambah($customer);
	}

	public function tambah($customer)
	{

		$customerData = $this->siapkan_data($customer);

		return $this->db->insert('customer', $customerData);
	}

	public function perbarui($customer)
	{

		$customerData = $this->siapkan_data($customer);

		$this->db->where('customer_id', $customer['customer_id']);
		return $this->db->update('customer', $customerData);
	}

	public function hapus($customer)
	{

		$this->db->where('customer_id', $customer['customer_id']);
		return $this->db->delete('customer');
	}

	public function select_all_customers_data()
	{
		$this->db->select('
				customer.customer_id, 
				customer.name AS customer_name, 
				customer.company AS customer_company, 
				customer.address AS customer_address, 
				customer.phone AS customer_phone, 
				customer.email AS cust_email,
				(
						SELECT IFNULL( SUM(quantity),0 ) FROM product_sale WHERE customer.customer_id = product_sale.customer_id
				) AS buy_qty,
				(
					SELECT IFNULL( SUM(quantity),0 ) FROM `order` WHERE customer.customer_id = `order`.customer_id
				) AS order_qty,
				(
					(
						SELECT
							IFNULL(SUM(quantity * price), 0)
						FROM
							product_sale
						WHERE
							customer.customer_id = product_sale.customer_id
					) + (
						SELECT
							IFNULL(SUM(quantity * price), 0)
						FROM
							`order`
						WHERE
							customer.customer_id = `order`.customer_id
					)
				) AS total_value
		');
		$this->db->from('customer');

		return $this->db->get_compiled_select();
	}

	public function list_all_customers()
	{

		$all_customer_data_query   = $this->db->query($this->select_all_customers_data());
		return $all_customer_data_query->result_array();
	}

	public function get_all_customers()
	{

		$this->db->select("
			customer_id, 
			IF(
				company != '', 
					CONCAT(name, ' (', UPPER(company), ')'),
					CONCAT(name, ' ', UPPER(address))
			) AS customer_name
		");
		$customer_query = $this->db->get('customer');

		return $customer_query->result_array();
	}

	public function get_customer_by_id($customer_id)
	{

		$this->db->select('
            customer_id,
            UPPER(company) AS customer_company,
            name AS customer_name,
            address AS customer_address,
            phone AS customer_phone            
        ');
		$this->db->from('customer');
		$this->db->where('customer_id', $customer_id);

		return $this->db->get()->row_array();
	}

	public function get_total_customer()
	{
		$query = $this->db->query("SELECT COUNT(customer_id) AS total_customer FROM customer");
		$result = $query->row_array();

		return $result['total_customer'];
	}

	public function get_most_order_by_month($month)
	{
		$query = $this->db->query("SELECT 
									customer.name,
									SUM(`order`.quantity) AS total_order
							FROM
									embryo.`order`
							JOIN
								customer ON `order`.customer_id = customer.customer_id
							WHERE MONTH(`order`.received_date) = {$month}
							GROUP BY customer.name
							ORDER BY total_order DESC
							LIMIT 1
		");

		$result = $query->row_array();

		return $result['name'];
	}

	public function get_most_buy_by_month($month)
	{
		$query = $this->db->query("SELECT 
									customer.name, SUM(product_sale.quantity) AS total_buy
							FROM
									invoice
											JOIN
									product_sale ON invoice.invoice_id = product_sale.invoice_id
											JOIN
									customer ON product_sale.customer_id = customer.customer_id
							WHERE
									MONTH(invoice.invoice_date) = {$month}
							GROUP BY customer.name
							ORDER BY total_buy DESC
							LIMIT 1
		");

		$result = $query->row_array();

		return $result['name'];
	}

	public function get_order_value_per_customer_by_month($month)
	{
		$query = $this->db->query("SELECT 
									`customer`.`name` AS `customer_name`,
									IFNULL(SUM(`order`.quantity * `order`.price),0) AS order_sale
							FROM
									`invoice`
											LEFT JOIN
									`customer` ON `invoice`.`customer_id` = `customer`.`customer_id`
											LEFT JOIN
									`order` ON invoice.invoice_id = `order`.invoice_id
							WHERE
									MONTH(invoice.invoice_date) = {$month}
							GROUP BY customer.name");

		return $query->result_array();
	}

	public function get_product_sale_value_per_customer_by_month($month)
	{
		$query = $this->db->query("SELECT 
									`customer`.`name` AS `customer_name`,
									IFNULL(SUM(product_sale.quantity * product_sale.price),0) AS product_sale
							FROM
									`invoice`
											LEFT JOIN
									`customer` ON `invoice`.`customer_id` = `customer`.`customer_id`
											LEFT JOIN
									product_sale ON invoice.invoice_id = product_sale.invoice_id
							WHERE
									MONTH(invoice.invoice_date) = {$month}
							GROUP BY customer.name");

		return $query->result_array();
	}

	public function get_most_valueable_by_month($month)
	{
		$order_sale = $this->get_order_value_per_customer_by_month($month);
		$product_sale = $this->get_product_sale_value_per_customer_by_month($month);

		function calculate_total_sale($order_sale, $product_sale)
		{
			$total[$order_sale['customer_name']] = $order_sale['order_sale'] + $product_sale['product_sale'];

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

	public function siapkan_data($customer)
	{
		$customer_db_data = [];

		foreach ($customer as $col => $val) {
			$customer_db_data[$col] = $val;
		}

		return $customer_db_data;
	}
}
