<!-- Begin Page Content -->
<div class="container-fluid">

  <!-- Page Heading -->
  <h1 class="h3 mb-3 text-gray-800"><?= $title; ?></h1>

  <div class="row">

    <!-- Main Panel -->
    <div class="col-8">

      <div class="card shadow mb-4">

        <div class="col bg-primary text-white py-3 mb-4" style="border-top-left-radius: 0.35rem;border-top-right-radius: 0.35rem">

          <div class="col d-flex justify-content-between align-items-center">
            <h4 class="text-uppercase font-weight-bold my-0">Swasti Bordir</h4>
            <p class="my-0">Formulir Desain Bordir</p>
          </div>

        </div>

        <?php if ($design_detail['artwork']) : ?>
          <div class="row py-4">
            <div class="col d-flex justify-content-center align-items-center">
              <img class="img-fluid" src="<?= base_url('assets/img/artwork/') . $design_detail['artwork']; ?>" alt="">
            </div>
          </div>
        <?php else : ?>
          <div class="row py-4">
            <div class="col d-flex justify-content-center align-items-center">
              <div class="text-danger">Belum ada gambar. Silakan unggah.</div>
            </div>
          </div>
        <?php endif; ?>

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

        <?php if ($design_detail['note']) : ?>
          <div class="row py-4">
            <div class="col d-flex justify-content-center align-items-center">
              <h5><?= $design_detail['note']; ?></h5>
            </div>
          </div>
        <?php endif; ?>

      </div>

    </div>

    <!-- Side Panel -->
    <div class="col-4">

      <!-- Artwork Upload Card -->
      <div class="card shadow mb-3">
        <!-- Card Header - Accordion -->
        <a href="#file-card__body" class="d-block card-header py-3" data-toggle="collapse" role="button">
          <h6 class="m-0 font-weight-bold text-primary">File Gambar</h6>
        </a>
        <!-- Card Content - Collapse -->
        <div class="collapse show" id="file-card__body">
          <div class="card-body">

            <?php if (isset($design_detail['artwork'])) : ?>
              <div class="d-flex align-items-center mb-3">
                <img src="<?= base_url('assets/img/artwork/') . $design_detail['artwork']; ?>" alt="" class="img-thumbnail mr-2" style="width:15%;height:100%">
                <div>
                  <p class="font-weight-bold my-0"><?= $design_detail['artwork']; ?></p>
                  <small>Diunggah pada: 29 Juli 2019</small>
                </div>
                <div class="ml-auto">
                  <i class="fas fa-download"></i>
                </div>
              </div>
            <?php else : ?>
              <div class="custom-file">
                <input type="file" name="image" class="custom-file-input" id="image">
                <label class="custom-file-label" for="customFile">Pilih file...</label>
              </div>
            <?php endif; ?>

          </div>
        </div>
      </div>

      <!-- Machine File Upload Card -->
      <div class="card shadow mb-3">
        <!-- Card Header - Accordion -->
        <a href="#file-card__body" class="d-block card-header py-3" data-toggle="collapse" role="button">
          <h6 class="m-0 font-weight-bold text-primary">File Mesin</h6>
        </a>
        <!-- Card Content - Collapse -->
        <div class="collapse show" id="file-card__body">
          <div class="card-body">

            <?php if (isset($design_detail['emb'])) : ?>
              <div class="d-flex align-items-center mb-3">
                <img src="<?= base_url('assets/img/artwork/') . $design_detail['artwork']; ?>" alt="" class="img-thumbnail mr-2" style="width:15%;height:100%">
                <div>
                  <p class="font-weight-bold my-0"><?= $design_detail['emb']; ?></p>
                  <small>Diunggah pada: 29 Juli 2019</small>
                </div>
                <button type="button" data-toggle="modal" data-target="#del-artwork-modal" class="close ml-auto">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
            <?php else : ?>
              <div class="custom-file">
                <input type="file" name="image" class="custom-file-input" id="image">
                <label class="custom-file-label" for="customFile">Pilih file...</label>
              </div>
            <?php endif; ?>

          </div>
        </div>
      </div>

      <!-- Otomatis Input -->
      <div class="card shadow mb-3">
        <!-- Card Header - Accordion -->
        <a href="#file-card__body" class="d-block card-header py-3" data-toggle="collapse" role="button">
          <h6 class="m-0 font-weight-bold text-primary">Urutan Otomatis</h6>
        </a>
        <!-- Card Content - Collapse -->
        <div class="collapse show" id="file-card__body">
          <div class="card-body">

            <form action="">
              <textarea name="" id="" class="form-control" style="font-size:13px"><?= $design_detail['color_order'] ?></textarea>
            </form>

          </div>
        </div>
      </div>

    </div>

  </div>

</div>
<!-- /.container-fluid -->