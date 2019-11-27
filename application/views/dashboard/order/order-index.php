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

  <!-- Orders Table -->
  <div class="card shadow mb-4">
    <div class="card-body" id="order-detail-table-card">
      <table class="table table-hover" id="dataTable">
        <thead class="thead-light">
          <tr>
            <th scope="col" class="text-center">Gambar</th>
            <th scope="col">ID</th>
            <th scope="col">Barang</th>
            <th scope="col">Posisi</th>
            <th scope="col">Jml</th>
            <th scope="col">Diambil</th>
            <th scope="col">Status</th>
            <th scope="col">Pelanggan</th>
            <th scope="col">Invoice</th>
            <th scope="col">#</th>
          </tr>
        </thead>
        <tbody style="font-size:14px">

          <?php foreach ($orders as $order) : ?>

            <tr data-order-id="<?= $order['order_id']; ?>" data-order-price="<?= $order['price']; ?>">
              <td class="text-center">
                <img style="width:33px;height:100%" src="<?= isset($order['image2']) ? base_url('assets/img/artwork/') . $order['image2'] : base_url('assets/icon/') . $order['item_icon']; ?>">
              </td>
              <td data-sort="<?= $order['order_number']; ?>">
                PSN-<?= $order['order_number']; ?>
              </td>
              <td>
                <a style="color:#858796" href="<?= base_url('pesanan/sunting/') . $order['order_id']; ?>" class="text-link"><?= $order['item_name']; ?>: <?= $order['description']; ?></a>
              </td>
              <td>
                <?= $order['position_name']; ?>
              </td>
              <td>
                <?= moneyStrDot($order['quantity']); ?>
              </td>
              <td data-sort="<?= strtotime($order['order_deadline']); ?>">
                <?= date('d/m/Y', strtotime($order['order_deadline'])); ?>
              </td>
              <td>
                <?= $order['process_status']; ?>
              </td>
              <td>
                <?= $order['customer_name']; ?>
              </td>
              <td data-sort="<?= $order['invoice_number']; ?>">
                <?php if (empty($order['invoice_number'])) : ?>
                  <span>Belum ada</span>
                <?php else : ?>
                  <a href="<?= base_url('invoice/sunting/') . $order['invoice_number']; ?>" style="color:#858796">
                    INV-<?= $order['invoice_number']; ?>
                  </a>
                <?php endif; ?>
              </td>
              <td>

                <a class="dropdown-toggle text-right" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown">
                  <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
                </a>

                <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in">

                  <a class="dropdown-item" href="<?= base_url('pesanan/sunting/') . $order['order_id']; ?>">Sunting Pesanan</a>

                  <a class="dropdown-item status-mark-trigger" href="#" data-toggle="modal" data-target="#mark-as-modal" data-status-id="<?= $order['process_status_id']; ?>">Tandai Sebagai</a>

                  <a class="dropdown-item spec-modal-trigger" href="#" data-toggle="modal" data-target="#spec-modal">Atur Produksi</a>

                  <a class="dropdown-item del-modal-trigger" href="#" data-toggle="modal" data-target="#del-order-modal">Hapus Pesanan</a>

                </div>

              </td>
            </tr>
          <?php endforeach; ?>

        </tbody>
      </table>
    </div>
  </div>

  <!-- Mark as Modal-->
  <div class="modal fade" id="mark-as-modal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">

      <form action="<?= base_url('processor/pesanan_pcsr/tandai_sebagai'); ?>" method="post" id="update-process-form">

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

            <select name="order[process_status_id]" id="modal-process-status" class="custom-select">

              <option value="">Pilih...</option>

              <?php $process_list = $this->db->get('process_status')->result_array(); ?>

              <?php foreach ($process_list as $status) : ?>
                <option value="<?= $status['process_status_id']; ?>"><?= $status['name']; ?></option>
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
                <option value="material" data-form-action="<?= base_url('processor/produksi_pcsr/atur_spek_material'); ?>">
                  Bahan Baku
                </option>
                <option value="design" data-form-action="<?= base_url('processor/produksi_pcsr/pesan_desain'); ?>">
                  Desain
                </option>
                <option value="embroidery" data-form-action="<?= base_url('processor/produksi_pcsr/atur_spek_bordir'); ?>">
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
      <form action="<?= base_url('processor/pesanan_pcsr/hapus_pesanan'); ?>" method="post" id="del-order-form">
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

    // if (clickedEl.matches('.spec-modal-trigger')) {

    //   // Assign orderId to order-id input in the spec-modal
    //   document.querySelector('#spec-modal #order-id').value = orderId;

    //   // Assign orderPrice to order-price input in the spec-modal
    //   document.querySelector('#spec-modal #order-price').value = orderPrice;

    //   // Reset form
    //   document.querySelector('#spec-form').reset();

    // }

  });
</script>