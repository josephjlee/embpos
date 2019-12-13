<!-- Begin Page Content -->
<div class="container-fluid">

  <!-- Page Heading -->
  <div class="row mb-2">
    <div class="col d-flex justify-content-between align-items-center">
      <h1 class="h3 text-gray-800 mr-auto"><?= $title; ?></h1>
      <a href="" class="badge badge-primary py-2 px-3 text-uppercase mr-2 shadow" id="input-cust-trigger" data-toggle="modal" data-target="#addCreditorModal">Tambah Kreditur</a>
      <a href="" class="badge badge-danger py-2 px-3 text-uppercase shadow" id="add-debt-trigger" data-toggle="modal" data-target="#debtEditorModal">Catat Hutang</a>
    </div>
  </div>

  <div class="row">

    <!-- Total Hutang -->

    <?php $most_buy = $this->pelanggan_model->get_most_buy_by_month(date('m')); ?>

    <div class="col-xl-3 col-md-6 mb-4">
      <div class="card border-left-info shadow h-100 py-2">
        <div class="card-body">
          <div class="row no-gutters align-items-center">
            <div class="col mr-2">
              <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Total Hutang (<?= date('M') ?>)</div>
              <div class="h5 mb-0 font-weight-bold text-gray-800">Rp<?= '35.000.000'; ?></div>
            </div>
            <div class="col-auto">
              <i class="fas fa-hand-holding-usd fa-2x text-gray-300"></i>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Total Creditor -->

    <?php $total_customer = $this->pelanggan_model->get_total_customer(); ?>

    <div class="col-xl-3 col-md-6 mb-4">
      <div class="card border-left-primary shadow h-100 py-2">
        <div class="card-body">
          <div class="row no-gutters align-items-center">
            <div class="col mr-2">
              <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Kreditur Aktif (<?= date('M') ?>)</div>
              <div class="h5 mb-0 font-weight-bold text-gray-800"><?= '7'; ?></div>
            </div>
            <div class="col-auto">
              <i class="fas fa-user-tie fa-2x text-gray-300"></i>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Kreditur Terbesar -->

    <?php $most_order = $this->pelanggan_model->get_most_order_by_month(date('m')); ?>

    <div class="col-xl-3 col-md-6 mb-4">
      <div class="card border-left-success shadow h-100 py-2">
        <div class="card-body">
          <div class="row no-gutters align-items-center">
            <div class="col mr-2">
              <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Kreditur Terbesar (<?= date('M') ?>)</div>
              <div class="h5 mb-0 font-weight-bold text-gray-800"><?= 'BRI'; ?></div>
            </div>
            <div class="col-auto">
              <i class="fas fa-user-secret fa-2x text-gray-300"></i>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Tenggat Terdekat -->

    <?php $the_most_valuable = $this->pelanggan_model->get_most_valueable_by_month(date('m')); ?>

    <div class="col-xl-3 col-md-6 mb-4">
      <div class="card border-left-warning shadow h-100 py-2">
        <div class="card-body">
          <div class="row no-gutters align-items-center">
            <div class="col mr-2">
              <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Tenggat Terdekat (<?= date('M') ?>)</div>
              <div class="h5 mb-0 font-weight-bold text-gray-800">Rp<?= '275.000'; ?></div>
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
      <table class="table table-hover" id="dataTable">
        <thead class="thead-light">
          <tr>
            <th scope="col">ID</th>
            <th scope="col">Kreditur</th>
            <th scope="col">Description</th>
            <th scope="col">Nominal</th>
            <th scope="col">Dimulai</th>
            <th scope="col">Tenggat</th>
            <th scope="col">Terbayar</th>
            <th scope="col" style="width: 6px !important">#</th>
          </tr>
        </thead>
      </table>
    </div>
  </div>

  <!-- Creditor Editor Modal -->
  <div class="modal fade" id="addCreditorModal" tabindex="-1" role="dialog">

    <div class="modal-dialog" role="document">

      <div class="modal-content">

        <form action="<?= base_url('processor/ajax/keuangan_ajax/tambah_kreditur'); ?>" method="post" id="creditorForm">

          <input type="hidden" name="request-source" id="req-src" value="<?= $this->uri->uri_string() ?>">
          <input type="hidden" name="creditor[creditor_id]" id="cust_id" value="">

          <div class="modal-header">
            <h5 class="modal-title">Tambah Kreditor Baru</h5>
            <button type="button" class="close" data-dismiss="modal">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>

          <div class="modal-body">

            <div class="form-group">
              <label for="creditor_name">Nama lengkap</label>
              <input type="text" name="creditor[name]" id="creditor_name" class="form-control mb-2">
            </div>

            <div class="form-row">
              <div class="form-group col">
                <label for="creditor_address">Alamat</label>
                <input type="text" name="creditor[address]" id="creditor_address" class="form-control">
              </div>
              <div class="form-group col">
                <label for="creditor_phone">Ponsel</label>
                <input type="tel" name="creditor[phone]" id="creditor_phone" class="form-control">
              </div>
            </div>

          </div>

          <div class="modal-footer">
            <button type="button" class="btn btn-secondary rounded" data-dismiss="modal">Close</button>
            <input type="submit" name="save_creditor_data" class="btn btn-primary" id="save-creditor" value="Simpan data">
          </div>

        </form>
      </div>
    </div>
  </div>

  <!-- Debt Editor Modal -->
  <div class="modal fade" id="debtEditorModal" tabindex="-1" role="dialog">

    <div class="modal-dialog" role="document">

      <div class="modal-content">

        <form id="debtForm">

          <input type="hidden" name="request-source" id="req-src" value="<?= $this->uri->uri_string() ?>">
          <input type="hidden" name="debt[debt_id]" id="debt-id" value="">

          <div class="modal-header">
            <h5 class="modal-title">Catat Hutang Baru</h5>
            <button type="button" class="close" data-dismiss="modal">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>

          <div class="modal-body">

            <div class="form-group">
              <label for="description"><small>Judul</small></label>
              <input type="text" name="debt[description]" id="description" class="form-control description" value="<?= $debt['description'] ?? '' ?>">
            </div>

            <div class="form-row">

              <div class="form-group col">
                <label for="debt-date"><small>Tanggal transaksi</small></label>
                <input type="date" name="debt[transaction_date]" id="debt-date" class="form-control" value="<?= isset($debt_detail['transaction_date']) ? date('Y-m-d', strtotime($debt_detail['transaction_date'])) : ''; ?>">
              </div>

              <div class="form-group col">
                <label for="payment-date"><small>Tanggal pembayaran</small></label>
                <input type="date" name="debt[payment_date]" id="payment-date" class="form-control" value="<?= isset($debt_detail['payment_date']) ? date('Y-m-d', strtotime($debt_detail['payment_date'])) : ''; ?>">
              </div>

            </div>

            <?php $creditors = $this->kreditur_model->list_all_creditors(); ?>

            <div class="form-row">
              <div class="form-group col">
                <label for="creditors"><small>Judul</small></label>
                <select name="debt[creditor_id]" id="creditors" class="custom-select">
                  <option value="">Pilih Kreditur</option>

                  <?php if (isset($debt_detail['creditor_id'])) : ?>

                    <?php foreach ($creditors as $creditor) : ?>
                      <option value="<?= $creditor['creditor_id']; ?>" <?= $creditor['creditor_id'] == $debt_detail['creditor_id'] ? 'selected' : ''; ?>><?= $creditor['name']; ?></option>
                    <?php endforeach; ?>

                  <?php else : ?>

                    <?php foreach ($creditors as $creditor) : ?>
                      <option value="<?= $creditor['creditor_id']; ?>"><?= $creditor['name']; ?></option>
                    <?php endforeach; ?>

                  <?php endif; ?>
                </select>
              </div>
              <div class="form-group col">
                <label for="amount"><small>Nominal</small></label>
                <input type="number" name="debt[amount]" id="amount" class="form-control amount" value="<?= $debt['amount'] ?? '' ?>">
              </div>
            </div>

            <div class="form-group">
              <label for="note"><small>Catatan</small></label>
              <textarea name="debt[note]" id="note" class="form-control" style="font-size:13px"><?= $debt['note'] ?? ''; ?></textarea>
            </div>

          </div>

          <div class="modal-footer">
            <button type="button" class="btn btn-secondary rounded" data-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-primary" id="save-debt">Simpan data</button>
          </div>

        </form>
      </div>
    </div>
  </div>

  <!-- Delete Debt Modal -->
  <div class="modal fade" id="delDebtModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
      <form action="<?= base_url('processor/pelanggan_pcsr/hapus_data'); ?>" method="post" id="delete-order-form">
        <input type="hidden" name="customer[customer_id]" id="cust_id" value="">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Yakin akan menghapus?</h5>
            <button class="close" type="button" data-dismiss="modal">
              <span aria-hidden="true">×</span>
            </button>
          </div>
          <div class="modal-body">Menghapus data pelanggan juga akan menghapus seluruh data pesanan yang pernah dibuatnya.</div>
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