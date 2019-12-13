$(document).ready(function () {

	/**
	 * Debt dataTable Initialization
	 */

	let table = $('#debtDataTable').DataTable({
		"ajax": `${window.location.origin}/ajax/keuangan_ajax/list_all_debts`,
		"columns": [
			{ "data": "debt_id" },
			{ "data": "creditor" },
			{ "data": "description" },
			{
				"data": {
					"_": "amount.display",
					"sort": "amount.raw"
				}
			},
			{
				"data": {
					"_": "transaction_date.display",
					"sort": "transaction_date.raw"
				}
			},
			{
				"data": {
					"_": "payment_date.display",
					"sort": "payment_date.raw"
				}
			},
			{
				"data": {
					"_": "paid.display",
					"sort": "paid.raw"
				}
			},
			{
				"data": "debt_id"
			}
		],
		"createdRow": function (row, data, dataIndex) {
			$(row).attr('data-debt-id', data.debt_id);
			$(row).attr('data-creditor-id', data.creditor_id);
			$(row).attr('data-description', data.description);
			$(row).attr('data-transaction-date', data.transaction_date.input);
			$(row).attr('data-payment-date', data.payment_date.input);
			$(row).attr('data-amount', data.amount.raw);
			$(row).attr('data-note', data.note);
		},
		"columnDefs": [
			{
				"targets": 0,
				"createdCell": function (td, cellData, rowData, row, col) {
					$(td).html(`HTG-${cellData}`);
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

						<a class="dropdown-item edit-debt-trigger" href="#" data-toggle="modal" data-target="#debtEditorModal">Sunting Detail</a>

						<a class="dropdown-item save-payment-trigger" href="#" data-toggle="modal" data-target="#paymentEditorModal">Rekam Pembayaran</a>

						<a class="dropdown-item save-payment-trigger" href="#" data-toggle="modal" data-target="#paymentHistoryModal">Riwayat Pembayaran</a>

						<a class="dropdown-item del-modal-trigger" href="#" data-toggle="modal" data-target="#delDebtModal">Hapus Hutang</a>

					</div>`;

					$(td).html(actionBtn);
				}
			}
		]
	});

	/**
	 * Debt entry submission
	 */

	$('#debtForm').submit(function (event) {

		event.preventDefault();

		let formData = $(this).serialize();

		let saveDebt = sendAjax(
			`${window.location.origin}/ajax/keuangan_ajax/simpan_hutang`,
			formData
		);

		saveDebt.done(function (data) {
			table.ajax.reload();
		});

		$('#debtEditorModal').modal('hide');

		// $('.toast').toast('show');

	});

	/**
	 * Debt Entry Ajax Edit
	 */

	$('#add-debt-trigger').click(function (event) {

		// Add title to the modal
		$('#debtEditorModal .modal-title').html('Catat Hutang Baru');

		$('#debtForm')[0].reset();

	});

	$('#debtDataTable').on('click', '.edit-debt-trigger', function (event) {

		// Add title to the modal
		$('#debtEditorModal .modal-title').html('Sunting Detail');

		// Grab Entry Data
		let entryRow = $(this).parents('tr');
		let debtId = entryRow.data('debt-id');
		let creditorId = entryRow.data('creditor-id');
		let amount = entryRow.data('amount');
		let note = entryRow.data('note');
		let description = entryRow.data('description');
		let transactionDate = entryRow.data('transaction-date');
		let paymentDate = entryRow.data('payment-date');

		// Fill form with the data
		$('#debtForm #debt-id').val(debtId);
		$('#debtForm #creditors').val(creditorId);
		$('#debtForm #amount').val(amount);
		$('#debtForm #note').val(note);
		$('#debtForm #description').val(description);
		$('#debtForm #transaction-date').val(transactionDate);
		$('#debtForm #payment-date').val(paymentDate);

	});

});