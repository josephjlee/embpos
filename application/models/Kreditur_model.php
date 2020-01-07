<?php

class Kreditur_model extends CI_Model
{

	public function simpan($creditor)
	{

		if (!empty($creditor['creditor_id'])) {
			return $this->perbarui($creditor);
		}

		return $this->tambah($creditor);
	}

	public function tambah($creditor)
	{

		$creditorData = $this->siapkan_data($creditor);

		return $this->db->insert('creditor', $creditorData);
	}

	public function perbarui($creditor)
	{

		$creditorData = $this->siapkan_data($creditor);

		$this->db->where('creditor_id', $creditor['creditor_id']);
		return $this->db->update('creditor', $creditorData);
	}

	public function hapus($creditor)
	{

		$this->db->where('creditor_id', $creditor['creditor_id']);
		return $this->db->delete('creditor');
	}

	public function list_all_creditors()
	{
		$query = $this->db->query("SELECT 
								creditor.creditor_id,
									creditor.name,
									creditor.address,
									creditor.phone,
									creditor.email,
									(
									SELECT IFNULL(SUM(debt.amount),0) FROM debt WHERE debt.creditor_id = creditor.creditor_id
									) AS receivable,
									(
									SELECT IFNULL(SUM(debt_payment.amount),0) FROM debt_payment WHERE debt_payment.creditor_id = creditor.creditor_id
									) AS paid,
									(
									(
										SELECT IFNULL(SUM(debt.amount),0) FROM debt WHERE debt.creditor_id = creditor.creditor_id) - (SELECT IFNULL(SUM(debt_payment.amount),0) FROM debt_payment WHERE debt_payment.creditor_id = creditor.creditor_id)
									) AS due
							FROM
									embryo.creditor");

		return $query->result_array();
	}

	public function get_creditor_by_id($creditor_id)
	{
		return $this->db->get_where('creditor', ['creditor_id' => $creditor_id])->row_array();
	}

	public function get_total_creditor()
	{
		$query = $this->db->query("SELECT COUNT(creditor_id) AS total_creditor FROM creditor");
		$result = $query->row_array();

		return $result['total_creditor'];
	}

	public function get_most_order_by_month($month)
	{
		$query = $this->db->query("SELECT 
									creditor.name,
									SUM(`order`.quantity) AS total_order
							FROM
									embryo.`order`
							JOIN
								creditor ON `order`.creditor_id = creditor.creditor_id
							WHERE MONTH(`order`.received_date) = {$month}
							GROUP BY creditor.name
							ORDER BY total_order DESC
							LIMIT 1
		");

		$result = $query->row_array();

		return $result['name'];
	}

	public function get_most_buy_by_month($month)
	{
		$query = $this->db->query("SELECT 
									creditor.name, SUM(product_sale.quantity) AS total_buy
							FROM
									invoice
											JOIN
									product_sale ON invoice.invoice_id = product_sale.invoice_id
											JOIN
									creditor ON product_sale.creditor_id = creditor.creditor_id
							WHERE
									MONTH(invoice.invoice_date) = {$month}
							GROUP BY creditor.name
							ORDER BY total_buy DESC
							LIMIT 1
		");

		$result = $query->row_array();

		return $result['name'];
	}

	public function get_order_value_per_creditor_by_month($month)
	{
		$query = $this->db->query("SELECT 
									`creditor`.`name` AS `creditor_name`,
									IFNULL(SUM(`order`.quantity * `order`.price),0) AS order_sale
							FROM
									`invoice`
											LEFT JOIN
									`creditor` ON `invoice`.`creditor_id` = `creditor`.`creditor_id`
											LEFT JOIN
									`order` ON invoice.invoice_id = `order`.invoice_id
							WHERE
									MONTH(invoice.invoice_date) = {$month}
							GROUP BY creditor.name");

		return $query->result_array();
	}

	public function get_product_sale_value_per_creditor_by_month($month)
	{
		$query = $this->db->query("SELECT 
									`creditor`.`name` AS `creditor_name`,
									IFNULL(SUM(product_sale.quantity * product_sale.price),0) AS product_sale
							FROM
									`invoice`
											LEFT JOIN
									`creditor` ON `invoice`.`creditor_id` = `creditor`.`creditor_id`
											LEFT JOIN
									product_sale ON invoice.invoice_id = product_sale.invoice_id
							WHERE
									MONTH(invoice.invoice_date) = {$month}
							GROUP BY creditor.name");

		return $query->result_array();
	}

	public function get_most_valueable_by_month($month)
	{
		$order_sale = $this->get_order_value_per_creditor_by_month($month);
		$product_sale = $this->get_product_sale_value_per_creditor_by_month($month);

		function calculate_total_sale($order_sale, $product_sale)
		{
			$total[$order_sale['creditor_name']] = $order_sale['order_sale'] + $product_sale['product_sale'];

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

	public function siapkan_data($creditor)
	{
		$creditor_db_data = [];

		foreach ($creditor as $col => $val) {
			$creditor_db_data[$col] = $val;
		}

		return $creditor_db_data;
	}
}
