$(document).ready(function () {

	// DataTable
	let table = $('#embroLogTable').DataTable({
		"ajax": `${window.location.origin}/ajax/produksi_ajax/list_embro_output_log`,
		"columns": [
			{ "data": "description" },
			{ "data": "operator" },
			{ "data": "machine" },
			{ "data": "shift" },
			{ "data": "started" },
			{ "data": "finished" },
			{ "data": "quantity" },
			{ "data": "labor_price" },
			{ "data": "value" },
			{ "data": "output_embro_id" }
		],
		"columnDefs": [
			{
				"targets": 4,
				"createdCell": function (td, cellData, rowData, row, col) {
					if (!cellData) {
						return;
					}
					const dateTime = cellData.split(' ');
					const dateDiv = `<div class="text-center">${dateTime[0]}</div>`;
					const timeDiv = `<div class="text-center">${dateTime[1]}</div>`;
					$(td).html(dateDiv + timeDiv);
				}
			},
			{
				"targets": 5,
				"createdCell": function (td, cellData, rowData, row, col) {
					if (!cellData) {
						return;
					}
					const dateTime = cellData.split(' ');
					const dateDiv = `<div class="text-center">${dateTime[0]}</div>`;
					const timeDiv = `<div class="text-center">${dateTime[1]}</div>`;
					$(td).html(dateDiv + timeDiv);
				}
			},
			{
				"targets": 6,
				"createdCell": function (td, cellData, rowData, row, col) {
					$(td).html(`<div class="text-right">${cellData}</div>`);
				}
			},
			{
				"targets": 7,
				"createdCell": function (td, cellData, rowData, row, col) {
					$(td).html(`<div class="text-right">${cellData}</div>`);
				}
			},
			{
				"targets": 8,
				"createdCell": function (td, cellData, rowData, row, col) {
					$(td).html(`<div class="text-right">${cellData}</div>`);
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

							<a class="dropdown-item" href="${window.location.origin}/pesanan/sunting/${cellData}">
								Sunting Pesanan
							</a>

							<a class="dropdown-item status-mark-trigger" href="#" data-toggle="modal" data-target="#mark-as-modal" data-status-id="">Tandai Sebagai</a>

							<a class="dropdown-item del-modal-trigger" href="#" data-toggle="modal" data-target="#del-order-modal">Hapus Pesanan</a>

							<a class="dropdown-item" href="${window.location.origin}/keuangan/sunting_invoice/${rowData.invoice_number}">
								Lihat Invoice
							</a>

						</div>`;

					$(td).html(actionBtn);
				}
			}
		],
		"order": [
			[4, "desc"]
		],
		"footerCallback": function (row, data, start, end, display) {
			var api = this.api(), data;

			// Remove the formatting to get integer data for summation
			var intVal = function (i) {
				return typeof i === 'string' ?
					i.replace(/[\$,]/g, '') * 1 :
					typeof i === 'number' ?
						i : 0;
			};

			// Total over all pages
			total = api
				.column(8)
				.data()
				.reduce(function (a, b) {
					return intVal(a) + intVal(b);
				}, 0);

			// Total over this page
			pageTotal = api
				.column(8, { page: 'current' })
				.data()
				.reduce(function (a, b) {
					return intVal(a) + intVal(b);
				}, 0);

			// Update footer
			$(api.column(8).footer()).html(
				// 'Rp' + pageTotal + ' ( $' + total + ' total)'
				`<div class="d-flex justify-content-between">
					<div>Rp</div>
					<div>${pageTotal}</div>
				</div>
				<div class="d-flex">
					<div>Rp</div>
					<div>${total}</div>
				</div>`
			);

		}
	});

	// setInterval(function () {
	// 	table.ajax.reload();
	// }, 15000);

	// $('#filter-select').change(function (e) {
	// 	let filter = $(this).val();
	// 	table.columns(8).search(filter).draw();
	// });

});