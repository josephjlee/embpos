<!-- Begin Page Content -->
<div class="container-fluid">

  <!-- Page Heading -->
  <h1 class="h3 mb-3 text-gray-800"><?= $title; ?></h1>

  <!-- embro List Table -->
  <div class="card shadow mb-4">
    <div class="card-body" id="production-table-card">
      <table class="table table-hover" id="embroLogTable">
        <thead class="thead-light">
          <tr>
            <th>Pesanan</th>
            <th>Operator</th>
            <th>Mesin</th>
            <th>Shift</th>
            <th>Mulai</th>
            <th>Sampai</th>
            <th class="text-right">Output</th>
            <th class="text-right">Harga</th>
            <th class="text-right">Nilai</th>
            <th>#</th>
          </tr>
        </thead>
        <tfoot>
          <tr>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            <th colspan="2">
              <div class="text-right">Sub Total:</div>
              <div class="text-right">Total:</div>
            </th>
            <th></th>
            <th></th>
          </tr>
        </tfoot>
      </table>
    </div>
  </div>

</div>