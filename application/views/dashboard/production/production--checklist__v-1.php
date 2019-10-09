<div class="container-fluid">	

	<div class="row">

		<div class="col-sm-6 col-lg-4">

			<div data-form-action="<?= base_url('produksi/desain'); ?>" id="is-designed-card" class="mb-3">

				<div class="bg-white list-group-header">
					<h4 class="mb-0">Desain</h4>
				</div>

				<ul class="list-group" style="height:15rem;overflow:auto">

					<?php $undesigned_list = $this->produksi->get_undesigned_list(); ?>
					<?php foreach ($undesigned_list as $undesigned) : ?>
						<li class="list-group-item d-flex justify-content-between align-items-center" data-order-detail-id="<?= $undesigned['order_detail_id']; ?>">
							<div>
								<a class="dropdown-toggle text-right" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown">
									<i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400 process-action"></i>
								</a>
								<div class="dropdown-menu dropdown-menu-right shadow animated--fade-in">
									<div class="dropdown-header">Tandai:</div>
									<a href="#" class="dropdown-item pending" >Pending</a>
									<a href="#" class="dropdown-item processed">Dikerjakan</a>
									<a href="#" class="dropdown-item ready-to-embro">Siap Dibordir</a>
								</div>
								<span style="font-size:15px"><?= $undesigned['item_desc']; ?></span>
							</div>
							<i class="<?= $undesigned['process_icon']; ?>"></i>
						</li>
					<?php endforeach; ?>

				</ul>

			</div>

			<div data-form-action="<?= base_url('produksi/persiapan'); ?>" id="is-prepared-card">

				<div class="bg-white list-group-header">
					<h4 class="mb-0">Persiapan Bahan Baku</h4>
				</div>

				<ul class="list-group" style="height:15rem;overflow:auto">

					<?php $unprepared_list = $this->produksi->get_unprepared_list(); ?>
					<?php foreach ($unprepared_list as $unprepared) : ?>
						<li class="list-group-item d-flex justify-content-between align-items-center">
							<div>
								<a class="dropdown-toggle text-right" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown">
									<i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400 process-action"></i>
								</a>
								<div class="dropdown-menu dropdown-menu-right shadow animated--fade-in">
									<div class="dropdown-header">Tandai:</div>
									<a href="" class="dropdown-item" data-toggle="modal" data-target="#mark-as-finished-modal">Pending</a>
									<a href="" class="dropdown-item">Dikerjakan</a>
									<a href="" class="dropdown-item">Siap Dibordir</a>
								</div>
								<span style="font-size:15px"><?= $unprepared['item_desc']; ?></span>
							</div>
							<i class="<?= $unprepared['process_icon']; ?>"></i>
						</li>
					<?php endforeach; ?>

				</ul>

			</div>

		</div>

		<div class="col-sm-6 col-lg-4">

			<div data-form-action="<?= base_url('produksi/bordir'); ?>" id="is-embroideried-card">

				<div class="bg-white list-group-header">
					<h4 class="mb-0">Bordir</h4>
				</div>


				<ul class="list-group" style="height:15rem;overflow:auto">

					<?php $unembroideried_list = $this->produksi->get_unembroideried_list(); ?>
					<?php foreach ($unembroideried_list as $unembroideried) : ?>
						<li class="list-group-item d-flex justify-content-between align-items-center">
							<div>
								<a class="dropdown-toggle text-right" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown">
									<i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400 process-action"></i>
								</a>
								<div class="dropdown-menu dropdown-menu-right shadow animated--fade-in">
									<div class="dropdown-header">Tandai:</div>
									<a href="" class="dropdown-item" data-toggle="modal" data-target="#mark-as-finished-modal">Pending</a>
									<a href="" class="dropdown-item">Dikerjakan</a>
									<a href="" class="dropdown-item">Siap di-Finishing</a>
								</div>
								<span style="font-size:15px"><?= $unembroideried['item_desc']; ?></span>
							</div>
							<i class="<?= $unembroideried['process_icon']; ?>"></i>
						</li>
					<?php endforeach; ?>

				</ul>

			</div>

		</div>

		<div class="col-sm-6 col-lg-4">

			<div data-form-action="<?= base_url('produksi/finishing'); ?>" id="is-finished-card" class="mb-3">

				<div class="bg-white list-group-header">
					<h4 class="mb-0">Finishing</h4>
				</div>


				<ul class="list-group" style="height:15rem;overflow:auto">

					<?php $unfinished_list = $this->produksi->get_unfinished_list(); ?>
					<?php foreach ($unfinished_list as $unfinished) : ?>
						<li class="list-group-item d-flex justify-content-between align-items-center">
							<div>
								<a class="dropdown-toggle text-right" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown">
									<i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400 process-action"></i>
								</a>
								<div class="dropdown-menu dropdown-menu-right shadow animated--fade-in">
									<div class="dropdown-header">Tandai:</div>
									<a href="" class="dropdown-item" data-toggle="modal" data-target="#mark-as-finished-modal">Pending</a>
									<a href="" class="dropdown-item">Dikerjakan</a>
									<a href="" class="dropdown-item">Siap Dikemas</a>
								</div>
								<span style="font-size:15px"><?= $unfinished['item_desc']; ?></span>
							</div>
							<i class="<?= $unfinished['process_icon']; ?>"></i>
						</li>
					<?php endforeach; ?>

				</ul>

			</div>

			<div data-form-action="<?= base_url('produksi/pengemasan'); ?>" id="is-packed-card">

				<div class="bg-white list-group-header">
					<h4 class="mb-0">Pengemasan</h4>
				</div>

				<ul class="list-group" style="height:15rem;overflow:auto">

					<?php $unpacked_list = $this->produksi->get_unpacked_list(); ?>
					<?php foreach ($unpacked_list as $unpacked) : ?>
						<li class="list-group-item d-flex justify-content-between align-items-center">
							<div>
								<a class="dropdown-toggle text-right" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown">
									<i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400 process-action"></i>
								</a>
								<div class="dropdown-menu dropdown-menu-right shadow animated--fade-in">
									<div class="dropdown-header">Tandai:</div>
									<a href="" class="dropdown-item" data-toggle="modal" data-target="#mark-as-finished-modal">Pending</a>
									<a href="" class="dropdown-item">Dikerjakan</a>
									<a href="" class="dropdown-item">Selesai</a>
								</div>
								<span style="font-size:15px"><?= $unpacked['item_desc']; ?></span>
							</div>
							<i class="<?= $unpacked['process_icon']; ?>"></i>
						</li>
					<?php endforeach; ?>

				</ul>

			</div>

		</div>

	</div>

