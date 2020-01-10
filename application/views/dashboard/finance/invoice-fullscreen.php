<!-- Begin Page Content -->
<div class="container-fluid invoice-detail">

  <!-- Title -->
  <div class="row mb-2">
    <div class="col">
      <h1 class="h3 text-gray-800 mr-2"><?= $title; ?></h1>
    </div>
  </div>

  <!-- Invoice Section -->
  <div class="row mb-4">

    <!-- Invoice Card - Dekstop Version -->
    <div class="col-8">

      <!-- Version 1 -->
      <div class="card shadow mb-4">

        <div class="card-body pt-2">

          <div class="row bg-primary text-white py-3 mb-4" style="border-top-left-radius: 0.35rem;border-top-right-radius: 0.35rem">

            <div class="col d-flex justify-content-between align-items-center">
              <h4 class="text-uppercase font-weight-bold my-0">Swasti Bordir</h4>
              <p class="my-0">Penerbitan Invoice</p>
            </div>

          </div>

          <div class="row mb-2">
            <div class="col">
              <p class="font-weight-bold">
                <span>Ditagihkan Kepada</span>
              </p>
            </div>
          </div>

          <div class="row justify-content-between mb-4">

            <!-- Customer Detail -->
            <div class="col-6" style="color:#283044">
              <div class="bill-name mb-2">
                <p><?= $customer['customer_name']; ?> <?= $customer['customer_company'] ? "({$customer['customer_company']})" : ""; ?></p>
                <p><?= $customer['customer_phone'] ? "({$customer['customer_phone']})" : ''; ?></p>
              </div>
              <div class="bill-to">
                <p><?= $customer['customer_address']; ?></p>
              </div>
            </div>

            <!-- Invoice Meta Table -->
            <div class="col-6">
              <table class="table order-preview-table table-borderless">
                <tbody style="font-size:14px">
                  <tr>
                    <th scope="col" class="text-right">
                      Nomor Invoice:
                    </th>
                    <td>
                      <span><?= $invoice_detail['invoice_number']; ?></span>
                    </td>
                  </tr>
                  <tr>
                    <th scope="col" class="text-right">
                      Tanggal Penerbitan:
                    </th>
                    <td>
                      <span id="order-date-display"><?= date('j F Y', strtotime($invoice_detail['invoice_date'])); ?></span>
                    </td>
                  </tr>
                  <tr>
                    <th scope=" col" class="text-right">
                      Tenggat Pelunasan:
                    </th>
                    <td>
                      <span id="payment-date-display"><?= date('j F Y', strtotime($invoice_detail['payment_date'])); ?></span>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>

          </div>

          <div class=" row mb-4">

            <div class="col">

              <table class="table order-detail-table">

                <thead class="thead-light">
                  <tr>
                    <th scope="col">Nama Barang</th>
                    <th scope="col" class="text-right">Kuantitas</th>
                    <th scope="col" class="text-right price-column">Harga</th>
                    <th style="width:22%" scope="col" class="text-right pr-0">Jumlah</th>
                    <th style="width:3%"></th>
                  </tr>
                </thead>

                <tbody id="item-list">

                  <?php foreach ($order_details as $order_detail) : ?>

                    <tr>
                      <td id="order-item-col" style="width:40%">
                        <p><?= $order_detail['description']; ?></p>
                      </td>
                      <td id="order-qty-col" class="text-right">
                        <p><?= $order_detail['quantity']; ?></p>
                      </td>
                      <td id="order-price-col" class="text-right align-middle"">
                                                    <p>Rp<?= number_format($order_detail['price'], 2, ',', '.'); ?></p>
                                                  </td>
                                                  <td id=" order-amount-col" class="text-right align-middle pr-0">
                        <p>Rp<?= number_format($order_detail['amount'], 2, ',', '.'); ?></p>
                      </td>
                    </tr>

                  <?php endforeach; ?>

                  <?php foreach ($product_details as $product) : ?>

                    <tr>
                      <td id="product-items-col" style="width:40%">
                        <p><?= $product['title']; ?></p>
                      </td>
                      <td id="product-qty-col" class="text-right">
                        <p><?= moneyStr($product['quantity']); ?></p>
                      </td>
                      <td id="product-price-col" class="text-right">
                        <p>Rp<?= moneyStr($product['price']); ?>,00</p>
                      </td>
                      <td id="product-amount-col" class="text-right pr-0">
                        <p>Rp<?= moneyStr($product['amount']); ?>,00</p>
                      </td>
                    </tr>

                  <?php endforeach; ?>

                </tbody>

                <tfoot>

                  <tr>
                    <td colspan="2" class="font-weight-bold">
                      Catatan
                    </td>
                    <th scope="col" class="text-right">
                      Sub Total:
                    </th>
                    <td class="text-left pr-0 d-flex justify-content-between">
                      <p>Rp</p>
                      <p><?= number_format($invoice_detail['sub_total'], 2, ',', '.'); ?></p>
                    </td>
                  </tr>
                  <tr>
                    <td colspan="2" rowspan="5">
                      <div class="w-100 notes pr-5"><?= $invoice_detail['note']; ?></div>
                    </td>
                    <th scope="col" class="text-right">
                      Diskon:
                    </th>
                    <td class="text-left pr-0 d-flex justify-content-between">
                      <p>Rp</p>
                      <p><?= number_format($invoice_detail['discount'], 2, ',', '.'); ?></p>
                    </td>
                  </tr>
                  <tr>
                    <th scope="col" class="text-right ">Total Tagihan:</th>
                    <td class="text-left pr-0 d-flex justify-content-between mb-2">
                      <p>Rp</p>
                      <p><?= number_format($invoice_detail['total_due'], 2, ',', '.'); ?></p>
                    </td>
                  </tr>
                  <tr>
                    <th scope="col" class="text-right">Pembayaran:</th>
                    <td class="text-left pr-0 d-flex justify-content-between">
                      <p>Rp</p>
                      <p id="paid"><?= number_format($invoice_detail['paid'], 2, ',', '.'); ?></p>
                    </td>
                  </tr>
                  <tr>
                    <th scope="col" class="text-right">Sisa Tagihan:</th>
                    <td class="text-left font-weight-bold pr-0 d-flex justify-content-between">
                      <p>Rp</p>
                      <p id="payment-due-bottom"><?= number_format($invoice_detail['invoice_due'], 2, ',', '.'); ?></p>
                    </td>
                  </tr>

                </tfoot>

              </table>

            </div>
          </div>

          <hr>

          <?php $company_profile = $this->db->get('company_profile')->row_array(); ?>

          <!-- Invoice Footer -->
          <div class="row">

            <div class="col">

              <p><small><i class="fas fa-fw fa-mobile-alt"></i> <?= $company_profile['mobile']; ?></small></p>
              <p><small><i class="fas fa-fw fa-envelope"></i> <?= $company_profile['email']; ?></small></p>
              <p><small><i class="fas fa-fw fa-building"></i> Jl. Juwet, Lumajang, Jawa Timur</small></p>

            </div>

            <div class="col text-right">
              <p><small><strong>BCA:</strong> 125-06-222-83</small></p>
              <p><small><strong>BTPN:</strong> 90011014473</small></p>
              <p><small><strong>Mandiri:</strong> 143-00-189189-10</small></p>
            </div>

          </div>

        </div>

      </div>

    </div>

  </div>

</div>
<!-- /.container-fluid -->