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
			[6, "desc"]
		],
		"aoColumns": [
			null,
			null,
			{ "sType": "date-uk" },
			null,
			null,
			null,  
			{ "sType": "date-uk" },
			null
	]
	});

	jQuery.extend( jQuery.fn.dataTableExt.oSort, {
		"date-uk-pre": function ( a ) {
				var ukDatea = a.split('/');
				return (ukDatea[2] + ukDatea[1] + ukDatea[0]) * 1;
		},
		
		"date-uk-asc": function ( a, b ) {
				return ((a < b) ? -1 : ((a > b) ? 1 : 0));
		},
		
		"date-uk-desc": function ( a, b ) {
				return ((a < b) ? 1 : ((a > b) ? -1 : 0));
		}
		} );

});


