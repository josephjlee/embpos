$(document).ready(function () {

	// Initialize Flatpickr
	flatpickr(".date-time-picker", {
		enableTime: true,
		dateFormat: "Y-m-d H:i",
	});

	let customerForm = $('#customer-form');
	let saveCustBtn = $('#save-customer');

	const itemSelect = $('#item');
	const customerSelect = $('#customer-select');
	const quantityInput = $('#quantity');
	const priceInput = $('#price');

	const quantityVal = quantityInput.val();
	const priceVal = priceInput.val();
	const suggestedPrice = moneyStr(moneyInt(priceVal) - 250);

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
			<div class="form-group">
				<label for="harga"><small>Harga Operator</small></label>
				<input type="text" name="production[labor_price]" id="harga" class="form-control" value="${suggestedPrice}">
			</div>

			<p class="mb-1"><small>Kerjakan di Mesin:</small></p>
			<div class="form-row">
				<div class="form-group col">
					<div class="custom-control custom-checkbox col">
						<input type="checkbox" name="production[machine_number][]" class="custom-control-input" id="machine-1" value="1">
						<label class="custom-control-label" for="machine-1">1</label>
					</div>
				</div>
				<div class="form-group col">
					<div class="custom-control custom-checkbox col">
						<input type="checkbox" name="production[machine_number][]" class="custom-control-input" id="machine-2" value="2">
						<label class="custom-control-label" for="machine-2">2</label>
					</div>
				</div>
				<div class="form-group col">
					<div class="custom-control custom-checkbox col">
						<input type="checkbox" name="production[machine_number][]" class="custom-control-input" id="machine-3" value="3">
						<label class="custom-control-label" for="machine-3">3</label>
					</div>
				</div>
				<div class="form-group col">
					<div class="custom-control custom-checkbox col">
						<input type="checkbox" name="production[machine_number][]" class="custom-control-input" id="machine-4" value="4">
						<label class="custom-control-label" for="machine-4">4</label>
					</div>
				</div>
				<div class="form-group col">
					<div class="custom-control custom-checkbox col">
						<input type="checkbox" name="production[machine_number][]" class="custom-control-input" id="machine-5" value="5">
						<label class="custom-control-label" for="machine-5">5</label>
					</div>
				</div>
				<div class="form-group col">
					<div class="custom-control custom-checkbox col">
						<input type="checkbox" name="production[machine_number][]" class="custom-control-input" id="machine-6" value="6">
						<label class="custom-control-label" for="machine-6">6</label>
					</div>
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

		$('#amount').val(moneyStr(multiplyTwoNums(qty, price)));

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
				$('#spec-form').attr('action', `${window.location.origin}/action/produksi_action/atur_bordir`);
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

	$('#embro-production').on('click', '.update-machine-trigger', function (event) {

		const selectedRow = $(this).parents('tr');
		const machineNum = selectedRow.data('machine');
		const laborPrice = selectedRow.data('labor-price');
		const productionId = selectedRow.data('production-id');

		$('#editMachineModal #production-id').val(productionId);
		$('#editMachineModal #machine-number').val(machineNum);
		$('#editMachineModal #machine-price').val(laborPrice);

	});

	$('#embro-production').on('click', '.del-machine-trigger', function (event) {

		const selectedRow = $(this).parents('tr');
		const productionId = selectedRow.data('production-id');

		$('#deleteMachineModal #modal-production-id').val(productionId);

	});

	const outputModal = $('#output-modal');

	$('#new-output-trigger').click(function (event) {
		// Reset previous value
		$('#output-form')[0].reset();

		// Set Modal Title
		outputModal.find('.modal-title').html('Catat Output');
	});

	$('#output-history').on('click', '.update-output-trigger', function (event) {
		const outputId = $(this).parents('tr').data('output-id');
		const outputQty = $(this).parents('tr').data('output-quantity');
		const outputOperator = $(this).parents('tr').data('output-operator');
		const outputShift = $(this).parents('tr').data('output-shift');
		const outputMachine = $(this).parents('tr').data('output-machine');
		const outputStarted = $(this).parents('tr').data('output-started');
		const outputFinished = $(this).parents('tr').data('output-finished');
		const outputIsHelper = $(this).parents('tr').data('output-helper');

		outputModal.find('.modal-title').html('Sunting Output');
		outputModal.find('#output-embro-id').val(outputId);
		outputModal.find('#modal-shift').val(outputShift);
		outputModal.find('#modal-machine').val(outputMachine);
		outputModal.find('#modal-started').val(outputStarted);
		outputModal.find('#modal-finished').val(outputFinished);
		outputModal.find('#modal-output-qty').val(outputQty);
		outputModal.find('#modal-operator').val(outputOperator);

		if (outputIsHelper == 1) {
			outputModal.find('#modal-is-helper').prop('checked', true);
		}

	})

	// Output money format on keyup
	$('#quantity').keyup(numberFormat);
	$('#price').keyup(numberFormat);

	// Enable tooltip
	$('#design-progress-bar').tooltip();
	$('#embro-progress-bar').tooltip();
	$('#finishing-progress-bar').tooltip();

});
