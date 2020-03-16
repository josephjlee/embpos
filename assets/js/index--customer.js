const inputCustTrigger = document.querySelector('#input-cust-trigger');
const customerTable = document.querySelector('#customer-table-card #dataTable');
const customerForm = document.querySelector('#customerForm');

inputCustTrigger.addEventListener('click', function (e) {

	customerForm.reset();
	customerForm.setAttribute('action', `${window.location.origin}/action/pelanggan_action/tambah`)

});

customerTable.addEventListener('click', function (e) {

	e.preventDefault();

	let clickedEl = e.target;
	let currentRow = clickedEl.closest('tr');

	if (clickedEl.matches('.edit-modal-trigger')) {

		customerForm.setAttribute('action', `${window.location.origin}/action/pelanggan_action/perbarui`);

		document.querySelector('#addCustomerModal #cust_id').value = currentRow.dataset.id;

		document.querySelector('#cust_name').value = currentRow.dataset.name;
		document.querySelector('#cust_company').value = currentRow.dataset.company;
		document.querySelector('#cust_address').value = currentRow.dataset.address;
		document.querySelector('#cust_phone').value = currentRow.dataset.phone;
		document.querySelector('#cust_email').value = currentRow.dataset.email;
		document.querySelector('#cust_category').value = currentRow.dataset.category;

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
