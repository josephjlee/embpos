<!-- Begin Page Content -->
<div class="container-fluid">

  <!-- Page Heading -->
  <h1 class="h3 mb-3 text-gray-800"><?= $title; ?></h1>

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
          <tr data-order-id="<?= $order['order_id']; ?>">
            <td class="text-center">
              <img style="width:33px;height:100%" src="<?= isset($order['image2']) ? base_url('assets/img/artwork/') . $order['image2'] : base_url('assets/icon/') . $order['item_icon']; ?>">
            </td>
            <td>
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
            <td>
              <?= date('d/m/Y', strtotime($order['order_deadline'])); ?>
            </td>
            <td>
              <?= $order['process_status']; ?>
            </td>
            <td>
              <?= $order['customer_name']; ?>
            </td>
            <td>
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

                <div class="dropdown-header">Tandai:</div>

                <?php $process_status = $this->db->get('process_status')->result_array(); ?>
                <?php foreach ($process_status as $status) : ?>
                <a href="#" class="dropdown-item status-mark-trigger" data-toggle="modal" data-target="#mark-as-modal" data-status-name=<?= $status['name']; ?> data-status-id="<?= $status['process_status_id']; ?>">
                  <?= $status['name']; ?> <?= $status['name'] == $order['process_status'] ? '&#10003;' : ''; ?>
                </a>
                <?php endforeach; ?>

                <div class="dropdown-header">Tindakan:</div>
                <a class="dropdown-item" href="<?= base_url('pesanan/sunting/') . $order['order_id']; ?>">Sunting Pesanan</a>
                <a class="dropdown-item del-modal-trigger" href="#" data-toggle="modal" data-target="#del-order-modal">Hapus Pesanan</a>

              </div>

            </td>
          </tr>
          <?php endforeach; ?>

        </tbody>
      </table>
    </div>
  </div>

  <!-- Mark as Finished Modal-->
  <div class="modal fade" id="mark-as-modal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">

      <form action="<?= base_url('processor/pesanan_pcsr/tandai_sebagai'); ?>" method="post" id="mark-as-finished-form">

        <input type="hidden" name="redirect-here" value="<?= "{$this->uri->segment(1)}/{$this->uri->segment(2)}/{$this->uri->segment(3)}" ?>">
        <input type="hidden" name="order[order_id]" id="mark-modal-order-id" value="">

        <div class="modal-content">

          <div class="modal-header">
            <h5 class="modal-title">Status Pengerjaan</h5>
            <button class="close" type="button" data-dismiss="modal">
              <span aria-hidden="true">×</span>
            </button>
          </div>

          <div class="modal-body"></div>

          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
            <button type="submit" class="btn btn-primary" name="order[process_status_id]" id="status-btn" value="">Ya</button>
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

  orderDetailCard.addEventListener('click', (e) => {

    let clickedEl = e.target;
    console.log(clickedEl);

    // Grab order-detail-id value from order-detail-id data attribute of the respective row
    let currentRow = clickedEl.closest('tr');
    let orderId = currentRow.dataset.orderId;

    console.log(orderId);

    if (clickedEl.matches('.del-modal-trigger')) {

      // Assign order-detail-id value to the order-detail-id hidden input in the del-order-modal
      document.querySelector('#del-modal-order-id').value = orderId

    }

    if (clickedEl.matches('.status-mark-trigger')) {


      // Grab status data
      let statusId = clickedEl.dataset.statusId;
      let statusName = clickedEl.dataset.statusName;

      // Assign order-detail-id value to the order-detail-id hidden input in the mark-as-finished-modal
      document.querySelector('#mark-modal-order-id').value = orderId

      // Assign status data to the modal
      document.querySelector('#mark-as-modal .modal-body').innerHTML = `
        Anda ingin mengubah status pesanan ini menjadi <strong>${statusName}?</strong>
      `;

      document.querySelector('#status-btn').value = statusId;

    }

  });
</script>