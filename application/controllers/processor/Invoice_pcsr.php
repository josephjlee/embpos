<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Invoice_pcsr extends CI_Controller
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

	public function simpan()
	{
		// Grab invoice data from form submission
		$invoice 	= $this->input->post('invoice');
		$products = $this->input->post('products');
		$orders 	= $this->input->post('orders');
		$payment 	= $this->input->post('payment');

		// Save invoice data into invoice table
		$this->invoice_model->simpan($invoice);

		// Get the id of the newly inserted invoice for recording the product sale
		$new_invoice_id = $this->db->insert_id();

		// Get the customer_id for recording the product sale
		$customer_id = $invoice['customer_id'];

		// Save product sales when product added
		if ($products) {
			$this->penjualan_model->tambah($products, $new_invoice_id, $customer_id);
			$this->produk_model->update_stock_on_purchase($products);
		}

		// Update order by assigning $new_invoice_id when orders added
		if ($orders) {
			$this->pesanan_model->assign_invoice_id($orders, $new_invoice_id);
		}

		// If empty, Redirect to its detil page using the newly created invoice_number
		if (!$payment['amount']) {

			$invoice_number = $this->invoice_model->get_invoice_number($new_invoice_id);
			redirect(base_url('invoice/sunting/') . $invoice_number);
		}

		// Complete payment data using $invoice data
		$payment['invoice_id'] 		= $new_invoice_id;
		$payment['customer_id'] 	= $customer_id;
		$payment['payment_date'] 	= $invoice['invoice_date'];

		// Save $payment data
		$this->pembayaran_model->simpan($payment);

		// Redirect to its detil page using the newly created invoice_number
		$invoice_number = $this->invoice_model->get_invoice_number($new_invoice_id);
		redirect(base_url('invoice/sunting/') . $invoice_number);
	}

	public function perbarui()
	{

		$invoice 	= $this->input->post('invoice');
		$orders 	= $this->input->post('orders');
		$products = $this->input->post('products');
		$payment 	= $this->input->post('payment');

		// Create callback functions for processing the data
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
		$this->invoice_model->simpan($invoice);

		// Record order when order is added
		if ($orders) {

			// Grab new orders only
			$new_order = array_filter($orders, "new_order");

			// Assign invoice_id to the new orders
			if ($new_order) {

				$this->pesanan_model->assign_invoice_id($new_order, $invoice['invoice_id']);
			}
		}

		// Record product_sale update when product is change
		if ($products) {

			$existing_products = array_filter($products, "existing_product");
			$new_products 		 = array_filter($products, "new_product");

			// When existing products submitted, run update
			if ($existing_products) {
				$this->penjualan_model->perbarui($existing_products, $invoice['invoice_id'], $invoice['customer_id']);
			}

			// When new products submitted, run insert
			if ($new_products) {
				$this->penjualan_model->tambah($new_products, $invoice['invoice_id'], $invoice['customer_id']);
			}
		}

		// If empty, Redirect to current editor page 
		if (!$payment['amount']) {
			redirect(base_url('invoice/sunting/') . $invoice['number']);
		}

		// Complete payment data using $invoice data
		$payment['invoice_id'] 		= $invoice['invoice_id'];
		$payment['customer_id'] 	= $invoice['customer_id'];

		// Save $payment data
		$this->pembayaran_model->simpan($payment);

		redirect(base_url('invoice/sunting/') . $invoice['number']);
	}

	public function hapus_invoice()
	{

		$invoice = $this->input->post('invoice');

		$this->invoice_model->hapus($invoice);

		redirect(base_url('invoice/semua'));
	}

	public function lepas_pesanan()
	{

		$invoice 	= $this->input->post('invoice');
		$order 		= $this->input->post('order');

		$this->pesanan_model->detach_order($order);

		// Redirect to current edit page to refresh the data
		redirect(base_url('invoice/sunting/') . $invoice['number']);
	}

	public function lepas_produk()
	{

		$invoice 			= $this->input->post('invoice');
		$product_sale = $this->input->post('product_sale');

		$this->penjualan_model->hapus($product_sale);

		// Redirect to current edit page to refresh the data
		redirect(base_url('invoice/sunting/') . $invoice['number']);
	}

	// Payment
	public function simpan_pembayaran()
	{

		$invoice = $this->input->post('invoice');

		$payment = $this->input->post('payment');
		$payment['invoice_id'] 	= $invoice['invoice_id'];
		$payment['customer_id'] = $invoice['customer_id'];

		// Insert the payment data using simpan method of pembayaran_model
		$this->pembayaran_model->simpan($payment);

		// Redirect to current detil page to refresh the data
		redirect(base_url('invoice/sunting/') . $invoice['number']);
	}

	public function perbarui_pembayaran()
	{

		$invoice = $this->input->post('invoice');
		$payment = $this->input->post('payment');

		// Update the payment data using simpan method of pembayaran_model
		$this->pembayaran_model->simpan($payment);

		// Redirect to current detil page to refresh the data
		redirect(base_url('invoice/sunting/') . $invoice['number']);
	}

	public function hapus_pembayaran()
	{

		$invoice = $this->input->post('invoice');
		$payment = $this->input->post('payment');

		$this->pembayaran_model->hapus($payment);

		redirect(base_url('invoice/sunting/') . $invoice['number']);
	}
}
