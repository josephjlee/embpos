$(document).ready(function () {

	// DataTable
	let table = $('#invoiceTable').DataTable({
		"ajax": `${window.location.origin}/ajax/keuangan_ajax/list_all_invoices`,
		"columns": [
			{ "data": "number" },
			{ "data": "customer" },
			{
				"data": {
					"_": "payment_date.display",
					"sort": "payment_date.raw"
				}
			},
			{
				"data": {
					"_": "payment_due.display",
					"sort": "payment_due.raw"
				}
			},
			{ "data": "payment_status" },
			{ "data": "order_progress" },
			{
				"data": {
					"_": "invoice_date.display",
					"sort": "invoice_date.raw"
				}
			},
			{ "data": "number" }
		],
		"createdRow": function (row, data, dataIndex) {
			$(row).attr('data-invoice-id', data.invoice_id);
		},
		"columnDefs": [
			{
				"targets": [7],
				"orderable": false
			},
			{
				"targets": 0,
				"createdCell": function (td, cellData, rowData, row, col) {
					$(td).html(`INV-${cellData}`);
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

							<a class="dropdown-item" href="${window.location.origin}/keuangan/sunting_invoice/${cellData}">
								Sunting Invoice
							</a>

							<a href="#" data-toggle="modal" data-target="#del-invoice-modal" class="dropdown-item del-modal-trigger">
                Hapus Invoice
              </a>

						</div>`;

					$(td).html(actionBtn);
				}
			}
		],
		"order": [0, "desc"]
	});

	setInterval(function () {
		table.ajax.reload();
	}, 15000);

	$('#filter-select').change(function (e) {
		let filter = $(this).val();
		table.columns(4).search(filter).draw();
	});

	$activeInvoice = $('#active-inv');
	$activeInvoice.load(`${window.location.origin}/ajax/keuangan_ajax/get_total_active_invoice`);

	setInterval(function () {
		$activeInvoice.load(`${window.location.origin}/ajax/keuangan_ajax/get_total_active_invoice`);
	}, 15000);
});


