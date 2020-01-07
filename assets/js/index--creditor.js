$(document).ready(function () {

	/**
	 * Initialize Plugin
	 */

	// DataTable
	let table = $('#creditorDataTable').DataTable({
		"ajax": `${window.location.origin}/ajax/keuangan_ajax/list_all_creditors`,
		"columns": [
			{ "data": "name" },
			{ "data": "phone" },
			{ "data": "email" },
			{ "data": "address" },
			{
				"data": {
					"_": "receivable.display",
					"sort": "receivable.raw"
				}
			},
			{
				"data": {
					"_": "paid.display",
					"sort": "paid.raw"
				}
			},
			{
				"data": {
					"_": "due.display",
					"sort": "due.raw"
				}
			},
			{ "data": "creditor_id" }
		],
		"createdRow": function (row, data, dataIndex) {
			$(row).attr('data-creditor-id', data.creditor_id);
			$(row).attr('data-name', data.name);
			$(row).attr('data-phone', data.phone);
			$(row).attr('data-email', data.email);
			$(row).attr('data-address', data.address);
			$(row).attr('data-receivable', data.receivable.raw);
			$(row).attr('data-paid', data.paid.raw);
			$(row).attr('data-due', data.due.raw);
		},
		"columnDefs": [
			{
				"targets": -1,
				"createdCell": function (td, cellData, rowData, row, col) {

					let actionBtn = `
					<a class="dropdown-toggle text-right" href="#" role="button" data-toggle="dropdown">
						<i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
					</a>

					<div class="dropdown-menu dropdown-menu-right shadow animated--fade-in">

						<a class="dropdown-item edit-creditor-trigger" href="#" data-toggle="modal" data-target="#creditorEditorModal">Sunting Detail</a>

						<a class="dropdown-item del-creditor-trigger" href="#" data-toggle="modal" data-target="#delCreditorModal">Hapus Creditor</a>

					</div>`;

					$(td).html(actionBtn);
				}
			}
		]
	});

	/**
	 * Modal Action Trigger
	 */

	// Add Creditor Trigger
	$('#add-creditor-trigger').click(function (event) {

		// Add title to the modal
		$('#creditorEditorModal .modal-title').html('Tambah Creditor Baru');

		// Reset previous value
		$('#creditorForm')[0].reset();
		$('#creditorForm #creditor-id').val(null);

	});

	// Edit Creditor Trigger
	$('#creditorDataTable').on('click', '.edit-creditor-trigger', function (event) {

		// Add title to the modal
		$('#creditorEditorModal .modal-title').html('Sunting Detail');

		// Grab Entry Data
		let entryRow = $(this).parents('tr');
		let creditorId = entryRow.data('creditor-id');
		let name = entryRow.data('name');
		let phone = entryRow.data('phone');
		let email = entryRow.data('email');
		let address = entryRow.data('address');
		let selling = entryRow.data('selling');

		// Fill form with the data
		$('#creditorForm #creditor-id').val(creditorId);
		$('#creditorForm #name').val(name);
		$('#creditorForm #selling').val(selling);
		$('#creditorForm #address').val(address);
		$('#creditorForm #phone').val(phone);
		$('#creditorForm #email').val(email);

	});

	// Delete Creditor Trigger
	$('#creditorDataTable').on('click', '.del-creditor-trigger', function (event) {

		let entryRow = $(this).parents('tr');
		let creditorId = entryRow.data('creditor-id');

		$('#del-creditor-form #creditor-id').val(creditorId);

	});

	/**
	 * Creditor entry submission
	 */

	$('#creditorForm').submit(function (event) {

		event.preventDefault();

		let formData = $(this).serialize();

		let saveCreditor = sendAjax(
			`${window.location.origin}/ajax/keuangan_ajax/simpan_creditor`,
			formData
		);

		saveCreditor.done(function (data) {

			// Prepend success notif into main page container
			$('#creditorEditorModal .modal-body').prepend(data.alert);

			if (data.action == 'create') {
				// Reset previous value
				$('#creditorForm')[0].reset();
				$('#creditorForm #creditor-id').val(null);
			}

			// Reload creditor table to show the new data
			table.ajax.reload();

		});

	});

	/**
	 * Creditor deletion 
	 */

	$('#del-creditor-form').submit(function (e) {

		e.preventDefault();

		let creditorData = $(this).serialize();

		let deleteCreditor = sendAjax(
			`${window.location.origin}/ajax/keuangan_ajax/hapus_creditor`,
			creditorData
		)

		deleteCreditor.done(function (data) {

			// Prepend delete success notif into main page container
			$('#creditor-index').prepend(data.alert);

			// Reload creditor table to show the new data
			table.ajax.reload();

		});

		$('#delCreditorModal').modal('hide');

	});

});