$('document').ready(function () {

  // Turn operatorSelect into Selectize
  $select = $('#modal-operator').selectize();
  var selectize = $select[0].selectize;

  const outputModal = $('#output-modal');

  // Enable tooltip
  $('#embro-progress-bar').tooltip();

  // Initialize Flatpickr
  flatpickr(".date-time-picker", {
    enableTime: true,
    dateFormat: "Y-m-d H:i",
  });

  $('#new-output-trigger').click(function (event) {
    // Reset previous value
    $('#output-form')[0].reset();
    selectize.clear();

    // Set Modal Title
    outputModal.find('.modal-title').html('Catat Output');
  });

  $('#output-history').on('click', '.update-output-trigger', function (event) {
    const outputId = $(this).parents('tr').data('output-id');
    const outputQty = $(this).parents('tr').data('output-quantity');
    const outputOperator = $(this).parents('tr').data('output-operator');
    const outputShift = $(this).parents('tr').data('output-shift');
    const outputStarted = $(this).parents('tr').data('output-started');
    const outputFinished = $(this).parents('tr').data('output-finished');

    outputModal.find('.modal-title').html('Sunting Output');
    outputModal.find('#output-embro-id').val(outputId);
    outputModal.find('#modal-shift').val(outputShift);
    outputModal.find('#modal-started').val(outputStarted);
    outputModal.find('#modal-finished').val(outputFinished);
    outputModal.find('#modal-output-qty').val(outputQty);

    selectize.setValue(outputOperator, false);
  })

})
