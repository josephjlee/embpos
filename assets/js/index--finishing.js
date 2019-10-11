$(document).ready(function () {

	$('#dataTable').DataTable({
		"language": {
			"decimal": ",",
			"thousands": "."
		},
		"columnDefs": [{
			"targets": [0, 7],
			"orderable": false
		}],
		"order": [
			[5, "desc"]
		]
	});

});
