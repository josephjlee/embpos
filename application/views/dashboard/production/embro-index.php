<!-- Begin Page Content -->
<div class="container-fluid">

  <!-- Page Heading -->
  <h1 class="h3 mb-3 text-gray-800"><?= $title; ?></h1>

  <!-- embro List Table -->
  <div class="card shadow mb-4">
    <div class="card-body" id="order-detail-table-card">
      <table class="table table-hover" id="dataTable">
        <thead class="thead-light">
          <tr>
            <th>Gambar</th>
            <th>Pesanan</th>
            <th>Judul</th>
            <th>Mesin</th>
            <th>Operator</th>
            <th>Diambil</th>
            <th>Status</th>
            <th>#</th>
          </tr>
        </thead>
        <tbody style="font-size:14px">
          <?php foreach ($embro_list as $embro) : ?>
            <tr>
              <td class="text-center">
                <?php if ($embro['artwork']) : ?>
                  <img style="width:33px;height:100%" src="<?= base_url('assets/img/artwork/') . $embro['artwork']; ?>">
                <?php else : ?>
                  <div>-</div>
                <?php endif; ?>
              </td>
              <td data-sort="<?= $embro['order_number']; ?>">
                PS-<?= $embro['order_number']; ?>
              </td>
              <td>
                <a href="<?= base_url('produksi/detail_bordir/') . $embro['production_id']; ?>" style="color:#858796"><?= $embro['title']; ?></a>
              </td>
              <td>
                <?= $embro['machine']; ?>
              </td>
              <td>
                <?= $embro['operator']; ?>
              </td>
              <td data-sort="<?= $embro['required']; ?>">
                <?= date('d/m/Y', strtotime($embro['required'])); ?>
              </td>
              <td>
                <?= $embro['status']; ?>
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