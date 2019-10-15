<!-- Begin Page Content -->
<div class="container-fluid">

  <!-- Page Heading -->
  <h1 class="h3 mb-3 text-gray-800"><?= $title . ' ' . date('d/m', strtotime($period['start'])) . ' - ' . date('d/m', strtotime($period['end'])); ?> </h1>

  <!-- Finishing List Table -->
  <div class="row">

    <div class="col-8">

      <div class="card shadow mb-4">
        <div class="card-body" id="production-output-table-card">
          <table class="table table-hover" id="dataTable">
            <thead class="thead-light">
              <tr>
                <th class="text-center">No.</th>
                <th>Nama</th>
                <th class="text-right">Output</th>
                <th class="text-right">Nilai</th>
                <th class="text-center">#</th>
              </tr>
            </thead>
            <tbody style="font-size:14px">
              <?php $i = 1; ?>
              <?php foreach ($output_list as $output) : ?>
                <tr>
                  <td class="text-center">
                    <?= $i; ?>
                  </td>
                  <td>
                    <?= $output['name']; ?>
                  </td>
                  <td class="text-right">
                    <?= moneyStrDot($output['quantity']); ?>
                  </td>
                  <td class="text-right">
                    <?= moneyStrDot($output['value']); ?>,00
                  </td>
                  <td class="text-center">
                    <a class="dropdown-toggle text-right" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown">
                      <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
                    </a>
                  </td>
                </tr>
                <?php $i++; ?>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      </div>

    </div>

    <div class="col-4">

      <div class="card shadow mb-3">

        <!-- Card Header - Accordion -->
        <a href="#perodCard" class="d-block card-header py-3" data-toggle="collapse" role="button">
          <h6 class="m-0 font-weight-bold text-primary">Periode</h6>
        </a>

        <!-- Card Content - Collapse -->
        <div class="collapse show" id="perodCard">

          <form action="<?= base_url('produksi/output'); ?>" method="post">

            <div class="card-body">

              <div class="input-group mb-2">
                <div class="input-group-prepend">
                  <div class="input-group-text"><i class="fas fa-hourglass-start fa-fw"></i></div>
                </div>
                <input type="date" name="period[start]" id="" class="form-control">
              </div>

              <div class="input-group mb-2">
                <div class="input-group-prepend">
                  <div class="input-group-text"><i class="fas fa-hourglass-end fa-fw"></i></div>
                </div>
                <input type="date" name="period[end]" id="" class="form-control">
              </div>

              <button type="submit" class="btn btn-primary btn-sm w-100 font-weight-bold">CETAK</button>

            </div>

          </form>

        </div>

      </div>

    </div>

  </div>

</div>