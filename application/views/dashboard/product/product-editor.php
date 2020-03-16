<?php $method = $this->uri->segment(2) == 'tambah' ? 'simpan' : 'perbarui' ?>

<form action="<?= base_url("action/produk_action/{$method}") ?>" enctype="multipart/form-data" accept-charset="utf-8" method="post" id="order-create-form" class="container-fluid create-order mb-4">

	<input type="hidden" name="product[product_id]" id="hidden-product-id" value="<?= $product['product_id'] ?? ''; ?>">

	<!-- Page Heading -->
	<div class="row">
		<div class="col d-flex justify-content-end align-items-center">
			<h1 class="h3 text-gray-800 mr-auto"><?= $title; ?></h1>
		</div>
	</div>

	<div class="row">

		<!-- Main Panel -->
		<div class="col-8 mt-2">

			<?php if ($this->session->flashdata('message')) : ?>
				<?= $this->session->flashdata('message'); ?>
			<?php endif; ?>

			<!-- Order Form -->
			<div class="card shadow mb-3">

				<div class="card-body">

					<div class="form-group">
						<label for="title"><small>Nama barang</small></label>
						<input type="text" name="product[title]" id="product-title" class="form-control product-title" value="<?= $product['title'] ?? ''; ?>">
					</div>

					<div class="form-group">
						<label for="description"><small>Deskripsi</small></label>
						<textarea name="product[description]" id="description" class="form-control description" rows="4"><?= $product['description'] ?? ''; ?></textarea>
					</div>

					<div class="form-row">

						<div class="form-group col">

							<label for="item"><small>Jenis barang</small></label>
							<select name="product[item_id]" id="item">

								<option value="">Pilih...</option>

								<?php $product_category = $this->produk_model->get_product_category(); ?>

								<?php if ($product['item_id']) : ?>
									<?php foreach ($product_category as $item) : ?>
										<option value="<?= $item['item_id']; ?>" <?= $product['item_id'] == $item['item_id'] ? 'selected' : ''; ?>><?= $item['name']; ?></option>
									<?php endforeach; ?>
								<?php endif; ?>

								<?php foreach ($product_category as $item) : ?>
									<option value="<?= $item['item_id']; ?>"><?= $item['name']; ?></option>
								<?php endforeach; ?>

							</select>

						</div>

						<div class="form-group col">
							<label for=""><small>Kode Barang (SKU)</small></label>
							<input type="text" name="product[sku]" id="sku" class="form-control" value="<?= $product['sku'] ?? ''; ?>">
						</div>

					</div>

					<div class="form-row">

						<div class="form-group col">
							<label for=""><small>Harga Modal (Rp)</small></label>
							<input type="text" name="product[base_price]" id="base-price" class="form-control number text-right" placeholder="0" value="<?= isset($product['base_price']) ? moneyStr($product['base_price']) : ''; ?>">
						</div>

						<div class="form-group col">
							<label for=""><small>Harga Jual (Rp)</small></label>
							<input type="text" name="product[sell_price]" id="sell-price" class="form-control number text-right" placeholder="0" value="<?= isset($product['sell_price']) ? moneyStr($product['sell_price']) : ''; ?>">
						</div>

						<div class="form-group col">
							<label for="stock"><small>Stok (pcs)</small></label>
							<input type="text" name="product[stock]" id="stock" class="form-control number text-right" value="<?= isset($product['stock']) ? moneyStr($product['stock']) : ''; ?>">
						</div>

					</div>

				</div>

			</div>

		</div>

		<!-- Side Panel -->
		<div class="col-4 mt-2 invoice-action-panel">

			<div class="card shadow mb-3">
				<!-- Card Header - Accordion -->
				<a href="#actionCard" class="d-block card-header py-3" data-toggle="collapse" role="button">
					<h6 class="m-0 font-weight-bold text-primary">Tindakan</h6>
				</a>
				<!-- Card Content - Collapse -->
				<div class="collapse show" id="actionCard">
					<div class="card-body d-flex align-items-center">
						<button type="submit" id="save-data-btn" class="mr-2 action-btn"><i class="fas fa-save fa-2x"></i></button>
						<a href="" class="action-btn" data-toggle="modal" data-target="#del-product-modal"><i class="fas fa-trash-alt fa-2x"></i></a>
					</div>
				</div>
			</div>

			<div class="card shadow mb-3">
				<!-- Card Header - Accordion -->
				<a href="#file-card__body" class="d-block card-header py-3" data-toggle="collapse" role="button">
					<h6 class="m-0 font-weight-bold text-primary">Gambar</h6>
				</a>
				<!-- Card Content - Collapse -->
				<div class="collapse show" id="file-card__body">
					<div class="card-body">

						<?php if (isset($product['image'])) : ?>
							<div class="d-flex align-items-center mb-3">
								<img src="<?= base_url('assets/img/product/') . $product['image']; ?>" alt="" class="img-thumbnail mr-2" style="width:15%;height:100%">
								<div>
									<p class="font-weight-bold my-0"><?= $product['image']; ?></p>
								</div>
								<button type="button" data-toggle="modal" data-target="#del-product-img-modal" class="close ml-auto">
									<span aria-hidden="true">&times;</span>
								</button>
							</div>
						<?php endif; ?>

						<div class="custom-file">
							<input type="file" name="image" class="custom-file-input" id="product-image">
							<label class="custom-file-label" for="customFile">Pilih file...</label>
						</div>
					</div>
				</div>
			</div>

		</div>

	</div>

</form>

<!-- Delete Product Image Modal-->
<div class="modal fade" id="del-product-img-modal" tabindex="-1" role="dialog">
	<div class="modal-dialog" role="document">
		<form action="<?= base_url('action/produk_action/lepas_gambar'); ?>" method="post" id="del-product-img-form">
			<input type="hidden" name="product[product_id]" id="product-id" value="<?= $product['product_id'] ?? ''; ?>">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title">Melepas gambar</h5>
					<button class="close" type="button" data-dismiss="modal">
						<span aria-hidden="true">×</span>
					</button>
				</div>
				<div class="modal-body">Gambar akan dilepas dari Produk ini tanpa menghapusnya dari sistem. Klik "Lepas" jika Anda yakin untuk melakukannya. </div>
				<div class="modal-footer">
					<button class="btn btn-secondary" type="button" data-dismiss="modal">Batal</button>
					<button type="submit" class="btn btn-primary" id="del-product-img-btn">Lepas</button>
				</div>
			</div>
		</form>
	</div>
</div>

<!-- Delete Product Modal -->
<div class="modal fade" id="del-product-modal" tabindex="-1" role="dialog">
	<div class="modal-dialog" role="document">
		<form action="<?= base_url('action/produk_action/hapus_produk'); ?>" method="post" id="del-product-form">
			<input type="hidden" name="product[product_id]" id="product-id" value="<?= $product['product_id'] ?? ''; ?>">
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