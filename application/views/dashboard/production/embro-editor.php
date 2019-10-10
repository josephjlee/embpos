<!-- Begin Page Content -->
<div class="container-fluid">

  <form action="<?= base_url('processor/produksi_pcsr/perbarui_detail') ?>" method="post">
    <input type="hidden" name="production[production_id]" value="<?= $embro_detail['production_id']; ?>">    

    <!-- Page Heading -->
	  <div class="row mb-2">

      <div class="col d-flex justify-content-start align-items-center">

        <h1 class="h3 text-gray-800 mr-2"><?= $title; ?></h1>

        <a href="<?= base_url('produksi/form_bordir/') . $embro_detail['production_id']; ?>" class="pb-1 action-btn"><i class="fas fa-fw fa-file-image fa-lg"></i></a>

        <div class="ml-auto">

          <a href="#"><span class="badge badge-danger py-2 px-3 text-uppercase"><?= $embro_detail['status']; ?></span></a>

        </div>

      </div>

    </div>

    <div class="row">

      <!-- Main Panel -->
      <div class="col-8">

        <!-- Order Form - Header -->
        <div class="card shadow mb-3">

          <div class="card-body">

            <div class="d-flex justify-content-between align-items-center mb-2">

              <div class="input-group mr-2">
                <div class="input-group-prepend">
                  <div class="input-group-text"><i class="fas fa-box fa-fw"></i></div>
                </div>
                <input type="text" name="" class="form-control" id="order-id" value="<?= $embro_detail['order_number']; ?>">
              </div>

              <div class="input-group">
                <div class="input-group-prepend">
                  <div class="input-group-text"><i class="fas fa-box fa-fw"></i></div>
                </div>
                <input type="text" name="" class="form-control" id="machine-number" value="<?= $embro_detail['machine']; ?>">
              </div>              

            </div>

            <div class="d-flex justify-content-between align-items-center">

              <div class="input-group mr-2">
                <div class="input-group-prepend">
                  <div class="input-group-text"><i class="fas fa-calendar-plus fa-fw"></i></div>
                </div>
                <input class="form-control" type="text" name="" id="labor-price" value="<?= $embro_detail['labor_price']; ?>">
              </div>

              <div class="input-group">
                <div class="input-group-prepend">
                  <div class="input-group-text"><i class="fas fa-calendar-check fa-fw"></i></div>
                </div>
                <input class="form-control" type="date" name="" id="deadline" value="<?= date('Y-m-d', strtotime($embro_detail['required'])); ?>"
              </div>
              
            </div>

          </div>
        </div>

        <!-- Order Form - Body -->
        <div class="card shadow mb-3" id="order-form-body">

          <div class="card-body">

            <div class="form-group">
              <label for="description"><small>Judul bordiran</small></label>
              <input type="text" name="" id="description" class="form-control description" value="<?= '??' ?>">
            </div>

            <div class="form-row">

              <div class="form-group col">
                <label for="dimension"><small>Dimensi</small></label>
                <input type="text" id="dimension" class="form-control dimension number" value="<?= $embro_detail['dimension'] ?? ''; ?>">
              </div>
              <div class="form-group col">
                <label for=""><small>Warna</small></label>
                <input type="text" name="order[color]" id="color" class="form-control color number" value="<?= $embro_detail['color'] ?? ''; ?>">
              </div>
              <div class="form-group col">
                <label for=""><small>Bahan</small></label>
                <input type="text" id="material" class="form-control material number" value="<?= $embro_detail['material']; ?>">
              </div>

            </div>

            <div class="form-row">

              <div class="form-group col">
                <label for="quantity"><small>Kuantitas</small></label>
                <input type="text" name="order[quantity]" id="quantity" class="form-control quantity number text-right" value="<?= isset($embro_detail['quantity']) ? moneyStr($embro_detail['quantity']) : ''; ?>">
              </div>
              <div class="form-group col">
                <label for=""><small>Harga</small></label>
                <input type="text" name="order[price]" id="price" class="form-control price number text-right" value="<?= isset($embro_detail['price']) ? moneyStr($embro_detail['price']) : ''; ?>">
              </div>
              <div class="form-group col">
                <label for=""><small>Diskon</small></label>
                <input type="text" name="order[discount]" id="discount" class="form-control discount number text-right" value="<?= isset($embro_detail['discount']) ? moneyStr($embro_detail['discount']) : ''; ?>" placeholder="0">
              </div>

            </div>

            <div class="form-group">
              <label for=""><small>Total</small></label>
              <input type="text" name="" id="amount" class="form-control amount" value="<?= isset($embro_detail['amount']) ? moneyStr($embro_detail['amount']) : ''; ?>" readonly>
            </div>

          </div>

        </div>

        <div class="card shadow mb-4">

          <div class="col bg-primary text-white py-3 mb-4" style="border-top-left-radius: 0.35rem;border-top-right-radius: 0.35rem">

            <div class="col d-flex justify-content-between align-items-center">
              <h4 class="text-uppercase font-weight-bold my-0">Swasti Bordir</h4>
              <p class="my-0">Detail Pesanan Bordir</p>
            </div>

          </div>

          <?php if ($embro_detail['artwork']) : ?>
            <div class="row py-4">
              <div class="col d-flex justify-content-center align-items-center">
                <img class="img-fluid" src="<?= base_url('assets/img/artwork/') . $embro_detail['artwork']; ?>" alt="">
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
                  <p class="my-0" style="color:black"><?= $embro_detail['title'] ?? '-'; ?> (PS-<?= $embro_detail['order_number']; ?>)</p>
                </li>
                <li class="list-group-item">
                  <p class="my-0"><small>Barang:</small></p>
                  <p class="my-0" style="color:black"><?= $embro_detail['item'] ?? '-'; ?></p>
                </li>
                <li class="list-group-item">
                  <p class="my-0"><small>Bahan:</small></p>
                  <p class="my-0" style="color:black"><?= $embro_detail['material'] ?? '-'; ?></p>
                </li>
                <li class="list-group-item">
                  <p class="my-0"><small>Dimensi:</small></p>
                  <p class="my-0" style="color:black"><?= $embro_detail['dimension'] ?? '-'; ?>cm</p>
                </li>
                <li class="list-group-item">
                  <p class="my-0"><small>Warna:</small></p>
                  <p class="my-0" style="color:black"><?= $embro_detail['color'] ?? '-'; ?></p>
                </li>
                <li class="list-group-item">
                  <p class="my-0"><small>Jumlah:</small></p>
                  <p class="my-0" style="color:black"><?= $embro_detail['quantity'] ?? '-'; ?></p>
                </li>
              </ul>
            </div>

            <div class="col d-flex justify-content-center">
              <ul class="list-group">              
                <li class="list-group-item">
                  <p class="my-0"><small>Mesin</small></p>
                  <p class="my-0" style="color:black"><?= $embro_detail['machine'] ?? '-'; ?></p>
                </li>
                <li class="list-group-item">
                  <p class="my-0"><small>Flashdisk</small></p>
                  <p class="my-0" style="color:black"><?= $embro_detail['flashdisk'] ?? '-'; ?></p>
                </li>
                <li class="list-group-item">
                  <p class="my-0"><small>Nama File</small></p>
                  <p class="my-0" style="color:black"><?= $embro_detail['file'] ?? '-'; ?></p>
                </li>
                <li class="list-group-item">
                  <p class="my-0"><small>Otomatis</small></p>
                  <p class="my-0" style="color:black"><?= $embro_detail['color_order'] ?? '-'; ?></p>
                </li>
                <li class="list-group-item">
                  <p class="my-0"><small>Harga Operator</small></p>
                  <p class="my-0" style="color:black"><?= moneyStr($embro_detail['labor_price']) ?? '-'; ?></p>
                </li>
                <li class="list-group-item">
                  <p class="my-0"><small>Operator</small></p>
                  <p class="my-0" style="color:black"><?= $embro_detail['operator'] ?? '-'; ?></p>
                </li>
              </ul>
            </div>

          </div>

          <?php if ($embro_detail['note']) : ?>
            <div class="row py-4">
              <div class="col d-flex justify-content-center align-items-center">
                <h5><?= $embro_detail['note']; ?></h5>
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

              <a href="#" data-toggle="modal" data-target="#update-process-modal" class="action-btn mr-3"><i class="fas fa-tasks fa-2x"></i></a>

              <a href="#" data-toggle="modal" data-target="#input-output-modal" class="action-btn"><i class="fas fa-calculator fa-2x"></i></a>

            </div>
          </div>
        </div>

        <!-- Machine File Card -->
        <div class="card shadow mb-3">

          <!-- Card Header - Accordion -->
          <a href="#machine-file-card" class="d-block card-header py-3" data-toggle="collapse" role="button">
            <h6 class="m-0 font-weight-bold text-primary">File Mesin</h6>
          </a>
          
          <!-- Card Content - Collapse -->
          <div class="collapse show" id="machine-file-card">
            <div class="card-body">

                <div class="d-flex align-items-center mb-3">
                  <img src="<?= base_url('assets/img/artwork/') . $embro_detail['artwork']; ?>" alt="" class="img-thumbnail mr-2" style="width:15%;height:100%">
                  <div>
                    <p class="font-weight-bold my-0"><?= $embro_detail['file']; ?></p>
                  </div>
                  <div class="ml-auto">
                    <i class="fas fa-download"></i>
                  </div>
                </div>

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

        <input type="hidden" name="production[production_id]" id="production-id" value="<?= $embro_detail['production_id']; ?>">
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

              <?php $embro_status_list = $this->produksi_model->get_embro_status(); ?>

              <?php foreach ($embro_status_list as $status) : ?>
                <option value="<?= $status['production_status_id']; ?>" <?= $status['production_status_id'] == $embro_detail['production_status_id'] ? 'selected' : ''; ?>><?= $status['name']; ?></option>
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