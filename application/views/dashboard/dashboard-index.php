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
              <div class="h5 mb-0 font-weight-bold text-gray-800">R<?= moneyStrDot($data_card['total_receivable']); ?></div>
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

    <!-- Area Chart -->
    <div class="col-xl-8 col-lg-7">
      <div class="card shadow mb-4">
        <!-- Card Header - Dropdown -->
        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
          <h6 class="m-0 font-weight-bold text-primary">Tren Pemasukan Invoice</h6>
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

    <!-- Pie Chart -->
    <div class="col-xl-4 col-lg-5">
      <div class="card shadow mb-4">
        <!-- Card Header - Dropdown -->
        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
          <h6 class="m-0 font-weight-bold text-primary">Metode Pembayaran</h6>
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
              <i class="fas fa-circle text-primary"></i> Direct
            </span>
            <span class="mr-2">
              <i class="fas fa-circle text-success"></i> Social
            </span>
            <span class="mr-2">
              <i class="fas fa-circle text-info"></i> Referral
            </span>
          </div>
        </div>
      </div>
    </div>

  </div>

  <!-- Debitor, Order, dll Row -->
  <div class="row mb-4">

    <!-- Receivable Invoice -->
    <div class="col-lg-4">

      <div class="card shadow">
        <div class="card-header py-3">
          <h6 class="m-0 font-weight-bold text-primary">Piutang Invoice</h6>
        </div>
        <div class="card-body">
          <table class="table">
            <tbody>
              <tr>
                <td class="px-0">INV-2163</td>
                <td class="text-right px-0">
                  <span class="badge badge-default">Rp5.750.000</span>
                </td>
              </tr>
              <tr>
                <td class="px-0">INV-2271</td>
                <td class="text-right px-0">
                  <span class="badge badge-success">Rp5.275.000</span>
                </td>
              </tr>
              <tr>
                <td class="px-0">INV-2200</td>
                <td class="text-right px-0">
                  <span class="badge badge-danger">Rp4.500.000</span>
                </td>
              </tr>
              <tr>
                <td class="px-0">INV-1924</td>
                <td class="text-right px-0">
                  <span class="badge badge-default">Rp4.000.000</span>
                </td>
              </tr>
              <tr>
                <td class="px-0">INV-2345</td>
                <td class="text-right px-0">
                  <span class="badge badge-default">Rp3.395.000</span>
                </td>
              </tr>
              <tr>
                <td class="px-0">INV-2500</td>
                <td class="text-right px-0">
                  <span class="badge badge-warning">Rp2.119.000</span>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>

    </div>

    <!-- Debt List -->
    <div class="col-lg-4">

      <div class="card shadow">
        <div class="card-header py-3">
          <h6 class="m-0 font-weight-bold text-primary">Daftar Hutang</h6>
        </div>
        <div class="card-body">
          <table class="table">
            <tbody>
              <tr>
                <td class="px-0">Token Mesin</td>
                <td class="text-right px-0">
                  <span class="badge badge-default">Rp202.000</span>
                </td>
              </tr>
              <tr>
                <td class="px-0">Benang TOP Hitam 1lsn</td>
                <td class="text-right px-0">
                  <span class="badge badge-success">Rp170.000</span>
                </td>
              </tr>
              <tr>
                <td class="px-0">Kain Keras 60G</td>
                <td class="text-right px-0">
                  <span class="badge badge-danger">Rp107.000</span>
                </td>
              </tr>
              <tr>
                <td class="px-0">Spul 2kg</td>
                <td class="text-right px-0">
                  <span class="badge badge-default">Rp90.000</span>
                </td>
              </tr>
              <tr>
                <td class="px-0">Token Lampu</td>
                <td class="text-right px-0">
                  <span class="badge badge-default">Rp202.000</span>
                </td>
              </tr>
              <tr>
                <td class="px-0">Roti & Susu</td>
                <td class="text-right px-0">
                  <span class="badge badge-warning">Rp150.000</span>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>

    </div>

    <!-- Order Deadline -->
    <div class="col-lg-4">

      <div class="card shadow">
        <div class="card-header py-3">
          <h6 class="m-0 font-weight-bold text-primary">Pesanan Mendekati Deadline</h6>
        </div>
        <div class="card-body">
          <table class="table">
            <tbody>
              <tr>
                <td class="px-0">PSN-202</td>
                <td class="text-right px-0">
                  <span class="badge badge-default">02 Desember 2019</span>
                </td>
              </tr>
              <tr>
                <td class="px-0">PSN-212</td>
                <td class="text-right px-0">
                  <span class="badge badge-default">04 Desember 2019</span>
                </td>
              </tr>
              <tr>
                <td class="px-0">PSN-222</td>
                <td class="text-right px-0">
                  <span class="badge badge-default">04 Desember 2019</span>
                </td>
              </tr>
              <tr>
                <td class="px-0">PSN-207</td>
                <td class="text-right px-0">
                  <span class="badge badge-default">06 Desember 2019</span>
                </td>
              </tr>
              <tr>
                <td class="px-0">PSN-252</td>
                <td class="text-right px-0">
                  <span class="badge badge-default">07 Desember 2019</span>
                </td>
              </tr>
              <tr>
                <td class="px-0">PSN-241</td>
                <td class="text-right px-0">
                  <span class="badge badge-default">07 Desember 2019</span>
                </td>
              </tr>
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
      <div class="card shadow">
        <div class="card-header py-3">
          <h6 class="m-0 font-weight-bold text-primary">Cicilan</h6>
        </div>
        <div class="card-body" style="padding-top:2rem!important;padding-bottom:2rem!important">
          <h4 class="small font-weight-bold">BRI <span class="float-right">20%</span></h4>
          <div class="progress mb-4">
            <div class="progress-bar bg-danger" role="progressbar" style="width: 20%" aria-valuenow="20" aria-valuemin="0" aria-valuemax="100"></div>
          </div>
          <h4 class="small font-weight-bold">Koperasi <span class="float-right">40%</span></h4>
          <div class="progress mb-4">
            <div class="progress-bar bg-warning" role="progressbar" style="width: 40%" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100"></div>
          </div>
          <h4 class="small font-weight-bold">Lain-lain <span class="float-right">60%</span></h4>
          <div class="progress mb-4">
            <div class="progress-bar" role="progressbar" style="width: 60%" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100"></div>
          </div>
          <h4 class="small font-weight-bold">Mobil <span class="float-right">80%</span></h4>
          <div class="progress mb-4">
            <div class="progress-bar bg-info" role="progressbar" style="width: 80%" aria-valuenow="80" aria-valuemin="0" aria-valuemax="100"></div>
          </div>
          <h4 class="small font-weight-bold">Bahan Baku <span class="float-right">Complete!</span></h4>
          <div class="progress">
            <div class="progress-bar bg-success" role="progressbar" style="width: 100%" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100"></div>
          </div>
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