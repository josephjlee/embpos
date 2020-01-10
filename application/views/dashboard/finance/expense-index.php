<!-- Begin Page Content -->
<div class="container-fluid" id="expense-index">

  <!-- Page Heading -->
  <div class=" row mb-2">
    <div class="col d-flex justify-content-between align-items-center">
      <h1 class="h3 text-gray-800 mr-auto"><?= $title; ?></h1>
      <a href="" class="badge badge-primary py-2 px-3 text-uppercase mr-2 shadow" id="input-cust-trigger" data-toggle="modal" data-target="#addVendorModal">Tambah Vendor</a>
      <a href="" class="badge badge-danger py-2 px-3 text-uppercase shadow" id="add-expense-trigger" data-toggle="modal" data-target="#expenseEditorModal">Catat Pengeluaran</a>
    </div>
  </div>

  <div class="row mb-4">

    <!-- Total Pengeluaran Bulan Ini -->

    <?php $total_expense = $this->keuangan_model->get_total_expense_by_month(date('m')); ?>

    <div class="col-xl-3 col-md-6">
      <div class="card border-left-info shadow h-100 py-2">
        <div class="card-body">
          <div class="row no-gutters align-items-center">
            <div class="col mr-2">
              <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Total Pengeluaran (<?= date('M') ?>)</div>
              <div class="h5 mb-0 font-weight-bold text-gray-800">Rp<?= empty($total_expense) ? 0 : moneyStrDot($total_expense['amount']); ?></div>
            </div>
            <div class="col-auto">
              <i class="fas fa-comment-dollar fa-2x text-gray-300"></i>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Kategori Pengeluaran Terbesar -->

    <?php $biggest_expense_category = $this->keuangan_model->get_the_biggest_expense_category_by_month(date('m')); ?>

    <div class="col-xl-3 col-md-6">
      <div class="card border-left-primary shadow h-100 py-2">
        <div class="card-body">
          <div class="row no-gutters align-items-center">
            <div class="col mr-2">
              <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Pos Terbesar (<?= date('M') ?>)</div>
              <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $biggest_expense_category ?? '-'; ?></div>
            </div>
            <div class="col-auto">
              <i class="fas fa-tag fa-2x text-gray-300"></i>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Pengeluaran Terbesar -->

    <?php $biggest_expense = $this->keuangan_model->get_the_biggest_expense_by_month(date('m')); ?>

    <div class="col-xl-3 col-md-6">
      <div class="card border-left-success shadow h-100 py-2">
        <div class="card-body">
          <div class="row no-gutters align-items-center">
            <div class="col mr-2">
              <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Belanja Terbesar (<?= date('M') ?>)</div>
              <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $biggest_expense ?? '-'; ?></div>
            </div>
            <div class="col-auto">
              <i class="fas fa-cart-plus fa-2x text-gray-300"></i>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Kategori Pengeluaran Tersering -->

    <?php $the_most_frequent = $this->keuangan_model->get_the_most_frequent_buy(date('m')); ?>

    <div class="col-xl-3 col-md-6">
      <div class="card border-left-warning shadow h-100 py-2">
        <div class="card-body">
          <div class="row no-gutters align-items-center">
            <div class="col mr-2" data-toggle="tooltip">
              <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Belanja Tersering (<?= date('M') ?>)</div>
              <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $the_most_frequent ?? '-'; ?></div>
            </div>
            <div class="col-auto">
              <i class="fas fa-star fa-2x text-gray-300"></i>
            </div>
          </div>
        </div>
      </div>
    </div>

  </div>

  <!-- Debt Table -->
  <div class="card shadow mb-4" id="debt-table-card">
    <div class="card-body">
      <table class="table table-hover" id="expenseDataTable">
        <thead class="thead-light">
          <tr>
            <th scope="col">ID</th>
            <th scope="col">Tanggal</th>
            <th scope="col">Deskripsi</th>
            <th scope="col">Kategori</th>
            <th scope="col">Nominal</th>
            <th scope="col">Vendor</th>
            <th scope="col" style="width: 6px !important">#</th>
          </tr>
        </thead>
      </table>
    </div>
  </div>

  <!-- Vendor Editor Modal -->
  <div class="modal fade" id="addVendorModal" tabindex="-1" role="dialog">

    <div class="modal-dialog" role="document">

      <div class="modal-content">

        <form id="vendorForm">

          <input type="hidden" name="vendor[vendor_id]" id="vendor_id" value="">

          <div class="modal-header">
            <h5 class="modal-title">Tambah Vendor Baru</h5>
            <button type="button" class="close" data-dismiss="modal">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>

          <div class="modal-body">

            <div class="form-row">

              <div class="form-group col">
                <label for="name">Nama lengkap</label>
                <input type="text" name="vendor[name]" id="name" class="form-control mb-2">
              </div>

              <div class="form-group col">
                <label for="selling">Menyediakan</label>
                <input type="text" name="vendor[selling]" id="selling" class="form-control mb-2">
              </div>

            </div>

            <div class="form-group">
              <label for="address">Alamat</label>
              <input type="text" name="vendor[address]" id="address" class="form-control">
            </div>

            <div class="form-row">

              <div class="form-group col">
                <label for="phone">Ponsel</label>
                <input type="tel" name="vendor[phone]" id="phone" class="form-control">
              </div>

              <div class="form-group col">
                <label for="email">Email</label>
                <input type="email" name="vendor[email]" id="email" class="form-control">
              </div>

            </div>

          </div>

          <div class="modal-footer">
            <button type="button" class="btn btn-secondary rounded" data-dismiss="modal">Close</button>
            <input type="submit" name="save_vendor_data" class="btn btn-primary" id="save-vendor" value="Simpan data">
          </div>

        </form>
      </div>
    </div>
  </div>

  <!-- Expense Editor Modal -->
  <div class="modal fade" id="expenseEditorModal" role="dialog">

    <div class="modal-dialog" role="document">

      <div class="modal-content">

        <form id="expenseForm">

          <input type="hidden" name="expense[expense_id]" id="expense-id" value="">

          <div class="modal-header">
            <h5 class="modal-title"></h5>
            <button type="button" class="close" data-dismiss="modal">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>

          <div class="modal-body">

            <div class="form-group">
              <label for="description"><small>Judul</small></label>
              <input type="text" name="expense[description]" id="description" class="form-control description" value="<?= $expense['description'] ?? '' ?>" required>
            </div>

            <?php $categories = $this->keuangan_model->list_all_expense_categories(); ?>

            <div class="form-row">

              <div class="form-group col">
                <label for="categories"><small>Kategori</small></label>
                <select name="expense[expense_category_id]" id="categories" required>
                  <option value="">Pilih Kategori</option>

                  <?php if (isset($expense_detail['category_id'])) : ?>

                    <?php foreach ($categories as $category) : ?>
                      <option value="<?= $category['category_id']; ?>" <?= $category['category_id'] == $expense_detail['category_id'] ? 'selected' : ''; ?>><?= $category['name']; ?></option>
                    <?php endforeach; ?>

                  <?php else : ?>

                    <?php foreach ($categories as $category) : ?>
                      <option value="<?= $category['expense_category_id']; ?>"><?= $category['name']; ?></option>
                    <?php endforeach; ?>

                  <?php endif; ?>
                </select>
              </div>

              <div class="form-group col">
                <label for="transaction-date"><small>Tanggal transaksi</small></label>
                <input type="date" name="expense[transaction_date]" id="transaction-date" class="form-control" value="<?= isset($expense_detail['transaction_date']) ? date('Y-m-d', strtotime($expense_detail['transaction_date'])) : ''; ?>" required>
              </div>

            </div>

            <?php $vendors = $this->vendor_model->list_all_vendors(); ?>

            <div class="form-row">
              <div class="form-group col">
                <label for="vendors"><small>Vendor</small></label>
                <select name="expense[vendor_id]" id="vendors" required>
                  <option value="">Pilih Vendor</option>

                  <?php if (isset($expense_detail['vendor_id'])) : ?>

                    <?php foreach ($vendors as $vendor) : ?>
                      <option value="<?= $vendor['vendor_id']; ?>" <?= $vendor['vendor_id'] == $expense_detail['vendor_id'] ? 'selected' : ''; ?>><?= $vendor['name']; ?></option>
                    <?php endforeach; ?>

                  <?php else : ?>

                    <?php foreach ($vendors as $vendor) : ?>
                      <option value="<?= $vendor['vendor_id']; ?>"><?= $vendor['name']; ?></option>
                    <?php endforeach; ?>

                  <?php endif; ?>
                </select>
              </div>
              <div class="form-group col">
                <label for="amount"><small>Nominal</small></label>
                <input type="number" name="expense[amount]" id="amount" class="form-control amount" value="<?= $expense['amount'] ?? '' ?>" required>
              </div>
            </div>

            <div class="form-group">
              <label for="note"><small>Catatan</small></label>
              <textarea name="expense[note]" id="note" class="form-control" style="font-size:13px"><?= $expense['note'] ?? ''; ?></textarea>
            </div>

          </div>

          <div class="modal-footer">
            <button type="button" class="btn btn-secondary rounded" data-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-primary" id="save-expense">Simpan data</button>
          </div>

        </form>
      </div>
    </div>
  </div>

  <!-- Delete Expense Modal -->
  <div class="modal fade" id="delExpenseModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
      <form action="" method="post" id="delete-expense-form">
        <input type="hidden" name="expense[expense_id]" id="expense-id" value="">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Yakin akan menghapus?</h5>
            <button class="close" type="button" data-dismiss="modal">
              <span aria-hidden="true">×</span>
            </button>
          </div>
          <div class="modal-body">Pengeluaran ini akan dihapus dari database.</div>
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