<!-- Begin Page Content -->
<div class="container-fluid">

  <!-- Page Heading -->
  <div class="row mb-2">
    <div class="col d-flex justify-content-between align-items-center">
      <h1 class="h3 text-gray-800"><?= $title; ?></h1>
      <a href="" class="badge badge-primary py-2 px-3 text-uppercase" id="input-cust-trigger" data-toggle="modal" data-target="#addCustomerModal">+ Pelanggan</a>
    </div>
  </div>

  <div class="row">

    <!-- Total Customer -->

    <?php $total_customer = $this->pelanggan_model->get_total_customer(); ?>

    <div class="col-xl-3 col-md-6 mb-4">
      <div class="card border-left-primary shadow h-100 py-2">
        <div class="card-body">
          <div class="row no-gutters align-items-center">
            <div class="col mr-2">
              <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Total Pelanggan (<?= date('M') ?>)</div>
              <div class="h5 mb-0 font-weight-bold text-gray-800"><?= moneyStrDot($total_customer); ?></div>
            </div>
            <div class="col-auto">
              <i class="fas fa-user fa-2x text-gray-300"></i>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Most Order -->

    <?php $most_order = $this->pelanggan_model->get_most_order_by_month(date('m')); ?>

    <div class="col-xl-3 col-md-6 mb-4">
      <div class="card border-left-success shadow h-100 py-2">
        <div class="card-body">
          <div class="row no-gutters align-items-center">
            <div class="col mr-2">
              <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Pemesan Terbanyak (<?= date('M') ?>)</div>
              <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $most_order ?? '-'; ?></div>
            </div>
            <div class="col-auto">
              <i class="fas fa-box-open fa-2x text-gray-300"></i>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Most Buy -->

    <?php $most_buy = $this->pelanggan_model->get_most_buy_by_month(date('m')); ?>

    <div class="col-xl-3 col-md-6 mb-4">
      <div class="card border-left-info shadow h-100 py-2">
        <div class="card-body">
          <div class="row no-gutters align-items-center">
            <div class="col mr-2">
              <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Pembeli Terbanyak (<?= date('M') ?>)</div>
              <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $most_buy ?? '-'; ?></div>
            </div>
            <div class="col-auto">
              <i class="fas fa-gift fa-2x text-gray-300"></i>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Most Sale -->

    <?php $the_most_valuable = $this->pelanggan_model->get_most_valueable_by_month(date('m')); ?>

    <div class="col-xl-3 col-md-6 mb-4">
      <div class="card border-left-warning shadow h-100 py-2">
        <div class="card-body">
          <div class="row no-gutters align-items-center">
            <div class="col mr-2">
              <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Belanja Terbanyak (<?= date('M') ?>)</div>
              <div class="h6 mb-0 font-weight-bold text-gray-800"><?= word_limiter($the_most_valuable, 2, ' ...'); ?></div>
            </div>
            <div class="col-auto">
              <i class="fas fa-receipt fa-2x text-gray-300"></i>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Customer Table -->
  <div class="card shadow mb-4" id="customer-table-card">
    <div class="card-body">
      <table class="table table-hover" id="dataTable">
        <thead class="thead-light">
          <tr>
            <th scope="col">Nama</th>
            <th scope="col">Telepon</th>
            <th scope="col">Email</th>
            <th scope="col">Alamat</th>
            <th scope="col">Beli</th>
            <th scope="col">Pesan</th>
            <th scope="col">Belanja</th>
            <th scope="col">#</th>
          </tr>
        </thead>
        <tbody style="font-size:14px">

          <?php foreach ($customers as $customer) : ?>

            <tr data-id="<?= $customer['customer_id'] ?>" data-name="<?= $customer['customer_name']; ?>" data-phone="<?= $customer['customer_phone']; ?>" data-address="<?= $customer['customer_address']; ?>" data-email="<?= $customer['cust_email']; ?>" data-company="<?= $customer['customer_company']; ?>">

              <td data-sort="<?= $customer['customer_name']; ?>">
                <?php if ($customer['customer_company']) : ?>
                  <small><?= $customer['customer_company']; ?></small>
                  <p class="my-0"><?= $customer['customer_name']; ?></p>
                <?php else : ?>
                  <p class="my-2"><?= $customer['customer_name']; ?></p>
                <?php endif; ?>
              </td>

              <td>
                <?= $customer['customer_phone'] ? "{$customer['customer_phone']}" : 'tidak diketahui'; ?>
              </td>

              <td>
                <?= $customer['cust_email'] ? "{$customer['cust_email']}" : 'tidak diketahui'; ?>
              </td>

              <td>
                <?= $customer['customer_address'] ? "{$customer['customer_address']}" : 'tidak diketahui'; ?>
              </td>

              <td>
                <?= moneyStrDot($customer['buy_qty']); ?>
              </td>

              <td>
                <?= moneyStrDot($customer['order_qty']); ?>
              </td>

              <td>
                <?= moneyStrDot($customer['total_value']); ?>,00
              </td>

              <td>
                <a class="dropdown-toggle text-right" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown">
                  <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
                </a>
                <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in">
                  <div class="dropdown-header">Tindakan:</div>
                  <a href="" class="dropdown-item edit-modal-trigger" data-toggle="modal" data-target="#addCustomerModal">Sunting Detail</a>
                  <a href="" class="dropdown-item del-modal-trigger" data-toggle="modal" data-target="#delCustomerModal">Hapus Pelanggan</a>
                </div>
              </td>

            </tr>

          <?php endforeach; ?>

        </tbody>
      </table>
    </div>
  </div>

  <!-- Add/Update Customer Modal -->
  <div class="modal fade" id="addCustomerModal" tabindex="-1" role="dialog">

    <div class="modal-dialog" role="document">

      <div class="modal-content">

        <form action="<?= base_url('processor/pelanggan_pcsr/simpan_data'); ?>" method="post" id="customerForm">

          <input type="hidden" name="customer[customer_id]" id="cust_id" value="">

          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Tambah Pelanggan Baru</h5>
            <button type="button" class="close" data-dismiss="modal">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>

          <div class="modal-body">
            <div class="mb-4">
              <div class="form-row">
                <div class="form-group col">
                  <label for="cust_name">Nama lengkap</label>
                  <input type="text" name="customer[name]" id="cust_name" class="form-control mb-2">
                </div>
                <div class="form-group col">
                  <label for="cust_company">Afiliasi</label>
                  <input type="text" name="customer[company]" id="cust_company" class="form-control mb-2">
                </div>
              </div>
              <div class="form-group">
                <label for="cust_address">Alamat</label>
                <input type="text" name="customer[address]" id="cust_address" class="form-control">
              </div>
              <div class="form-row">
                <div class="form-group col">
                  <label for="cust_phone">Ponsel</label>
                  <input type="tel" name="customer[phone]" id="cust_phone" class="form-control">
                </div>
                <div class="form-group col">
                  <label for="cust_email">Email</label>
                  <input type="email" name="customer[email]" id="cust_email" class="form-control">
                </div>
              </div>
            </div>
          </div>

          <div class="modal-footer">
            <button type="button" class="btn btn-secondary rounded" data-dismiss="modal">Close</button>
            <input type="submit" name="save_customer_data" class="btn btn-primary" id="save-customer" value="Simpan data">
          </div>

        </form>
      </div>
    </div>
  </div>

  <!-- Delete Customer Modal -->
  <div class="modal fade" id="delCustomerModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
      <form action="<?= base_url('processor/pelanggan_pcsr/hapus_data'); ?>" method="post" id="delete-order-form">
        <input type="hidden" name="customer[customer_id]" id="cust_id" value="">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Yakin akan menghapus?</h5>
            <button class="close" type="button" data-dismiss="modal">
              <span aria-hidden="true">×</span>
            </button>
          </div>
          <div class="modal-body">Menghapus data pelanggan juga akan menghapus seluruh data pesanan yang pernah dibuatnya.</div>
          <div class="modal-footer">
            <button class="btn btn-secondary" type="button" data-dismiss="modal">Batal</button>
            <button type="submit" class="btn btn-primary">Hapus</button>
          </div>
        </div>
      </form>
    </div>
  </div>

</div>
<!-- /.container-fluid -->