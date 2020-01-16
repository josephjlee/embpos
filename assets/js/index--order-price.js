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
      { "data": "invoice_number" }
    ],
    "columnDefs": [
      {
        "targets": 0,
        "createdCell": function (td, cellData, rowData, row, col) {
          $(td).css('text-align', 'center')
          $(td).html(`<img style="width:33px;height:100%" src="${cellData}">`);
        }
      }
    ]
  });
});