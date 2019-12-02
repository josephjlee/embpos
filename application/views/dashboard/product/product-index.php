<!-- Begin Page Content -->
<div class="container-fluid">

  <!-- Page Heading -->
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h1 class="h3 text-gray-800"><?= $title; ?></h1>
  </div>

  <!-- Data Cards -->
  <div class="row">

    <!-- Stock Value -->

    <div class="col-xl-3 col-md-6 mb-4">
      <div class="card border-left-primary shadow h-100 py-2">
        <div class="card-body">
          <div class="row no-gutters align-items-center">
            <div class="col mr-2">
              <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Nilai Stok (<?= date('M') ?>)</div>
              <div class="h5 mb-0 font-weight-bold text-gray-800">Rp<?= moneyStrDot($stock_data['value']) ?></div>
            </div>
            <div class="col-auto">
              <i class="fas fa-coins fa-2x text-gray-300"></i>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Stock Quantity -->

    <div class="col-xl-3 col-md-6 mb-4">
      <div class="card border-left-success shadow h-100 py-2">
        <div class="card-body">
          <div class="row no-gutters align-items-center">
            <div class="col mr-2">
              <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Jumlah Stok (<?= date('M') ?>)</div>
              <div class="h5 mb-0 font-weight-bold text-gray-800"><?= moneyStrDot($stock_data['quantity']) ?>pcs</div>
            </div>
            <div class="col-auto">
              <i class="fas fa-gift fa-2x text-gray-300"></i>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Total Sold -->

    <?php $total_sold = $this->penjualan_model->get_total_sold_by_month(date('m')); ?>

    <div class="col-xl-3 col-md-6 mb-4">
      <div class="card border-left-info shadow h-100 py-2">
        <div class="card-body">
          <div class="row no-gutters align-items-center">
            <div class="col mr-2">
              <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Total Laku (<?= date('M') ?>)</div>
              <div class="h5 mb-0 font-weight-bold text-gray-800"><?= moneyStrDot($total_sold); ?>pcs</div>
            </div>
            <div class="col-auto">
              <i class="fas fa-dolly fa-2x text-gray-300"></i>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Total Sale -->

    <?php $total_sale = $this->penjualan_model->get_total_sale_by_month(date('m')); ?>

    <div class="col-xl-3 col-md-6 mb-4">
      <div class="card border-left-warning shadow h-100 py-2">
        <div class="card-body">
          <div class="row no-gutters align-items-center">
            <div class="col mr-2">
              <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Penjualan (<?= date('M') ?>)</div>
              <div class="h5 mb-0 font-weight-bold text-gray-800">Rp<?= moneyStrDot($total_sale); ?></div>
            </div>
            <div class="col-auto">
              <i class="fas fa-shopping-cart fa-2x text-gray-300"></i>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Orders Table -->
  <div class="card shadow mb-4">

    <div class="card-body" id="product-table-card">

      <table class="table table-hover" id="product-index-table">

        <thead class="thead-light">
          <tr>
            <th scope="col" class="text-center">Gambar</th>
            <th scope="col">SKU</th>
            <th scope="col">Nama barang</th>
            <th scope="col">Kategori</th>
            <th scope="col">Stok</th>
            <th scope="col">Laku</th>
            <th scope="col">Modal</th>
            <th scope="col">Jual</th>
            <th scope="col">#</th>
          </tr>
        </thead>
        <tbody style="font-size:14px">

          <?php foreach ($products as $product) : ?>

            <tr data-product-id="<?= $product['product_id']; ?>">
              <td class="text-center">
                <img style="width:33px;height:100%" src="<?= isset($product['image']) ? base_url('assets/img/product/') . $product['image'] : base_url('assets/icon/') . $product['item_icon']; ?>">
              </td>
              <td>
                <?= $product['sku']; ?>
              </td>
              <td>
                <a style="color:#858796" href="<?= base_url('produk/sunting/') . $product['product_id']; ?>">
                  <?= $product['title']; ?>
                </a>
              </td>
              <td>
                <?= $product['item_name']; ?>
              </td>
              <td>
                <?= moneyStrDot($product['stock']); ?>
              </td>
              <td>
                <?= moneyStrDot($product['sold']); ?>
              </td>
              <td>
                <span><?= moneyStrDot($product['base_price']) . ',00'; ?></span>
              </td>
              <td>
                <span><?= moneyStrDot($product['sell_price']) . ',00'; ?></span>
              </td>
              <td>
                <a class="dropdown-toggle text-right" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown">
                  <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
                </a>
                <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in">
                  <a href="<?= base_url('produk/sunting/') . $product['product_id']; ?>" class="dropdown-item">
                    Sunting Produk
                  </a>
                  <a href="#" data-toggle="modal" data-target="#del-product-modal" class="dropdown-item del-modal-trigger">Hapus Produk</a>
              </td>
            </tr>

          <?php endforeach; ?>

        </tbody>

      </table>

    </div>

  </div>

</div>
<!-- /.container-fluid -->

<!-- Delete Product Modal -->
<div class="modal fade" id="del-product-modal" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <form action="<?= base_url('processor/produk_pcsr/hapus_produk'); ?>" method="post" id="del-product-form">
      <input type="hidden" name="product[product_id]" id="modal-product-id" value="">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Yakin akan menghapus?</h5>
          <button class="close" type="button" data-dismiss="modal">
            <span aria-hidden="true">×</span>
          </button>
        </div>
        <div class="modal-body">Klik "Hapus" jika Anda yakin untuk menghapus produk ini.</div>
        <div class="modal-footer">
          <button class="btn btn-secondary" type="button" data-dismiss="modal">Batal</button>
          <button type="submit" class="btn btn-primary" id="del-product-btn">Hapus</button>
        </div>
      </div>
    </form>
  </div>
</div>

<script>
  const productCard = document.querySelector('#product-table-card');

  productCard.addEventListener('click', (e) => {

    let clickedEl = e.target;
    console.log(clickedEl);

    if (clickedEl.matches('.del-modal-trigger')) {

      // Grab product-id value from product-id data attribute of the respective row
      let currentRow = clickedEl.closest('tr');
      let productId = currentRow.dataset.productId;

      // Assign product-id value to the product-id hidden input in the mark-as-finished-modal
      document.querySelector('#modal-product-id').value = productId

    }

  });
</script>