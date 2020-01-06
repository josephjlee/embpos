$(document).ready(function () {

	/**
	 * Initialize Plugin
	 */

	// DataTable
	let table = $('#vendorDataTable').DataTable({
		"ajax": `${window.location.origin}/ajax/keuangan_ajax/list_all_vendors`,
		"columns": [
			{ "data": "name" },
			{ "data": "phone" },
			{ "data": "email" },
			{ "data": "address" },
			{ "data": "selling" },
			{
				"data": {
					"_": "value.display",
					"sort": "value.raw"
				}
			},
			{ "data": "vendor_id" }
		],
		"createdRow": function (row, data, dataIndex) {
			$(row).attr('data-vendor-id', data.vendor_id);
			$(row).attr('data-name', data.name);
			$(row).attr('data-phone', data.phone);
			$(row).attr('data-email', data.email);
			$(row).attr('data-address', data.address);
			$(row).attr('data-selling', data.selling);
			$(row).attr('data-value', data.value.raw);
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

						<a class="dropdown-item edit-vendor-trigger" href="#" data-toggle="modal" data-target="#vendorEditorModal">Sunting Detail</a>

						<a class="dropdown-item del-vendor-trigger" href="#" data-toggle="modal" data-target="#delVendorModal">Hapus Vendor</a>

					</div>`;

					$(td).html(actionBtn);
				}
			}
		]
	});

	// /**
	//  * Modal Action Trigger
	//  */

	// Add Vendor Trigger
	$('#add-vendor-trigger').click(function (event) {

		// Add title to the modal
		$('#vendorEditorModal .modal-title').html('Tambah Vendor Baru');

		// Reset previous value
		$('#vendorForm')[0].reset();
		$('#vendorForm #vendor-id').val(null);

	});

	// Edit Vendor Trigger
	$('#vendorDataTable').on('click', '.edit-vendor-trigger', function (event) {

		// Add title to the modal
		$('#vendorEditorModal .modal-title').html('Sunting Detail');

		// Grab Entry Data
		let entryRow = $(this).parents('tr');
		let vendorId = entryRow.data('vendor-id');
		let name = entryRow.data('name');
		let phone = entryRow.data('phone');
		let email = entryRow.data('email');
		let address = entryRow.data('address');
		let selling = entryRow.data('selling');

		// Fill form with the data
		$('#vendorForm #vendor-id').val(vendorId);
		$('#vendorForm #name').val(name);
		$('#vendorForm #selling').val(selling);
		$('#vendorForm #address').val(address);
		$('#vendorForm #phone').val(phone);
		$('#vendorForm #email').val(email);

	});

	// Delete Expense Modal Trigger
	$('#expenseDataTable').on('click', '.del-expense-trigger', function (event) {

		let entryRow = $(this).parents('tr');
		let expenseId = entryRow.data('expense-id');

		$('#delete-expense-form #expense-id').val(expenseId);

	});

	/**
	 * Vendor entry submission
	 */

	$('#vendorForm').submit(function (event) {

		event.preventDefault();

		let formData = $(this).serialize();

		let saveVendor = sendAjax(
			`${window.location.origin}/ajax/keuangan_ajax/simpan_vendor`,
			formData
		);

		saveVendor.done(function (data) {

			// Prepend success notif into main page container
			$('#vendorEditorModal .modal-body').prepend(data.alert);

			if (data.action == 'create') {
				// Reset previous value
				$('#vendorForm')[0].reset();
				$('#vendorForm #vendor-id').val(null);
			}

			// Reload vendor table to show the new data
			table.ajax.reload();

		});

	});

	// /**
	//  * Expense deletion 
	//  */

	// $('#delete-expense-form').submit(function (e) {

	// 	e.preventDefault();

	// 	let expenseData = $(this).serialize();

	// 	let deleteExpense = sendAjax(
	// 		`${window.location.origin}/ajax/keuangan_ajax/hapus_pengeluaran`,
	// 		expenseData
	// 	)

	// 	deleteExpense.done(function (data) {

	// 		// Prepend delete success notif into main page container
	// 		$('#expense-index').prepend(data.alert);

	// 		// Reload expense table to show the new data
	// 		table.ajax.reload();

	// 	});

	// 	$('#delExpenseModal').modal('hide');

	// });

	// /**
	//  * New vendor creation
	//  */

	// $('#vendorForm').submit(function (event) {

	// 	event.preventDefault();

	// 	let vendorData = $(this).serialize();

	// 	let saveVendor = sendAjax(
	// 		`${window.location.origin}/ajax/keuangan_ajax/tambah_vendor`,
	// 		vendorData
	// 	);

	// 	saveVendor.done(function (data) {

	// 		// Prepend success notif into main page container
	// 		$('#debt-index').prepend(data.alert);

	// 		// Append newVendor into Vendor-select
	// 		let newVendorOptions = `<option value="${data.newVendor.id}">${data.newVendor.text}</option>`;;
	// 		vendorSelect.append(newVendorOptions);

	// 		// Transform vendorSelect into select2
	// 		vendorSelect.select2();

	// 	});

	// 	// Hide the modal
	// 	$('#addVendorModal').modal('hide');

});