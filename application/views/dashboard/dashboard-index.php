<div class="container-fluid">

  <!-- Page Heading -->
  <div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Dashboard</h1>
    <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i class="fas fa-download fa-sm text-white-50"></i> Generate Report</a>
  </div>

  <!-- Content Row -->
  <div class="row">

    <!-- Rata-Rata Omzet Invoice -->
    <div class="col-xl-3 col-md-6 mb-4">
      <div class="card border-left-primary shadow h-100 py-2">
        <div class="card-body">
          <div class="row no-gutters align-items-center">
            <div class="col mr-2">
              <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Rata-Rata Nilai Invoice</div>
              <div class="h5 mb-0 font-weight-bold text-gray-800">Rp<?= moneyStrDot($data_card['monthly_invoice_rev_avg']); ?>/bln</div>
            </div>
            <div class="col-auto">
              <i class="fas fa-calendar fa-2x text-gray-300"></i>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Rata-rata pesanan -->
    <div class="col-xl-3 col-md-6 mb-4">
      <div class="card border-left-success shadow h-100 py-2">
        <div class="card-body">
          <div class="row no-gutters align-items-center">
            <div class="col mr-2">
              <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Rata-rata pesanan</div>
              <div class="h5 mb-0 font-weight-bold text-gray-800"><?= moneyStrDot($data_card['monthly_order_qty_avg']); ?>pcs/bln</div>
            </div>
            <div class="col-auto">
              <i class="fas fa-box-open fa-2x text-gray-300"></i>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Rata-rata penjualan produk -->
    <div class="col-xl-3 col-md-6 mb-4">
      <div class="card border-left-info shadow h-100 py-2">
        <div class="card-body">
          <div class="row no-gutters align-items-center">
            <div class="col mr-2">
              <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Rata-rata penjualan</div>
              <div class="h5 mb-0 mr-3 font-weight-bold text-gray-800"><?= moneyStrDot($data_card['monthly_product_sale_avg']); ?>pcs/bln</div>
            </div>
            <div class="col-auto">
              <i class="fas fa-gift fa-2x text-gray-300"></i>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Total Piutang -->
    <div class="col-xl-3 col-md-6 mb-4">
      <div class="card border-left-warning shadow h-100 py-2">
        <div class="card-body">
          <div class="row no-gutters align-items-center">
            <div class="col mr-2">
              <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Total Piutang</div>
              <div class="h5 mb-0 font-weight-bold text-gray-800">Rp<?= moneyStrDot($data_card['total_receivable']); ?></div>
            </div>
            <div class="col-auto">
              <i class="fas fa-comment-dollar fa-2x text-gray-300"></i>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Grafik Invoice, Grafik Metode Pembayaran -->

  <div class="row">

    <!-- Payment Chart -->
    <div class="col-xl-8 col-lg-7">
      <div class="card shadow mb-4">
        <!-- Card Header - Dropdown -->
        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
          <h6 class="m-0 font-weight-bold text-primary">Grafik Pembayaran</h6>
          <div class="dropdown no-arrow">
            <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
              <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
            </a>
            <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in" aria-labelledby="dropdownMenuLink">
              <div class="dropdown-header">Dropdown Header:</div>
              <a class="dropdown-item" href="#">Action</a>
              <a class="dropdown-item" href="#">Another action</a>
              <div class="dropdown-divider"></div>
              <a class="dropdown-item" href="#">Something else here</a>
            </div>
          </div>
        </div>
        <!-- Card Body -->
        <div class="card-body" style="padding-top:1.85rem!important;padding-bottom:1.85rem!important">
          <div class="chart-area">
            <div class="chartjs-size-monitor">
              <div class="chartjs-size-monitor-expand">
                <div class=""></div>
              </div>
              <div class="chartjs-size-monitor-shrink">
                <div class=""></div>
              </div>
            </div>
            <canvas id="myAreaChart" style="display: block; width: 670px; height: 320px;" width="670" height="320" class="chartjs-render-monitor"></canvas>
          </div>
        </div>
      </div>
    </div>

    <!-- Payment per method Chart -->
    <div class="col-xl-4 col-lg-5">
      <div class="card shadow mb-4">
        <!-- Card Header - Dropdown -->
        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
          <h6 class="m-0 font-weight-bold text-primary">Pembayaran per Metode</h6>
          <div class="dropdown no-arrow">
            <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
              <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
            </a>
            <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in" aria-labelledby="dropdownMenuLink">
              <div class="dropdown-header">Dropdown Header:</div>
              <a class="dropdown-item" href="#">Action</a>
              <a class="dropdown-item" href="#">Another action</a>
              <div class="dropdown-divider"></div>
              <a class="dropdown-item" href="#">Something else here</a>
            </div>
          </div>
        </div>
        <!-- Card Body -->
        <div class="card-body">
          <div class="chart-pie pt-4 pb-2">
            <div class="chartjs-size-monitor">
              <div class="chartjs-size-monitor-expand">
                <div class=""></div>
              </div>
              <div class="chartjs-size-monitor-shrink">
                <div class=""></div>
              </div>
            </div>
            <canvas id="myPieChart" style="display: block; width: 302px; height: 245px;" width="302" height="245" class="chartjs-render-monitor"></canvas>
          </div>
          <div class="mt-4 text-center small">
            <span class="mr-2">
              <i class="fas fa-circle text-primary"></i> Bayar Tunai
            </span>
            <span class="mr-2">
              <i class="fas fa-circle text-success"></i> Giro Maybank
            </span>
            <br>
            <span class="mr-2">
              <i class="fas fa-circle text-info"></i> BCA
            </span>
            <span class="mr-2">
              <i class="fas fa-circle" style="color:#e5e5e5"></i> Mandiri
            </span>
          </div>
        </div>
      </div>
    </div>

  </div>

  <!-- Debitor, Order, dll Row -->
  <div class="row mb-4 three-table-card">

    <!-- Receivable Invoice -->
    <div class="col-lg-4">

      <div class="card shadow h-100">
        <div class="card-header py-3">
          <h6 class="m-0 font-weight-bold text-primary">Piutang Invoice</h6>
        </div>
        <div class="card-body">
          <table class="table" id="unpaid-invoice-table">
            <thead class="thead-light d-none">
              <tr>
                <th scope="col">Invoice</th>
                <th scope="col">Piutang</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($unpaid_invoices as $unpaid_invoice) : ?>
                <tr>
                  <td class="px-0"><a href="<?= base_url('invoice/sunting/') . $unpaid_invoice['INV']; ?>">INV-<?= $unpaid_invoice['INV'] ?> (<?= $unpaid_invoice['name'] ?>)</a></td>
                  <td class="text-right px-0">
                    <span class="badge badge-<?= moneyBadge($unpaid_invoice['amount']); ?>">Rp<?= moneyStrDot($unpaid_invoice['amount']); ?></span>
                  </td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      </div>

    </div>

    <!-- Debt List -->
    <div class="col-lg-4">

      <div class="card shadow h-100">
        <div class="card-header py-3">
          <h6 class="m-0 font-weight-bold text-primary">Daftar Hutang</h6>
        </div>
        <div class="card-body">
          <table class="table" id="unpaid-debt-table">
            <thead class="thead-light d-none">
              <tr>
                <th scope="col">Item</th>
                <th scope="col">Amount</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($unpaid_debts as $debt) : ?>
                <tr>
                  <td class="px-0"><?= $debt['description']; ?></td>
                  <td class="text-right px-0">
                    <span class="badge badge-<?= moneyBadge($debt['due']); ?>">Rp<?= moneyStrDot($debt['due']); ?></span>
                  </td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      </div>

    </div>

    <!-- Order Deadline -->
    <div class="col-lg-4">

      <div class="card shadow h-100">
        <div class="card-header py-3">
          <h6 class="m-0 font-weight-bold text-primary">Pesanan Mendekati Deadline</h6>
        </div>
        <div class="card-body">
          <table class="table" id="order-deadline-table">
            <thead class="thead-light d-none">
              <tr>
                <th scope="col">Pesanan</th>
                <th scope="col">Deadline</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($near_deadline_orders as $near_deadline) : ?>
                <tr>
                  <td class="px-0"><a href="<?= base_url('pesanan/sunting/') . $near_deadline['order_id']; ?>"><?= $near_deadline['description']; ?></a></td>
                  <td class="text-right px-0">
                    <span class="badge badge-<?= deadlineBadge($near_deadline['countdown']); ?>"><?= $near_deadline['countdown'] < 0 ? 'Lewat ' . abs($near_deadline['countdown']) . ' hari' : 'Kurang ' . abs($near_deadline['countdown']) . ' hari'; ?></span>
                  </td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      </div>

    </div>

  </div>

  <!-- Debt & Expense Row -->
  <div class="row mb-4">

    <!-- Debt Progress -->
    <div class="col-lg-6">
      <div class="card shadow h-100">

        <div class="card-header py-3">
          <h6 class="m-0 font-weight-bold text-primary">Cicilan</h6>
        </div>

        <div class="card-body" style="padding-top:2rem!important;padding-bottom:2rem!important">

          <?php if ($unpaid_payables['top_four']) : ?>

            <?php foreach ($unpaid_payables['top_four'] as $payable) : ?>
              <?php $progress = round(($payable['total_paid'] / $payable['debt_value']) * 100); ?>
              <?php $formatted_total_paid = moneyStrDot($payable['total_paid']); ?>
              <?php $formatted_debt_value = moneyStrDot($payable['debt_value']); ?>
              <h4 class="small font-weight-bold"><?= $payable['creditor_name']; ?> <span class="float-right"><?= $progress; ?>%</span></h4>
              <div class="progress mb-4">
                <div class="progress-bar bg-danger" role="progressbar" data-toggle="tooltip" data-placement="top" title="<?= "Rp{$formatted_total_paid} dari Rp{$formatted_debt_value}"; ?>" <?= "style='width: {$progress}%'" ?>></div>
              </div>
            <?php endforeach; ?>

            <?php if ($unpaid_payables['the_rest']['debt_value'] != 0) : ?>

              <?php $the_rest_progress = round(($unpaid_payables['the_rest']['total_paid'] / $unpaid_payables['the_rest']['debt_value']) * 100); ?>
              <?php $formatted_total_paid2 = moneyStrDot($unpaid_payables['the_rest']['total_paid']); ?>
              <?php $formatted_debt_value2 = moneyStrDot($unpaid_payables['the_rest']['debt_value']); ?>

              <h4 class="small font-weight-bold">Lain-lain <span class="float-right"><?= $the_rest_progress; ?>%</span></h4>
              <div class="progress">
                <div class="progress-bar bg-danger" role="progressbar" data-toggle="tooltip" data-placement="top" title="<?= "Rp{$formatted_total_paid2} dari Rp{$formatted_debt_value2}"; ?>" <?= "style='width: {$the_rest_progress}%'" ?>></div>
              </div>

            <?php endif; ?>

          <?php else : ?>

            <div class="d-flex flex-column justify-content-center align-items-center h-100">
              <div class="mb-4 text-primary"><i class="fas fa-thumbs-up fa-5x"></i></div>
              <h4 class="text-primary">Anda bersih dari cicilan</h4>
            </div>

          <?php endif; ?>

        </div>

      </div>
    </div>

    <!-- Expense Chart -->
    <div class="col-lg-6">
      <div class="card shadow">
        <!-- Card Header - Dropdown -->
        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
          <h6 class="m-0 font-weight-bold text-primary">Pengeluaran</h6>
          <div class="dropdown no-arrow">
            <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
              <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
            </a>
            <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in" aria-labelledby="dropdownMenuLink">
              <div class="dropdown-header">Dropdown Header:</div>
              <a class="dropdown-item" href="#">Action</a>
              <a class="dropdown-item" href="#">Another action</a>
              <div class="dropdown-divider"></div>
              <a class="dropdown-item" href="#">Something else here</a>
            </div>
          </div>
        </div>
        <!-- Card Body -->
        <div class="card-body">
          <div class="chart-pie pt-4 pb-2">
            <div class="chartjs-size-monitor">
              <div class="chartjs-size-monitor-expand">
                <div class=""></div>
              </div>
              <div class="chartjs-size-monitor-shrink">
                <div class=""></div>
              </div>
            </div>
            <canvas id="expenseChart" style="display: block; width: 302px; height: 245px;" width="302" height="245" class="chartjs-render-monitor"></canvas>
          </div>
          <div class="mt-4 text-center small">
            <span class="mr-2">
              <i class="fas fa-circle text-primary"></i> Gaji
            </span>
            <span class="mr-2">
              <i class="fas fa-circle text-success"></i> Bayar Hutang
            </span>
            <span class="mr-2">
              <i class="fas fa-circle text-info"></i> Operasional
            </span>
          </div>
        </div>
      </div>
    </div>

  </div>

</div>