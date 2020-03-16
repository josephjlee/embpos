<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Invoice_action extends CI_Controller
{

	public function __construct()
	{
		parent::__construct();

		$this->load->model('invoice_model');
		$this->load->model('pelanggan_model', 'pelanggan');
		$this->load->model('pesanan_model');
		$this->load->model('penjualan_model');
		$this->load->model('produk_model');
		$this->load->model('pembayaran_model');
	}

	/*
	| -------------------------------------------------------------------
	| PROCESSOR-TYPE METHOD
	| -------------------------------------------------------------------
	| These method have no front-end, their job is to process 
	| form submission. Ex: delete request, input request, etc...
	|     
	*/

	public function tambah()
	{
		// Grab invoice data from form submission
		$invoice 	= $this->input->post('invoice');
		$products = $this->input->post('products');
		$orders 	= $this->input->post('orders');
		$payment 	= $this->input->post('payment');

		// Save invoice data into invoice table
		$this->db->insert('invoice', $invoice);

		// Get the id of the newly inserted invoice for recording the product sale
		$new_invoice_id = $this->db->insert_id();

		// Get the customer_id for recording the product sale
		$customer_id = $invoice['customer_id'];

		// Save product sales when product added
		if ($products) {
			$this->penjualan_model->tambah($products, $new_invoice_id);
			$this->produk_model->update_stock_on_purchase($products);
		}

		// Update order by assigning $new_invoice_id when orders added
		if ($orders) {
			$this->pesanan_model->assign_invoice_id($orders, $new_invoice_id);
		}

		// If empty, Redirect to its detil page using the newly created invoice_number
		if (!$payment['amount']) {

			$invoice_number = $this->invoice_model->get_invoice_number($new_invoice_id);
			redirect(base_url('keuangan/sunting_invoice/') . $invoice_number);
		}

		// Complete payment data using $invoice data
		$payment['invoice_id'] 		= $new_invoice_id;
		$payment['customer_id'] 	= $customer_id;
		$payment['payment_date'] 	= $invoice['invoice_date'];

		// Save $payment data
		$this->db->insert('payment', $payment);

		// Redirect to its detil page using the newly created invoice_number
		$invoice_number = $this->invoice_model->get_invoice_number($new_invoice_id);
		redirect(base_url('keuangan/sunting_invoice/') . $invoice_number);
	}

	public function perbarui()
	{
		$invoice 	= $this->input->post('invoice');
		$orders 	= $this->input->post('orders');
		$products = $this->input->post('products');
		$payment 	= $this->input->post('payment');

		// Create callback functions for processing the data
		function existing_order($order)
		{
			// Return order that already have order_id
			return !empty($order['order_id']);
		}

		function new_order($order)
		{
			// Return orders that don't have invoice_id yet
			return !array_key_exists('invoice_id', $order);
		}

		function existing_product($products)
		{
			// Return product that already have product_sale_id
			return !empty($products['product_sale_id']);
		}

		function new_product($products)
		{
			// Return product that don't have product_sale_id yet
			return empty($products['product_sale_id']);
		}

		// Save invoice update
		$this->db->update('invoice', $invoice, ['invoice_id' => $invoice['invoice_id']]);

		// Record order entry based on order status (existing or new)
		if ($orders) {

			$existing_orders = array_filter($orders, "existing_order");
			$new_order = array_filter($orders, "new_order");

			// For the existing_orders, update its detail
			if ($existing_orders) {
				$this->pesanan_model->perbarui_banyak($existing_orders);
			}

			// For the new orders, assign invoice_id
			if ($new_order) {
				$this->pesanan_model->assign_invoice_id($new_order, $invoice['invoice_id']);
			}
		}

		// Record product_sale update when product is change
		if ($products) {

			$existing_products = array_filter($products, "existing_product");
			$new_products 	= array_filter($products, "new_product");

			// When existing products submitted, run update
			if ($existing_products) {
				$this->produk_model->update_stock_on_update($existing_products);
				$this->penjualan_model->perbarui($existing_products, $invoice['invoice_id']);
			}

			// When new products submitted, run insert
			if ($new_products) {
				$this->penjualan_model->tambah($new_products, $invoice['invoice_id']);
				$this->produk_model->update_stock_on_purchase($new_products);
			}
		}

		// If empty, Redirect to current editor page 
		if (!$payment['amount']) {
			redirect(base_url('keuangan/sunting_invoice/') . $invoice['number']);
		}

		// Complete payment data using $invoice data
		$payment['invoice_id'] 		= $invoice['invoice_id'];
		$payment['customer_id'] 	= $invoice['customer_id'];

		// Save $payment data
		$payment['amount'] = str_replace(',', '', $payment['amount']);
		$this->db->insert('payment', $payment);

		redirect(base_url('keuangan/sunting_invoice/') . $invoice['number']);
	}

	public function hapus_invoice()
	{

		$invoice = $this->input->post('invoice');

		$this->db->delete('invoice', ['invoice_id' => $invoice['invoice_id']]);

		redirect(base_url('invoice/semua'));
	}

	public function lepas_pesanan()
	{

		$invoice 	= $this->input->post('invoice');
		$order 		= $this->input->post('order');

		$this->pesanan_model->detach_order($order);

		// Redirect to current edit page to refresh the data
		redirect(base_url('keuangan/sunting_invoice/') . $invoice['number']);
	}

	public function lepas_produk()
	{

		$invoice 			= $this->input->post('invoice');
		$product_sale = $this->input->post('product_sale');

		$this->db->delete('product_sale', ['product_sale_id' => $product_sale['product_sale_id']]);

		// Redirect to current edit page to refresh the data
		redirect(base_url('keuangan/sunting_invoice/') . $invoice['number']);
	}

	// Payment
	public function simpan_pembayaran()
	{

		$invoice = $this->input->post('invoice');

		$payment = $this->input->post('payment');
		$payment['invoice_id'] 	= $invoice['invoice_id'];
		$payment['customer_id'] = $invoice['customer_id'];

		$payment['amount'] = str_replace(',', '', $payment['amount']);

		// Insert the payment data using simpan method of pembayaran_model
		$this->db->insert('payment', $payment);

		// Redirect to current detil page to refresh the data
		redirect(base_url('keuangan/sunting_invoice/') . $invoice['number']);
	}

	public function perbarui_pembayaran()
	{

		$invoice = $this->input->post('invoice');
		$payment = $this->input->post('payment');

		$payment['amount'] = str_replace(',', '', $payment['amount']);

		// Update the payment data using simpan method of pembayaran_model
		$this->db->update('payment', $payment, ['payment_id' => $payment['payment_id']]);

		// Redirect to current detil page to refresh the data
		redirect(base_url('keuangan/sunting_invoice/') . $invoice['number']);
	}

	public function hapus_pembayaran()
	{

		$invoice = $this->input->post('invoice');
		$payment = $this->input->post('payment');

		$this->db->delete('payment', ['payment_id' => $payment['payment_id']]);

		redirect(base_url('keuangan/sunting_invoice/') . $invoice['number']);
	}
}
