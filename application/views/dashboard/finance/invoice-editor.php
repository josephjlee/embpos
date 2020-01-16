<!-- Begin Page Content -->
<div id="invoice-editor" class="container-fluid invoice-detail" data-page-type="<?= $this->uri->segment('2') == 'buat_invoice' ? 'buat' : 'sunting'; ?>">

  <?php $method = $this->uri->segment(2) == 'buat_invoice' ? 'simpan' : 'perbarui'; ?>

  <form action='<?= base_url("processor/invoice_pcsr/{$method}"); ?>' method="post" id="invoice-form">

    <input type="hidden" name="invoice[invoice_id]" id="invoice_id" value="<?= $invoice_detail['invoice_id'] ?? ''; ?>">
    <input type="hidden" name="invoice[discount]" id="hidden-discount" value="<?= isset($invoice_detail['discount']) ? moneyStr($invoice_detail['discount']) : '0'; ?>">

    <input type="hidden" name="payment[payment_method_id]" id="hidden-payment-method" value="">
    <input type="hidden" name="payment[amount]" id="hidden-payment-amount" value="">

    <!-- Content Title -->
    <div class="row">
      <div class="col d-flex justify-content-start align-items-center">

        <h1 class="h3 text-gray-800 mr-2"><?= $title; ?></h1>

        <?php if ($this->uri->segment(2) == 'sunting_invoice') : ?>

          <a href="<?= base_url('keuangan/lihat_invoice/') . $invoice_detail['invoice_number']; ?>" class="pb-1 action-btn"><i class="fas fa-fw fa-file-image fa-lg"></i></a>

          <div class="ml-auto">
            <span class="badge badge-primary mr-2 py-2 px-3 text-uppercase"><?= $invoice_detail['payment_status']; ?></span>
            <span class="badge badge-danger py-2 px-3 text-uppercase"><?= $invoice_detail['order_status']; ?></span>
          </div>

        <?php endif; ?>

      </div>
    </div>

    <div class="row mt-2">

      <!-- Main Panel -->
      <div class="col-8">

        <!-- Invoice Header Card-->
        <div class="card shadow mb-3" id="customer-detail">

          <div class="card-body">

            <div class="d-flex justify-content-between align-items-center mb-2">

              <div class="input-group mr-2">
                <div class="input-group-prepend">
                  <div class="input-group-text"><i class="fas fa-file-invoice fa-fw"></i></div>
                </div>
                <input type="text" name="invoice[number]" id="order-number" placeholder="nomor invoice" value="<?= $invoice_number ?? $invoice_detail['invoice_number']; ?>" class="form-control">
              </div>

              <div class="input-group customer-select-wrapper">
                <div class="input-group-prepend">
                  <a href="" class="input-group-text" id="add-customer" data-toggle="modal" data-target="#addCustomerModal"><i class="fas fa-user fa-fw"></i></a>
                </div>

                <!-- Populate customer select option using get_all_customers method from pelanggan_model -->
                <?php $customers = $this->pelanggan_model->get_all_customers(); ?>

                <select name="invoice[customer_id]" id="customers" class="custom-select">
                  <option value="">Pilih Pelanggan</option>

                  <?php if (isset($invoice_detail['customer_id'])) : ?>

                    <?php foreach ($customers as $customer) : ?>
                      <option value="<?= $customer['customer_id']; ?>" <?= $customer['customer_id'] == $invoice_detail['customer_id'] ? 'selected' : ''; ?>><?= $customer['customer_name']; ?></option>
                    <?php endforeach; ?>

                  <?php else : ?>

                    <?php foreach ($customers as $customer) : ?>
                      <option value="<?= $customer['customer_id']; ?>"><?= $customer['customer_name']; ?></option>
                    <?php endforeach; ?>

                  <?php endif; ?>
                </select>
              </div>

            </div>

            <div class="d-flex justify-content-between align-items-center">

              <div class="input-group mr-2">
                <div class="input-group-prepend">
                  <div class="input-group-text"><i class="fas fa-calendar-plus fa-fw"></i></div>
                </div>
                <input type="date" name="invoice[invoice_date]" id="invoice-date" placeholder="tanggal penerbitan" class="form-control" value="<?= isset($invoice_detail['invoice_date']) ? date('Y-m-d', strtotime($invoice_detail['invoice_date'])) : ''; ?>">
              </div>

              <div class="input-group">
                <div class="input-group-prepend">
                  <div class="input-group-text"><i class="fas fa-calendar-check fa-fw"></i></div>
                </div>
                <input type="date" name="invoice[payment_date]" id="payment-date" placeholder="tanggal pembayaran" class="form-control" value="<?= isset($invoice_detail['payment_date']) ? date('Y-m-d', strtotime($invoice_detail['payment_date'])) : ''; ?>">
              </div>

            </div>

          </div>

        </div>

        <!-- Invoice Card -->
        <div class="card shadow mb-3" id="invoice-detail">

          <div class="card-body">

            <!-- Invoice Body Table -->
            <table class="table" id="invoice-detail-table">
              <thead class="thead-light">
                <tr>
                  <th scope="col"></th>
                  <th scope="col" style="width:40%">Barang</th>
                  <th scope="col">Kuantitas</th>
                  <th scope="col">Harga</th>
                  <th scope="col">Jumlah</th>
                </tr>
              </thead>

              <?php $item_index = 0; ?>

              <tbody>

                <?php $order_index = 0; ?>

                <?php if (isset($current_orders)) : ?>

                  <?php foreach ($current_orders as $order) : ?>

                    <tr data-item-index="<?= $item_index; ?>" data-order-index="<?= $order_index; ?>" data-order-id="<?= $order['order_id']; ?>" class="order-entry">

                      <input type="hidden" name="orders[<?= $item_index; ?>][order_id]" value="<?= $order['order_id']; ?>">
                      <input type="hidden" name="orders[<?= $item_index; ?>][invoice_id]" value="<?= $order['invoice_id']; ?>">

                      <td class="pr-0">
                        <a href="#" style='color:#AAB0C6' class="order-del-trigger" data-toggle="modal" data-target="#del-order-modal">
                          <i class="fas fa-times"></i>
                        </a>
                      </td>

                      <td id="items-col" style="width:40%">
                        <input type="text" name="orders[<?= $item_index; ?>][description]" id="items" class="items form-control" placeholder="Nama pesanan" value="<?= $order['description']; ?>">
                      </td>

                      <td id="qty-col">
                        <input type="text" name="orders[<?= $item_index; ?>][quantity]" id="quantity-<?= $item_index; ?>" class="form-control quantity number text-right" value="<?= $order['quantity']; ?>">
                      </td>

                      <td id="price-col">
                        <input type="text" name="orders[<?= $item_index; ?>][price]" id="price-<?= $item_index; ?>" class="form-control price number text-right" value="<?= $order['price']; ?>">
                      </td>

                      <td id="amount-col" class="pr-0">
                        <input type="text" id="amount-<?= $item_index; ?>" class="form-control amount text-right" value="<?= moneyStr($order['amount']); ?>" readonly>
                      </td>

                    </tr>

                    <?php $item_index++; ?>
                    <?php $order_index++; ?>

                  <?php endforeach; ?>

                <?php endif; ?>

                <?php if (isset($current_products)) : ?>

                  <?php

                  function product_id_only($product)
                  {
                    $product_id_array = $product['product_id'];
                    return $product_id_array;
                  }

                  $selected_product_id = array_map("product_id_only", $current_products);

                  ?>

                  <?php

                  /**
                   * 
                   * Item index (row index) for the product entry is relative to the order entry above
                   * If order entry is not empty, then item index for the product entry is the continuation
                   * If order entry is empty, then item index for the product entry start from 0
                   * 
                   * Meanwhile, the product index always starts from 0
                   * 
                   */

                  $current_item_index = $item_index != 0 ? $item_index : 0;

                  $product_index = 0;

                  ?>

                  <?php foreach ($current_products as $current_product) : ?>

                    <tr id="product-entry-<?= $current_product['product_id']; ?>" class="product-entry" data-item-index="<?= $current_item_index; ?>" data-product-index="<?= $product_index; ?>" data-product-id="<?= $current_product['product_id']; ?>" data-product-sale-id="<?= $current_product['product_sale_id']; ?>">
                      <input type="hidden" name="products[<?= $product_index ?>][product_sale_id]" value="<?= $current_product['product_sale_id']; ?>">
                      <input type="hidden" name="products[<?= $product_index ?>][product_id]" value="<?= $current_product['product_id']; ?>">
                      <td id="product-del-btn-col" class="pr-0">
                        <a href='#' style='color:#AAB0C6' class="product-del-trigger" data-toggle="modal" data-target="#del-product-modal"><i class="fas fa-times"></i></a>
                      </td>
                      <td id="product-items-col" style="width:40%">
                        <input type="text" id="items" class="items form-control" value="<?= $current_product['title']; ?>" readonly>
                      </td>
                      <td id="product-qty-col">
                        <input type="text" name="products[<?= $product_index ?>][quantity]" id="quantity-<?= $product_index ?>" class="form-control text-right quantity number" value="<?= $current_product['quantity']; ?>">
                      </td>
                      <td id="product-price-col">
                        <input type="text" id="price-<?= $product_index ?>" class="form-control text-right price number" value="<?= $current_product['price']; ?>" readonly>
                      </td>
                      <td id="product-amount-col" class="pr-0">
                        <input type="text" id="amount-<?= $product_index ?>" class="form-control text-right amount" value="<?= moneyStr($current_product['amount']); ?>" readonly>
                      </td>
                    </tr>

                    <?php $current_item_index++; ?>
                    <?php $product_index++; ?>

                  <?php endforeach; ?>

                <?php endif; ?>

              </tbody>

            </table>

            <!-- Add Order Button -->
            <div class="row mb-3">
              <div class="col">
                <button type="button" class="btn btn-primary btn-sm w-100 rounded-pill" data-toggle="modal" data-target="#add-order-modal">+ Tambah Pesanan</button>
              </div>
            </div>

            <!-- Invoice Calculation Table -->
            <div class="row justify-content-end mb-3">
              <div class="col pt-2" style="font-size:14px">
                <table class="table order-preview-table table-borderless">
                  <tbody>
                    <tr>
                      <th scope="col" class="text-right">
                        Diskon:
                      </th>
                      <td scope="col" class="text-left">
                        <div class="d-flex justify-content-between">
                          <p>Rp</p>
                          <input type="text" style="border:none" class="text-right number" id="discount" value="<?= isset($invoice_detail['discount']) ? moneyStr($invoice_detail['discount']) : '0'; ?>" readonly>
                        </div>
                      </td>
                      <td>&nbsp;</td>
                      <th scope="col" class="text-right">
                        Sub Total:
                      </th>
                      <td scope="col" class="text-left">
                        <div class="d-flex justify-content-between">
                          <p>Rp</p>
                          <input type="text" id="sub-total" style="border:none" class="text-right" value="<?= isset($invoice_detail['sub_total']) ? moneyStr($invoice_detail['sub_total']) : '0'; ?>" readonly>
                        </div>
                      </td>
                    </tr>
                    <tr>
                      <th scope="col" class="text-right">
                        Pembayaran:
                      </th>
                      <td scope="col" class="text-left">
                        <div class="d-flex justify-content-between">
                          <p>Rp</p>
                          <input type="text" style="border:none" class="text-right number" id="paid" value="<?= isset($invoice_detail['paid']) ? moneyStr($invoice_detail['paid']) : '0'; ?>" readonly>
                        </div>
                      </td>
                      <td>&nbsp;</td>
                      <th scope="col" class="text-right">
                        Total:
                      </th>
                      <td scope="col">
                        <div class="d-flex justify-content-between">
                          <p>Rp</p>
                          <input type="text" id="total-due" style="border:none" class="text-right" value="<?= isset($invoice_detail['total_due']) ? moneyStr($invoice_detail['total_due']) : '0'; ?>" readonly>
                        </div>
                      </td>
                    </tr>
                    <tr>
                      <td></td>
                      <td></td>
                      <td>&nbsp;</td>
                      <th scope="col" class="text-right">Sisa Tagihan:</th>
                      <td scope="col" class="text-left">
                        <div class="d-flex justify-content-between">
                          <p>Rp</p>
                          <input type="text" id="payment-due" style="border:none" class="text-right number" value="<?= isset($invoice_detail['invoice_due']) ? moneyStr($invoice_detail['invoice_due']) : '0'; ?>" <?= $this->uri->segment(2) == 'sunting_invoice' ? 'readonly' : ''; ?>>
                        </div>
                      </td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>

            <div class="row">
              <div class="col">
                <textarea name="invoice[note]" id="note" class="form-control" style="border:none;font-size:13px" placeholder="Catatan..."><?= $invoice_detail['note'] ?? ''; ?></textarea>
              </div>
            </div>

          </div>

        </div>

      </div>

      <!-- Side Panel -->
      <div class="col-4" id="side-panel">

        <!-- Action Card -->
        <div class="card shadow mb-3 invoice-action-panel">
          <!-- Card Header - Accordion -->
          <a href="#actionCard" class="d-block card-header py-3" data-toggle="collapse" role="button">
            <h6 class="m-0 font-weight-bold text-primary">Tindakan</h6>
          </a>
          <!-- Card Content - Collapse -->
          <div class="collapse show" id="actionCard">
            <div class="card-body">
              <button type="submit" id="save-data-btn" class="mr-2 action-btn">
                <i class="fas fa-save fa-2x"></i>
              </button>
              <a href="#" data-toggle="modal" data-target="#product-list-modal" class="action-btn">
                <i class="fas fa-gift fa-2x"></i>
              </a>
              <a href="#" data-toggle="modal" data-target="#order-list-modal" class="action-btn">
                <i class="fas fa-box-open fa-2x"></i>
              </a>
              <a href="#" data-toggle="modal" data-target="#paymentModal" data-is-input="true" class="action-btn">
                <i class="fas fa-cash-register fa-2x"></i>
              </a>
              <a href="#" data-toggle="modal" data-target="#discountModal" class="action-btn">
                <i class="fas fa-tag fa-2x"></i>
              </a>
              <a href="#" data-toggle="modal" data-target="#delOrderModal" class="action-btn">
                <i class="fas fa-trash-alt fa-2x"></i>
              </a>
            </div>
          </div>
        </div>

        <?php if ($this->uri->segment(2) == 'sunting_invoice') : ?>

          <!-- Payment History Card -->
          <div class="card shadow mb-3">
            <a href="#paymentHistory" class="d-block card-header py-3" data-toggle="collapse" role="button">
              <h6 class="m-0 font-weight-bold text-primary">Riwayat Pembayaran</h6>
            </a>
            <div class="collapse show" id="paymentHistory">
              <div class="card-body py-0">

                <table class="table">
                  <tbody>

                    <?php if (!empty($payment_records)) : ?>

                      <?php foreach ($payment_records as $payment) : ?>

                        <tr data-payment-id="<?= $payment['payment_id']; ?>" data-payment-amount="<?= $payment['payment_amount']; ?>" data-payment-date="<?= date('Y-m-d', strtotime($payment['payment_date'])); ?>" data-payment-method="<?= $payment['payment_method_id']; ?>">
                          <td class="px-0">
                            <div>
                              <small id="payment-date-display" style="color:#ec8615">
                                Rp<span id="payment-amount-display"><?= moneyStr($payment['payment_amount']); ?></span>,00
                              </small>
                              <p style="font-size:14px;" id="payment-name-display">
                                <span style="color:#495057"><?= date('d-m-Y', strtotime($payment['payment_date'])); ?></span> | <?= $payment['payment_name']; ?>
                              </p>
                            </div>
                          </td>
                          <td class="px-0 align-middle text-right">
                            <a class="dropdown-toggle text-right" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown">
                              <i class="fas fa-ellipsis-v fa-sm fa-fw" style="color:#aba9bf"></i>
                            </a>
                            <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in">
                              <a href="" class="dropdown-item update-payment-trigger" data-toggle="modal" data-target="#updatePaymentModal">Sunting Detail</a>
                              <a href="" class="dropdown-item del-payment-trigger" data-toggle="modal" data-target="#deletePaymentModal">Hapus Pembayaran</a>
                            </div>
                          </td>
                        </tr>

                      <?php endforeach; ?>

                    <?php else : ?>

                      <tr>
                        <td class="px-0">Belum ada pembayaran.</td>
                      </tr>

                    <?php endif; ?>

                  </tbody>
                </table>
              </div>
            </div>
          </div>

          <!-- Order Process Card -->
          <div class="card shadow mb-3">
            <a href="#process" class="d-block card-header py-3" data-toggle="collapse" role="button">
              <h6 class="m-0 font-weight-bold text-primary">Status Pengerjaan</h6>
            </a>
            <div class="collapse show" id="process">
              <div class="card-body py-0">

                <table class="table">
                  <tbody>

                    <?php if ($current_orders) : ?>

                      <?php foreach ($current_orders as $order) : ?>

                        <?php $production_status = $this->produksi_model->check_production_status_by_order_id($order['order_id']); ?>

                        <?php $output = $this->produksi_model->get_production_output_by_order_id($order['order_id']); ?>

                        <?php $production = $this->produksi_model->get_production_by_id($order['order_id']); ?>

                        <tr data-order-id="<?= $order['order_id']; ?>" data-description="<?= $order['description']; ?>" data-order-date="<?= date('Y-m-d', strtotime($order['received_date'])); ?>" data-required-date="<?= date('Y-m-d', strtotime($order['required_date'])); ?>" data-item-id="<?= $order['item_id'] ?>" data-position-id="<?= $order['position_id'] ?>" data-dimension="<?= $order['dimension']; ?>" data-color="<?= $order['color']; ?>" data-color="<?= $order['color']; ?>" data-material="<?= $order['material']; ?>" data-quantity="<?= $order['quantity']; ?>" data-price="<?= $order['price']; ?>" data-amount="<?= $order['amount']; ?>" data-note="<?= $order['note']; ?>" data-process-status-id="<?= $order['process_status_id']; ?>" data-status-design="<?= $production_status['design']; ?>" data-status-embro="<?= $production_status['embro']; ?>" data-status-finishing="<?= $production_status['finishing']; ?>" data-output-design="<?= $output['design']; ?>" data-output-embro="<?= $output['embro']; ?>" data-output-finishing="<?= $output['finishing']; ?>" data-labor-price="<?= $production['labor_price']; ?>" data-production-id="<?= $production['production_id']; ?>">

                          <td class="px-0">
                            <div>
                              <small id="payment-date-display" style="color:#ec8615"><?= $order['process_status']; ?></small>
                              <p style="font-size:14px;" id="payment-name-display">
                                <span style="color:#495057"><?= date('d-m-Y', strtotime($order['required_date'])); ?></span> | <?= $order['description']; ?>
                              </p>
                            </div>
                          </td>

                          <td class="px-0 align-middle text-right">

                            <a class="dropdown-toggle text-right" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown">
                              <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
                            </a>

                            <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in">

                              <a href="#" class="dropdown-item view-order-detail" data-toggle="modal" data-target="#order-detail-modal">Lihat Detail</a>
                              <a href="#" class="dropdown-item view-order-process" data-toggle="modal" data-target="#order-process-modal">Lihat Proses</a>
                              <a href="#" class="dropdown-item status-mark-trigger" data-toggle="modal" data-target="#update-process-modal">Tandai Sebagai</a>
                              <a href="#" class="dropdown-item set-price-trigger" data-toggle="modal" data-target="#operator-price-modal">Harga Operator</a>
                              <a href="#" class="dropdown-item upload-artwork-trigger" data-toggle="modal" data-target="#upload-artwork-modal">Unggah Gambar</a>

                            </div>

                          </td>

                        </tr>

                      <?php endforeach; ?>

                    <?php else : ?>

                      <tr>
                        <td class="px-0">Semua pesanan telah diselesaikan</td>
                      </tr>

                    <?php endif; ?>

                  </tbody>
                </table>
              </div>
            </div>
          </div>

          <!-- Product Fulfillment Card -->

        <?php endif; ?>

      </div>

    </div>

  </form>

  <!-- Add Customer Modal -->
  <div class="modal fade" id="addCustomerModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">

      <div class="modal-content">

        <form id="customer-form">

          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Tambah Pelanggan Baru</h5>
            <button type="button" class="close" data-dismiss="modal">
              <span aria-hidden="true">×</span>
            </button>
          </div>

          <div class="modal-body">
            <div class="mb-4">

              <div class="form-group">
                <label for="cust_name"><small>Nama lengkap</small></label>
                <input type="text" name="customer[name]" class="form-control mb-2" value="" required>
              </div>

              <?php $customer_categories = $this->pelanggan_model->get_customer_categories(); ?>
              <div class="form-row mb-2">
                <div class="form-group col">
                  <label for="cust_company"><small>Afiliasi</small></label>
                  <input type="text" name="customer[company]" class="form-control">
                </div>
                <div class="form-group col">
                  <label for="cust_category"><small>Kategori</small></label>
                  <select name="customer[customer_category_id]" class="custom-select" required>
                    <option value="">Pilih kategori ...</option>

                    <?php foreach ($customer_categories as $customer_category) : ?>
                      <option value="<?= $customer_category['id'] ?>"><?= $customer_category['name'] ?></option>
                    <?php endforeach; ?>
                  </select>
                </div>
              </div>

              <div class="form-row">
                <div class="form-group col">
                  <label for="cust_phone"><small>Ponsel</small></label>
                  <input type="tel" name="customer[phone]" class="form-control" value="">
                </div>
                <div class="form-group col">
                  <label for="cust_email"><small>Email</small></label>
                  <input type="email" name="customer[email]" class="form-control">
                </div>
              </div>

              <div class="form-group">
                <label for="cust_address"><small>Alamat</small></label>
                <input type="text" name="customer[address]" class="form-control">
              </div>

            </div>
          </div>

          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-primary">Simpan Data</button>
          </div>
        </form>

      </div>
    </div>
  </div>

  <!-- Input Payment Modal -->
  <div class="modal fade" id="paymentModal" tabindex="-1" role="dialog">

    <div class="modal-dialog" role="document">

      <form action="" method="post" id="payment-form">

        <input type="hidden" name="invoice[invoice_id]" id="invoice-id" value="<?= $invoice_detail['invoice_id'] ?? ''; ?>">
        <input type="hidden" name="invoice[number]" id="invoice-number" value="<?= $invoice_detail['invoice_number'] ?? ''; ?>">
        <input type="hidden" name="invoice[customer_id]" id="customer-id" value="<?= $invoice_detail['customer_id'] ?? ''; ?>">

        <input type="hidden" name="payment[payment_id]" id="modal-payment-id" value="">

        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Rekam Pembayaran</h5>
            <button type="button" class="close" data-dismiss="modal">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>

          <div class="modal-body">

            <div class="form-group">
              <label for="modal-payment-amount"><small>Nominal</small></label>
              <input type="text" name="payment[amount]" id="modal-payment-amount" class="form-control" value="0">
            </div>

            <div class="form-group">
              <label for="modal-payment-method"><small>Metode Pembayaran</small></label>
              <select name="payment[payment_method_id]" id="modal-payment-method" class="custom-select">
                <option value="">Pilih...</option>

                <?php $payment_methods = $this->pembayaran_model->get_payment_method(); ?>

                <?php foreach ($payment_methods as $payment_method) : ?>
                  <option value="<?= $payment_method['payment_method_id']; ?>"><?= $payment_method['name']; ?></option>
                <?php endforeach; ?>

              </select>
            </div>

            <?php if ($this->uri->segment(2) == 'sunting_invoice') : ?>
              <div class="form-group">
                <label for="modal-payment-date"><small>Tanggal</small></label>
                <input type="date" name="payment[payment_date]" id="modal-payment-date" class="form-control">
              </div>
            <?php endif; ?>

          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
            <button type="submit" class="btn btn-primary" id="save-payment-btn">Simpan data</button>
          </div>
        </div>
      </form>

    </div>
  </div>

  <!-- Discount Modal -->
  <div class="modal fade" id="discountModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
      <form action="" method="post" id="discount-form">
        <input type="hidden" name="order-id" id="order-id" value="<?= $invoice_detail['invoice_id'] ?? ''; ?>">
        <input type="hidden" name="order-number" id="order-number" value="<?= $invoice_detail['invoice_number'] ?? ''; ?>">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Tambahkan Diskon</h5>
            <button type="button" class="close" data-dismiss="modal">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <div class="form-group">
              <label for="discount-amount"><small>Nominal</small></label>
              <input type="text" name="discount-amount" id="discount-amount" class="form-control" value="<?= isset($invoice_detail['discount']) ? moneyStr($invoice_detail['discount']) : '0'; ?>">
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
            <button type="submit" class="btn btn-primary" id="save-discount-btn">Simpan data</button>
          </div>
        </div>
      </form>
    </div>
  </div>

  <!-- Product List Modal -->
  <div class="modal fade" id="product-list-modal" tabindex="-1" role="dialog">

    <div class="modal-dialog" role="document">

      <div class="modal-content">

        <div class="modal-header">
          <h5 class="modal-title">Daftar Produk</h5>
          <button class="close" type="button" data-dismiss="modal">
            <span aria-hidden="true">×</span>
          </button>
        </div>

        <div class="input-group mt-3 px-3">
          <div class="input-group-prepend">
            <span class="input-group-text" id="inputGroupPrepend2"><i class="fas fa-search"></i></span>
          </div>
          <input type="text" id="filter-input" class="form-control" placeholder="Cari produk...">
        </div>

        <div class="modal-body">

          <!-- product-index is auto incremented on product list iteration -->

          <?php $catalog = $this->produk_model->get_simple_product_catalog(); ?>

          <?php if (isset($current_products)) : ?>

            <?php for ($i = 0; $i < count($catalog); $i++) : ?>

              <div class="product-list-item justify-content-between align-items-center mb-3 <?= in_array($catalog[$i]['product_id'], $selected_product_id) ? 'unavailable' : ''; ?>" data-product-index="<?= $i; ?>" data-product-id="<?= $catalog[$i]['product_id']; ?>" data-desc="<?= $catalog[$i]['title']; ?>" data-qty="1" data-price="<?= $catalog[$i]['sell_price']; ?>" data-amount="<?= $catalog[$i]['sell_price']; ?>" id="product-list-item-<?= $catalog[$i]['product_id']; ?>" style="display:flex">

                <div class="d-flex align-items-center">
                  <a class="dropdown-toggle mr-3" href="#" role="button">
                    <img style="width:33px;height:100%" src="<?= isset($catalog[$i]['image']) ? base_url('assets/img/product/') . $catalog[$i]['image'] : base_url('assets/icon/') . $catalog[$i]['item_icon']; ?>">
                  </a>
                  <div>
                    <div style="color:#9aa0ac;font-size:13px;">
                      <span style="margin-right:2px;color:#ec8615">Rp<?= number_format($catalog[$i]['sell_price'], 2, ',', '.'); ?></span>
                      (<span id="stock"><?= $catalog[$i]['stock']; ?></span>pcs)
                    </div>
                    <div class="product-name" style="color:#495057;font-size:15px"><?= $catalog[$i]['title']; ?></div>
                  </div>
                </div>
                <a href="#" style="color:#AAB0C6" class="<?= in_array($catalog[$i]['product_id'], $selected_product_id) ? '' : 'add-product-btn'; ?>" id="add-product-<?= $catalog[$i]['product_id']; ?>" data-product-id="<?= $catalog[$i]['product_id']; ?>">
                  <i class="fas fa-plus"></i>
                </a>
              </div>

            <?php endfor; ?>

          <?php else : ?>

            <?php for ($i = 0; $i < count($catalog); $i++) : ?>

              <div class="product-list-item justify-content-between align-items-center mb-3" data-product-index="<?= $i; ?>" data-product-id="<?= $catalog[$i]['product_id']; ?>" data-desc="<?= $catalog[$i]['title']; ?>" data-qty="1" data-price="<?= $catalog[$i]['sell_price']; ?>" data-amount="<?= $catalog[$i]['sell_price']; ?>" data-stock="<?= $catalog[$i]['stock']; ?>" id="product-list-item-<?= $catalog[$i]['product_id']; ?>" style="display:flex">

                <div class="d-flex align-items-center">

                  <a class="dropdown-toggle mr-3" href="#" role="button">
                    <img style="width:33px;height:100%" src="<?= isset($catalog[$i]['image']) ? base_url('assets/img/product/') . $catalog[$i]['image'] : base_url('assets/icon/') . $catalog[$i]['item_icon']; ?>">
                  </a>

                  <div>
                    <div style="color:#9aa0ac;font-size:13px;">
                      <span style="margin-right:2px;color:#ec8615">
                        Rp<?= number_format($catalog[$i]['sell_price'], 2, ',', '.'); ?>
                      </span>
                      <span>
                        (<span id="stock"><?= $catalog[$i]['stock']; ?></span>pcs)
                      </span>
                    </div>
                    <div class="product-name" style="color:#495057;font-size:15px"><?= $catalog[$i]['title']; ?></div>
                  </div>

                </div>

                <a href='#' style='color:#AAB0C6' class='add-product-btn' id='add-product-<?= $catalog[$i]['product_id']; ?>' data-product-id="<?= $catalog[$i]['product_id']; ?>">
                  <i class="fas fa-plus"></i>
                </a>

              </div>

            <?php endfor; ?>

          <?php endif; ?>
        </div>


      </div>
    </div>

  </div>

  <!-- Order List Modal -->
  <div class="modal fade" id="order-list-modal" tabindex="-1" role="dialog">

    <div class="modal-dialog" role="document">

      <div class="modal-content">

        <div class="modal-header">
          <h5 class="modal-title">Daftar Pesanan</h5>
          <button class="close" type="button" data-dismiss="modal">
            <span aria-hidden="true">×</span>
          </button>
        </div>

        <div class="modal-body">

          <?php if ($this->uri->segment(2) == 'sunting_invoice') : ?>

            <?php if ($uninvoiced_orders) : ?>

              <?php foreach ($uninvoiced_orders as $uninvoiced_order) : ?>

                <?= $uninvoiced_order; ?>

              <?php endforeach; ?>

            <?php else : ?>

              Tidak ada pesanan

            <?php endif; ?>

          <?php endif; ?>

        </div>

      </div>

    </div>

  </div>

  <!-- Add Order Modal -->
  <div class="modal fade" id="add-order-modal" tabindex="-1" role="dialog">

    <div class="modal-dialog" role="document">

      <form action="<?= base_url('ajax/pesanan_ajax/add_order') ?>" id="add-order-form" method="post" data-order-number="">

        <input type="hidden" id="amount" value="">
        <input type="hidden" id="customer-id" name="order[customer_id]" value="">
        <input type="hidden" id="order-number" name="order[number]" value="">

        <div class="modal-content">

          <div class="modal-header">
            <h5 class="modal-title">Detail Pesanan</h5>
            <button type="button" class="close" data-dismiss="modal">
              <span>&times;</span>
            </button>
          </div>

          <div class="modal-body">

            <div class="form-row">
              <div class="form-group col">
                <label for="description"><small>Judul bordiran</small></label>
                <input type="text" id="description" class="form-control description" name="order[description]" value="" required>
              </div>
            </div>

            <div class="form-row">

              <div class="form-group col">
                <label for="received-date"><small>Tanggal Pesan</small></label>
                <input class="form-control" type="date" id="received-date" name="order[received_date]" value="" required>
              </div>

              <div class="form-group col">
                <label for="received-date"><small>Tanggal Diambil</small></label>
                <input class="form-control" type="date" id="required-date" name="order[required_date]" value="" required>
              </div>

            </div>

            <div class="form-row">

              <div class="form-group col">

                <label for="item"><small>Jenis barang</small></label>
                <select id="item" name="order[item_id]" required>

                  <option value="">Pilih...</option>

                  <?php $items = $this->pesanan_model->get_all_items(); ?>

                  <?php foreach ($items as $item) : ?>

                    <option value="<?= $item['item_id']; ?>">
                      <?= $item['item_name'] ?>
                    </option>

                  <?php endforeach; ?>

                </select>

              </div>

              <div class="form-group col">

                <label for="position"><small>Posisi yang diinginkan</small></label>
                <select id="position" class="custom-select position" name="order[position_id]" required>
                  <option value="">Pilih...</option>

                  <?php $positions = $this->pesanan_model->get_item_position_pairs(); ?>

                  <?php foreach ($positions as $position) : ?>

                    <option value="<?= $position['position_id']; ?>">
                      <?= $position['name'] ?>
                    </option>

                  <?php endforeach; ?>


                </select>

              </div>

            </div>

            <div class="form-row">

              <div class="form-group col">
                <label for="dimension"><small>Dimensi</small></label>
                <input type="text" id="dimension" class="form-control dimension number" name="order[dimension]" value="">
              </div>
              <div class="form-group col">
                <label for=""><small>Warna</small></label>
                <input type="text" id="color" class="form-control color number" name="order[color]" value="">
              </div>
              <div class="form-group col">
                <label for=""><small>Bahan</small></label>
                <input type="text" id="material" class="form-control material number" name="order[material]" value="">
              </div>

            </div>

            <div class="form-row">

              <div class="form-group col">
                <label for="quantity"><small>Kuantitas</small></label>
                <input type="text" id="quantity" class="form-control quantity number text-right" name="order[quantity]" placeholder="0" value="" required>
              </div>

              <div class="form-group col">
                <label for=""><small>Harga</small></label>
                <input type="text" id="price" class="form-control price number text-right" placeholder="0" name="order[price]" value="" required>
              </div>

            </div>

            <div class="form-row">
              <div class="form-group col">
                <label for="note"><small>Catatan</small></label>
                <textarea id="note" class="form-control" name="order[note]" style="font-size:13px"></textarea>
              </div>
            </div>

          </div>

          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
            <button type="submit" class="btn btn-primary">Simpan</button>
          </div>

        </div>

      </form>

    </div>

  </div>


