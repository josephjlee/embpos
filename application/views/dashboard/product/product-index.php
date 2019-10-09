<!-- Begin Page Content -->
<div class="container-fluid">

  <!-- Page Heading -->
  <h1 class="h3 mb-3 text-gray-800"><?= $title; ?></h1>

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