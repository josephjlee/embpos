<!-- Begin Page Content -->
<div class="container-fluid">

  <!-- Page Heading -->
  <h1 class="h3 mb-3 text-gray-800"><?= $title; ?></h1>

  <!-- Orders Table -->

  <div class="card shadow mb-4">
    <div class="card-body">
      <table class="table table-hover" id="dataTable">
        <thead class="thead-light">
          <tr>
            <th scope="col">Pesanan</th>
            <th scope="col">Pelanggan</th>
            <th scope="col">Tanggal Diambil</th>
            <th scope="col" class="text-center">Proses</th>
            <th scope="col" class="text-center">Pembayaran</th>
            <th scope="col" class="text-center">Tagihan</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($orders as $order) : ?>
            <tr>
              <td><a href="<?= base_url('pesanan/detil/') . $order['number']; ?>">INV-<?= $order['number']; ?></a></td>
              <td><?= $order['customer']; ?></td>
              <td><?= date('j M Y', strtotime($order['required_date'])); ?></td>
              <td class="text-center"><?= $order['process']; ?></td>
              <td class="text-center"><?= $order['payment']; ?></td>
              <td>
                <div class="d-flex justify-content-between w-75">
                  <p>Rp</p>
                  <p><?= number_format($order['invoice_due'], 2, ',', '.'); ?></p>
                </div>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>
  

</div>
<!-- /.container-fluid -->