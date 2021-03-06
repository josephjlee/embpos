<?php

class Invoice_model extends CI_Model
{
    public function get_order_sub_total($invoice_id)
    {

        $this->db->select('SUM(quantity * price) AS sub_total');
        $this->db->from('order');
        $this->db->where('invoice_id', $invoice_id);

        $sub_total_query = $this->db->get();
        $sub_total = $sub_total_query->row_array();

        return $sub_total['sub_total'];
    }

    public function get_product_sub_total($invoice_id)
    {

        $this->db->select('SUM(product_sale.price*product_sale.quantity) AS sub_total');
        $this->db->from('invoice');
        $this->db->join('product_sale', 'invoice.invoice_id = product_sale.invoice_id');
        $this->db->where('invoice.invoice_id', $invoice_id);

        $sub_total_query = $this->db->get();
        $sub_total = $sub_total_query->row_array();

        return $sub_total['sub_total'];
    }

    public function get_sub_total($invoice_id)
    {
        return $this->get_product_sub_total($invoice_id) + $this->get_order_sub_total($invoice_id);
    }

    public function get_discount($invoice_id)
    {
        $this->db->select('discount');
        $this->db->from('invoice');
        $this->db->where('invoice_id', $invoice_id);

        $discount_query = $this->db->get();
        $discount = $discount_query->row_array();

        return $discount['discount'];
    }

    public function get_total_due($invoice_id)
    {
        // Query sub_total of particular invoice_id and assign to a variabel
        $sub_total = $this->get_sub_total($invoice_id);

        // Query discount of particular invoice_id and assign to a variabel
        $discount = $this->get_discount($invoice_id);

        // Calculate total_due by substracting $sub_total with $discount
        $total_due = $sub_total - $discount;
        return $total_due;
    }

    public function get_paid($invoice_id)
    {
        $this->db->select('IFNULL(SUM(amount), 0) AS paid');
        $this->db->from('payment');
        $this->db->where('invoice_id', $invoice_id);

        $payment_query = $this->db->get();
        $payment = $payment_query->row_array();

        return $payment['paid'];
    }

    public function get_payment_due($invoice_id)
    {
        $total_due = $this->get_total_due($invoice_id);
        $paid = $this->get_paid($invoice_id);

        $payment_due = $total_due - $paid;
        return $payment_due;
    }

    public function generate_invoice_calc($invoice_id)
    {
        $invoice_calc_var = [
            'discount'  => $this->get_discount($invoice_id),
            'paid'      => $this->get_paid($invoice_id),
            'sub_total' => $this->get_sub_total($invoice_id),
            'total_due' => $this->get_total_due($invoice_id),
            'invoice_due' => $this->get_payment_due($invoice_id)
        ];

        return $invoice_calc_var;
    }

    public function check_payment_status($invoice_id)
    {
        $invoice_calc_var = $this->generate_invoice_calc($invoice_id);
        $total_due = $invoice_calc_var['total_due'];
        $paid      = $invoice_calc_var['paid'];

        $payment_status = [
            'Kosong',
            'Sebagian',
            'Lunas'
        ];

        $status['payment_status'] = $payment_status[0];
        switch (true) {
            case $paid > 0 && $paid < $total_due:
                $status['payment_status'] = $payment_status[1];
                break;
            case $paid == $total_due:
                $status['payment_status'] = $payment_status[2];
                break;
        }

        return $status;
    }

