<!-- Begin Page Content -->
<div id="invoice-editor" class="container-fluid invoice-detail">

  <form action='<?= base_url("processor/keuangan_pcsr/sunting_hutang"); ?>' method="post" id="debt-form">

    <input type="hidden" name="request-source" value="<?= $this->uri->uri_string(); ?>">
    <input type="hidden" name="debt[debt_id]" value="<?= $debt_detail['debt_id']; ?>">

    <!-- Content Title -->
    <div class="row">
      <div class="col d-flex justify-content-start align-items-center">

        <h1 class="h3 text-gray-800 mr-2"><?= $title; ?></h1>

        <?php if ($this->uri->segment(2) == 'sunting') : ?>

          <a href="<?= base_url('debt/tampil/') . $debt_detail['debt_number']; ?>" class="pb-1 action-btn"><i class="fas fa-fw fa-file-image fa-lg"></i></a>

          <div class="ml-auto">
            <span class="badge badge-primary mr-2 py-2 px-3 text-uppercase"><?= $debt_detail['payment_status']; ?></span>
            <span class="badge badge-danger py-2 px-3 text-uppercase"><?= $debt_detail['order_status']; ?></span>
          </div>

        <?php endif; ?>

      </div>
    </div>

    <div class="row mt-2">

      <!-- Main Panel -->
      <div class="col-8">

        <!-- debt Header Card-->
        <div class="card shadow mb-3" id="customer-detail">

          <div class="card-body">

            <div class="d-flex justify-content-between align-items-center mb-2">

              <div class="input-group customer-select-wrapper">

                <div class="input-group-prepend">
                  <a href="" class="input-group-text" id="add-customer" data-toggle="modal" data-target="#addCustomerModal"><i class="fas fa-user fa-fw"></i></a>
                </div>

                <!-- Populate customer select option using get_all_customers method from pelanggan_model -->
                <?php $creditors = $this->kreditur_model->list_all_creditors(); ?>

                <select name="debt[creditor_id]" id="creditors" class="custom-select">
                  <option value="">Pilih Kreditur</option>

                  <?php if (isset($debt_detail['creditor_id'])) : ?>

                    <?php foreach ($creditors as $creditor) : ?>
                      <option value="<?= $creditor['creditor_id']; ?>" <?= $creditor['creditor_id'] == $debt_detail['creditor_id'] ? 'selected' : ''; ?>><?= $creditor['name']; ?></option>
                    <?php endforeach; ?>

                  <?php else : ?>

                    <?php foreach ($creditors as $creditor) : ?>
                      <option value="<?= $creditor['creditor_id']; ?>"><?= $creditor['name']; ?></option>
                    <?php endforeach; ?>

                  <?php endif; ?>
                </select>

              </div>

            </div>

            <div class="d-flex justify-content-between align-items-center">

              <div class="input-group mr-2">
                <div class="input-group-prepend">
                  <div class="input-group-text"><i class="fas fa-calendar-plus fa-fw"></i></div>
                </div>
                <input type="date" name="debt[transaction_date]" id="transaction-date" placeholder="tanggal penerbitan" class="form-control" value="<?= isset($debt_detail['transaction_date']) ? date('Y-m-d', strtotime($debt_detail['transaction_date'])) : ''; ?>">
              </div>

              <div class="input-group">
                <div class="input-group-prepend">
                  <div class="input-group-text"><i class="fas fa-calendar-check fa-fw"></i></div>
                </div>
                <input type="date" name="debt[payment_date]" id="payment-date" placeholder="tanggal pembayaran" class="form-control" value="<?= isset($debt_detail['payment_date']) ? date('Y-m-d', strtotime($debt_detail['payment_date'])) : ''; ?>">
              </div>

            </div>

          </div>

        </div>

        <!-- debt Card -->
        <div class="card shadow mb-3" id="debt-detail">

          <div class="card-body">

            <div class="form-row">
              <div class="form-group col">
                <label for="description"><small>Judul</small></label>
                <input type="text" name="debt[description]" id="description" class="form-control description" value="<?= $debt_detail['description'] ?? '' ?>">
              </div>
              <div class="form-group col">
                <label for="description"><small>Nominal</small></label>
                <input type="text" name="debt[amount]" id="amount" class="form-control amount" value="<?= $debt_detail['amount'] ?? '' ?>">
              </div>
            </div>

            <div class="form-group">
              <label for="note"><small>Catatan</small></label>
              <textarea name="debt[note]" id="note" class="form-control" style="font-size:13px"><?= $debt_detail['note'] ?? ''; ?></textarea>
            </div>

          </div>

        </div>

      </div>

      <!-- Side Panel -->
      <div class="col-4" id="side-panel">

        <!-- Action Card -->
        <div class="card shadow mb-3 debt-action-panel">
          <!-- Card Header - Accordion -->
          <a href="#actionCard" class="d-block card-header py-3" data-toggle="collapse" role="button">
            <h6 class="m-0 font-weight-bold text-primary">Tindakan</h6>
          </a>
          <!-- Card Content - Collapse -->
          <div class="collapse show" id="actionCard">
            <div class="card-body">
              <button type="submit" id="save-data-btn" class="mr-2 action-btn">
                <i class="fas fa-save fa-2x"></i>
              </button>
              <a href="#" data-toggle="modal" data-target="#paymentModal" data-is-input="true" class="action-btn">
                <i class="fas fa-cash-register fa-2x"></i>
              </a>
            </div>
          </div>
        </div>

        <?php if ($this->uri->segment(2) == 'sunting') : ?>

          <!-- Payment History Card -->
          <div class="card shadow mb-3">
            <a href="#paymentHistory" class="d-block card-header py-3" data-toggle="collapse" role="button">
              <h6 class="m-0 font-weight-bold text-primary">Riwayat Pembayaran</h6>
            </a>
            <div class="collapse show" id="paymentHistory">
              <div class="card-body py-0">

                <table class="table">
                  <tbody>

                    <?php if (!empty($payment_records)) : ?>

                      <?php foreach ($payment_records as $payment) : ?>

                        <tr data-payment-id="<?= $payment['payment_id']; ?>" data-payment-amount="<?= $payment['payment_amount']; ?>" data-payment-date="<?= date('Y-m-d', strtotime($payment['payment_date'])); ?>" data-payment-method="<?= $payment['payment_method_id']; ?>">
                          <td class="px-0">
                            <div>
                              <small id="payment-date-display" style="color:#ec8615">
                                Rp<span id="payment-amount-display"><?= moneyStr($payment['payment_amount']); ?></span>,00
                              </small>
                              <p style="font-size:14px;" id="payment-name-display">
                                <span style="color:#495057"><?= date('d-m-Y', strtotime($payment['payment_date'])); ?></span> | <?= $payment['payment_name']; ?>
                              </p>
                            </div>
                          </td>
                          <td class="px-0 align-middle text-right">
                            <a class="dropdown-toggle text-right" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown">
                              <i class="fas fa-ellipsis-v fa-sm fa-fw" style="color:#aba9bf"></i>
                            </a>
                            <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in">
                              <a href="" class="dropdown-item update-payment-trigger" data-toggle="modal" data-target="#updatePaymentModal">Sunting Detail</a>
                              <a href="" class="dropdown-item del-payment-trigger" data-toggle="modal" data-target="#deletePaymentModal">Hapus Pembayaran</a>
                            </div>
                          </td>
                        </tr>

                      <?php endforeach; ?>

                    <?php else : ?>

                      <tr>
                        <td class="px-0">Belum ada pembayaran.</td>
                      </tr>

                    <?php endif; ?>

                  </tbody>
                </table>
              </div>
            </div>
          </div>

        <?php endif; ?>

      </div>

    </div>

  </form>

  <!-- Add Customer Modal -->
  <div class="modal fade" id="addCustomerModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">

      <div class="modal-content">

        <form action="" id="customer-form">

          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Tambah Pelanggan Baru</h5>
            <button type="button" class="close" data-dismiss="modal">
              <span aria-hidden="true">×</span>
            </button>
          </div>

          <div class="modal-body">
            <div class="mb-4">

              <div class="form-row">
                <div class="form-group col">
                  <label for="cust_name"><small>Nama lengkap</small></label>
                  <input type="text" name="customer[name]" class="form-control mb-2">
                </div>
                <div class="form-group col">
                  <label for="cust_company"><small>Afiliasi</small></label>
                  <input type="text" name="customer[company]" class="form-control mb-2">
                </div>
              </div>
              <div class="form-group">
                <label for="cust_address"><small>Alamat</small></label>
                <input type="text" name="customer[address]" class="form-control">
              </div>
              <div class="form-row">
                <div class="form-group col">
                  <label for="cust_phone"><small>Ponsel</small></label>
                  <input type="tel" name="customer[phone]" class="form-control">
                </div>
                <div class="form-group col">
                  <label for="cust_email"><small>Email</small></label>
                  <input type="email" name="customer[email]" class="form-control">
                </div>
              </div>

            </div>
          </div>

          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            <input type="submit" name="save_customer_data" class="btn btn-primary" id="save-customer" value="Simpan data">
          </div>

        </form>

      </div>
    </div>
  </div>

  <!-- Input Payment Modal -->
  <div class="modal fade" id="paymentModal" tabindex="-1" role="dialog">

    <div class="modal-dialog" role="document">

      <form action="" method="post" id="payment-form">

        <input type="hidden" name="debt[debt_id]" id="debt-id" value="<?= $debt_detail['debt_id'] ?? ''; ?>">
        <input type="hidden" name="debt[number]" id="debt-number" value="<?= $debt_detail['debt_number'] ?? ''; ?>">
        <input type="hidden" name="debt[customer_id]" id="customer-id" value="<?= $debt_detail['customer_id'] ?? ''; ?>">

        <input type="hidden" name="payment[payment_id]" id="modal-payment-id" value="">

        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Rekam Pembayaran</h5>
            <button type="button" class="close" data-dismiss="modal">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>

          <div class="modal-body">

            <div class="form-group">
              <label for="modal-payment-amount"><small>Nominal</small></label>
              <input type="text" name="payment[amount]" id="modal-payment-amount" class="form-control" value="0">
            </div>

            <div class="form-group">
              <label for="modal-payment-date"><small>Tanggal</small></label>
              <input type="date" name="payment[payment_date]" id="modal-payment-date" class="form-control">
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

