$(document).ready(function () {

	// Define the elements
	let customerSelect = $('#customers');
	let itemSelect = $('#item');

	const orderList = $('#order-list-modal .modal-body');
	let amountArr = [];
	let paidArr = [moneyInt($('#paid').val())];
	let tableBody = $('#invoice-detail-table tbody');

	let subTotalOutput = $('#sub-total');
	let discOutput = $('#discount');
	let paidInput = $('#paid');
	let totalDueOutput = $('#total-due');
	let paymentDueOutput = $('#payment-due');

	// Define the functions
	function sumArray(array) {

		let sum = 0;

		array.forEach(function (value) {
			sum += value;
		});

		return sum;

	}

	function calcOrder(subtotalVal, discount = 0, paid = 0) {

		totalDueVal = substractTwoNums(subtotalVal, discount);
		paymentDueVal = substractTwoNums(totalDueVal, paid);

		outputValTo(totalDueVal, totalDueOutput);
		outputValTo(paymentDueVal, paymentDueOutput);

	}

	function deleteItem(itemIndex) {
		amountArr.splice(itemIndex, 1);
	}

	function collectAmount() {

		// Select each item amount 
		let amountClass = document.querySelectorAll('.amount');

		// Convert all selected .amount class into an array of amount object
		let amountObjArr = Object.values(amountClass);

		// Iterate over each object to grab each amount's value and itemIndex
		amountObjArr.forEach(function (amountOutput) {

			let currentRow = amountOutput.closest('tr');
			let itemIndex = currentRow.dataset.itemIndex;

			// Push the amount's value into amountArr using its respective itemIndex as the array index
			amountArr[itemIndex] = moneyInt(amountOutput.value);

		});

	}

	function updateCalcTable() {

		// Sum all amount value found in the amountArr
		amountSum = sumArray(amountArr);

		// Output the sum result into subTotalOutput
		outputValTo(amountSum, subTotalOutput);

		// Calculate all needed information and output the result into each respective element
		calcOrder(moneyInt(subTotalOutput.val()), moneyInt(discOutput.val()), moneyInt(paidInput.val()));
	}

	function toggleListItem(listItemType, listItemId) {

		$(`#${listItemType}-list-item-${listItemId}`).toggleClass('unavailable');

		$(`#add-${listItemType}-${listItemId}`).toggleClass(`add-${listItemType}-btn`);

	}

	function getItemEntryIndex() {

		// Find out the last row entry to determine the start of itemIndex count
		let lastItemEntry = tableBody.find('tr:last');

		// When there is a row entry, continue the count from that number by adding 1. Otherwise, start from 0.
		let itemIndex = lastItemEntry.length != 0 ? lastItemEntry.data('item-index') + 1 : 0;

		return itemIndex;

	}

	function getEntryIndexFor(entryType) {

		// Find out the last product entry of a specific entry type (product/order) to determine the start of index count
		let lastEntry = tableBody.find(`.${entryType}-entry:last`);

		// When there is an entry, continue the count from that number by adding 1. Otherwise, start from 0. 
		let entryIndex = lastEntry.length != 0 ? lastEntry.data(`${entryType}-index`) + 1 : 0;

		return entryIndex;

	}

	function createProductEntry(productId, productIndex, itemIndex) {

		/**
		 * --------------------------------
		 * Construct the product entry row
		 * --------------------------------
		 * 
		 * Assign detail into its respective element identified by productId.
		 * 
		 */

		let productListItem = $(`#product-list-item-${productId}`);

		let desc = productListItem.data('desc');
		let qty = productListItem.data('qty');
		let price = productListItem.data('price');
		let amount = productListItem.data('amount');

		let productRowTemplate = `
				<tr data-item-index="${itemIndex}" data-product-index="${productIndex}" data-product-id="${productId}" class="product-entry" id="product-entry-${productId}">
					<input type="hidden" name="products[${productIndex}][product_id]" value="${productId}">
					<input type="hidden" name="products[${productIndex}][product_sale_id]" value="">
					<td id="product-del-btn-col" class="pr-0">
						<a href='#' style='color:#AAB0C6' class="del-product-btn" data-product-id="${productId}"><i class="fas fa-times"></i></a>
					</td>
					<td id="product-items-col" style="width:40%">
						<input type="text" id="items" class="items form-control" value="${desc}" readonly>
					</td>
					<td id="product-qty-col">
						<input type="text" name="products[${productIndex}][quantity]" id="quantity-${productIndex}" class="form-control text-right quantity number" value="${qty}">
					</td>
					<td id="product-price-col">
						<input type="text" name="products[${productIndex}][price]" id="price-${productIndex}" class="form-control text-right price number" value="${price}" readonly>
					</td>
					<td id="product-amount-col" class="pr-0">
						<input type="text" id="amount-${productIndex}" class="form-control text-right amount" value="${amount}" readonly>
					</td>
				</tr>
			`;

		return productRowTemplate;

	}

	function createOrderEntry(orderId, orderIndex, itemIndex) {

		let orderListItem = $(`#order-list-item-${orderId}`);

		let desc = orderListItem.data('desc');
		let dimension = orderListItem.data('dimension');

		if (dimension) {
			desc += ` (${dimension} cm)`;
		}

		let qty = orderListItem.data('qty');
		let price = orderListItem.data('price');
		let amount = orderListItem.data('amount');

		// Row template
		let orderRowTemplate = `
			<tr id="order-entry-${orderId}" class="order-entry" data-item-index="${itemIndex}" data-order-index="${orderIndex}" data-order-id="${orderId}">
				<input type="hidden" name="orders[${orderIndex}][order_id]" value="${orderId}">
				<td id="del-btn-col" class="pr-0">
					<a href='#' style='color:#AAB0C6' class="del-item-btn" data-order-id="${orderId}"><i class="fas fa-times"></i></a>
				</td>
				<td id="items-col" style="width:40%">
					<input type="text" id="items" class="items form-control" value="${desc}" readonly>
				</td>
				<td id="qty-col">
					<input type="text" name="orders[${itemIndex}][quantity]" id="quantity-${orderIndex}" class="form-control text-right quantity number" value="${qty}">
				</td>
				<td id="price-col">
					<input type="text" name="orders[${itemIndex}][price]" id="price-${orderIndex}" class="form-control text-right price number" value="${price}">
				</td>
				<td id="amount-col" class="pr-0">
					<input type="text" id="amount-${orderIndex}" class="form-control text-right amount" value="${amount}" readonly>
				</td>
			</tr>
		`;

		return orderRowTemplate;
	}

	function removeEntry(entryType, entryId, itemIndex) {

		// Remove the respective product-entry row
		$(`#${entryType}-entry-${entryId}`).remove();

		// Delete its amount value from the amountArr
		deleteItem(itemIndex);

	}

	function calcProductListStock(productId, operationType) {

		// Grab product-list stock and parse into integer 
		let stock = parseInt($(`#product-list-item-${productId}`).find('#stock').html());

		// 1 is the default quantity value for product entry
		let quantity = 1;

		// Declare a newStock variable to hold the new stock value 
		let newStock

		// Substraction operation. Used when the product-list is added into invoice table
		if (operationType == 'minus') {
			newStock = stock - quantity;
		}

		// Addition operation. Used when the product-list is removed from invoice table
		if (operationType == 'plus') {
			newStock = stock + quantity;
		}

		// Output the newStock
		$(`#product-list-item-${productId} #stock`).html(newStock);

	}

	// Initialize Select2 on document load
	initSelect2(customerSelect);
	var $select = itemSelect.selectize();
	var selectize = $select[0].selectize;

	// AJAX - Populate order-list modal with uninvoiced orders
	customerSelect.change(function () {

		let customerReq = sendAjax(
			`${window.location.origin}/ajax/pesanan_ajax/get_customer_order`,
			{ customer_id: $(this).val() }
		);

		console.log(customerReq);

		customerReq.done(function (data) {

			let orderListItems = JSON.parse(data);

			if (orderListItems.length != 0) {
				orderList.html(orderListItems);
			} else {
				orderList.html('Tidak ada pesanan');
			}

		});

	});

	// Add product item
	$('#product-list-modal').on('click', '.add-product-btn', function (e) {

		// Prevent default link behavior
		e.preventDefault();

		// Grab productId from the clicked plus button
		let productId = $(this).data('product-id');

		// Assign item & product index for form submission purpose
		itemIndex = getItemEntryIndex();
		productIndex = getEntryIndexFor('product');

		// Create product using createProductEntry function
		let productEntry = createProductEntry(productId, productIndex, itemIndex);

		// Append product entry into tableBody
		tableBody.append(productEntry);

		// Increment item index
		itemIndex++;

		// Increment product index
		productIndex++;

		// Perform real-time stock substraction
		calcProductListStock(productId, 'minus');

		// Collect all amount val into amountArr
		collectAmount();

		// Calculate all amount sum and output the result into calculation table
		updateCalcTable();

		// Disable product-list to prevent re-added
		toggleListItem('product', productId);

	});

	// Delete product item
	tableBody.on("click", ".del-product-btn", function (e) {

		// Prevent default behavior
		e.preventDefault();

		// Grab productId for dom selection purpose
		let productId = $(this).data('product-id');

		// Grab itemIndex for array calculation purpose
		let itemIndex = $(`#product-entry-${productId}`).data('item-index');

		// Reset product-list stock value (adding 1)
		calcProductListStock(productId, 'plus');

		// Remove product entry from invoice table
		removeEntry('product', productId, itemIndex);

		// Calculate all amount sum and output the result into calculation table
		updateCalcTable();

		// Re-enable product-list 
		toggleListItem('product', productId);

	});

	// Add order item
	$('#order-list-modal').on('click', '.add-order-btn', function (e) {

		// Prevent default link behavior
		e.preventDefault();

		// Grab orderId from the clicked plus button
		let orderId = $(this).data('order-id');

		// Assign item & order index for form submission purpose
		let itemIndex = getItemEntryIndex();
		let orderIndex = getEntryIndexFor('order');

		// Create order using createOrderEntry function
		let orderRowTemplate = createOrderEntry(orderId, orderIndex, itemIndex);

		// Append the created order entry into tableBody
		tableBody.append(orderRowTemplate);

		// Increment item index
		itemIndex++;

		// Increment order index
		orderIndex++;

		// Collect all amount val into amountArr
		collectAmount();

		// Calculate all amount sum and output the result into calculation table
		updateCalcTable();

		// Disable order-list to prevent re-added
		toggleListItem('order', orderId);

	});

	// Delete order item
	tableBody.on("click", ".del-item-btn", function (e) {

		// Prevent default behavior
		e.preventDefault();

		// Grab orderId for dom selection purpose
		let orderId = $(this).data('order-id');

		// Grab itemIndex for array calculation purpose
		let itemIndex = $(`#order-entry-${orderId}`).data('item-index');

		// Remove order entry from invoice table
		removeEntry('order', orderId, itemIndex);

		// Calculate all amount sum and output the result into calculation table
		updateCalcTable();

		// Re-enable order-list 
		toggleListItem('order', orderId);

	});

	/*** 
	 * Listen for Events on the tableBody 
	 *    and delegate it to the respective element 
	 ************************************************/

	//  Output money format on price type input
	$('#modal-payment-amount').keyup(numberFormat);
	$('#discount-amount').keyup(numberFormat);

	//  Calculate amount, update the subtotal and calculation panel on 'product quantity change (keyup)'
	tableBody.on('keyup', '.quantity', function (e) {

		preventNaN($(this));

		// Grab productId from parent row data attribute
		// let productId = $(this).parents('tr').data('product-id');
		let rowIndex = $(this).parents('tr').data('item-index');

		// Grab quantity and price value for amount calculation purpose
		let qty = moneyInt($(this).val());
		// let price = moneyInt($(`#product-entry-${productId}`).find('.price').val());
		let price = moneyInt($(`[data-item-index=${rowIndex}]`).find('.price').val());

		console.log('RowIndex: ' + rowIndex);
		console.log('price: ' + price);

		// Format the result of qty*price operation into money string and store into amount variable
		let amount = moneyStr(multiplyTwoNums(qty, price))

		// Output the amount into its respective amount 
		$(`[data-item-index=${rowIndex}]`).find('.amount').val(amount);

		// Collect all amount value to recalculate the subtotal
		collectAmount();

		// Output the calculation result into calculation table
		updateCalcTable();

	});

	tableBody.on('keyup', '.price', function (e) {

		preventNaN($(this));

		// Grab itemIndex from parent row data attribute
		let rowIndex = $(this).parents('tr').data('item-index');

		// Grab quantity and price value for amount calculation purpose
		let qty = moneyInt($(`[data-item-index=${rowIndex}]`).find('.quantity').val());
		let price = moneyInt($(this).val());

		// Format the result of qty*price operation into money string and store into amount variable
		let amount = moneyStr(multiplyTwoNums(qty, price))

		// Output the amount into its respective amount 
		$(`[data-item-index=${rowIndex}]`).find('.amount').val(amount);

		// Collect all amount value to recalculate the subtotal
		collectAmount();

		// Output the calculation result into calculation table
		updateCalcTable();

	});

	// Process user-inputted amount
	$('#payment-form').submit(function (e) {

		// Prevent default behavior
		e.preventDefault();

		// Grab payment detail from modal 
		let modalPaymentAmount = $('#modal-payment-amount').val()
		let modalPaymentMethod = $('#modal-payment-method').val();

		// Assign payment detail into hidden payment input
		$('#hidden-payment-amount').val(modalPaymentAmount);
		$('#hidden-payment-method').val(modalPaymentMethod);

		// Assign user-inputed payment amount as the second element of paidArr
		paidArr[1] = moneyInt(modalPaymentAmount);

		// Add user inputed payment amount to the initial paid value
		let newPaidAmount = sumArray(paidArr);

		// Output the sum value of paidArr into #paid 
		outputValTo(newPaidAmount, $('#paid'));

		// Update invoice calculation table
		calcOrder(moneyInt(subTotalOutput.val()), moneyInt(discOutput.val()), newPaidAmount);

		// Hide payment modal
		$('#paymentModal').modal('hide');

	});

	// Process user-inputted discount
	$('#discount-form').submit(function (e) {

		// Prevent default behavior
		e.preventDefault();

		// Grab user-inputted discount value and assign to #hidden-discount
		let modalDiscountntAmount = $('#discount-amount').val()
		$('#hidden-discount').val(modalDiscountntAmount);

		// Output modal discount amount into discOutput
		outputValTo(moneyInt(modalDiscountntAmount), discOutput);

		// Update invoice calculation table
		calcOrder(moneyInt(subTotalOutput.val()), moneyInt(discOutput.val()), moneyInt(paidInput.val()));

		// Hide payment modal
		$('#discountModal').modal('hide');

	});

	// Send Customer Form Data
	$('#save-customer').click(function (event) {

		// Prevent browser default submit
		event.preventDefault();

		// Serialize the form data.
		let formData = $('#customer-form').serialize();

		// Submit the form using AJAX.
		let saveCustomer = sendAjax(
			`${window.location.origin}/ajax/pelanggan_ajax/tambah_pelanggan`,
			formData
		);

		saveCustomer.done(function (data) {

			// Parse ajax response data and assign into custData
			let custData = JSON.parse(data);

			// Create option element for the newly added customer
			let newCustomer = `<option value="${custData.customer_id}">${custData.customer_name}</option>`;

			// Append new customer option elemenet into customerSelect
			customerSelect.append(newCustomer);

			// Set customerSelect value to the newly created customer_id
			customerSelect.val(custData.customer_id);

			// Transform customerSelect into select2
			customerSelect.select2();

			// Hide customer modal
			$('#addCustomerModal').modal('hide');

		});

	});

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
			{ item_id: itemSelect.val() }
		);

		// Assign respective position
		positionReq.done(function (data) {

			let options = outputOptions(data, 'Pilih posisi');

			$('#position').html(options);
			$('#order-detail-modal #position').val(positionId);

		});

		$('#order-detail-modal #item').data('is-selected', true);

	});

	// After order detail modal is shown, populate positionSelect based on itemSelect change
	itemSelect.on('change', function () {

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
				$('#position').html(options);
				$('#position').focus();
			}

		});

	});

	// Populate Order Process Modal on 'Lihat Proses' click
	$('#process').on('click', '.view-order-process', function (e) {

		let description = $(this).parents('tr').data('description');

		$('#order-process-modal .modal-title').html(description);

	});

	// Set Selected Process in Status Mark Modal on 'Tandai Sebagai' click
	$('#process').on('click', '.status-mark-trigger', function (e) {

		let description = $(this).parents('tr').data('description');
		let processStatusId = $(this).parents('tr').data('process-status-id');

		$('#update-process-modal .modal-title').html(description);
		$('#update-process-modal #process-status').val(processStatusId);

	});



});