$(document).ready(function () {

	let orderDetailItemSelect = $('#order-detail-modal #item');
	var $select = orderDetailItemSelect.selectize();
	var selectize = $select[0].selectize;

	// Populate Order Detail Modal on 'Lihat Detail' click
	$('#process').on('click', '.view-order-detail', function (e) {

		let orderId = $(this).parents('tr').data('order-id');
		let description = $(this).parents('tr').data('description');
		let orderDate = $(this).parents('tr').data('order-date');
		let requiredDate = $(this).parents('tr').data('required-date');
		let itemId = $(this).parents('tr').data('item-id');
		let positionId = $(this).parents('tr').data('position-id');
		let dimension = $(this).parents('tr').data('dimension');
		let color = $(this).parents('tr').data('color');
		let material = $(this).parents('tr').data('material');
		let quantity = $(this).parents('tr').data('quantity');
		let price = $(this).parents('tr').data('price');
		let amount = $(this).parents('tr').data('amount');
		let note = $(this).parents('tr').data('note');

		$('#order-detail-modal #order-id').val(orderId);
		$('#order-detail-modal #received-date').val(orderDate);
		$('#order-detail-modal #required-date').val(requiredDate);
		$('#order-detail-modal #description').val(description);
		$('#order-detail-modal #dimension').val(dimension);
		$('#order-detail-modal #color').val(color);
		$('#order-detail-modal #material').val(material);
		$('#order-detail-modal #quantity').val(quantity);
		$('#order-detail-modal #price').val(price);
		$('#order-detail-modal #amount').val(amount);
		$('#order-detail-modal #note').val(note);

		selectize.setValue(itemId, false);

		// Request position by item_id
		positionReq = sendAjax(
			`${window.location.origin}/ajax/pesanan_ajax/get_item_position`,
			{ item_id: orderDetailItemSelect.val() }
		);

		// Assign respective position
		positionReq.done(function (data) {

			let options = outputOptions(data, 'Pilih posisi');

			$('#order-detail-modal #position').html(options);
			$('#order-detail-modal #position').val(positionId);

		});

		$('#order-detail-modal #item').data('is-selected', true);

	});

	// After order detail modal is shown, populate positionSelect based on orderDetailItemSelect change
	orderDetailItemSelect.on('change', function () {

		let isSelected = $(this).data('is-selected');

		// Request position by item_id
		positionReq = sendAjax(
			`${window.location.origin}/ajax/pesanan_ajax/get_item_position`,
			{ item_id: $(this).val() }
		);

		// Assign respective position
		positionReq.done(function (data) {

			let options = outputOptions(data, 'Pilih posisi');

			if (isSelected) {
				$('#order-detail-modal #position').html(options);
				$('#order-detail-modal #position').focus();
			}

		});

	});

	// Populate Order Process Modal on 'Lihat Proses' click
	$('#process').on('click', '.view-order-process', function (e) {

		let description = $(this).parents('tr').data('description');
		let designStatus = $(this).parents('tr').data('status-design');
		let embroStatus = $(this).parents('tr').data('status-embro');
		let finishingStatus = $(this).parents('tr').data('status-finishing');
		let designOutput = $(this).parents('tr').data('output-design');
		let embroOutput = $(this).parents('tr').data('output-embro');
		let finishingOutput = $(this).parents('tr').data('output-finishing');

		$('#order-process-modal .modal-title').html(description);
		$('#order-process-modal #design-progress-bar-title').html(`${designStatus}%`);
		$('#order-process-modal #embro-progress-bar-title').html(`${embroStatus}%`);
		$('#order-process-modal #finishing-progress-bar-title').html(`${finishingStatus}%`);
		$('#order-process-modal #design-progress-bar').css('width', `${designStatus}%`);
		$('#order-process-modal #embro-progress-bar').css('width', `${embroStatus}%`);
		$('#order-process-modal #finishing-progress-bar').css('width', `${finishingStatus}%`);
		$('#order-process-modal #design-progress-bar').attr('title', `${designOutput}`);
		$('#order-process-modal #embro-progress-bar').attr('title', `${embroOutput}pcs`);
		$('#order-process-modal #finishing-progress-bar').attr('title', `${finishingOutput}pcs`);

	});

	// Set Selected Process in Status Mark Modal on 'Tandai Sebagai' click
	$('#process').on('click', '.status-mark-trigger', function (e) {

		let orderId = $(this).parents('tr').data('order-id');
		let description = $(this).parents('tr').data('description');
		let processStatusId = $(this).parents('tr').data('process-status-id');

		$('#update-process-modal .modal-title').html(description);
		$('#update-process-modal #order-id').val(orderId);
		$('#update-process-modal #process-status').val(processStatusId);

	});

	// Set Operator Price Trigger
	$('#process').on('click', '.set-price-trigger', function (e) {
		let orderId = $(this).parents('tr').data('order-id');
		let productionId = $(this).parents('tr').data('production-id');
		let description = $(this).parents('tr').data('description');
		let price = $(this).parents('tr').data('price');
		let laborPrice = $(this).parents('tr').data('labor-price')

		if (laborPrice) {
			$('#operator-price-modal #set-price-btn').html('Perbarui');
			$('#operator-price-modal #original-price').html(`Harga Operator (harga asli: ${price})`);
			$('#operator-price-modal #labor-price').val(laborPrice);
		}
		else {
			suggestedPrice = parseInt(price) - 250;
			$('#operator-price-modal #original-price').html(`Saran Harga Operator (harga asli: Rp${price})`);
			$('#operator-price-modal #labor-price').val(suggestedPrice.toString());
		};

		$('#operator-price-modal .modal-title').html(description);
		$('#operator-price-modal #order-id').val(orderId);
		$('#operator-price-modal #production-id').val(productionId);
	});

	// Upload Artwork Trigger
	$('#process').on('click', '.upload-artwork-trigger', function (e) {
		let orderId = $(this).parents('tr').data('order-id');
		let description = $(this).parents('tr').data('description');

		$('#upload-artwork-modal .modal-title').html(description);
		$('#upload-artwork-modal #order-id').val(orderId);
	});

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

	// Output number format on payment amount field keyup
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
