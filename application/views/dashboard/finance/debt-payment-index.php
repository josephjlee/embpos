<!-- Begin Page Content -->
<div class="container-fluid" id="debt-payment-index">

  <!-- Page Heading -->
  <div class=" row mb-2">
    <div class="col d-flex justify-content-between align-items-center">
      <h1 class="h3 text-gray-800 mr-auto"><?= $title; ?></h1>
    </div>
  </div>

  <div class="row mb-4">

    <!-- Total Hutang -->

    <?php $total_debt_due = $this->keuangan_model->get_total_debt_due(); ?>

    <div class="col-xl-3 col-md-6">
      <div class="card border-left-info shadow h-100 py-2">
        <div class="card-body">
          <div class="row no-gutters align-items-center">
            <div class="col mr-2">
              <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Total Hutang (<?= date('M') ?>)</div>
              <div class="h5 mb-0 font-weight-bold text-gray-800">Rp<?= moneyStrDot($total_debt_due); ?></div>
            </div>
            <div class="col-auto">
              <i class="fas fa-hand-holding-usd fa-2x text-gray-300"></i>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Total Pembayaran -->

    <?php $active_creditors = $this->keuangan_model->count_active_creditor(); ?>

    <div class="col-xl-3 col-md-6">
      <div class="card border-left-primary shadow h-100 py-2">
        <div class="card-body">
          <div class="row no-gutters align-items-center">
            <div class="col mr-2">
              <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Total Pembayaran (<?= date('M') ?>)</div>
              <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $active_creditors; ?></div>
            </div>
            <div class="col-auto">
              <i class="fas fa-money-bill-wave fa-2x text-gray-300"></i>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Pembayaran Terbesar -->

    <?php $biggest_creditor = $this->keuangan_model->get_the_biggest_creditor(); ?>

    <div class="col-xl-3 col-md-6">
      <div class="card border-left-success shadow h-100 py-2">
        <div class="card-body">
          <div class="row no-gutters align-items-center">
            <div class="col mr-2">
              <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Pmbayaran Trbesar (<?= date('M') ?>)</div>
              <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $biggest_creditor; ?></div>
            </div>
            <div class="col-auto">
              <i class="fas fa-star fa-2x text-gray-300"></i>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Tenggat Terdekat -->

    <?php

    $the_nearest_due = $this->keuangan_model->get_the_nearest_debt_due();

    if ($the_nearest_due != '-') {
      $the_nearest_due['payment_date'] = date('d-M-y', strtotime($the_nearest_due['payment_date']));
    }

    ?>

    <div class="col-xl-3 col-md-6">
      <div class="card border-left-warning shadow h-100 py-2">
        <div class="card-body">
          <div class="row no-gutters align-items-center">
            <div class="col mr-2" data-toggle="tooltip">
              <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Tenggat Terdekat (<?= date('M') ?>)</div>
              <?php if ($the_nearest_due != '-') : ?>
                <div class="h5 mb-0 font-weight-bold text-gray-800" data-placement="top" title="<?= "{$the_nearest_due['description']} | {$the_nearest_due['payment_date']}"; ?>">Rp<?= moneyStrDot($the_nearest_due['amount']); ?></div>
              <?php else : ?>
                <h5>-</h5>
              <?php endif; ?>
            </div>
            <div class="col-auto">
              <i class="fas fa-exclamation-triangle fa-2x text-gray-300"></i>
            </div>
          </div>
        </div>
      </div>
    </div>

  </div>

  <!-- Debt Table -->
  <div class="card shadow mb-4" id="debt-table-card">
    <div class="card-body">
      <table class="table table-hover" id="debtPaymentDataTable">
        <thead class="thead-light">
          <tr>
            <th scope="col">ID</th>
            <th scope="col">Tanggal</th>
            <th scope="col">Hutang</th>
            <th scope="col">Kreditur</th>
            <th scope="col">Nominal</th>
            <th scope="col">Metode</th>
            <th scope="col" style="width: 6px !important">#</th>
          </tr>
        </thead>
      </table>
    </div>
  </div>

  <!-- Vendor Editor Modal -->
  <div class="modal fade" id="addVendorModal" tabindex="-1" role="dialog">

    <div class="modal-dialog" role="document">

      <div class="modal-content">

        <form id="vendorForm">

          <input type="hidden" name="vendor[vendor_id]" id="vendor_id" value="">

          <div class="modal-header">
            <h5 class="modal-title">Tambah Vendor Baru</h5>
            <button type="button" class="close" data-dismiss="modal">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>

          <div class="modal-body">

            <div class="form-group">
              <label for="vendor_name">Nama lengkap</label>
              <input type="text" name="vendor[name]" id="vendor_name" class="form-control mb-2" required>
            </div>

            <div class="form-row">
              <div class="form-group col">
                <label for="vendor_address">Alamat</label>
                <input type="text" name="vendor[address]" id="vendor_address" class="form-control">
              </div>

              <div class="form-group col">
                <label for="vendor_phone">Ponsel</label>
                <input type="tel" name="vendor[phone]" id="vendor_phone" class="form-control" required>
              </div>
            </div>

          </div>

          <div class="modal-footer">
            <button type="button" class="btn btn-secondary rounded" data-dismiss="modal">Close</button>
            <input type="submit" name="save_vendor_data" class="btn btn-primary" id="save-vendor" value="Simpan data">
          </div>

        </form>
      </div>
    </div>
  </div>

  <!-- DebtPayment Editor Modal -->
  <div class="modal fade" id="debtPaymentEditorModal" tabindex="-1" role="dialog">

    <div class="modal-dialog" role="document">

      <form action="" method="post" id="debtPaymentForm">

        <input type="hidden" name="debt_payment[debt_payment_id]" id="debt-payment-id" value="">

        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Sunting Pembayaran Hutang</h5>
            <button type="button" class="close" data-dismiss="modal">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>

          <div class="modal-body">

            <div class="form-group">
              <label for="modal-payment-amount"><small>Nominal</small></label>
              <input type="number" name="debt_payment[amount]" id="debt-payment-amount" class="form-control" placeholder="0" value="">
            </div>

            <div class="form-group">
              <label for="update-payment-method"><small>Metode Pembayaran</small></label>
              <select name="debt_payment[payment_method_id]" id="debt-payment-method" class="custom-select">
                <option value="">Pilih...</option>

                <?php $payment_methods = $this->pembayaran_model->get_payment_method(); ?>

                <?php foreach ($payment_methods as $payment_method) : ?>
                  <option value="<?= $payment_method['payment_method_id']; ?>"><?= $payment_method['name']; ?></option>
                <?php endforeach; ?>

              </select>
            </div>

            <div class="form-group">
              <label for="modal-debt-payment-date"><small>Tanggal</small></label>
              <input type="date" name="debt_payment[payment_date]" id="debt-payment-date" class="form-control">
            </div>

          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
            <button type="submit" class="btn btn-primary" id="save-debt-payment-btn">Simpan data</button>
          </div>
        </div>
      </form>

    </div>
  </div>

  <!-- Delete DebtPayment Modal -->
  <div class="modal fade" id="delDebtPaymentModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
      <form action="" method="post" id="delete-debt-payment-form">
        <input type="hidden" name="debt_payment[debt_payment_id]" id="debt-payment-id" value="">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Yakin akan menghapus?</h5>
            <button class="close" type="button" data-dismiss="modal">
              <span aria-hidden="true">×</span>
            </button>
          </div>
          <div class="modal-body">Pengeluaran ini akan dihapus dari database.</div>
          <div class="modal-footer">
            <button class="btn btn-secondary" type="button" data-dismiss="modal">Batal</button>
            <button type="submit" class="btn btn-primary">Hapus</button>
          </div>
        </div>
      </form>
    </div>
  </div>

</div>
<!-- /.container-fluid -->