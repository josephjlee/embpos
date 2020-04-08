// Gather all required data from invoice-editor dom
const invoiceFileName = $('#invoice-preview').data('invoice-filename');
const customerName = $('#invoice-preview').data('customer-name');
const customerAffiliation = $('#invoice-preview').data('customer-affiliation');
const subTotal = $('#invoice-preview').data('subtotal');
const discount = $('#invoice-preview').data('discount');
const totalDue = $('#invoice-preview').data('total-due');
const paid = $('#invoice-preview').data('paid');
const paymentDue = $('#invoice-preview').data('payment-due');
const invoiceNote = $('#invoice-preview').data('invoice-note');
const invoiceDate = $('#invoice-preview').data('invoice-date');
const paymentDate = $('#invoice-preview').data('payment-date');
const invoiceNumber = $('#invoice-preview').data('invoice-number');
const invoiceId = $('#invoice-preview').data('invoice-id');

$('#export-pdf').click(function (event) {

  // Prevent hyperlink's default behaviour
  event.preventDefault();

  // Request orders
  orderReq = sendAjax(
    `${window.location.origin}/ajax/pesanan_ajax/list_order_for_invoice_pdf`,
    { invoice_id: invoiceId }
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

    pdfMake.createPdf(dd).download(`${invoiceFileName}.pdf`);

  });

});

$('#export-png').click(function (event) {

  event.preventDefault();

  function saveAs(uri, filename) {

    var link = document.createElement('a');

    if (typeof link.download === 'string') {

      link.href = uri;
      link.download = filename;

      //Firefox requires the link to be in the body
      document.body.appendChild(link);

      //simulate click
      link.click();

      //remove the link when done
      document.body.removeChild(link);

    } else {

      window.open(uri);

    }
  }

  html2canvas(document.querySelector("#capture")).then(canvas => {

    saveAs(canvas.toDataURL(), `${invoiceFileName}.png`);

  });

});