<!-- Begin Page Content -->
<div class="container-fluid">

  <!-- Page Heading -->
  <div class="d-flex align-items-center justify-content-between mb-3">
    <h1 class="h3 text-gray-800"><?= $title; ?></h1>
    <div>
      <select id="filter-select" class="custom-select">
        <option value="">semua</option>
        <option value="tunai">tunai</option>
        <option value="transfer">transfer</option>
        <option value="mandiri">mandiri</option>
        <option value="bca">bca</option>
      </select>
    </div>
  </div>

  <div class="row">

    <!-- This month payment -->

    <?php $total_payment = $this->pembayaran_model->get_total_payment_by_month(date('m')); ?>

    <div class="col-xl-3 col-md-6 mb-4">
      <div class="card border-left-primary shadow h-100 py-2">
        <div class="card-body">
          <div class="row no-gutters align-items-center">
            <div class="col mr-2">
              <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Total Pembayaran (<?= date('M') ?>)</div>
              <div class="h5 mb-0 font-weight-bold text-gray-800">Rp<?= moneyStrDot($total_payment); ?></div>
            </div>
            <div class="col-auto">
              <i class="fas fa-wallet fa-2x text-gray-300"></i>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Total cash payment -->

    <?php $total_cash_payment = $this->pembayaran_model->get_total_payment_by_month_by_method(date('m'), 1); ?>

    <div class="col-xl-3 col-md-6 mb-4">
      <div class="card border-left-success shadow h-100 py-2">
        <div class="card-body">
          <div class="row no-gutters align-items-center">
            <div class="col mr-2">
              <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Bayar Tunai (<?= date('M') ?>)</div>
              <div class="h5 mb-0 font-weight-bold text-gray-800">Rp<?= moneyStrDot($total_cash_payment); ?></div>
            </div>
            <div class="col-auto">
              <i class="fas fa-money-bill fa-2x text-gray-300"></i>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Total bank transfer payment -->

    <?php
    $total_mandiri_transfer = $this->pembayaran_model->get_total_payment_by_month_by_method(date('m'), 2);
    $total_bri_transfer = $this->pembayaran_model->get_total_payment_by_month_by_method(date('m'), 3);
    $total_bca_transfer = $this->pembayaran_model->get_total_payment_by_month_by_method(date('m'), 4);
    $total_transfer_payment = $total_mandiri_transfer + $total_bri_transfer + $total_bca_transfer;
    ?>

    <div class="col-xl-3 col-md-6 mb-4">
      <div class="card border-left-info shadow h-100 py-2">
        <div class="card-body">
          <div class="row no-gutters align-items-center">
            <div class="col mr-2">
              <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Transfer Bank (<?= date('M') ?>)</div>
              <div class="h5 mb-0 font-weight-bold text-gray-800">Rp<?= moneyStrDot($total_transfer_payment); ?></div>
            </div>
            <div class="col-auto">
              <i class="far fa-credit-card fa-2x text-gray-300"></i>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Giro Payment -->

    <?php $total_giro_payment = $this->pembayaran_model->get_total_payment_by_month_by_method(date('m'), 5); ?>

    <div class="col-xl-3 col-md-6 mb-4">
      <div class="card border-left-warning shadow h-100 py-2">
        <div class="card-body">
          <div class="row no-gutters align-items-center">
            <div class="col mr-2">
              <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Bilyet Giro (<?= date('M') ?>)</div>
              <div class="h5 mb-0 font-weight-bold text-gray-800">Rp<?= moneyStrDot($total_giro_payment); ?></div>
            </div>
            <div class="col-auto">
              <i class="fas fa-money-check fa-2x text-gray-300"></i>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Payments Table -->
  <div class="card shadow mb-4">

    <div class="card-body" id="payment-table-card">

      <table class="table table-hover" id="dataTable">

        <thead class="thead-light">
          <tr>
            <th scope="col">ID</th>
            <th scope="col">Tanggal</th>
            <th scope="col">Pelanggan</th>
            <th scope="col">Metode</th>
            <th scope="col">Nominal</th>
            <th scope="col">Invoice</th>
          </tr>
        </thead>

        <tbody style="font-size:14px">

          <?php foreach ($payments as $payment) : ?>
            <tr data-payment-id="<?= $payment['payment_id']; ?>" data-order-id="<?= $payment['invoice_id']; ?>">
              <td>
                <?= $payment['payment_id']; ?>
              </td>
              <td data-sort="<?= strtotime($payment['payment_date']); ?>">
                <?= date('d/m/Y', strtotime($payment['payment_date'])); ?>
              </td>
              <td>
                <?= $payment['customer_name']; ?>
              </td>
              <td>
                <?= $payment['payment_name']; ?>
              </td>
              <td data-sort="<?= $payment['payment_amount']; ?>">
                <?= moneyStrDot($payment['payment_amount']) . ',00'; ?>
              </td>
              <td data-sort="<?= $payment['order_number']; ?>">
                <a href="<?= base_url('invoice/sunting/') . $payment['order_number']; ?>">INV-<?= $payment['order_number']; ?></a>
              </td>
            </tr>
          <?php endforeach; ?>

        </tbody>

      </table>

    </div>

  </div>

</div>
<!-- /.container-fluid -->