    public function get_all_product_sales_amount()
    {
        $this->db->select('
          IFNULL( SUM(product_sale.quantity*product_sale.price),0 ) AS total_due
        ');

        $this->db->from('invoice');
        $this->db->join('product_sale', 'invoice.invoice_id = product_sale.invoice_id', 'left');
        $this->db->group_by('invoice.invoice_id');
        $this->db->order_by('invoice.number', 'DESC');

        return $this->db->get()->result_array();
    }

    public function get_all_orders_amount()
    {
        $this->db->select(' 
          IFNULL( SUM(order.quantity*order.price),0 ) AS total_due
        ');

        $this->db->from('invoice');
        $this->db->join('order', 'order.invoice_id = invoice.invoice_id', 'left');
        $this->db->group_by('invoice.invoice_id');
        $this->db->order_by('invoice.number', 'DESC');

        return $this->db->get()->result_array();
    }

    public function get_all_invoice_discount()
    {
        $this->db->select('discount');
        $this->db->from('invoice');
        $this->db->order_by('invoice.number', 'DESC');

        return $this->db->get()->result_array();
    }

    public function get_all_invoices_payment()
    {
        $this->db->select('IFNULL( SUM(payment.amount),0 ) AS payment');
        $this->db->from('invoice');
        $this->db->join('payment', 'invoice.invoice_id = payment.invoice_id', 'left');
        $this->db->group_by('invoice.invoice_id');
        $this->db->order_by('invoice.number', 'DESC');

        return $this->db->get()->result_array();
    }

    public function get_all_invoices_meta()
    {
        $this->db->select('
            invoice.invoice_id,
            invoice.number, 
            invoice.invoice_date, 
            invoice.payment_date, 
            customer.name AS customer, 
        ');

        $this->db->from('invoice');
        $this->db->join('customer', 'invoice.customer_id = customer.customer_id');
        $this->db->order_by('invoice.number', 'DESC');

        return $this->db->get()->result_array();
    }

    public function get_all_invoices_payment_due()
    {

        function calculate_payment_due($order_amount, $product_amount, $discount, $payment)
        {

            $invoice_entry['payment_due'] = $order_amount['total_due'] + $product_amount['total_due'] - $discount['discount'] - $payment['payment'];

            return $invoice_entry;
        }

        $order_amount = $this->get_all_orders_amount();
        $product_sale = $this->get_all_product_sales_amount();
        $discount     = $this->get_all_invoice_discount();
        $payment      = $this->get_all_invoices_payment();

        return array_map("calculate_payment_due", $order_amount, $product_sale, $discount, $payment);
    }

    public function list_all_invoices()
    {
        function merge_payment_due($payment_due_arr, $invoice_meta_arr)
        {
            $invoice_meta_arr['payment_due'] = $payment_due_arr['payment_due'];
            return $invoice_meta_arr;
        }

        return array_map("merge_payment_due", $this->get_all_invoices_payment_due(), $this->get_all_invoices_meta());
    }

    public function get_invoice_meta($invoice_number)
    {
        $this->db->select('
            invoice.customer_id,
            invoice.invoice_id,
            invoice.number AS invoice_number,
            invoice.invoice_date,
            invoice.payment_date,
            invoice.note,
        ');

        $this->db->from('invoice');
        $this->db->where('invoice.number', $invoice_number);

        $order_meta_query = $this->db->get();
        $order_meta = $order_meta_query->row_array();

        return $order_meta;
    }

    public function generate_invoice_data($invoice_number)
    {

        $invoice_meta = $this->get_invoice_meta($invoice_number);
        $invoice_calc = $this->generate_invoice_calc($invoice_meta['invoice_id']);
        $payment_status = $this->check_payment_status($invoice_meta['invoice_id']);

        $invoice_detail = array_merge($invoice_meta, $invoice_calc, $payment_status);
        return $invoice_detail;
    }

    public function get_invoice_number($invoice_id)
    {
        $this->db->select('number');
        $this->db->where('invoice_id', $invoice_id);
        $order_number = $this->db->get('invoice');

        return $order_number->row_array()['number'];
    }

    public function get_artwork_by_invoice_id($invoice_id)
    {

        $this->db->select('artwork.file_name, order.description');
        $this->db->from('order');
        $this->db->join('artwork', 'order.artwork_id = artwork.artwork_id');
        $this->db->join('invoice', 'order.invoice_id = invoice.invoice_id');
        $this->db->where('invoice.invoice_id', $invoice_id);

        return $this->db->get()->result_array();
    }

    public function get_total_invoice_by_month($month)
    {
        $query = $this->db->query("SELECT
            MONTH(invoice.invoice_date),
            SUM((
                SELECT 
                    IFNULL(SUM(`order`.quantity * `order`.price), 0)
                FROM
                    `order`
                WHERE
                    `order`.invoice_id = invoice.invoice_id
            )) AS total_order_sale,
            SUM((
                SELECT 
                    IFNULL(SUM(product_sale.quantity * product_sale.price),0)
                FROM
                    product_sale
                WHERE
                    product_sale.invoice_id = invoice.invoice_id
            )) AS total_product_sale,
            SUM(invoice.discount) AS total_discount
        FROM
            invoice
                JOIN
            customer ON invoice.customer_id = customer.customer_id
        WHERE MONTH(invoice.invoice_date) = {$month}
        GROUP BY MONTH(invoice.invoice_date)
        ");
        $result = $query->row_array();

        return $result['total_order_sale'] + $result['total_product_sale'] - $result['total_discount'];
    }

    public function get_total_paid_by_month($month)
    {
        $query = $this->db->query("SELECT
            MONTH(invoice.invoice_date),
            SUM(payment.amount) AS total_paid
        FROM
            invoice
                JOIN
            payment ON invoice.invoice_id = payment.invoice_id
        WHERE MONTH(invoice.invoice_date) = MONTH(CURDATE())
        GROUP BY MONTH(invoice.invoice_date)
        ");
        $result = $query->row_array();

        return $result['total_paid'];
    }

    public function get_monthly_invoice_revenue()
    {
        $query = $this->db->query("SELECT
                    ANY_VALUE((COALESCE(orderRevenue,0) + COALESCE(productRevenue,0) - SUM(invoice.discount))) AS revenue
                FROM invoice
                LEFT JOIN (
                    SELECT 
                        MONTH(invoice.invoice_date) AS monthNum,
                        SUM(`order`.price*`order`.quantity) AS orderRevenue
                    FROM invoice
                    LEFT JOIN `order` ON invoice.invoice_id = `order`.invoice_id
                    GROUP BY monthNum
                ) AS orderRevenue ON MONTH(invoice.invoice_date) = orderRevenue.monthNum
                LEFT JOIN (
                    SELECT 
                        MONTH(invoice.invoice_date) AS monthNum,
                        SUM(product_sale.price*product_sale.quantity) AS productRevenue
                    FROM invoice
                        LEFT JOIN product_sale ON invoice.invoice_id = product_sale.invoice_id
                        GROUP BY monthNum
                ) AS productRevenue ON MONTH(invoice.invoice_date) = productRevenue.monthNum
                GROUP BY MONTH(invoice.invoice_date)
                ORDER BY MONTH(invoice.invoice_date)
        ");

        $result =  [];
        foreach ($query->result_array() as $revenue) {
            array_push($result, $revenue['revenue']);
        }

        return $result;
    }

    public function get_monthly_invoice_revenue_average()
    {
        $monthly_invoice_revenue = $this->get_monthly_invoice_revenue();

        $monthly_average = array_sum($monthly_invoice_revenue) / count($monthly_invoice_revenue);

        return round($monthly_average);
    }

    public function get_monthly_receivable_sum()
    {
        $query = $this->db->query("SELECT
                    ANY_VALUE(COALESCE(orderRevenue,0) + COALESCE(productRevenue,0) - COALESCE(totalPaid,0) - SUM(invoice.discount)) AS receivable
                FROM invoice
                LEFT JOIN (
                    SELECT 
                        MONTH(invoice.invoice_date) AS monthNum,
                        SUM(`order`.price*`order`.quantity) AS orderRevenue
                    FROM invoice
                    LEFT JOIN `order` ON invoice.invoice_id = `order`.invoice_id
                    GROUP BY monthNum
                ) AS orderRevenue ON MONTH(invoice.invoice_date) = orderRevenue.monthNum
                LEFT JOIN (
                    SELECT 
                        MONTH(invoice.invoice_date) AS monthNum,
                        SUM(product_sale.price*product_sale.quantity) AS productRevenue
                    FROM invoice
                        LEFT JOIN product_sale ON invoice.invoice_id = product_sale.invoice_id
                        GROUP BY monthNum
                ) AS productRevenue ON MONTH(invoice.invoice_date) = productRevenue.monthNum
                LEFT JOIN (
                    SELECT 
                        MONTH(invoice.invoice_date) AS monthNum,
                        SUM(payment.amount) AS totalPaid
                    FROM invoice
                        LEFT JOIN payment ON invoice.invoice_id = payment.invoice_id
                        GROUP BY monthNum
                ) AS totalPaid ON MONTH(invoice.invoice_date) = totalPaid.monthNum
                GROUP BY MONTH(invoice.invoice_date)
                ORDER BY MONTH(invoice.invoice_date)");

        $result = [];

        foreach ($query->result_array() as $monthly_receivable) {
            array_push($result, $monthly_receivable['receivable']);
        }

        return $result;
    }

    public function get_monthly_receivable_avg()
    {
        $receivable_by_month = $this->get_monthly_receivable_sum();
        $monthly_receivable_avg = array_sum($receivable_by_month) / count($receivable_by_month);
        return $monthly_receivable_avg;
    }

    public function list_invoice_index()
    {
        $query = $this->db->query("SELECT
                    (
                        SELECT 
                            IFNULL(SUM(`order`.quantity * `order`.price), 0)
                        FROM
                            `order`
                        WHERE
                            `order`.invoice_id = invoice.invoice_id
                    ) AS order_sale,
                    (
                        SELECT 
                            IFNULL(SUM(product_sale.quantity * product_sale.price),0)
                        FROM
                            product_sale
                        WHERE
                            product_sale.invoice_id = invoice.invoice_id
                    ) AS product_sale,
                    invoice.discount,
                    (
                        SELECT 
                            IFNULL(SUM(payment.amount),0)
                        FROM
                            payment
                        WHERE
                            payment.invoice_id = invoice.invoice_id
                    ) AS paid
                FROM
                    invoice
                        JOIN
                    customer ON invoice.customer_id = customer.customer_id
                GROUP BY invoice.invoice_id
                ORDER BY invoice.number");

        $result = [];

        foreach ($query->result_array() as $invoice) {
            $invoice_due = $invoice['order_sale'] + $invoice['product_sale'] - $invoice['discount'] - $invoice['paid'];
            array_push($result, $invoice_due);
        }

        return $result;
    }

    public function get_total_receivable()
    {
        $invoice_due = $this->list_invoice_index();
        return array_sum($invoice_due);
    }

    public function get_unpaid_invoice()
    {
        $query = $this->db->query("SELECT 
                invoice.number AS INV,
                customer.name,
                (
                    (
                        SELECT 
                            IFNULL(SUM(`order`.quantity * `order`.price), 0)
                        FROM
                            `order`
                        WHERE
                            `order`.invoice_id = invoice.invoice_id
                    ) +
                    (
                        SELECT 
                            IFNULL(SUM(product_sale.quantity * product_sale.price),0)
                        FROM
                            product_sale
                        WHERE
                            product_sale.invoice_id = invoice.invoice_id
                    ) -
                    invoice.discount -
                    (
                        SELECT 
                            IFNULL(SUM(payment.amount),0)
                        FROM
                            payment
                        WHERE
                            payment.invoice_id = invoice.invoice_id
                    )
                ) AS amount
            FROM
                invoice
                    JOIN
                customer ON invoice.customer_id = customer.customer_id
            GROUP BY invoice.invoice_id
            HAVING amount > 0
            ORDER BY invoice.number");

        return $query->result_array();
    }
}
