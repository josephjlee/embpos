<!-- Update Customer Modal -->
<div class="modal fade" id="updateCustomerModal" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">

    <form action="<?= base_url('invoice/ganti_pelanggan')?>" method="post" id="update-customer-form">

      <input type="hidden" name="order-id" id="order-id" value="<?= $invoice_detail['order_id']; ?>">
      <input type="hidden" name="order-number" id="order-number" value="<?= $invoice_detail['invoice_number']; ?>">

      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Pelanggan</h5>
          <button type="button" class="close" data-dismiss="modal">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>

        <div class="modal-body">
          <div class="form-group">
            <label for="order-date"><small>Nama</small></label>
            <select name="customers" id="update-customers">
              <option value="">Pilih Pelanggan</option>

                <?php foreach ($customers as $customer) : ?>
                  <option value="<?= $customer['customer_id']; ?>" <?= $customer['customer_id'] == $invoice_detail['customer_id'] ? 'selected' : ''; ?>><?= $customer['customer_name']; ?></option>
                <?php endforeach; ?>

            </select>
          </div>
        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
          <button type="submit" class="btn btn-primary" id="update-order-date-btn">Simpan data</button>
        </div>
      </div>

    </form>
    
  </div>
</div>