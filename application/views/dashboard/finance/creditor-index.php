<!-- Begin Page Content -->
<div class="container-fluid">

  <!-- Page Heading -->
  <div class="row mb-2">
    <div class="col d-flex justify-content-between align-items-center">
      <h1 class="h3 text-gray-800 mr-auto"><?= $title; ?></h1>
      <a href="" class="badge badge-primary py-2 px-3 text-uppercase mr-2 shadow" id="input-cust-trigger" data-toggle="modal" data-target="#addCreditorModal">Tambah Kreditur</a>
    </div>
  </div>

  <!-- Creditor Card -->
  <?php if (!$creditors) : ?>
    <div class="row">
      <div class="col-6">
        <div class="card shadow h-100">
          <div class="card-body">
            <div class="row no-gutters align-items-center">
              <h5 class="font-weight-bold">Belum ada kreditur</h5>
            </div>
          </div>
        </div>
      </div>
    </div>
  <?php endif; ?>

  <div class="row">
    <?php foreach ($creditors as $creditor) : ?>
      <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-success shadow h-100">
          <div class="card-body">
            <div class="row no-gutters align-items-center">
              <div class="col mr-2">
                <div class="h5 font-weight-bold text-gray-800"><?= $creditor['name']; ?></div>
                <div class="text-xs font-weight-bold"><?= $creditor['address']; ?></div>
                <div class="text-xs font-weight-bold"><?= $creditor['phone']; ?></div>
              </div>
              <div class="col-auto">
                <i class="fas fa-user-tie fa-4x text-gray-300"></i>
              </div>
            </div>
          </div>
        </div>
      </div>
    <?php endforeach; ?>
  </div>

  <!-- Add/Update Creditor Modal -->
  <div class="modal fade" id="addCreditorModal" tabindex="-1" role="dialog">

    <div class="modal-dialog" role="document">

      <div class="modal-content">

        <form action="<?= base_url('processor/keuangan_pcsr/tambah_kreditur'); ?>" method="post" id="creditorForm">

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

  <!-- Delete creditor Modal -->
  <div class="modal fade" id="delcreditorModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
      <form action="<?= base_url('processor/pelanggan_pcsr/hapus_data'); ?>" method="post" id="delete-order-form">
        <input type="hidden" name="creditor[creditor_id]" id="cust_id" value="">
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