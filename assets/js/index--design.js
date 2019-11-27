$(document).ready(function () {


	$('#dataTable').DataTable({
		"language": {
			"decimal": ",",
			"thousands": "."
		},
		"columnDefs": [{
			"targets": [0, 2, 6],
			"orderable": false
		}],
		"order": [
			[4, "desc"]
		]
	});

});
