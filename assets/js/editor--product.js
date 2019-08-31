$('document').ready(function () {

	// Turn itemSelect into Select2
  initSelect2( $('#item') );

  // Output money format on keyup
  $('#base-price').keyup(numberFormat);
  $('#sell-price').keyup(numberFormat);
  $('#stock').keyup(numberFormat);
  
})
