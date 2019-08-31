$(document).ready(function () {

	$('#product-index-table').DataTable({
		"language": {
			"decimal": ",",
			"thousands": "."
		},
		"columnDefs": [{
			"targets": [0, 1, 8],
			"orderable": false
		}],
		"order": [
			[2, "asc"]
		]
	});

});
