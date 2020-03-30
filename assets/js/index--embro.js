$(document).ready(function () {

	// DataTable
	let table = $('#embroListTable').DataTable({
		"ajax": `${window.location.origin}/ajax/produksi_ajax/list_all_embro`,
		"columns": [
			{ "data": "thumbnail" },
			{ "data": "order_number" },
			{ "data": "title" },
			{ "data": "machine" },
			{
				"data": {
					"_": "required.display",
					"sort": "required.raw"
				}
			},
			{
				"data": {
					"_": "status.display",
					"sort": "status.raw"
				}
			},
			{ "data": "order_id" }
		],
		"columnDefs": [
			{
				"targets": [0],
				"orderable": false
			},
			{
				"targets": 0,
				"createdCell": function (td, cellData, rowData, row, col) {
					$(td).css('text-align', 'center')
					$(td).html(`<img style="width:33px;height:100%" src="${cellData}">`);
				}
			},
			{
				"targets": 1,
				"createdCell": function (td, cellData, rowData, row, col) {
					$(td).html(`PSN-${cellData}`);
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

						<a class="dropdown-item" href="${window.location.origin}/produksi/detail_bordir/${rowData.order_id}">
							Sunting Detail
						</a>

						<a class="dropdown-item" href="${window.location.origin}/pesanan/sunting/${rowData.order_id}">
							Lihat Pesanan
						</a>

					</div>`;

					$(td).html(actionBtn);
				}
			}
		],
		"order": [
			[5, "asc"],
			[4, "asc"]
		]
	});

	setInterval(function () {
		table.ajax.reload();
	}, 15000);

});
