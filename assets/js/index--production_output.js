$(document).ready(function () {

	$('#dataTable').DataTable({
		"language": {
			"decimal": ",",
			"thousands": "."
		},
		"columnDefs": [{
			"targets": [4],
			"orderable": false
		}]
	});

});
