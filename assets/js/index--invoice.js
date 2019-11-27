$(document).ready(function () {
	$('#dataTable').DataTable({
		"language": {
			"decimal": ",",
			"thousands": "."
		},
		"columnDefs": [{
			"targets": [7],
			"orderable": false
		}],
		"order": [
			[2, "desc"]
		]
	});

	var table = $('#dataTable').DataTable();

	$('#filter-select').change(function (e) {
		let filter = $(this).val();
		table.columns(4).search(filter).draw();
	});
});


