<!-- Begin Page Content -->
<div class="container-fluid">

  <!-- Page Heading -->
  <h1 class="h3 mb-3 text-gray-800"><?= $title; ?></h1>

  <!-- Finishing List Table -->
  <div class="card shadow mb-4">
    <div class="card-body" id="order-detail-table-card">
      <table class="table table-hover" id="dataTable">
        <thead class="thead-light">
          <tr>
            <th>Gambar</th>
            <th>ID</th>
            <th>Barang</th>
            <th>Posisi</th>
            <th>Jumlah</th>
            <th>Diambil</th>
            <th>Status</th>
            <th>#</th>
          </tr>
        </thead>
        <tbody style="font-size:14px">
          <?php foreach ($finishing_list as $finishing) : ?>
            <tr>
              <td class="text-center">
                <?php if ($finishing['image']) : ?>
                  <img style="width:33px;height:100%" src="<?= base_url('assets/img/artwork/') . $finishing['image']; ?>">
                <?php else : ?>
                  <div>-</div>
                <?php endif; ?>
              </td>
              <td data-sort="<?= $finishing['order_number']; ?>">
                PS-<?= $finishing['order_number']; ?>
              </td>
              <td>
                <a href="<?= base_url('produksi/detail_finishing/') . $finishing['production_id']; ?>" style="color:#858796"><?= $finishing['description']; ?></a>
              </td>
              <td>
                <?= $finishing['position_name']; ?>
              </td>
              <td>
                <?= $finishing['quantity']; ?>
              </td>
              <td data-sort="<?= $finishing['deadline']; ?>">
                <?= date('d/m/Y', strtotime($finishing['deadline'])); ?>
              </td>
              <td>
                <?= $finishing['status']; ?>
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