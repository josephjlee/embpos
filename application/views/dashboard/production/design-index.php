<!-- Begin Page Content -->
<div class="container-fluid">

  <!-- Page Heading -->
  <h1 class="h3 mb-3 text-gray-800"><?= $title; ?></h1>

  <!-- Design List Table -->
  <div class="card shadow mb-4">
    <div class="card-body" id="order-detail-table-card">
      <table class="table table-hover" id="dataTable">
        <thead class="thead-light">
          <tr>
            <th class="text-center">Gambar</th>
            <th>Pesanan</th>
            <th>Judul</th>
            <th>Dimensi</th>
            <th>Ulang</th>
            <th>Diambil</th>
            <th>Status</th>
            <th>#</th>
          </tr>
        </thead>
        <tbody style="font-size:14px">
          <?php foreach ($design_list as $design) : ?>
            <tr>
              <td class="text-center">
                <?php if ($design['artwork']) : ?>
                  <img style="width:33px;height:100%" src="<?= base_url('assets/img/artwork/') . $design['artwork']; ?>">
                <?php else : ?>
                  <div>-</div>
                <?php endif; ?>
              </td>
              <td data-sort="<?= $design['order_number']; ?>">
                PS-<?= $design['order_number']; ?>
              </td>
              <td>
                <a href="<?= base_url('produksi/detail_desain/') . $design['production_id']; ?>" style="color:#858796"><?= $design['title']; ?></a>
              </td>
              <td>
                <?= $design['dimension']; ?>
              </td>
              <td>
                <?= $design['repeat']; ?>
              </td>
              <td data-sort="<?= $design['required']; ?>">
                <?= date('d/m/Y', strtotime($design['required'])); ?>
              </td>
              <td>
                <?= $design['status']; ?>
              </td>
              <td>
                <a class="dropdown-toggle text-right" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown">
                  <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
                </a>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>

</div>