$(document).ready(function () {

	// DataTable
	let table = $('#orderTable').DataTable({
		"ajax": `${window.location.origin}/ajax/pesanan_ajax/list_all_orders`,
		"columns": [
			{ "data": "thumbnail" },
			{ "data": "description" },
			{ "data": "item_name" },
			{ "data": "position_name" },
			{ "data": "dimension" },
			{ "data": "color" },
			{
				"data": {
					"_": "quantity.display",
					"sort": "quantity.raw"
				}
			},
			{
				"data": {
					"_": "order_deadline.display",
					"sort": "order_deadline.raw"
				}
			},
			{ "data": "production_status" },
			{ "data": "customer_name" },
			{ "data": "order_id" }
		],
		"createdRow": function (row, data, dataIndex) {
			$(row).attr('data-order-id', data.order_id);
		},
		"columnDefs": [
			{
				"targets": 0,
				"createdCell": function (td, cellData, rowData, row, col) {
					$(td).css('text-align', 'center')
					$(td).html(`<img style="width:33px;height:100%" src="${cellData}">`);
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
			[8, "asc"],
			[7, "asc"]
		]
	});

	setInterval(function () {
		table.ajax.reload();
	}, 15000);

	$('#filter-select').change(function (e) {
		let filter = $(this).val();
		table.columns(8).search(filter).draw();
	});


});
