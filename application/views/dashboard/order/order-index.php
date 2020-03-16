<!-- Begin Page Content -->
<div class="container-fluid">

  <!-- Page Heading -->
  <div class="d-flex align-items-center justify-content-between mb-3">
    <h1 class="h3 text-gray-800"><?= $title; ?></h1>
    <div>
      <select id="filter-select" class="custom-select">
        <option value="">semua</option>
        <option value="antri">antri</option>
        <option value="didesain">didesain</option>
        <option value="dibordir">dibordir</option>
        <option value="dinishing">finishing</option>
        <option value="selesai">selesai</option>
      </select>
    </div>
  </div>

  <!-- Data Card -->
  <div class="row">

    <!-- Order Value -->

    <?php $order_value = $this->pesanan_model->get_order_value_by_month(date('m')); ?>

    <div class="col-xl-3 col-md-6 mb-4">
      <div class="card border-left-primary shadow h-100 py-2">
        <div class="card-body">
          <div class="row no-gutters align-items-center">
            <div class="col mr-2">
              <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Nilai Pesanan (<?= date('M') ?>)</div>
              <div class="h5 mb-0 font-weight-bold text-gray-800">Rp<?= moneyStrDot($order_value); ?></div>
            </div>
            <div class="col-auto">
              <i class="fas fa-coins fa-2x text-gray-300"></i>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Order Quantity -->

    <?php $order_quantity = $this->pesanan_model->get_order_quantity_by_month(date('m')); ?>

    <div class="col-xl-3 col-md-6 mb-4">
      <div class="card border-left-success shadow h-100 py-2">
        <div class="card-body">
          <div class="row no-gutters align-items-center">
            <div class="col mr-2">
              <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Jumlah Pesanan (<?= date('M') ?>)</div>
              <div class="h5 mb-0 font-weight-bold text-gray-800"><?= moneyStrDot($order_quantity); ?>pcs</div>
            </div>
            <div class="col-auto">
              <i class="fas fa-box-open fa-2x text-gray-300"></i>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Completed Order Card -->

    <div class="col-xl-3 col-md-6 mb-4">
      <div class="card border-left-info shadow h-100 py-2">
        <div class="card-body">
          <div class="row no-gutters align-items-center">
            <div class="col mr-2">
              <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Pesanan Selesai (<?= date('M') ?>)</div>
              <div class="h5 mb-0 font-weight-bold text-gray-800"><span id="complete-order-qty"></span>pcs</div>
            </div>
            <div class="col-auto">
              <i class="fas fa-clipboard-check fa-2x text-gray-300"></i>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Active Order -->
    <div class="col-xl-3 col-md-6 mb-4">
      <div class="card border-left-warning shadow h-100 py-2">
        <div class="card-body">
          <div class="row no-gutters align-items-center">
            <div class="col mr-2">
              <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Pesanan Aktif (<?= date('M') ?>)</div>
              <div class="h5 mb-0 font-weight-bold text-gray-800"><span id="active-order-qty"></span>pcs</div>
            </div>
            <div class="col-auto">
              <i class="fas fa-list fa-2x text-gray-300"></i>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Orders Table -->
  <div class="card shadow mb-4">
    <div class="card-body" id="order-detail-table-card">
      <table class="table table-hover" id="orderTable">
        <thead class="thead-light">
          <tr>
            <th scope="col" class="text-center">Gambar</th>
            <th scope="col">Judul</th>
            <th scope="col">Barang</th>
            <th scope="col">Posisi</th>
            <th scope="col">Ukuran</th>
            <th scope="col">Warna</th>
            <th scope="col">Jml</th>
            <th scope="col">Diambil</th>
            <th scope="col">Status</th>
            <th scope="col">Pelanggan</th>
            <th scope="col">#</th>
          </tr>
        </thead>
      </table>
    </div>
  </div>

  <!-- Mark as Modal-->
  <div class="modal fade" id="mark-as-modal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">

      <form action="<?= base_url('action/pesanan_action/tandai_sebagai'); ?>" method="post" id="update-process-form">

        <input type="hidden" name="redirect-here" value="<?= "{$this->uri->segment(1)}/{$this->uri->segment(2)}/{$this->uri->segment(3)}" ?>">

        <input type="hidden" name="order[order_id]" id="modal-order-id" value="">

        <div class="modal-content">

          <div class="modal-header">
            <h5 class="modal-title">Status Pengerjaan</h5>
            <button class="close" type="button" data-dismiss="modal">
              <span aria-hidden="true">×</span>
            </button>
          </div>

          <div class="modal-body">

            <select name="order[production_status_id]" id="modal-process-status" class="custom-select">

              <option value="">Pilih...</option>

              <?php $process_list = $this->db->get('production_status')->result_array(); ?>

              <?php foreach ($process_list as $status) : ?>
                <option value="<?= $status['production_status_id']; ?>"><?= $status['name']; ?></option>
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

  <!-- Production Modal-->
  <div class="modal fade" id="spec-modal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">

      <form action="" method="post" id="spec-form">

        <input type="hidden" name="production_design[order_id]" id="order-id">

        <input type="hidden" id="order-price">

        <div class="modal-content">

          <div class="modal-header">
            <h5 class="modal-title">Atur Produksi</h5>
            <button class="close" type="button" data-dismiss="modal">
              <span aria-hidden="true">×</span>
            </button>
          </div>

          <div class="modal-body">

            <div class="form-group">
              <label for=""><small>Pengerjaan</small></label>
              <select id="production-type" class="custom-select">
                <option value="">Pilih...</option>
                <option value="material" data-form-action="<?= base_url('action/produksi_action/atur_spek_material'); ?>">
                  Bahan Baku
                </option>
                <option value="design" data-form-action="<?= base_url('action/produksi_action/pesan_desain'); ?>">
                  Desain
                </option>
                <option value="embroidery" data-form-action="<?= base_url('action/produksi_action/atur_spek_bordir'); ?>">
                  Bordir
                </option>
              </select>
            </div>

            <div id="unique-form-wrapper"></div>

          </div>

          <div class="modal-footer">
            <button class="btn btn-secondary" type="button" data-dismiss="modal">Batal</button>
            <button type="submit" class="btn btn-primary" id="set-spec-btn">Atur</button>
          </div>

        </div>

      </form>

    </div>
  </div>

  <!-- Delete Order Modal -->
  <div class="modal fade" id="del-order-modal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
      <form action="<?= base_url('action/pesanan_action/hapus_pesanan'); ?>" method="post" id="del-order-form">
        <input type="hidden" name="order[order_id]" id="del-modal-order-id" value="">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Yakin akan menghapus?</h5>
            <button class="close" type="button" data-dismiss="modal">
              <span aria-hidden="true">×</span>
            </button>
          </div>
          <div class="modal-body">Klik "Hapus" jika Anda yakin untuk menghapus pesanan ini.</div>
          <div class="modal-footer">
            <button class="btn btn-secondary" type="button" data-dismiss="modal">Batal</button>
            <button type="submit" class="btn btn-primary" id="del-order-btn">Hapus</button>
          </div>
        </div>
      </form>
    </div>
  </div>


