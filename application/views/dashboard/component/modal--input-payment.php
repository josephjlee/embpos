<!-- Input Payment Modal -->
<div class="modal fade" id="paymentModal" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <form action="<?= base_url('invoice/simpan_pembayaran')?>" method="post" id="payment-form">
      <input type="hidden" name="order-id" id="order-id" value="<?= $invoice_detail['order_id']; ?>">
      <input type="hidden" name="order-number" id="order-number" value="<?= $invoice_detail['invoice_number']; ?>">
      <input type="hidden" name="customer-id" value="<?= $invoice_detail['customer_id']; ?>">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Rekam Pembayaran</h5>
          <button type="button" class="close" data-dismiss="modal">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="form-group">
            <label for="payment-amount"><small>Nominal</small></label>
            <input type="text" name="payment-amount" id="payment-amount" class="form-control" value="0">
          </div>
          <div class="form-group">
            <label for="payment-method"><small>Metode Pembayaran</small></label>
            <select name="payment-method" id="payment-method" class="custom-select">
              <option value="">Pilih...</option>

              <?php

              $this->db->select('payment_method_id, name');
              $this->db->from('payment_method');
              $payment_method_query = $this->db->get();

              $payment_methods = $payment_method_query->result_array();

              ?>

              <?php foreach ($payment_methods as $payment_method) : ?>
                <option value="<?= $payment_method['payment_method_id']; ?>"><?= $payment_method['name']; ?></option>
              <?php endforeach; ?>

            </select>
          </div>
          <div class="form-group">
            <label for="payment-date"><small>Tanggal</small></label>
            <input type="date" name="payment-date" id="payment-date" class="form-control">
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
          <button type="submit" class="btn btn-primary" id="save-payment-btn">Simpan data</button>
        </div>
      </div>
    </form>
  </div>
</div>