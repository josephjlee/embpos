$(document).ready(function () {

	// Trigger Delete Product
	$('#invoice-detail-table').on('click', '.product-del-trigger', function (e) {

		// Grab product-sale-id from tr data-attribute which is pre-filled by php
		let currentRow = $(this).parents('tr');
		let productSaleId = currentRow.data('product-sale-id');

		$('#del-product-modal #modal-product-sale-id').val(productSaleId);

	})

	// Trigger Delete Order
	$('#invoice-detail-table').on('click', '.order-del-trigger', function (e) {

		// Grab order-id from tr data-attribute which is pre-filled by php
		let currentRow = $(this).parents('tr');
		let orderId = currentRow.data('order-id');

		console.log(currentRow);
		console.log(orderId);

		$('#del-order-modal #modal-order-id').val(orderId);
		console.log($('#del-order-modal #modal-order-id').val());

	});

	$('#update-payment-amount').keyup(numberFormat);

	// Grab payment data on update-payment-trigger click and asign the data into respective modal 
	$('#paymentHistory').on('click', '.update-payment-trigger', function () {
		
		// Grab payment data from tr data-attribute which is pre-filled by php
		let currentRow = $(this).parents('tr');
		let paymentID = currentRow.data('payment-id');
		let paymentAmount = currentRow.data('payment-amount');
		let paymentMethod = currentRow.data('payment-method');
		let paymentDate = currentRow.data('payment-date');

		// Assign payment data into its respective input in the Payment modal
		$('#updatePaymentModal #update-payment-id').val(paymentID);
		$('#updatePaymentModal #update-payment-amount').val(moneyStr(paymentAmount));
		$('#updatePaymentModal #update-payment-method').val(paymentMethod);
		$('#updatePaymentModal #update-payment-date').val(paymentDate);

	});
	
	// Grab payment data on del-payment-trigger click and asign the data into respective modal 
	$('#paymentHistory').on('click', '.del-payment-trigger', function () {

		// Grab payment data from tr data-attribute which is pre-filled by php
		let currentRow = $(this).parents('tr');
		let paymentID = currentRow.data('payment-id');

		console.log(paymentID);

		// Assign payment data into its respective input in the Update/Delete Payment modal
		$('#del-payment-modal__payment-id').val(paymentID);

	});


});