</div>
<!-- /.container-fluid -->

<script>
  const orderDetailCard = document.querySelector('#order-detail-table-card');
  const productionType = document.querySelector('#spec-modal #production-type');

  const orderStatusCol = document.querySelectorAll('.order-status-col');
  const orderStatusArray = Array.from(orderStatusCol);

  let completeOrder = 0;
  let activeOrder = 0;

  for (let i = 0; i < orderStatusArray.length; i++) {
    if (orderStatusArray[i].innerText === 'Selesai') {
      completeOrder++;
    }
    if (orderStatusArray[i].innerText !== 'Selesai') {
      activeOrder++;
    }
  }

  document.querySelector('#complete-order-qty').innerHTML = completeOrder;
  document.querySelector('#active-order-qty').innerHTML = activeOrder;

  orderDetailCard.addEventListener('click', (e) => {

    let clickedEl = e.target;

    if (!clickedEl.closest('tr')) {
      return;
    }

    // Grab order-id value from order-id data attribute of the respective row
    let currentRow = clickedEl.closest('tr');
    let orderId = currentRow.dataset.orderId;
    let orderPrice = currentRow.dataset.orderPrice;

    if (clickedEl.matches('.del-modal-trigger')) {

      // Assign order-detail-id value to the order-detail-id hidden input in the del-order-modal
      document.querySelector('#del-modal-order-id').value = orderId

    }

    if (clickedEl.matches('.status-mark-trigger')) {

      // Grab status data
      let statusId = clickedEl.dataset.statusId;

      // Assign order-detail-id value to the order-id hidden input in the mark-as-finished-modal
      document.querySelector('#mark-as-modal #modal-order-id').value = orderId

      // Assign status data to the modal
      document.querySelector('#mark-as-modal #modal-process-status').value = statusId

    }

  });
</script>