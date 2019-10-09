<!-- Update Date_Payment Modal -->
<div class="modal fade" id="paymentDateModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <form action="<?= base_url('invoice/ajax_update_payment_date') ?>" method="post" id="payment-date-form">
            <input type="hidden" name="order-id" id="order-id" value="<?= $invoice_detail['order_id']; ?>">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Jatuh Tempo Pembayaran</h5>
                    <button type="button" class="close" data-dismiss="modal">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="payment-date"><small>Tanggal</small></label>
                        <input type="date" name="payment-date" id="payment-date" class="form-control" value="<?= date('Y-m-d', strtotime($invoice_detail['payment_date'])); ?>">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                    <button type="submit" class="btn btn-primary" id="update-payment-date-btn">Simpan data</button>
                </div>
            </div>
        </form>
    </div>
</div>