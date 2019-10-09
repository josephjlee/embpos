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

		$this->db->select('customer.customer_id, customer.name AS customer_name');
		$customer_query = $this->db->get('customer');

		return $customer_query->result_array();
	}

	public function get_customer_by_id($customer_id)
	{

		$this->db->select('
            customer_id,
            company AS customer_company,
            name AS customer_name,
            address AS customer_address,
            phone AS customer_phone            
        ');
		$this->db->from('customer');
		$this->db->where('customer_id', $customer_id);

		return $this->db->get()->row_array();
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
