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