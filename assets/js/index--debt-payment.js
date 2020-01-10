$(document).ready(function () {

	/**
	 * Initialize Plugin
	 */

	// DataTable
	let table = $('#debtPaymentDataTable').DataTable({
		"ajax": `${window.location.origin}/ajax/keuangan_ajax/list_all_debt_payments`,
		"columns": [
			{ "data": "debt_payment_id" },
			{
				"data": {
					"_": "payment_date.display",
					"sort": "payment_date.raw"
				}
			},
			{ "data": "description" },
			{ "data": "creditor" },
			{
				"data": {
					"_": "amount.display",
					"sort": "amount.raw"
				}
			},
			{ "data": "payment_method" },
			{ "data": "debt_payment_id" }
		],
		"createdRow": function (row, data, dataIndex) {
			$(row).attr('data-debt-payment-id', data.debt_payment_id);
			$(row).attr('data-creditor-id', data.vendor_id);
			$(row).attr('data-description', data.description);
			$(row).attr('data-payment-date', data.payment_date.input);
			$(row).attr('data-amount', data.amount.raw);
			$(row).attr('data-method-id', data.method_id);
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

						<a class="dropdown-item edit-debt-payment-trigger" href="#" data-toggle="modal" data-target="#debtPaymentEditorModal">Sunting Detail</a>

						<a class="dropdown-item del-debt-payment-trigger" href="#" data-toggle="modal" data-target="#delDebtPaymentModal">Hapus Pembayaran</a>

					</div>`;

					$(td).html(actionBtn);
				}
			}
		]
	});

	/**
	 * Modal Action Trigger
	 */

	// Edit DebtPayment Trigger
	$('#debtPaymentDataTable').on('click', '.edit-debt-payment-trigger', function (event) {

		// Add title to the modal
		$('#debtPaymentEditorModal .modal-title').html('Sunting Detail');

		// Grab Entry Data
		let entryRow = $(this).parents('tr');
		let debtPaymentId = entryRow.data('debt-payment-id');
		let paymentMethodId = entryRow.data('method-id');
		let amount = entryRow.data('amount');
		let paymentDate = entryRow.data('payment-date');

		// Fill form with the data
		$('#debtPaymentForm #debt-payment-id').val(debtPaymentId);
		$('#debtPaymentForm #debt-payment-amount').val(amount);
		$('#debtPaymentForm #debt-payment-date').val(paymentDate);
		$('#debtPaymentForm #debt-payment-method').val(paymentMethodId);

	});

	// Delete DebtPayment Modal Trigger
	$('#debtPaymentDataTable').on('click', '.del-debt-payment-trigger', function (event) {

		let entryRow = $(this).parents('tr');
		let debtPaymentId = entryRow.data('debt-payment-id');

		$('#delete-debt-payment-form #debt-payment-id').val(debtPaymentId);

	});

	/**
	 * DebtPayment entry submission
	 */

	$('#debtPaymentForm').submit(function (event) {

		event.preventDefault();

		let formData = $(this).serialize();

		let saveDebtPayment = sendAjax(
			`${window.location.origin}/ajax/keuangan_ajax/simpan_pembayaran_hutang`,
			formData
		);

		saveDebtPayment.done(function (data) {

			// Prepend success notif into main page container
			$('#debtPaymentEditorModal .modal-body').prepend(data.alert);

			if (data.action == 'create') {
				// Reset previous value
				$('#debtPaymentForm')[0].reset();
				$('#debtPaymentForm #debt-payment-id').val(null);
			}

			// Reload debt_payment table to show the new data
			table.ajax.reload();

		});

	});

	/**
	 * DebtPayment deletion 
	 */

	$('#delete-debt-payment-form').submit(function (e) {

		e.preventDefault();

		let debtPaymentId = $(this).children('#debt-payment-id').val();

		let deleteDebtPayment = sendAjax(
			`${window.location.origin}/ajax/keuangan_ajax/delete_debt_payment_by_id`,
			{ "debt-payment-id": debtPaymentId }
		)

		deleteDebtPayment.done(function (data) {

			// Prepend delete success notif into main page container
			$('#debt-payment-index').prepend(data.alert);

			// Reload debt_payment table to show the new data
			table.ajax.reload();

		});

		$('#delDebtPaymentModal').modal('hide');

	});

})