$(document).ready(function () {
  // DataTable
  let table = $('#orderPriceTable').DataTable({
    "ajax": `${window.location.origin}/ajax/pesanan_ajax/list_order_price`,
    "columns": [
      { "data": "thumbnail" },
      { "data": "description" },
      { "data": "item_name" },
      { "data": "dimension" },
      {
        "data": {
          "_": "quantity.display",
          "sort": "quantity.raw"
        }
      },
      {
        "data": {
          "_": "price.display",
          "sort": "price.raw"
        }
      },
      { "data": "customer_name" },
      { "data": "invoice_number" },
      { "data": "order_id" }
    ],
    "columnDefs": [
      {
        "targets": 0,
        "createdCell": function (td, cellData, rowData, row, col) {
          $(td).css('text-align', 'center')
          $(td).html(`<img style="width:33px;height:100%" src="${cellData}">`);
        }
      },
      {
        "targets": 7,
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

							<a class="dropdown-item" href="${window.location.origin}/pesanan/sunting/${cellData}">
								Lihat Pesanan
							</a>

							<a class="dropdown-item" href="${window.location.origin}/keuangan/sunting_invoice/${rowData.invoice_number}">
								Lihat Invoice
							</a>

						</div>`;

          $(td).html(actionBtn);
        }
      }
    ],
    "order": [
      [2, "asc"]
    ]
  });
});