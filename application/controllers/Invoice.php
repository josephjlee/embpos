<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Invoice extends CI_Controller
{

	public function __construct()
	{
		parent::__construct();

		$this->load->model('invoice_model');
		$this->load->model('pelanggan_model');
		$this->load->model('pesanan_model');
		$this->load->model('penjualan_model');
		$this->load->model('produk_model');
		$this->load->model('pembayaran_model');
	}


	/*
	| -------------------------------------------------------------------
	| RENDER-TYPE METHOD
	| -------------------------------------------------------------------
	| Each of these method correspond to a single page on the front-end.
	| Example: $pesanan->buat() = Halaman 'Buat Pesanan Baru'
	|
	*/

	public function buat()
	{

		$data['title'] = 'Buat Invoice';

		$data['invoice_number'] = $this->new_invoice_number();

		$data['content'] = $this->load->view('dashboard/invoice/invoice-editor', $data, TRUE);

		$data['view_script'] = 'editor--invoice.js';

		$this->load->view('layout/dashboard', $data);
	}

	public function sunting($invoice)
	{
		$data['invoice_detail'] = $this->invoice_model->generate_invoice_data($invoice);

		$data['invoice_detail']['order_status'] = $this->pesanan_model->check_order_progress($data['invoice_detail']['invoice_id']);

		$data['title'] = 'Sunting INV-' . $data['invoice_detail']['invoice_number'];

		$data['current_orders']  = $this->pesanan_model->get_order_by_invoice_id($data['invoice_detail']['invoice_id']);

		$data['uninvoiced_orders'] = $this->pesanan_model->get_order_by_customer_id($data['invoice_detail']['customer_id']);

		$data['current_products']  = $this->penjualan_model->get_product_sale_by_invoice_id($data['invoice_detail']['invoice_id']);

		$data['payment_records'] = $this->pembayaran_model->get_payment_by_invoice_id($data['invoice_detail']['invoice_id']);

		$data['content'] = $this->load->view('dashboard/invoice/invoice-editor', $data, TRUE);

		$data['view_script'] = 'editor--invoice.js';

		$this->load->view('layout/dashboard', $data);
	}

	public function semua()
	{

		$data['title'] = 'Semua Invoice';

		// $data['receivable'] = $this->invoice_model->get_receivable_val();

		// pretty_print($this->invoice_model->get_receivable_val());

		$data['invoices'] = $this->invoice_model->list_all_invoices();

		$data['content'] = $this->load->view('dashboard/invoice/invoice-index', $data, TRUE);

		$data['view_script'] = 'index--invoice.js';

		$this->load->view('layout/dashboard', $data);
	}

	public function tampil($invoice)
	{

		$data['invoice_detail'] = $this->invoice_model->generate_invoice_data($invoice);

		$data['customer'] = $this->pelanggan_model->get_customer_by_id($data['invoice_detail']['customer_id']);

		// Prepare data for page title
		$customer = $data['customer']['customer_name'];
		$date = date('dmy');

		// Generate the tile using data above
		$data['title'] = "INV_{$invoice}_{$customer}_{$date}";

		$data['orders']  = $this->pesanan_model->get_order_by_invoice_id($data['invoice_detail']['invoice_id']);

		$data['order_details']  = $this->pesanan_model->get_order_by_invoice_id($data['invoice_detail']['invoice_id']);

		$data['product_details']  = $this->penjualan_model->get_product_sale_by_invoice_id($data['invoice_detail']['invoice_id']);

		$data['content'] = $this->load->view('dashboard/invoice/invoice-fullscreen', $data, TRUE);

		$this->load->view('layout/dashboard', $data);
	}

	public function pembayaran()
	{

		$data['title']    = 'Riwayat Pembayaran';
		$data['payments'] = $this->pembayaran_model->get_payment_history();
		$data['content']  = $this->load->view('dashboard/invoice/payment-index', $data, TRUE);

		$data['view_script'] = 'index--payment.js';

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

	public function new_invoice_number()
	{
		$this->db->select('MAX(number) AS invoice_number');
		$last_invoice_number = $this->db->get('invoice');

		$new_order_num = $last_invoice_number->row_array()['invoice_number'] + 1;

		return $new_order_num;
	}
}
