<!-- Begin Page Content -->
<div class="container-fluid">

  <!-- Page Heading -->
  <div class=" row mb-2">
    <div class="col d-flex justify-content-between align-items-center">
      <h1 class="h3 text-gray-800 mr-auto"><?= $title; ?></h1>
      <a href="<?= base_url('keuangan/buat_invoice') ?>" class="badge badge-primary py-2 px-3 text-uppercase shadow">Buat Invoice</a>
    </div>
  </div>

  <div class="row">

    <!-- This month Gross Revenue -->

    <?php $total_invoice = $this->invoice_model->get_total_invoice_by_month(date('m')); ?>

    <div class="col-xl-3 col-md-6 mb-4">
      <div class="card border-left-primary shadow h-100 py-2">
        <div class="card-body">
          <div class="row no-gutters align-items-center">
            <div class="col mr-2">
              <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Total Invoice (<?= date('M') ?>)</div>
              <div class="h5 mb-0 font-weight-bold text-gray-800">Rp<?= moneyStrDot($total_invoice); ?></div>
            </div>
            <div class="col-auto">
              <i class="fas fa-file-invoice-dollar fa-2x text-gray-300"></i>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Total paid -->

    <?php $total_paid = $this->invoice_model->get_total_paid_by_month(date('m')); ?>

    <div class="col-xl-3 col-md-6 mb-4">
      <div class="card border-left-success shadow h-100 py-2">
        <div class="card-body">
          <div class="row no-gutters align-items-center">
            <div class="col mr-2">
              <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Total Terbayar (<?= date('M') ?>)</div>
              <div class="h5 mb-0 font-weight-bold text-gray-800">Rp<?= moneyStrDot($total_paid); ?></div>
            </div>
            <div class="col-auto">
              <i class="fas fa-hand-holding-usd fa-2x text-gray-300"></i>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Receivable Card -->

    <?php $total_receivable = $total_invoice - $total_paid; ?>

    <div class="col-xl-3 col-md-6 mb-4">
      <div class="card border-left-info shadow h-100 py-2">
        <div class="card-body">
          <div class="row no-gutters align-items-center">
            <div class="col mr-2">
              <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Total Piutang (<?= date('M') ?>)</div>
              <div class="h5 mb-0 font-weight-bold text-gray-800">Rp<?= moneyStrDot($total_receivable); ?></div>
            </div>
            <div class="col-auto">
              <i class="fas fa-comment-dollar fa-2x text-gray-300"></i>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Active Order -->
    <div class="col-xl-3 col-md-6 mb-4">
      <div class="card border-left-warning shadow h-100 py-2">
        <div class="card-body">
          <div class="row no-gutters align-items-center">
            <div class="col mr-2">
              <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Pengerjaan Aktif (<?= date('M') ?>)</div>
              <div class="h5 mb-0 font-weight-bold text-gray-800"><span id="active-inv"></span> invoice</div>
            </div>
            <div class="col-auto">
              <i class="fas fa-cog fa-2x text-gray-300"></i>
            </div>
          </div>
        </div>
      </div>
    </div>
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
                <a style="color:#858796" href="<?= base_url('keuangan/sunting_invoice/') . $invoice['number']; ?>">INV-<?= $invoice['number']; ?></a>
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
              <td <?= date('m', strtotime($invoice['invoice_date'])) == date('m') ? 'class="invoice-status-col"' : ''; ?>>
                <?= $this->pesanan_model->check_order_progress($invoice['invoice_id']); ?>
              </td>
              <td data-sort="<?= strtotime($invoice['invoice_date']); ?>">
                <?= date('d/m/Y', strtotime($invoice['invoice_date'])); ?>
              </td>
              <td>
                <a class=" dropdown-toggle text-right" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown">
                  <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
                </a>
                <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in">
                  <a href="<?= base_url('keuangan/sunting_invoice/') . $invoice['number']; ?>" class="dropdown-item">
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
  const invStatusNodeList = document.querySelectorAll('.invoice-status-col');
  const invStatusArray = Array.from(invStatusNodeList);

  let totalActiveInv = 0;
  for (let index = 0; index < invStatusArray.length; index++) {
    if (invStatusArray[index].innerText == 'Tuntas') continue;
    totalActiveInv++
  }

  document.querySelector('#active-inv').innerHTML = totalActiveInv;

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