$(document).ready(function () {

	let customerForm = $('#customer-form');
	let saveCustBtn = $('#save-customer');

	const itemSelect = $('#item');
	const customerSelect = $('#customer-select');
	const discountInput = $('#discount');
	const quantityInput = $('#quantity');
	const priceInput = $('#price');

	const quantityVal = quantityInput.val();
	const priceVal = priceInput.val();
	const suggestedPrice = moneyStr(moneyInt(priceVal) - 250);

	const colorOrder = $('#spec-modal .modal-body').data('color-order');
	const fileName = $('#spec-modal .modal-body').data('file');
	const flashdisk = $('#spec-modal .modal-body').data('flashdisk');
	const operator = $('#spec-modal .modal-body').data('operator');
	const machineNum = $('#spec-modal .modal-body').data('machine');
	const laborPrice = $('#spec-modal .modal-body').data('labor-price') ? $('#spec-modal .modal-body').data('labor-price') : suggestedPrice;

	const uniqueFormWrapper = $('#spec-modal .modal-body #unique-form-wrapper');

	const materialFormEl = `
			<div class="form-group">
				<label for="thread"><small>Benang</small></label>
				<input type="text" name="production[thread]" id="thread" class="form-control">
			</div>
			<div class="form-group">
				<label for="stabilizer"><small>Kain Keras</small></label>
				<input type="text" name="production[stabilizer]" id="stabilizer" class="form-control">
			</div>
	`;

	const designFormEl = `
			<div class="form-group">
				<label for="repeat"><small>Ulang (kuantitas: ${quantityVal}pcs)</small></label>
				<input type="text" name="production[repeat]" id="repeat" class="form-control">
			</div>
		`;

	const embroFormEl = `
			<div class="form-row">
				<div class="form-group col">
					<label for="otomatis"><small>Otomatis</small></label>
					<input type="text" name="production[color_order]" id="otomatis" class="form-control" value="${colorOrder}">
				</div>
				<div class="form-group col">
					<label for="file"><small>Nama File</small></label>
					<input type="text" name="production[file]" id="file" class="form-control" value="${fileName}">
				</div>
			</div>
			<div class="form-row">
				<div class="form-group col">
					<label for="flashdisk"><small>Flashdisk</small></label>
					<input type="text" name="production[flashdisk]" id="flashdisk" class="form-control" value="${flashdisk}">
				</div>
				<div class="form-group col">
					<label for="mesin"><small>Mesin</small></label>
					<input type="text" name="production[machine]" id="mesin" class="form-control" value="${machineNum}">
				</div>
			</div>
			<div class="form-row">
				<div class="form-group col">
					<label for="operator"><small>Operator</small></label>
					<input type="text" name="production[operator]" id="operator" class="form-control" value="${operator}">
				</div>
				<div class="form-group col">
					<label for="harga"><small>Harga (harga asli: Rp${priceVal})</small></label>
					<input type="text" name="production[labor_price]" id="harga" class="form-control" value="${laborPrice}">
				</div>
			</div>
	`;

	function generatePriceCard() {

		preventNaN($(this));
		outputValTo(moneyInt($(this).val()), $(this));

		let selectedItem = $("#item option:selected");
		let priceConstant = selectedItem.data('priceconst');

		stitchReq = sendAjax(
			`${window.location.origin}/ajax/pesanan_ajax/get_stitch_price`, {
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
                            <h5>${multiplyTwoNums(moneyInt(val.stitch_price), priceConstant)}</h5>
                        </div>
                    </div>
                </div>
            `;

			});

			$('#stitch-row').html(stitchCard);

		})

	}

	function outputAmountCalc() {

		const qty = moneyInt($('#quantity').val()) ? moneyInt($('#quantity').val()) : 0;
		const price = moneyInt($('#price').val()) ? moneyInt($('#price').val()) : 0;
		const discount = moneyInt($('#discount').val()) ? moneyInt($('#discount').val()) : 0;

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
			`${window.location.origin}/ajax/pesanan_ajax/get_item_position`, {
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

	// Set Production Spec
	$('#production-type').change(function (e) {

		// Populate form input element based on production type change
		switch ($(this).val()) {

			case 'material':
				uniqueFormWrapper.html(materialFormEl);
				break;

			case 'design':
				uniqueFormWrapper.html(designFormEl);
				$('#spec-modal #repeat').val($(this).parents('.modal').data('repeat'));
				break;

			case 'embroidery':
				uniqueFormWrapper.html(embroFormEl);
				break;
		}

	});

	// Send Customer Form Data
	$(saveCustBtn).click(function (event) {

		// Prevent browser default submit
		event.preventDefault();

		// Serialize the form data.
		let formData = $(customerForm).serialize();

		// Submit the form using AJAX.
		let saveCustomer = sendAjax(
			`${window.location.origin}/ajax/pelanggan_ajax/tambah_pelanggan`,
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
