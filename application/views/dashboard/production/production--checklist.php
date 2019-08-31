<div class="container-fluid">

	<div class="row">

		<?php foreach ($production_data as $card) : ?>

			<div class="<?= strtolower($card['title']) == 'bordir' ? 'col-12' : 'col-6'; ?> mb-4">
				<div data-form-action="<?= base_url(`{$card['url']}`); ?>" id="is-designed-card" class="shadow">

					<div class="bg-white list-group-header">
						<h4 class="mb-0"><i class="<?= $card['icon']; ?>"></i><?= $card['title']; ?></h4>
					</div>

					<?php if (empty($card['data'])) : ?>
						<div class="card">
							<div class="card-body">Belum ada yang siap dikerjakan</div>
						</div>
					<?php endif; ?>

					<?php $data['title'] = $card['title']; ?>
					<ul class="list-group" style="<?= sizeof($card['data']) > 5 ? 'height:20rem;overflow:auto' : ''; ?>">
						<?php foreach ($card['data'] as $data['card_detail']) : ?>
							<?php $this->load->view('dashboard/component/list--production-card--6col', $data); ?>
						<?php endforeach; ?>
					</ul>
					
				</div>
			</div>

		<?php endforeach; ?>

	</div>

</div>