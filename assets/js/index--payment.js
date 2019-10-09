$(document).ready(function () {

	$('#dataTable').DataTable({
		"language": {
			"decimal": ",",
			"thousands": "."
		},
		"order": [
			[1, "desc"]
		],
		"aoColumns": [
			null,
			{
				"sType": "date-uk"
			},
			null,
			null,
			null,
			null
		]

	});

	jQuery.extend(jQuery.fn.dataTableExt.oSort, {
		"date-uk-pre": function (a) {
			var ukDatea = a.split('/');
			return (ukDatea[2] + ukDatea[1] + ukDatea[0]) * 1;
		},

		"date-uk-asc": function (a, b) {
			return ((a < b) ? -1 : ((a > b) ? 1 : 0));
		},

		"date-uk-desc": function (a, b) {
			return ((a < b) ? 1 : ((a > b) ? -1 : 0));
		}
	});

});
