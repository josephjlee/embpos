const inputCustTrigger = document.querySelector('#input-cust-trigger');
const customerTable = document.querySelector('#customer-table-card #dataTable');

inputCustTrigger.addEventListener('click', function (e) {

	document.querySelector('#customerForm').reset();

});

customerTable.addEventListener('click', function (e) {

	e.preventDefault();

	let clickedEl = e.target;
	let currentRow = clickedEl.closest('tr');

	if (clickedEl.matches('.edit-modal-trigger')) {

		document.querySelector('#addCustomerModal #cust_id').value = currentRow.dataset.id;
		console.log(currentRow.dataset.id);

		document.querySelector('#cust_name').value = currentRow.dataset.name;
		document.querySelector('#cust_company').value = currentRow.dataset.company;
		document.querySelector('#cust_address').value = currentRow.dataset.address;
		document.querySelector('#cust_phone').value = currentRow.dataset.phone;
		document.querySelector('#cust_email').value = currentRow.dataset.email;

	}

	if (clickedEl.matches('.del-modal-trigger')) {

		document.querySelector('#delCustomerModal #cust_id').value = currentRow.dataset.id;
		console.log(document.querySelector('#delCustomerModal #cust_id').value);

	}

});

$('#dataTable').DataTable({
	"language": {
		"decimal": ",",
		"thousands": "."
	},
	"columnDefs": [{
		"targets": [1, 2, 7],
		"orderable": false
	}],
	"order": [
		[0, "asc"]
	]
});
