$(document).ready(function () {

	$('#dataTable').DataTable({
		"language": {
			"decimal": ",",
			"thousands": "."
		},
		"order": [
			[1, "desc"]
		]
	});

	var table = $('#dataTable').DataTable();

	$('#filter-select').change(function (e) {
		let filter = $(this).val();
		table.columns(3).search(filter).draw();
	});

});