</div>

<script>
  const filterInput = document.querySelector('#filter-input');

  filterInput.addEventListener('keyup', (e) => {

    let filterValue = filterInput.value.toUpperCase();

    let productNames = document.querySelectorAll('.product-name');

    for (let i = 0; i < productNames.length; i++) {
      const productName = productNames[i].innerHTML;

      // console.log(productNames[i].closest('.product-list-item'));

      if (productName.toUpperCase().indexOf(filterValue) > -1) {
        productNames[i].closest('.product-list-item').style.display = 'flex';
      } else {
        productNames[i].closest('.product-list-item').style.display = 'none';
      }
    };
  });
</script>
<!-- /.container-fluid -->

<?php if ($this->uri->segment(2) == 'sunting_invoice') : ?>

  <!-- Detach Product Modal-->
  <div class="modal fade" id="del-product-modal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
      <form action="<?= base_url('processor/invoice_pcsr/lepas_produk'); ?>" method="post" id="detach-product-form">
        <input type="hidden" name="invoice[number]" id="invoice-number" value="<?= $invoice_detail['invoice_number'] ?? ''; ?>">
        <input type="hidden" name="product_sale[product_sale_id]" id="modal-product-sale-id" value="">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Yakin akan menghapus?</h5>
            <button class="close" type="button" data-dismiss="modal">
              <span aria-hidden="true">×</span>
            </button>
          </div>
          <div class="modal-body">Klik "Hapus" jika Anda yakin untuk menghapus produk ini dari Invoice.</div>
          <div class="modal-footer">
            <button class="btn btn-secondary" type="button" data-dismiss="modal">Batal</button>
            <button type="submit" class="btn btn-primary" id="del-product-btn">Hapus</button>
          </div>
        </div>
      </form>
    </div>
  </div>

  <!-- Detach Order Modal-->
  <div class="modal fade" id="del-order-modal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
      <form action="<?= base_url('processor/invoice_pcsr/lepas_pesanan'); ?>" method="post" id="detach-order-form">
        <input type="hidden" name="invoice[number]" id="invoice-number" value="<?= $invoice_detail['invoice_number'] ?? ''; ?>">
        <input type="hidden" name="order[order_id]" id="modal-order-id" value="">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Yakin akan menghapus?</h5>
            <button class="close" type="button" data-dismiss="modal">
              <span aria-hidden="true">×</span>
            </button>
          </div>
          <div class="modal-body">Klik "Hapus" jika Anda yakin untuk menghapus pesanan ini dari Invoice.</div>
          <div class="modal-footer">
            <button class="btn btn-secondary" type="button" data-dismiss="modal">Batal</button>
            <button type="submit" class="btn btn-primary" id="del-product-btn">Hapus</button>
          </div>
        </div>
      </form>
    </div>
  </div>

  <!-- Delete Invoice Modal-->
  <div class="modal fade" id="delOrderModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
      <form action="<?= base_url('processor/invoice_pcsr/hapus_invoice'); ?>" method="post" id="delete-invoice-form">
        <input type="hidden" name="invoice[invoice_id]" id="invoice-id" value="<?= $invoice_detail['invoice_id']; ?>">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Yakin akan menghapus?</h5>
            <button class="close" type="button" data-dismiss="modal">
              <span aria-hidden="true">×</span>
            </button>
          </div>
          <div class="modal-body">Klik "Hapus" jika Anda yakin untuk menghapus Invoice ini.</div>
          <div class="modal-footer">
            <button class="btn btn-secondary" type="button" data-dismiss="modal">Batal</button>
            <button type="submit" class="btn btn-primary" id="delInvoiceBtn">Hapus</button>
          </div>
        </div>
      </form>
    </div>
  </div>

  <!-- Update Payment Modal -->
  <div class="modal fade" id="updatePaymentModal" tabindex="-1" role="dialog">

    <div class="modal-dialog" role="document">

      <form action="<?= base_url('processor/invoice_pcsr/perbarui_pembayaran') ?>" method="post" id="update-payment-form">

        <input type="hidden" name="invoice[number]" id="invoice-number" value="<?= $invoice_detail['invoice_number'] ?? ''; ?>">

        <input type="hidden" name="payment[payment_id]" id="update-payment-id" value="">

        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Rekam Pembayaran</h5>
            <button type="button" class="close" data-dismiss="modal">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>

          <div class="modal-body">

            <div class="form-group">
              <label for="update-payment-amount"><small>Nominal</small></label>
              <input type="text" name="payment[amount]" id="update-payment-amount" class="form-control" value="0">
            </div>

            <div class="form-group">
              <label for="update-payment-method"><small>Metode Pembayaran</small></label>
              <select name="payment[payment_method_id]" id="update-payment-method" class="custom-select">
                <option value="">Pilih...</option>

                <?php $payment_methods = $this->pembayaran_model->get_payment_method(); ?>

                <?php foreach ($payment_methods as $payment_method) : ?>
                  <option value="<?= $payment_method['payment_method_id']; ?>"><?= $payment_method['name']; ?></option>
                <?php endforeach; ?>

              </select>
            </div>

            <div class="form-group">
              <label for="update-payment-date"><small>Tanggal</small></label>
              <input type="date" name="payment[payment_date]" id="update-payment-date" class="form-control">
            </div>

          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
            <button type="submit" class="btn btn-primary" id="save-payment-btn">Perbarui data</button>
          </div>
        </div>
      </form>

    </div>
  </div>

  <!-- Delete Payment Modal-->
  <div class="modal fade" id="deletePaymentModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
      <form action="<?= base_url('processor/invoice_pcsr/hapus_pembayaran'); ?>" method="post" id="delete-payment-form">
        <input type="hidden" name="invoice[number]" id="invoice-number" value="<?= $invoice_detail['invoice_number']; ?>">
        <input type="hidden" name="payment[payment_id]" id="del-payment-modal__payment-id" value="">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Yakin akan menghapus?</h5>
            <button class="close" type="button" data-dismiss="modal">
              <span aria-hidden="true">×</span>
            </button>
          </div>
          <div class="modal-body">Klik "Hapus" jika Anda yakin untuk menghapus pembayaran ini.</div>
          <div class="modal-footer">
            <button class="btn btn-secondary" type="button" data-dismiss="modal">Batal</button>
            <button type="submit" class="btn btn-primary" id="delPaymentBtn">Hapus</button>
          </div>
        </div>
      </form>
    </div>
  </div>

  <!-- Order Detail Modal -->
  <div class="modal fade" id="order-detail-modal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">

      <form action="<?= base_url('processor/pesanan_pcsr/simpan_dari_invoice'); ?>" method="post">

        <input type="hidden" name="order[order_id]" id="order-id" value="">
        <input type="hidden" name="source-url" id="source-url" value="<?= $this->uri->uri_string(); ?>">

        <div class="modal-content">

          <div class="modal-header">
            <h5 class="modal-title">Detail Pesanan</h5>
            <button type="button" class="close" data-dismiss="modal">
              <span>&times;</span>
            </button>
          </div>

          <div class="modal-body">

            <div class="form-row">
              <div class="form-group col">
                <label for="description"><small>Judul bordiran</small></label>
                <input type="text" name="order[description]" id="description" class="form-control description" value="">
              </div>
            </div>

            <div class="form-row">

              <div class="form-group col">
                <label for="received-date"><small>Tanggal Pesan</small></label>
                <input class="form-control" type="date" name="order[received_date]" id="received-date" value="">
              </div>

              <div class="form-group col">
                <label for="received-date"><small>Tanggal Diambil</small></label>
                <input class="form-control" type="date" name="order[required_date]" id="required-date" value="">
              </div>

            </div>

            <div class="form-row">

              <div class="form-group col">

                <label for="item"><small>Jenis barang</small></label>
                <select name="order[item_id]" id="item" data-is-selected="false">

                  <option value="">Pilih...</option>

                  <?php $items = $this->pesanan_model->get_all_items(); ?>

                  <?php foreach ($items as $item) : ?>

                    <option value="<?= $item['item_id']; ?>">
                      <?= $item['item_name'] ?>
                    </option>

                  <?php endforeach; ?>

                </select>

              </div>

              <div class="form-group col">

                <label for="position"><small>Posisi yang diinginkan</small></label>
                <select name="order[position_id]" id="position" class="custom-select position">
                  <option value="">Pilih...</option>

                  <?php $positions = $this->pesanan_model->get_item_position_pairs(); ?>

                  <?php foreach ($positions as $position) : ?>

                    <option value="<?= $position['position_id']; ?>">
                      <?= $position['name'] ?>
                    </option>

                  <?php endforeach; ?>


                </select>

              </div>

            </div>

            <div class="form-row">

              <div class="form-group col">
                <label for="dimension"><small>Dimensi</small></label>
                <input type="text" name="order[dimension]" id="dimension" class="form-control dimension number" value="">
              </div>
              <div class="form-group col">
                <label for=""><small>Warna</small></label>
                <input type="text" name="order[color]" id="color" class="form-control color number" value="">
              </div>
              <div class="form-group col">
                <label for=""><small>Bahan</small></label>
                <input type="text" name="order[material]" id="material" class="form-control material number" value="">
              </div>

            </div>

            <div class="form-row">

              <div class="form-group col">
                <label for="quantity"><small>Kuantitas</small></label>
                <input type="text" name="order[quantity]" id="quantity" class="form-control quantity number text-right" value="">
              </div>

              <div class="form-group col">
                <label for=""><small>Harga</small></label>
                <input type="text" name="order[price]" id="price" class="form-control price number text-right" value="">
              </div>

              <div class="form-group col">
                <label for=""><small>Total</small></label>
                <input type="text" name="" id="amount" class="form-control amount" value="" readonly>
              </div>

            </div>

            <div class="form-row">
              <div class="form-group col">
                <label for="note"><small>Catatan</small></label>
                <textarea name="order[note]" id="note" class="form-control" style="font-size:13px"></textarea>
              </div>
            </div>

          </div>

          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
            <button type="submit" class="btn btn-primary">Simpan</button>
          </div>

        </div>
      </form>

    </div>
  </div>

  <!-- Order Progress Modal -->
  <div class="modal fade" id="order-process-modal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title"></h5>
          <button type="button" class="close" data-dismiss="modal">
            <span>&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <h4 class="small font-weight-bold">Desain <span class="float-right" id="design-progress-bar-title"></span></h4>
          <div class="progress mb-4">
            <div class="progress-bar bg-danger" id="design-progress-bar" role="progressbar" data-toggle="tooltip" title=""></div>
          </div>
          <h4 class="small font-weight-bold">Bordir <span class="float-right" id="embro-progress-bar-title"></span></h4>
          <div class="progress mb-4">
            <div class="progress-bar bg-warning" id="embro-progress-bar" role="progressbar" data-toggle="tooltip" title=""></div>
          </div>
          <h4 class="small font-weight-bold">Finishing <span class="float-right" id="finishing-progress-bar-title"></span></h4>
          <div class="progress mb-4">
            <div class="progress-bar" id="finishing-progress-bar" role="progressbar" data-toggle="tooltip" title=""></div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
        </div>
      </div>
    </div>
  </div>

  <!-- Mark as Modal-->
  <div class="modal fade" id="update-process-modal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">

      <form action="<?= base_url('processor/pesanan_pcsr/tandai_sebagai'); ?>" method="post" id="update-process-form">

        <input type="hidden" name="order[order_id]" id="order-id" value="">
        <input type="hidden" name="redirect-here" value="<?= $this->uri->uri_string(); ?>">

        <div class="modal-content">

          <div class="modal-header">
            <h5 class="modal-title"></h5>
            <button class="close" type="button" data-dismiss="modal">
              <span>×</span>
            </button>
          </div>

          <div class="modal-body">

            <label for="process-status"><small>Tandai sebagai</small></label>
            <select name="order[process_status_id]" id="process-status" class="custom-select">

              <option value="">Pilih...</option>

              <?php $process_list = $this->db->get('process_status')->result_array(); ?>

              <?php foreach ($process_list as $status) : ?>
                <option value="<?= $status['process_status_id']; ?>">
                  <?= $status['name']; ?>
                </option>
              <?php endforeach; ?>

            </select>

          </div>

          <div class="modal-footer">
            <button class="btn btn-secondary" type="button" data-dismiss="modal">Batal</button>
            <button type="submit" class="btn btn-primary" id="update-process-btn">Perbarui</button>
          </div>

        </div>

      </form>

    </div>
  </div>

  <!-- Operator Price Modal-->
  <div class="modal fade" id="operator-price-modal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">

      <form action="<?= base_url('processor/produksi_pcsr/atur_produksi'); ?>" method="post" id="set-operator-price-form">

        <input type="hidden" name="production[production_id]" id="production-id" value="">
        <input type="hidden" name="production[order_id]" id="order-id" value="">
        <input type="hidden" name="input-src" value="<?= current_url(); ?>">

        <div class="modal-content">

          <div class="modal-header">
            <h5 class="modal-title"></h5>
            <button class="close" type="button" data-dismiss="modal">
              <span>×</span>
            </button>
          </div>

          <div class="modal-body">

            <div class="form-group">
              <label for="labor-price"><small><span id="original-price"></span></small></label>
              <input type="text" name="production[labor_price]" id="labor-price" class="form-control" value="">
            </div>

          </div>

          <div class="modal-footer">
            <button class="btn btn-secondary" type="button" data-dismiss="modal">Batal</button>
            <button type="submit" class="btn btn-primary" id="set-price-btn">Atur</button>
          </div>

        </div>

      </form>

    </div>
  </div>

  <!-- Upload Artwork Modal-->
  <div class="modal fade" id="upload-artwork-modal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">

      <form action="<?= base_url("processor/pesanan_pcsr/perbarui"); ?>" enctype="multipart/form-data" accept-charset="utf-8" method="post" id="set-operator-price-form">

        <input type="hidden" name="order[order_id]" id="order-id" value="">
        <input type="hidden" name="redirect-here" value="<?= $this->uri->uri_string(); ?>">

        <div class="modal-content">

          <div class="modal-header">
            <h5 class="modal-title"></h5>
            <button class="close" type="button" data-dismiss="modal">
              <span>×</span>
            </button>
          </div>

          <div class="modal-body">

            <div class="custom-file">
              <input type="file" name="image" class="custom-file-input" id="image">
              <label class="custom-file-label" for="customFile">Pilih file...</label>
            </div>

          </div>

          <div class="modal-footer">
            <button class="btn btn-secondary" type="button" data-dismiss="modal">Batal</button>
            <button type="submit" class="btn btn-primary" id="upload-order-btn">Unggah</button>
          </div>

        </div>

      </form>

    </div>
  </div>

  <script>
    const processCard = document.querySelector('#process');

    processCard.addEventListener('click', (e) => {

      let clickedEl = e.target;

      // Grab order-id value from order-id data attribute of the respective row
      let currentRow = clickedEl.closest('tr');
      let orderId = currentRow.dataset.orderId;

      if (clickedEl.matches('.status-mark-trigger')) {


        // Grab status data
        let statusId = clickedEl.dataset.statusId;
        let statusName = clickedEl.dataset.statusName;

        // Assign order-id value to the order-id hidden input in the mark-as-finished-modal
        document.querySelector('#mark-modal-order-id').value = orderId

        // Assign status data to the modal
        document.querySelector('#mark-as-modal .modal-body').innerHTML = `
        Anda ingin mengubah status pesanan ini menjadi <strong>${statusName}?</strong>
      `;

        document.querySelector('#status-btn').value = statusId;

      }

    });
  </script>

<?php endif; ?>