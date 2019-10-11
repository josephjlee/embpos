<!-- Begin Page Content -->
<div class="container-fluid">

  <form action="<?= base_url('processor/produksi_pcsr/rekam_output_desainer') ?>" method="post" enctype="multipart/form-data" accept-charset="utf-8">
    <input type="hidden" name="production[production_id]" value="<?= $design_detail['production_id']; ?>">
    <input type="hidden" name="input-src" value="<?= current_url(); ?>">

    <!-- Page Heading -->
    <div class="row mb-2">

      <div class="col d-flex justify-content-start align-items-center">

        <h1 class="h3 text-gray-800 mr-2"><?= $title; ?></h1>

          <div class="ml-auto">

            <a href="#"><span class="badge badge-danger py-2 px-3 text-uppercase"><?= $design_detail['status']; ?></span></a>

          </div>

      </div>

    </div>

    <div class="row">

      <!-- Main Panel -->
      <div class="col-8">

        <!-- Alert Message -->
        <?= $this->session->flashdata('message') ?? ''; ?>

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
                <div class="text-danger">Belum ada gambar.</div>
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

        <!-- Action Card -->
        <div class="card shadow mb-3">
          <!-- Card Header - Accordion -->
          <a href="#actionCard" class="d-block card-header py-3" data-toggle="collapse" role="button">
            <h6 class="m-0 font-weight-bold text-primary">Tindakan</h6>
          </a>
          <!-- Card Content - Collapse -->
          <div class="collapse show" id="actionCard">

            <div class="card-body d-flex align-items-center">

              <button type="submit" id="save-data-btn" class="mr-2 action-btn"><i class="fas fa-save fa-2x"></i></button>

              <a href="#" data-toggle="modal" data-target="#update-process-modal" class="action-btn"><i class="fas fa-tasks fa-2x"></i></a>

            </div>
          </div>
        </div>

        <!-- Artwork Card -->
        <div class="card shadow mb-3">
          <!-- Card Header - Accordion -->
          <a href="#artwork-card" class="d-block card-header py-3" data-toggle="collapse" role="button">
            <h6 class="m-0 font-weight-bold text-primary">File Gambar</h6>
          </a>
          <!-- Card Content - Collapse -->
          <div class="collapse show" id="artwork-card">
            <div class="card-body">

              <?php if ($design_detail['artwork']) : ?>
                <div class="d-flex align-items-center">
                  <img src="<?= base_url('assets/img/artwork/') . $design_detail['artwork']; ?>" alt="" class="img-thumbnail mr-2" style="width:15%;height:100%">
                  <div>
                    <p class="font-weight-bold my-0"><?= $design_detail['artwork']; ?></p>
                    <!-- <small>Diunggah pada: 29 Juli 2019</small> -->
                  </div>
                  <div class="ml-auto">
                    <i class="fas fa-download"></i>
                  </div>
                </div>
              <?php else : ?>
                <div class="align-items-center">
                  Belum ada gambar.
                </div>
              <?php endif; ?>

            </div>
          </div>
        </div>

        <!-- Machine File Upload Card -->
        <div class="card shadow mb-3">
          <!-- Card Header - Accordion -->
          <a href="#machine-file-card" class="d-block card-header py-3" data-toggle="collapse" role="button">
            <h6 class="m-0 font-weight-bold text-primary">File Mesin</h6>
          </a>
          <!-- Card Content - Collapse -->
          <div class="collapse show" id="machine-file-card">
            <div class="card-body">

              <?php if (isset($design_detail['file'])) : ?>
                <div class="d-flex align-items-center mb-3">
                  <img src="<?= base_url('assets/img/artwork/') . $design_detail['artwork']; ?>" alt="" class="img-thumbnail mr-2" style="width:15%;height:100%">
                  <div>
                    <p class="font-weight-bold my-0"><?= $design_detail['file']; ?></p>
                  </div>
                  <button type="button" data-toggle="modal" data-target="#del-emb-modal" class="close ml-auto">
                    <span aria-hidden="true">&times;</span>
                  </button>
                </div>
              <?php else : ?>
                <div class="custom-file">
                  <input type="file" name="file" class="custom-file-input" id="file">
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

              <textarea name="production[color_order]" id="color-order" class="form-control" style="font-size:13px"><?= $design_detail['color_order'] ?></textarea>

            </div>
          </div>
        </div>

      </div>

    </div>

  </form>

  <!-- Mark as Modal-->
  <div class="modal fade" id="update-process-modal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">

      <form action="<?= base_url('processor/produksi_pcsr/perbarui_detail'); ?>" method="post" id="update-process-form">

        <input type="hidden" name="production[production_id]" id="production-id" value="<?= $design_detail['production_id']; ?>">
        <input type="hidden" name="input-src" value="<?= current_url(); ?>">

        <div class="modal-content">

          <div class="modal-header">
            <h5 class="modal-title">Status Pengerjaan</h5>
            <button class="close" type="button" data-dismiss="modal">
              <span aria-hidden="true">×</span>
            </button>
          </div>

          <div class="modal-body">

            <select name="production[production_status_id]" id="process-status" class="custom-select">

              <option value="">Pilih...</option>

              <?php $design_status_list = $this->produksi_model->get_design_status(); ?>

              <?php foreach ($design_status_list as $status) : ?>
                <option value="<?= $status['production_status_id']; ?>" <?= $status['production_status_id'] == $design_detail['production_status_id'] ? 'selected' : ''; ?>><?= $status['name']; ?></option>
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

</div>
<!-- /.container-fluid -->