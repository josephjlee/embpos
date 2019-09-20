$(document).ready(function () {

	$('#dataTable').DataTable({
		"language": {
			"decimal": ",",
			"thousands": "."
		},
		"columnDefs": [{
			"targets": [4, 7],
			"orderable": false
		}],
		"order": [
			[0, "desc"]
		]
	});

});
