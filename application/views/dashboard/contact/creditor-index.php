<!-- Begin Page Content -->
<div class="container-fluid" id="creditor-index">

  <!-- Page Heading -->
  <div class="row mb-2">
    <div class="col d-flex justify-content-between align-items-center">
      <h1 class="h3 text-gray-800"><?= $title; ?></h1>
      <a href="" class="badge badge-primary py-2 px-3 text-uppercase" id="add-creditor-trigger" data-toggle="modal" data-target="#creditorEditorModal">+ Kreditur</a>
    </div>
  </div>

  <!-- Creditor Table -->
  <div class="card shadow mb-4" id="creditor-table-card">
    <div class="card-body">
      <table class="table table-hover" id="creditorDataTable">
        <thead class="thead-light">
          <tr>
            <th scope="col">Nama</th>
            <th scope="col">Telepon</th>
            <th scope="col">Email</th>
            <th scope="col">Alamat</th>
            <th scope="col">Hutang</th>
            <th scope="col">Bayar</th>
            <th scope="col">Tagihan</th>
            <th scope="col" style="width: 6px !important">#</th>
          </tr>
        </thead>
      </table>
    </div>
  </div>

  <!-- Add/Update Creditor Modal -->
  <div class="modal fade" id="creditorEditorModal" tabindex="-1" role="dialog">

    <div class="modal-dialog" role="document">

      <div class="modal-content">

        <form id="creditorForm">

          <input type="hidden" name="creditor[creditor_id]" id="creditor-id" value="">

          <div class="modal-header">
            <h5 class="modal-title"></h5>
            <button type="button" class="close" data-dismiss="modal">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>

          <div class="modal-body">
            <div class="form-group">
              <label for="name">Nama lengkap</label>
              <input type="text" name="creditor[name]" id="name" class="form-control">
            </div>
            <div class="form-group">
              <label for="address">Alamat</label>
              <input type="text" name="creditor[address]" id="address" class="form-control">
            </div>
            <div class="form-row">
              <div class="form-group col">
                <label for="phone">Ponsel</label>
                <input type="tel" name="creditor[phone]" id="phone" class="form-control">
              </div>
              <div class="form-group col">
                <label for="email">Email</label>
                <input type="email" name="creditor[email]" id="email" class="form-control">
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

  <!-- Delete Creditor Modal -->
  <div class="modal fade" id="delCreditorModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
      <form id="del-creditor-form">
        <input type="hidden" name="creditor[creditor_id]" id="creditor-id" value="">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Yakin akan menghapus?</h5>
            <button class="close" type="button" data-dismiss="modal">
              <span aria-hidden="true">×</span>
            </button>
          </div>
          <div class="modal-body">Menghapus kreditur juga akan menghapus seluruh data hutang atas nama kreditur yang bersangkutan.</div>
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