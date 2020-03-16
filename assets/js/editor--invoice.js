$(document).ready(function () {

	// Define the elements
	let customerSelect = $('#customers');
	let addOrderItemSelect = $('#add-order-modal #item');

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
		let amountClass = document.querySelectorAll('#invoice-detail-table .amount');

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

	function createProductEntry(customerId, productId, productIndex, itemIndex) {

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
					<input type="hidden" name="products[${productIndex}][customer_id]" value="${customerId}">
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

	function createOrderListItem(order) {
		return `
		<div class="order-list-item d-flex justify-content-between align-items-center mb-3" data-order-id="${order.order_id}" data-desc="${order.description}" data-dimension="${order.dimension}" data-qty="${order.quantity}" data-price="${order.price}" data-amount="${order.amount}" id="order-list-item-${order.order_id}">
			<div class="d-flex align-items-center">
					<img class="mr-3" style="width:33px;height:33px" src="http://embpos.com//assets/icon/jacket.png">
					<div>
							<div style="color:#9aa0ac;font-size:13px" class="order-item__required-date">Diambil: ${order.required_date}</div>
							<div style="color:#495057;font-size:15px" class="order-item__description">${order.description}</div>
					</div>
			</div>
			<a href="#" style="color:#AAB0C6" class="add-order-btn" id="add-order-${order.order_id}" data-order-id="${order.order_id}">
					<i class="fas fa-plus"></i>
			</a>
		</div>`;
	}

	function addNewOrder(order, itemIndex, orderIndex) {

		// Row template
		let orderRowTemplate = `
			<tr id="order-entry-${order.order_id}" class="order-entry" data-item-index="${itemIndex}" data-order-index="${orderIndex}" data-order-id="${order.order_id}">
				<input type="hidden" name="orders[${orderIndex}][order_id]" value="${order.order_id}">
				<input type="hidden" name="orders[${orderIndex}][received_date]" value="${order.orderDate}">
				<input type="hidden" name="orders[${orderIndex}][required_date]" value="${order.requiredDate}">
				<input type="hidden" name="orders[${orderIndex}][dimension]" value="${order.dimension}">
				<input type="hidden" name="orders[${orderIndex}][color]" value="${order.color}">
				<input type="hidden" name="orders[${orderIndex}][material]" value="${order.material}">
				<input type="hidden" name="orders[${orderIndex}][note]" value="${order.note}">
				<td id="del-btn-col" class="pr-0">
					<a href='#' style='color:#AAB0C6' class="del-item-btn" data-order-id="${order.order_id}"><i class="fas fa-times"></i></a>
				</td>
				<td id="items-col" style="width:40%">
					<input type="text" id="items" class="items form-control" value="${order.description}" readonly>
				</td>
				<td id="qty-col">
					<input type="text" name="orders[${itemIndex}][quantity]" id="quantity-${orderIndex}" class="form-control text-right quantity number" value="${order.quantity}">
				</td>
				<td id="price-col">
					<input type="text" name="orders[${itemIndex}][price]" id="price-${orderIndex}" class="form-control text-right price number" value="${order.price}">
				</td>
				<td id="amount-col" class="pr-0">
					<input type="text" id="amount-${orderIndex}" class="form-control text-right amount" value="${order.amount}" readonly>
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

	// Initialize Select2/selectize on document load
	initSelect2(customerSelect);

	var $select = addOrderItemSelect.selectize();
	var selectize = $select[0].selectize;

	// AJAX - Populate order-list modal with uninvoiced orders
	customerSelect.change(function () {

		let customerReq = sendAjax(
			`${window.location.origin}/ajax/pesanan_ajax/get_customer_order`,
			{ customer_id: $(this).val() }
		);

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

		// Grab customerId from invoice
		let customerId = $('#customers').val();
		console.log(customerId);
		// Grab productId from the clicked plus button
		let productId = $(this).data('product-id');

		// Assign item & product index for form submission purpose
		itemIndex = getItemEntryIndex();
		productIndex = getEntryIndexFor('product');

		// Create product using createProductEntry function
		let productEntry = createProductEntry(customerId, productId, productIndex, itemIndex);

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

	// Add pre-defined order item
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

	// Add new order to invoice
	$('#add-order-form').submit(function (e) {

		// Prevent form submit default behavior
		e.preventDefault();

		// Fill order form customer_id with invoice's customer_id
		let customerId = $('#customers').val();
		$(this).find('#customer-id').val(customerId);

		// Request new order-number using ajax
		$.ajax({

			url: `${window.location.origin}/ajax/pesanan_ajax/new_order_number`,

			success: function (result) {

				// Assign the new order number into order-number hidden field
				$('#add-order-form #order-number').val(result);

				// Serialize order form data
				let orderFormData = $('#add-order-form').serialize();

				// Submit form data using ajax
				createOrder = sendAjax(
					`${window.location.origin}/ajax/pesanan_ajax/add_order`,
					orderFormData
				);

				createOrder.done(function (data) {

					let orderData = JSON.parse(data);

					console.log(orderData);

					// Assign item & order index for form submission purpose
					let itemIndex = getItemEntryIndex();
					let orderIndex = getEntryIndexFor('order');

					// Append the newly created order into order-list-modal
					let orderListItem = createOrderListItem(orderData);
					orderList.append(orderListItem);

					// Disable order-list to prevent re-added
					toggleListItem('order', orderData.order_id);

					// Create order using createOrderEntry function
					let newOrderEntry = createOrderEntry(orderData.order_id, orderIndex, itemIndex);

					// Append the created order entry into tableBody
					tableBody.append(newOrderEntry);

					// Increment item index
					itemIndex++;

					// Increment order index
					orderIndex++;

					// Collect all amount val into amountArr
					collectAmount();

					// Calculate all amount sum and output the result into calculation table
					updateCalcTable();

				});

				$('#add-order-form').trigger('reset');
				selectize.clear();
			}

		});

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
		let rowIndex = $(this).parents('tr').data('item-index');

		// Grab quantity and price value for amount calculation purpose
		let qty = moneyInt($(this).val());
		let price = moneyInt($(`[data-item-index=${rowIndex}]`).find('.price').val());

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

	//  Update add-order-modal's amount field when its quantity is change
	$('#add-order-modal #quantity').keyup(function (e) {

		// Grab quantity and price value for amount calculation purpose
		let qty = moneyInt($(this).val());
		let price = moneyInt($(this).parents('#add-order-modal').find('#price').val());

		// Format the result of qty*price operation into money string and store into amount variable
		let amount = moneyStr(multiplyTwoNums(qty, price));

		// Output the amount into its respective amount 
		$(this).parents('#add-order-modal').find('#amount').val(amount);

	});

	//  Update add-order-modal's amount field when its price is change
	$('#add-order-modal #price').keyup(function (e) {

		// Grab quantity and price value for amount calculation purpose
		let qty = moneyInt($(this).parents('#add-order-modal').find('#quantity').val());
		let price = moneyInt($(this).val());

		// Format the result of qty*price operation into money string and store into amount variable
		let amount = moneyStr(multiplyTwoNums(qty, price));

		// Output the amount into its respective amount 
		$(this).parents('#add-order-modal').find('#amount').val(amount);

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
	$('#customer-form').submit(function (event) {

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

	// populate add-order-modal positionSelect based on addOrderItemSelect change
	addOrderItemSelect.on('change', function () {

		// Request position by item_id
		positionReq = sendAjax(
			`${window.location.origin}/ajax/pesanan_ajax/get_item_position`,
			{ item_id: $(this).val() }
		);

		// Assign respective position
		positionReq.done(function (data) {

			let options = outputOptions(data, 'Pilih posisi');

			$('#add-order-modal #position').html(options);
			$('#add-order-modal #position').focus();

		});

	});

});