</div>


<div class="row mb-4">
		<div class="col-12">

			<div data-form-action="<?= base_url('produksi/bordir'); ?>" id="is-embroideried-card" class="shadow">

				<div class="bg-white list-group-header">
					<h4 class="mb-0"><i class="fas fa-cog mr-2"></i>Bordir</h4>
				</div>


				<ul class="list-group">

					<?php $unembroideried_list = $this->produksi->get_unembroideried_list(); ?>
					<?php foreach ($unembroideried_list as $unembroideried) : ?>
						<li class="list-group-item d-flex justify-content-start align-items-end">
							<a class="dropdown-toggle text-right mr-1" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown">
								<i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400 process-action"></i>
							</a>
							<div class="dropdown-menu dropdown-menu-right shadow animated--fade-in">
								<div class="dropdown-header">Tandai:</div>
								<a href="" class="dropdown-item">Pending <?= $undesigned['process_status_id'] == 1 ? '&#10003;' : ''; ?></a>
								<a href="" class="dropdown-item">Dikerjakan <?= $unembroideried['process_status_id'] == 1 ? '&#10003;' : ''; ?></a>
								<a href="" class="dropdown-item">Siap di-Finishing</a>
							</div>
							<div>
								<div style="color:#9aa0ac;font-size:13px">Diambil: <?= date('d-M-Y', strtotime($unembroideried['required_date'])); ?></div>
								<div style="color:#495057;font-size:15px"><?= $unembroideried['item_desc']; ?></div>
							</div>
							<i class="<?= $unembroideried['process_icon']; ?> ml-auto"></i>
						</li>
					<?php endforeach; ?>

				</ul>

			</div>

		</div>
	</div>

	<div class="row mb-4">

		<div class="col-6">

			<div data-form-action="<?= base_url('produksi/finishing'); ?>" id="is-finished-card" class="shadow">

				<div class="bg-white list-group-header">
					<h4 class="mb-0"><i class="fas fa-cubes mr-2"></i>Finishing</h4>
				</div>


				<ul class="list-group">

					<?php $unfinished_list = $this->produksi->get_unfinished_list(); ?>
					<?php foreach ($unfinished_list as $unfinished) : ?>
						<li class="list-group-item d-flex justify-content-start align-items-end">
							<a class="dropdown-toggle text-right mr-1" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown">
								<i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400 process-action"></i>
							</a>
							<div class="dropdown-menu dropdown-menu-right shadow animated--fade-in">
								<div class="dropdown-header">Tandai:</div>
								<a href="" class="dropdown-item">Pending <?= $undesigned['process_status_id'] == 1 ? '&#10003;' : ''; ?></a>
								<a href="" class="dropdown-item">Dikerjakan <?= $unfinished['process_status_id'] == 1 ? '&#10003;' : ''; ?></a>
								<a href="" class="dropdown-item">Siap Dikemas</a>
							</div>
							<div>
								<div style="color:#9aa0ac;font-size:13px">Diambil: <?= date('d-M-Y', strtotime($unfinished['required_date'])); ?></div>
								<div style="color:#495057;font-size:15px"><?= $unfinished['item_desc']; ?></div>
							</div>
							<i class="<?= $unfinished['process_icon']; ?> ml-auto"></i>
						</li>
					<?php endforeach; ?>

				</ul>

			</div>

		</div>

		<div class="col-6">
			<div data-form-action="<?= base_url('produksi/pengemasan'); ?>" id="is-packed-card" class="shadow">

				<div class="bg-white list-group-header shadow">
					<h4 class="mb-0"><i class="fas fa-gift mr-2"></i>Pengemasan</h4>
				</div>

				<ul class="list-group">

					<?php $unpacked_list = $this->produksi->get_unpacked_list(); ?>
					<?php foreach ($unpacked_list as $unpacked) : ?>
						<li class="list-group-item d-flex justify-content-start align-items-end">
							<a class="dropdown-toggle text-right mr-1" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown">
								<i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400 process-action"></i>
							</a>
							<div class="dropdown-menu dropdown-menu-right shadow animated--fade-in">
								<div class="dropdown-header">Tandai:</div>
								<a href="" class="dropdown-item">Pending <?= $undesigned['process_status_id'] == 1 ? '&#10003;' : ''; ?></a>
								<a href="" class="dropdown-item">Dikerjakan <?= $unpacked['process_status_id'] == 2 ? '&#10003;' : ''; ?></a>
								<a href="" class="dropdown-item">Selesai</a>
							</div>
							<div>
								<div style="color:#9aa0ac;font-size:13px">Diambil: <?= date('d-M-Y', strtotime($unpacked['required_date'])); ?></div>
								<div style="color:#495057;font-size:15px"><?= $unpacked['item_desc']; ?></div>
							</div>
							<i class="<?= $unpacked['process_icon']; ?> ml-auto"></i>
						</li>
					<?php endforeach; ?>

				</ul>

			</div>
		</div>
	</div>

	<div class="col-6">

			<div data-form-action="<?= base_url('produksi/persiapan'); ?>" id="is-prepared-card" class="shadow">

				<div class="bg-white list-group-header">
					<h4 class="mb-0"><i class="fas fa-tape mr-2"></i>Persiapan Alat/Bahan</h4>
				</div>

				<ul class="list-group">

					<?php $unprepared_list = $this->produksi->get_unprepared_list(); ?>
					<?php foreach ($unprepared_list as $unprepared) : ?>
						<li class="list-group-item d-flex justify-content-start align-items-end">
							<a class="dropdown-toggle text-right mr-1" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown">
								<i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400 process-action"></i>
							</a>
							<div class="dropdown-menu dropdown-menu-right shadow animated--fade-in">
								<div class="dropdown-header">Tandai:</div>
								<a href="" class="dropdown-item">Pending <?= $undesigned['process_status_id'] == 1 ? '&#10003;' : ''; ?></a>
								<a href="" class="dropdown-item">Dikerjakan <?= $unprepared['process_status_id'] == 2 ? '&#10003;' : ''; ?></a>
								<a href="" class="dropdown-item">Siap Dibordir</a>
							</div>
							<div>
								<div style="color:#9aa0ac;font-size:13px">Diambil: <?= date('d-M-Y', strtotime($unprepared['required_date'])); ?></div>
								<div style="color:#495057;font-size:15px"><?= $unprepared['item_desc']; ?></div>
							</div>
							<div><?= $undesigned['design_started'] == NULL ? '' : '' ?></div>
						</li>
					<?php endforeach; ?>

				</ul>

			</div>

		</div>

<!-- Update Process Status Modal-->
<div class="modal fade" id="update-process-modal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
      <form action="" method="post" id="update-process-form">
        <input type="hidden" name="order-detail-id" id="order-detail-id" value="">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Tahap Pengerjaan</h5>
            <button class="close" type="button" data-dismiss="modal">
              <span aria-hidden="true">×</span>
            </button>
          </div>
          <div class="modal-body">
            Silakan klik tombol yang sesuai dengan status pengerjaan pesanan ini.
          </div>
          <div class="modal-footer">
            <button type="submit" class="btn btn-secondary" name="is-unfinished" value="0">Belum</button>
            <button type="submit" class="btn btn-primary" name="is-finished" value="1">Selesai</button>
          </div>
        </div>
      </form>
    </div>
  </div>