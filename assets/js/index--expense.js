// $(document).ready(function () {

let vendorSelect = $('#vendors');
let categorySelect = $('#categories');

/**
 * Initialize Plugin
 */

// Select2
vendorSelect.select2();
categorySelect.select2();

// DataTable
let table = $('#expenseDataTable').DataTable({
	"ajax": `${window.location.origin}/ajax/keuangan_ajax/list_all_expenses`,
	"columns": [
		{ "data": "expense_id" },
		{
			"data": {
				"_": "transaction_date.display",
				"sort": "transaction_date.raw"
			}
		},
		{ "data": "description" },
		{ "data": "category" },
		{
			"data": {
				"_": "amount.display",
				"sort": "amount.raw"
			}
		},
		{ "data": "vendor" },
		{ "data": "expense_id" }
	],
	"createdRow": function (row, data, dataIndex) {
		$(row).attr('data-expense-id', data.expense_id);
		$(row).attr('data-vendor-id', data.vendor_id);
		$(row).attr('data-category-id', data.expense_category_id);
		$(row).attr('data-description', data.description);
		$(row).attr('data-transaction-date', data.transaction_date.input);
		$(row).attr('data-amount', data.amount.raw);
		$(row).attr('data-note', data.note);
	},
	"columnDefs": [
		{
			"targets": 0,
			"createdCell": function (td, cellData, rowData, row, col) {
				$(td).html(`PGL-${cellData}`);
			}
		},
		{
			"targets": -1,
			"createdCell": function (td, cellData, rowData, row, col) {

				let actionBtn = `
					<a class="dropdown-toggle text-right" href="#" role="button" data-toggle="dropdown">
						<i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
					</a>

					<div class="dropdown-menu dropdown-menu-right shadow animated--fade-in">

						<a class="dropdown-item edit-expense-trigger" href="#" data-toggle="modal" data-target="#expenseEditorModal">Sunting Detail</a>

						<a class="dropdown-item del-expense-trigger" href="#" data-toggle="modal" data-target="#delExpenseModal">Hapus Pengeluaran</a>

					</div>`;

				$(td).html(actionBtn);
			}
		}
	]
});

/**
 * Modal Action Trigger
 */

// Add Expense Trigger
$('#add-expense-trigger').click(function (event) {

	// Add title to the modal
	$('#expenseEditorModal .modal-title').html('Catat Pengeluaran Baru');

	// Reset previous value
	$('#expenseForm')[0].reset();
	$('#expenseForm #expense-id').val(null);
	$('#expenseForm #vendors').val(null).trigger('change');
	$('#expenseForm #categories').val(null).trigger('change');

});

// Edit Expense Trigger
$('#expenseDataTable').on('click', '.edit-expense-trigger', function (event) {

	// Add title to the modal
	$('#expenseEditorModal .modal-title').html('Sunting Detail');

	// Grab Entry Data
	let entryRow = $(this).parents('tr');
	let expenseId = entryRow.data('expense-id');
	let vendorId = entryRow.data('vendor-id');
	let categoryId = entryRow.data('category-id');
	let amount = entryRow.data('amount');
	let description = entryRow.data('description');
	let transactionDate = entryRow.data('transaction-date');
	let note = entryRow.data('note');

	// Fill form with the data
	$('#expenseForm #expense-id').val(expenseId);
	$('#expenseForm #amount').val(amount);
	$('#expenseForm #description').val(description);
	$('#expenseForm #transaction-date').val(transactionDate);
	$('#expenseForm #note').val(note);

	$('#expenseForm #vendors').val(vendorId);
	$('#expenseForm #vendors').trigger('change');

	$('#expenseForm #categories').val(categoryId);
	$('#expenseForm #categories').trigger('change');

});

// Delete Expense Modal Trigger
$('#expenseDataTable').on('click', '.del-expense-trigger', function (event) {

	let entryRow = $(this).parents('tr');
	let expenseId = entryRow.data('expense-id');

	$('#delete-expense-form #expense-id').val(expenseId);

});

/**
 * Expense entry submission
 */

$('#expenseForm').submit(function (event) {

	event.preventDefault();

	let formData = $(this).serialize();

	let saveExpense = sendAjax(
		`${window.location.origin}/ajax/keuangan_ajax/simpan_pengeluaran`,
		formData
	);

	saveExpense.done(function (data) {

		// Prepend success notif into main page container
		$('#expenseEditorModal .modal-body').prepend(data.alert);

		if (data.action == 'create') {
			// Reset previous value
			$('#expenseForm')[0].reset();
			$('#expenseForm #expense-id').val(null);
			$('#expenseForm #vendors').val(null).trigger('change');
			$('#expenseForm #categories').val(null).trigger('change');
		}

		// Reload expense table to show the new data
		table.ajax.reload();

	});

});

/**
 * Expense deletion 
 */

$('#delete-expense-form').submit(function (e) {

	e.preventDefault();

	let expenseData = $(this).serialize();

	let deleteExpense = sendAjax(
		`${window.location.origin}/ajax/keuangan_ajax/hapus_pengeluaran`,
		expenseData
	)

	deleteExpense.done(function (data) {

		// Prepend delete success notif into main page container
		$('#expense-index').prepend(data.alert);

		// Reload expense table to show the new data
		table.ajax.reload();

	});

	$('#delExpenseModal').modal('hide');

});

/**
 * New vendor creation
 */

$('#vendorForm').submit(function (event) {

	event.preventDefault();

	let vendorData = $(this).serialize();

	let saveVendor = sendAjax(
		`${window.location.origin}/ajax/keuangan_ajax/tambah_vendor`,
		vendorData
	);

	saveVendor.done(function (data) {

		// Prepend success notif into main page container
		$('#debt-index').prepend(data.alert);

		// Append newVendor into Vendor-select
		let newVendorOptions = `<option value="${data.newVendor.id}">${data.newVendor.text}</option>`;;
		vendorSelect.append(newVendorOptions);

		// Transform vendorSelect into select2
		vendorSelect.select2();

	});

	// Hide the modal
	$('#addVendorModal').modal('hide');

});