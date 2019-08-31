$(document).ready(function () {

	let customerForm = $('#customer-form');
	let saveCustBtn = $('#save-customer');

	const itemSelect = $('#item');
	const customerSelect = $('#customer-select');
	const discountInput = $('#discount');
	const quantityInput = $('#quantity');
	const priceInput = $('#price');

	function generatePriceCard() {

		preventNaN($(this));
		outputValTo(moneyInt($(this).val()), $(this));

		let selectedItem = $("#item option:selected");
		let priceConstant = selectedItem.data('priceconst');

		stitchReq = sendAjax(
			'http://embryo.test/ajax/pesanan_ajax/get_stitch_price', {
				quantity_id: quantityID(moneyInt($(this).val()))
			}
		);

		stitchReq.done(function (data) {

			let stitchCard = '';

			stitchData = JSON.parse(data);
			$.each(stitchData, function (prop, val) {

				stitchCard += `
                <div class="col-3 px-1 py-1">
                    <div class="card stitch-card shadow">
                        <div class="card-body">
                            <small>Stitch &#8804 ${val.stitch_count}: </small>
                            <h5>${multiplyTwoNums( moneyInt(val.stitch_price), priceConstant )}</h5>
                        </div>
                    </div>
                </div>
            `;

			});

			$('#stitch-row').html(stitchCard);

		})

	}

	function outputAmountCalc() {

		const qty 		 = moneyInt($('#quantity').val()) ? moneyInt($('#quantity').val()) : 0 ;
		const price 	 = moneyInt($('#price').val()) ? moneyInt($('#price').val()) : 0 ;
		const discount = moneyInt($('#discount').val()) ? moneyInt($('#discount').val()) : 0 ;

		$('#amount').val(moneyStr(multiplyTwoNums(qty, price) - discount));

	}

	// Turn customerSelect into Select2
	initSelect2(customerSelect);

	// Turn itemSelect into Select2
	initSelect2(itemSelect);

	// Populate positionSelect based on itemSelect change
	itemSelect.change(function () {

		// Request position by item_id
		positionReq = sendAjax(
			'http://embryo.test/ajax/pesanan_ajax/get_item_position', {
				item_id: $(this).val()
			}
		);

		// Assign respective position
		positionReq.done(function (data) {

			const positionSelect = $('#position');

			let options = outputOptions(data, 'Pilih posisi');
			positionSelect.html(options);

			positionSelect.focus();

		});

	});

	// Generate stitchCard on quantityInput focus
	quantityInput.focus(generatePriceCard);

	// Generate stitchCard on quantityInput keyup
	quantityInput.keyup(generatePriceCard);

	// Generate amount on quantityInput blur
	quantityInput.keyup(outputAmountCalc);

	// Generate amount on priceInput blur
	priceInput.keyup(outputAmountCalc);

	// Generate amount on discountInput blur
	discountInput.keyup(outputAmountCalc);

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

	// Output money format on keyup
	$('#quantity').keyup(numberFormat);
	$('#price').keyup(numberFormat);
	$('#discount').keyup(numberFormat);

});
