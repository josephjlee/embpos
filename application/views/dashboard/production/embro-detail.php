<!-- Begin Page Content -->
<div class="container-fluid">

  <form action="<?= base_url('processor/produksi_pcsr/perbarui_detail') ?>" method="post">
    <input type="hidden" name="production[production_id]" value="<?= $embro_detail['production_id']; ?>">

    <div class="row">

      <!-- Main Panel -->
      <div class="col-8">

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
      <div class="col-4" id="side-panel">

        <!-- Action Card -->
        <div class="card shadow mb-3">

          <a href="#actionCard" class="d-block card-header py-3" data-toggle="collapse" role="button">
            <h6 class="m-0 font-weight-bold text-primary">Tindakan</h6>
          </a>

          <div class="collapse show" id="actionCard">

            <div class="card-body d-flex align-items-center">

              <button type="submit" id="save-data-btn" class="mr-2 action-btn"><i class="fas fa-save fa-2x"></i></button>

              <a href="#" data-toggle="modal" data-target="#update-process-modal" class="action-btn mr-3"><i class="fas fa-tasks fa-2x"></i></a>

              <a href="#" data-toggle="modal" data-target="#output-modal" class="action-btn"><i class="fas fa-calculator fa-2x"></i></a>

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

                <div class="d-flex align-items-center">
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

        <!-- Output History Card -->
        <div class="card shadow mb-3">

          <a href="#output-history" class="d-block card-header py-3" data-toggle="collapse" role="button">
            <h6 class="m-0 font-weight-bold text-primary">Riwayat Output</h6>
          </a>

          <div class="collapse show" id="output-history">

            <div class="card-body py-0">

              <table class="table">

                <tbody>

                  <?php if (!empty($output_records)) : ?>

                  <?php foreach ($output_records as $output) : ?>

                  <tr data-output-id="<?= $output['output_id']; ?>" data-output-quantity="<?= $output['quantity']; ?>" data-output-operator="<?= $output['employee_id']; ?>" data-output-shift="<?= $output['shift']; ?>">

                    <td class="px-0">
                      <div>
                        <small id="output-date-display" style="color:#ec8615">
                          <span id="output-amount-display"><?= moneyStr($output['quantity']); ?></span>pcs
                        </small>
                        <p style="font-size:14px;" id="output-name-display" class="my-0">
                          <span style="color:#495057"><?= date('d-m-Y', strtotime($output['date'])); ?> | Shift <?= $output['shift']; ?> |</span> <?= $output['operator']; ?>
                        </p>
                      </div>
                    </td>

                    <td class="px-0 align-middle text-right">
                      <a class="dropdown-toggle text-right" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown">
                        <i class="fas fa-ellipsis-v fa-sm fa-fw" style="color:#aba9bf"></i>
                      </a>
                      <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in">
                        <div class="dropdown-header">Tindakan:</div>
                        <a href="" class="dropdown-item update-output-trigger" data-toggle="modal" data-target="#updateoutputModal">Sunting Detail</a>
                        <a href="" class="dropdown-item del-output-trigger" data-toggle="modal" data-target="#deleteoutputModal">Hapus Pembayaran</a>
                      </div>
                    </td>

                  </tr>

                  <?php endforeach; ?>

                  <?php else : ?>

                  <tr>
                    <td class="px-0">Belum ada output.</td>
                  </tr>

                  <?php endif; ?>

                </tbody>

              </table>

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

  <!-- Outut Modal -->
  <div class="modal fade" id="output-modal" tabindex="-1" role="dialog">

    <div class="modal-dialog" role="document">

      <form action="<?= base_url('processor/produksi_pcsr/record_machine_output'); ?>" method="post">

        <input type="hidden" name="output[machine]" value="<?= $embro_detail['machine']; ?>">
        <input type="hidden" name="output[production_id]" value="<?= $embro_detail['production_id']; ?>">
        <input type="hidden" name="input-src" value="<?= current_url(); ?>">
        

        <div class="modal-content">

          <div class="modal-header">
            <h5 class="modal-title">Catat Output</h5>
            <button type="button" class="close" data-dismiss="modal">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>

          <div class="modal-body">

            <div class="form-group">
              <label for="modal-output-qty"><small>Jumlah</small></label>
              <input type="text" name="output[quantity]" id="modal-output-qty" class="form-control" value="" placeholder="0">
            </div>

            <div class="form-group">

              <label for="modal-operator"><small>Operator</small></label>

              <?php $operators = $this->produksi_model->get_operator_name(); ?>

              <select name="output[employee_id]" id="modal-operator">

                <option value="">Pilih operator</option>

                <?php foreach($operators as $operator) : ?>
                  <option value="<?= $operator['employee_id']; ?>"><?= $operator['nick_name']; ?></option>
                <?php endforeach; ?>
                
              </select>

            </div>

            <div class="form-group">

              <label for="modal-shift"><small>Shift</small></label>

              <select name="output[shift]" id="modal-shift" class="custom-select">
                <option value="">Pilih...</option>
                <option value="1">Siang</option>
                <option value="2">Malam</option>
              </select>

            </div>

          </div>

          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
            <button type="submit" class="btn btn-primary" id="save-payment-btn">Simpan data</button>
          </div>

        </div>

      </form>

    </div>
  </div>

</div>
<!-- /.container-fluid -->