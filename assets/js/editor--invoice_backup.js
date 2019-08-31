$(document).ready(function () {

	// Define the elements
	let customerSelect = $('#customers');

	let customerForm = $('#customer-form');
	let saveCustBtn = $('#save-customer');

	const orderList = $('#order-list-modal .modal-body');

	let amountArr = [];

	let tableBody = $('#invoice-detail-table tbody');

	let subTotalOutput = $('#sub-total');
	let discOutput = $('#discount');
	let paidInput = $('#paid');
	let totalDueOutput = $('#total-due');
	let paymentDueOutput = $('#payment-due');

	// Define the functions
	function sumAmount(amountArr) {

		let sum = 0;
		amountArr.forEach(function (itemAmount) {
			sum += itemAmount;
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

	// Initialize Select2 on document load
	initSelect2(customerSelect);

	// AJAX - Populate order-list modal with uninvoiced orders
	customerSelect.change(function () {

		let customerReq = sendAjax(
			'http://embryo.test/ajax/pesanan_ajax/get_customer_order', {
				customer_id: $(this).val()
			}
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

		e.preventDefault();

		$(this).parent().toggleClass('unavailable');
		$(this).toggleClass('add-product-btn');

		let lastItemEntry 	 = tableBody.find('tr:last');
		let lastProductEntry = tableBody.find('.product-entry:last');

		console.log(lastProductEntry);
		
		let i = lastItemEntry.length == 0 ? 0 : lastItemEntry.data('item-index') + 1;
		let productIndex = lastProductEntry.length == 0 ? 0 : lastProductEntry.data('product-index') + 1;

		let productId = $(this).parent().data('product-id');
		let desc = $(this).parent().data('desc');
		let qty = $(this).parent().data('qty');
		let price = $(this).parent().data('price');
		let amount = $(this).parent().data('amount');

		let stock = parseInt($(`#product-list-item-${productId}`).find('#stock').html());
		let quantity = parseInt(qty);
		let newStock = stock - quantity;

		$(`#product-list-item-${productId} #stock`).html(newStock);

		// Product Row template
		let productRowTemplate = `
			<tr data-item-index="${i}" data-product-index="${productIndex}" data-product-id="${productId}" class="product-entry">
				<input type="hidden" name="products[${productIndex}][product_id]" value="${productId}">
				<input type="hidden" name="products[${productIndex}][product_sale_id]" value="">
				<td id="product-del-btn-col" class="pr-0">
					<a href='#' style='color:#AAB0C6' class="del-product-btn"><i class="fas fa-times"></i></a>
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

		tableBody.append(productRowTemplate);
		i++;
		productIndex++;

		// Grab each item amount and put their value into the amountArr
		let amountClass = document.querySelectorAll('.amount');
		let amountObjArr = Object.values(amountClass);

		// Iterate over to input the amount's value into amountArr
		// using their respective itemIndex as the array index
		amountObjArr.forEach(function (amountOutput) {

			let currentRow = amountOutput.closest('tr');
			let itemIndex = currentRow.dataset.itemIndex;

			amountArr[itemIndex] = moneyInt(amountOutput.value);

		});

		console.log(amountArr);
		amountSum = sumAmount(amountArr);
		console.log(amountSum);

		outputValTo(amountSum, subTotalOutput);

		calcOrder(moneyInt(subTotalOutput.val()), moneyInt(discOutput.val()), moneyInt(paidInput.val()));

	});

	// Delete product item
	tableBody.on("click", ".del-product-btn", function (e) {

		e.preventDefault();

		let delBtn = $(this);
		let currentRow = delBtn.parents('tr');
		let itemIndex = currentRow.data('item-index');
		let productId = currentRow.data('product-id');

		let stock = parseInt($(`#product-list-item-${productId}`).find('#stock').html());
		let quantity = parseInt($(this).parent().nextAll('#product-qty-col').find(`#quantity-${itemIndex}`).val());
		let newStock = stock + quantity;
		$(`#product-list-item-${productId} #stock`).html(newStock);

		$(`#product-list-item-${productId}`).toggleClass('unavailable');
		$(`#add-product-${productId}`).toggleClass('add-product-btn');

		/* Delete product entry by removing the respective row first, 
		then delete the respective element in amountArr */

		currentRow.remove();
		deleteItem(itemIndex);
		amountSum = sumAmount(amountArr);

		outputValTo(amountSum, subTotalOutput);
		discOutput.val('0');
		paidInput.val('0');
		totalDueOutput.val('0');
		paymentDueOutput.val('0');

		calcOrder(moneyInt(subTotalOutput.val()), moneyInt(discOutput.val()), moneyInt(paidInput.val()));


	});

	// Add order item
	$('#order-list-modal').on('click', '.add-item-btn', function (e) {

		e.preventDefault();

		$(this).parent().toggleClass('unavailable');
		$(this).toggleClass('add-item-btn');

		let lastItemEntry = tableBody.find('tr:last');
		let lastOrderEntry = tableBody.find('.order-entry:last');

		let i = lastItemEntry.length == 0 ? 0 : lastItemEntry.data('item-index') + 1;
		let orderIndex = lastOrderEntry.length == 0 ? 0 : lastItemEntry.data('order-index') + 1;

		let orderId = $(this).parent().data('order-id');
		let desc = $(this).parent().data('desc');
		let dimension = $(this).parent().data('dimension');

		if (dimension) {
			desc += ` (${dimension} cm)`;
		}

		let qty = $(this).parent().data('qty');
		let price = $(this).parent().data('price');
		let amount = $(this).parent().data('amount');

		// Row template
		let orderRowTemplate = `
			<tr data-item-index="${i}" data-order-index="${orderIndex}" data-order-id="${orderId}" class="order-entry">
				<input type="hidden" name="orders[${orderIndex}][order_id]" value="${orderId}">
				<td id="del-btn-col" class="pr-0">
					<a href='#' style='color:#AAB0C6' class="del-item-btn"><i class="fas fa-times"></i></a>
				</td>
				<td id="items-col" style="width:40%">
					<input type="text" id="items" class="items form-control" value="${desc}" readonly>
				</td>
				<td id="qty-col">
					<input type="text" id="quantity-${orderIndex}" class="form-control text-right quantity number" value="${qty}" readonly>
				</td>
				<td id="price-col">
					<input type="text" id="price-${orderIndex}" class="form-control text-right price number" value="${price}" readonly>
				</td>
				<td id="amount-col" class="pr-0">
					<input type="text" id="amount-${orderIndex}" class="form-control text-right amount" value="${amount}" readonly>
				</td>
			</tr>
		`;

		tableBody.append(orderRowTemplate);

		i++;
		orderIndex++;

		// Grab each item amount and put their value into the amountArr
		let amountClass = document.querySelectorAll('.amount');
		let amountObjArr = Object.values(amountClass);

		// Iterate over to input the amount's value into amountArr
		// using their respective itemIndex as the array index
		amountObjArr.forEach(function (amountOutput) {

			let currentRow = amountOutput.closest('tr');
			let itemIndex = currentRow.dataset.itemIndex;

			amountArr[itemIndex] = moneyInt(amountOutput.value);

		});

		// console.log(amountArr);
		amountSum = sumAmount(amountArr);
		// console.log(amountSum);

		outputValTo(amountSum, subTotalOutput);

		calcOrder(moneyInt(subTotalOutput.val()), moneyInt(discOutput.val()), moneyInt(paidInput.val()));

	});

	// Delete order item
	tableBody.on("click", ".del-item-btn", function (e) {

		e.preventDefault();

		let delBtn = $(this);
		let currentRow = delBtn.parents('tr');
		let itemIndex = currentRow.data('item-index');
		let orderId = currentRow.data('order-id');

		$(`#order-list-item-${orderId}`).toggleClass('unavailable');
		$(`#add-item-${orderId}`).toggleClass('add-item-btn');

		/* Delete item by removing the respective row first, 
		then delete the respective element in amountArr */

		currentRow.remove();
		deleteItem(itemIndex);
		amountSum = sumAmount(amountArr);

		outputValTo(amountSum, subTotalOutput);
		discOutput.val('0');
		paidInput.val('0');
		totalDueOutput.val('0');
		paymentDueOutput.val('0');

		calcOrder(moneyInt(subTotalOutput.val()), moneyInt(discOutput.val()), moneyInt(paidInput.val()));

	});

	/*** 
	 * Listen for Events on the tableBody 
	 *    and delegate it to the respective element 
	 ************************************************/

	//  Output money format on price type input
	$('#modal-payment-amount').keyup(function () {

		preventNaN($(this));
		outputValTo(moneyInt($(this).val()), $(this));

	});

	$('#discount-amount').keyup(function () {

		preventNaN($(this));
		outputValTo(moneyInt($(this).val()), $(this));

	});

	//  Calculate amount, update the subtotal and calculation panel on 'quantity change (keyup)'
	tableBody.on('keyup', '.quantity', function (e) {

		preventNaN($(this));

		const qty = moneyInt($(this).val());
		const price = moneyInt($(this).parent().next().find('.price').val());

		$(this).parent().nextAll('#product-amount-col').find('.amount').val(moneyStr(multiplyTwoNums(qty, price)));

		// Grab each item amount and put their value into the amountArr
		let amountClass = document.querySelectorAll('.amount');
		let amountObjArr = Object.values(amountClass);

		// Iterate over to input the amount's value into amountArr
		// using their respective itemIndex as the array index
		amountObjArr.forEach(function (amountOutput) {

			let currentRow = amountOutput.closest('tr');
			let itemIndex = currentRow.dataset.itemIndex;

			amountArr[itemIndex] = moneyInt(amountOutput.value);

		});

		console.log(amountArr);
		amountSum = sumAmount(amountArr);
		console.log(amountSum);

		outputValTo(amountSum, subTotalOutput);

		calcOrder(moneyInt(subTotalOutput.val()), moneyInt(discOutput.val()), moneyInt(paidInput.val()));

	});


	// Update calculation panel on 'paid change (keyup)'
	paidInput.keyup(function () {

		preventNaN($(this));
		outputValTo(moneyInt($(this).val()), $(this));

		calcOrder(moneyInt(subTotalOutput.val()), moneyInt(discOutput.val()), moneyInt($(this).val()));

	});

	// Output money format to calculation panel and assign value to hidden input
	$('#payment-form').submit(function (e) {

		e.preventDefault();

		let paidAmount = $('#paid').val();

		let modalPaymentAmount = $('#modal-payment-amount').val()
		let modalPaymentMethod = $('#modal-payment-method').val();

		let newPaidAmount = moneyInt(modalPaymentAmount) + moneyInt(paidAmount);

		$('#paymentModal').modal('hide');

		$('#hidden-payment-amount').val(modalPaymentAmount);
		$('#hidden-payment-method').val(modalPaymentMethod);

		outputValTo(newPaidAmount, $('#paid'));

		calcOrder(moneyInt(subTotalOutput.val()), moneyInt(discOutput.val()), newPaidAmount);

	});

	$('#discount-form').submit(function (e) {

		e.preventDefault();

		let modalDiscountntAmount = $('#discount-amount').val()

		$('#discountModal').modal('hide');
		$('#hidden-discount').val(modalDiscountntAmount);

		outputValTo(moneyInt(modalDiscountntAmount), discOutput);

		calcOrder(moneyInt(subTotalOutput.val()), moneyInt(discOutput.val()), moneyInt(paidInput.val()));

	});

	// Send Customer Form Data
	$(saveCustBtn).click(function (event) {

		// Prevent browser default submit
		event.preventDefault();

		// Serialize the form data.
		let formData = $(customerForm).serialize();

		// Submit the form using AJAX.
		let saveCustomer = sendAjax(
			'http://embryo.test/ajax/pelanggan_ajax/tambah_pelanggan',
			formData
		);

		// Append new customer data to the customerSelect & populate contact detail accordingly
		saveCustomer.done(function (data) {

			let custData = JSON.parse(data);

			let newCustomer = `<option value="${custData.customer_id}">${custData.customer_name}</option>`;
			customerSelect.append(newCustomer);
			customerSelect.val(custData.customer_id);
			customerSelect.select2();

			$('#addCustomerModal').modal('hide');

		});

	});

});
