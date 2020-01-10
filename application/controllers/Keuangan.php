<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Keuangan extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();

        $this->load->model('keuangan_model');
        $this->load->model('kreditur_model');
        $this->load->model('vendor_model');
        $this->load->model('invoice_model');
        $this->load->model('pelanggan_model');
        $this->load->model('pesanan_model');
        $this->load->model('penjualan_model');
        $this->load->model('produk_model');
        $this->load->model('pembayaran_model');
        $this->load->model('produksi_model');
    }

    public function invoice()
    {

        $data['title'] = 'Invoice';

        $data['invoices'] = $this->invoice_model->list_all_invoices();

        $data['content'] = $this->load->view('dashboard/finance/invoice-index', $data, TRUE);

        $data['view_script'] = 'index--invoice.js';

        $this->load->view('layout/dashboard', $data);
    }

    public function buat_invoice()
    {

        $data['title'] = 'Buat Invoice';

        $data['invoice_number'] = $this->new_invoice_number();

        $data['content'] = $this->load->view('dashboard/finance/invoice-editor', $data, TRUE);

        $data['view_script'] = 'editor--invoice.js';

        $this->load->view('layout/dashboard', $data);
    }

    public function sunting_invoice($invoice)
    {
        $data['invoice_detail'] = $this->invoice_model->generate_invoice_data($invoice);

        $data['invoice_detail']['order_status'] = $this->pesanan_model->check_order_progress($data['invoice_detail']['invoice_id']);

        $data['title'] = 'Sunting INV-' . $data['invoice_detail']['invoice_number'];

        $data['current_orders']  = $this->pesanan_model->get_order_by_invoice_id($data['invoice_detail']['invoice_id']);

        $data['uninvoiced_orders'] = $this->pesanan_model->get_order_by_customer_id($data['invoice_detail']['customer_id']);

        $data['current_products']  = $this->penjualan_model->get_product_sale_by_invoice_id($data['invoice_detail']['invoice_id']);

        $data['payment_records'] = $this->pembayaran_model->get_payment_by_invoice_id($data['invoice_detail']['invoice_id']);

        $data['content'] = $this->load->view('dashboard/finance/invoice-editor', $data, TRUE);

        $data['view_script'] = 'editor--invoice.js';

        $this->load->view('layout/dashboard', $data);
    }

    public function lihat_invoice($invoice)
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

        $data['content'] = $this->load->view('dashboard/finance/invoice-fullscreen', $data, TRUE);

        $this->load->view('layout/dashboard', $data);
    }


    public function pengeluaran()
    {
        $data['title'] = 'Pengeluaran';

        $data['debts'] = $this->keuangan_model->list_all_debts();

        $data['content'] = $this->load->view('dashboard/finance/expense-index', $data, TRUE);

        $data['view_script'] = 'index--expense.js';

        $this->load->view('layout/dashboard', $data);
    }

    public function hutang()
    {

        $data['title'] = 'Hutang';

        $data['debts'] = $this->keuangan_model->list_all_debts();

        $data['content'] = $this->load->view('dashboard/finance/debt-index', $data, TRUE);

        $data['view_script'] = 'index--debt.js';

        $this->load->view('layout/dashboard', $data);
    }

    public function detail_hutang($debt_id)
    {
        $data['title'] = "Detail Hutang HTG-{$debt_id}";

        $data['debt_detail'] = $this->keuangan_model->get_debt_by_debt_id($debt_id);

        $data['content'] = $this->load->view('dashboard/finance/debt-editor', $data, TRUE);

        // $data['view_script'] = 'editor--debt.js';

        $this->load->view('layout/dashboard', $data);
    }

    public function pembayaran_invoice()
    {

        $data['title']    = 'Pembayaran Invoice';
        $data['payments'] = $this->pembayaran_model->get_payment_history();
        $data['content']  = $this->load->view('dashboard/finance/payment-index', $data, TRUE);

        $data['view_script'] = 'index--payment.js';

        $this->load->view('layout/dashboard', $data);
    }

    public function pembayaran_hutang()
    {
        $data['title']    = 'Pembayaran Hutang';

        $data['content']  = $this->load->view('dashboard/finance/debt-payment-index', $data, TRUE);

        $data['view_script'] = 'index--debt-payment.js';

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
