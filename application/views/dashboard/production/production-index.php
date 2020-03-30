<!-- Begin Page Content -->
<div class="container-fluid">

  <!-- Page Heading -->
  <h1 class="h3 mb-3 text-gray-800"><?= $title; ?></h1>

  <!-- embro List Table -->
  <div class="card shadow mb-4">
    <div class="card-body" id="production-table-card">
      <table class="table table-hover" id="embroLogTable">
        <thead class="thead-light">
          <tr>
            <th>Pesanan</th>
            <th>Operator</th>
            <th>Mesin</th>
            <th>Shift</th>
            <th>Mulai</th>
            <th>Sampai</th>
            <th class="text-right">Output</th>
            <th class="text-right">Harga</th>
            <th class="text-right">Nilai</th>
            <th>#</th>
          </tr>
        </thead>
        <tfoot>
          <tr>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            <th colspan="2">
              <div class="text-right">Sub Total:</div>
              <div class="text-right">Total:</div>
            </th>
            <th></th>
            <th></th>
          </tr>
        </tfoot>
      </table>
    </div>
  </div>

</div>

<!-- deleteOutputModal -->
<div class="modal fade" id="del-output-modal" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <form action="<?= base_url('action/produksi_action/hapus_output_embro'); ?>" method="post" id="del-output-form">
      <input type="hidden" name="output[output_embro_id]" id="output-id" value="">
      <input type="hidden" name="referrer" value="<?= current_url(); ?>">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Yakin akan menghapus?</h5>
          <button class="close" type="button" data-dismiss="modal">
            <span aria-hidden="true">×</span>
          </button>
        </div>
        <div class="modal-body">Klik "Hapus" jika Anda yakin untuk menghapus catatan output ini.</div>
        <div class="modal-footer">
          <button class="btn btn-secondary" type="button" data-dismiss="modal">Batal</button>
          <button type="submit" class="btn btn-primary" id="del-output-btn">Hapus</button>
        </div>
      </div>
    </form>
  </div>
</div>