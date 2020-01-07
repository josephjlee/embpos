<!-- Begin Page Content -->
<div class="container-fluid" id="vendor-index">

  <!-- Page Heading -->
  <div class="row mb-2">
    <div class="col d-flex justify-content-between align-items-center">
      <h1 class="h3 text-gray-800"><?= $title; ?></h1>
      <a href="" class="badge badge-primary py-2 px-3 text-uppercase" id="add-vendor-trigger" data-toggle="modal" data-target="#vendorEditorModal">+ Vendor</a>
    </div>
  </div>

  <!-- Vendor Table -->
  <div class="card shadow mb-4" id="vendor-table-card">
    <div class="card-body">
      <table class="table table-hover" id="vendorDataTable">
        <thead class="thead-light">
          <tr>
            <th scope="col">Nama</th>
            <th scope="col">Telepon</th>
            <th scope="col">Email</th>
            <th scope="col">Alamat</th>
            <th scope="col">Sedia</th>
            <th scope="col">Nilai</th>
            <th scope="col">#</th>
          </tr>
        </thead>
      </table>
    </div>
  </div>

  <!-- Add/Update Vendor Modal -->
  <div class="modal fade" id="vendorEditorModal" tabindex="-1" role="dialog">

    <div class="modal-dialog" role="document">

      <div class="modal-content">

        <form id="vendorForm">

          <input type="hidden" name="vendor[vendor_id]" id="vendor-id" value="">

          <div class="modal-header">
            <h5 class="modal-title"></h5>
            <button type="button" class="close" data-dismiss="modal">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>

          <div class="modal-body">
            <div class="mb-4">
              <div class="form-row">
                <div class="form-group col">
                  <label for="name">Nama lengkap</label>
                  <input type="text" name="vendor[name]" id="name" class="form-control mb-2">
                </div>
                <div class="form-group col">
                  <label for="selling">Menyediakan</label>
                  <input type="text" name="vendor[selling]" id="selling" class="form-control mb-2">
                </div>
              </div>
              <div class="form-group">
                <label for="address">Alamat</label>
                <input type="text" name="vendor[address]" id="address" class="form-control">
              </div>
              <div class="form-row">
                <div class="form-group col">
                  <label for="phone">Ponsel</label>
                  <input type="tel" name="vendor[phone]" id="phone" class="form-control">
                </div>
                <div class="form-group col">
                  <label for="email">Email</label>
                  <input type="email" name="vendor[email]" id="email" class="form-control">
                </div>
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

  <!-- Delete Vendor Modal -->
  <div class="modal fade" id="delVendorModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
      <form id="del-vendor-form">
        <input type="hidden" name="vendor[vendor_id]" id="vendor-id" value="">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Yakin akan menghapus?</h5>
            <button class="close" type="button" data-dismiss="modal">
              <span aria-hidden="true">×</span>
            </button>
          </div>
          <div class="modal-body">Menghapus vendor juga akan menghapus seluruh data pengeluaran atas nama vendor yang bersangkutan.</div>
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