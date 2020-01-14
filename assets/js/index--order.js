$(document).ready(function () {

	$('#dataTable').DataTable({
		"language": {
			"decimal": ",",
			"thousands": "."
		},
		"columnDefs": [{
			"targets": [0, 8],
			"orderable": false
		}],
		"order": [
			[6, "asc"],
			[5, "asc"]
		]
	});

	var table = $('#dataTable').DataTable();

	$('#filter-select').change(function (e) {
		let filter = $(this).val();
		table.columns(6).search(filter).draw();
	});

	// const uniqueFormWrapper = $('#spec-modal .modal-body #unique-form-wrapper');

	// const materialFormEl = `
	// 		<div class="form-group">
	// 			<label for="thread"><small>Benang</small></label>
	// 			<input type="text" name="production_material[thread]" id="thread" class="form-control">
	// 		</div>
	// 		<div class="form-group">
	// 			<label for="stabilizer"><small>Kain Keras</small></label>
	// 			<input type="text" name="production_material[stabilizer]" id="stabilizer" class="form-control">
	// 		</div>
	// `;

	// const designFormEl = `
	// 		<div class="form-group">
	// 			<label for="repeat"><small>Ulang</small></label>
	// 			<input type="text" name="production_design[repeat]" id="repeat" class="form-control">
	// 		</div>
	// 	`;

	// const embroFormEl = `
	// 		<div class="form-group">
	// 			<label for="otomatis"><small>Otomatis</small></label>
	// 			<input type="text" name="production_embro[otomatis]" id="otomatis" class="form-control">
	// 		</div>
	// 		<div class="form-group">
	// 			<label for="harga"><small>Harga</small></label>
	// 			<input type="text" name="production_embro[harga]" id="harga" class="form-control">
	// 		</div>
	// `;

	// $('#production-type').change(function (e) {

	// 	// Grab form processor url from data attribute on each select box option
	// 	let formAction = $(this).find('option:selected').data('form-action');

	// 	// Assign form url to form action
	// 	$(this).parents('form').attr('action', formAction);

	// 	// Populate form input element based on production type change
	// 	switch ($(this).val()) {

	// 		case 'material':
	// 			uniqueFormWrapper.html(materialFormEl);
	// 			break;

	// 		case 'design':
	// 			uniqueFormWrapper.html(designFormEl);
	// 			break;

	// 		case 'embroidery':
	// 			uniqueFormWrapper.html(embroFormEl);
	// 			$('#spec-modal #harga').val($('#spec-modal #order-price').val());
	// 			break;
	// 	}

	// });

});
