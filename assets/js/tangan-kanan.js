/*
|--------------------------------------------------------------------------
| Function Helpers
|--------------------------------------------------------------------------
|
| Common helper for simple task. These helpers are not tied to specific
| page, rather they are applied to all page in the application. 
|
*/

function moneyStr(num) {
	return num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
}

function moneyInt(num) {
	return parseInt(num.replace(/,/g, ''));
}

function outputValTo(val, element) {
	element.val(moneyStr(val));
}

function numberFormat() {

	preventNaN($(this));
	outputValTo(moneyInt($(this).val()), $(this));

}

function preventNaN(input) {
	if (input.val() == '') {
		input.val('0');
	}
}

function sendAjax(url, postData) {

	return $.ajax({
		type: "POST",
		url: url,
		data: postData
	});

}

function initSelect2(selectElement) {
	selectElement.select2()
}

function multiplyTwoNums(num1, num2) {

	let result = num1 * num2;
	return result

}

function substractTwoNums(num1, num2) {

	let result = num1 - num2;
	return result;

}

function outputOptions(optJSON, placeholder) {

	let options = `<option value="">${placeholder}</option>`;

	let parsedData = JSON.parse(optJSON);
	$.each(parsedData, function (name, value) {
		options += `<option value="${value.id}">${value.text}</option>`;
	});

	return options;

}

function quantityID(qty) {

	switch (true) {
		case qty <= 24:
			quantity_id = 1;
			break;
		case qty <= 100:
			quantity_id = 2;
			break;
		case qty <= 200:
			quantity_id = 3;
			break;
		case qty <= 500:
			quantity_id = 4;
			break;
		case qty <= 1000:
			quantity_id = 5;
			break;
		case qty >= 1000:
			quantity_id = 6;
			break;
	}

	return quantity_id;

}

// Show name of the selected file
$('input[type="file"]').on('change', function (e) {
	// get the file name
	let fileName = $(this).val().replace('C:\\fakepath\\', " ");
	//replace the "Choose a file" label
	$(this).next('.custom-file-label').html(fileName);
});
