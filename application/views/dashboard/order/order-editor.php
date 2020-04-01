<!-- Begin Page Content -->
<?php $method = $this->uri->segment(2) == 'buat' ? 'simpan' : 'perbarui'; ?>

<form action="<?= base_url("action/pesanan_action/{$method}"); ?>" enctype="multipart/form-data" accept-charset="utf-8" method="post" id="order-create-form" class="container-fluid create-order mb-4">

	<input type="hidden" name="order[order_id]" value="<?= $order['order_id'] ?? ''; ?>">
	<input type="hidden" name="redirect-here" value="<?= $this->uri->uri_string(); ?>">

	<!-- Page Heading -->
	<div class="row">

		<div class="col d-flex justify-content-start align-items-center">

			<h1 class="h3 text-gray-800 mr-2"><?= $title; ?></h1>

			<?php if ($this->uri->segment(2) == 'sunting') : ?>

				<a href="<?= base_url('pesanan/pratinjau/') . $order['order_number']; ?>" class="pb-1 action-btn"><i class="fas fa-fw fa-file-image fa-lg"></i></a>

				<div class="ml-auto">

					<a href="<?= $is_invoiced['number'] ? base_url('keuangan/sunting_invoice/') . $is_invoiced['number'] : '#'; ?>"><span class="badge badge-primary py-2 px-3 text-uppercase mr-2"><?= $is_invoiced['message']; ?></span></a>

					<a href="#"><span class="badge badge-danger py-2 px-3 text-uppercase"><?= $order['production_status']; ?></span></a>

				</div>

			<?php endif; ?>

		</div>

	</div>

	<!-- Page Content -->
	<div class="row">

		<!-- Main Panel -->
		<div class="col-8 mt-2">

			<!-- Alert Message -->
			<?= $this->session->flashdata('message') ?? ''; ?>

			<!-- Order Form - Header -->
			<div class="card shadow mb-3">

				<div class="card-body">

					<div class="d-flex justify-content-between align-items-center mb-2">

						<div class="input-group mr-2">
							<div class="input-group-prepend">
								<div class="input-group-text"><i class="fas fa-box fa-fw"></i></div>
							</div>
							<input type="text" name="order[number]" class="form-control" id="order-id" value="<?= $order['order_number']; ?>" placeholder="ID Barang">
						</div>

						<div class="input-group customer-select-wrapper">
							<div class="input-group-prepend">
								<a href="" class="input-group-text" id="add-customer" data-toggle="modal" data-target="#addCustomerModal"><i class="fas fa-user fa-fw"></i></a>
							</div>

							<?php $customers = $this->pelanggan_model->get_all_customers(); ?>

							<select name="order[customer_id]" id="customer-select" class="custom-select">

								<option value="">Piih pelanggan...</option>

								<?php foreach ($customers as $customer) : ?>

									<?php if ($this->uri->segment(2) == 'sunting') : ?>
										<option value="<?= $customer['customer_id']; ?>" <?= $customer['customer_id'] == $order['customer_id'] ? 'selected' : ''; ?>>
											<?= $customer['customer_name']; ?>
										</option>
									<?php endif; ?>

									<option value="<?= $customer['customer_id']; ?>">
										<?= $customer['customer_name']; ?>
									</option>

								<?php endforeach; ?>

							</select>
						</div>

					</div>

					<div class="d-flex justify-content-between align-items-center">
						<div class="input-group mr-2">
							<div class="input-group-prepend">
								<div class="input-group-text"><i class="fas fa-calendar-plus fa-fw"></i></div>
							</div>
							<input class="form-control" type="date" name="order[received_date]" id="received-date" value="<?= isset($order['received_date']) ? date('Y-m-d', strtotime($order['received_date'])) : ''; ?>">
						</div>

						<div class="input-group">
							<div class="input-group-prepend">
								<div class="input-group-text"><i class="fas fa-calendar-check fa-fw"></i></div>
							</div>
							<input class="form-control" type="date" name="order[required_date]" id="required-date" value="<?= isset($order['required_date']) ? date('Y-m-d', strtotime($order['required_date'])) : ''; ?>">
						</div>
					</div>

				</div>
			</div>

			<!-- Order Form - Body -->
			<div class="card shadow mb-3" id="order-form-body">

				<div class="card-body">

					<div class="form-group">
						<label for="description"><small>Judul bordiran</small></label>
						<input type="text" name="order[description]" id="description" class="form-control description" value="<?= $order['description'] ?? '' ?>">
					</div>

					<div class="form-row">

						<div class="form-group col">

							<label for="item"><small>Jenis barang</small></label>
							<select name="order[item_id]" id="item">

								<option value="">Pilih...</option>

								<?php $items = $this->pesanan_model->get_all_items(); ?>

								<?php foreach ($items as $item) : ?>

									<?php if ($this->uri->segment(2) == 'sunting') : ?>

										<option value="<?= $item['item_id']; ?>" data-priceconst="<?= $item['item_pc']; ?>" <?= $item['item_id'] == $order['item_id'] ? 'selected' : ''; ?>>
											<?= $item['item_name'] ?>
										</option>

									<?php endif; ?>

									<option value="<?= $item['item_id']; ?>" data-priceconst="<?= $item['item_pc']; ?>">
										<?= $item['item_name'] ?>
									</option>

								<?php endforeach; ?>

							</select>

						</div>

						<div class="form-group col">

							<label for="position"><small>Posisi yang diinginkan</small></label>
							<select name="order[position_id]" id="position" class="custom-select position">
								<option value="">Pilih...</option>

								<?php if ($this->uri->segment(2) == 'sunting') : ?>

									<?php $positions = $this->pesanan_model->get_position_by_item_id($order['item_id']); ?>

									<?php foreach ($positions as $position) : ?>

										<option value="<?= $position['position_id']; ?>" <?= $position['position_id'] == $order['position_id'] ? 'selected' : ''; ?>>
											<?= $position['name'] ?>
										</option>

									<?php endforeach; ?>

								<?php endif; ?>

								<?php $positions = $this->pesanan_model->get_item_position_pairs(); ?>

								<?php foreach ($positions as $position) : ?>

									<option value="<?= $position['position_id']; ?>">
										<?= $position['name'] ?>
									</option>

								<?php endforeach; ?>


							</select>

						</div>

					</div>

					<div class="form-row">

						<div class="form-group col">
							<label for="dimension"><small>Dimensi</small></label>
							<input type="text" name="order[dimension]" id="dimension" class="form-control dimension number" value="<?= $order['dimension'] ?? ''; ?>">
						</div>
						<div class="form-group col">
							<label for=""><small>Warna</small></label>
							<input type="text" name="order[color]" id="color" class="form-control color number" value="<?= $order['color'] ?? ''; ?>">
						</div>
						<div class="form-group col">
							<label for=""><small>Bahan</small></label>
							<input type="text" name="order[material]" id="material" class="form-control material number" value="<?= $order['material'] ?? ''; ?>">
						</div>

					</div>

					<div class="form-row">

						<div class="form-group col">
							<label for="quantity"><small>Kuantitas</small></label>
							<input type="text" name="order[quantity]" id="quantity" class="form-control quantity number text-right" value="<?= isset($order['quantity']) ? moneyStr($order['quantity']) : ''; ?>">
						</div>

						<div class="form-group col">
							<label for=""><small>Harga</small></label>
							<input type="text" name="order[price]" id="price" class="form-control price number text-right" value="<?= isset($order['price']) ? moneyStr($order['price']) : ''; ?>">
						</div>

					</div>

					<div class="row px-2 mb-2" id="stitch-row"></div>

					<div class="form-group">
						<label for=""><small>Total</small></label>
						<input type="text" name="" id="amount" class="form-control amount" value="<?= isset($order['amount']) ? moneyStr($order['amount']) : ''; ?>" readonly>
					</div>

					<div class="form-group">
						<label for="note"><small>Catatan</small></label>
						<textarea name="order[note]" id="note" class="form-control" style="font-size:13px"><?= $order['note'] ?? ''; ?></textarea>
					</div>

				</div>

			</div>

			<!-- Production Form -->
			<div class="card shadow">
				<div class="card-body">
					<div class="form-group">
						<label for="harga"><small>Harga Operator (disarankan: Rp<?= moneyStrDot($order['price'] - 250); ?>)</small></label>
						<input type="text" name="order[labor_price]" id="harga" class="form-control" value="<?= $production['labor_price']; ?>" placeholder="0">
					</div>

					<p class="mb-1"><small>Kerjakan di Mesin:</small></p>
					<div class="form-row">
						<?php for ($i = 1; $i < 7; $i++) : ?>
							<div class="form-group col">
								<div class="custom-control custom-checkbox col">
									<input type="checkbox" name="order[machine_number][]" class="custom-control-input" id="machine-<?= $i; ?>" value="<?= $i; ?>" <?= in_array($i, explode(',', $production['machine'])) ? 'checked' : ''; ?>>
									<label class="custom-control-label" for="machine-<?= $i; ?>"><?= $i; ?></label>
								</div>
							</div>
						<?php endfor; ?>
					</div>

				</div>

			</div>

		</div>

		<!-- Side Panel -->
		<div class="col-4 mt-2 invoice-action-panel">

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

						<?php if ($this->uri->segment(2) == 'sunting') : ?>
							<a href="#" data-toggle="modal" data-target="#update-process-modal" class="action-btn"><i class="fas fa-tasks fa-2x"></i></a>
						<?php endif; ?>

						<a href="#" data-toggle="modal" data-target="#output-modal" class="action-btn" id="new-output-trigger"><i class="fas fa-calculator fa-2x"></i></a>

						<a href="#" data-toggle="modal" data-target="#del-order-modal" class="action-btn"><i class="fas fa-trash-alt fa-2x"></i></a>

					</div>
				</div>
			</div>

			<!-- Artwork Upload Card -->
			<div class="card shadow mb-3">
				<!-- Card Header - Accordion -->
				<a href="#file-card__body" class="d-block card-header py-3" data-toggle="collapse" role="button">
					<h6 class="m-0 font-weight-bold text-primary">File Gambar</h6>
				</a>
				<!-- Card Content - Collapse -->
				<div class="collapse show" id="file-card__body">
					<div class="card-body">

						<?php if (!empty($order['image'])) : ?>
							<div class="d-flex align-items-center">
								<img src="<?= base_url('assets/img/artwork/') . $order['image']; ?>" alt="" class="img-thumbnail mr-2" style="width:15%;height:100%">
								<div>
									<p class="font-weight-bold my-0"><?= $order['image']; ?></p>
									<!-- <small>Diunggah pada: 29 Juli 2019</small> -->
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

			<?php if ($this->uri->segment(2) == 'sunting') : ?>

				<!-- Output History Card -->
				<div class="card shadow mb-3">

					<a href="#output-history" class="d-block card-header py-3" data-toggle="collapse" role="button">
						<h6 class="m-0 font-weight-bold text-primary">Riwayat Bordir</h6>
					</a>

					<div class="collapse show" id="output-history">

						<div class="card-body py-0">

							<table class="table">

								<tbody>

									<?php $total_output = 0; ?>

									<?php $output_records = $this->produksi_model->get_embro_output_by_order_id($order['order_id']); ?>

									<?php if (!empty($output_records)) : ?>

										<?php foreach ($output_records as $output) : ?>

											<tr data-output-id="<?= $output['output_id']; ?>" data-output-quantity="<?= $output['quantity']; ?>" data-output-operator="<?= $output['employee_id']; ?>" data-output-shift="<?= $output['shift']; ?>" data-output-started="<?= $output['started']; ?>" data-output-finished="<?= $output['finished']; ?>" data-output-machine="<?= $output['machine']; ?>" data-output-helper="<?= $output['is_helper']; ?>">

												<td class="px-0">
													<small id="output-date-display" style="color:#ec8615">
														<span id="output-amount-display"><?= moneyStr($output['quantity']); ?></span>pcs
													</small>
													<p class="my-0">
														<a href="<?= base_url('produksi/detail_bordir/') . $order['order_id']; ?>" style="font-size:14px;" id="output-name-display">
															<span style="color:#495057"><?= date('d-m-Y', strtotime($output['finished'])); ?> | <?= date('H:i', strtotime($output['finished'])); ?> | <?= $output['machine'] ?> |</span> <?= $output['operator']; ?> <?= $output['is_helper'] == 1 ? '(asisten)' : ''; ?>
														</a>
													</p>
												</td>

												<td class="px-0 align-middle text-right">
													<a class="dropdown-toggle text-right" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown">
														<i class="fas fa-ellipsis-v fa-sm fa-fw" style="color:#aba9bf"></i>
													</a>
													<div class="dropdown-menu dropdown-menu-right shadow animated--fade-in">
														<div class="dropdown-header">Tindakan:</div>
														<a href="" class="dropdown-item update-output-trigger" data-toggle="modal" data-target="#output-modal">Sunting Detail</a>
														<a href="" class="dropdown-item del-output-trigger" data-toggle="modal" data-target="#del-output-modal">Hapus Output</a>
													</div>
												</td>

											</tr>

											<?php $total_output += (int) $output['quantity']; ?>

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

				<!-- Order Progress Card -->
				<div class="card shadow mb-4">
					<div class="card-header py-3">
						<h6 class="m-0 font-weight-bold text-primary">Perkembangan</h6>
					</div>
					<div class="card-body">
						<h4 class="small font-weight-bold">Desain <span class="float-right"><?= $production_status['design']; ?>%</span></h4>
						<div class="progress mb-4">
							<div class="progress-bar bg-danger" id="design-progress-bar" role="progressbar" data-toggle="tooltip" title="<?= $production_output['design']; ?>" <?= 'style="width:' . $production_status['design'] . '%"'; ?>></div>
						</div>
						<h4 class="small font-weight-bold">Bordir <span class="float-right"><?= $production_status['embro']; ?>%</span></h4>
						<div class="progress mb-4">
							<div class="progress-bar bg-warning" id="embro-progress-bar" role="progressbar" data-toggle="tooltip" title="<?= $production_output['embro']; ?>pcs" <?= 'style="width:' . $production_status['embro'] . '%"'; ?>></div>
						</div>
						<h4 class="small font-weight-bold">Finishing <span class="float-right"><?= $production_status['finishing']; ?>%</span></h4>
						<div class="progress mb-4">
							<div class="progress-bar" id="finishing-progress-bar" role="progressbar" data-toggle="tooltip" title="<?= $production_output['finishing']; ?>pcs" <?= 'style="width:' . $production_status['finishing'] . '%"'; ?>></div>
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
								<input type="text" name="customer[name]" id="cust_name" class="form-control mb-2">
							</div>
							<div class="form-group col">
								<label for="cust_company"><small>Afiliasi</small></label>
								<input type="text" name="customer[company]" id="cust_company" class="form-control mb-2">
							</div>
						</div>
						<div class="form-group">
							<label for="cust_address"><small>Alamat</small></label>
							<input type="text" name="customer[address]" id="cust_address" class="form-control">
						</div>
						<div class="form-row">
							<div class="form-group col">
								<label for="cust_phone"><small>Ponsel</small></label>
								<input type="tel" name="customer[phone]" id="cust_phone" class="form-control">
							</div>
							<div class="form-group col">
								<label for="cust_email"><small>Email</small></label>
								<input type="email" name="customer[email]" id="cust_email" class="form-control">
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

