<!-- Begin Page Content -->
<div class="container-fluid">

  <!-- Page Heading -->
  <h1 class="h3 mb-3 text-gray-800"><?= $title; ?></h1>

  <div class="row">

    <div class="col-8">

      <div class="card shadow mb-4">

        <div class="col bg-primary text-white py-3 mb-4" style="border-top-left-radius: 0.35rem;border-top-right-radius: 0.35rem">

          <div class="col d-flex justify-content-between align-items-center">
            <h4 class="text-uppercase font-weight-bold my-0">Swasti Bordir</h4>
            <p class="my-0">Formulir Desain Bordir</p>
          </div>

        </div>

        <div class="row py-4">
          <div class="col d-flex justify-content-center align-items-center">
            <img class="img-fluid" src="<?= base_url('assets/img/artwork/') . 'summer-3658073_1280.png'; ?>" alt="">
          </div>
        </div>

        <div class="row py-4">

          <div class="col d-flex justify-content-center">
            <ul class="list-group">
              <li class="list-group-item">
                <p class="my-0"><small>Judul:</small></p>
                <p class="my-0" style="color:black"><?= $design_detail['title'] ?? '-'; ?></p>
              </li>
              <li class="list-group-item">
                <p class="my-0"><small>Barang:</small></p>
                <p class="my-0" style="color:black"><?= $design_detail['item'] ?? '-'; ?></p>
              </li>
              <li class="list-group-item">
                <p class="my-0"><small>Bahan:</small></p>
                <p class="my-0" style="color:black"><?= $design_detail['material'] ?? '-'; ?></p>
              </li>
            </ul>
          </div>

          <div class="col d-flex justify-content-center">
            <ul class="list-group">
              <li class="list-group-item">
                <p class="my-0"><small>Dimensi:</small></p>
                <p class="my-0" style="color:black"><?= $design_detail['dimension'] ?? '-'; ?>cm</p>
              </li>
              <li class="list-group-item">
                <p class="my-0"><small>Warna:</small></p>
                <p class="my-0" style="color:black"><?= $design_detail['color'] ?? '-'; ?></p>
              </li>
              <li class="list-group-item">
                <p class="my-0"><small>Ulang:</small></p>
                <p class="my-0" style="color:black"><?= $design_detail['repeat'] ?? '-'; ?>x</p>
              </li>
            </ul>
          </div>

        </div>

        <div class="row py-4">
          <div class="col d-flex justify-content-center align-items-center">
            <h5><?= $design_detail['note'] ?? '-'; ?></h5>
          </div>
        </div>

      </div>

    </div>

  </div>

</div>
<!-- /.container-fluid -->