<script>
  const filterInput = document.querySelector('#filter-input');

  filterInput.addEventListener('keyup', (e) => {

    let filterValue = filterInput.value.toUpperCase();

    let productNames = document.querySelectorAll('.product-name');

    for (let i = 0; i < productNames.length; i++) {
      const productName = productNames[i].innerHTML;

      // console.log(productNames[i].closest('.product-list-item'));

      if (productName.toUpperCase().indexOf(filterValue) > -1) {
        productNames[i].closest('.product-list-item').style.display = 'flex';
      } else {
        productNames[i].closest('.product-list-item').style.display = 'none';
      }
    };
  });
</script>
<!-- /.container-fluid -->

<?php if ($this->uri->segment(2) == 'sunting') : ?>

  <!-- Delete debt Modal-->
  <div class="modal fade" id="delOrderModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
      <form action="<?= base_url('processor/debt_pcsr/hapus_debt'); ?>" method="post" id="delete-debt-form">
        <input type="hidden" name="debt[debt_id]" id="debt-id" value="<?= $debt_detail['debt_id']; ?>">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Yakin akan menghapus?</h5>
            <button class="close" type="button" data-dismiss="modal">
              <span aria-hidden="true">×</span>
            </button>
          </div>
          <div class="modal-body">Klik "Hapus" jika Anda yakin untuk menghapus debt ini.</div>
          <div class="modal-footer">
            <button class="btn btn-secondary" type="button" data-dismiss="modal">Batal</button>
            <button type="submit" class="btn btn-primary" id="deldebtBtn">Hapus</button>
          </div>
        </div>
      </form>
    </div>
  </div>

  <!-- Update Payment Modal -->
  <div class="modal fade" id="updatePaymentModal" tabindex="-1" role="dialog">

    <div class="modal-dialog" role="document">

      <form action="<?= base_url('processor/debt_pcsr/perbarui_pembayaran') ?>" method="post" id="update-payment-form">

        <input type="hidden" name="debt[number]" id="debt-number" value="<?= $debt_detail['debt_number'] ?? ''; ?>">

        <input type="hidden" name="payment[payment_id]" id="update-payment-id" value="">

        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Rekam Pembayaran</h5>
            <button type="button" class="close" data-dismiss="modal">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>

          <div class="modal-body">

            <div class="form-group">
              <label for="update-payment-amount"><small>Nominal</small></label>
              <input type="text" name="payment[amount]" id="update-payment-amount" class="form-control" value="0">
            </div>

            <div class="form-group">
              <label for="update-payment-method"><small>Metode Pembayaran</small></label>
              <select name="payment[payment_method_id]" id="update-payment-method" class="custom-select">
                <option value="">Pilih...</option>

                <?php $payment_methods = $this->pembayaran_model->get_payment_method(); ?>

                <?php foreach ($payment_methods as $payment_method) : ?>
                  <option value="<?= $payment_method['payment_method_id']; ?>"><?= $payment_method['name']; ?></option>
                <?php endforeach; ?>

              </select>
            </div>

            <div class="form-group">
              <label for="update-payment-date"><small>Tanggal</small></label>
              <input type="date" name="payment[payment_date]" id="update-payment-date" class="form-control">
            </div>

          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
            <button type="submit" class="btn btn-primary" id="save-payment-btn">Perbarui data</button>
          </div>
        </div>
      </form>

    </div>
  </div>

  <!-- Delete Payment Modal-->
  <div class="modal fade" id="deletePaymentModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
      <form action="<?= base_url('processor/debt_pcsr/hapus_pembayaran'); ?>" method="post" id="delete-payment-form">
        <input type="hidden" name="debt[number]" id="debt-number" value="<?= $debt_detail['debt_number']; ?>">
        <input type="hidden" name="payment[payment_id]" id="del-payment-modal__payment-id" value="">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Yakin akan menghapus?</h5>
            <button class="close" type="button" data-dismiss="modal">
              <span aria-hidden="true">×</span>
            </button>
          </div>
          <div class="modal-body">Klik "Hapus" jika Anda yakin untuk menghapus pembayaran ini.</div>
          <div class="modal-footer">
            <button class="btn btn-secondary" type="button" data-dismiss="modal">Batal</button>
            <button type="submit" class="btn btn-primary" id="delPaymentBtn">Hapus</button>
          </div>
        </div>
      </form>
    </div>
  </div>

  <script>
    const processCard = document.querySelector('#process');

    processCard.addEventListener('click', (e) => {

      let clickedEl = e.target;

      // Grab order-id value from order-id data attribute of the respective row
      let currentRow = clickedEl.closest('tr');
      let orderId = currentRow.dataset.orderId;

      if (clickedEl.matches('.status-mark-trigger')) {


        // Grab status data
        let statusId = clickedEl.dataset.statusId;
        let statusName = clickedEl.dataset.statusName;

        // Assign order-id value to the order-id hidden input in the mark-as-finished-modal
        document.querySelector('#mark-modal-order-id').value = orderId

        // Assign status data to the modal
        document.querySelector('#mark-as-modal .modal-body').innerHTML = `
        Anda ingin mengubah status pesanan ini menjadi <strong>${statusName}?</strong>
      `;

        document.querySelector('#status-btn').value = statusId;

      }

    });
  </script>

<?php endif; ?>