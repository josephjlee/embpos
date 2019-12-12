$(document).ready(function () {

	let table = $('#dataTable').DataTable({
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
					console.log(rowData);

					let actionBtn = `
					<a class="dropdown-toggle text-right" href="#" role="button" data-toggle="dropdown">
						<i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
					</a>

					<div class="dropdown-menu dropdown-menu-right shadow animated--fade-in">

						<a class="dropdown-item" href="${window.location.origin}/keuangan/detail_hutang/${rowData.debt_id}">Detail/Sunting</a>

						<a class="dropdown-item del-modal-trigger" href="#" data-toggle="modal" data-target="#delDebtModal">Hapus Hutang</a>

					</div>`;

					$(td).html(actionBtn);
				}
			}
		]
	});

	$('#debtForm').submit(function (event) {

		event.preventDefault();

		let formData = $(this).serialize();
		let saveDebt = sendAjax(
			`${window.location.origin}/ajax/keuangan_ajax/tambah_hutang`,
			formData
		);

		saveDebt.done(function (data) {
			table.ajax.reload();
		});

		$('#addDebtModal').modal('hide');

	});


});