<?php if ($this->uri->segment(2) == 'sunting') : ?>

	<!-- Delete Artwork Modal-->
	<div class="modal fade" id="del-artwork-modal" tabindex="-1" role="dialog">
		<div class="modal-dialog" role="document">
			<form action="<?= base_url('action/pesanan_action/lepas_artwork'); ?>" method="post" id="del-order-artwork-form">
				<input type="hidden" name="order[order_id]" id="order-id" value="<?= $order['order_id'] ?? ''; ?>">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title">Melepas gambar</h5>
						<button class="close" type="button" data-dismiss="modal">
							<span aria-hidden="true">×</span>
						</button>
					</div>
					<div class="modal-body">Gambar akan dilepas dari Pesanan ini tanpa menghapusnya dari sistem. Klik "Lepas" jika Anda yakin untuk melakukannya. </div>
					<div class="modal-footer">
						<button class="btn btn-secondary" type="button" data-dismiss="modal">Batal</button>
						<button type="submit" class="btn btn-primary" id="del-artwork-btn">Lepas</button>
					</div>
				</div>
			</form>
		</div>
	</div>

	<!-- Delete Order Modal -->
	<div class="modal fade" id="del-order-modal" tabindex="-1" role="dialog">
		<div class="modal-dialog" role="document">
			<form action="<?= base_url('action/pesanan_action/hapus_pesanan'); ?>" method="post" id="del-order-form">
				<input type="hidden" name="order[order_id]" id="modal-order-id" value="<?= $order['order_id']; ?>">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title">Yakin akan menghapus?</h5>
						<button class="close" type="button" data-dismiss="modal">
							<span aria-hidden="true">×</span>
						</button>
					</div>
					<div class="modal-body">Klik "Hapus" jika Anda yakin untuk menghapus pesanan ini.</div>
					<div class="modal-footer">
						<button class="btn btn-secondary" type="button" data-dismiss="modal">Batal</button>
						<button type="submit" class="btn btn-primary" id="del-order-btn">Hapus</button>
					</div>
				</div>
			</form>
		</div>
	</div>

	<!-- Mark as Modal-->
	<div class="modal fade" id="update-process-modal" tabindex="-1" role="dialog">
		<div class="modal-dialog" role="document">

			<form action="<?= base_url('action/pesanan_action/tandai_sebagai'); ?>" method="post" id="update-process-form">

				<input type="hidden" name="order[order_id]" id="order-id" value="<?= $order['order_id']; ?>">

				<div class="modal-content">

					<div class="modal-header">
						<h5 class="modal-title">Status Pengerjaan</h5>
						<button class="close" type="button" data-dismiss="modal">
							<span aria-hidden="true">×</span>
						</button>
					</div>

					<div class="modal-body">

						<select name="order[production_status_id]" id="process-status" class="custom-select">

							<option value="">Pilih...</option>

							<?php $process_list = $this->db->get('production_status')->result_array(); ?>

							<?php foreach ($process_list as $status) : ?>
								<option value="<?= $status['production_status_id']; ?>" <?= $status['production_status_id'] == $order['production_status_id'] ? 'selected' : ''; ?>><?= $status['name']; ?></option>
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

	<!-- Set Production Modal-->
	<div class="modal fade" id="spec-modal" tabindex="-1" role="dialog" data-repeat="<?= $production['repeat'] ?? ''; ?>">

		<div class="modal-dialog" role="document">

			<form action="<?= base_url('action/produksi_action/atur_produksi'); ?>" method="post" id="spec-form">

				<input type="hidden" name="input-src" value="<?= current_url(); ?>">

				<input type="hidden" name="production[order_id]" id="order-id" value="<?= $order['order_id']; ?>">

				<div class="modal-content">

					<div class="modal-header">
						<h5 class="modal-title">Atur Produksi</h5>
						<button class="close" type="button" data-dismiss="modal">
							<span aria-hidden="true">×</span>
						</button>
					</div>

					<div class="modal-body">

						<div class="form-group">
							<label for=""><small>Pengerjaan</small></label>
							<select id="production-type" class="custom-select">
								<option value="">Pilih...</option>
								<option value="material">Bahan Baku</option>
								<option value="design">Desain</option>
								<option value="embroidery">Bordir</option>
							</select>
						</div>

						<div id="unique-form-wrapper"></div>

					</div>

					<div class="modal-footer">
						<button class="btn btn-secondary" type="button" data-dismiss="modal">Batal</button>
						<button type="submit" class="btn btn-primary" id="set-spec-btn">Atur</button>
					</div>

				</div>

			</form>

		</div>
	</div>

	<!-- editMachineModal -->
	<div class="modal fade" id="editMachineModal" tabindex="-1" role="dialog" data-repeat="<?= $production['repeat'] ?? ''; ?>">

		<div class="modal-dialog" role="document">

			<form action="<?= base_url('action/produksi_action/atur_produksi'); ?>" method="post" id="machine-form">

				<input type="hidden" name="input-src" value="<?= current_url(); ?>">

				<input type="hidden" name="production[order_id]" id="order-id" value="<?= $order['order_id']; ?>">

				<input type="hidden" name="production[production_id]" id="production-id" value="">

				<div class="modal-content">

					<div class="modal-header">
						<h5 class="modal-title">Sunting Mesin</h5>
						<button class="close" type="button" data-dismiss="modal">
							<span aria-hidden="true">×</span>
						</button>
					</div>

					<div class="modal-body">

						<div class="form-group">
							<label for="machine-number"><small>Mesin</small></label>
							<select name="production[machine]" id="machine-number" class="custom-select">
								<option value="">Pilih...</option>
								<option value="1">Mesin-1</option>
								<option value="2">Mesin-2</option>
								<option value="3">Mesin-3</option>
								<option value="4">Mesin-4</option>
								<option value="5">Mesin-5</option>
								<option value="6">Mesin-6</option>
							</select>
						</div>
						<div class="form-group">
							<label for="machine-price"><small>Harga</small></label>
							<input type="number" name="production[labor_price]" id="machine-price" class="form-control">
						</div>

					</div>

					<div class="modal-footer">
						<button class="btn btn-secondary" type="button" data-dismiss="modal">Batal</button>
						<button type="submit" class="btn btn-primary" id="set-spec-btn">Atur</button>
					</div>

				</div>

			</form>

		</div>
	</div>

	<!-- deleteMachineModal -->
	<div class="modal fade" id="deleteMachineModal" tabindex="-1" role="dialog">
		<div class="modal-dialog" role="document">
			<form action="<?= base_url('action/produksi_action/hapus_produksi'); ?>" method="post" id="del-machine-form">
				<input type="hidden" name="production[production_id]" id="modal-production-id" value="">
				<input type="hidden" name="requester" value="<?= current_url(); ?>">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title">Yakin akan menghapus?</h5>
						<button class="close" type="button" data-dismiss="modal">
							<span aria-hidden="true">×</span>
						</button>
					</div>
					<div class="modal-body">Klik "Hapus" jika Anda yakin untuk menghapus produksi di mesin ini.</div>
					<div class="modal-footer">
						<button class="btn btn-secondary" type="button" data-dismiss="modal">Batal</button>
						<button type="submit" class="btn btn-primary" id="del-order-btn">Hapus</button>
					</div>
				</div>
			</form>
		</div>
	</div>

	<!-- Output Modal -->
	<div class="modal fade" id="output-modal" tabindex="-1" role="dialog">

		<div class="modal-dialog" role="document">

			<form action="<?= base_url('action/produksi_action/rekam_output_operator'); ?>" method="post" id="output-form">

				<?php $embro_detail = $this->produksi_model->get_order_production_detail($order['order_id']); ?>
				<input type="hidden" name="order[order_id]" id="order-id" value="<?= $embro_detail['order_id']; ?>">
				<input type="hidden" name="output[output_embro_id]" value="" id="output-embro-id">
				<input type="hidden" name="current-output" value="<?= $total_output; ?>">
				<input type="hidden" name="order-qty" value="<?= $embro_detail['quantity']; ?>">
				<input type="hidden" name="input-src" value="<?= current_url(); ?>">

				<div class="modal-content">

					<div class="modal-header">
						<h5 class="modal-title"></h5>
						<button type="button" class="close" data-dismiss="modal">
							<span aria-hidden="true">&times;</span>
						</button>
					</div>

					<div class="modal-body">

						<div class="form-group">

							<label for="modal-operator"><small>Operator</small></label>


							<select name="output[employee_id]" id="modal-operator" class="custom-select">

								<option value="">Pilih operator</option>

								<?php $operators = $this->produksi_model->get_employee_name_by_job_id(2); ?>

								<?php foreach ($operators as $operator) : ?>
									<option value="<?= $operator['employee_id']; ?>"><?= $operator['nick_name']; ?></option>
								<?php endforeach; ?>

							</select>

						</div>

						<div class="form-row">
							<div class="form-group col">
								<label for="started"><small>Mulai</small></label>
								<input type="date" name="output[started]" id="modal-started" class="form-control date-time-picker">
							</div>
							<div class="form-group col">
								<label for="started"><small>Sampai</small></label>
								<input type="date" name="output[finished]" id="modal-finished" class="form-control date-time-picker">
							</div>
						</div>

						<div class="form-row">
							<div class="form-group col">

								<label for="modal-machine"><small>Mesin</small></label>

								<?php $machines = $this->produksi_model->get_assigned_machine_by_order_id($order['order_id']); ?>

								<select name="output[machine]" id="modal-machine" class="custom-select">
									<option value="">Pilih...</option>
									<?php if (count($machines) == 0) : ?>
										<option value="1">Mesin-1</option>
										<option value="2">Mesin-2</option>
										<option value="3">Mesin-3</option>
										<option value="4">Mesin-4</option>
										<option value="5">Mesin-5</option>
										<option value="6">Mesin-6</option>
									<?php elseif (count($machines) == 1) : ?>
										<option value="<?= $machines[0]; ?>" selected>Mesin-<?= $machines[0]; ?></option>
									<?php else : ?>
										<?php for ($i = 0; $i < count($machines); $i++) : ?>
											<option value="<?= $machines[$i]; ?>">Mesin-<?= $machines[$i]; ?></option>
										<?php endfor; ?>
									<?php endif; ?>
								</select>

							</div>
							<div class="form-group col">

								<label for="modal-shift"><small>Shift</small></label>

								<select name="output[shift]" id="modal-shift" class="custom-select">
									<option value="">Pilih...</option>
									<option value="1" selected>Siang</option>
									<option value="2">Malam</option>
								</select>

							</div>
						</div>

						<div class="form-group">
							<label for="modal-output-qty"><small>Jumlah (kurang <?= $embro_detail['quantity'] - $total_output; ?>pcs)</small></label>
							<input type="number" min="1" name="output[quantity]" id="modal-output-qty" class="form-control" value="" placeholder="0">
						</div>

						<div class="form-group">
							<div class="custom-control custom-checkbox">
								<input type="checkbox" class="custom-control-input" id="modal-is-helper" name="output[is_helper]" value="1">
								<label class="custom-control-label" for="modal-is-helper">Asisten?</label>
							</div>
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

	<!-- deleteOutputModal -->
	<div class="modal fade" id="del-output-modal" tabindex="-1" role="dialog">
		<div class="modal-dialog" role="document">
			<form action="<?= base_url('action/produksi_action/hapus_output_embro'); ?>" method="post" id="del-output-form">
				<input type="hidden" name="output[output_embro_id]" id="output-id" value="">
				<input type="hidden" name="referrer" value="<?= base_url(uri_string()); ?>">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title">Yakin akan menghapus?</h5>
						<button class="close" type="button" data-dismiss="modal">
							<span aria-hidden="true">×</span>
						</button>
					</div>
					<div class="modal-body">Klik "Hapus" jika Anda yakin untuk menghapus catatan output ini.</div>
					<div class="modal-footer">
						<button class="btn btn-secondary" type="button" data-dismiss="modal">Batal</button>
						<button type="submit" class="btn btn-primary" id="del-output-btn">Hapus</button>
					</div>
				</div>
			</form>
		</div>
	</div>

<?php endif; ?>

<!-- /.container-fluid -->