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
});


