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

// Edit Debt Trigger
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

// 	// Delete Debt Modal Trigger
// 	$('#debtDataTable').on('click', '.del-debt-trigger', function (event) {

// 		let entryRow = $(this).parents('tr');
// 		let debtId = entryRow.data('debt-id');

// 		$('#delete-debt-form #debt-id').val(debtId);

// 	});

// 	function paymentListTemplate(index, debt_payment_id, amount, date) {

// 		let paymentHistory = `
// 				<div class="form-row">
// 					<input type="hidden" name="debt_payment[${index}][debt_payment_id]" id="debt-payment-id" value="${debt_payment_id}">
// 					<div class="form-group col-2">
// 						<input type="text" class="form-control text-center" value="${index + 1}">
// 					</div>
// 					<div class="form-group col-5">
// 						<input type="number" name="debt_payment[${index}][amount]" class="form-control debt-payment-amount" value="${amount}">
// 					</div>
// 					<div class="form-group col-5 d-flex align-items-center">
// 						<input type="date" name="debt_payment[${index}][payment_date]" class="form-control debt-payment-date" value="${date}">
// 						<a href="#" class="text-danger ml-3 del-payment-trigger"><i class="fas fa-trash fa-lg"></i></a>
// 					</div>
// 				</div>
// 		`;

// 		return paymentHistory;

// 	}

// 	// Add Payment Trigger
// 	$('#debtDataTable').on('click', '.add-payment-trigger', function (event) {

// 		// Reset previous input
// 		$('#debt-payment-form')[0].reset();

// 		let entryRow = $(this).parents('tr');
// 		let debtId = entryRow.data('debt-id');
// 		let creditorId = entryRow.data('creditor-id');
// 		let debtAmount = entryRow.data('amount');

// 		$('#debt-payment-form #debt-payment-id').val(debtId);
// 		$('#debt-payment-form #debt-payment-amount').attr('max', debtAmount);
// 		$('#debt-payment-form #creditor-id').val(creditorId);

// 	});

// 	// Show Payment History Trigger
// 	$('#debtDataTable').on('click', '.show-payment-trigger', function (event) {

// 		// Clear previous list
// 		$('#paymentHistoryModal .modal-body').html('');

// 		let entryRow = $(this).parents('tr');
// 		let debtId = entryRow.data('debt-id');

// 		let reqPaymentHistory = sendAjax(
// 			`${window.location.origin}/ajax/keuangan_ajax/list_debt_payment_by_debt_id`,
// 			{ "debt-id": debtId }
// 		);

// 		reqPaymentHistory.done(function (data) {

// 			let paymentHistoryData = data.payment_history;

// 			// if there is no payment history then bailed out
// 			if (paymentHistoryData.length < 1) {
// 				$('#paymentHistoryModal .modal-body').append(`<p>Belum ada pembayaran.</p>`);
// 			}

// 			// Populate the modal body with payment history data
// 			$.each(paymentHistoryData, function (index, payment) {
// 				let paymentList = paymentListTemplate(index, payment.debt_payment_id, payment.amount, payment.payment_date);
// 				$('#paymentHistoryModal .modal-body').append(paymentList);
// 			});

// 		});

// 	});

// 	// Delete Payment History Trigger
// 	$('#paymentHistoryModal').on('click', '.del-payment-trigger', function (event) {

// 		event.preventDefault();

// 		let selectedRow = $(this).parents('.form-row');
// 		let debtPaymentId = selectedRow.children('#debt-payment-id').val();

// 		let deletePayment = sendAjax(
// 			`${window.location.origin}/ajax/keuangan_ajax/delete_debt_payment_by_id`,
// 			{ "debt-payment-id": debtPaymentId }
// 		);

// 		deletePayment.done(function (data) {

// 			selectedRow.remove();
// 			$('#paymentHistoryModal .modal-body').prepend(data.alert);

// 		});


// 	})

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

// 	/**
// 	 * Debt deletion 
// 	 */

// 	$('#delete-debt-form').submit(function (e) {

// 		e.preventDefault();

// 		let debtData = $(this).serialize();

// 		let deleteDebt = sendAjax(
// 			`${window.location.origin}/ajax/keuangan_ajax/hapus_hutang`,
// 			debtData
// 		)

// 		deleteDebt.done(function (data) {

// 			// Prepend delete success notif into main page container
// 			$('#debt-index').prepend(data.alert);

// 			// Reload debt table to show the new data
// 			table.ajax.reload();

// 		});

// 		$('#delDebtModal').modal('hide');

// 	});

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

// 	/**
// 	 * Debt Payment Submission
// 	 */

// 	$('#debt-payment-form').submit(function (event) {

// 		event.preventDefault();

// 		let debtPaymentData = $(this).serialize();

// 		let payDebt = sendAjax(
// 			`${window.location.origin}/ajax/keuangan_ajax/bayar_hutang`,
// 			debtPaymentData
// 		)

// 		payDebt.done(function (data) {

// 			// Prepend delete success notif into the modal
// 			$('#debtPaymentModal .modal-body').prepend(data.alert);

// 			// Reset previous value
// 			$('#debt-payment-form')[0].reset();

// 			// Reload debt table to show the new data
// 			table.ajax.reload();

// 		});

// 	});

// 	/**
// 	 * Payment History Edit
// 	 */

// 	$('#payment-history-form').submit(function (event) {

// 		event.preventDefault();

// 		let debtPaymentData = $(this).serialize();

// 		let editDebt = sendAjax(
// 			`${window.location.origin}/ajax/keuangan_ajax/bulk_edit_debt_payment`,
// 			debtPaymentData
// 		)

// 		editDebt.done(function (data) {

// 			// Prepend delete success notif into main page container
// 			$('#debt-index').prepend(data.alert);

// 			// Reload debt table to show the new data
// 			table.ajax.reload();

// 		});

// 		$('#paymentHistoryModal').modal('hide');

// 	});

// });
