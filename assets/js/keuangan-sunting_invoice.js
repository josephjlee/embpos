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



	$('#export-pdf').click(function (event) {

		// Prevent hyperlink's default behaviour
		event.preventDefault();

		// Gather all required data from invoice-editor dom
		const invoiceFileName = $('#invoice-editor').data('invoice-filename');
		const customerName = $('#invoice-editor').data('customer-name');
		const customerAffiliation = $('#invoice-editor').data('customer-company');
		const subTotal = $('#sub-total').val();
		const discount = $('#discount').val();
		const totalDue = $('#total-due').val();
		const paid = $('#paid').val();
		const paymentDue = $('#payment-due').val();
		const invoiceNote = $('#note').val();
		const invoiceDate = $('#invoice-date').val();
		const paymentDate = $('#payment-date').val();
		const invoiceNumber = $('#invoice-number').val();

		// Request orders
		orderReq = sendAjax(
			`${window.location.origin}/ajax/pesanan_ajax/list_order_for_invoice_pdf`,
			{ invoice_id: $('#invoice-form #invoice_id').val() }
		);

		// Assign requested order data to pdfMake's document definition
		orderReq.done(function (data) {

			var dd = {
				content: [
					// Header
					{
						columns: [
							{
								text: 'SWASTI BORDIR',
								style: 'companyTitle',
							},
							{
								text: 'INVOICE',
								style: 'invoiceTitle',
								width: '*'
							}
						]
					},

					// Line breaks
					'\n\n',

					// Billing Info
					{
						columns: [
							{
								stack: [
									// Billing Headers
									{
										text: 'Ditagihkan Kepada',
										style: 'invoiceBillingTitle',
									},
									// Billing Details
									{
										text: `${customerName} \n ${customerAffiliation}`,
										style: 'invoiceBillingDetails'
									},
								]
							},
							{
								stack: [
									{
										columns: [
											{
												text: 'Invoice #',
												style: 'invoiceSubTitle',
												width: '*'
											},
											{
												text: invoiceNumber,
												style: 'invoiceSubValue',
												width: 100
											}
										]
									},
									{
										columns: [
											{
												text: 'Diterbitkan',
												style: 'invoiceSubTitle',
												width: '*'
											},
											{
												text: invoiceDate,
												style: 'invoiceSubValue',
												width: 100
											}
										]
									},
									{
										columns: [
											{
												text: 'Tenggat',
												style: 'invoiceSubTitle',
												width: '*'
											},
											{
												text: paymentDate,
												style: 'invoiceSubValue',
												width: 100
											}
										]
									}
								]
							}
						]
					},

					// Line breaks
					'\n\n',

					// Items
					{
						table: {
							// headers are automatically repeated if the table spans over multiple pages
							// you can declare how many rows should be treated as headers
							headerRows: 1,
							widths: ['*', 40, 'auto', 80],

							body: data
						}, // table
						//  layout: 'lightHorizontalLines'
					},
					// TOTAL
					{
						table: {
							// headers are automatically repeated if the table spans over multiple pages
							// you can declare how many rows should be treated as headers
							headerRows: 0,
							widths: ['*', 80],

							body: [
								// Total
								[
									{
										text: 'Subtotal',
										style: 'itemsFooterTitle'
									},
									{
										text: subTotal,
										style: 'itemsFooterValue'
									}
								],
								[
									{
										text: 'Diskon',
										style: 'itemsFooterTitle'
									},
									{
										text: discount,
										style: 'itemsFooterValue'
									}
								],
								[
									{
										text: 'Total Tagihan',
										style: 'itemsFooterTitle'
									},
									{
										text: totalDue,
										style: 'itemsFooterValue'
									}
								],
								[
									{
										text: 'Pembayaran',
										style: 'itemsFooterTitle'
									},
									{
										text: paid,
										style: 'itemsFooterValue'
									}
								],
								[
									{
										text: 'Sisa Tagihan',
										style: 'itemsFooterTitle'
									},
									{
										text: paymentDue,
										style: 'itemsFooterValue'
									}
								],
							]
						}, // table
						layout: 'lightHorizontalLines'
					},
					// Signature
					{
						columns: [
							{
								text: '',
							},
							{
								stack: [
									{
										text: '_________________________________',
										style: 'signaturePlaceholder'
									},
									{
										text: 'Swasti Riska Putri',
										style: 'signatureName'

									},
									{
										text: 'Direktur',
										style: 'signatureJobTitle'

									}
								],
								width: 180
							},
						]
					},
					// Notes
					{
						text: 'CATATAN',
						style: 'notesTitle'
					},
					{
						text: invoiceNote,
						style: 'notesText'
					}
				],
				styles: {
					// Document Header
					documentHeaderLeft: {
						fontSize: 10,
						margin: [5, 5, 5, 5],
						alignment: 'left'
					},
					documentHeaderCenter: {
						fontSize: 10,
						margin: [5, 5, 5, 5],
						alignment: 'center'
					},
					documentHeaderRight: {
						fontSize: 10,
						margin: [5, 5, 5, 5],
						alignment: 'right'
					},
					// Document Footer
					documentFooterLeft: {
						fontSize: 10,
						margin: [5, 5, 5, 5],
						alignment: 'left'
					},
					documentFooterCenter: {
						fontSize: 10,
						margin: [5, 5, 5, 5],
						alignment: 'center'
					},
					documentFooterRight: {
						fontSize: 10,
						margin: [5, 5, 5, 5],
						alignment: 'right'
					},
					// Company Title
					companyTitle: {
						fontSize: 22,
						bold: true,
						alignment: 'left',
						margin: [0, 0, 0, 15]
					},
					// Invoice Title
					invoiceTitle: {
						fontSize: 22,
						bold: true,
						alignment: 'right',
						margin: [0, 0, 0, 15]
					},
					// Invoice Details
					invoiceSubTitle: {
						fontSize: 12,
						alignment: 'right'
					},
					invoiceSubValue: {
						fontSize: 12,
						alignment: 'right'
					},
					// Billing Headers
					invoiceBillingTitle: {
						fontSize: 14,
						bold: true,
						alignment: 'left',
						margin: [0, 0, 0, 5],
					},
					// Billing Details
					invoiceBillingDetails: {
						alignment: 'left'

					},
					invoiceBillingAddressTitle: {
						margin: [0, 7, 0, 3],
						bold: true
					},
					invoiceBillingAddress: {

					},
					// Items Header
					itemsHeader: {
						margin: [0, 5, 0, 5],
						bold: true
					},
					// Item Title
					itemTitle: {
						bold: true,
					},
					itemSubTitle: {
						italics: true,
						fontSize: 11
					},
					itemNumber: {
						margin: [0, 5, 0, 5],
						alignment: 'right',
					},
					itemTotal: {
						margin: [0, 5, 0, 5],
						bold: true,
						alignment: 'center',
					},

					// Items Footer (Subtotal, Total, Tax, etc)
					itemsFooterTitle: {
						margin: [0, 5, 0, 5],
						bold: true,
						alignment: 'right',
					},
					itemsFooterValue: {
						margin: [0, 5, 5, 5],
						bold: true,
						alignment: 'right',
					},
					signaturePlaceholder: {
						margin: [0, 70, 0, 0],
					},
					signatureName: {
						bold: true,
						alignment: 'center',
					},
					signatureJobTitle: {
						italics: true,
						fontSize: 10,
						alignment: 'center',
					},
					notesTitle: {
						fontSize: 10,
						bold: true,
						margin: [0, 50, 0, 3],
					},
					notesText: {
						fontSize: 10
					},
					center: {
						alignment: 'center',
					},
					right: {
						alignment: 'right'
					}
				},
				defaultStyle: {
					columnGap: 20,
				}
			}

			pdfMake.createPdf(dd).download(invoiceFileName);

		});

	});

});
