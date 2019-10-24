<!-- Begin Page Content -->
<div class="container-fluid">

  <!-- Page Heading -->
  <div class="d-flex justify-content-between align-items-center mb-3">

    <h1 class="h3 text-gray-800"><?= $title; ?></h1>

    <?php

    $receivable = 0;

    foreach ($invoices as $invoice) {
      $receivable += $invoice['payment_due'];
    }

    ?>

    <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm">
      Piutang: Rp<?= moneyStrDot($receivable) ?>,00
    </a>

  </div>

  <!-- Orders Table -->
  <div class="card shadow mb-4" id="invoice-index-card">
    <div class="card-body">
      <table class="table table-hover" id="dataTable">
        <thead class="thead-light">
          <tr>
            <th scope="col">Invoice</th>
            <th scope="col">Pelanggan</th>
            <th scope="col">Jatuh Tempo</th>
            <th scope="col">Tagihan</th>
            <th scope="col">Status</th>
            <th scope="col">Pengerjaan</th>
            <th scope="col">Penerbitan</th>
            <th scope="col">#</th>
          </tr>
        </thead>
        <tbody style="font-size:14px">
          <?php foreach ($invoices as $invoice) : ?>
            <tr data-invoice-id="<?= $invoice['invoice_id']; ?>">
              <td data-sort="<?= $invoice['number']; ?>">
                <a style="color:#858796" href="<?= base_url('invoice/sunting/') . $invoice['number']; ?>">INV-<?= $invoice['number']; ?></a>
              </td>
              <td>
                <?= $invoice['customer']; ?>
              </td>
              <td data-sort="<?= strtotime($invoice['payment_date']); ?>">
                <?= date('d/m/Y', strtotime($invoice['payment_date'])); ?>
              </td>
              <td>
                <?= moneyStrDot($invoice['payment_due']); ?>,00
              </td>
              <td>
                <?= $this->invoice_model->check_payment_status($invoice['invoice_id'])['payment_status']; ?>
              </td>
              <td>
                <?= $this->pesanan_model->check_order_progress($invoice['invoice_id']) ?>
              </td>
              <td data-sort="<?= strtotime($invoice['invoice_date']); ?>">
                <?= date('d/m/Y', strtotime($invoice['invoice_date'])); ?>
              </td>
              <td>
                <a class=" dropdown-toggle text-right" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown">
                  <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
                </a>
                <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in">
                  <a href="<?= base_url('invoice/sunting/') . $invoice['number']; ?>" class="dropdown-item">
                    Sunting Invoice
                  </a>
                  <a href="#" data-toggle="modal" data-target="#del-invoice-modal" class="dropdown-item del-modal-trigger">
                    Hapus Invoice
                  </a>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>


</div>
<!-- /.container-fluid -->

<!-- Delete Invoice Modal-->
<div class="modal fade" id="del-invoice-modal" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <form action="<?= base_url('processor/invoice_pcsr/hapus_invoice'); ?>" method="post" id="delete-invoice-form">
      <input type="hidden" name="invoice[invoice_id]" id="modal-invoice-id" value="">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Yakin akan menghapus?</h5>
          <button class="close" type="button" data-dismiss="modal">
            <span aria-hidden="true">×</span>
          </button>
        </div>
        <div class="modal-body">Klik "Hapus" jika Anda yakin untuk menghapus Invoice ini.</div>
        <div class="modal-footer">
          <button class="btn btn-secondary" type="button" data-dismiss="modal">Batal</button>
          <button type="submit" class="btn btn-primary" id="delInvoiceBtn">Hapus</button>
        </div>
      </div>
    </form>
  </div>
</div>

<script>
  const invoiceIndexCard = document.querySelector('#invoice-index-card');

  invoiceIndexCard.addEventListener('click', (e) => {

    let clickedEl = e.target;

    if (clickedEl.matches('.del-modal-trigger')) {

      // Grab invoice-id value from invoice-id data attribute of the respective row
      let currentRow = clickedEl.closest('tr');
      let invoiceId = currentRow.dataset.invoiceId;

      // Assign invoice-id value to the invoice-id hidden input in the mark-as-finished-modal
      document.querySelector('#modal-invoice-id').value = invoiceId

    }

  });
</script>